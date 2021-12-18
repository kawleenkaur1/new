<?php
use App\Http\Controllers\ADMIN\BlogController;
use App\Http\Controllers\ADMIN\BannerController;
use App\Http\Controllers\ADMIN\CategoryController;
use App\Http\Controllers\ADMIN\CityAdminController;
use App\Http\Controllers\ADMIN\CommonController;
use App\Http\Controllers\ADMIN\CouponController;
use App\Http\Controllers\ADMIN\DashboardController;
use App\Http\Controllers\ADMIN\DeliveryBoyController;
use App\Http\Controllers\ADMIN\FaqController;
use App\Http\Controllers\ADMIN\MasterController;
use App\Http\Controllers\ADMIN\FeedbackController;
use App\Http\Controllers\ADMIN\InventoryController;
use App\Http\Controllers\ADMIN\LocationController;
use App\Http\Controllers\ADMIN\LoginController;
use App\Http\Controllers\ADMIN\NotificationController;
use App\Http\Controllers\ADMIN\OrderController;
use App\Http\Controllers\ADMIN\ProductController;
use App\Http\Controllers\ADMIN\ReportsController;
use App\Http\Controllers\ADMIN\ReturnsController;
use App\Http\Controllers\ADMIN\SocietyController;
use App\Http\Controllers\ADMIN\SubscriptionController;
use App\Http\Controllers\ADMIN\SupportController;
use App\Http\Controllers\ADMIN\TransactionController;
use App\Http\Controllers\ADMIN\UserController;
use App\Http\Controllers\ADMIN\WarehouseController;
use App\Http\Controllers\ADMIN\CollaboratesController;
use App\Http\Controllers\ADMIN\CareersController;



use App\Http\Controllers\WAREHOUSE\LoginController as WAREHOUSELoginController;
use App\Http\Controllers\WAREHOUSE\DashboardController as WAREHOUSEDashboardController;
use App\Http\Controllers\WAREHOUSE\DeliveryBoyController as WAREHOUSEDeliveryBoyController;
use App\Http\Controllers\WAREHOUSE\UserController as WAREHOUSEUserController;
use App\Http\Controllers\WAREHOUSE\OrderController as WAREHOUSEOrderController;
use App\Http\Controllers\WAREHOUSE\InventoryController as WAREHOUSEInventoryController;

use App\Http\Controllers\WAREHOUSE\WarehouseController as WAREHOUSEWarehouseController;


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::any('/', [LoginController::class, 'login_page'])->name('admin_login');

Route::any('login', [LoginController::class, 'login_page'])->name('admin_login');

Route::any('admin_login_request', [LoginController::class, 'admin_login_request'])->name('admin_login_request');

Route::any('logout', [LoginController::class, 'logout'])->name('admin_logout');



/**agency login */

Route::prefix('warehouse')->group(function () {
Route::get('/', [WAREHOUSELoginController::class, 'warehouse_login_page'])->name('warehouse_login');
Route::any('warehouse_login_request', [WAREHOUSELoginController::class, 'warehouse_login_request'])->name('warehouse_login_request');
});

