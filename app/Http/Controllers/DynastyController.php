<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Family;
use App\Models\Dynasty;
use App\Models\Gallery;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;


class DynastyController extends Controller
{
    use ImageUpload;

    public function create(Request $request)
    {
        $userId = Auth::id();

       $validated = $request->validate([
            'name' => 'required|unique:dynasties,name',
            'location' => 'nullable|string',
            'notable_individual' => 'nullable|string',
            'about' => 'required|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'reference' => 'nullable|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:2048',
            'reference_link'=>'nullable|url'
        ]);

        $dynasty = Dynasty::create([
            'name' => $validated['name'],
            'location' => $validated['location'],
            'notable_individual' => $validated['notable_individual'],
            'about' => $validated['about'],
            'reference_link'=> $validated['reference_link'],
            'created_by' => $userId,
        ]);

        if ($request->hasFile('reference')) {
            $dynastyReference = $request->file('reference');
            if ($dynastyReference !== null) {

                // Store the new Reference
                $dynastyReferencePath = $this->uploadImage($request->reference);

                // Update family's Reference column
                $dynasty->reference = $dynastyReferencePath;
                $dynasty->save();

            } else {
                // Handle the case where the Reference is null
                return response()->json(['error' => 'Reference is required'], 400);
            }
        } else {
            // Handle the case where no Reference was uploaded
            return response()->json(['error' => 'No Reference uploaded'], 400);
        }

        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            if ($profilePicture !== null) {

                // Store the new profile picture
                $profilePicturePath = $this->uploadImage($request->profile_picture);

                // Update family's profile_picture column
                $dynasty->profile_picture = $profilePicturePath;
                $dynasty->save();

                // Save the new profile picture into galleries table
                // $gallery = new Gallery();
                // $gallery->image = $profilePicturePath;
                // $gallery->dynasty_id = $dynasty->id;
                // $gallery->save();
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
                 $dynasty->cover_picture = $coverPicturePath;
                 $dynasty->save();

                 // Save cover picture into galleries table
                // $gallery = new Gallery();
                // $gallery->image = $coverPicturePath;
                // $gallery->dynasty_id = $dynasty->id;
                // $gallery->save();
            } else {
                // Handle the case where the cover picture is null
                return response()->json(['error' => 'Cover picture is required'], 400);
            }
        } else {
            // Handle the case where no cover picture was uploaded
            return response()->json(['error' => 'No Cover picture uploaded'], 400);
        }


        // Add the user to the dynasty
        $user = auth()->user(); // Assuming the authenticated user is creating the dynasty
        $dynasty->users()->attach($user, ['created_at' => now()]);

        // Generate the dynasty profile link
        $link = route('dynasty.show', $dynasty->id); // Assuming you have a route named 'dynasty.show' for the dynasty profile

