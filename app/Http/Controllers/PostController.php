<?php

namespace App\Http\Controllers;

use FFMpeg\FFMpeg;
use App\Models\Post;
use App\Models\Image;
use App\Models\Video;
use App\Models\Family;
use App\Models\Repost;
use App\Models\ReportPost;
use App\Traits\ImageUpload;
use App\Traits\VideoUpload;
use Illuminate\Support\Str;
use App\Models\FamilyMember;
use App\Models\Notification;
use Illuminate\Http\Request;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PromotePostSubscription;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Http\Exceptions\HttpResponseException;



class PostController extends Controller
{
    use ImageUpload;
    use VideoUpload;



    public function store(Request $request)
    {
        $user = $request->user();
        // Validate the incoming request data
        $validatedData = $request->validate([
            'text' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
            'videos.*' => 'nullable|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:30048|',
            'family_id' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    $associatedFamilies = $user->families()->pluck('families.id')->toArray();
                    if (!in_array($value, $associatedFamilies)) {
                        $fail("You are not a member of the family you selected");
                    }
                },
            ],
        ]);

        $text = $validatedData['text'] ?? null;

        $familyId = $request->input('family_id');
        $family = Family::findOrFail($familyId);

        if($family->status != "accepted"){
            return response()->json(['error' => 'This family does not exist'], 400);
        }
        // Get the user's associated family IDs
        $familyIds = $user->families()->pluck('families.id')->toArray();

        // Create a new post
        $post = new Post();
        $post->user_id = $request->user()->id;
        $post->text = $text;
        $post->slug = $post->generateSlug();
        $post->status = "active";
        // Check if the requested family ID is one of the user's associated families
        if (in_array($familyId, $familyIds)) {
            $post->family_id = $familyId; // Assign the requested family ID to the post
        } else {

            return response()->json(['error' => 'You are not associated with this family'], 400);
        }
        $post->save();

        // Get the family members
        $familyMembers = $family->users()->where('family_id', $family->id)->get();

            Notification::create([
                'user_id' =>  $user->id,
                'sender_id' => $user->id,
                'family_id' => $family->id,
                'post_id' => $post->id,
                'notification_type' => 'family_post',
            ]);

        $postId = $post->id; // Store the post_id

       // Upload multiple images
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {
                $postImage = $this->manualStoreMedia($image)['name'];
                 $post->addMedia(storage_path('tmp/uploads/'.basename($postImage)))->toMediaCollection('images');

             }

        }


        // Upload multiple videos
        if ($request->hasFile('videos')) {

            foreach ($request->file('videos') as $video) {
               $postVideo = $this->manualStoreMedia($video)['name'];
                $post->addMedia(storage_path('tmp/uploads/'.basename($postVideo)))->toMediaCollection('videos');

            }
        }

        $post->load('media');

        // Prepare the response data
        $responseData = [
            'message' => 'Post created successfully',
            'data' => [
                'user' => $user,
                'user_id' => $post->user_id,
                'text' => $post->text,
                'link' => $post->slug,
                'updated_at' => $post->updated_at,
                'created_at' => $post->created_at,
                'post_id' => $post->id,
                'family_id' => $post->family_id,
                'images' => $post->getMedia('images')->map->getUrl(),
                'videos' => $post->getMedia('videos')->map->getUrl(),
            ],
        ];

        return response()->json($responseData, 200);
    }



    public function getPostBySlug($slug)
    {
        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($post, 200);
    }


    public function update(Request $request, Post $postId)
    {
        $user = $request->user();

        // Validate the incoming request data
        $validatedData = $request->validate([
            'text' => 'nullable|string',
            'images.*' => 'nullable|image',
            'videos.*' => 'nullable|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4',
            'likes' => 'nullable',
            'comments' => 'nullable',
        ]);



        // Find the post to be updated
        $post = $postId;

            // Check if the user is authorized to update the post
        if ($post->user_id !== $user->id) {
            return response()->json(['error' => 'You are not authorized to update this post'], 403);
        }



        // Update the post data
        $post->text = $validatedData['text'];
        $post->save();

        // Update the images if provided
        if ($request->hasFile('images')) {
            // Check if images exist and delete them if they do
            if ($post->hasMedia('images')) {
                $post->clearMediaCollection('images');
            }

            // Add the new images
            foreach ($request->file('images') as $image) {
                $postImage = $this->manualStoreMedia($image)['name'];
                 $post->addMedia(storage_path('tmp/uploads/'.basename($postImage)))->toMediaCollection('images');

             }
        }

        // Update the videos if provided
        if ($request->hasFile('videos')) {
            // Check if videos exist and delete them if they do
            if ($post->hasMedia('videos')) {
                $post->clearMediaCollection('videos');
            }

            // Add the new videos
            foreach ($request->file('videos') as $video) {
                $postVideo = $this->manualStoreMedia($video)['name'];
                 $post->addMedia(storage_path('tmp/uploads/'.basename($postVideo)))->toMediaCollection('videos');

             }
        }

        // Reload the updated post along with images and videos
         $post->load('media');

        $responseData = [
            'message' => 'Post updated successfully',
            'data' => [
                'user_id' => $post->user_id,
                'text' => $post->text,
                'visibility' => $post->visibility,
                'updated_at' => $post->updated_at,
                'created_at' => $post->created_at,
                'post_id' => $post->id,
                'family_id' => $post->family_id,
                'images' => $post->getMedia('images')->map->getUrl(),
                'videos' => $post->getMedia('videos')->map->getUrl(),
            ],
        ];

         return response()->json($responseData, 200);
    }

    public function deletePost(Request $request, Post $postId)
    {
        // Find the post to be deleted
        $post = $postId;

        // Check if the user is authorized to delete the post
        if ($post->user_id !== $request->user()->id) {
            return response()->json(['error' => 'You are not authorized to delete this post'], 403);
        }

        // Delete the images associated with the post
        $post->clearMediaCollection('images');

        // Delete the videos associated with the post
        $post->clearMediaCollection('videos');

        $post->notifications()->delete();
        // Delete the post
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }




    public function fetchPosts(Request $request)
    {
        $payment = new PaystackController();


        $payment->handleRecurringPayment();

        //charge the user

        // Get the Ids Of the Blocked Users
        $blockedUsersIds = auth()->user()->blockedUsers()->pluck('blocked_user_id')->toArray();

        $reportpostids = auth()->user()->reportPost()->pluck('post_id')->toArray();

        // Check if there are any reported posts
        if (!empty($reportpostids)) {
            foreach ($reportpostids as $postId) {
                if ($postId > 100) {
                    // Update the status of this post to 'inactive'
                    Post::where('id', $postId)
                        ->update([
                            'status' => 'inactive'
                        ]);
                }
            }
        }

       // Check if there are any posts that have been promoted and payment is successful
        $promotedPosts = PromotePostSubscription::where('payment_status', 'successful')
        ->pluck('post_id')
        ->toArray();

        // Fetch original posts, including promoted posts with successful payment
        $posts = Post::where('status', 'active')
        ->whereNotIn('user_id', $blockedUsersIds)
        ->whereNotIn('id', $reportpostids) // Exclude reported posts
        ->with('user', 'family', 'likes', 'comments.user', 'reposts.media', 'reposts.user','reposts.family', 'reposts.likes', 'reposts.comments.user', 'media')
        ->orWhereHas('reposts');// Include posts that have been reposted;

        if (!empty($promotedPosts)) {
        $posts->orderByRaw(DB::raw('FIELD(id, ' . implode(',', $promotedPosts) . ') DESC')); // Promoted posts come first
        }

        $posts = $posts->latest()->paginate(20)->withQueryString();



        return response()->json($posts, 200);
    }

    public function fetchSinglePost($postId)
    {
        $getPost = Post::where('status', 'active')
            ->where('id', $postId)
            ->with('user', 'family', 'likes', 'comments.user', 'comments.replies.user', 'reposts.media', 'reposts.user', 'reposts.family', 'media')
            ->with(['comments' => function ($query) {
                // Fetch comments where parent_id is null
                $query->whereNull('parent_id')->with('replies.user');
            }])
            ->first();

        if (!$getPost) {
            return response()->json(["message" => "Post not found!"], 400);
        }

        $response = [
            'post' => $getPost,
        ];

        return response()->json($response, 200);
    }






    public function getVideos(Request $request)
    {
       // Fetch the IDs of posts that have associated video media items
            $postIdsWithVideos = Media::where('model_type', Post::class)
            ->where('collection_name', 'videos')
            ->pluck('model_id')
            ->unique();

        // Fetch only the posts with associated video media items
        $videos = Post::with('user', 'likes', 'comments')
            ->whereIn('id', $postIdsWithVideos)
            ->paginate(10)->withQueryString();

        // Map the video URLs for each post
       // Map the video URLs for each post
        $videos->getCollection()->transform(function ($post) {
            // Fetch only video media items associated with the post
            $videoMediaItems = Media::where('model_type', Post::class)
                ->where('model_id', $post->id)
                ->where('collection_name', 'videos')
                ->get();

            // Extract the video URLs
            $videoUrls = $videoMediaItems->map(function (Media $mediaItem) {
                return $mediaItem->getUrl();
            });

            // Unset the 'images' attribute from the post
            unset($post['images']);

            // Associate the video URLs with the post
            $post->videos = $videoUrls;

            return $post;
        });


        return response()->json(['videos' => $videos], 200);
    }



    public function reportPost(Request $request, $post_id)
    {
        $post = Post::findOrFail($post_id);

        $request->validate([
            'message' => 'required|string',
        ]);
        if($post->user_id !== auth()->user()->id )
        {
        $reportPost = ReportPost::create([

                'user_id' => auth()->user()->id,
                'post_id' => $post_id,
                'message' => $request->input('message'),
            ]);

            $response = [
                'message' => 'reports created successfully',
                'reportPost' => $reportPost,
            ];
            return response()->json($response, 200 );
        }else{
            return response()->json(['message' => 'Methods not allowed', 405]);

        }


    }


    public function repostPost(Request $request, Post $post)
    {
        // Get the authenticated user who is making the repost
        $user = $request->user();

        // Check if the post has already been reposted by the user
        if ($post->reposts()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You have already reposted this post'], 400);
        }

        // Create a new repost record
        $repost = new Post();
        $repost->user_id = $user->id;
        $repost->repost_id = $post->id; // Save the original post ID in the repost
        $repost->save();

        Notification::create([
            "user_id"=>$post->user_id,
            "post_id"=>$post->id,
            "sender_id"=>$user->id,
            "notification_type"=>"repost_post"
        ]);

        // Return the new repost data including the original media URLs
        return response()->json([
            'message' => 'Post reposted successfully',
            'repost' => [
                'id' => $repost->id,
                'user' => $repost->user,
                'user_id' => $repost->user_id,
                'link' => $repost->slug,
                'updated_at' => $repost->updated_at,
                'created_at' => $repost->created_at,
                'post_id' => $repost->id,
                'family_id' => $repost->family_id,
                'images' => $post->getMedia('images')->map->getUrl(),
                'videos' => $post->getMedia('videos')->map->getUrl(),
            ],
        ], 200);
    }

    public function fetchIndividualPost(Request $request)
    {
        //Get Authenticated user
        $user = Auth::user();

        //Fetch the user post with likes comments and family
        $posts = Post::where('user_id', $user->id)
        ->with('user', 'family', 'likes', 'comments.user', 'reposts.media', 'reposts.user', 'media')
        ->latest()
        ->get();
        // dd($posts);

        if($posts->count() === 0){
            $response = [
                'status' => false,
                'message' => 'This User has not made any post',
                'data' => $posts
            ];

            return response()->json($response, 200);
        }


        return response()->json($posts, 200);

    }




}
