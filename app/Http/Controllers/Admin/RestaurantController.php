<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRestaurantRequest;
use App\Http\Requests\StoreRestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Models\Restaurant;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Traits\VideoUpload;
use App\Traits\ImageUpload;
use App\Models\RestaurantCategory;

class RestaurantController extends Controller
{
    use ImageUpload;
    use VideoUpload;

    public function index()
    {
        abort_if(Gate::denies('restaurant_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $restaurants = Restaurant::with(['media'])->get();

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        abort_if(Gate::denies('restaurant_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.restaurants.create');
    }

    public function store(StoreRestaurantRequest $request)
    {
        $request->validated();

        $creator = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::user();
        
        $restaurant = Restaurant::create([
            'name' => $request->name,
            'location' => $request->location,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'website_link' => $request->website_link,
            'description' => $request->description,
            'status' => $request->status,
            'creator_id' => $creator->id,
            'restaurant_category_id'=>$request->restaurant_category_id,
            'creator_type' => get_class($creator),
        ]);
        // $restaurant = Restaurant::create($request->all());

        if ($request->hasFile('images')) {
            
            foreach ($request->file('images') as $image) {
                $restaurantImage = $this->manualStoreMedia($image)['name'];
                 $restaurant->addMedia(storage_path('tmp/uploads/'.basename($restaurantImage)))->toMediaCollection('images');
 
             }

        }
        return redirect()->route('admin.restaurants.index');
    }

    public function edit(Restaurant $restaurant)
    {
        abort_if(Gate::denies('restaurant_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        
        $restaurant_categories = RestaurantCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $restaurant->load('restaurant_category');

        return view('admin.restaurants.edit', compact('restaurant', 'restaurant_categories'));
    }

    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {
        
        $creator = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::user();
        
        $restaurant->update([
            'name' => $request->name,
            'location' => $request->location,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'website_link' => $request->website_link,
            'description' => $request->description,
            'status' => $request->status,
            'creator_id' => $creator->id,
            'creator_type' => get_class($creator),
        ]);

        if ($request->hasFile('images')) {
            if ($restaurant->hasMedia('images')) {
                $restaurant->clearMediaCollection('images');
            }

            // Add the new images
            foreach ($request->file('images') as $image) {
                $restaurantImage = $this->manualStoreMedia($image)['name'];
                 $restaurant->addMedia(storage_path('tmp/uploads/'.basename($restaurantImage)))->toMediaCollection('images');
 
             }
        }
        

        return redirect()->route('admin.restaurants.index');
    }

    public function show(Restaurant $restaurant)
    {
        abort_if(Gate::denies('restaurant_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.restaurants.show', compact('restaurant'));
    }

    public function destroy(Restaurant $restaurant)
    {
        abort_if(Gate::denies('restaurant_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $restaurant->delete();

        return back();
    }

    public function massDestroy(MassDestroyRestaurantRequest $request)
    {
        $restaurants = Restaurant::find(request('ids'));

        foreach ($restaurants as $restaurant) {
            $restaurant->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

   

}
