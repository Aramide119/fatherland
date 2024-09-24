<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\NewsComment;
use Illuminate\Http\Request;
use App\Models\Notification;

class CommentController extends Controller
{
    public function index($postId)
    {

        $comments = Comment::with('replies', 'user', 'replies.user', 'likes.user')->where('post_id', $postId)->get();

         // Check if comments are empty
        if ($comments->isEmpty()) {
            return response()->json(['message' => 'No comments yet.'], 404);
        }

        $nestedComments = $comments->groupBy('parent_id');

        $rootComments = $nestedComments->get(null);

        $commentsWithReplies = $rootComments->map(function ($comment) use ($nestedComments) {
            $comment->replies = $nestedComments->get($comment->id);
            return $comment;
        });

        return response()->json(['comments' => $commentsWithReplies]);
    
    }


    public function store(Request $request, Post $post)
    {
        $user = $request->user();

        $request->validate([
            'body' => 'required|string',
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->user()->id,
            'body' => $request->input('body'),
        ]);

        Notification::create([
            "user_id"=>$post->user_id,
            "sender_id"=>$user->id,
            "post_id"=>$post->id,
            "comment_id"=>$comment->id,
            "notification_type"=>"comment"
        ]);
        $response = [
            'message' => 'Comment created successfully',
            'comment' => $comment,
            'user' => $user,
        ];

        return response()->json($response, 200 );
    }

    public function reply(Request $request, Comment $comment)
    {
        $user = $request->user();

        $request->validate([
            'body' => 'required|string',
        ]);
    
        $reply = new Comment([
            'user_id' => auth()->user()->id,
            'body' => $request->input('body'),
            'post_id' => $comment->post_id,
        ]);
    
        $comment->replies()->save($reply);

        $post = Post::find($comment->post_id);

        Notification::create([
            "user_id"=>$post->user_id,
            "sender_id"=>$user->id,
            "post_id"=>$comment->post_id,
            "comment_id"=>$comment->id,
            "notification_type"=>"reply_comment"
        ]);
        return response()->json([
            'message' => 'Reply created successfully',
            'reply' => $reply,
            'user' => $user,
        ]);
    }


    public function likeComment(Request $request, $commentId)
    {
        // Get the authenticated user
        $user = $request->user();

        // Find the comment to be liked
        $comment = Comment::findOrFail($commentId);

        // Check if the user has already liked the comment
        $existingLike = $comment->likes()->where('user_id', $user->id)->first();
       
        
        if($existingLike) {
            //If the user has already liked the post , unlike it.
            $existingLike->delete();

            return response()->json(['message' => 'comment unliked successfully']);
        }else{
             // Create a new like for the comment
        $like = new CommentLike();
        $like->user_id = $user->id;

        // Associate the like with the comment
        $comment->likes()->save($like);

        Notification::create([
            "user_id"=>$comment->post->user_id,
            "sender_id"=>$user->id,
            "post_id"=>$comment->post_id,
            "comment_id"=>$comment->id,
            "notification_type"=>"like_comment"
        ]);
        return response()->json([
            'message' => 'Comment liked successfully.',
        ], 200);
        }
       
    }


    public function unlikeComment(Request $request, $commentId)
    {
        // Get the authenticated user
        $user = $request->user();

        // Find the comment to be unliked
        $comment = Comment::findOrFail($commentId);

        // Check if the user has liked the comment
        $existingLike = $comment->likes()->where('user_id', $user->id)->first();
        if (!$existingLike) {
            // User hasn't liked the comment, return a response indicating that
            return response()->json([
                'message' => 'You have not liked this comment.',
            ], 200);
        }

        // Delete the like record
        $existingLike->delete();

        return response()->json([
            'message' => 'Comment unliked successfully.',
        ], 200);
    }


    public function comment(Request $request, News $news)
    {
        $user = auth()->user();

        $request->validate([
            'comment' => 'required|string',
        ]);
         // Create the comment using the NewsComment model
        $comments = $news->comments()->create([
            'user_id' => auth()->user()->id,
            'comment' => $request->input('comment'),
            'status' => 'active',
        ]);
        
        $response = [
            'message' => 'Comment created successfully',
            'comment' => $comments,
            'user' => $user,
        ];

        return response()->json($response, 200 );
    }


    public function replyComment(Request $request, NewsComment $comment)
    {
        $user = auth()->user();
    
        $request->validate([
            'comment' => 'required|string',
        ]);
    
        // Create the reply using the NewsComment model
        $replyComment = new NewsComment([
            'user_id' => auth()->user()->id,
            'comment' => $request->input('comment'),
            'news_id' => $comment->news_id,
            'status' => 'active',
        ]);
    
        $comment->replies()->save($replyComment);
    
        $response = [
            'message' => 'Reply created successfully',
            'reply' => $replyComment,
            'user' => $user,
        ];
    
        return response()->json($response, 200);
    }
    
    public function fetchNewsComments($newsId)
    {
        $newsComments = NewsComment::with('user', 'replies.user')
            ->where('news_id', $newsId)
            ->where('status', 'active') // Add this line to filter by status
            ->get();
            // dd($newsComments);
    
        $nestedComments = $newsComments->groupBy('parent_id');
    
        $rootComments = $nestedComments->get(null);
    
        if ($rootComments === null) {
            return response()->json(['comments' => 'No comments']);
        }
    
        $allNewsComments = $rootComments->map(function ($comments) use ($nestedComments) {
            $comments->replies = $nestedComments->get($comments->id);
            return $comments;
        });
    
        return response()->json(['comments' => $allNewsComments]);
    }
    


}
