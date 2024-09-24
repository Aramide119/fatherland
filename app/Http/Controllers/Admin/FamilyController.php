<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\User;
use App\Models\Admin;
use App\Models\Family;
use App\Traits\ImageUpload;
use App\Traits\VideoUpload;
use Illuminate\Support\Str;
// use App\Http\Controllers\Traits\MediaUploadingTrait;
// use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyFamiliesRequest;



class FamilyController extends Controller
{
    use ImageUpload, VideoUpload;

    public function index()
    {
        $families = Family::with(['user','media', 'createdBy'])->latest()->paginate(100)->withQueryString();
        // dd($families);

        return view('admin.families.index', compact('families'));
    }

    public function familyMembers($familyId)
    {
        $admin = Auth::user();
        $family = Family::with('createdBy')->findOrFail($familyId);

        $users = DB::table('user_families')
                ->where('family_id', $familyId)
                ->whereNotNull('user_id')  // Ensure this is a user
                ->join('users', 'user_families.user_id', '=', 'users.id')
                ->select('users.*', 'user_families.member_type')
                ->get();

        // Fetch admins
        $admins = DB::table('user_families')
                ->where('family_id', $familyId)
                ->whereNotNull('admin_id')  // Ensure this is an admin
                ->join('admins', 'user_families.admin_id', '=', 'admins.id')
                ->select('admins.*', 'user_families.member_type')
                ->get();

        $members = $users->merge($admins);

        return view('admin.families.members', compact('family', 'members', 'admin'));


    }
    public function showMember($id)
    {
        $user = User::findOrFail($id);

        return view('admin.families.showMember', compact('user'));
    }
    public function getPendingJoinRequests($familyId)
    {
        // Get the authenticated admin user
        $admin = Auth::user();
        $adminId = $admin->id;



        // Fetch all families created by this admin
        $family = Family::where('id', $familyId)
                        ->where('created_by_id', $admin->id)
                        ->where('created_by_type', get_class($admin))
                        ->firstOrFail();


        // Fetch all pending join requests for these families
        $pendingRequests = DB::table('family_requests')
                         ->where('family_id', $familyId)
                         ->where('status', 'pending')
                         ->join('users', 'family_requests.user_id', '=', 'users.id')
                         ->select('family_requests.*', 'users.name as user_name')
                         ->get();

        return view('admin.families.request', compact('pendingRequests', 'family'));
    }

