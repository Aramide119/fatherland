<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyContentTypeRequest;
use App\Http\Requests\StoreContentTypeRequest;
use App\Http\Requests\UpdateContentTypeRequest;
use App\Models\ContentType;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentTypeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('content_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contentTypes = ContentType::all();

        return view('admin.contentTypes.index', compact('contentTypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('content_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.contentTypes.create');
    }

    public function store(StoreContentTypeRequest $request)
    {
        $contentType = ContentType::create($request->all());

        return redirect()->route('admin.content-types.index');
    }

    public function edit(ContentType $contentType)
    {
        abort_if(Gate::denies('content_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.contentTypes.edit', compact('contentType'));
    }

    public function update(UpdateContentTypeRequest $request, ContentType $contentType)
    {
        $contentType->update($request->all());

        return redirect()->route('admin.content-types.index');
    }

    public function show(ContentType $contentType)
    {
        abort_if(Gate::denies('content_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.contentTypes.show', compact('contentType'));
    }

    public function destroy(ContentType $contentType)
    {
        abort_if(Gate::denies('content_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contentType->delete();

        return back();
    }

    public function massDestroy(MassDestroyContentTypeRequest $request)
    {
        $contentTypes = ContentType::find(request('ids'));

        foreach ($contentTypes as $contentType) {
            $contentType->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
