<?php

use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\CUSTOMERS\AddressController;
use App\Http\Controllers\API\CUSTOMERS\AuthController;
use App\Http\Controllers\API\CUSTOMERS\CategoryController;
use App\Http\Controllers\API\CUSTOMERS\HomeController;
use App\Http\Controllers\API\CUSTOMERS\OrderController;
use App\Http\Controllers\API\CUSTOMERS\ProductController;
use App\Http\Controllers\API\CUSTOMERS\VacationController;
use App\Http\Controllers\API\CUSTOMERS\WalletController;

use App\Http\Controllers\API\DELIVERYBOY\AuthController as DeliveryboyAuthController;
use App\Http\Controllers\API\DELIVERYBOY\OrderController as DeliveryboyOrderController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::any('customer/remove_stock_from_location', [CommonController::class, 'remove_stock_from_location']);
Route::any('customer/add_stock_from_location', [CommonController::class, 'add_stock_from_location']);


Route::any('deliveryboy/edit_profile', [AuthController::class, 'edit_profile']);
Route::any('deliveryboy/submit_cash', [CommonController::class, 'submit_cash']);

/**customers api start */
Route::group(['prefix'=>'customers'], function () {
    Route::any('signup_otp', [AuthController::class, 'signup_otp']);
    Route::any('signup_verify', [AuthController::class, 'signup_verify']);
    Route::any('resend_otp_signup', [AuthController::class, 'resend_otp_signup']);
    Route::any('login_otp', [AuthController::class, 'login_otp']);
    Route::any('resend_otp_login', [AuthController::class, 'resend_otp_signup']);
    Route::any('login_verify', [AuthController::class, 'login_verify']);

    Route::any('resend_otp', [AuthController::class, 'resend_otp']);


    Route::any('get_homepage_2',[HomeController::class,'get_homepage']);
    Route::any('fetch_whatsnew_2',[HomeController::class,'fetch_whatsnew']);
    Route::any('fetch_bestoffers_2',[HomeController::class,'fetch_bestoffers']);

    Route::any('check_if_location_in_service',[CommonController::class,'check_if_location_in_service']);
    Route::any('fetch_company_data',[HomeController::class,'fetch_company_data']);







    /**DELIVERYBOY */

    Route::any('deliveryboy_login',[DeliveryboyAuthController::class,'login']);



    /**without token api's */
    Route::any('get_homepage_without_token',[HomeController::class,'get_homepage']);


    Route::any('fetch_whatsnew_without_token',[HomeController::class,'fetch_whatsnew']);
    Route::any('fetch_bestoffers_without_token',[HomeController::class,'fetch_bestoffers']);
    

    Route::any('get_subcategories_without_token',[CategoryController::class,'get_subcategories']);
    Route::any('get_products_by_subcategory_without_token',[ProductController::class,'get_products_by_subcategory']);

    Route::any('get_product_details_without_token',[ProductController::class,'get_product_details']);

});

