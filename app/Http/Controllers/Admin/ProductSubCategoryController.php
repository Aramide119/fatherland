<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductSubCategoryRequest;
use App\Http\Requests\StoreProductSubCategoryRequest;
use App\Http\Requests\UpdateProductSubCategoryRequest;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductSubCategoryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('product_sub_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productSubCategories = ProductSubCategory::all();

        return view('admin.productSubCategories.index', compact('productSubCategories'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_sub_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ProductCategory::get();

        return view('admin.productSubCategories.create', compact('categories'));
    }

    public function store(StoreProductSubCategoryRequest $request)
    {
        $productSubCategory = ProductSubCategory::create($request->all());

        return redirect()->route('admin.product-sub-categories.index');
    }

    public function edit(ProductSubCategory $productSubCategory)
    {
        abort_if(Gate::denies('product_sub_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ProductCategory::all();
        $selectedCategoryId = $productSubCategory->product_category_id;

        return view('admin.productSubCategories.edit', compact('productSubCategory', 'categories', 'selectedCategoryId'));
    }

    public function update(UpdateProductSubCategoryRequest $request, ProductSubCategory $productSubCategory)
    {
        $productSubCategory->update($request->all());

        return redirect()->route('admin.product-sub-categories.index');
    }

    public function show(ProductSubCategory $productSubCategory)
    {
        abort_if(Gate::denies('product_sub_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.productSubCategories.show', compact('productSubCategory'));
    }

    public function destroy(ProductSubCategory $productSubCategory)
    {
        abort_if(Gate::denies('product_sub_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productSubCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductSubCategoryRequest $request)
    {
        $productSubCategories = ProductSubCategory::find(request('ids'));

        foreach ($productSubCategories as $productSubCategory) {
            $productSubCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
