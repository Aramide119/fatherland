<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAdvertRequest;
use App\Http\Requests\StoreAdvertRequest;
use App\Http\Requests\UpdateAdvertRequest;
use App\Models\Advert;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('advert_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adverts = Advert::with(['media'])->get();

        return view('admin.adverts.index', compact('adverts'));
    }

    public function create()
    {
        abort_if(Gate::denies('advert_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.adverts.create');
    }

    public function store(StoreAdvertRequest $request)
    {
        $advert = Advert::create($request->all());

        if ($request->input('content', false)) {
            $advert->addMedia(storage_path('tmp/uploads/' . basename($request->input('content'))))->toMediaCollection('content');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $advert->id]);
        }

        return redirect()->route('admin.adverts.index');
    }

    public function edit(Advert $advert)
    {
        abort_if(Gate::denies('advert_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.adverts.edit', compact('advert'));
    }

    public function update(UpdateAdvertRequest $request, Advert $advert)
    {
        $advert->update($request->all());

        if ($request->input('content', false)) {
            if (! $advert->content || $request->input('content') !== $advert->content->file_name) {
                if ($advert->content) {
                    $advert->content->delete();
                }
                $advert->addMedia(storage_path('tmp/uploads/' . basename($request->input('content'))))->toMediaCollection('content');
            }
        } elseif ($advert->content) {
            $advert->content->delete();
        }

        return redirect()->route('admin.adverts.index');
    }

    public function show(Advert $advert)
    {
        abort_if(Gate::denies('advert_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.adverts.show', compact('advert'));
    }

    public function destroy(Advert $advert)
    {
        abort_if(Gate::denies('advert_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advert->delete();

        return back();
    }

    public function massDestroy(MassDestroyAdvertRequest $request)
    {
        $adverts = Advert::find(request('ids'));

        foreach ($adverts as $advert) {
            $advert->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('advert_create') && Gate::denies('advert_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Advert();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
