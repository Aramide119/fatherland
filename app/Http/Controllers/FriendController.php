<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class FriendController extends Controller
{
    public function sendRequest(Request $request, $recipientId)
    {
        $senderId = auth()->user()->id;

        // Validate that the recipient exists
        $recipient = User::find($recipientId);
        if (!$recipient) {
            return response()->json(['message' => 'Recipient not found'], 404);
        }

        // Check if a friend request already exists
        $sender = User::find($senderId);
        if ($sender->friends()->where('friend_id', $recipientId)->exists()) {
            return response()->json(['message' => 'Friend request already sent'], 400);
        }

        // Create the friend request
        $sender->friends()->attach($recipientId, ['status' => 'pending']);

        $response = [
            'data' => $sender,
            'message' => "Friend Request Sent to $recipient->name.",
        ];

        return response()->json($response, 200);
    }

    public function acceptRequest(Request $request, $senderId)
    {
        $recipient = auth()->user(); // Retrieve the authenticated user object instead of the ID

        // Validate that the sender exists
        $sender = User::find($senderId);
        if (!$sender) {
            return response()->json(['message' => 'Sender not found'], 404);
        }

        // Check if a friend request exists
        $friendRequest = $sender->friends()->where('friend_id', $recipient->id)->first();
        if (!$friendRequest || $friendRequest->pivot->status !== 'pending') {
            return response()->json(['message' => 'Friend Request Not Found'], 404);
        }

        // Accept the friend request
        $sender->friends()->updateExistingPivot($recipient->id, ['status' => 'accepted']);
        $recipient->friends()->attach($sender->id, ['status' => 'accepted']);

        $response = [
            "status" => true,
            'sender_name' => $sender->name,
            'recipient_name' => $recipient->name,
            "user" => $recipient,
            "message" => "Friend Request Accepted.",
        ];

        return response()->json($response, 200);
    }

    public function declineRequest(Request $request, $senderId)
    {
        $recipientId = $request->user()->id; // Get the recipient's ID from the authenticated user
        $sender = User::find($senderId);

        if (!$sender) {
            return response()->json(['message' => 'Sender user not found.'], 404);
        }

        // Check if a pending friend request exists from the sender
        $friendship = Friend::where([
            'user_id' => $senderId,
            'friend_id' => $recipientId,
            'status' => 'pending'
        ])->first();

        if (!$friendship) {
            return response()->json(['message' => 'Friend request not found.'], 404);
        }

        // Delete the friend request
        $friendship->delete();

        $response=[
            'status' => true,
            'sender' => $sender->name,
            'data' => $friendship,
            'message' => "You Have Declined A Friend request Sent From $sender->name.",
        ];

        return response()->json($response, 200);
    }

    // public function getUsers()
    // {
    //     $user = User::paginate(20)->withQueryString();

    //     $response = [
    //         "data" => $user,
    //     ];

    //     return response()->json($response, 200);
    // }


    public function getUsers()
    {
        $user = User::whereDoesntHave('roles')
        ->paginate(20)
        ->withQueryString();

        $response = [
            "data" => $user,
        ];

        return response()->json($response, 200);
    }
    
    

    public function getUser($userId)
    {
       // Get user profile
       $user = User::where('id', $userId)->first();
       
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
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $acceptedFriends = $user->acceptedFriends;
        // dd($acceptedFriends);

        $response = [
            'Friends' => $acceptedFriends,
            'message' => 'Friends List found',
        ];

        return response()->json($response, 200);

    }

    public function suggestedFriend(Request $request)
    {
        $user = $request->user();

        // Get the IDs of the user's friends
        $friendIds = $user->friends->pluck('id')->toArray();

        // Exclude the user's current friends and the user themselves
        $suggestedFriends = User::whereNotIn('id', $friendIds)
            ->where('id', '!=', $user->id)
            ->inRandomOrder()
            ->take(5) // Adjust the number of suggested friends as needed
            ->get();
        // dd($suggestedFriends);


        $response = [
            'suggested_users' => $suggestedFriends,
            'message' => 'Suggested users list',
        ];

        return response()->json($response, 200);
    }

    // public function getMutualFriends(Request $request, $otherUserId)
    // {
    //     $user = $request->user();

    //     $mutualFriends = Friend::
    //         join('users', 'users.id', '=', 'friends.friend_id')
    //         ->where('friends.user_id', $user->id)
    //         ->whereIn('friends.friend_id', function ($query) use ($user, $otherUserId) {
    //             $query->select('friend_id')
    //                 ->from('friends')
    //                 ->where('user_id', $otherUserId);
    //         })
    //         // ->select('users.*')
    //         ->select('users.id', 'users.name', 'users.email')
    //         ->get();

    //     return response()->json([
    //         'mutual_friends' => $mutualFriends,
    //         'message' => 'Mutual friends retrieved successfully.'
    //     ], 200);
    // }

    // public function listFriendRequest(Request $request)
    // {
    //     $user = $request->user();
    //     $friendRequestsSent = Friend::where('friend_id', $user->id)
    //     ->where('status', 'pending')
    //     ->with('users')
    //     ->get();
    //     // dd($friendRequestsSent);

    //     $response = [
    //         'friend_request_sent' => $friendRequestsSent,
    //         'message' => 'friend request list',
    //     ];

    //     return response()->json($response, 200);
    // }

    // public function cancelRequest($senderId, $receiverId)
    // {
    //     $sender = User::find($senderId);
    //     $receiver = User::find($receiverId);

    //     if (!$sender || !$receiver) {
    //         return response()->json(['message' => 'User not found'], 404);
    //     }

    //     $friendRequest = Friend::where('user_id', $senderId)
    //         ->where('friend_id', $receiverId)
    //         ->where('status', 'pending')
    //         ->first();

    //     if (!$friendRequest) {
    //         return response()->json(['message' => 'Friend request not found'], 404);
    //     }

    //     $friendRequest->delete();

    //     $response = [
    //         'message' => 'Friend request canceled.',
    //     ];

    //     return response()->json($response, 200);
    // }
}