Route::middleware('warehouseauth')->prefix('warehousepanel')->group(function () {
    Route::any('warehouse_dashboard', [WAREHOUSEDashboardController::class, 'index'])->name('warehouse_dashboard');
    Route::any('warehouseLogout', [WAREHOUSELoginController::class, 'warehouseLogout'])->name('warehouseLogout');

     Route::any('edit_warehousewarehouse/{id}', [WAREHOUSEWarehouseController::class, 'edit_warehousewarehouse'])->name('edit_warehousewarehouse');

         Route::post('update_warehousewarehouse/{id}', [WAREHOUSEWarehouseController::class, 'update_warehousewarehouse'])->name('update_warehousewarehouse');




 /**Delivery Boy */
    Route::any('changePasswordwarehouse', [WAREHOUSEDeliveryBoyController::class, 'changePasswordwarehouse'])->name('changePasswordwarehouse');

    Route::any('changePassword', [WAREHOUSEDeliveryBoyController::class, 'changePassword'])->name('changePassword');

    Route::any('add_deliveryboywarehouse', [WAREHOUSEDeliveryBoyController::class, 'add_deliveryboywarehouse'])->name('add_deliveryboywarehouse');
    Route::post('save_deliveryboywarehouse', [WAREHOUSEDeliveryBoyController::class, 'save_deliveryboywarehouse'])->name('save_deliveryboywarehouse');
    Route::any('edit_deliveryboywarehouse/{id}', [WAREHOUSEDeliveryBoyController::class, 'edit_deliveryboywarehouse'])->name('edit_deliveryboywarehouse');
    Route::post('update_deliveryboywarehouse/{id}', [WAREHOUSEDeliveryBoyController::class, 'update_deliveryboywarehouse'])->name('update_deliveryboywarehouse');
    Route::any('fetch_deliveryboyswarehouse', [WAREHOUSEDeliveryBoyController::class, 'fetch_deliveryboyswarehouse'])->name('fetch_deliveryboyswarehouse');
    Route::get('fetch_deliveries_by_deliveryboywarehouse', [WAREHOUSEDeliveryBoyController::class, 'fetch_deliveries_by_deliveryboywarehouse'])->name('fetch_deliveries_by_deliveryboywarehouse');
    
      Route::get('can_cash_hold_enablewarehouse/{id}', [WAREHOUSEUserController::class, 'can_cash_hold_enablewarehouse'])->name('can_cash_hold_enablewarehouse');
      Route::get('can_cash_hold_disablewarehouse/{id}', [WAREHOUSEUserController::class, 'can_cash_hold_disablewarehouse'])->name('can_cash_hold_disablewarehouse');
    Route::any('get_user_detailswarehouse/{id}', [WAREHOUSEUserController::class, 'get_user_detailswarehouse'])->name('get_user_detailswarehouse');


//order

     Route::get('fetch_new_orderswarehouse', [WAREHOUSEOrderController::class, 'fetch_new_orderswarehouse'])->name('fetch_new_orderswarehouse');
     Route::get('fetch_buyonce_orderswarehouse', [WAREHOUSEOrderController::class, 'fetch_buyonce_orderswarehouse'])->name('fetch_buyonce_orderswarehouse');
    Route::get('fetch_pending_orderswarehouse', [WAREHOUSEOrderController::class, 'fetch_pending_orderswarehouse'])->name('fetch_pending_orderswarehouse');
    Route::get('fetch_completedorderswarehouse', [WAREHOUSEOrderController::class, 'fetch_completedorderswarehouse'])->name('fetch_completedorderswarehouse');
    Route::get('fetch_cancelledorderswarehouse', [WAREHOUSEOrderController::class, 'fetch_cancelledorderswarehouse'])->name('fetch_cancelledorderswarehouse');

    Route::get('load_order_datawarehouse/{id}', [WAREHOUSEOrderController::class, 'load_order_datawarehouse'])->name('load_order_datawarehouse');
    Route::get('load_assign_deliveryBoy_datawarehouse/{id}', [WAREHOUSEOrderController::class, 'load_assign_deliveryBoy_datawarehouse'])->name('load_assign_deliveryBoy_datawarehouse');

    Route::post('assign_deliveryBoywarehouse/{id}', [WAREHOUSEOrderController::class, 'assign_deliveryBoywarehouse'])->name('assign_deliveryBoywarehouse');
    Route::any('delivered_orderwarehouse/{id}', [WAREHOUSEOrderController::class, 'delivered_orderwarehouse'])->name('delivered_orderwarehouse');

    Route::get('order_invoicewarehouse/{id}', [WAREHOUSEOrderController::class, 'order_invoicewarehouse'])->name('order_invoicewarehouse');
// inventoryproduct
 Route::any('fetch_inventoryproductswarehouse', [WAREHOUSEInventoryController::class, 'fetch_inventoryproductswarehouse'])->name('fetch_inventoryproductswarehouse');

    Route::any('manage_inventorywarehouse/{id}', [WAREHOUSEInventoryController::class, 'manage_inventorywarehouse'])->name('manage_inventorywarehouse');
    Route::post('save_inventorywarehouse', [WAREHOUSEInventoryController::class, 'save_inventorywarehouse'])->name('save_inventorywarehouse');
    Route::any('fetch_inventorywarehouse', [WAREHOUSEInventoryController::class, 'fetch_inventorywarehouse'])->name('fetch_inventorywarehouse');
    Route::any('fetch_all_inventorywarehouse', [WAREHOUSEInventoryController::class, 'fetch_all_inventorywarehouse'])->name('fetch_all_inventorywarehouse');
    
    Route::any('fetch_inventoryoutwarehouse', [WAREHOUSEInventoryController::class, 'fetch_inventoryoutwarehouse'])->name('fetch_inventoryoutwarehouse');


});




