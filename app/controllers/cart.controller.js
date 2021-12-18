const { successRes, failedRes } = require("../helpers/response.helper");
const { currentTimeStamp, get_location_id, generateOTP } = require("../helpers/user.helper");
const { Cart, Product, sequelize, Address, Order, OrderHistory, User, Transaction, Delivery, Location } = require("../models");
const { fetch_product_price } = require("./product.controller");
const moment = require('moment');
const { send_new_order_notification } = require("../helpers/notification.helper");
const { Op } = require("sequelize");
const { new_order_function } = require("./deliveryboy/order.controller");
const { makeRequest } = require("../helpers/axios.helper");
const { php_apis } = require("../config/app.config");


// const count_stock_product = async (product_id,location_id) => {
//     const location = await Location.findOne({
//         where:{
//             id:location_id
//         }
//     });
//     if(location){

//         const query = `select * from users where CONCAT('|', pincode_allowance, '|') like '%|${pincode}|%' AND status = 1  AND user_type=5`;
//         await sequelize.query(query,{
//             model: User,
//             mapToModel: true // pass true here if you have any mapped fields
//         })
//         .then(async (userss)=>{

//         });

//     }
// }

const check_if_product_cart = async (user_id,product_id) => {

    const data =await Cart.findOne({
        where:{
            user_id:user_id,
            product_id:product_id
        }
    })
    return data;
}

const add_cart = async (req,res) => {
    const {user_id,product_id,qty}=req.body;
    const dateTime = currentTimeStamp();

    if(qty==0){

            await Cart.destroy({
                where:{
                    user_id,
                    product_id
                }
            })
            res.json(successRes('cart removed'))
    }else{
        var checkifcarted = await Cart.findOne({
            where:{
                user_id:user_id,
                product_id:product_id
            }
        })
        if(checkifcarted){  /**edit cart */
            const updateData = {
                qty:qty,
                updated_at:dateTime
            }
            const update = await Cart.update(updateData,{
                where:{
                    id:checkifcarted.id
                }
            })
            if(updateData){
                checkifcarted.qty = qty;
                res.json(successRes('added',checkifcarted))
            }else{
                res.json(failedRes('failed'))
            }
        }else{ /**add to cart */
            const storeData = {
                user_id:user_id,
                product_id:product_id,
                qty:1,
                created_at:dateTime,
                updated_at:dateTime
            }
            const strdata = await Cart.create(storeData)
            if(strdata){
                res.json(successRes('added',strdata))
            }else{
                res.json(failedRes('something went wrong'))
            }
        }
    }
    // const storedata = {
    //     product_id:
    // };

}

const remove_cart_item = async (req,res) => {
    const {user_id,id}=req.body;
    await Cart.destroy({
        where:{
            id:id,
            user_id:user_id
        }
    })

    res.json(successRes('removes'))
}


const get_cart_items = async (req,res) => {
    const {user_id} = req.body;
    const data = await get_cart_items_fun(user_id,req);
    res.json(data)
}


const get_cart_items_fun = async (user_id,req) => {
    var total_amount = 0;
    var delivery_charges = 20;
    const location_id = await get_location_id(req)
    var query = `SELECT pd.*,pc.qty,pc.id as cart_id FROM products as pd INNER JOIN carts pc ON pd.id=pc.product_id WHERE pc.user_id=${user_id} AND pd.status=1 ORDER BY pd.name ASC `;

    const products = await sequelize.query(query, {
        model: Product,
        mapToModel: true // pass true here if you have any mapped fields
      }).then(async(carts)=>{
        for (let cart of carts) {

            const price_conf = await fetch_product_price(cart.id,location_id);
            if(price_conf){
                selling_price = parseFloat(price_conf.selling_price)
                cart.dataValues.mrp =price_conf.mrp ;
                cart.dataValues.selling_price =price_conf.selling_price ;
                cart.dataValues.discount =price_conf.discount ;
                cart.dataValues.discount_type =price_conf.discount_type ;
                cart.dataValues.location_id =price_conf.location_id ;
                cart.dataValues.stock =price_conf.stock ;
                var selling_price = price_conf.selling_price;
                const amount = parseFloat(selling_price)*parseInt(cart.dataValues.qty)
                total_amount+=amount
                cart.dataValues.cart_amount = amount;
            }else{
                continue;
            }

           
        }
        return await carts
    })

    const final_amount = total_amount + delivery_charges;

    if(products){
        return ({
            status:true,
            subtotal:total_amount,
            discount:0,
            store_charges:0,
            delivery_charges:delivery_charges,
            total_amount:final_amount,
            data:products
        })
    }else{
        return (failedRes('no records found',products))
    }
}

