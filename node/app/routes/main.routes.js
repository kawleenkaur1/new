const validation = require('../middleware/validation.middleware');
const UserController = require('../controllers/user.controller.js');
const HomeController = require('../controllers/home.controller');
const LocationController = require('../controllers/location.controller');
const ProductController = require('../controllers/product.controller');

const CartController = require('../controllers/cart.controller');
const AddressController = require('../controllers/address.controller');
const DeliveryConfigController = require('../controllers/deliveryconfig.controller');

const TransactionCntroller = require('../controllers/transaction.controller');
const PaymentController = require('../controllers/payment.controller');

const DeliveryOrderController = require('../controllers/deliveryboy/order.controller');



const multer = require("multer");
var upload = multer();



const jwt = require('jsonwebtoken');
const { jwtsecret } = require('../config/app.config');
const { failedRes } = require('../helpers/response.helper');
const { User } = require('../models');


var authenticateUser =  (req, res, next)=> {
    const authorization=req.headers.authorization;

    // console.log('language',req.headers);

    if(authorization==undefined){
        res.json({
            'status':false,
            'message':'Unauthorized'
        })
        return;
    }else{
        jwt.verify(authorization, jwtsecret,async (err, decoded) => {
            if (err)
            return res.status(401).json(failedRes('Failed to authenticate token.',null));
    
            // if everything good, save to request for use in other routes
            req.userId = decoded.id;
            req.user_id = decoded.id;
            req.body.userId = decoded.id;
            req.body.user_id = decoded.id;
    
    
            const user = await User.findOne({
                where:{id:decoded.id},
            })
            req.body.user=user;

    
            next();
        });
    }
   
    // next()
}


var authenticateUser2 =  (req, res, next)=> {
    const authorization=req.headers.authorization;
    // console.log('language',req.headers);


    if(authorization==undefined || !authorization){
        // req.userId = 0;
        // req.user_id = 0;
        req.body.userId = 0;
        req.body.user_id = 0;
        next();
    }else{
        jwt.verify(authorization, jwtsecret,async (err, decoded) => {
            if (err)
            return res.status(401).json(failedRes('Failed to authenticate token.',null));

            // if everything good, save to request for use in other routes
            req.userId = decoded.id;
            req.user_id = decoded.id;
            req.body.userId = decoded.id;
            req.body.user_id = decoded.id;

            const user = await User.findOne({
                where:{id:decoded.id},
            })
            req.body.user=user;

            next();
        });
    }
    // next()
}


