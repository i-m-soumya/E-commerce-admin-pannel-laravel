<?php
use App\Http\Controllers\Android\Aggregator\AggregatorController;
use App\Http\Controllers\Android\Aggregator\OrdersController;
use App\Http\Controllers\Android\CartController;
use App\Http\Controllers\Android\OfferAndDeliveryChargeController;
use App\Http\Controllers\Android\OrderController;
use App\Http\Controllers\Android\ProductsController;
use App\Http\Controllers\Android\SearchController;
use App\Http\Controllers\Android\UserController;
use App\Http\Controllers\Android\WishlistController;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\MailController;
use App\Http\Controllers\OfferMailAndPush;
use App\Http\Controllers\Order;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\Product;
use App\Http\Controllers\Report;
use App\Http\Controllers\Setting;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

// This Section is for Pages redirect routes
Route::get('/', [PagesController::class, 'signin']);
Route::get('/dashboard', [PagesController::class, 'dashboard']);
Route::get('/product', [PagesController::class, 'product']);
Route::get('/order_details', [PagesController::class, 'order_details']);
Route::get('/product', [PagesController::class, 'product']);
Route::get('/report', [PagesController::class, 'report']);
Route::get('/users', [PagesController::class, 'users']);
Route::get('/setting', [PagesController::class, 'setting']);
Route::get('/analytics', [PagesController::class, 'analytics']);
Route::get('/sales', [PagesController::class, 'sales']);

