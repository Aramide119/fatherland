<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FavouriteController extends Controller
{
    public function index()
    {
        // Ensure the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $favorites = $user->favorites()
            ->with([
                'media',
                'category',
                'sub_category',
                'colors',
                'sizes',
                'reviews'
            ])->get();

            $response = $favorites->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'category' => $product->category ? $product->category->name : null,
                    'sub_category' => $product->sub_category ? $product->sub_category->name : null,
                    'images' => $product->getMedia('images')->map->getUrl(), 
                    'colors' => $product->colors->pluck('name'),
                    'sizes' => $product->sizes->pluck('name'), 
                    'reviews' => $product->reviews->map(function ($review) {
                        return [
                            'user_id' => $review->user_id,
                            'content' => $review->content,
                            'rating' => $review->rating,
                            'created_at' => $review->created_at->toDateTimeString()
                        ];
                    }),
                ];
            });

            return response()->json(['favorites' => $response], 200);
        } else {
            return response()->json(['message' => 'You need to be logged in to view favorite products!'], 401);
        }
    }

    public function store(Request $request, $productId)
    {
        try {
            // Find the product by ID or throw an exception
            $product = Product::findOrFail($productId);

            // Ensure the user is authenticated
            if (Auth::check()) {
                $user = Auth::user();

                // Attach the product to the user's favorites
                if (!$user->favorites()->where('product_id', $product->id)->exists()) {
                    $user->favorites()->attach($product->id);

                    return response()->json(['message' => 'Product added to favorites!'], 200);
                } else {
                    return response()->json(['message' => 'Product is already in your favorites!'], 200);
                }
            } else {
                return response()->json(['message' => 'You need to be logged in to add favorite products!'], 401);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found!'], 404);
        }
    }


    public function destroy($productId)
    {
        try {
            // Find the product by ID or throw an exception
            $product = Product::findOrFail($productId);

            // Ensure the user is authenticated
            if (Auth::check()) {
                $user = Auth::user();

                // Check if the product exists in the user's favorites
                if ($user->favorites()->where('product_id', $product->id)->exists()) {
                    // Detach the product from the user's favorites
                    $user->favorites()->detach($product->id);

                    return response()->json(['message' => 'Product removed from favorites!'], 200);
                } else {
                    return response()->json(['message' => 'Product not found in favorites!'], 404);
                }
            } else {
                return response()->json(['message' => 'You need to be logged in to remove favorite products!'], 401);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found!'], 404);
        }
    }


   
   
   
}
