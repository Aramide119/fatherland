<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Family;
use App\Models\Gallery;
use App\Traits\ImageUpload;
use App\Traits\VideoUpload;
use Illuminate\Support\Str;
use App\Models\FamilyMember;
use App\Models\Notification;
use App\Models\ReportFamily;
use Illuminate\Http\Request;
use App\Models\BlockedFamily;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
// use App\Http\Controllers\Traits\MediaUploadingTrait;




class FamilyController extends Controller
{
    use ImageUpload, VideoUpload;

    public function store(Request $request)
    {

        $userId = Auth::id(); // Get the authenticated user

        $validatedData = $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
            'current_location' => 'required|string',
            'notable_individual' => 'required|string',
            'about' => 'required|string',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'reference' =>'nullable|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:2048',
            'reference_link'=>'nullable|url'
        ]);

        $familyName = $validatedData['name'];


        // Remove "Family" from the family name if it exists
        $familyName = str_replace(['\'s', 'Community'], ['', ''], $familyName);

             // Generate a unique token for the invite link
            $token = Str::uuid()->toString();

            $user = User::find($userId);

        // Create the family
        $family = new Family();
        $family->name = $familyName;
        $family->location = $validatedData['location'];
        $family->current_location = $validatedData['current_location'];
        $family->notable_individual = $validatedData['notable_individual'];
        $family->about = $validatedData['about'];
        $family->reference_link = $validatedData['reference_link'];
        $family->createdBy()->associate($user);
        $family->invite_token = $token;
        $family->save();


        if ($request->hasFile('reference')) {
            $familyReference = $request->file('reference');
            if ($familyReference !== null) {

                // Store the new Reference
                $familyReferencePath = $this->uploadImage($request->reference);

                // Update family's Reference column
                $family->reference = $familyReferencePath;
                $family->save();

            }
        }


        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            if ($profilePicture !== null) {

                // Store the new profile picture
                $profilePicturePath = $this->uploadImage($request->profile_picture);

                // Update family's profile_picture column
                $family->profile_picture = $profilePicturePath;
                $family->save();
            } else {
                // Handle the case where the profile picture is null
                return response()->json(['error' => 'Profile picture is required'], 400);
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
                 $family->cover_picture = $coverPicturePath;
                 $family->save();

            } else {
                // Handle the case where the cover picture is null
                return response()->json(['error' => 'Cover picture is required'], 400);
            }
        } else {
            // Handle the case where no cover picture was uploaded
            return response()->json(['error' => 'No Cover picture uploaded'], 400);
        }

          // Generate the link
        $link = route('family.show', ['uuid' => $family->link]);

        $user = User::find($userId);

        $user->families()->attach($family->id, [
            'created_at' => now(),
            'member_type' => $user->id === $family->createdBy->id ? 'admin' : null, // Set status based on user match
        ]);

        $family->load('media');
        $response = [
            'message' => 'community Created Successfully',
            'family' => $family
        ];

        return response()->json($response, 200);
    }


    public function familyRequest(Request $request, $familyId)
    {
        $user = Auth::user(); // Get the authenticated user object
        $userId = $user->id; // Extract the user ID

        $user = User::find($userId); // Retrieve the user instance

        try {
            $familyDetails = Family::findOrFail($familyId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return response()->json(['message' => 'This Community Does Not Exist'], 400);
        }

        // Check if the user is the creator of the family
        if ($familyDetails->createdBy && $familyDetails->createdBy->id == $userId && get_class($familyDetails->createdBy) == User::class) {
            $response = [
                'status' => 'You cannot leave this Community',
            ];
        } else {
            // Check if the user is already a member of the family
            $isFamilyMember = $user->families()->where('family_id', $familyId)->exists();

            if ($isFamilyMember) {
                // User is already a member, proceed to leave the family
                $user->familyRequests()->detach($familyId); // Remove the association

                // User is already a member, provide a specific response
                $response = [
                    'status' => "You are already a member of " . $familyDetails->name . "'s Community",
                ];
            } else {
                // Check if the user has a pending request to join the family
                $pendingRequest = $user->familyRequests()->where('family_id', $familyId)->first();

                if ($pendingRequest) {
                    // User has a pending request, cancel the request
                    $user->familyRequests()->detach($familyId);

                    $response = [
                        'status' => "Your pending request to join " . $familyDetails->name . "'s Community has been cancelled",
                    ];
                } else {
                    // User is not a member and has no pending request, proceed to join the family
                    $user->familyRequests()->attach($familyId, [
                        'created_at' => now(),
                        'status' => 'pending',
                    ]);

                    DB::table('notifications')->insert([
                        'user_id' => $userId, // Correct user ID
                        'sender_id' => $userId, // Assuming the sender is the same user
                        'family_id' => $familyId,
                        'notification_type' => 'family_request',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $response = [
                        'user' => $user,
                        'status' => 'Your Request To Join ' . $familyDetails->name . ' Community Has Been Sent To The Community Admin',
                    ];
                }
            }
        }

        return response()->json($response, 200);
    }

    public function leaveFamily(Request $request, $familyId)
    {
        $user = Auth::user(); // Get the authenticated user

        try {
            $family = Family::findOrFail($familyId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return response()->json(['message' => 'This Community Does Not Exist'], 400);
        }

        // Find the family member record
        $familyMember = $user->families()->where('family_id', $familyId)
            ->first();

        // Check if the user is the creator of the family
        if ($family->created_by_id == $user->id) {
            return response()->json(['message' => 'You cannot leave this Community'], 400);
        }

        // Check if the user is a member of the family
        if (!$family->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'User is not a member of the Community'], 400);
        }

        $user = User::find($user->id); // Retrieve the user instance

        $user->families()->detach($familyId); // Remove the association

        $family = Family::findOrFail($familyId); // Retrieve the Family object

        $response = [
            'status' => 'You have successfully left the ' .$family->name. ' Community',
        ];

        return response()->json($response, 200);
    }

    public function allFamilies()
    {
        // $perPage = 2; // Set the number of families per page
        $families = Family::with('createdBy', 'users')
        ->latest()
        ->get();

            $response = $families->map(function ($family) {
            return [
                    'families' => $family,
            ];
        });


        return response()->json($response, 200);
    }


    public function getFamily($familyId)
    {
        $family = Family::with('post','createdBy', 'users', 'familyRequests')->find($familyId);

        if ($family === null) {
            // Family not found, return a JSON response with an error message
            return response()->json(['message' => 'Community not found'], 404);
        }

        // Return a JSON response with the family and its members
        $response = [
            'family' => $family,
        ];

        return response()->json($response, 200);
    }


    public function createdFamilies()
    {
        $user = Auth::user();

        $getFamily = Family::with('createdBy', 'users')
        ->where('created_by_id', $user->id)
        ->get();

        return response()->json([ 'data' => $getFamily ], 200);
    }


    public function pendingRequests()
    {
        $user = Auth::user();
        $userId = $user->id;

        // $getRequest = Family::with('users')
        // ->where('created_by', $user->id)
        // ->get();

        $getRequest = Family::with('users')
       ->where('created_by_id', $user->id)
        ->where('created_by_type', get_class($user))
        ->get();

        return response()->json([ 'data' => $getRequest ], 200);
    }


    public function getFamilyMembers($familyId)
    {
        $family = Family::with('createdBy', 'users')->find($familyId);

        if ($family === null) {
            return response()->json(['message' => 'Family not found'], 404);
        }

        if ($family->users->isEmpty()) {
            return response()->json(['message' => 'This Community has no accepted members yet']);
        }

        $response = [
            'family' => $family,
            // 'accepted_members' => $family->users,
        ];

        return response()->json($response, 200);
    }


    public function familyJoined()
    {
        $user = Auth::user();

        // Check if the user exists
        if (!$user) {
            // User not found, return a JSON response with an error message
            return response()->json(['message' => 'User not found'], 404);
        }

        // Retrieve the families where the user's status is accepted
        $families = $user->families()->with('users')->get();

        // Check if the user has joined any family
        if ($families->isEmpty()) {
            return response()->json(['message' => 'You have not joined any community yet'], 200);
        }

        // Process and format the response for families the user has joined
        $response = $families->map(function ($family) {
            // Access the users relationship directly
            $family['members'] = $family->users->map(function ($user) {
                // Exclude the "pivot" key from each user in the response
                unset($user->pivot);
                return $user;
            });
            unset($family['users']); // Optionally unset if you don't want the 'users' key

            return $family;
        });

        return response()->json($response, 200);
    }



    public function editFamily(Request $request, $familyId)
    {
        $family = Family::findOrFail($familyId);
        $user = Auth::user(); // Get the authenticated user

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string',
            'about' => 'sometimes|required|string',
            'location' => 'sometimes|required|string',
            'current_location' => 'sometimes|required|string',
            'notable_individual' => 'sometimes|required|string',
            'profile_picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $family->update($validatedData);

        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $this->uploadImage($request->file('profile_picture'));
            $family->profile_picture = $profilePicturePath;
        }

        if ($request->hasFile('cover_picture')) {
            $coverPicturePath = $this->uploadImage($request->file('cover_picture'));
            $family->cover_picture = $coverPicturePath;
        }

        $family->save();

        // Fetch the updated family model
        $updatedFamily = Family::findOrFail($familyId);

        $response = [
            'message' => 'Community updated successfully',
            'family' => $updatedFamily, // Use the updated family model
        ];

        return response()->json($response, 200);
    }


    public function addFamilyProfilePicture(Request $request, $familyId)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the family
        $family = Family::findOrFail($familyId);

        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            if ($profilePicture !== null) {


                // Store the new profile picture
                $profilePicturePath = $this->uploadImage($request->profile_picture);

                // Update family's profile_picture column
                $family->profile_picture = $profilePicturePath;
                $family->save();

                // Save the new profile picture into galleries table
                $gallery = new Gallery();
                $gallery->image = $profilePicturePath;
                $gallery->family_id = $family->id;
                $gallery->save();
            } else {
                // Handle the case where the profile picture is null
                return response()->json(['error' => 'Profile picture is required'], 400);
            }
        } else {
            // Handle the case where no profile picture was uploaded
            return response()->json(['error' => 'No profile picture uploaded'], 400);
        }

        return response()->json(['message' => 'Profile picture added successfully']);
    }

    public function addFamilyCoverPicture(Request $request, $familyId)
    {
        $request->validate([
            'cover_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the family
        $family = Family::findOrFail($familyId);

        if ($request->hasFile('cover_picture')) {
            $coverPicture = $request->file('cover_picture');
            if ($coverPicture !== null) {

                // Rest of the code to store the cover picture
                $coverPicturePath = $this->uploadImage($request->cover_picture);

                 // Update user's profile_picture column
                 $family->cover_picture = $coverPicturePath;
                 $family->save();

                 // Save cover picture into galleries table
                $gallery = new Gallery();
                $gallery->image = $coverPicturePath;
                $gallery->family_id = $family->id;
                $gallery->save();
            } else {
                // Handle the case where the cover picture is null
                return response()->json(['error' => 'Cover picture is required'], 400);
            }
        } else {
            // Handle the case where no cover picture was uploaded
            return response()->json(['error' => 'No Cover picture uploaded'], 400);
        }

        return response()->json(['message' => 'Cover picture added successfully']);
    }

    public function destroy(Request $request, $familyId)
    {
        $family = Family::findOrFail($familyId);

         // Check if the authenticated user created the dynasty
        if ($family->created_by_id !== auth()->user()->id) {
             return response()->json(['error' => 'You are not authorized to delete this Community'], 403);
        }

        $family->delete();

        return response()->json(['message' => 'Community deleted successfully']);
    }

    public function suggestedFamily(Request $request)
    {
        $user = Auth::user();
        // Get the Ids Of the Blocked families
        $blockedFamilyIds = $user->blockedFamilies->pluck('id')->toArray();

        // Get the IDs of the user's family
        $familyIds = $user->families->pluck('id')->toArray();

         // Fetch all families if the user has no current family members
         if (empty($familyIds) && empty($blockedFamilyIds)) {
            $suggestedFamily = Family::with('createdBy', 'users', 'familyRequests')->whereDoesntHave('familyRequests', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->inRandomOrder()
                ->where('status', 'accepted')
                ->take(10) // Adjust the number of suggested families as needed
                ->get();
        } else {
            //Fetch suggested families the user is not a member of
            $suggestedFamily = Family::with('createdBy', 'users', 'familyRequests')
                ->whereDoesntHave('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereDoesntHave('familyRequests', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->where('created_by_id', '!=', $user->id)
                ->where('status', 'accepted')
                ->whereNotIn('id', $blockedFamilyIds) // Exclude blocked families
                ->inRandomOrder()
                ->take(10) // Adjust the number of suggested families as needed
                ->get();

        }

        $response = [
            'suggested_family' => $suggestedFamily,
            'message' => 'Suggested family list',
        ];

        return response()->json($response, 200);
    }

    public function getPendingRequests($familyId)
    {
        $user = Auth::user();
        $userId = $user->id;

        // Check if the user is an admin of the specified family
        $family = Family::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('member_type', 'admin');
        })->where('id', $familyId)->first();

        if (!$family) {
            return response()->json(['message' => 'User is not an admin of the specified family or family not found'], 403);
        }

        // Retrieve users with pending requests for the specified family
        $usersWithPendingRequestsInFamily = $family->familyRequests()->where('status', 'pending')->get();

        $usersWithPendingRequests = [];

        // Append the users with pending requests to the main array
        foreach ($usersWithPendingRequestsInFamily as $userWithPendingRequest) {
            $usersWithPendingRequests[] = [
                'user' => $userWithPendingRequest,
                'family' => $family,
            ];
        }

        // Check if there are no pending requests
        if (empty($usersWithPendingRequests)) {
            return response()->json(['message' => 'No pending requests for the specified family'], 200);
        }

        return response()->json($usersWithPendingRequests, 200);
    }




    public function acceptRequest($familyId, $userId)
    {
        try {
            $user = Auth::user();
            $member = User::findOrFail($userId);
            $family = Family::findOrFail($familyId);

            $isCreator = $user->id === $family->created_by_id;

            if (!$isCreator) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

           // Check if the user exists in the family requests
            $userRequest = $member->familyRequests()->where('family_id', $familyId)->first();

            if (!$userRequest) {
                return response()->json(['message' => 'User Request Not Found in Community Requests'], 400);
            }

            // Detach the user from family requests
            $member->familyRequests()->detach($familyId);

            // Attach the user to the family with accepted status
            $family->users()->attach($userId, [
                'created_at' => now(),
                'member_type' => 'member'
            ]);

            // Check if there is an existing request notification
            $requestNotification = Notification::where("user_id", $userId)
                ->where("family_id", $familyId)
                ->first();

            // Create a new accepted notification
            Notification::create([
                "user_id" => $userId,
                "family_id" => $familyId,
                "sender_id" => $family->created_by_id,
                "notification_type" => "accept_request",
            ]);

            return response()->json(['message' => 'Request accepted'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return response()->json(['message' => 'User or Community Not Found'], 404);
        }
    }



    public function declineRequest($familyId, $userId)
    {
        $user = Auth::user();
        $member = User::find($userId);
        $family = Family::find($familyId);

        // Check if the user, member, and family exist
        if (!$family) {
            return response()->json(['message' => 'Community Not Found'], 404);
        }

        // Check if the user is the creator of the family
        if ($user->id !== $family->created_by_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if the user exists before accessing its methods
        if (!$member) {
            return response()->json(['message' => 'User Not Found'], 404);
        }


        // Check if the user exists in the family requests
        $userRequest = $member->familyRequests()->where('family_id', $familyId)->first();

        if (!$userRequest) {
            return response()->json(['message' => 'User Request Not Found in Community Requests'], 400);
        }

        // Detach the user from family requests
        $member->familyRequests()->detach($familyId);

        Notification::create([
            "user_id" => $userId,
            "family_id" => $familyId,
            "sender_id" => $family->created_by_id,
            "notification_type" => "decline_request"
        ]);

        return response()->json(['message' => 'Request declined'], 200);
    }



    public function inviteLink($token)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Get the family associated with the invite link
            $family = Family::where('invite_token', $token)->first();

            if (!$family) {
                // Handle the case when the invite link is invalid or expired
                return response()->json(['error' => 'Invalid invite link.'], 404);
            }

            // Check if the user is already a member of the family
            $isMember = $family->users()->where('user_id', $user->id)->exists();
            // dd($isMember);

            if (!$isMember) {
                // If the user is not a member, add them to the family

                $user->familyRequests()->attach($family->id, [
                    'created_at' => now(),
                    'status' => 'pending',
                ]);

                // Return a success response with the family data
                return response()->json([
                    'message' => 'You have joined the Community.',
                    'family' => $family,
                ], 200);
            }

            // If the user is already a member, return a response with the family data
            return response()->json([
                'message' => 'You are already a member of the Community.',
                'family' => $family,
            ], 200);
        }else{
            // If the user is not authenticated, return a response with an error message
            return response()->json(['error' => 'Authentication required.'], 403);
        }


    }

    public function updateFamilyStatus(Request $request, $familyId)
    {
            $user = Auth::id();
            // Get the user family
            $family = Family::where('created_by_id', $user)
            ->where('id', $familyId)
            ->first();

        if ($family) {
            // Determine the new account type based on the current value
            $newAccountType = $family->account_type === 'private' ? 'public' : 'private';

            // Update the family account status
            Family::where('created_by_id', $user)
                ->where('id', $familyId)
                ->update(['account_type' => $newAccountType]);

            $newFamily = Family::findOrFail($familyId); // Retrieve the Family object

            $response = [
                'data' => $newFamily,
                'message' => 'Community account updated successfully'
            ];

            return response()->json($response, 200);
        } else {
            return response()->json(['error' => 'Community not found'], 404);
        }
    }

    public function deleteFamilyMember($userId, $familyId)
    {
        $user = Auth::user(); // Get the authenticated user

        // Find the family by its ID
        $family = Family::findOrFail($familyId);

        // Check if the authenticated user is the creator of the family
        if ($user->id !== $family->created_by_id) {
            return response()->json([
                'error' => 'You are not authorized to remove users from this Community',
            ], 403);
        }
         // Check if the user to be removed has a "pending" status in the family
            $isUserPending = $family->users()
            ->where('users.id', $userId)
            ->exists();

        if ($isUserPending) {
            return response()->json([
                'error' => 'The user is not a member of this community yet',
            ], 400); // HTTP 400 Bad Request
        }
         // Check if the user to be removed is actually a member of the family
        $isUserMember = $family->users()->where('users.id', $userId)->exists();
        if (!$isUserMember) {
            return response()->json([
                'error' => 'The user is not a member of this community',
            ], 400); // HTTP 400 Bad Request
        }

        // Detach the user from the family
        $family->users()->detach($userId);

        return response()->json([
            'message' => 'User removed from community successfully',
        ], 200);
    }

    public function searchFamily(Request $request)
    {

        $query = $request->query('name', '');

        $family = Family::where('name', 'like', '%' . $query . '%')
                        ->paginate(10)
                        ->withQueryString();

        $response = [
            'data' => $family,
        ];
        return response()->json($response, 200);
    }

    public function blockFamily(Request $request, $id)
    {

        $block_family = Family::findOrFail($id);
        $user = Auth::user();

        $existingBlockedFamily= BlockedFamily::where('family_id', $block_family->id)
        ->where('user_id', $user->id)->first();

        if($existingBlockedFamily) {
            //If the user has already blocked this user , unblock it.
            $existingBlockedFamily->delete();

            return response()->json(['message' => 'you have successfully unblocked '.$block_family->name."'s community"]);

        }
        if($user->id != $id )
        {

        $blockedFamily = BlockedFamily::create([
            'user_id' => $user->id,
            'family_id' =>$block_family->id
        ]);

        $response = [
            'message' => 'You have successfully blocked '.$block_family->name."'s community",
            'data' => $blockedFamily,
        ];
        return response()->json($response, 200 );
        }
        else{
            return response()->json(['message' => 'Methods not allowed', 405]);

        }

    }

    public function reportFamily(Request $request, $family_id)
    {
        $family = Family::findOrFail($family_id);

        $request->validate([
            'message' => 'required|string',
        ]);
        if($family->user_id !== auth()->user()->id )
        {
        $reportFamily = ReportFamily::create([

                'user_id' => auth()->user()->id,
                'family_id' => $family->id,
                'message' => $request->input('message'),
            ]);

            $response = [
                'message' => 'reports created successfully',
                'reportFamily' => $reportFamily,
            ];
            return response()->json($response, 200 );
        }else{
            return response()->json(['message' => 'Methods not allowed', 405]);

        }


    }


    public function getPopularFamilies()
    {
        $popularFamilies = Family::withCount('users')
                                ->orderBy('users_count', 'desc')
                                ->take(3)
                                ->get();

        return response()->json([
            'message' => 'Popular families retrieved successfully',
            'data' => $popularFamilies
        ]);
    }

}
