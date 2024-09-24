<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\User;
use App\Models\Family;
use App\Models\Gallery;
use App\Traits\ImageUpload;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AdminMadeNotification;

class ProfileController extends Controller
{
    use ImageUpload;

    //
    public function index(Request $request)
    {
        $id = Auth::id();

        $user = User::where('id', $id)->first();

        if ($user) {
            // Get all posts the user has made
            $posts = Post::where('user_id', $user->id)
            ->with('likes', 'family', 'comments.user', 'reposts.media', 'reposts.user', 'media')
            ->latest()
            ->get();

            // Get all families the user belongs to
            $families = $user->families()->get();

            // Get all dynasties the user belongs to
            $dynasties = $user->dynasties()->get();

            $response = [
                'status' => true,
                "user" => $user,
                "posts" => $posts,
                "families" => $families,
                "dynasties" => $dynasties
            ];

            return response()->json($response, 200);
        } else {
            // User does not exist
            return response()->json(['message' => 'User not found'], 404);
        }

        // $response =[
        //     'status' => true,
        //     'data' => $data,
        // ];

        // return response()->json($response,200);

    }

    public function updateProfile(Request $request, $userId)
    {
          // Get the authenticated user
        $authenticatedUser = Auth::user();

        // Compare the authenticated user's ID with the requested user's ID
        if ($authenticatedUser->id != $userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::findOrFail($userId);
        // dd($user);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            "phone_number" => "sometimes|required|numeric",
            "date_of_birth" => "sometimes|required|date_format:Y-m-d",
            "account_type" => "sometimes|required",
            'profession' => 'sometimes|required|string',
            'education' => 'sometimes|required|string',
            'location' => 'sometimes|required|string',
            'about' => 'sometimes|required|string',
            'university' => 'sometimes|required|string',
            'professionLocation' => 'sometimes|required|string',
            'profile_pics' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_picture' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');

            // Validate the uploaded profile picture
            $request->validate([
                'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Store the new profile picture
            $profilePicturePath = $this->uploadImage($profilePicture);


            // Update user's profile_picture column
            $user->profile_picture = $profilePicturePath;
            $user->save();



            return response()->json([
                'message'=>'Profile picture updated successfully',
                'user' => $user
            ]);
        }


        if ($request->hasFile('cover_picture')) {
            $coverPicture = $request->file('cover_picture');

            // Validate the uploaded profile picture
            $request->validate([
                'cover_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Store the new profile picture
            $coverPicturePath = $this->uploadImage($coverPicture);
             // Update user's profile_picture column
             $user->cover_picture = $coverPicturePath;
             $user->save();


             return response()->json([
                'message'=>'Cover picture updated successfully',
                'user' => $user
            ]);
        }

        $user->update($validatedData);

        return response()->json([
            'message'=>'user profile updated successfully',
            'user' => $user
        ]);
    }

    public function addProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            if ($profilePicture !== null) {

                // Store the new profile picture
                $profilePicturePath = $this->uploadImage($request->profile_picture);

                // Update user's profile_picture column
                $user->profile_picture = $profilePicturePath;
                $user->save();

            } else {
                // Handle the case where the profile picture is null
                return response()->json(['error' => 'Profile picture is required'], 400);
            }
        } else {
            // Handle the case where no profile picture was uploaded
            return response()->json(['error' => 'No profile picture uploaded'], 400);
        }

        return response()->json(['message'=>'Profile picture updated successfully']);
    }



    public function addCoverPicture(Request $request)
    {
        $request->validate([
            'cover_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('cover_picture')) {
            $coverPicture = $request->file('cover_picture');
            if ($coverPicture !== null) {
                // Rest of the code to store the cover picture
                $coverPicturePath = $this->uploadImage($request->cover_picture);

                 // Update user's profile_picture column
                 $user->cover_picture = $coverPicturePath;
                 $user->save();

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

    public function updateStatus() {

        $user = Auth::user();

        if ($user) {
            $user->toggleAccountType();
            return response()->json(['message' => 'Account type toggled successfully'], 200);
        }

        return response()->json(['message' => 'User not found'], 404);
    }


    public function deleteUser()
    {
        $userId = Auth::id(); // Get the authenticated user ID

        // Step 1: Leave the family
        $user = User::with('families')->find($userId);

        // Step 1: Remove the user from all families
        foreach ($user->families as $family) {
            $this->leaveFamily($family->id);
        }

        // Step 2: Remove the user from all dynasties
        foreach ($user->dynasties as $dynasty){
            $this->leaveDynasties($dynasty->id);
        }

        // Step 3: Delete the user's posts
        foreach ($user->posts as $post) {
            // You may need to perform additional actions before deleting the post
            $post->delete();
        }

        // Step 4: Delete user's notification
        Notification::where('sender_id', $userId)->delete();

        // Step 5: Revoke the user's access token
        Auth::user()->tokens()->where('user_id', $userId)->delete();

        // Step 6: Delete the user account
        Auth::user()->forceDelete();

        // Additional response or redirect as needed
        return response()->json(['message' => 'User account and associated data deleted successfully'], 200);
    }



    private function leaveFamily($familyId)
    {
        $userId = Auth::id(); // Get the authenticated user ID

        // Find the family
        $family = Family::find($familyId);

        // Find all families associated with the user
        $user = User::with('families')->find($userId);

        foreach ($user->families as $family) {
            // Check if the user is the creator of the family
            if ($family->createdBy == $userId) {
                // Check if the user is the only member of the family
                if ($family->users()->count() == 1) {
                    // If the user is the only member, delete the family
                    $family->delete();
                } else {
                    // Find another user from the same family to replace the creator
                    $replacementUser = $family->users()
                        ->where('user_id', '!=', $userId) // Exclude the current user
                        ->inRandomOrder() // Get a random user
                        ->first();
                        // dd($replacementUser);

                    if ($replacementUser) {
                        // dd($replacementUser->id);
                        // Update the family's created_by with the replacement user
                        $family->created_by = $replacementUser->id;
                        $family->save();

                         // Update the member_type in the user_families pivot table
                         $family->users()->updateExistingPivot($replacementUser->id, ['member_type' => 'admin']);

                         // Send notification to the user
                        // $replacementUser->notify(new AdminMadeNotification($family));
                    }
                }
            }

            // Remove the association of the current user from the family
            $user->families()->detach($family->id);
        }

        // Additional response or redirect as needed
        return response()->json(['message' => 'User successfully left all families'], 200);
    }



    private function leaveDynasties()
    {
        $userId = Auth::id(); // Get the authenticated user ID

        // Find all dynasties associated with the user
        $user = User::with('dynasties')->find($userId);

        foreach ($user->dynasties as $dynasty) {
            // Check if the user is the creator of the dynasty
            if ($dynasty->created_by == $userId) {
                // Check if the user is the only member of the dynasty
                if ($dynasty->users()->count() == 1) {
                    // If the user is the only member, delete the dynasty
                    $dynasty->delete();
                } else {
                    // Find another user from the same dynasty to replace the creator
                    $replacementUser = $dynasty->users()
                        ->where('user_id', '!=', $userId) // Exclude the current user
                        ->inRandomOrder() // Get a random user
                        ->first();

                    if ($replacementUser) {
                        // Update the dynasty's created_by with the replacement user
                        $dynasty->created_by = $replacementUser->id;
                        $dynasty->save();

                        // Update the member_type in the user_dynasties pivot table
                        // $dynasty->users()->updateExistingPivot($replacementUser->id, ['member_type' => 'admin']);

                        // Send notification to the user
                        // $replacementUser->notify(new AdminMadeNotification($dynasty));
                    }
                }
            }

            // Remove the association of the current user from the dynasty
            $user->dynasties()->detach($dynasty->id);
        }

        // Additional response or redirect as needed
        return response()->json(['message' => 'User successfully left all dynasties'], 200);
    }

    public function usersRequest()
    {
        $user = Auth::id();

        $userDetails= User::where('id', $user)->first();

        if($userDetails){

        $pendingRequest = $userDetails->familyRequests()->wherePivot('status', 'pending')->get();

        return response()->json(['pending_requests' => $pendingRequest], 200);
        }

        return response()->json(['error' => 'User not found'], 400);
    }

    public function cancelRequest(Request $request, $familyId)
    {
        $userId = Auth::id(); // Get the authenticated user

        $user = User::find($userId); // Retrieve the user instance

        $familyDetails = Family::findOrFail($familyId);

        // Check if the user has a pending request to join the family
        $pendingRequest = $user->familyRequests()->where('family_id', $familyId)->first();

        if ($pendingRequest) {
            // User has a pending request, cancel the request
            $user->familyRequests()->detach($familyId);

            $response = [
                'status' => "Your pending request to join " . $familyDetails->name . "'s Community has been cancelled",
            ];

            return response()->json($response, 200);
        }

        return response()->json(['error' => 'community request does not exist'], 400);
    }



    public function changePassword(Request $request)
    {
        $user = $request->user();
        // dd($user);
        $oldPassword = $request->input('old_password');
        $newPassword = $request->input('new_password');
        $confirmPassword = $request->input('confirm_password');

        // Check if the old password matches
        if (!Hash::check($oldPassword, $user->password)) {
            return response()->json(['message' => 'Password Does Not Match Your Current Paasword'], 400);
        }

            // Check if the new password and confirmation match
        if ($newPassword !== $confirmPassword) {
            return response()->json(['message' => 'New password and confirmation do not match'], 400);
        }

         // Check if the new password is the same as the old password
        if (Hash::check($newPassword, $user->password)) {
            return response()->json(['message' => 'New password cannot be the same as the current password'], 400);
        }

        // Update the password
        $user->password = Hash::make($newPassword);
        $user->save();

        // Logout the user
        $user->tokens()->where('user_id', $user->id)->delete();

        return response()->json(['message' => 'Password changed successfully'], 200);

    }

    public function updateAccount(Request $request)
    {
        $user = $request->user();

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'location' => 'required|string|max:255',
        ]);

        // Update user details
        $user->name = $request->input('name');
        $user->phone_number = $request->input('phone_number');
        $user->location = $request->input('location');

        // Save the changes
        $user->save();

        return response()->json(['message' => 'Account updated successfully'], 200);
    }

    public function getUserDetails($id)
    {
        $user = User::with('uniqueNumber')->findOrFail($id);

        if($user->uniqueNumber)
        {
            $response = [
                'user_id' => $user->id,
                'name' => $user->name,
                'unique_number' => $user->uniqueNumber->unique_number,
                'plan_type' => $user->plan_type
            ];
    
            return response()->json($response, 200);
        }else{
            return response()->json(['message' => 'Please Subscribe To Access Membership Card'], 403);
        }

        
    }

}
