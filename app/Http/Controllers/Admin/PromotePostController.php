<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromotePost;
use Illuminate\Http\Request;
use App\Models\Post;

class PromotePostController extends Controller
{
    //
    public function index()
    {
        $promotedPosts= PromotePost::get();
        return view('admin.promotePosts.index', compact('promotedPosts'));
    }
    public function create()
    {

        return view('admin.promotePosts.create');

    }
    public function store(Request $request)
    {
         PromotePost::create($request->all());

         return redirect()->route('admin.promotePosts.index');
    }

    public function edit($id)
    {
        $promotedPost = PromotePost::findOrFail($id);
        
        return view('admin.promotePosts.edit' , compact('promotedPost'));
    }

    public function update(Request $request, $id)
    {
        $promotedPost=PromotePost::findOrFail($id);
        
        $promotedPost->update($request->all());   
    
        return redirect()->route('admin.promotePosts.index');
    }
    public function show( $id)
    {
        $showPromotedPost = PromotePost::findOrFail($id);
        return view('admin.promotePosts.show', compact('showPromotedPost'));
    }

    public function inactivePosts() 
    {
        $posts = Post::where('status','inactive')
                        ->with('media')
                        ->get();
        return view('admin.inactivePosts.index', compact('posts'));
    }

    public function showInactivePosts($id) 
    {
        $posts = Post::with('media')
                    ->findOrFail($id);
        return view('admin.inactivePosts.show', compact('posts'));
    }

    public function destroy( $id)
    {

        $promotedPost = PromotePost::findOrFail($id);
        $promotedPost->delete();

        return back();
    
    }

}
