<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantCategory;
use Symfony\Component\HttpFoundation\Response;

class RestaurantCategoryController extends Controller
{
    public function index()
    {

        $restaurantCategories = RestaurantCategory::all();

        return view('admin.restaurantCategories.index', compact('restaurantCategories'));
    }

    public function create()
    {

        return view('admin.restaurantCategories.create');
    }

    public function store(Request $request)
    {
        $restaurantCategory = RestaurantCategory::create($request->all());

        return redirect()->route('admin.restaurant-categories.index');
    }

    public function edit(RestaurantCategory $restaurantCategory)
    {
        
        return view('admin.restaurantCategories.edit', compact('restaurantCategory'));
    }

    public function update(Request $request,RestaurantCategory $restaurantCategory)
    {
        $restaurantCategory->update($request->all());

        return redirect()->route('admin.restaurant-categories.index');
    }

    public function show(RestaurantCategory $restaurantCategory)
    {

        return view('admin.restaurantCategories.show', compact('restaurantCategory'));
    }

    public function destroy(RestaurantCategory $restaurantCategory)
    {

        $restaurantCategory->delete();

        return back();
    }

    public function massDestroy(Request $request)
    {
        $restaurantCategories = RestaurantCategory::find(request('ids'));

        foreach ($restaurantCategories as $restaurantCategory) {
            $restaurantCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
