<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyContentRequest;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;
use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\ContentType;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ContentController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('content_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contents = Content::with(['content_type', 'content_category', 'media'])->get();

        $content_types = ContentType::get();

        $content_categories = ContentCategory::get();

        return view('admin.contents.index', compact('content_categories', 'content_types', 'contents'));
    }

    public function create()
    {
        abort_if(Gate::denies('content_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $content_types = ContentType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $content_categories = ContentCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.contents.create', compact('content_categories', 'content_types'));
    }

    public function store(StoreContentRequest $request)
    {
        $content = Content::create($request->all());

        if ($request->input('blog_image', false)) {
            $content->addMedia(storage_path('tmp/uploads/' . basename($request->input('blog_image'))))->toMediaCollection('blog_image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $content->id]);
        }

        return redirect()->route('admin.contents.index');
    }

    public function edit(Content $content)
    {
        abort_if(Gate::denies('content_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $content_types = ContentType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $content_categories = ContentCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $content->load('content_type', 'content_category');

        return view('admin.contents.edit', compact('content', 'content_categories', 'content_types'));
    }

    public function update(UpdateContentRequest $request, Content $content)
    {
        $content->update($request->all());

        if ($request->input('blog_image', false)) {
            if (! $content->blog_image || $request->input('blog_image') !== $content->blog_image->file_name) {
                if ($content->blog_image) {
                    $content->blog_image->delete();
                }
                $content->addMedia(storage_path('tmp/uploads/' . basename($request->input('blog_image'))))->toMediaCollection('blog_image');
            }
        } elseif ($content->blog_image) {
            $content->blog_image->delete();
        }

        return redirect()->route('admin.contents.index');
    }

    public function show(Content $content)
    {
        abort_if(Gate::denies('content_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $content->load('content_type', 'content_category');

        return view('admin.contents.show', compact('content'));
    }

    public function destroy(Content $content)
    {
        abort_if(Gate::denies('content_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $content->delete();

        return back();
    }

    public function massDestroy(MassDestroyContentRequest $request)
    {
        $contents = Content::find(request('ids'));

        foreach ($contents as $content) {
            $content->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('content_create') && Gate::denies('content_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Content();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