// This Section is for web application's API
Route::get('/logout', [Authentication::class, 'logout']);
Route::Post('/signin', [Authentication::class, 'login']);
Route::Post('/view_admin_profile', [Authentication::class, 'view_admin_profile']);
//product page routes
Route::prefix('dashboards')->group(function () {
    Route::Post('/fetch_total_sales_and_orders', [Dashboard::class, 'fetch_total_sales_and_orders']);
    Route::Post('/fetch_leatest_order', [Dashboard::class, 'fetch_leatest_order']);
    Route::Post('/fetch_orders_status_today', [Dashboard::class, 'fetch_orders_status_today']);
    Route::Post('/fetch_orders_status_monthly', [Dashboard::class, 'fetch_orders_status_monthly']);
    Route::Post('/fetch_top_selling_products', [Dashboard::class, 'fetch_top_selling_products']);
    Route::Post('/fetch_new_members', [Dashboard::class, 'fetch_new_members']);
    Route::Post('/fetch_pin_wise_order', [Dashboard::class, 'fetch_pin_wise_order']);
    Route::Post('/fetch_day_wise_orders', [Dashboard::class, 'fetch_day_wise_orders']);
    Route::Post('/fetch_sales_chart_data', [Dashboard::class, 'fetch_sales_chart_data']);
    Route::Post('/fetch_customer_chart_data', [Dashboard::class, 'fetch_customer_chart_data']);
    Route::Post('/fetch_customer_feedback', [Dashboard::class, 'fetch_customer_feedback']);
    Route::Post('/fetch_feedback_details', [Dashboard::class, 'fetch_feedback_details']);
    Route::Post('/update_reply_msg', [Dashboard::class, 'update_reply_msg']);
});
//product page routes
Route::prefix('products')->group(function () {
    Route::Post('/product_details_list', [Product::class, 'product_details_list']);
    Route::Post('/fetch_product_list', [Product::class, 'fetch_product_list']);
    Route::Post('/fetch_category', [Product::class, 'fetch_category']);
    Route::Post('/fetch_sub_category', [Product::class, 'fetch_sub_category']);
    Route::Post('/fetch_brand', [Product::class, 'fetch_brand']);
    Route::Post('/fetch_unit_type', [Product::class, 'fetch_unit_type']);
    Route::Post('/insert_product_details', [Product::class, 'insert_product_details']);
    Route::Post('/edit_product_details', [Product::class, 'edit_product_details']);
    Route::Post('/delete_product', [Product::class, 'delete_product']);
    Route::Post('/update_product_image', [Product::class, 'update_product_image']);
});
// settings page routes
Route::prefix('settings')->group(function () {
    Route::Post('/add_new_category', [Setting::class, 'add_new_category']);
    Route::Post('/add_new_sub_category', [Setting::class, 'add_new_sub_category']);
    Route::Post('/add_new_brand', [Setting::class, 'add_new_brand']);
    Route::Post('/add_new_unit_type', [Setting::class, 'add_new_unit_type']);
    Route::Post('/add_new_cancellation_reason', [Setting::class, 'add_new_cancellation_reason']);
    Route::Post('/add_new_pincode', [Setting::class, 'add_new_pincode']);
    Route::Post('/add_new_village', [Setting::class, 'add_new_village']);
    Route::Post('/fetch_all_categories', [Setting::class, 'fetch_all_categories']);
    Route::Post('/fetch_all_sub_categories', [Setting::class, 'fetch_all_sub_categories']);
    Route::Post('/fetch_all_brands', [Setting::class, 'fetch_all_brands']);
    Route::Post('/fetch_all_unit_type', [Setting::class, 'fetch_all_unit_type']);
    Route::Post('/fetch_all_cancellation_reason', [Setting::class, 'fetch_all_cancellation_reason']);
    Route::Post('/fetch_all_pincode', [Setting::class, 'fetch_all_pincode']);
    Route::Post('/fetch_all_village', [Setting::class, 'fetch_all_village']);
    Route::Post('/delete_category', [Setting::class, 'delete_category']);
    Route::Post('/delete_sub_category', [Setting::class, 'delete_sub_category']);
    Route::Post('/delete_brand', [Setting::class, 'delete_brand']);
    Route::Post('/delete_unit_type', [Setting::class, 'delete_unit_type']);
    Route::Post('/update_village_status', [Setting::class, 'update_village_status']);
    Route::Post('/update_pincode_status', [Setting::class, 'update_pincode_status']);
    Route::Post('/fetch_action_data', [Setting::class, 'fetch_action_data']);
    Route::Post('/add_featured_image', [Setting::class, 'add_featured_image']);
    Route::Post('/fetch_product', [Setting::class, 'fetch_product']);
    Route::Post('/fetch_delivery_charges', [Setting::class, 'fetch_delivery_charges']);
    Route::Post('/modify_delivery_charge', [Setting::class, 'modify_delivery_charge']);
    Route::Post('/fetch_featured_images', [Setting::class, 'fetch_featured_images']);
    Route::Post('/delete_featured_image', [Setting::class, 'delete_featured_image']);
    Route::Post('/add_offer', [Setting::class, 'add_offer']);
    Route::Post('/fetch_offers', [Setting::class, 'fetch_offers']);
    Route::Post('/delete_offer', [Setting::class, 'delete_offer']);
    Route::Post('/fetch_minimum_order', [Setting::class, 'fetch_minimum_order']);
    Route::Post('/modify_minimum_order', [Setting::class, 'modify_minimum_order']);
});
//order_details page routes
Route::prefix('orders')->group(function () {
    Route::Post('/fetch_order_details', [Order::class, 'fetch_order_details']);
    Route::Post('/fetch_delivery_partner_details', [Order::class, 'fetch_delivery_partner_details']);
    Route::Post('/assign_delivery_partner', [Order::class, 'assign_delivery_partner']);
    Route::Post('/fetch_order_full_details', [Order::class, 'fetch_order_full_details']);
    Route::get('/export_order_details', [Order::class, 'export_order_details']);
    Route::POST('/reject_order', [Order::class, 'reject_order']);
});
//report page routes
Route::prefix('reports')->group(function () {
    Route::POST('/fetch_aggregator_report', [Report::class, 'fetch_aggregator_report']);
    Route::get('/print_aggregator_report', [Report::class, 'print_aggregator_report']);
    Route::POST('/fetch_order_by_product', [Report::class, 'fetch_order_by_product']);
    Route::get('/fetch_order_by_product', [Report::class, 'fetch_order_by_product']);
    Route::get('/print_order_by_product_report', [Report::class, 'print_order_by_product_report']);
    Route::POST('/fetch_village', [Report::class, 'fetch_village']);
    Route::POST('/fetch_pincode', [Report::class, 'fetch_pincode']);
    Route::POST('/data_on_pincode_village', [Report::class, 'data_on_pincode_village']);
    Route::POST('/fetch_aggregator_order_details', [Report::class, 'fetch_aggregator_order_details']);
});
//users page routes
Route::prefix('users')->group(function () {
    Route::POST('/fetch_customer', [User::class, 'fetch_customer']);
    Route::POST('/add_admin', [User::class, 'add_admin']);
    Route::POST('/add_aggregator', [User::class, 'add_aggregator']);
    Route::POST('/add_salesman', [User::class, 'add_salesman']);
    Route::POST('/fetch_admin', [User::class, 'fetch_admin']);
    Route::POST('/fetch_aggregator', [User::class, 'fetch_aggregator']);
    Route::POST('/fetch_salesman', [User::class, 'fetch_salesman']);
    Route::POST('/change_admin_status', [User::class, 'change_admin_status']);
    Route::POST('/change_aggregator_status', [User::class, 'change_aggregator_status']);
    Route::POST('/change_salesman_status', [User::class, 'change_salesman_status']);
    Route::POST('/reset_admin_password', [User::class, 'reset_admin_password']);
    Route::POST('/reset_aggregator_password', [User::class, 'reset_aggregator_password']);
    Route::POST('/reset_salesman_password', [User::class, 'reset_salesman_password']);
    Route::POST('/fetch_customer_details', [User::class, 'fetch_customer_details']);
});

