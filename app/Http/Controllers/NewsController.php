<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function fetchNews()
    {
        $news = News::with('likedBy', 'comments')
        ->latest()
        ->get();
        

        $response = [
            'news' => $news,
        ];

        return response()->json($response, 200);
    }


    public function getRandomNews()
    {
        // Retrieve random news item
        $randomNews = News::with('likedBy', 'comments')
        ->inRandomOrder()
        ->take(10)
        ->get();

        return response()->json($randomNews, 200);
    }

    public function fetchSingleNews($newsId)
    {
        $singleNews = News::with('likedBy', 'comments')->where('id', $newsId)->first();

        $response = [
            'news' => $singleNews,
        ];

        return response()->json($response, 200);
    }

}
