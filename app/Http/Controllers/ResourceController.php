<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Content::with(['content_type', 'content_category'])
        ->latest()
        ->get();

        $response = [
            'content' => $resources,
        ];

        return response()->json($response, 200);
    }

    public function getRandomContent()
    {
        // Retrieve random news item
        $randomContent = Content::with(['content_type', 'content_category'])
        ->inRandomOrder()
        ->take(10)
        ->get();

        return response()->json($randomContent, 200);
    }

    public function getExploreRecords(Request $request)
    {
        $user = Auth::user();
        $query = $request->input('query');
        $records = [];
    
        // Check if the user's plan type is "basic"
        if ($user->plan_type === 'basic') {
            // Only fetch records that the user is qualified to access based on the search query
            return response()->json(['message' => 'Upgrade your account to access records.'], 403);
        } else {
            // Fetch records based on the search query for users with other plan types
            $records = Record::where('name', 'like', "%$query%")
                ->orWhere('lineage', 'like', "%$query%")
                ->orWhere('notable_individual', 'like', "%$query%")
                ->orWhere('about', 'like', "%$query%")
                ->get();
        }
    
        return response()->json($records, 200);
    }
    

    public function show($id)
    {
        // $user = Auth::user();
        $content = Content::with('content_type', 'content_category')
        ->where('id', $id)
        ->first();

        if(!$content){
            return response()->json(["message" => "This resource is not available"], 400);
        }

        $response = [
            'message' => 'Resource returned successfully',
            'data' => [
                'id' => $content->id,
                'title' => $content->title,
                'author' => $content->author,
                'blog_content' =>  $content->blog_content,
                'content_image' => $content->getMedia('blog_image')->map->getUrl(),
                'content_type' => $content->content_type['name'],
                'content_category' => $content->content_category['name'],
                'created_at' => $content->created_at,
                'updated_at' => $content->updated_at,
                'deleted_at' => $content->deleted_at,
            ]
        ];

        return response()->json($response, 200);
    }
}


   