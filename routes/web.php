<?php

use App\Http\Controllers\Admin\LogoController;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\CoachingVideoController;

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    echo "Storage linked Successfully";
 });

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    // Route::resource('users', 'UsersController');
    Route::get('users', 'UsersController@index')->name('users.index');
    Route::get('admin/users/create', 'UsersController@create')->name('users.create');
    Route::post('admin/users/store', 'UsersController@store')->name('users.store');
    Route::get('admin/users/{admin}/show', 'UsersController@show')->name('users.show');
    Route::get('admin/users/{admin}/edit', 'UsersController@edit')->name('users.edit');
    Route::post('admin/users/{admin}/update', 'UsersController@update')->name('users.update');
    Route::delete('admin/users/{admin}/destroy', 'UsersController@destroy')->name('users.destroy');


    //// members
    Route::get('members/index', 'MembersController@index')->name('members.index');
    Route::get('members/show/{user}', 'MembersController@show')->name('members.show');
    Route::delete('members/destroy/{users}', 'MembersController@destroy')->name('members.destroy');

     // Advert
     Route::delete('adverts/destroy', 'AdvertController@massDestroy')->name('adverts.massDestroy');
     Route::post('adverts/media', 'AdvertController@storeMedia')->name('adverts.storeMedia');
     Route::post('adverts/ckmedia', 'AdvertController@storeCKEditorImages')->name('adverts.storeCKEditorImages');
     Route::resource('adverts', 'AdvertController');

     // Restaurant
     Route::delete('restaurants/destroy', 'RestaurantController@massDestroy')->name('restaurants.massDestroy');
     Route::post('restaurants/media', 'RestaurantController@storeMedia')->name('restaurants.storeMedia');
     Route::post('restaurants/ckmedia', 'RestaurantController@storeCKEditorImages')->name('restaurants.storeCKEditorImages');
     Route::resource('restaurants', 'RestaurantController');

    // Restaurant Category
    Route::delete('restaurant-categories/destroy', 'RestaurantCategoryController@massDestroy')->name('restaurant-categories.massDestroy');
    Route::resource('restaurant-categories', 'RestaurantCategoryController');

     // Advert Category
     Route::delete('advert-categories/destroy', 'AdvertCategoryController@massDestroy')->name('advert-categories.massDestroy');
     Route::resource('advert-categories', 'AdvertCategoryController');

      // Resource Category
    Route::delete('resource-categories/destroy', 'ResourceCategoryController@massDestroy')->name('resource-categories.massDestroy');
    Route::resource('resource-categories', 'ResourceCategoryController');

    // Resources
    Route::delete('resources/destroy', 'ResourcesController@massDestroy')->name('resources.massDestroy');
    Route::post('resources/media', 'ResourcesController@storeMedia')->name('resources.storeMedia');
    Route::post('resources/ckmedia', 'ResourcesController@storeCKEditorImages')->name('resources.storeCKEditorImages');
    Route::resource('resources', 'ResourcesController');

    // Interests
    Route::delete('interests/destroy', 'InterestController@massDestroy')->name('interests.massDestroy');
    Route::resource('interests', 'InterestController');

   // Travel
   Route::delete('travels/destroy', 'TravelController@massDestroy')->name('travels.massDestroy');
   Route::post('travels/media', 'TravelController@storeMedia')->name('travels.storeMedia');
   Route::post('travels/ckmedia', 'TravelController@storeCKEditorImages')->name('travels.storeCKEditorImages');
   Route::resource('travels', 'TravelController');

   // Travel Order
   Route::delete('travel-orders/destroy', 'TravelOrderController@massDestroy')->name('travel-orders.massDestroy');
   Route::resource('travel-orders', 'TravelOrderController');

   // Product
   Route::delete('products/destroy', 'ProductController@massDestroy')->name('products.massDestroy');
   Route::resource('products', 'ProductController');
   Route::post('products/create', 'ProductController@product')->name('products.product');
   Route::post('products/ckmedia', 'ProductController@storeCKEditorImages')->name('products.storeCKEditorImages');

   // Product Category
   Route::delete('product-categories/destroy', 'ProductCategoryController@massDestroy')->name('product-categories.massDestroy');
   Route::resource('product-categories', 'ProductCategoryController');

   //product orders
   Route::get('product-orders', 'ProductController@getOrder')->name('product.order');
   Route::get('product-orders/show/{id}', 'ProductController@showOrder')->name('product-orders.show');


   // Product Sub Category
   Route::delete('product-sub-categories/destroy', 'ProductSubCategoryController@massDestroy')->name('product-sub-categories.massDestroy');
   Route::resource('product-sub-categories', 'ProductSubCategoryController');

   // Product Variation
   Route::delete('product-variations/destroy', 'ProductVariationController@massDestroy')->name('product-variations.massDestroy');
   Route::resource('product-variations', 'ProductVariationController');

   // Product Rating
   Route::delete('product-ratings/destroy', 'ProductRatingController@massDestroy')->name('product-ratings.massDestroy');
   Route::resource('product-ratings', 'ProductRatingController', ['except' => ['create', 'store', 'edit', 'update']]);

   // Course
   Route::delete('courses/destroy', 'CourseController@massDestroy')->name('courses.massDestroy');
   Route::resource('courses', 'CourseController');
     // Event
    Route::delete('events/destroy', 'EventController@massDestroy')->name('events.massDestroy');
    Route::post('events/media', 'EventController@storeMedia')->name('events.storeMedia');
    Route::post('events/ckmedia', 'EventController@storeCKEditorImages')->name('events.storeCKEditorImages');
    Route::resource('events', 'EventController');

    // Event Category
    Route::delete('event-categories/destroy', 'EventCategoryController@massDestroy')->name('event-categories.massDestroy');
    Route::resource('event-categories', 'EventCategoryController');

   // Event Order
   Route::delete('event-orders/destroy', 'EventOrderController@massDestroy')->name('event-orders.massDestroy');
   Route::resource('event-orders', 'EventOrderController');

   //Event Attendeed
   Route::get('event/attendees', 'EventAttendeeController@index')->name('eventAttendees.index');
   Route::get('event/details', 'EventAttendeeController@fetchEventDetails')->name('event.details');

    //Ticket Type
    Route::get('event/ticket-type', 'TicketTypeController@create')->name('ticketType.create');
   Route::post('event/ticket-type/create','TicketTypeController@store')->name('ticketType.store');

    // Advert Inquiry
   Route::delete('advert-inquiries/destroy', 'AdvertInquiryController@massDestroy')->name('advert-inquiries.massDestroy');
   Route::resource('advert-inquiries', 'AdvertInquiryController');

    // Record
    Route::delete('records/destroy', 'RecordController@massDestroy')->name('records.massDestroy');
    Route::post('records/media', 'RecordController@storeMedia')->name('records.storeMedia');
    Route::post('records/ckmedia', 'RecordController@storeCKEditorImages')->name('records.storeCKEditorImages');
    Route::post('records/parse-csv-import', 'RecordController@parseCsvImport')->name('records.parseCsvImport');
    Route::post('records/process-csv-import', 'RecordController@processCsvImport')->name('records.processCsvImport');
    Route::resource('records', 'RecordController');

    // Content Type
    Route::delete('content-types/destroy', 'ContentTypeController@massDestroy')->name('content-types.massDestroy');
    Route::resource('content-types', 'ContentTypeController');

    // Content Category
    Route::delete('content-categories/destroy', 'ContentCategoryController@massDestroy')->name('content-categories.massDestroy');
    Route::resource('content-categories', 'ContentCategoryController');

    // Content
    Route::delete('contents/destroy', 'ContentController@massDestroy')->name('contents.massDestroy');
    Route::post('contents/media', 'ContentController@storeMedia')->name('contents.storeMedia');
    Route::post('contents/ckmedia', 'ContentController@storeCKEditorImages')->name('contents.storeCKEditorImages');
    Route::resource('contents', 'ContentController');

    // News
    Route::delete('newss/destroy', 'NewsController@massDestroy')->name('newss.massDestroy');
    Route::post('newss/media', 'NewsController@storeMedia')->name('newss.storeMedia');
    Route::post('newss/ckmedia', 'NewsController@storeCKEditorImages')->name('newss.storeCKEditorImages');
    // Route::resource('newss', 'NewsController');
    Route::get('newss/index', 'NewsController@index')->name('newss.index');
    Route::get('newss/create', 'NewsController@create')->name('newss.create');
    Route::post('newss/store', 'NewsController@store')->name('newss.store');
    Route::get('newss/show/{news}', 'NewsController@show')->name('newss.show');
    Route::get('newss/edit/{news}', 'NewsController@edit')->name('newss.edit');
    Route::post('newss/update/{news}', 'NewsController@update')->name('newss.update');
    Route::delete('newss/destroy/{news}', 'NewsController@destroy')->name('newss.destroy');

    // Comments
    Route::get('comments/index', 'CommentController@index')->name('comments.index');
    Route::get('comments/reply/{id}', 'CommentController@create')->name('comments.reply');
    Route::post('comments/store/{replies}', 'CommentController@store')->name('comments.store');
    // Change Comment Status
    Route::put('comments/{id}/change-status', 'CommentController@changeStatus')->name('comments.changeStatus');
    Route::get('comments/show/{id}', 'CommentController@show')->name('comments.show');

    //logo
    Route::resource('settings', 'SettingController');
    // Learning Category
    Route::delete('learning-categories/destroy', 'LearningCategoryController@massDestroy')->name('learning-categories.massDestroy');
    Route::resource('learning-categories', 'LearningCategoryController');

    //Coaches
    Route::resource('coaches', 'CoachController');
    Route::delete('coaches/destroy', 'EventController@massDestroy')->name('coaches.massDestroy');
    Route::post('coaches/media', 'EventController@storeMedia')->name('coaches.storeMedia');
    Route::post('coaches/ckmedia', 'EventController@storeCKEditorImages')->name('coaches.storeCKEditorImages');

    //coaching videos
    Route::resource('coaching-videos', 'CoachingVideoController');
    Route::post('coaching-videos/create', 'CoachingVideoController@coach')->name('coaching-videos.coach');
    Route::delete('coaching-videos/destroy', 'CoachingVideoController@massDestroy')->name('coaching-videos.massDestroy');
    Route::post('coaching-videos/media', 'CoachingVideoController@storeMedia')->name('coaching-videos.storeMedia');
    Route::post('coaching-videos/ckmedia', 'CoachingVideoController@storeCKEditorVideos')->name('coaching-videos.storeCKEditorVideos');


    // Family
    Route::get('reportedFamilies/index', 'ReportFamilyController@index' )->name('reportedFamilies.index');
    Route::get('reportedFamilies/show/{showFamily}', 'ReportFamilyController@show')->name('reportedFamilies.show');
    Route::get('families/index', 'FamilyController@index')->name('families.index');
    Route::get('families/create', 'FamilyController@create')->name('families.create');
    Route::post('families/store', 'FamilyController@store')->name('families.store');

    Route::get('families/{userId}/member', 'FamilyController@showMember')->name('families.show-member');
    Route::get('families/{familyId}/members', 'FamilyController@familyMembers')->name('families.members');
    Route::get('families/{familyId}/requests', 'FamilyController@getPendingJoinRequests')->name('families.request');
    Route::delete('families/{userId}/{familyId}/remove', 'FamilyController@deleteFamilyMember')->name('families.remove');
    Route::post('families/{familyId}/{userId}/accept-request', 'FamilyController@acceptPendingRequests')->name('families.accept-request');
    Route::post('families/{familyId}/{userId}/decline-request', 'FamilyController@declinePendingRequests')->name('families.decline-request');
    // In web.php or your routes file
    Route::patch('families/{familyId}/members/{userId}/toggle-role', 'FamilyController@toggleAdminRole')->name('families.toggle-role');



    Route::get('families/show/{showFamily}', 'FamilyController@show')->name('families.show');
    Route::get('families/edit/{editFamily}', 'FamilyController@edit')->name('families.edit');
    Route::post('families/update/{editFamily}', 'FamilyController@update')->name('families.update');
    Route::delete('families/destroy/{id}', 'FamilyController@destroy')->name('families.destroy');
    Route::post('families/destroy', 'FamilyController@massDestroy')->name('families.massDestroy');
    Route::post('families/media', 'FamiliesController@storeMedia')->name('families.storeMedia');
    Route::post('families/ckmedia', 'FamilyController@storeCKEditorImages')->name('families.storeCKEditorImages');

    // Dynasty
    Route::get('dynasties/index', 'LineageController@index')->name('dynasties.index');
    Route::get('dynasties/show/{showDynasty}', 'LineageController@show')->name('dynasties.show');
    Route::get('dynasties/edit/{editDynasty}', 'LineageController@edit')->name('dynasties.edit');
    Route::post('dynasties/update/{editDynasty}', 'LineageController@update')->name('dynasties.update');
    Route::delete('dynasties/destroy/{id}', 'LineageController@destroy')->name('dynasties.destroy');

    //Post
    Route::get('reportedPosts/index', 'ReportPostController@index' )->name('reportedPosts.index');
    Route::get('reportedPosts/show/{showPost}', 'ReportPostController@show')->name('reportedPosts.show');
    Route::get('promotePosts/create', 'PromotePostController@create')->name('promotePosts.create');
    Route::get('promotePosts/index', 'PromotePostController@index')->name('promotePosts.index');
    Route::post('promotePosts/store', 'PromotePostController@store')->name('promotePosts.store');
    Route::get('promotePosts/edit/{id}', 'PromotePostController@edit')->name('promotePosts.edit');
    Route::post('promotePost/update/{id}', 'PromotePostController@update')->name('promotePosts.update');
    Route::get('promotePosts/show/{id}', 'PromotePostController@show')->name('promotePosts.show');
    Route::delete('promotePosts/destroy/{id}', 'PromotePostController@destroy')->name('promotePost.destroy');
    Route::get('inactivePosts/index', 'PromotePostController@inactivePosts')->name('inactivePosts.index');
    Route::get('inactivePosts/show/{id}', 'PromotePostController@showInactivePosts')->name('inactivePosts.show');


    // Colors
    Route::get('colors/index', 'ColorsController@index')->name('colors.index');
    Route::get('colors/create', 'ColorsController@create')->name('colors.create');
    Route::post('colors/store', 'ColorsController@store')->name('colors.store');
    Route::get('colors/edit/{id}', 'ColorsController@edit')->name('colors.edit');
    Route::put('colors/update/{id}', 'ColorsController@update')->name('colors.update');
    Route::get('colors/show/{id}', 'ColorsController@show')->name('colors.show');
    Route::delete('colors/destroy/{id}', 'ColorsController@destroy')->name('colors.destroy');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
