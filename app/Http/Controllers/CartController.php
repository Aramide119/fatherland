<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\CartItem;
use App\Services\Paypal;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserTransaction;
use App\Models\BillingInformation;
use Illuminate\Support\Facades\DB;
use App\Models\CheckoutTransaction;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request, $productId)
    {
        
        if (!Auth::check()) {
            return response()->json(['message' => 'You need to be logged in to add products to your cart!'], 401);
        }
    
        $user = Auth::user();
        $quantity = $request->input('quantity', 1);
        $selectedColor = $request->input('color_id');
        $selectedSize = $request->input('size_id');
    
        // Find the product
        $product = Product::find($productId);
    
        if (!$product) {
            return response()->json(['message' => 'Product not found!'], 404);
        }
    
        // Check if the product has colors
        if ($product->colors()->exists()) {
            if ($selectedColor) {
                // Validate that the selected color exists for this product
                $colorExists = DB::table('colors')
                    ->join('color_product', 'colors.id', '=', 'color_product.color_id')
                    ->where('color_product.product_id', $productId)
                    ->where('colors.id', $selectedColor)
                    ->exists();
    
                if (!$colorExists) {
                    return response()->json(['message' => 'Selected color is not available for this product!'], 400);
                }
            } else {
                return response()->json(['message' => 'A color must be selected for this product!'], 400);
            }
        }
    
        // Check if the product has sizes
        if ($product->sizes()->exists()) {
            if ($selectedSize) {
                // Validate that the selected size exists for this product
                $sizeExists = DB::table('sizes')
                    ->join('product_size', 'sizes.id', '=', 'product_size.size_id')
                    ->where('product_size.product_id', $productId)
                    ->where('sizes.id', $selectedSize)
                    ->exists();
    
                if (!$sizeExists) {
                    return response()->json(['message' => 'Selected size is not available for this product!'], 400);
                }
            } else {
                return response()->json(['message' => 'A size must be selected for this product!'], 400);
            }
        }
    
        // Get or create the cart
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
    
        // Check if the same product with the same color and size is already in the cart
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->where('color_id', $selectedColor)
                            ->where('size_id', $selectedSize)
                            ->first();
                            
    
        if ($cartItem) {
            // Return a message if the product is already in the cart
            return response()->json(['message' => 'Product already in cart!'], 400);
        } else {
            // Add the product with the selected color and size to the cart
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'color_id' => $selectedColor,
                'size_id' => $selectedSize,
                'quantity' => $quantity,
            ]);
        }
    
        return response()->json(['message' => 'Product added to cart!'], 200);
    }
    




    public function getCart()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'You need to be logged in to view your cart!'], 401);
        }

        $cart = Cart::where('user_id', Auth::id())
            ->with([
                'items.product.category', 
                'items.product.sub_category', 
                'items.product.media', 
                'items.product.colors', 
                'items.product.sizes', 
                'items.product.reviews.user'
            ])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Your cart is empty!',
                'cart' => []
            ], 200);
        }

        $response = $cart->items->map(function ($item) {
            $product = $item->product;
            $selectedColor = $product->colors->find($item->color_id);
            $selectedSize = $product->sizes->find($item->size_id);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'quantity' => $item->quantity,
                'category' => $product->category ? $product->category->name : null,
                'sub_category' => $product->sub_category ? $product->sub_category->name : null,
                'images' => $product->getMedia('images')->map->getUrl(),
                'color' => $selectedColor ? $selectedColor->name : null,
                'size' => $selectedSize ? $selectedSize->name : null,
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
            'message' => 'Cart retrieved successfully!',
            'cart' => $response
        ], 200);
    }



    public function removeItem(Request $request, $itemId)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'You need to be logged in to modify your cart!'], 401);
        }

        // Retrieve the user's cart
        $cart = Cart::where('user_id', Auth::id())->first();

        // Check if the cart exists
        if (!$cart) {
            return response()->json(['message' => 'Cart not found.'], 404);
        }

        // Retrieve the cart item
        $cartItem = CartItem::where('id', $itemId)
                            ->where('cart_id', $cart->id)
                            ->first();

        // Check if the cart item exists
        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }

        // Delete the cart item
        $cartItem->delete();

        return response()->json(['message' => 'Cart item removed successfully.'], 200);

    }


    public function checkout(Request $request)
    {
        $user = $request->user();

        // Retrieve the cart for the authenticated user
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();

        // Check if the cart exists and is not empty
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 404);
        }

        $referenceId = Str::uuid()->toString();

        if ($request->has('billing.is_default')) {
            $request->merge(['billing.is_default' => filter_var($request->input('billing.is_default'), FILTER_VALIDATE_BOOLEAN)]);
        }

       // Validate the request
        $request->validate([
            'total_amount' => 'required|numeric',
            'billing_id' => 'nullable|exists:billing_information,id', // Validate existing billing ID
            'billing.first_name' => 'required_without:billing_id|string|max:255',
            'billing.last_name' => 'required_without:billing_id|string|max:255',
            'billing.email' => 'required_without:billing_id|email|max:255',
            'billing.country' => 'required_without:billing_id|string|max:255',
            'billing.state' => 'required_without:billing_id|string|max:255',
            'billing.street_address' => 'required_without:billing_id|string|max:255',
            'billing.zip_code' => 'required_without:billing_id|string|max:255',
            'billing.contact_number' => 'required_without:billing_id|string|max:255',
            'billing.is_default' => 'sometimes|boolean',
        ]);

            // Calculate the total amount from the cart items
        $calculatedTotalAmount = 0;

        foreach ($cart->items as $item) {
            $calculatedTotalAmount += $item->product->price * $item->quantity;
        }

        // Compare the calculated total with the provided total_amount
        if ($calculatedTotalAmount != $request->input('total_amount')) {
            return response()->json(['message' => 'The total amount is incorrect. Please try again.'], 400);
        }


        // Check if the user wants to use existing billing information
        if ($request->filled('billing_id')) {
            $billingInfo = BillingInformation::where('user_id', $user->id)->where('id', $request->billing_id)->first();
        } else {
            // Save new billing information
            $billingInfo = BillingInformation::create([
                'user_id' => $user->id,
                'first_name' => $request->billing['first_name'],
                'last_name' => $request->billing['last_name'],
                'email' => $request->billing['email'],
                'country' => $request->billing['country'],
                'state' => $request->billing['state'],
                'street_address' => $request->billing['street_address'],
                'zip_code' => $request->billing['zip_code'],
                'contact_number' => $request->billing['contact_number'],
                'is_default' => $request->billing['is_default'] ?? false,
            ]);
        }

        $totalAmount = $request->input('total_amount');

        // Delete any pending transactions that are not completed
        $pendingTransactions = CheckoutTransaction::where('user_id', $user->id)
            ->where('status', '<>', 'COMPLETED')
            ->get();

        foreach ($pendingTransactions as $transaction) {
            $transaction->delete();
        }

         // Create a new UserTransaction
         $paypal = new Paypal;
         $response = $paypal->initiatePaypalCheckout($referenceId, $totalAmount);

        // Create a new Payment record
        $payment = new Payment;
        $payment->user_id = $user->id;
        $payment->amount = $totalAmount;
        $payment->reference_id = $response['id'];
        $payment->status = 'pending';
        $payment->save();



        $checkoutTransaction = new CheckoutTransaction;
        $checkoutTransaction->user_id = $user->id;
        $checkoutTransaction->paypal_checkout_id = $response['id'];
        $checkoutTransaction->status = $response['status'];
        $checkoutTransaction->billing_information_id = $billingInfo->id;
        $checkoutTransaction->save();

        return response()->json(["redirectUrl" => $response['links'][1]['href']]);
    }

    function checkoutPaymentSuccess(string $referenceId){

        $paypal = new Paypal;

        $response = $paypal->confirmPayPalCheckout($referenceId);
        // dd($response);

        if ($response && isset($response['status'])) {
            // Update the transaction details if found
            $checkoutTransactionDetails = CheckoutTransaction::where('paypal_checkout_id', $referenceId)->first();
            if ($checkoutTransactionDetails) {
                $checkoutTransactionDetails->update([
                    "status" => $response['status'],
                ]);
            }
            return $response;
        } else {
            // Handle the case where the checkout response is invalid
            return response()->json(['error' => 'Invalid checkout response'], 400);
        }

    }

    public function cartPaymentSuccess(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Retrieve the user and cart
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty!'], 404);
        }

        // Initialize PayPal and validate the payment success
        $paypal = new Paypal;
        $response = $this->checkoutPaymentSuccess($request->reference_id);

        if ($response && isset($response['status']) && $response['status'] === 'COMPLETED') {
            // Retrieve the transaction details
            $checkoutTransactionDetails = CheckoutTransaction::where('paypal_checkout_id', $request->reference_id)->first();

            if (!$checkoutTransactionDetails) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }

           // Generate a unique order number
        $stringPart = strtoupper(substr($user->name, 0, 2));
        $orderNumber = strtoupper(date('dmy') . $stringPart . uniqid());

            // Create a new order for the user
            $order = Order::create([
                'user_id' => $user->id,
                'billing_information_id' => $checkoutTransactionDetails->billing_information_id,
                'status' => 'Processing',
                'order_number' => $orderNumber,
                'seller_status'=>'Pending',
            ]);

            $totalAmount = 0;

            // Add items from cart to order
            foreach ($cart->items as $cartItem) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'delivery_amount'=>$cartItem->product->delivery_amount,
                    'color_id' => $cartItem->color_id,
                    'size_id' => $cartItem->size_id,
                    'seller_status'=>'Pending',
                ]);

                 // Subtract the quantity from the stock
                $cartItem->product->decrement('stock', $cartItem->quantity);

                $totalAmount += $cartItem->quantity * $cartItem->product->price;
            }

            // Update the payment record to link it with the order
            $payment = Payment::where('reference_id', $request->reference_id)->first();
            if ($payment) {
                $payment->order_id = $order->id;
                $payment->status = 'Successful';
                $payment->save();
            }

            // Clear the user's cart
            $cart->items()->delete();

            return response()->json(['message' => 'Order created successfully!'], 200);
        } else {
            return response()->json(['error' => 'Invalid checkout response'], 400);
        }
    }




}
