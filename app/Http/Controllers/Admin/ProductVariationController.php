<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductVariationRequest;
use App\Http\Requests\StoreProductVariationRequest;
use App\Http\Requests\UpdateProductVariationRequest;
use App\Models\Product;
use App\Models\ProductVariation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductVariationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('product_variation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productVariations = ProductVariation::with(['product'])->get();

        $products = Product::get();

        return view('admin.productVariations.index', compact('productVariations', 'products'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_variation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.productVariations.create', compact('products'));
    }

    public function store(StoreProductVariationRequest $request)
    {
        $productVariation = ProductVariation::create($request->all());

        return redirect()->route('admin.product-variations.index');
    }

    public function edit(ProductVariation $productVariation)
    {
        abort_if(Gate::denies('product_variation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productVariation->load('product');

        return view('admin.productVariations.edit', compact('productVariation', 'products'));
    }

    public function update(UpdateProductVariationRequest $request, ProductVariation $productVariation)
    {
        $productVariation->update($request->all());

        return redirect()->route('admin.product-variations.index');
    }

    public function show(ProductVariation $productVariation)
    {
        abort_if(Gate::denies('product_variation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productVariation->load('product');

        return view('admin.productVariations.show', compact('productVariation'));
    }

    public function destroy(ProductVariation $productVariation)
    {
        abort_if(Gate::denies('product_variation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productVariation->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductVariationRequest $request)
    {
        $productVariations = ProductVariation::find(request('ids'));

        foreach ($productVariations as $productVariation) {
            $productVariation->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
