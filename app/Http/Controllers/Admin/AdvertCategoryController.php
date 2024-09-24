<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAdvertCategoryRequest;
use App\Http\Requests\StoreAdvertCategoryRequest;
use App\Http\Requests\UpdateAdvertCategoryRequest;
use App\Models\AdvertCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertCategoryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('advert_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertCategories = AdvertCategory::all();

        return view('admin.advertCategories.index', compact('advertCategories'));
    }

    public function create()
    {
        abort_if(Gate::denies('advert_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertCategories.create');
    }

    public function store(StoreAdvertCategoryRequest $request)
    {
        $advertCategory = AdvertCategory::create($request->all());

        return redirect()->route('admin.advert-categories.index');
    }

    public function edit(AdvertCategory $advertCategory)
    {
        abort_if(Gate::denies('advert_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertCategories.edit', compact('advertCategory'));
    }

    public function update(UpdateAdvertCategoryRequest $request, AdvertCategory $advertCategory)
    {
        $advertCategory->update($request->all());

        return redirect()->route('admin.advert-categories.index');
    }

    public function show(AdvertCategory $advertCategory)
    {
        abort_if(Gate::denies('advert_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.advertCategories.show', compact('advertCategory'));
    }

    public function destroy(AdvertCategory $advertCategory)
    {
        abort_if(Gate::denies('advert_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyAdvertCategoryRequest $request)
    {
        $advertCategories = AdvertCategory::find(request('ids'));

        foreach ($advertCategories as $advertCategory) {
            $advertCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