Route::middleware('adminauth')->group(function () {
    Route::any('dashboard', [DashboardController::class, 'index'])->name('admin_dashboard');

    /**edit admin profile */
    Route::any('edit_admin_profile', [UserController::class, 'edit_admin_profile'])->name('edit_admin_profile');
    Route::post('update_admin_profile', [UserController::class, 'update_admin_profile'])->name('update_admin_profile');


    /***settings */
    Route::get('settings_page', [CommonController::class, 'settings_page'])->name('settings_page');
    Route::post('settings_update', [CommonController::class, 'settings_update'])->name('settings_update');


    /***customer */
      Route::any('fetch_customers', [UserController::class, 'fetch_customers'])->name('fetch_customers');
      Route::any('get_user_details/{id}', [UserController::class, 'get_user_details'])->name('get_user_details');
      Route::get('cod_user_disable/{id}', [UserController::class, 'cod_user_disable'])->name('cod_user_disable');
      Route::get('cod_user_enable/{id}', [UserController::class, 'cod_user_enable'])->name('cod_user_enable');
      Route::get('user_enable/{id}', [UserController::class, 'user_enable'])->name('user_enable');
      Route::get('user_disable/{id}', [UserController::class, 'user_disable'])->name('user_disable');
      Route::get('can_cash_hold_enable/{id}', [UserController::class, 'can_cash_hold_enable'])->name('can_cash_hold_enable');
      Route::get('can_cash_hold_disable/{id}', [UserController::class, 'can_cash_hold_disable'])->name('can_cash_hold_disable');


    Route::get('fetch_carts', [UserController::class, 'fetch_carts'])->name('fetch_carts');
    Route::get('fetch_list',[UserController::class, 'fetch_list'])->name('fetch_list');




    /**categories */
    Route::any('fetch_categories', [CategoryController::class, 'fetch_categories'])->name('fetch_categories');
    Route::post('save_category', [CategoryController::class, 'save_category'])->name('save_category');
    Route::post('edit_category/{id}', [CategoryController::class, 'edit_category'])->name('edit_category');
    Route::get('delete_category/{id}', [CategoryController::class, 'delete_category'])->name('delete_category');
    Route::get('load_category_data/{id}', [CategoryController::class, 'load_category_data'])->name('load_category_data');

    /**Subcategories */
    Route::any('fetch_subcategories', [CategoryController::class, 'fetch_subcategories'])->name('fetch_subcategories');
    Route::post('save_subcategory', [CategoryController::class, 'save_subcategory'])->name('save_subcategory');
    Route::post('edit_subcategory/{id}', [CategoryController::class, 'edit_subcategory'])->name('edit_subcategory');
    Route::get('delete_subcategory/{id}', [CategoryController::class, 'delete_subcategory'])->name('delete_subcategory');
    Route::get('load_subcategory_data/{id}', [CategoryController::class, 'load_subcategory_data'])->name('load_subcategory_data');
    Route::get('subcategories_by_category_dropdown_html/{id}', [CategoryController::class, 'subcategories_by_category_dropdown_html'])->name('subcategories_by_category_dropdown_html');


    /**Banners */
    Route::any('fetch_banners', [BannerController::class, 'fetch_banners'])->name('fetch_banners');
    Route::post('save_banner', [BannerController::class, 'save_banner'])->name('save_banner');
    Route::post('edit_banner/{id}', [BannerController::class, 'edit_banner'])->name('edit_banner');
    Route::get('delete_banner/{id}', [BannerController::class, 'delete_banner'])->name('delete_banner');
    Route::get('load_banner_data/{id}', [BannerController::class, 'load_banner_data'])->name('load_banner_data');
    Route::get('add_banner', [BannerController::class, 'add_banner'])->name('add_banner');
    Route::get('update_banner/{id}', [BannerController::class, 'update_banner'])->name('update_banner');

    /**Products */
    Route::any('fetch_products', [ProductController::class, 'fetch_products'])->name('fetch_products');
    Route::any('fetch_deal_products', [ProductController::class, 'fetch_deal_products'])->name('fetch_deal_products');



    Route::any('fetch_outofstocks', [InventoryController::class, 'fetch_outofstocks'])->name('fetch_outofstocks');
    Route::any('fetch_inventoryproducts', [InventoryController::class, 'fetch_inventoryproducts'])->name('fetch_inventoryproducts');
    Route::any('manage_inventory/{id}', [InventoryController::class, 'manage_inventory'])->name('manage_inventory');
    Route::post('save_inventory', [InventoryController::class, 'save_inventory'])->name('save_inventory');
    Route::any('fetch_inventory', [InventoryController::class, 'fetch_inventory'])->name('fetch_inventory');
    Route::any('fetch_all_inventory', [InventoryController::class, 'fetch_all_inventory'])->name('fetch_all_inventory');

    Route::any('fetch_inventoryout', [InventoryController::class, 'fetch_inventoryout'])->name('fetch_inventoryout');
    Route::any('add_to_stock/{id}', [InventoryController::class, 'add_to_stock'])->name('add_to_stock');
    Route::any('fetch_inventory_by_warehouse/{id}', [InventoryController::class, 'fetch_inventory_by_warehouse'])->name('fetch_inventory_by_warehouse');



    Route::get('delete_product/{id}', [ProductController::class, 'delete_product'])->name('delete_product');
    Route::get('create_product', [ProductController::class, 'create_product'])->name('create_product');
    Route::post('save_product', [ProductController::class, 'save_product'])->name('save_product');
    Route::get('edit_product/{id}', [ProductController::class, 'edit_product'])->name('edit_product');
    Route::post('update_product/{id}', [ProductController::class, 'update_product'])->name('update_product');

    Route::get('create_deal_product', [ProductController::class, 'create_deal_product'])->name('create_deal_product');


    /**city admins */
    Route::any('add_cityadmin', [CityAdminController::class, 'add_cityadmin'])->name('add_cityadmin');
    Route::post('save_cityadmin', [CityAdminController::class, 'save_cityadmin'])->name('save_cityadmin');
    Route::any('edit_cityadmin/{id}', [CityAdminController::class, 'edit_cityadmin'])->name('edit_cityadmin');
    Route::post('update_cityadmin/{id}', [CityAdminController::class, 'update_cityadmin'])->name('update_cityadmin');
    Route::any('fetch_cityadmins', [CityAdminController::class, 'fetch_cityadmins'])->name('fetch_cityadmins');



    /**warehouses */
    Route::any('add_warehouse', [WarehouseController::class, 'add_warehouse'])->name('add_warehouse');
    Route::post('save_warehouse', [WarehouseController::class, 'save_warehouse'])->name('save_warehouse');
    Route::any('edit_warehouse/{id}', [WarehouseController::class, 'edit_warehouse'])->name('edit_warehouse');
    Route::post('update_warehouse/{id}', [WarehouseController::class, 'update_warehouse'])->name('update_warehouse');
    Route::any('fetch_warehouses', [WarehouseController::class, 'fetch_warehouses'])->name('fetch_warehouses');


    /**Delivery Boy */
    Route::any('add_deliveryboy', [DeliveryBoyController::class, 'add_deliveryboy'])->name('add_deliveryboy');
    Route::post('save_deliveryboy', [DeliveryBoyController::class, 'save_deliveryboy'])->name('save_deliveryboy');
    Route::any('edit_deliveryboy/{id}', [DeliveryBoyController::class, 'edit_deliveryboy'])->name('edit_deliveryboy');
    Route::post('update_deliveryboy/{id}', [DeliveryBoyController::class, 'update_deliveryboy'])->name('update_deliveryboy');
    Route::any('fetch_deliveryboys', [DeliveryBoyController::class, 'fetch_deliveryboys'])->name('fetch_deliveryboys');

 Route::get('cash_PayOut_deliveryboys/{id}', [DeliveryBoyController::class, 'cash_PayOut_deliveryboys'])->name('cash_PayOut_deliveryboys');
 
    /**Coupon  */
    Route::any('fetch_coupons', [CouponController::class, 'fetch_coupons'])->name('fetch_coupons');
    Route::any('create_coupon', [CouponController::class, 'create_coupon'])->name('create_coupon');
    Route::post('save_coupon', [CouponController::class, 'save_coupon'])->name('save_coupon');
    Route::any('edit_coupon/{id}', [CouponController::class, 'edit_coupon'])->name('edit_coupon');
    Route::post('update_coupon/{id}', [CouponController::class, 'update_coupon'])->name('update_coupon');
    Route::get('delete_coupon/{id}', [CouponController::class, 'delete_coupon'])->name('delete_coupon');
    Route::get('fetch_couponhistory', [CouponController::class, 'fetch_couponhistory'])->name('fetch_couponhistory');



    /**send notification */
    Route::any('send_notification_to_customer', [NotificationController::class, 'send_notification_to_customer'])->name('send_notification_to_customer');
    Route::any('store_send_notification', [NotificationController::class, 'store_send_notification'])->name('store_send_notification');
    Route::any('send_notification_to_deliveryboy', [NotificationController::class, 'send_notification_to_deliveryboy'])->name('send_notification_to_deliveryboy');


    /**ORDERS */
    Route::get('fetch_deliveries_by_deliveryboy', [DeliveryBoyController::class, 'fetch_deliveries_by_deliveryboy'])->name('fetch_deliveries_by_deliveryboy');
    Route::get('fetch_orders', [OrderController::class, 'fetch_orders'])->name('fetch_orders');
    Route::get('fetch_buyonce_orders', [OrderController::class, 'fetch_buyonce_orders'])->name('fetch_buyonce_orders');
    Route::get('fetch_subscribe_orders', [OrderController::class, 'fetch_subscribe_orders'])->name('fetch_subscribe_orders');

    Route::get('load_order_data/{id}', [OrderController::class, 'load_order_data'])->name('load_order_data');
    Route::get('load_assign_deliveryBoy_data/{id}', [OrderController::class, 'load_assign_deliveryBoy_data'])->name('load_assign_deliveryBoy_data');
    Route::post('assign_deliveryBoy/{id}', [OrderController::class, 'assign_deliveryBoy'])->name('assign_deliveryBoy');
    Route::post('cancel_order/{id}', [OrderController::class, 'cancel_order'])->name('cancel_order');
    Route::any('delivered_order/{id}', [OrderController::class, 'delivered_order'])->name('delivered_order');

    Route::get('load_order_item_data/{id}', [OrderController::class, 'load_order_item_data'])->name('load_order_item_data');
    Route::get('order_invoice/{id}', [OrderController::class, 'order_invoice'])->name('order_invoice');
    Route::get('fetch_completedorders', [OrderController::class, 'fetch_completedorders'])->name('fetch_completedorders');
    Route::get('fetch_cancelledorders', [OrderController::class, 'fetch_cancelledorders'])->name('fetch_cancelledorders');
    Route::get('fetch_floting_orders', [OrderController::class, 'fetch_floting_orders'])->name('fetch_floting_orders');
    Route::get('fetch_cash_deposits', [OrderController::class, 'fetch_cash_deposits'])->name('fetch_cash_deposits');
    Route::get('cash_deposits_verified/{id}', [OrderController::class, 'cash_deposits_verified'])->name('cash_deposits_verified');
    Route::post('refund_for_order/{id}', [OrderController::class, 'refund_for_order'])->name('refund_for_order');
    Route::get('stop_subscription/{id}', [OrderController::class, 'stop_subscription'])->name('stop_subscription');
    Route::get('search', [OrderController::class, 'search']);
    

    /**returns */
    Route::get('fetch_returnsorder', [ReturnsController::class, 'fetch_returnsorder'])->name('fetch_returnsorder');
    Route::get('fetch_pending_orders', [OrderController::class, 'fetch_pending_orders'])->name('fetch_pending_orders');

    
    Route::post('refund_for_return/{id}', [ReturnsController::class, 'refund_for_return'])->name('refund_for_return');


    /**Subscriptions and BuyOnces */
    Route::get('fetch_subscriptions', [SubscriptionController::class, 'fetch_subscriptions'])->name('fetch_subscriptions');
    Route::get('fetch_buyonces', [SubscriptionController::class, 'fetch_buyonces'])->name('fetch_buyonces');

    Route::get('fetch_quantity_orders', [SubscriptionController::class, 'fetch_quantity_orders'])->name('fetch_quantity_orders');
    Route::get('quantity_orders_next_day', [SubscriptionController::class, 'quantity_orders_next_day'])->name('quantity_orders_next_day');


    Route::any('todays_tomorrows_buyonce_delivery', [SubscriptionController::class, 'todays_tomorrows_buyonce_delivery'])->name('todays_tomorrows_buyonce_delivery');

    Route::any('today_deliveries', [SubscriptionController::class, 'today_deliveries'])->name('today_deliveries');
    Route::any('tomorrows_deliveries', [SubscriptionController::class, 'tomorrows_deliveries'])->name('tomorrows_deliveries');



    /**wallet */
    Route::post('wallet_recharge/{id}', [UserController::class, 'wallet_recharge'])->name('wallet_recharge');




    /**Locations */
    Route::any('fetch_locations', [LocationController::class, 'fetch_locations'])->name('fetch_locations');
    Route::post('save_location', [LocationController::class, 'save_location'])->name('save_location');
    Route::post('edit_location/{id}', [LocationController::class, 'edit_location'])->name('edit_location');
    Route::get('delete_location/{id}', [LocationController::class, 'delete_location'])->name('delete_location');
    Route::get('load_location_data/{id}', [LocationController::class, 'load_location_data'])->name('load_location_data');
    Route::get('add_location', [LocationController::class, 'add_location'])->name('add_location');
    Route::get('update_location/{id}', [LocationController::class, 'update_location'])->name('update_location');



    /**Societies */
    Route::any('fetch_societys', [SocietyController::class, 'fetch_societys'])->name('fetch_societys');
    Route::post('save_society', [SocietyController::class, 'save_society'])->name('save_society');
    Route::post('edit_society/{id}', [SocietyController::class, 'edit_society'])->name('edit_society');
    Route::get('delete_society/{id}', [SocietyController::class, 'delete_society'])->name('delete_society');
    Route::get('load_society_data/{id}', [SocietyController::class, 'load_society_data'])->name('load_society_data');
    Route::get('add_society', [SocietyController::class, 'add_society'])->name('add_society');
    Route::get('update_society/{id}', [SocietyController::class, 'update_society'])->name('update_society');



    /**feedbacks */
    Route::get('fetch_feedbacks', [FeedbackController::class, 'fetch_feedbacks'])->name('fetch_feedbacks');



    /**faqs */
    Route::any('fetch_faqs', [FaqController::class, 'fetch_faqs'])->name('fetch_faqs');
    Route::post('save_faq', [FaqController::class, 'save_faq'])->name('save_faq');
    Route::post('edit_faq/{id}', [FaqController::class, 'edit_faq'])->name('edit_faq');
    Route::get('delete_faq/{id}', [FaqController::class, 'delete_faq'])->name('delete_faq');
    Route::get('load_faq_data/{id}', [FaqController::class, 'load_faq_data'])->name('load_faq_data');




    /**master_unit */
    Route::any('fetch_units', [MasterController::class, 'fetch_units'])->name('fetch_units');
    Route::post('save_unit', [MasterController::class, 'save_unit'])->name('save_unit');
    Route::post('edit_unit/{id}', [MasterController::class, 'edit_unit'])->name('edit_unit');
    Route::get('delete_unit/{id}', [MasterController::class, 'delete_unit'])->name('delete_unit');
    Route::get('load_unit_data/{id}', [MasterController::class, 'load_unit_data'])->name('load_unit_data');


    /**Transaction */
    Route::any('fetch_wallettxns', [TransactionController::class, 'fetch_wallettxns'])->name('fetch_wallettxns');
    Route::any('fetch_onlinetxns', [TransactionController::class, 'fetch_onlinetxns'])->name('fetch_onlinetxns');

    Route::any('fetch_walletusers', [TransactionController::class, 'fetch_walletusers'])->name('fetch_walletusers');


    /**reports */
    Route::any('fetch_wishlists', [ReportsController::class, 'fetch_wishlists'])->name('fetch_wishlists');
    Route::any('fetch_referrals', [ReportsController::class, 'fetch_referrals'])->name('fetch_referrals');


    /***support */
    Route::any('fetch_supports', [SupportController::class, 'fetch_supports'])->name('fetch_supports');
    Route::any('fetch_pending_supports', [SupportController::class, 'fetch_pending_supports'])->name('fetch_pending_supports');
    Route::any('fetch_completed_supports', [SupportController::class, 'fetch_completed_supports'])->name('fetch_completed_supports');

    Route::any('mark_as_solved/{id}', [SupportController::class, 'mark_as_solved'])->name('mark_as_solved');

    /***careers */
    Route::any('fetch_careers', [CareersController::class, 'fetch_careers'])->name('fetch_careers');
    Route::any('fetch_pending_careers', [CareersController::class, 'fetch_pending_careers'])->name('fetch_pending_careers');
    Route::any('fetch_completed_careers', [CareersController::class, 'fetch_completed_careers'])->name('fetch_completed_careers');

    Route::any('mark_as_solved_careers/{id}', [CareersController::class, 'mark_as_solved_careers'])->name('mark_as_solved_careers');

/***collaborates */
    Route::any('fetch_collaborates', [CollaboratesController::class, 'fetch_collaborates'])->name('fetch_collaborates');
    Route::any('fetch_pending_collaborates', [CollaboratesController::class, 'fetch_pending_collaborates'])->name('fetch_pending_collaborates');
    Route::any('fetch_completed_collaborates', [CollaboratesController::class, 'fetch_completed_collaborates'])->name('fetch_completed_collaborates');

    Route::any('mark_as_solved_collaborates/{id}', [CollaboratesController::class, 'mark_as_solved_collaborates'])->name('mark_as_solved_collaborates');

Route::any('search-2',[OrderController::class, 'search']);
Route::any('pending_order',[OrderController::class, 'pending_order'])->name('pending_order');
Route::any('order_delivered',[OrderController::class, 'order_delivered'])->name('order_delivered');

/**blog */
Route::post('store',[BlogController::class,'store'])->name('store');
Route::get('show',[BlogController::class,'show'])->name('show');
Route::get('delete/{id}',[BlogController::class,'destroy'])->name('delete');
Route::get('edit/{id}',[BlogController::class,'edit'])->name('edit');
Route::post('update/{id}',[BlogController::class,'update'])->name('update');


});

// Route::get('/', function () {
//     return view('welcome');
// });
