<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class MembersController extends Controller
{
    //
    public function index()
    {
        $users= User::get();

        return view('admin.members.index', compact('users'));
    }
    public function show(User $user)
    {

        return view('admin.members.show', compact('user'));

    }

    public function destroy(User $user)
    {
        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $users = User::find(request('ids'));

        foreach ($users as $user) {
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
