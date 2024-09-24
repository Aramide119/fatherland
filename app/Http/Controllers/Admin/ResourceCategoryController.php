<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyResourceCategoryRequest;
use App\Http\Requests\StoreResourceCategoryRequest;
use App\Http\Requests\UpdateResourceCategoryRequest;
use App\Models\ResourceCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceCategoryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('resource_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $resourceCategories = ResourceCategory::all();

        return view('admin.resourceCategories.index', compact('resourceCategories'));
    }

    public function create()
    {
        abort_if(Gate::denies('resource_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.resourceCategories.create');
    }

    public function store(StoreResourceCategoryRequest $request)
    {
        $resourceCategory = ResourceCategory::create($request->all());

        return redirect()->route('admin.resource-categories.index');
    }

    public function edit(ResourceCategory $resourceCategory)
    {
        abort_if(Gate::denies('resource_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.resourceCategories.edit', compact('resourceCategory'));
    }

    public function update(UpdateResourceCategoryRequest $request, ResourceCategory $resourceCategory)
    {
        $resourceCategory->update($request->all());

        return redirect()->route('admin.resource-categories.index');
    }

    public function show(ResourceCategory $resourceCategory)
    {
        abort_if(Gate::denies('resource_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.resourceCategories.show', compact('resourceCategory'));
    }

    public function destroy(ResourceCategory $resourceCategory)
    {
        abort_if(Gate::denies('resource_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $resourceCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyResourceCategoryRequest $request)
    {
        $resourceCategories = ResourceCategory::find(request('ids'));

        foreach ($resourceCategories as $resourceCategory) {
            $resourceCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
