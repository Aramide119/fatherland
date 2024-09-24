<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Traits\VideoUpload;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    
    use ImageUpload;
    use VideoUpload;

    public function index()
    {
         // Fetch all restaurants with related data
        $restaurants = Restaurant::with('media', 'restaurant_category')
        ->where('status', 'active')
        ->get();

        // Check if any restaurants were found
        if ($restaurants->isEmpty()) {
            return response()->json(["message" => "No Restaurants Found"], 400);
        }

        // Prepare response data
        $response = $restaurants->map(function ($restaurant) {
            return [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'location' => $restaurant->location,
                'phone_number' => $restaurant->phone_number,
                'email' => $restaurant->email,
                'website_link' => $restaurant->website_link,
                'description' => $restaurant->description,
                'creator_id' => $restaurant->creator_id,
                'creator_type' => $restaurant->creator_type,
                'status' => $restaurant->status,
                'restaurant_category_id' => $restaurant->restaurant_category_id,
                'restaurant_category' => $restaurant->restaurant_category,
                'images' => $restaurant->getMedia('images')->map->getUrl(), // Extract only the image URLs
            ];
        });

        // Return JSON response
        return response()->json([
            'message' => 'Restaurant data retrieved successfully',
            'restaurants' => $response,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // Validate the request
         $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
            'website_link' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            // 'status' => 'required|in:active,inactive',
            'restaurant_category_id' => 'required|exists:restaurant_categories,id',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Get the authenticated user
        $creator = Auth::user();

        // Create the restaurant
        $restaurant = Restaurant::create([
            'name' => $request->name,
            'location' => $request->location,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'website_link' => $request->website_link,
            'description' => $request->description,
            'status' => 'active',
            'creator_id' => $creator->id,
            'restaurant_category_id' => $request->restaurant_category_id,
            'creator_type' => get_class($creator),
        ]);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $restaurantImage = $this->manualStoreMedia($image)['name'];
                $restaurant->addMedia(storage_path('tmp/uploads/' . basename($restaurantImage)))->toMediaCollection('images');
            }
        }

        $restaurant->load('media');

        $response = [
            'message' => 'Restaurant created successfully',
            'data' => [
                'name' => $restaurant->name,
                'location' => $restaurant->location,
                'phone_number' => $restaurant->phone_number,
                'email' => $restaurant->email,
                'website_link' => $restaurant->website_link,
                'description' => $restaurant->description,
                'status' => $restaurant->status,
                'creator_id' => $restaurant->creator_id,
                'restaurant_category_id' => $restaurant->restaurant_category_id,
                'creator_type' => $restaurant->creator_type,
                'images' =>$restaurant->getMedia('images')->map->getUrl(),
            ],
        ];

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($restaurantId)
    {
          // Fetch all restaurants with related data
          $singleRestaurant = Restaurant::with('media', 'restaurant_category')
          ->where('id', $restaurantId)
          ->where('status', 'active')
          ->first();
  
          // Check if any restaurants were found
          if (!$singleRestaurant) {
              return response()->json(["message" => "No Restaurants Found"], 400);
          }
  
          // Prepare response data
          $response = [
                  'id' => $singleRestaurant->id,
                  'name' => $singleRestaurant->name,
                  'location' => $singleRestaurant->location,
                  'phone_number' => $singleRestaurant->phone_number,
                  'email' => $singleRestaurant->email,
                  'website_link' => $singleRestaurant->website_link,
                  'description' => $singleRestaurant->description,
                  'creator_id' => $singleRestaurant->creator_id,
                  'creator_type' => $singleRestaurant->creator_type,
                  'status' => $singleRestaurant->status,
                  'restaurant_category_id' => $singleRestaurant->restaurant_category_id,
                  'restaurant_category' => $singleRestaurant->restaurant_category,
                  'images' => $singleRestaurant->getMedia('images')->map->getUrl(), // Extract only the image URLs
              ];
  
          // Return JSON response
          return response()->json([
              'message' => 'Restaurant data retrieved successfully',
              'restaurants' => $response,
          ], 200);
    }

    public function searchRestaurants(Request $request)
    {
        // Get search parameters from the request
        $name = $request->input('name');
        $location = $request->input('location');
    
        // Check if name parameter is provided
        if (!$name) {
            return response()->json(["message" => "Name is required"], 400);
        }
    
        // Build the base query to search for restaurants
        $query = Restaurant::query();
    
        // Add name search condition
        $query->where('name', 'like', '%' . $name . '%');
    
        // If location is provided, add it as an additional condition
        if ($location) {
            $query->where('location', 'like', '%' . $location . '%');
        }
    
        // Fetch the results with related data
        $restaurants = $query->with('media', 'restaurant_category')->get();
    
        // Check if any restaurants were found
        if ($restaurants->isEmpty()) {
            return response()->json(["message" => "No Restaurants Found"], 404);
        }
    
        // Prepare response data
        $response = $restaurants->map(function ($restaurant) {
            return [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'location' => $restaurant->location,
                'phone_number' => $restaurant->phone_number,
                'email' => $restaurant->email,
                'website_link' => $restaurant->website_link,
                'description' => $restaurant->description,
                'creator_id' => $restaurant->creator_id,
                'creator_type' => $restaurant->creator_type,
                'status' => $restaurant->status,
                'restaurant_category_id' => $restaurant->restaurant_category_id,
                'restaurant_category' => $restaurant->restaurant_category,
                'images' => $restaurant->getMedia('images')->map->getUrl(), // Extract only the image URLs
            ];
        });
    
        // Return JSON response
        return response()->json([
            'message' => 'Restaurant data retrieved successfully',
            'restaurants' => $response,
        ], 200);
    }

    
    public function getRestaurantsCreatedByUser(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

         // Retrieve restaurants created by the authenticated user
         $restaurants = Restaurant::where('creator_id', $user->id)
         ->where('creator_type', get_class($user)) // Ensure creator type matches the authenticated user
         ->with('media', 'restaurant_category')
         ->get();

        // Check if the user has created any restaurants
        if ($restaurants->isEmpty()) {
            return response()->json(["message" => "No restaurant has been created by you yet"], 404);
        }

        // Prepare response data
        $response = $restaurants->map(function ($restaurant) {
            return [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'location' => $restaurant->location,
                'phone_number' => $restaurant->phone_number,
                'email' => $restaurant->email,
                'website_link' => $restaurant->website_link,
                'description' => $restaurant->description,
                'creator_id' => $restaurant->creator_id,
                'creator_type' => $restaurant->creator_type,
                'status' => $restaurant->status,
                'restaurant_category_id' => $restaurant->restaurant_category_id,
                'restaurant_category' => $restaurant->restaurant_category,
                'images' => $restaurant->getMedia('images')->map->getUrl(), // Extract only the image URLs
            ];
        });

        // Return JSON response
        return response()->json([
            'message' => 'Restaurants created by you retrieved successfully',
            'restaurants' => $response,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // Validate the request
         $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
            'website_link' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            // 'status' => 'required|in:active,inactive',
            'restaurant_category_id' => 'required|exists:restaurant_categories,id',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Find the restaurant by ID and ensure it belongs to the authenticated user
        $restaurant = Restaurant::where('id', $id)
            ->where('creator_id', $user->id)
            ->where('creator_type', get_class($user))
            ->first();

        // Check if the restaurant exists
        if (!$restaurant) {
            return response()->json(["message" => "Restaurant not found or you are not authorized to update it"], 404);
        }

        // Update the restaurant details
        $restaurant->update([
            'name' => $request->name,
            'location' => $request->location,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'website_link' => $request->website_link,
            'description' => $request->description,
            'status' => $request->status,
            'restaurant_category_id' => $request->restaurant_category_id,
        ]);

        // Handle images if provided
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $restaurantImage = $this->manualStoreMedia($image)['name'];
                $restaurant->addMedia(storage_path('tmp/uploads/' . basename($restaurantImage)))->toMediaCollection('images');
            }
        }

        // Return updated restaurant data
        return response()->json([
            'message' => 'Restaurant updated successfully',
            'restaurant' => $restaurant
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $user = Auth::user();

        $restaurant = Restaurant::where('id', $id)
        ->where('creator_id', $user->id)
        ->where('creator_type', get_class($user))
        ->first();

        if(!$restaurant) {
            return response()->json([
               'message' => 'Restaurant not found'
           ], 400);
        }

        $restaurant->delete();

        return response()->json([
            'message' => 'Restaurant deleted successfully'
        ], 200);
    }
}
