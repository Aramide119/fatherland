<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\News;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class LikeController extends Controller
{
    public function like($id)
    {
        // Find the post
        $post = Post::findOrFail($id);
        $user = Auth::user();

        $existing_like = Like::where('user_id', $user->id)
        ->where('post_id', $post->id)->first();

        if($existing_like) {
            //If the user has already liked the post , unlike it.
            $existing_like->delete();

            return response()->json(['message' => 'post unliked successfully']);
        }else{
            
        // Create the like record
        $like = new Like();
        $like->user_id = $user->id;
        $like->post_id = $post->id;
        $like->save();

        Notification::create([
            "sender_id"=>$user->id,
            "post_id"=>$post->id,
            "user_id"=>$post->user_id,
            "notification_type"=>"like_post"
        ]);
        }

        return response()->json(['message' => 'Post liked successfully']);
    }

    public function unlike($id)
    {
        // Find the post
        $post = Post::findOrFail($id);
        $user = Auth::user()->id;

        $unlike = Like::where('user_id', $user)->where('post_id', $post->id)->delete();

        return response()->json(['message' => 'Post unliked successfully']);
    }

    public function getLikes($postId)
    {
        $post = Post::findOrFail($postId);
        $likes = Like::with('user')->where('post_id', $post->id)->get();
    
        return response()->json($likes);
    }    

    
    public function LikeNews(Request $request, $newsId)
    {
        $user = Auth::user();
        $news = News::findOrFail($newsId);

        if ($user->likes()->where('news_id', $news->id)->exists()) {
            // If user has already liked the news, unlike it
            $user->likes()->detach($news->id);
            $message = 'News unliked successfully.';
            $liked = false;
        } else {
            // If user has not liked the news, like it
            $user->likes()->attach($news->id);
            $message = 'News liked successfully.';
            $liked = true;
        }

        return response()->json([
            'message' => $message,
            'liked' => $liked,
        ]);
    }


}