        return response()->json([
            'message' => 'Conversation Successfully Created',
            'dynasty' => $dynasty,
            'link' => $link,
        ], 200);
    }

    public function edit(Request $request, $dynastyId)
    {
        $dynasty = Dynasty::findOrFail($dynastyId);
        $user = Auth::user(); // Get the authenticated user

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string',
            'about' => 'sometimes|required|string',
            'location' => 'sometimes|required|string',
            'notable_individual' => 'sometimes|required|string',
            'profile_picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'reference_link' => 'sometimes|required|url',
        ]);

        $dynasty->update($validatedData);

        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $this->uploadImage($request->file('profile_picture'));
            $dynasty->profile_picture = $profilePicturePath;
        }

        if ($request->hasFile('cover_picture')) {
            $coverPicturePath = $this->uploadImage($request->file('cover_picture'));
            $dynasty->cover_picture = $coverPicturePath;
        }
        $dynasty->save();

        // Fetch the updated family model
        $updatedDynasty = Dynasty::findOrFail($dynastyId);

        // dd($family);

        $response = [
            'message' => 'Conversation updated successfully',
            'dynasty' => $updatedDynasty,
        ];

        return response()->json($response, 200);
    }

    public function addDynastyProfilePicture(Request $request, $dynastyId)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the dynasty
        $dynasty = Dynasty::findOrFail($dynastyId);

        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            if ($profilePicture !== null) {


                // Store the new profile picture
                $profilePicturePath = $this->uploadImage($request->profile_picture);

                // Update dynasty$dynasty's profile_picture column
                $dynasty->profile_picture = $profilePicturePath;
                $dynasty->save();

                // Save the new profile picture into galleries table
                $gallery = new Gallery();
                $gallery->image = $profilePicturePath;
                $gallery->dynasty_id = $dynasty->id;
                $gallery->save();
            } else {
                // Handle the case where the profile picture is null
                return response()->json(['error' => 'Profile picture is required'], 400);
            }
        } else {
            // Handle the case where no profile picture was uploaded
            return response()->json(['error' => 'No profile picture uploaded'], 400);
        }

        return response()->json(['message' => 'Profile picture updated successfully']);
    }


    public function addDynastyCoverPicture(Request $request, $dynastyId)
    {
        $request->validate([
            'cover_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

          // Find the dynasty
          $dynasty = Dynasty::findOrFail($dynastyId);

        if ($request->hasFile('cover_picture')) {
            $coverPicture = $request->file('cover_picture');
            if ($coverPicture !== null) {

                // Rest of the code to store the cover picture
                $coverPicturePath = $this->uploadImage($request->cover_picture);

                 // Update user's profile_picture column
                 $dynasty->cover_picture = $coverPicturePath;
                 $dynasty->save();

                 // Save cover picture into galleries table
                $gallery = new Gallery();
                $gallery->image = $coverPicturePath;
                $gallery->dynasty_id = $dynasty->id;
                $gallery->save();
            } else {
                // Handle the case where the cover picture is null
                return response()->json(['error' => 'Cover picture is required'], 400);
            }
        } else {
            // Handle the case where no cover picture was uploaded
            return response()->json(['error' => 'No Cover picture uploaded'], 400);
        }

        return response()->json(['message' => 'Cover picture updated successfully']);
    }


    public function destroy(Request $request, $dynastyId)
    {
        $dynasty = Dynasty::findOrFail($dynastyId);

         // Check if the authenticated user created the dynasty
        if ($dynasty->created_by !== auth()->user()->id) {
             return response()->json(['error' => 'You are not authorized to delete this conversation'], 403);
        }

        $dynasty->delete();

        return response()->json(['message' => 'Conversation deleted successfully']);
    }

    public function getAllDynasties()
    {
        $dynasties = Dynasty::with('user','users')->where('status','accepted')->latest()->get();

        // dd($dynasties);

        $response = $dynasties->map(function ($dynasty) {
            return [
               'dynasties' => $dynasty,
            ];
        });

        return response()->json($response, 200);
    }


    public function getDynasty($dynastyId)
    {
        $dynasty = Dynasty::with('user', 'users')->where('status','accepted')->find($dynastyId);

        if ($dynasty === null) {
            // dynasty not found, return a JSON response with an error message
            return response()->json(['message' => 'conversation not found'], 404);
        }

        // Return a JSON response with the dynasty and its members
        $response = [
            'dynasty' => $dynasty,
        ];

        return response()->json($response, 200);
    }

    public function createdDynasties()
    {
        $user = Auth::user();

        $getDynasty = Dynasty::with(['user', 'users'])
        ->where('status', 'accepted')
            ->where('created_by', $user->id)
            ->get();

        if($getDynasty->isEmpty()){
            return response()->json(['message' => 'You have not created any conversation']);
        }else{
            $response = $getDynasty->map(function ($dynasty) {
                return [
                    'created_by' => $dynasty->user->name,
                    'data' => $dynasty
                ];
            });
            return response()->json($response, 200);
        }
    }

    public function dynastiesMember($dynastyId)
    {
        // Access users belonging to a dynasty
        $dynasty = Dynasty::find($dynastyId);

        if ($dynasty === null) {
            // Family does not exist
            return response()->json(['message' => 'Conversation not found'], 404);
        }

        $users = $dynasty->users;

        if($users->isEmpty()){
            return response()->json(['message' => 'This Conversation has no member yet']);
        }

        $response = [
            'name' => $dynasty->name,
            'members' => $dynasty->users->map(function ($member) {
                return [
                   'members' => $member,
                ];
            }),
        ];

        return response()->json($response, 200);
    }

    public function joinDynasty(Request $request, $dynastyId)
    {
        try {
            $user = Auth::user();

            $dynasty = Dynasty::findOrFail($dynastyId);

            $dynastyMember = $dynasty->users()->where('users.id', $user->id)
            ->where('dynasty_id', $dynastyId)
            ->first();

            if ($dynastyMember) {

                $dynasty->users()->detach($user->id); // Remove the association

                $response = [
                    'status' => 'You have successfully left the ' .$dynasty->name,
                ];
            }else{
                // Attach the user to the dynasty
                $dynasty->users()->attach($user->id, ['created_at' => now()]);

                $dynastyName = Dynasty::findOrFail($dynastyId);

                $notification = new Notification();
                        $notification->user_id = $dynasty->created_by;
                        $notification->sender_id=$user->id;
                        $notification->dynasty_id = $dynasty->id;
                        $notification->notification_type = "dynasty_request";
                        $notification->save();

                $response = [
                    'Message' => 'You have successfully joined the ' .$dynastyName->name,
                ];
            }

            return response()->json($response, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            // Dynasty does not exist, return a response
            $response = [
                'status' => 'Error',
                'message' => 'This conversation does not exist.',
            ];

            return response()->json($response, 404);
        }

    }

    public function leaveDynasty(Request $request, $dynastyId)
    {
        $user = Auth::user();
        $dynasty = Dynasty::findOrFail($dynastyId);

        // Check if the user is a member of the dynasty
        if (!$dynasty->users()->where('users.id', $user->id)->exists()) {
            return response()->json(['error' => 'You are not a member of this conversation'], 400);
        }

        // Detach the user from the dynasty
        $dynasty->users()->detach($user->id);

        $response = [
            'Message' => 'You have Successfully Left The ' .$dynasty->name
        ];

        return response()->json($response, 200);

    }

    public function dynastiesJoined()
    {
        // Access dynasties associated with a user
       $user = Auth::user();
        // dd($user);

        // Check if the user exists
        if ($user === null) {
            // User not found, return a JSON response with an error message
            return response()->json(['message' => 'User not found'], 404);
        }

        $dynasties = $user->dynasties()->with('users')->get(); // get all the dynasties user belongs to


        if($dynasties->isEmpty() ){
            return response()->json(['message' => 'You have not joined any Conversation']);
        }else{

            $response = $dynasties->map(function ($dynasty) {
                // Get all members of the family
                $members = $dynasty->users;

                return [
                    'Dynasty' => $dynasty,
                    // 'members' => $members,
                ];
            });

            return response()->json($response, 200);
        }


    }

    public function suggestedDynasty(Request $request)
    {
        $user = Auth::user();


        // Get the IDs of the user's dynasty
        $dynastyIds = $user->dynasties->pluck('id')->toArray();



         // Fetch all dynasties if the user has no current dynasty users
    if (empty($dynastyIds)) {
        $suggestedDynasty = Dynasty::with('user', 'users')->inRandomOrder()
            ->where('status', 'accepted')
            ->take(10) // Adjust the number of suggested dynasties as needed
            ->get();
    } else {
        // Fetch suggested dynasties the user is not a member of
        $suggestedDynasty = Dynasty::with('users')
        ->whereDoesntHave('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('created_by', '!=', $user->id)
            ->where('status', 'accepted')
            ->inRandomOrder()
            ->take(10) // Adjust the number of suggested dynasties as needed
            ->get();
    }





        $response = [
            'suggested_dynasty' => $suggestedDynasty,
            'message' => 'Suggested Conversation list',
        ];

        return response()->json($response, 200);
    }

    public function searchDynasty(Request $request)
    {
        $query = $request->query('name', '');

        $dynasty = Dynasty::where('name', 'like', '%' . $query . '%')
                            ->paginate(10)
                            ->withQueryString();

        $response = [
            'data' => $dynasty,
        ];
        return response()->json($response, 200);
    }
}
