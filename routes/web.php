<?php
use Illuminate\Support\Facades\Route;
//frontend
use App\Http\Controllers\Frontend\AuthController as UserController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ProductController as FrontProduct;
use App\Http\Controllers\Frontend\DashboardController as UserDashboard;
use App\Http\Controllers\Frontend\UserOrderController;
use App\Http\Controllers\Frontend\UserAccountDetailsController;
use App\Http\Controllers\Frontend\UserWishlistController;
use App\Http\Controllers\Frontend\ProductReviewController;

//backend
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ExpenseController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\TypeController;
use App\Http\Controllers\Backend\SizeController;
use App\Http\Controllers\Backend\HomeSectionImageController;
use App\Http\Controllers\Backend\ProductDiscountController;
use App\Http\Controllers\Backend\PurchaseController;
use App\Http\Controllers\Backend\AboutUsController;
use App\Http\Controllers\Backend\CareerController;
use App\Http\Controllers\Backend\SocialIconController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\ComboController;
use App\Http\Controllers\Backend\ColorController;
use App\Http\Controllers\Backend\DeliveryChargeController;
use App\Http\Controllers\Backend\OrderPaymentController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\LandingPageController;
use App\Http\Controllers\Backend\CouponCodeController;
use App\Http\Controllers\Backend\CourierController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\InformationController;
use App\Http\Controllers\Backend\IPBlockController;

use App\Http\Controllers\Auth\ResetPasswordController;


Route::get('/clear-cache', function(){
    \Artisan::call('optimize'); 
    \Artisan::call('view:clear'); 
    \Artisan::call('cache:clear'); 
    \Artisan::call('config:clear'); 
    \Artisan::call('config:cache'); 
    \Artisan::call('route:clear');  
     
     dd('ok');
});
Route::group(['as'=>'front.'], function() {
    Route::resource('orders',UserOrderController::class);
    Route::controller(HomeController::class)->group(function(){
        Route::get('/','home')->name('home');
        Route::get('page/{page}', 'pageName')->name('page.name');
        Route::get('/about-us','aboutUs')->name('aboutUs');
        Route::get('/contact-us','contactUs')->name('contactUs');
        Route::get('/careers','career')->name('career');
        Route::get('/privacy-policy','privacyPolicy')->name('privacyPolicy');
        Route::get('/term-condition','termCondition')->name('termCondition');
        Route::get('/return-policy','returnPolicy')->name('returnPolicy');
        Route::get('/faq','faq')->name('faq');
        Route::get('/send-sms','sendSMs')->name('sendSMs');
        Route::post('/contacts','contact')->name('contact');
    });

    Route::controller(FrontProduct::class)->group(function(){
        Route::get('/products-list','index')->name('products.index');
        Route::get('/category','categories')->name('categories');
        Route::get('/c/{slug}','subCategories')->name('subCategories');
        Route::get('/cs/{slug}','subCategories1')->name('subCategories1');
      	Route::get('/s/{slug}','subsubCategories')->name('subsubCategories');
        Route::get('/brands','brands')->name('brands');
        Route::get('/discount-products','discountProduct')->name('discountProduct');
        Route::get('/product-show/{id}','show')->name('products.show');
      	Route::get('/relative-product/{id}','relativeProduct')->name('products.relativeProduct');
        
        Route::get('/combo-products','comboProducts')->name('combo_products');
        Route::get('/get-trending-products','trendingProduct')->name('trendingProduct');
        Route::get('/get-hotdeal-products','hotdealProduct')->name('hotdealProduct');
        Route::get('/get-recommended-products','recommendedProduct')->name('recommendedProduct');
        Route::get('view-landing-page/{id}','landing_page')->name('landing_pages.view_page');
        Route::get('view-landing-page-two/{id}','landing_pages_two')->name('landing_pages_two.view_page');
        Route::get('/free-shipping-product', 'free_shipping')->name('free-shipping');
        Route::get('/get-variation_price','get_variation_price')->name('get-variation_price');
      
    });
    
    Route::group(['middleware' => 'auth'], function() {
        
        Route::resource('dashboard',UserDashboard::class);
        //Route::resource('orders',UserOrderController::class);
        Route::resource('account_details',UserAccountDetailsController::class);
        Route::resource('wishlist',UserWishlistController::class);
    
    });
      	Route::resource('product-reviews',ProductReviewController::class);
      	Route::put('product-reviews', [ProductReviewController::class, 'update2'])->name('product.view.update');
    
    Route::controller(UserDashboard::class)->group(function(){
        Route::get('/confirm-order/{id}','confirmOrder')->name('confirmOrder');
        Route::get('/confirm-order-landing/{id}','confirmOrderlanding')->name('confirmOrderlanding');
    });
    
    Route::controller(UserController::class)->group(function(){
        Route::post('/user-login','login')->name('login');
        // Route::get('/seller-register','sellerRegister')->name('sellerRegister');
        Route::get('/seller-register', function(){
            return null;
        })->name('sellerRegister');
        Route::post('/seller-register-post','sellerRegisterPost')->name('sellerRegisterPost');
        Route::post('/user-register','Register')->name('register');
        
         Route::get('/get-otp','getOpt')->name('getOpt');
         Route::post('/otp-verify','optVerify')->name('optVerify');
    });


    Route::resource('/carts',CartController::class);
    Route::post('/cart/store', [CartController::class, 'storeCart'])->name('carts.storeCart');
    Route::get('/cart/clear-all', [CartController::class, 'clearAll'])->name('carts.clearAll');

    Route::group(['middleware' => 'auth'], function() {
        
        Route::get('/coupon-discount',[CheckoutController::class,'getCouponDiscount'])->name('getCouponDiscount');
    });
        Route::resource('/checkouts',CheckoutController::class);  
        Route::post('store-data',[CheckoutController::class,'storeData'])->name('storeData');
        Route::post('/store/checkout',[CheckoutController::class,'StoreChk'])->name('store.checkout');

    Route::post('/store/landing/data',[CheckoutController::class,'storelandData'])->name('storelandData');

});


