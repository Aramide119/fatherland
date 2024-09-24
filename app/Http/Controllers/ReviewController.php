<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{

    public function index(Product $product)
    {
        // Load reviews with the user who created each review
        $reviews = $product->reviews()->with('user')->get();

        // Transform the reviews
        $response = $reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'user_id' => $review->user_id,
                'user_name' => $review->user ? $review->user->name : 'Anonymous',
                'content' => $review->content,
                'rating' => $review->rating,
                'created_at' => $review->created_at->toDateTimeString(),
                'updated_at' => $review->updated_at->toDateTimeString(),
            ];
        });

        return response()->json(['reviews' => $response], 200);
    }

    public function store(Request $request, $productId)
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized, kindly log in before performing this action'], 401);
        }

        // Find the product by ID, or return a 404 response if it doesn't exist
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Validate the request data
        $request->validate([
            'rating' => 'integer|min:1|max:5',
        ]);

        // Create the review
        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'content' => $request->content,
            'rating' => $request->rating,
        ]);

        // Prepare the response
        $response = [
            'message' => 'Review Sent',
            'data' => $review
        ];

        return response()->json($response, 200);
    }

    public function destroy(Review $review)
    {
        // Check if the authenticated user is the owner of the review
        if ($review->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Delete the review
        $review->delete();

        // Prepare the response
        $response = [
            'message' => 'Review deleted successfully',
        ];

        return response()->json($response, 200);
    }

}
