<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DynastyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\PromotePostSubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('v1/register', [AuthController::class, 'register']);
Route::post('v1/login', [AuthController::class, 'login']);
Route::post('v1/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('v1/reset-password', [ForgotPasswordController::class, 'sendResetEmail']);
Route::post('v1/reset', [ForgotPasswordController::class, 'reset']);
Route::post('v1/resetPassword', [ForgotPasswordController::class, 'passwordReset']);



Route::prefix('/v1')->middleware('auth:api')->group(function () {
    //get user profile
    Route::get('/profile', [ProfileController::class, 'index']);
    //Update user account status
    Route::post('/update-account-status', [ProfileController::class, 'updateStatus']);
    //logout user
    Route::post('/logout', [AuthController::class, 'logout']);
    //get all users
    Route::get('/users', [FriendController::class, 'getUsers']);
    //add friends
    Route::post('/friends/{friend}', [FriendController::class, 'sendRequest']);
    //accept request
    Route::post('/friendrequest/{senderId}', [FriendController::class, 'acceptRequest']);
    //delete friend request
    Route::post('/friends/{senderId}/decline', [FriendController::class, 'declineRequest']);
    //get all friends list
    Route::get('/friendslist', [FriendController::class, 'index']);
    // get a user
    Route::get('/user/{userId}', [FriendController::class, 'getUser']);
    // get list of friend request
    Route::get('/requestlist', [FriendController::class, 'listFriendRequest']);
    // get mutual friends
    Route::get('/users/{otherUserId}/mutual-friends', [FriendController::class, 'getMutualFriends']);
    // cancel friend request
    Route::post('/friend-request/{senderId}/{recieverId}', [FriendController::class, 'cancelRequest']);
    // create post
    Route::post('/posts', [PostController::class, 'store']);
    //  generate slug request
    Route::get('/posts/{slug}', [PostController::class, 'getPostBySlug']);
    // Edit Post
    Route::post('/edit-posts/{postId}', [PostController::class, 'update']);
    // Delete  Post
    Route::post('/delete-posts/{postId}', [PostController::class, 'deletePost']);
    // Fetch Post
    Route::get('/get-posts', [PostController::class, 'fetchPosts']);
    // Fetch single post
    Route::get('/post/{postId}', [PostController::class, 'fetchSinglePost']);
    // Repost Post
    Route::post('/repost/{post}', [PostController::class, 'repostPost']);
    // update users profile
    Route::post('/users/{userId}/profile', [ProfileController::class, 'updateProfile']);
    // add profile picture
    Route::post('/profile-picture', [ProfileController::class, 'addProfilePicture']);
    // add cover picture
    Route::post('/cover-picture', [ProfileController::class, 'addCoverPicture']);
    // delete user account
    Route::post('delete-account', [ProfileController::class, 'deleteUser']);
    //get users pending request
    Route::get('all-requests', [ProfileController::class, 'usersRequest']);
    //cancel family request
    Route::post('user/cancel-request/{familyId}', [ProfileController::class, 'cancelRequest']);
    // change password
    Route::post('/change-password', [ProfileController::class, 'changePassword']);
    // update account
    Route::post('/account-settings', [ProfileController::class, 'updateAccount']);
    // Like post
    Route::post('/posts/{id}/like', [LikeController::class, 'like']);
    // Unlike post
    Route::post('/posts/{id}/unlike', [LikeController::class, 'unlike']);
    // Get Likes
    Route::get('/likes/{postId}', [LikeController::class, 'getLikes']);
    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    // Comment Reply
    Route::post('/comments/{comment}/replies', [CommentController::class, 'reply']);
    // get all comment
    Route::get('/comments/{postId}', [CommentController::class, 'index']);
    // like user comment
    Route::post('/comments/{commentId}/like', [CommentController::class, 'likeComment']);
    // Unlike User Comment
    Route::post('/comments/{commentId}/unlike', [CommentController::class, 'unlikeComment']);
    // Chat
    Route::post('/chats', [ChatMessageController::class, 'sendMessage']);
    // Get all chat history
    Route::get('/getChats', [ChatMessageController::class, 'getChatHistory']);
    // Fetch Chat between each users
    Route::get('/getUsersChat/{chatId}', [ChatMessageController::class, 'getChatMessages']);
    // Create Families
    Route::post('/create-families', [FamilyController::class, 'store']);
    // Delete Family
    Route::post('/delete-family/{familyId}', [FamilyController::class, 'destroy']);
    //  get the link for the family
    Route::get('/family/{uuid}', [FamilyController::class, 'show'])->name('family.show');
    // Join family
    Route::post('/family/{familyId}/join', [FamilyController::class, 'familyRequest']);
    //  Join Family Using Invite Link
    Route::get('/family/invite/{token}', [FamilyController::class, 'inviteLink'])->name('family.invite');
    // Leave Family
    Route::post('/family/{familyId}/leave', [FamilyController::class, 'leaveFamily']);
    // Get all Families
    Route::get('/all-families', [FamilyController::class, 'allFamilies']);
    // get single family
    Route::get('/get-family/{familyId}', [FamilyController::class, 'getFamily']);
    // Get Individual Families Created by A user
    Route::get('/manage-families', [FamilyController::class, 'createdFamilies']);
    // Get List Of Family Members
    Route::get('/family-members/{familyId}', [FamilyController::class, 'getFamilyMembers']);
    // Get Families User Joined
    Route::get('/joined-family', [FamilyController::class, 'familyJoined']);
    // get suggested families
    Route::get('/suggestedfamily', [FamilyController::class, 'suggestedFamily']);
    // update family status
    Route::post('/update-family-status/{familyId}', [FamilyController::class, 'updateFamilyStatus']);
    // Delete family members
    Route::post('/delete-family-member/{userId}/{familyId}', [FamilyController::class, 'deleteFamilyMember']);
    // Edit Family
    Route::post('/edit-family/{familyId}', [FamilyController::class, 'editFamily']);
    // Add family profile picture
    Route::post('/family-pics/{familyId}', [FamilyController::class, 'addFamilyProfilePicture']);
    // Add Family Cover Picture
    Route::post('/family-cover-pics/{familyId}', [FamilyController::class, 'addFamilyCoverPicture']);
    // get pending request to join family
    Route::get('/pending-requests', [FamilyController::class, 'PendingRequests']);
    // get pending request to join family
    Route::get('/get-pending-requests/{familyId}', [FamilyController::class, 'getPendingRequests']);
    // Accept pending request
    Route::post('/families/{familyId}/accept-request/{userId}', [FamilyController::class, 'acceptRequest']);
    // Decline pending request
    Route::post('/families/{familyId}/decline-request/{userId}', [FamilyController::class, 'declineRequest']);
    //Search Family
    Route::get('/search-family', [FamilyController::class, 'searchFamily']);
    //Report a Family
    Route::post('/report-family/{family_id}', [FamilyController::class, 'reportFamily']);
    //Block a Family
    Route::post('/block-family/{id}', [FamilyController::class, 'blockFamily']);
    // popular families
    Route::get('/popular-families', [FamilyController::class, 'getPopularFamilies']);
    // Create Dynasties
    Route::post('/create-dynasty', [DynastyController::class, 'create']);
    //  get the link for the dynasty
    Route::get('/dynasty/{uuid}', [DynastyController::class, 'show'])->name('dynasty.show');
    // Edit Dynasty
    Route::post('/edit-dynasty/{dynastyId}', [DynastyController::class, 'edit']);
    // Add dynasty profile picture
    Route::post('/dynasty-pics/{dynastyId}', [DynastyController::class, 'addDynastyProfilePicture']);
    // Add Dynasty Cover Picture
    Route::post('/dynasty-cover-pics/{dynastyId}', [DynastyController::class, 'addDynastyCoverPicture']);
    // Delete Dynasty
    Route::post('/delete-dynasty/{dynastyId}', [DynastyController::class, 'destroy']);
    // Get all Dynasties
    Route::get('/all-dynasties', [DynastyController::class, 'getAllDynasties']);
    // get single family
    Route::get('/get-dynasty/{dynastyId}', [DynastyController::class, 'getDynasty']);
    // Get Individual Dynasties Created by A user
    Route::get('/manage-dynasties', [DynastyController::class, 'createdDynasties']);
    //  Get all dynasties members
    Route::get('/dynasty-member/{dynastyId}', [DynastyController::class, 'dynastiesMember']);
    // Join Dynasty
    Route::post('/dynasty/{dynastyId}/join', [DynastyController::class, 'joinDynasty']);
    // Leave Dynasty
    Route::post('/dynasty/{dynastyId}/leave', [DynastyController::class, 'leaveDynasty']);
    // Get Dynasty User Joined
    Route::get('/joined-dynasty', [DynastyController::class, 'dynastiesJoined']);
    // get suggested users
    Route::get('/suggested-dynasty', [DynastyController::class, 'suggestedDynasty']);
    //Search Dynasty
    Route::get('/search-dynasty', [DynastyController::class, 'searchDynasty']);
    // Get All Videos
    Route::get('/videos', [PostController::class, 'getVideos']);
    //Get a users post
    Route::get('get-users-post', [PostController::class, 'fetchIndividualPost']);
    //Report a post
    Route::post('/report-post/{post_id}', [PostController::class, 'reportPost']);
    //Block a user
    Route::post('/block-user/{id}', [AuthController::class, 'blockUser']);
    //Get all blocked users
    Route::get('/get-blocked-users', [AuthController::class, 'getBlockedUser']);
    //payment
    Route::post('make-payment', [PaystackController::class, 'store']);
    Route::post('/verify-payment', [PaystackController::class, 'verifyPayment']);
    // Create Events
    Route::post('/create-event', [EventController::class, 'createEvent']);
    // Update Events
    Route::post('/update-event/{eventId}', [EventController::class, 'updateEvent']);
    // delete Events
    Route::post('/delete-event/{eventId}', [EventController::class, 'deleteEvent']);
    //  Fetch All Events
    Route::get('/fetch-event', [EventController::class, 'fetchEvents']);
    //Fetch Single Event
    Route::get('/events/{eventId}', [EventController::class, 'fetchSingleEvent']);
    //  Attend Event
    Route::post('/events/{eventId}/attend', [EventController::class, 'attendEvent']);
    // Leave Event
    Route::post('/events/{eventId}/leave', [EventController::class, 'leaveEvent']);
    //  Upcoming Events
    Route::get('/upcoming-events', [EventController::class, 'upcomingEvents']);
    // Random Events
    Route::get('/random-events', [EventController::class, 'randomEvents']);
      // Get user Created Event
    Route::get('/user-created-events', [EventController::class, 'userCreatedEvents']);
    //  Attending Events
    Route::get('/user-attending-events', [EventController::class, 'userAttendingEvents']);
    //  Attendee Events
    Route::get('/attended-events', [EventController::class, 'attendedEvents']);
    //Report an event
    Route::post('/report-event/{event_id}', [EventController::class, 'reportEvent']);
    // Get Event Order
    Route::get('/get-event-order', [EventController::class, 'getUserEventOrder']);
    // search event
    Route::get('/event/search', [EventController::class, 'searchEvents']);
    // Search by categories
    Route::post('/event/search-categories', [EventController::class, 'searchByCategory']);
    // Get Event Categories
    Route::get('/event/categories', [EventController::class, 'getAllEventCategories']);
    // Fetch News
    Route::get('/all-news', [NewsController::class, 'fetchNews']);
    // Fetch Single News
    Route::get('/news/{newsId}', [NewsController::class, 'fetchSingleNews']);
     // Fetch Random News
     Route::get('random-news', [NewsController::class, 'getRandomNews']);
    //  Fetch Resources
    Route::get('/resources', [ResourceController::class, 'index']);
     // Fetch Random Resources
     Route::get('random-content', [ResourceController::class, 'getRandomContent']);
    // Fetch Single Record
    Route::get('/resources/{resourceId}', [ResourceController::class,'show']);
    // Fetch All Records
    Route::get('/all-records', [ResourceController::class, 'getExploreRecords']);
    // Like News
    Route::post('/news/{newsId}/like', [LikeController::class, 'LikeNews']);
    // News Comments
    Route::post('/news/{news}/comment', [CommentController::class, 'comment']);
    // Reply News Comments
    Route::post('/news-comments/{comment}/reply', [CommentController::class, 'replyComment']);
    //  Fetch all news Comments
    Route::get('/all-comments/{newsId}', [CommentController::class, 'fetchNewsComments']);
    // Promote Post
    Route::post('/promote-post/{postId}', [PromotePostSubscriptionController::class, 'createSubscription']);
    // Promote Post Payment
    Route::post('/make-promote-payment', [PromotePostSubscriptionController::class, 'MakePayment']);
    // Verify Post Payment
    Route::post('/verify-promote-payment', [PromotePostSubscriptionController::class, 'verifyPostPayment']);
    //get users notifications
    Route::get('/user-notification', [NotificationController::class, 'notifications']);

    // Route::post('/paypal/payment', [PaypalController::class, 'createPayment']);
    // Route::get('/paypal/execute-payment', [PaypalController::class, 'executePayment']);

    Route::post('/handle-payment', [PayPalController::class, 'handlePayment']);

    Route::post('/checkout', [PayPalController::class, 'initiateCheckout']);


    // Search all
    Route::get('/search', [SearchController::class, 'search']);

    // Restaurants

    // Get all restaurants
    Route::get('/all-restaurants', [RestaurantController::class, 'index']);
    // Fetch single restaurants
    Route::get('/restaurant/{restaurantId}', [RestaurantController::class, 'show']);
    // Search for restaurant
    Route::get('/search-restaurants', [RestaurantController::class, 'searchRestaurants']);
    // create Restaurant
    Route::post('/create-restaurants', [RestaurantController::class, 'store']);
    // Fetch restaurants created by users
    Route::get('/restaurants/created-by', [RestaurantController::class, 'getRestaurantsCreatedByUser']);
    // update Restaurant
    Route::post('/update-restaurants/{restaurantId}', [RestaurantController::class, 'update']);
    // delete Restaurant
    Route::delete('/delete-restaurants/{restaurantId}', [RestaurantController::class, 'destroy']);

    // Product api
    // Get all products
    Route::get('/all-products', [StoreController::class, 'index']);

    // Reviews
    // Create review
    Route::post('products/{productId}/reviews', [ReviewController::class, 'store']);
    // Delete review
    Route::delete('delete-reviews/{review}', [ReviewController::class, 'destroy']);
    // Get reviews
    Route::get('reviews/{product}', [ReviewController::class, 'index']);

    // Carts
    // Add to cart
    Route::post('cart/{productId}/add', [CartController::class, 'store']);
     // Get Cart
     Route::get('/cart', [CartController::class, 'getCart']);
    //  Delete item from cart
    Route::delete('/cart/item/{itemId}', [CartController::class, 'removeItem']);
    // Checkout
    Route::post('/cart/checkout', [CartController::class, 'checkout']);



    // Favourites
    // Add to favourites
    Route::post('/products/{productId}/favorite', [FavouriteController::class, 'store']);
    // Remove From Favorite
    Route::delete('/products/{productId}/remove-favorite', [FavouriteController::class, 'destroy']);
    // Get Users Favorite Product
    Route::get('favorites', [FavouriteController::class, 'index']);




    // Sellers
    // Create Seller Profile
    Route::post('/add-seller-profile', [SellerController::class, 'store']);
    // Edit Seller Profile
    Route::post('/edit-seller-profile/{seller}', [SellerController::class, 'update']);
    // Delete Seller Profile
    Route::delete('/delete-seller-profile/{seller}', [SellerController::class, 'destroy']);
    // Add Products
    Route::post('/add-products', [SellerController::class, 'UploadProducts']);
    // get form-data
    Route::get('/product-form-data', [ProductController::class, 'getProductFormData']);
    // Get products
    Route::get('/seller/{sellerId}/products', [SellerController::class, 'getSellerProducts']);
    // Add Payment Method
    Route::post('/seller/payment-method', [PaymentMethodController::class, 'store']);
    // Update Payment Method
    Route::post('/seller/update-payment-method/{id}', [PaymentMethodController::class, 'update']);
    // Sellers Order
    Route::get('/seller/order/{sellerId}', [SellerController::class, 'getSellerOrders']);
    // Update Sellers Status
    Route::post('/seller-status/{orderId}', [SellerController::class, 'updateSellerOrderStatus']);
    // Edit Product
    Route::post('/products/{productId}/edit', [SellerController::class, 'editProduct']);
    // Delete product
    Route::delete('/products/{productId}', [SellerController::class, 'deleteProduct']);



    // Membership Card
    Route::get('/user/{id}/details', [ProfileController::class, 'getUserDetails']);
});

Route::patch('v1/payment-success', [PayPalController::class, 'paymentSuccess']);

Route::patch('v1/checkout-success', [PayPalController::class, 'checkoutSuccessDetails']);

Route::patch('v1/event-success/{eventId}/{userId}', [EventController::class, 'eventPaymentSuccess']);
// Checkout Success
Route::patch('v1/cart/checkout-success/{userId}', [CartController::class, 'cartPaymentSuccess']);

Route::get('v1/cancel-payment', [PayPalController::class, 'paymentCancel']); //Optional endPoint

Route::delete('v1/paypal/subscription/cancel', [PayPalController::class, 'cancelSubscription']);

Route::post('v1/paypal/subscription/webhook/callback',[PayPalController::class, 'subscriptionWebhookCallback']);


