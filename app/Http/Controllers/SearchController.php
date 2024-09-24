<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Family;
use App\Models\Dynasty;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Search users
        $users = User::where('name', 'like', "%$query%")->get();

        // Search posts by user_id
        $userPosts = Post::whereHas('user', function ($userQuery) use ($query) {
            $userQuery->where('name', 'like', "%$query%");
        })->with('user', 'family', 'likes', 'comments.user', 'reposts.media', 'reposts.user','reposts.family', 'media')
        ->get();

        // Extract URLs from the media collection
        $userPosts->each(function ($post) {
            $post->mediaUrls = $post->media->map(function ($media) {
                return $media->getUrl();
            });
            unset($post->media); // Remove the full media collection if not needed
        });

        // Search families by created_by
        $userFamilies = Family::where('name', 'like', "%$query%")->get();

        // Search dynasties by created_by
        $userDynasties = Dynasty::where('name', 'like', "%$query%")->get();

        // Search for videos
        $videos = Media::where('model_type', Post::class)
        ->where('collection_name', 'videos')
        ->whereHas('model', function ($postQuery) use ($query) {
            $postQuery->where('model_type', Post::class)
                ->where('name', 'like', "%$query%");
        })
        ->with('model.user', 'model.family', 'model.likes', 'model.comments.user')
        ->get();

        // Extract URLs from the video media collection
        $videosWithUrls = $videos->map(function ($video) {
            return [
                'id' => $video->id,
                'videoUrl' => $video->getUrl(),
                'user' => $video->model->user,
                'family' => $video->model->family,
                'likes' => $video->model->likes,
                'comments' => $video->model->comments,
                // Add other attributes as needed
            ];
        });

        // Check if any of the arrays is empty
        if ($users->isEmpty() && $userPosts->isEmpty() && $userFamilies->isEmpty() && $userDynasties->isEmpty() && $videos->isEmpty()) {
            // Customize the response when no results are found
            return response()->json(['message' => 'No results found'], 200);
        }

        // You can customize the response structure based on your needs
        $response = [
            'users' => $users,
            'posts' => $userPosts,
            'families' => $userFamilies,
            'dynasties' => $userDynasties,
            'videos' => $videosWithUrls,
        ];

        return response()->json($response, 200);
    }
}
