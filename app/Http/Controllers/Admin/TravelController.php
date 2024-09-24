<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTravelRequest;
use App\Http\Requests\StoreTravelRequest;
use App\Http\Requests\UpdateTravelRequest;
use App\Models\Travel;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class TravelController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('travel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $travels = Travel::with(['media'])->get();

        return view('admin.travels.index', compact('travels'));
    }

    public function create()
    {
        abort_if(Gate::denies('travel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.travels.create');
    }

    public function store(StoreTravelRequest $request)
    {
        $travel = Travel::create($request->all());

        if ($request->input('image', false)) {
            $travel->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $travel->id]);
        }

        return redirect()->route('admin.travels.index');
    }

    public function edit(Travel $travel)
    {
        abort_if(Gate::denies('travel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.travels.edit', compact('travel'));
    }

    public function update(UpdateTravelRequest $request, Travel $travel)
    {
        $travel->update($request->all());

        if ($request->input('image', false)) {
            if (! $travel->image || $request->input('image') !== $travel->image->file_name) {
                if ($travel->image) {
                    $travel->image->delete();
                }
                $travel->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($travel->image) {
            $travel->image->delete();
        }

        return redirect()->route('admin.travels.index');
    }

    public function show(Travel $travel)
    {
        abort_if(Gate::denies('travel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.travels.show', compact('travel'));
    }

    public function destroy(Travel $travel)
    {
        abort_if(Gate::denies('travel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $travel->delete();

        return back();
    }

    public function massDestroy(MassDestroyTravelRequest $request)
    {
        $travels = Travel::find(request('ids'));

        foreach ($travels as $travel) {
            $travel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('travel_create') && Gate::denies('travel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Travel();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
