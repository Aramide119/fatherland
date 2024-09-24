<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;
use App\Models\OrderItem;
use App\Traits\ImageUpload;
use App\Traits\VideoUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Color;

class SellerController extends Controller
{
    use ImageUpload;
    use VideoUpload;

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->name || !$user->email || !$user->phone_number) {
            return response()->json(['message' => 'User profile information is incomplete.'], 400);
        }

        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_address_country' => 'required|string|max:255',
            'business_address_state' => 'required|string|max:255',
            'business_address_city' => 'required|string|max:255',
            'business_address_postal_code' => 'required|string|max:255',
            'business_registration_number' => 'nullable|string|max:255',
            'business_license' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'identification_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $seller = new Seller();
        $seller->user_id = $user->id;
        $seller->name = $user->name;
        $seller->email = $user->email;
        $seller->phone_number = $user->phone_number;
        $seller->business_name = $request->business_name;
        $seller->business_address_country = $request->business_address_country;
        $seller->business_address_state = $request->business_address_state;
        $seller->business_address_city = $request->business_address_city;
        $seller->business_address_postal_code = $request->business_address_postal_code;
        $seller->business_registration_number = $request->business_registration_number;

        if ($request->hasFile('business_license')) {

                $sellerImage = $this->manualStoreMedia($request->file('business_license'))['name'];
                $seller->addMedia(storage_path('tmp/uploads/' . basename($sellerImage)))->toMediaCollection('business_license');
        }

        if ($request->hasFile('identification_document')) {
            $identification_document = $this->manualStoreMedia($request->file('identification_document'))['name'];
            $seller->addMedia(storage_path('tmp/uploads/' . basename($identification_document)))->toMediaCollection('identification_document');
        }

        $seller->save();


        $seller->load('media');

        $response = [
            'message' => "Profile Created Successfully",
            'data' => [
                'business_name' => $seller->business_name,
                'business_address_country' => $seller->business_address_country,
                'business_address_state' => $seller->business_address_state,
                'business_address_city' => $seller->business_address_city,
                'business_address_postal_code' => $seller->business_address_postal_code,
                'business_registration_number' => $seller->business_registration_number,
                'business_license' =>  $seller->getMedia('business_license')->map->getUrl(),
                'identification_document' => $seller->getMedia('identification_document')->map->getUrl(),
            ],
        ];

        return response()->json($response, 200);
    }



    public function update(Request $request, Seller $seller)
    {
        $user = Auth::user();

        if ($seller->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $fields = [
            'business_name' => 'string|max:255',
            'business_address_country' => 'string|max:255',
            'business_address_state' => 'string|max:255',
            'business_address_city' => 'string|max:255',
            'business_address_postal_code' => 'string|max:255',
            'business_registration_number' => 'nullable|string|max:255',
            'payment_method' => 'string|max:255',
            'business_license' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'identification_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ];

        $fieldToUpdate = array_keys($request->all());

        $request->validate([
            $fieldToUpdate[0] => $fields[$fieldToUpdate[0]],
        ]);


        if ($request->hasFile('business_license')) {
            $seller->clearMediaCollection('business_license');
            $sellerImage = $this->manualStoreMedia($request->file('business_license'))['name'];
            $seller->addMedia(storage_path('tmp/uploads/' . basename($sellerImage)))->toMediaCollection('business_license');
        } elseif ($request->hasFile('identification_document')) {
            $seller->clearMediaCollection('identification_document');
            $identification_document = $this->manualStoreMedia($request->file('identification_document'))['name'];
            $seller->addMedia(storage_path('tmp/uploads/' . basename($identification_document)))->toMediaCollection('identification_document');
        } else {
            $seller->update($request->only($fieldToUpdate[0]));
        }

        $seller->save();
        $seller->load('media');

        $response = [
            'message' => "Seller profile updated successfully",
            'data' => [
                'business_name' => $seller->business_name,
                'business_address_country' => $seller->business_address_country,
                'business_address_state' => $seller->business_address_state,
                'business_address_city' => $seller->business_address_city,
                'business_address_postal_code' => $seller->business_address_postal_code,
                'business_registration_number' => $seller->business_registration_number,
                'business_license' => $seller->getMedia('business_license')->map->getUrl(),
                'identification_document' => $seller->getMedia('identification_document')->map->getUrl(),
            ],
        ];

        return response()->json($response, 200);
    }


    public function destroy(Seller $seller)
    {
        $user = Auth::user();

        if ($seller->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        foreach ($seller->products as $product) {
            $product->delete();
        }

        $seller->delete();

        return response()->json(['message' => 'Seller and all related products deleted successfully'], 200);
    }


    public function UploadProducts(Request $request)
    {
        $user = Auth::user();

        $seller = Seller::where('user_id', $user->id)->first();

        if (!$seller) {
            return response()->json(['message' => 'Seller profile not found for this user.'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'delivery_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'sub_category_id' => 'required|exists:product_sub_categories,id',
            'specification' => 'nullable|string',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:sizes,id',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:colors,id',
            'new_colors' => 'nullable|array', 
            'new_colors.*' => 'string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpg,jpeg,png|max:2048',
            'stock'=>'nullable|numeric|min:0',
        ]);

        $product = new Product($request->only([
            'name',
            'price',
            'description',
            'delivery_amount',
            'discount',
            'category_id',
            'sub_category_id',
            'specification',
            'stock'
        ]));
        $product->seller_id = $seller->id;
        $product->save();

        if ($request->has('sizes')) {
            foreach ($request->input('sizes') as $sizeId) {
                $product->sizes()->attach($sizeId);
            }
        }

        if ($request->has('colors')) {
            foreach ($request->input('colors') as $colorId) {
                $product->colors()->attach($colorId);

            }
        }
        if ($request->filled('new_colors')) {
            foreach ($request->input('new_colors') as $newColorName) {
                // Check if the color already exists
                $existingColor = Color::where('name', $newColorName)->first();
                if (!$existingColor) {
                    // Create the new color if it doesn't exist
                    $color = Color::create(['name' => $newColorName]);
                    $product->colors()->attach($color->id);
                } else {
                    // Attach the existing color to the product
                    $product->colors()->attach($existingColor->id);
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $productImage = $this->manualStoreMedia($image)['name'];
                $product->addMedia(storage_path('tmp/uploads/' . basename($productImage)))->toMediaCollection('images');
            }
        }

        $product->load(['sizes', 'colors', 'media']);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    public function editProduct(Request $request, $productId)
    {
        $user = Auth::user();

        $seller = Seller::where('user_id', $user->id)->first();

        if (!$seller) {
            return response()->json(['message' => 'Seller profile not found for this user.'], 404);
        }

        // Find the product
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found!'], 404);
        }

        // Check if the authenticated seller is the owner of the product
        if ($product->seller_id !== $seller->id) {
            return response()->json(['message' => 'You do not have permission to edit this product!'], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'description' => 'sometimes|string',
            'delivery_amount' => 'sometimes|numeric|min:0',
            'discount' => 'nullable|string|max:255',
            'category_id' => 'sometimes|exists:product_categories,id',
            'sub_category_id' => 'sometimes|exists:product_sub_categories,id',
            'specification' => 'nullable|string',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:sizes,id',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:colors,id',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpg,jpeg,png|max:2048',
            'stock' => 'sometimes|numeric|min:0',
        ]);

        // Update product with only the fields provided
        $product->update($request->only([
            'name',
            'price',
            'description',
            'delivery_amount',
            'discount',
            'category_id',
            'sub_category_id',
            'specification',
            'stock'
        ]));

        if ($request->has('sizes')) {
            $product->sizes()->sync($request->input('sizes'));
        }

        if ($request->has('colors')) {
            $product->colors()->sync($request->input('colors'));
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                if (!$image->isValid()) {
                    return response()->json(['message' => "Image {$key} is not a valid file."], 400);
                }

                $extension = $image->getClientOriginalExtension();
                if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    return response()->json(['message' => "Image {$key} must be a file of type: jpg, jpeg, png."], 400);
                }

                // Process the valid image
                $productImage = $this->manualStoreMedia($image)['name'];
                $product->addMedia(storage_path('tmp/uploads/' . basename($productImage)))->toMediaCollection('images');
            }
        }


        $product->load(['sizes', 'colors', 'media']);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product
        ], 200);
    }

    public function deleteProduct($productId)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'You need to be logged in to delete a product!'], 401);
        }

        $seller = Auth::user();

        // Find the product
        $product = Product::find($productId);
        // dd($product);

        if (!$product) {
            return response()->json(['message' => 'Product not found!'], 404);
        }

        // Check if the authenticated seller is the owner of the product
        if ($product->seller_id !== $seller->id) {
            return response()->json(['message' => 'You do not have permission to delete this product!'], 403);
        }

        // Delete the product
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully!'], 200);
    }



    public function getSellerProducts($sellerId)
    {
        try {
            $seller = Seller::findOrFail($sellerId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Seller not found.'
            ], 404);
        }

        $sellersProducts = Product::where('seller_id', $seller->id)
            ->with(['category', 'sub_category', 'sizes', 'colors', 'media'])
            ->get();

        if ($sellersProducts->isEmpty()) {
            return response()->json([
                'message' => 'No products found for this seller.',
                'products' => []
            ], 200);
        }

        $response = $sellersProducts->map(function ($product) {
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
                        'user_name' => $review->user ? $review->user->name : 'Anonymous',
                        'content' => $review->content,
                        'rating' => $review->rating,
                        'created_at' => $review->created_at->toDateTimeString(),
                    ];
                }),
            ];
        });

        return response()->json([
            'message' => 'Products retrieved successfully!',
            'seller' => $seller->name,
            'products' => $response
        ], 200);
    }

    public function getSellerOrders($sellerId)
    {
        try {
            $seller = Seller::findOrFail($sellerId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Seller not found.'
            ], 404);
        }

        $productIds = $seller->products->pluck('id');

        $orders = Order::whereHas('orderItems', function ($query) use ($productIds) {
            $query->whereIn('product_id', $productIds);
        })->with(['orderItems.product', 'orderItems.color', 'orderItems.size'])->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'No orders found for this seller\'s products.',
                'orders' => []
            ], 200);
        }

        $orders = $orders->map(function ($order) {
            $order->orderItems->each(function ($item) {
                $item->product->images = $item->product->getMedia('images')->map->getUrl();
                unset($item->product->media);
            });
            return $order;
        });

        $formattedOrders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'status' => $order->status,
                'order_items' => $order->orderItems->map(function ($item) {
                    return [
                        'product' => [
                            'id' => $item->product->id,
                            'name' => $item->product->name,
                            'description' => $item->product->description,
                            'price' => $item->product->price,
                            'category' => $item->product->category ? $item->product->category->name : null,
                            'sub_category' => $item->product->sub_category ? $item->product->sub_category->name : null,
                            'images' => $item->product->getMedia('images')->map->getUrl(),
                            'color' => $item->color ? $item->color->name : null,
                            'size' => $item->size ? $item->size->name : null,
                        ],
                        'quantity' => $item->quantity,
                        'seller_status' => $item->seller_status,
                    ];
                })
            ];
        });


        return response()->json([
            'message' => 'Orders retrieved successfully!',
            'orders' => $formattedOrders
        ], 200);
    }



    public function updateSellerOrderStatus(Request $request, $orderItemId)
    {

        $request->validate([
            'seller_status' => 'required|string|in:shipped,delivered,returned,canceled'
        ]);

        try {
            $orderItem = OrderItem::findOrFail($orderItemId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Order item not found.'
            ], 404);
        }

        $seller = Auth::user();

        if ($orderItem->product->seller_id !== $seller->id) {
            return response()->json([
                'message' => 'You do not have permission to update this order item.'
            ], 403);
        }

        $orderItem->seller_status = $request->input('seller_status');

        if ($orderItem->save()) {
            return response()->json([
                'message' => 'Seller status updated successfully!'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to update seller status.'
            ], 500);
        }
    }









}
