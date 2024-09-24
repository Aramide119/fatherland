<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allComments = NewsComment::with(['user', 'news', 'replies'])
        ->whereNull('parent_id') // Filter by comments with no parent_id
        ->get();

        return view('admin.comments.index', compact('allComments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        // dd($id);
        $reply = NewsComment::findOrFail($id);
        return view('admin.comments.reply', compact('reply'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, NewsComment $replies)
    {
        $user = auth()->user();

        $request->validate([
            'comment' => 'required|string',
        ]);

        // Create the reply using the NewsComment model
        $replyComment = new NewsComment([
            'admin_id' => auth()->user()->id,
            'comment' => $request->input('comment'),
            'news_id' => $replies->news_id,
            'status' => 'active',
        ]);

        $replies->replies()->save($replyComment);

        return redirect()->route('admin.comments.index');
    }


    public function changeStatus($id)
    {
        $comment = NewsComment::findOrFail($id);

        // Toggle the status
        $comment->status = $comment->status == 'active' ? 'inactive' : 'active';
        $comment->save();

        return redirect()->back()->with('status', 'Comment status changed successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $viewComments = NewsComment::findOrFail($id);

        return view('admin.comments.show', compact('viewComments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
