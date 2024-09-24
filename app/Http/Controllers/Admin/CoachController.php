<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coach;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;


class CoachController extends Controller
{
    public function index()
    {
        
        $coaches = Coach::all();

        return view('admin.coaches.index', compact('coaches'));
    }

    public function create()
    {
        return view('admin.coaches.create');
    }
    
    public function store(Request $request)
    {
        $coach = Coach::create($request->all());

        if ($request->input('image', false)) {
            $coach->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $coach->id]);
        }
        return redirect()->route('admin.events.index');
    }

    public function show(Coach $coach)
    {

        return view('admin.coaches.show', compact('coach'));
    }

    public function edit(Coach $coach)
    {
        return view('admin.coaches.edit', compact('coach'));
    }
    public function update(Request $request, Coach $coach)
    {
        $coach->update($request->all());

        return redirect()->route('admin.coaches.index');
    }
    public function destroy(Coach $coach)
    {
        $coach->delete();

        return back();
    }

    public function massDestroy(Request $request)
    {
        $coaches = Coach::find(request('ids'));

        foreach ($coaches as $coach) {
            $coach->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        
        $model         = new Coach();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
