<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductRatingRequest;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductRatingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('product_rating_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productRatings = ProductRating::with(['user', 'product'])->get();

        $users = User::get();

        $products = Product::get();

        return view('admin.productRatings.index', compact('productRatings', 'products', 'users'));
    }

    public function show(ProductRating $productRating)
    {
        abort_if(Gate::denies('product_rating_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productRating->load('user', 'product');

        return view('admin.productRatings.show', compact('productRating'));
    }

    public function destroy(ProductRating $productRating)
    {
        abort_if(Gate::denies('product_rating_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productRating->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductRatingRequest $request)
    {
        $productRatings = ProductRating::find(request('ids'));

        foreach ($productRatings as $productRating) {
            $productRating->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