const remove_stock_qty_from_location = async (product_ids=[],stocks=[],location_id,user_id,order_id) => {
    console.log('remove_stock_qty_from_location');
    if(product_ids.length){
        for (let i = 0; i < product_ids.length; i++) {
            const product_id = product_ids[i];
           await makeRequest('POST',php_apis.remove_stock_from_location,{},{
                location_id:location_id,
                user_id:user_id,
                product_id:product_id,
                order_id:order_id,
                stock:stocks[i]
            })
            .then((rs)=>console.log(rs))
            .catch(err=>console.log(err))
            
        }
    }
}


const delete_stock_qty_from_location = async (location_id,user_id,order_id) => {
    await makeRequest('POST',php_apis.add_stock_from_location,{},{
        location_id:location_id,
        user_id:user_id,
        order_id:order_id,
    })
    .then((rs)=>console.log(rs))
    .catch(err=>console.log(err))
}


const generate_order = async (req,res) => {
    const {user_id,payment_mode,address_id,delivery_type,schedule_date,schedule_time} = req.body;
    var product_ids = [];
    var stocks = [];
    const location_id = await get_location_id(req)
    console.log('====================================');
    // console.log(req.body);
    console.log('====================================');
    var {txn_id} = req.body;
    const address = await Address.findOne({
        where:{
            id:address_id,
            user_id:user_id,
        },
        order:[['id','desc']]
    })
    const user = await User.findOne({
        where:{
            id:user_id
        }
    })
    const billdata = await get_cart_items_fun(user_id,req);
    const orderhistory = [];
    if(billdata.status){
        const dateTime = currentTimeStamp()
        const cartdata = billdata.data;

        for (let ct of cartdata) {
            product_ids.push(ct.id)
            stocks.push(ct.dataValues.qty)
            const obj = {
                user_id,
                product_id:ct.id,
                product_name:ct.name,
                product_mrp:ct.mrp,
                product_image:ct.imageUrl,
                product_discount:ct.discount,
                order_type:1,
                qty:ct.dataValues.qty,
                price:ct.dataValues.cart_amount,
                unit_price:ct.selling_price,
                actual_qty:ct.net_wt,
                unit:ct.unit,
                created_at:dateTime,
                updated_at:dateTime
            }
            orderhistory.push(obj)
        }

        const storedata = {
            user_id,
            order_type:1,
            delivery_type,
            schedule_date,
            schedule_time,
            subtotal:billdata.subtotal,
            delivery_charges:billdata.delivery_charges,
            payable_amount:billdata.total_amount,
            shipping_name:address.name,
            shipping_address_type:address.address_type,
            shipping_phone:address.phone,
            shipping_flat:address.flat ,
            shipping_pincode:address.pincode ,
            shipping_location:address.location,
            shipping_landmark:address.landmark,
            shipping_area:address.main_society,
            lat:address.lat ,
            lng:address.lng ,
            txn_id,
            confirmation_otp:generateOTP(),
            payment_mode:payment_mode,
            is_paid : payment_mode=='cod'?0:1,
            created_at:dateTime,
            updated_at:dateTime
        }

        
        const t = await sequelize.transaction();
        try {
            const order = await Order.create(storedata
                , { transaction: t });
            const bookingInsertID = order.id;

            for (let ord of orderhistory) {
                ord.order_id=bookingInsertID;
            }
            await OrderHistory.bulkCreate(orderhistory
                , { transaction: t });

            await Cart.destroy({
                where:{
                    user_id:user_id
                }
            }, { transaction: t })

            if(payment_mode=='wallet'){
                 txn_id = bookingInsertID+user_id
                const old_wallet = parseFloat(user.wallet);
                const txn_amount = parseFloat(billdata.total_amount);
                if(old_wallet < txn_amount){
                    return res.json(failedRes('wallet amount is not sufficient! Please recharge first!'))
                }
                const new_wallet = old_wallet - txn_amount;
                var transactiondata = {
                    user_id:user_id,
                    txn_name:'Deduction against orderID #'+bookingInsertID,
                    payment_mode:'wallet',
                    order_id:bookingInsertID,
                    order_txn_id:txn_id,
                    txn_for:'booking',
                    type:'debit',
                    old_wallet:old_wallet,
                    txn_amount:txn_amount,
                    update_wallet:new_wallet,
                    status:1,
                    created_at:dateTime,
                    updated_at:dateTime,
                };
                await User.update({
                    wallet:new_wallet
                  },{
                    where:{
                      id:user_id
                    }
                },  { transaction: t })
                 await Transaction.create(transactiondata
                , { transaction: t });
            }else if(payment_mode == 'online'){
                var transactiondata2 = {
                    user_id:user_id,
                    txn_name:'Booking orderID #'+bookingInsertID,
                    payment_mode:'online',
                    order_id:bookingInsertID,
                    order_txn_id:txn_id,
                    txn_for:'booking',
                    type:'debit',
                    txn_amount:billdata.total_amount,
                    status:1,
                    created_at:dateTime,
                    updated_at:dateTime,
                };
                await Transaction.create(transactiondata2
                    , { transaction: t });
            }


            await t.commit();
            await assign_warehouse_deliveryboy(bookingInsertID);
            remove_stock_qty_from_location(product_ids,stocks,location_id,user_id,bookingInsertID)

            const assigning_arr =await Delivery.findAll({
                where:{
                    order_id:bookingInsertID
                }
            }).then(async (asigns)=>{
                if(asigns){
                    for (let usr of asigns) {
                        const datafetch = await new_order_function(usr.delivery_boy_id)
                        if(datafetch){
                            res.io.sockets.in(usr.delivery_boy_id).emit('new_orders',successRes('',datafetch))

                        }
                    }

                }
                return asigns

            })


            res.json(successRes('done!!',order))
        } catch (error) {
            res.json(failedRes('something went wrong'))
        }

    }else{
        res.json(failedRes('failed!'))
    }

}