    public function acceptPendingRequests($familyId, $userId)
    {
        try {
            $admin = Auth::user();
            $member = User::findOrFail($userId);
            $family = Family::findOrFail($familyId);

            // Check if the authenticated user is the creator of the family
            if ($family->created_by_id != $admin->id || $family->created_by_type != get_class($admin)) {
                return redirect()->back()->with('error', 'Unauthorized');
            }

            // if (!$isCreator) {
            //     return back()->with('error', 'Unauthorized');
            // }

            // Check if the user exists in the family requests
            $userRequest = DB::table('family_requests')
                ->where('user_id', $userId)
                ->where('family_id', $familyId)
                ->where('status', 'pending')
                ->first();

            if (!$userRequest) {
                return back()->with('error', 'User Request Not Found in Group Requests');
            }

            // Delete the user from family_requests
            DB::table('family_requests')
                ->where('user_id', $userId)
                ->where('family_id', $familyId)
                ->delete();

            // Insert the user into user_families with accepted status
            DB::table('user_families')->insert([
                'user_id' => $userId,
                'family_id' => $familyId,
                'member_type' => 'member', // Change this based on your logic
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Check if there is an existing request notification
            $requestNotification = Notification::where('user_id', $userId)
                ->where('family_id', $familyId)
                ->where('notification_type', 'family_request')
                ->first();

            // Create a new accepted notification
            Notification::create([
                'user_id' => $userId,
                'family_id' => $familyId,
                'sender_id' => $admin->id,
                'notification_type' => 'accept_request',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('message', 'Request accepted');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return back()->with('error', 'User or Family Not Found');
        }

    }

    public function declinePendingRequests($familyId, $userId)
    {
        try {
            $admin = Auth::user();
            $family = Family::findOrFail($familyId);

            // Check if the authenticated user is the creator of the family
            if ($family->created_by_id != $admin->id || $family->created_by_type != get_class($admin)) {
                return redirect()->back()->with('error', 'Unauthorized');
            }

            // Check if the user exists in the family requests
            $userRequest = DB::table('family_requests')
                ->where('user_id', $userId)
                ->where('family_id', $familyId)
                ->where('status', 'pending')
                ->first();

            if (!$userRequest) {
                return back()->with('error', 'User Request Not Found in Group Requests');
            }

            // Delete the user from family_requests
            DB::table('family_requests')
                ->where('user_id', $userId)
                ->where('family_id', $familyId)
                ->delete();

            // Create a new declined notification
            Notification::create([
                'user_id' => $userId,
                'family_id' => $familyId,
                'sender_id' => $admin->id,
                'notification_type' => 'decline_request',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('message', 'Request Declined');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return back()->with('error', 'User or Family Not Found');
        }

    }

    public function toggleAdminRole(Request $request, $familyId, $userId)
    {
        try {
            $admin = Auth::user();
            $family = Family::findOrFail($familyId);

            // Find the family member relationship
            $userFamily = DB::table('user_families')
                ->where('family_id', $familyId)
                ->where('user_id', $userId)
                ->first();

            if (!$userFamily) {
                return back()->with('error', 'User Not Found in Family');
            }

            // Toggle the member type
            $newMemberType = $userFamily->member_type == 'admin' ? 'member' : 'admin';

            // Update the member type in the pivot table
            DB::table('user_families')
                ->where('family_id', $familyId)
                ->where('user_id', $userId)
                ->update(['member_type' => $newMemberType]);

            return back()->with('message', 'Member role updated');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return back()->with('error', 'Family or User Not Found');
        }
    }


    public function create()
    {
        return view('admin.families.create');
    }

    public function store(Request $request)
    {
        $authId = Auth::id(); // Get the authenticated user

        $validatedData = $request->validate([
            'name'=>'required',
            'location' => 'required',
            'current_location'=>'required',
            'notable_individual' => 'required',
            'about'=>'required',
            'reference' => 'required',
            'reference_link'=>'required|url',
            'profile_picture' => 'required|image',
            'cover_picture'=>'required|image'
        ]); 

        $familyName = $validatedData['name'];


        // Remove "Family" from the family name if it exists
         $familyName = str_replace(['\'s', 'Group'], ['', ''], $familyName);

             // Generate a unique token for the invite link
            $token = Str::uuid()->toString();

            $admin = Admin::find($authId);

         $families = new Family();
            $families->name = $familyName;
            $families->location = $validatedData['location'];
            $families->current_location = $validatedData['current_location'];
            $families->notable_individual = $validatedData['notable_individual'];
            $families->about = $validatedData['about'];
            $families->reference_link = $validatedData['reference_link'];
            $families->createdBy()->associate($admin);
            $families->invite_token = $token;
            $families->save();

            if ($request->hasFile('reference')) {
                $familyReference = $request->file('reference');
                if ($familyReference !== null) {

                    // Store the new Reference
                    $familyReferencePath = $this->uploadImage($request->reference);

                    // Update family's Reference column
                    $families->reference = $familyReferencePath;
                    $families->save();

                }
            }


            if ($request->hasFile('profile_picture')) {
                $profilePicture = $request->file('profile_picture');
                if ($profilePicture !== null) {

                    // Store the new profile picture
                    $profilePicturePath = $this->uploadImage($request->profile_picture);

                    // Update family's profile_picture column
                    $families->profile_picture = $profilePicturePath;
                    $families->save();
                }
            } else {
                // Handle the case where no profile picture was uploaded
                return response()->json(['error' => 'No profile picture uploaded'], 400);
            }

            if ($request->hasFile('cover_picture')) {
                $coverPicture = $request->file('cover_picture');
                if ($coverPicture !== null) {

                    // Rest of the code to store the cover picture
                    $coverPicturePath = $this->uploadImage($request->cover_picture);

                     // Update user's profile_picture column
                     $families->cover_picture = $coverPicturePath;
                     $families->save();

                }
            } else {
                // Handle the case where no cover picture was uploaded
                return response()->json(['error' => 'No Cover picture uploaded'], 400);
            }


            $link = route('family.show', ['uuid' => $families->link]);

            $user = Admin::find($authId);
            // dd($user);

            $user->families()->attach($families->id, [
                'created_at' => now(),
                'member_type' => $user->id === $families->createdBy->id ? 'admin' : null, // Set status based on user match
            ]);
            // $families->account_type = $validatedData['account_type'];

            return redirect()->route('admin.families.index')->with('success', 'Group created successfully.');

    }

    public function deleteFamilyMember($userId, $familyId)
    {

        // Find the family by its ID
        $family = Family::findOrFail($familyId);

        $user = User::find($userId);

        // if ($userId == $family->created_by_id && $family->created_by_type != get_class($user)) {
        //     return back()
        // ->with('message', 'You cannot remove this member');
        // }

        // Detach the user from the family
        $family->users()->detach($userId);

        return back()
        ->with('message', 'member removed successfully.');
    }

    public function show(Family $showFamily)
    {

        return view('admin.families.show', compact('showFamily'));
    }

    public function edit(Family $editFamily)
    {
        return view('admin.families.edit', compact('editFamily'));
    }


    public function update(Request $request, $id)
    {
        $editFamily = Family::findOrFail($id);
         $input = $request->validate([
        'name' => 'nullable|string|max:255',
        'location' => 'nullable|string|max:255',
        'current_location' => 'nullable|string|max:255',
        'notable_individual' => 'nullable|string',
        'about' => 'nullable|string',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'cover_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'reference' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'reference_link' => 'nullable|url',
        'status' => 'nullable|in:pending,accepted,decline',
    ]);

        $editFamily->update($input);

        if ($request->hasFile('reference')) {
            $familyReference = $request->file('reference');
            if ($familyReference !== null) {

                // Store the new Reference
                $familyReferencePath = $this->uploadImage($request->reference);

                // Update family's Reference column
                $editFamily->reference = $familyReferencePath;
                $editFamily->save();

            }
        }


        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            if ($profilePicture !== null) {

                // Store the new profile picture
                $profilePicturePath = $this->uploadImage($request->profile_picture);

                // Update family's profile_picture column
                $editFamily->profile_picture = $profilePicturePath;
                $editFamily->save();
            } else {
                // Handle the case where the profile picture is null
                return response()->json(['error' => 'Profile picture is required'], 400);
            }
        }

        if ($request->hasFile('cover_picture')) {
            $coverPicture = $request->file('cover_picture');
            if ($coverPicture !== null) {

                // Rest of the code to store the cover picture
                $coverPicturePath = $this->uploadImage($request->cover_picture);

                 // Update user's profile_picture column
                 $editFamily->cover_picture = $coverPicturePath;
                 $editFamily->save();

            }
        }
        return redirect()->route('admin.families.index')
            ->with('success', 'Status updated successfully.');

    }

    public function destroy($id)
    {
        $deleteFamily = Family::findOrFail($id);

        $deleteFamily->destroy($id);

        return redirect()->route('admin.families.index')
            ->with('success', 'Family Deleted successfully.');
    }

    public function massDestroy(MassDestroyFamiliesRequest $request)
    {
        $allFamilies = Family::find(request('ids'));

        foreach ($allFamilies as $families) {
            $families->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('families_create') && Gate::denies('families_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Family();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