//This Section is for Android application's API
Route::prefix('android-api')->group(function () {
    Route::get('/validate_phone_number_and_password', [UserController::class, 'validate_phone_number_and_password']);
    Route::post('/get_user_details', [UserController::class, 'get_user_details']);
    Route::get('/set_password', [UserController::class, 'set_password']);
    Route::post('/get_villages_from_pin', [UserController::class, 'fetchVillageFromPin']);
    Route::post('/delete_address', [UserController::class, 'deleteAddress']);
    Route::post('/add_address', [UserController::class, 'addAddress']);
    Route::post('/update_address', [UserController::class, 'updateAddress']);
    Route::post('/add_referal_code', [UserController::class, 'addReferalCode']);
    Route::post('/fetch_categories', [ProductsController::class, 'fetch_categories']);
    Route::post('/fetch_brands', [ProductsController::class, 'fetch_brands']);
    Route::post('/fetch_sub_categories', [ProductsController::class, 'fetch_sub_categories']);
    Route::post('/fetch_featured_images', [ProductsController::class, 'fetch_featured_images']);
    Route::post('/fetch_popular_distinct_tags', [ProductsController::class, 'fetch_popular_distinct_tags']);
    Route::post('/fetch_product_details', [ProductsController::class, 'fetch_product_details']);
    Route::post('/search', [SearchController::class, 'searchProducts']);
    Route::post('/search-filters', [SearchController::class, 'filtersForSearchPage']);
    Route::post('/search-suggetions', [SearchController::class, 'searchSuggetions']);
    Route::post('/update_name', [UserController::class, 'updateName']);
    Route::post('/update_email', [UserController::class, 'updateEmail']);
    Route::post('/update_password', [UserController::class, 'updatePassword']);
    Route::post('/update_phone', [UserController::class, 'updatePhone']);
    Route::post('/add_to_cart', [CartController::class, 'addToCart']);
    Route::post('/fetch_cart', [CartController::class, 'fetchCart']);
    Route::post('/update_cart_product_quantity', [CartController::class, 'updateCartProductQuantity']);
    Route::post('/add_to_wishlist', [WishlistController::class, 'addToWishlist']);
    Route::post('/fetch_wishlist', [WishlistController::class, 'fetchWishlist']);
    Route::post('/fetch_coupon', [OfferAndDeliveryChargeController::class, 'fetch_coupon']);
    Route::post('/fetch_delivery_charge', [OfferAndDeliveryChargeController::class, 'fetch_delivery_charge']);
    Route::post('/remove_from_cart', [CartController::class, 'removeFromCart']);
    Route::post('/remove_from_wishlist', [WishlistController::class, 'removeFromWishlist']);
    Route::post('/place_order', [OrderController::class, 'placeOrder']);
    Route::post('/order_list', [OrderController::class, 'orderList']);
    Route::post('/order_details', [OrderController::class, 'orderDetails']);
    Route::post('/update_fcm_token', [UserController::class, 'fcmTockenUpdate']);
    Route::get('/push_test', [UserController::class, 'push']);
    Route::post('/cancel_order', [OrderController::class, 'cancel_order']);
    Route::post('/fetch_best_deals', [SearchController::class, 'fetch_best_deals']);
    Route::post('/trending_products', [SearchController::class, 'trending_products']);
    Route::post('/fetch_notifications', [UserController::class, 'fetch_notifications']);
    Route::post('/fetch_faq', [UserController::class, 'fetch_faq']);
    Route::post('/fetch_total_order_and_money_saved', [UserController::class, 'fetch_total_order_and_money_saved']);
    Route::post('/add_feedback_query', [UserController::class, 'add_feedback_query']);
    Route::Post('/fetch_minimum_order', [Setting::class, 'fetch_minimum_order']);
    Route::prefix('aggregator-api')->group(function () {
        Route::get('/authenticate_aggregator', [AggregatorController::class, 'authenticate_aggregator']);
        Route::Post('/get_profile_details', [AggregatorController::class, 'get_profile_details']);
        Route::Post('/get_order_list_on_for_status', [OrdersController::class, 'get_order_list_on_for_status']);
        Route::Post('/get_order_details', [OrdersController::class, 'get_order_details']);
        Route::Post('/authenticate_delivery', [OrdersController::class, 'authenticate_delivery']);
        Route::Post('/update_password', [AggregatorController::class, 'update_password']);
        Route::Post('/get_delivered_order', [OrdersController::class, 'get_delivered_order']);
        Route::Post('/fetch_aggregator_notification', [OrdersController::class, 'fetch_notifications']);
        Route::Post('/get_canceled_orders', [OrdersController::class, 'get_canceled_orders']);
        Route::Post('/out_for_delivery', [OrdersController::class, 'out_for_delivery']);
        Route::post('/update_fcm_token', [UserController::class, 'fcmTockenUpdate']);
    });
});

//privacy-policies and term and conditions
Route::get('/privacy_policies', function () {
    return View('privacy_policies');
});
//offer mail
Route::get('/send_offer_mail', [OfferMailAndPush::class, 'sendMail']);

Route::get('/term_and_conditions', function () {
    return View('term_and_conditions');
});
Route::get('/about_us', function () {
    return View('about_us');
});
Route::Post('/contact_us', [ContactUsController::class, 'contact_us']);

//LARAVEL LOG VIEWER [Will be turned off in Production]
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('sendemail', [MailController::class, 'sendemail']);
Route::get('generatepdf', [PDFController::class, 'generatepdf']); // THIS ROUTE IS FOR PDF, WILL BE DELETED.
Route::get('generate_order_pdf', [PDFController::class, 'generate_order_pdf']);


Route::get('push', [OrderController::class, 'push']);