module.exports = (app) => {
    var router = require("express").Router();

    /**user auth */
    router.post('/login_otp_app',UserController.login_otp_app)
    router.post('/verify_login_otp',UserController.verify_login_otp)

    router.post('/user_login',UserController.user_login)
    router.post('/user_signup_otp',UserController.user_signup_otp)
    router.post('/forgot_otp',UserController.forgot_otp)
    router.post('/forgot_verify',UserController.forgot_verify)
    router.post('/forgot_verify_otp',UserController.forgot_verify_otp)
    router.post('/forgot_change_password',UserController.forgot_change_password)
    
    router.post('/resend_otp',UserController.resend_otp)
    router.post('/regenerateToken',UserController.regenerateToken)
    router.post('/user_signup_verify',UserController.user_signup_verify)
    router.all('/user_profile',authenticateUser,UserController.user_profile)
    router.post('/edit_user_profile',authenticateUser,UserController.edit_user_profile)
    router.post('/change_password_by_old_password',authenticateUser,UserController.change_password_by_old_password)
    router.all('/get_website_banners',UserController.get_website_banners)
    router.all('/resend_otp_app',UserController.resend_otp_app)
    
    
    
    router.all('/fetch_navbar_categories',HomeController.fetch_navbar_categories)
    router.all('/fetch_homepage_web',authenticateUser2,HomeController.fetch_homepage_web)
    router.all('/fetch_homepage_app',authenticateUser2,HomeController.fetch_homepage_app)
    router.all('/fetch_homepage_app_one',authenticateUser2,HomeController.fetch_homepage_app_one)
    router.all('/fetch_homepage_app_two',authenticateUser2,HomeController.fetch_homepage_app_two)
    router.all('/fetch_homepage_app_three',authenticateUser2,HomeController.fetch_homepage_app_three)
    router.all('/fetch_homepage_web_auth',authenticateUser,HomeController.fetch_homepage_web)
    router.all('/add_to_wishlist',authenticateUser,ProductController.add_to_wishlist)
    router.all('/remove_to_wishlist',authenticateUser,ProductController.remove_to_wishlist)
    router.all('/fetch_wishlists',authenticateUser,ProductController.fetch_wishlists)
    
    
    router.all('/fetch_showcase_category',HomeController.fetch_showcase_category)
    router.all('/fetch_showcase_products',authenticateUser2,HomeController.fetch_showcase_products)
    router.all('/fetch_navbar_category_product',HomeController.fetch_navbar_category_product)
    router.all('/get_products_by_filters',authenticateUser2,HomeController.get_products_by_filters)
    router.all('/get_settings',HomeController.get_settings)
    router.all('/get_testimonials',HomeController.get_testimonials)
    router.all('/get_recipes',HomeController.get_recipes)
     
    router.post('/add_support_data',authenticateUser2,HomeController.add_support_data)
    router.post('/add_career_data',authenticateUser2,HomeController.add_career_data)
    router.post('/add_collaborate_data',authenticateUser2,HomeController.add_collaborate_data)
    router.all('/fetch_faqs',HomeController.fetch_faqs)

    
    

    router.all('/fetch_locations',LocationController.fetch_locations)
    router.all('/fetch_areas',LocationController.fetch_areas)
    
    router.post('/fetch_location_by_lat_lon',LocationController.fetch_location_by_lat_lon)
    router.post('/check_if_service_location',LocationController.check_if_service_location)
    router.post('/fetch_location_by_id',LocationController.fetch_location_by_id)
    router.post('/fetch_society_by_id',LocationController.fetch_society_by_id)
    
    router.post('/fetch_product_details',authenticateUser2,ProductController.fetch_product_details)
    router.post('/fetch_products_by_category',authenticateUser2,ProductController.fetch_products_by_category)
    router.post('/you_may_like',authenticateUser2,ProductController.you_may_like)
    
    router.post('/search_product',authenticateUser2,ProductController.search_product)
    router.all('/fetch_combos',authenticateUser2,ProductController.fetch_combos)

    

    router.post('/add_cart',authenticateUser,CartController.add_cart)
    router.post('/remove_cart_item',authenticateUser,CartController.remove_cart_item)
    router.post('/get_cart_items',authenticateUser,CartController.get_cart_items)
    router.post('/generate_order',authenticateUser,CartController.generate_order)
    router.post('/my_order_history',authenticateUser,CartController.my_order_history)
    router.post('/order_details',authenticateUser,CartController.order_details)
    router.post('/cancel_order_user',authenticateUser,CartController.cancel_order_user)
    
    
    /**address */
    router.post('/add_address',authenticateUser,AddressController.add_address)
    router.post('/delete_address',authenticateUser,AddressController.delete_address)
    router.post('/fetch_addresses',authenticateUser,AddressController.fetch_addresses)

    
    router.all('/deliveryconfig',DeliveryConfigController.deliveryconfig)

    router.all('/wallet_plans',TransactionCntroller.wallet_plans)

    router.all('/recharge_user_wallet',authenticateUser,TransactionCntroller.recharge_user_wallet)
    router.all('/wallet_history',authenticateUser,TransactionCntroller.wallet_history)


    router.post('/generate_order_req',PaymentController.generate_order_req)
    router.post('/recharge_user_wallet_web',authenticateUser,PaymentController.recharge_user_wallet)














    /***delivery boy app */
    router.post('/deliveryapp/login_otp_app',UserController.login_otp_delivery_app)
    router.post('/deliveryapp/verify_login_otp',UserController.verify_login_deliveryapp)
    router.post('/deliveryapp/user_profile',authenticateUser,UserController.user_profile)


    router.post('/deliveryapp/start_duty',authenticateUser,DeliveryOrderController.start_duty)
    router.post('/deliveryapp/off_duty',authenticateUser,DeliveryOrderController.off_duty)
    router.post('/deliveryapp/my_orders',authenticateUser,DeliveryOrderController.my_orders)
    router.post('/deliveryapp/toggle_online_status',authenticateUser,DeliveryOrderController.toggle_online_status)
    router.post('/deliveryapp/complete_order',authenticateUser,DeliveryOrderController.complete_order)
    router.post('/deliveryapp/complete_order_cod',authenticateUser,DeliveryOrderController.complete_order_cod)
    router.post('/deliveryapp/get_earning_report',authenticateUser,DeliveryOrderController.get_earning_report)
    router.post('/deliveryapp/get_floating_cash',authenticateUser,DeliveryOrderController.get_floating_cash)
    router.post('/deliveryapp/get_shift_timing',authenticateUser,DeliveryOrderController.get_shift_timing)
    router.post('/deliveryapp/new_orders',authenticateUser,DeliveryOrderController.new_orders)
    router.post('/deliveryapp/accept_order',authenticateUser,DeliveryOrderController.accept_order)
    router.post('/deliveryapp/cancel_order',authenticateUser,DeliveryOrderController.cancel_order)
    router.post('/deliveryapp/pickup_order',authenticateUser,DeliveryOrderController.pickup_order)
    router.post('/deliveryapp/completed_order',authenticateUser,DeliveryOrderController.completed_order)
    router.post('/deliveryapp/cancelled_order',authenticateUser,DeliveryOrderController.cancelled_order)
    router.post('/deliveryapp/send_confirmation_otp',authenticateUser,DeliveryOrderController.send_confirmation_otp)
    router.post('/deliveryapp/add_review_to_customer',authenticateUser,DeliveryOrderController.add_review_to_customer)
    router.post('/deliveryapp/complete_order_online',authenticateUser,DeliveryOrderController.complete_order_online)
    
    
    router.post('/deliveryapp/floating_cash_history',authenticateUser,DeliveryOrderController.floating_cash_history)
    router.post('/deliveryapp/get_notifications',authenticateUser,HomeController.get_notifications)
    router.all('/deliveryapp/get_settings',HomeController.get_settings)
    router.all('/send_test_notification',HomeController.send_test_notification)
    router.all('/fetch_press_release',HomeController.fetch_press_release)
    router.all('/fetch_partners',HomeController.fetch_partners)
    router.all('/send_mail_test',HomeController.send_mail_test)

    
    // regenerateToken

    // create_broadcast
    // for parsing multipart/form-data
    app.use(upload.array());

    app.use("/api", router);
};