Route::post('incomplete/order/store',[CheckoutController::class,'incompleteStore'])->name('incompleteStore');
Route::get('/check-courier-percentage',[CheckoutController::class,'courierPercentage'])->name('courierPercentage');


Auth::routes();


//backend
Route::controller(AuthController::class)->group(function(){
    Route::get('/admin','login')->name('admin.login');
    Route::post('/admin-login','postLogin')->name('admin.postLogin');
});


Route::group(['prefix' => 'admin','middleware' => 'auth','as'=>'admin.'], function() {
	
  	Route::get('/Ip-block', [IPBlockController::class, 'index'])->name('ipblock');
  	Route::get('/Ip-block/delete/{id}', [IPBlockController::class, 'delete'])->name('ipblock.delete');
  	Route::get('/Ip-block/edit/{id}', [IPBlockController::class, 'edit'])->name('ipblock.edit');
	Route::put('/Ip-block/update/{id}', [IPBlockController::class, 'update'])->name('ipblock.update');
  
    Route::post('/Ip-block-submit', [IPBlockController::class, 'IPBlockSubmit'])->name('ipblock.submit');
  	
  
  	Route::get('/dashboard',[DashboardController::class,'dashboard'])->name('dashboard');
  	Route::get('/get-dashboard-data',[DashboardController::class,'getDashboardData'])->name('getDashboardData');
  	Route::get('/get-dashboard-data-two',[DashboardController::class,'getDashboardData2'])->name('getDashboardData2');
  	
    // Review Route
    Route::resource('reviews', DashboardController::class)->only(['index', 'destroy']);
    Route::get('reviews/action', [DashboardController::class, 'reviewAction'])->name('reviews.action');
    
    Route::post('/file-upload',[ProductController::class,'fileUpload'])->name('ckeditor.upload');
    Route::get('/file-delete/{id}',[ProductController::class,'deleteImage'])->name('deleteImage');
    Route::get('/get-sub-category',[ProductController::class,'getSubcategory'])->name('getSubcategory');
    Route::get('/product-export',[ProductController::class,'productExport'])->name('productExport');
    Route::post('/update-priority/{id}', [ProductController::class, 'updatePriority']);
    Route::get('/cat-wise-product',[ProductController::class,'cat_wise_product'])->name('cat_wise_product');
  
  	Route::controller(OrderController::class)->group(function(){
  	    Route::post('/fetch-address-details', 'fetchAddressDetails')->name('fetch.address.details');
  	    
  	    Route::get('order/fraud-check/{id}', 'fraudOrderCheck')->name('fraudOrderCheck');
        Route::get('order/fraudulent-check/{mobileNo}', 'fraudulentCheck')->name('fraudulentCheck');

        Route::get('/order-status/{id}','orderStatus')->name('orderStatus');
        Route::post('/order-status/update/{id}','orderStatusUPdate')->name('orderStatusUPdate');
        
        Route::get('/get-order-product','getOrderProduct')->name('getOrderProduct');
        Route::get('/get-order-product2','getOrderProduct2')->name('getOrderProduct2');
        Route::get('/order-product-entry','orderProductEntry')->name('orderProductEntry');
        Route::get('/landing-product-entry','landingProductEntry')->name('landingProductEntry');
        Route::get('/order-export','orderExport')->name('orderExport');

        Route::get('/assign-user','assignUser')->name('assignUser');
        Route::get('/order-status-opdate','orderStatusUpdateMulti')->name('orderStatusUpdateMulti');
        Route::get('/all-order-delete','deleteAllOrder')->name('deleteAllOrder');
        Route::get('/all-order-delete2','deleteAllOrder2')->name('deleteAllOrder2');
        Route::get('/order-list','orderList')->name('orderList');     
        Route::view('/print_multiple','backend.reports.print');      
      
        Route::get('/status-wise-order', 'status_wise_order')->name('status_wise_order');  
        Route::get('/search-order', 'searchOrder')->name('searchOrder');

        Route::get('/assign-user-store','assignUserStore')->name('assignUserStore');
        Route::get('/multi-order-status-update-store','multuOrderStatusUpdate')->name('multuOrderStatusUpdate');
      
      //Redx Courier Service
       Route::get('/create-redx-parcel','OrderSendToRedx')->name('createRedxParcel');     
      
      //Pathao Courier Service
       Route::get('/zones-by-city/{city}','getPathaoZoneListByCity')->name('zonesByCity');     
       Route::get('/areas-by-zone/{zone}','getPathaoAreaListByZone')->name('areasByZone');    
       Route::get('/create-pathao-parcel','OrderSendToPathao')->name('createPathaoParcel');
       
       //Steadfast Courier Service
       Route::get('/create-steadfast-parcel', 'OrderSendToSteadfast')->name('createSteadfastParcel');
      
       //Update Courier Status
       Route::get('/update-courier-status', [OrderController::class, 'updateCourierStatus'])->name('updateCourierStatus');
      
       //generate pathao access token
       Route::get('generate-access-token', 'viewAccessToken')->name('viewAccessToken');
       Route::post('generate-access-token', 'generatePathaoAccessToken')->name('generatePathaoAccessToken');
      
      /* Trashed Order */
        Route::get('/trashed/orders', 'trashed_orders')->name('trashed_orders');
        Route::get('/restore/order', 'restore_order')->name('restore_order');
        Route::get('/force/delete/order/{id}', 'forceDel')->name('forceDel');

    });
    
   

    Route::get('/recommended-update',[ProductController::class,'recommendedUpdate'])->name('recommendedUpdate');
    Route::get('/show-update',[ProductController::class,'showUpdate'])->name('showUpdate');
  	Route::get('/product-copy/{id}',[ProductController::class,'productCopy'])->name('productCopy');
  
    Route::resource('products',ProductController::class);
    Route::resource('expenses',ExpenseController::class);
    
    Route::get('/home-category',[CategoryController::class,'homeCatgeory'])->name('homecat');
    Route::post('/home-category',[CategoryController::class,'storehomeCatgeory'])->name('store-homecat');
    Route::delete('/del-home-category/{id}', [CategoryController::class, 'delhomeCatgeory'])->name('del_homecat');
    
    Route::get('/popular-category',[CategoryController::class,'popularCatgeory'])->name('popularCatgeory');
    Route::resource('categories',CategoryController::class);
    Route::resource('sliders',SliderController::class);
    Route::resource('orders',OrderController::class);
    Route::resource('users',UsersController::class);
    Route::resource('roles',RoleController::class);
    Route::resource('permissions',PermissionController::class);
    
    Route::get('/top-brand-update',[TypeController::class,'topBrandUpdate'])->name('topBrandUpdate');
    Route::resource('types',TypeController::class);
    Route::resource('sizes',SizeController::class);
    Route::resource('purchase',PurchaseController::class);
    Route::resource('about_us',AboutUsController::class);
    Route::resource('career',CareerController::class);
    Route::resource('suppliers',SupplierController::class);
    Route::resource('combos',ComboController::class);
    Route::resource('colors',ColorController::class);
  	Route::resource('pages',PageController::class);
  	Route::resource('landing_pages',LandingPageController::class);
  	Route::get('landing-page/{id}',[PageController::class,'landing_page'])->name('landing_index');
    Route::post('store-data',[LandingPageController::class,'storeData'])->name('landing_pages.storeData');
    Route::get('landing-page-two',[LandingPageController::class,'landing_page_two'])->name('landing_pages_two');
    Route::get('create-landing-page-two',[LandingPageController::class,'create_landing_page_two'])->name('landing_pages_two.create');
    Route::post('store-landing-page-two',[LandingPageController::class,'store_landing_page_two'])->name('landing_pages_two.store');
    Route::get('edit-landing-page-two/{id}',[LandingPageController::class,'edit_landing_page_two'])->name('landing_pages_two.edit');
    Route::patch('update-landing-page-two/{id}', [LandingPageController::class, 'update_landing_page_two'])->name('landing_pages_two_update');

    
    Route::get('delete-slider-image/{id}',[LandingPageController::class,'delete_slider'])->name('delete_slider');
  	Route::get('delete/review/{id}', [LandingPageController::class, 'delete_review'])->name('delete_review');
    
  	
    Route::resource('couriers',CourierController::class);
    Route::resource('social-icons',SocialIconController::class,['names'=>'social_icons']);
  	Route::resource('order-payments',OrderPaymentController::class,['names'=>'order_payments']);
    Route::resource('delivery-charges',DeliveryChargeController::class,['names'=>'delivery_charge']);
    Route::resource('coupon-codes',CouponCodeController::class,['names'=>'coupon_codes']);

    Route::get('/user-status-update',[UsersController::class,'userStatusUpdate'])->name('userStatusUpdate');
    Route::resource('/home-section-images',HomeSectionImageController::class,['names'=>'home_section_images']);
    Route::resource('/product-discounts',ProductDiscountController::class,['names'=>'product_discounts']);
    
    
    Route::get('/free-shipping-product',[ProductDiscountController::class,'free_shipping'])->name('free_shipping');
    Route::get('/create-free-shipping-product',[ProductDiscountController::class,'create_free_shipping'])->name('create_free_shipping');
    Route::post('/store-free-shipping',[ProductDiscountController::class,'store_free_shipping'])->name('store-free-shipping');
    Route::get('/destroy-free-shipping',[ProductDiscountController::class,'fshippingdestroy'])->name('free-shipping.fshippingdestroy');
    


    Route::get('/get-discount-product',[ProductDiscountController::class,'getDiscountProduct'])->name('getDiscountProduct');
    Route::get('/product-entry',[ProductDiscountController::class,'productEntry'])->name('productEntry');
    Route::get('/free-shipping-product-entry',[ProductDiscountController::class,'productEntry2'])->name('productEntry2');

    Route::get('/get-purchase-product',[PurchaseController::class,'getPurchaseProduct'])->name('getPurchaseProduct');
    Route::get('/purchase-product-entry',[PurchaseController::class,'purchaseProductEntry'])->name('purchaseProductEntry');
    
  	 
  	
  
    //Report Section
    
    //Order Report
   Route::group(['as'=> 'report.'], function(){
   Route::controller(ReportController::class)->group(function(){
   Route::get('/order-report', 'orderReport')->name('order'); 
   Route::get('/product-report', 'productReport')->name('product'); 
   Route::get('/user-report', 'userReport')->name('user'); 
   Route::get('/order-search', 'filterOrder')->name('order.search');
   Route::get('/product-search', 'filterProduct')->name('product.search');
   Route::get('/export-order-report', 'exportOrderReport')->name('order.export');
       }); 
   });
   
   Route::resource('settings', InformationController::class);
   
   //Update Profile
   Route::controller(InformationController::class)->group(function(){
       Route::get('/profile', 'showProfile')->name('profile');
       Route::post('/profile-update', 'updateProfile')->name('profile.update');
       Route::get('/status-coupon','statusCoupon')->name('status.coupon');
   });   

   //Change Password
   Route::controller(ResetPasswordController::class)->group(function(){
       Route::get('/change-password', 'show')->name('password');
       Route::post('/update-password', 'updatePassword')->name('password.update');
   });

});

if (file_exists(base_path('routes/cache_routes.php'))) {
    require base_path('routes/cache_routes.php');
}