const assign_warehouse_deliveryboy = async (order_id) => {

    const order = await Order.findOne({
        where:{
            id:order_id
        }
    })
    var deliveryboysarr = [];
    const dateTime = currentTimeStamp()
    if(!order){
        return false;
    }else{
        const pincode = order.shipping_pincode;
        const query = `select * from users where CONCAT('|', pincode_allowance, '|') like '%|${pincode}|%' AND status = 1  AND user_type=5`;
        await sequelize.query(query,{
            model: User,
            mapToModel: true // pass true here if you have any mapped fields
        })
        .then(async (userss)=>{

            for (let usr of userss) {
                const deliveryboys = await User.findAll({
                    where:{
                        warehouse_id:usr.id,
                        is_online:1,
                        status:1
                    }
                })
                .then(async (dbs)=>{
                    var assigns_arr = []
                    for (let dby of dbs) {
                        const storedata = {
                            warehouse_id:usr.id,
                            status:2,
                            delivery_boy_id:dby.id,
                            order_id:order.id,
                            created_at:dateTime,
                            updated_at:dateTime
                        }
                        // deliveryboysarr.push(dby.id)
                        // const datafetch = await new_order_function(dby.id)
                        // res.io.sockets.in(dby.id).emit('new_orders',)
                        send_new_order_notification(order.user_id,dby.id,order.id,pincode)
                        assigns_arr.push(storedata)
                        
                    }
                    assigns_arr.length ? Delivery.bulkCreate(assigns_arr) : ''
                })

                
            }
            return await userss

        })


    }
}

const order_details = async (req,res) => {
    const {user_id,order_id} = req.body;
    const orders = await Order.findOne({
        where:{
            id:order_id
        },
        order:[['id','desc']]
    }).then(async (od) => {
        if(od){
            const order_items = await OrderHistory.findAll({
                where:{
                    order_id:od.id
                }
            })
            od.dataValues.user =await User.findOne({
                attributes:['id','name','phone','email'],
                where:{
                    id:od.user_id
                }
            })
            od.dataValues.order_items = order_items
        }

           
        return await od
    })


    res.json(orders ? successRes('',orders):failedRes(''));
}


const my_order_history = async (req,res) => {
    const {user_id,type} = req.body;
    var whereobj  = {};
    if(type == 'ongoing'){
        whereobj = {
            user_id:user_id,
            status:{
                [Op.in]:[0,1]
            }
        }
    }
    else if(type == 'completed'){
        whereobj = {
            user_id:user_id,

            status:{
                [Op.in]:[2]
            }
        }
    }
    else if(type == 'cancelled'){
        whereobj = {
            user_id:user_id,

            status:{
                [Op.in]:[3,4]
            }
        }
    }
    const orders = await Order.findAll({
        where:whereobj,
        order:[['id','desc']]
    }).then(async (ords) => {
        for (let od of ords) {

            const order_items = await OrderHistory.findAll({
                where:{
                    order_id:od.id
                }
            })
            od.dataValues.user =await User.findOne({
                attributes:['id','name','phone','email'],
                where:{
                    id:od.user_id
                }
            })
            od.dataValues.order_items = order_items
        }
        return await ords
    })
    res.json(orders ? successRes('',orders):failedRes(''));

}

const cancel_order_user = async (req,res) => {
    const {user_id,order_id,cancel_reason} = req.body
    const location_id = await get_location_id(req)

    const order = await Order.findOne({
        where:{
            user_id:user_id,
            id:order_id,
            status:{
                [Op.in]:[0,1]

            }
        }
    });
    if(order){

        const updatedata = await Order.update({
            status:3,
            cancel_reason,
            cancel_by:'customer'
        },{
            where:{
                id:order_id
            }
        })

        if(updatedata){
            delete_stock_qty_from_location(location_id,user_id,order_id)
            res.json(successRes('success'))
        }else{
            res.json(failedRes('something went wrong!'))
        }


    }else{
        res.json(failedRes('something went wrong!'))
    }
}
module.exports = {
   add_cart,
   remove_cart_item,
   get_cart_items,
   check_if_product_cart,
   generate_order,
   my_order_history,
   order_details,
   cancel_order_user
};