// Route::middleware('auth:api','apikey')->prefix('customers')->group(function () {
Route::middleware('auth:api')->prefix('customers')->group(function () {

    Route::any('index', [AuthController::class, 'index']);
    Route::any('get_user_profile', [AuthController::class, 'get_user_profile']);
    Route::any('edit_profile', [AuthController::class, 'edit_profile']);
    Route::any('get_user_referral_code', [AuthController::class, 'get_user_referral_code']);



    Route::any('get_homepage',[HomeController::class,'get_homepage']);


    Route::any('fetch_whatsnew',[HomeController::class,'fetch_whatsnew']);
    Route::any('fetch_bestoffers',[HomeController::class,'fetch_bestoffers']);

    Route::any('check_if_pincode_in_service',[HomeController::class,'check_if_pincode_in_service']);
    Route::any('fetch_serviceable_pincodes',[HomeController::class,'fetch_serviceable_pincodes']);

    Route::any('add_feedback',[HomeController::class,'add_feedback']);



    Route::any('get_subcategories',[CategoryController::class,'get_subcategories']);
    Route::any('get_products_by_subcategory',[ProductController::class,'get_products_by_subcategory']);


    Route::any('toggle_wishlist',[ProductController::class,'toggle_wishlist']);
    Route::any('fetch_wishlists',[ProductController::class,'fetch_wishlists']);
    Route::any('global_search',[ProductController::class,'global_search']);
    Route::any('get_product_details',[ProductController::class,'get_product_details']);




    Route::any('get_frequencies',[CommonController::class,'get_frequencies']);
    Route::any('get_addresses',[AddressController::class,'get_addresses']);
    Route::any('add_address',[AddressController::class,'add_address']);
    Route::any('edit_address',[AddressController::class,'edit_address']);
    Route::any('select_default_address',[AddressController::class,'select_default_address']);

    Route::any('get_locations',[AddressController::class,'get_locations']);
    Route::any('get_societies',[AddressController::class,'get_societies']);




    Route::any('delete_address',[AddressController::class,'delete_address']);

    Route::any('add_to_cart',[OrderController::class,'add_to_cart']);
    Route::any('edit_cart',[OrderController::class,'edit_cart']);
    Route::any('get_cart_items',[OrderController::class,'get_cart_items']);
    Route::any('fetch_orders',[OrderController::class,'fetch_orders']);

    Route::any('cancel_order',[OrderController::class,'cancel_order']);


    Route::any('change_subscription_address',[OrderController::class,'change_subscription_address']);
    Route::any('my_subscriptions',[OrderController::class,'my_subscriptions']);





    Route::any('add_to_subscribe',[OrderController::class,'add_to_subscribe']);

    Route::any('get_deliveries_counts',[OrderController::class,'get_deliveries_counts']);


    Route::any('get_coupons',[OrderController::class,'get_coupons']);
    Route::any('apply_coupon',[OrderController::class,'apply_coupon']);
    Route::any('remove_coupon',[OrderController::class,'remove_coupon']);

    Route::any('create_order',[OrderController::class,'create_order']);

    Route::any('my_orders',[OrderController::class,'my_orders']);
    Route::any('count_cart_items',[OrderController::class,'count_cart_items']);

    Route::any('create_subscription_order',[OrderController::class,'create_subscription_order']);
    Route::any('get_subscription_deliveries',[OrderController::class,'get_subscription_deliveries']);


    Route::any('get_notifications',[HomeController::class,'get_notifications']);
    Route::any('fetch_faqs',[HomeController::class,'fetch_faqs']);



    Route::any('get_all_subcategories',[CategoryController::class,'get_all_subcategories']);

    Route::any('recharge_wallet',[WalletController::class,'recharge_wallet']);

    Route::any('recharge_lists',[WalletController::class,'recharge_lists']);


    Route::any('add_vacations',[VacationController::class,'add_vacations']);
    Route::any('remove_vacations',[VacationController::class,'remove_vacations']);
    Route::any('get_vacation',[VacationController::class,'get_vacation']);


    Route::any('return_order',[OrderController::class,'return_order']);
    Route::any('cancel_item',[OrderController::class,'cancel_item']);


    Route::any('order_details',[OrderController::class,'order_details']);


    Route::any('wallet_history',[WalletController::class,'wallet_history']);
    Route::any('cashback_wallet_history',[WalletController::class,'cashback_wallet_history']);

    Route::any('get_subscribed_items',[OrderController::class,'get_subscribed_items']);



    /**DELIVERYBOY */

    Route::any('my_deliveries',[DeliveryboyOrderController::class,'my_deliveries']);

    Route::any('complete_order',[DeliveryboyOrderController::class,'complete_order']);

    Route::any('deliveryboy_support',[DeliveryboyOrderController::class,'support']);

    Route::any('deliveryboy_toggle_status',[DeliveryboyOrderController::class,'toggle_status']);

    Route::any('deliveryboy_completed_order',[DeliveryboyOrderController::class,'fetch_completed_order']);

    Route::any('get_cash_collects',[DeliveryboyOrderController::class,'get_cash_collects']);
    Route::any('handover_cash',[DeliveryboyOrderController::class,'handover_cash']);


    //

    // my_deliveries
    // get_addresses
});

/**customers api end*/
