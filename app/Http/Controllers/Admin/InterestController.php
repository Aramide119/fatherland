<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Interest;
use App\Http\Requests\StoreInterestRequest;
use App\Http\Requests\UpdateInterestRequest;
use App\Http\Requests\MassDestroyInterestRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class InterestController extends Controller
{
    public function index()
    {
      
        $interests = Interest::all();

        return view('admin.interests.index', compact('interests'));
    }

    public function create()
    {

        return view('admin.interests.create');
    }

    public function store(Request $request)
    {
        $interest = Interest::create($request->all());

        return redirect()->route('admin.interests.index');
    }

    public function edit(Interest $interest)
    {

        return view('admin.interests.edit', compact('interest'));
    }

    public function update(Request $request, Interest $interest)
    {
        $interest->update($request->all());

        return redirect()->route('admin.interests.index');
    }

    public function show(Interest $interest)
    {

        return view('admin.interests.show', compact('interest'));
    }

    public function destroy(Interest $interest)
    {
        $interest->delete();

        return back();
    }

    public function massDestroy(MassDestroyInterestRequest $request)
    {
        $interests = Interest::find(request('ids'));

        foreach ($interests as $interest) {
            $interest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
