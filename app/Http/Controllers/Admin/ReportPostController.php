<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportPost;
use Illuminate\Http\Request;

class ReportPostController extends Controller
{
    //
    public function index()
    {
        $posts = ReportPost::with('post')->get();
        // dd($posts);

        return view('admin.reportedPosts.index', compact('posts'));
    }

    public function show(ReportPost $showPost)
    {
        // dd($showPost);
        return view('admin.reportedPosts.show', compact('showPost'));
    }

}
