<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\MassDestroyUserRequest;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = Admin::with(['roles'])->whereHas('roles')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = Admin::create($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function edit(Admin $admin)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $admin->load('roles');

        return view('admin.users.edit', compact('roles', 'admin'));
    }

    public function update(UpdateUserRequest $request, Admin $admin)
    {
        $admin->update($request->all());
        $admin->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function show(Admin $admin)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $admin->load('roles');

        return view('admin.users.show', compact('admin'));
    }

    public function destroy(Admin $admin)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $admin->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $admins = Admin::find(request('ids'));

        foreach ($admins as $admin) {
            $admin->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
