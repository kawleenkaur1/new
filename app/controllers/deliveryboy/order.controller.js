const { Op } = require("sequelize");
const { Order, OrderHistory, User, Delivery,sequelize, ReviewUser, Inventory } = require("../../models");
const { successRes, failedRes } = require("../../helpers/response.helper");
const { currentTimeStamp } = require("../../helpers/user.helper");
const { send_otp_msg91 } = require("../../helpers/msg91.helper");




const my_orders = async (req,res) => {
    const {user_id,type} = req.body;
    var whereobj  = {};
    if(type == 'ongoing'){
        whereobj = {
            delivery_boy_id:user_id,
            status:{
                [Op.in]:[1]
            }
        }
    }
    else if(type == 'completed'){
        whereobj = {
            delivery_boy_id:user_id,

            status:{
                [Op.in]:[2]
            }
        }
    }
    else if(type == 'cancelled'){
        whereobj = {
            delivery_boy_id:user_id,

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



const cancelled_order = async (req,res) => {
    const {user_id} = req.body;
    var whereobj = {
        delivery_boy_id:user_id,
        status:{
            [Op.in]:[3,4]
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

const completed_order = async (req,res) => {
    const {user_id} = req.body;
    var whereobj = {
        delivery_boy_id:user_id,

        status:{
            [Op.in]:[2]
        }
    };
   
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

const complete_order = async (req,res) => {
    const {user_id,order_id} = req.body;
    const order = await Order.scope(['ongoing']).findOne({
        where:{
            id:order_id
        }
    })
    if(order){
        const dateTime = currentTimeStamp()
        const updated = await Order.update({
            status:2,
            updated_at:dateTime,
            completed_at:dateTime
        },{
            where:{
                id:order.id
            }
        })

        const chechdeliveryrecord = await Delivery.findOne({
            where:{
                status:0,
                order_id:order.id
            }
        })
        if(chechdeliveryrecord){
            const upd = {
                status:1,
                delivery_boy_id:user_id,
                reach_time:dateTime,
                updated_at:dateTime
            }
            await Delivery.update(upd,{
                where:{
                   id: chechdeliveryrecord.id
                }
            })
        }else{
            const str = {
                order_id:order.id,
                user_id:order.user_id,
                delivery_boy_id:user_id,
                status:1,
                reach_time:dateTime,
                updated_at:dateTime
            }
            await Delivery.update(str)
        }
        if(updated){
            res.json(successRes('success'))
        }else{
            res.json(failedRes('failed'))
        }
    }else{
        res.json(successRes('delivered'))
    }
}



const complete_order_cod = async (req,res) => {
    const {user_id,order_id,cash} = req.body;
    const order = await Order.scope(['ongoing']).findOne({
        where:{
            id:order_id
        }
    })
    if(order){
        if(order.payable_amount == parseFloat(cash)){
            const dateTime = currentTimeStamp()
            const updated = await Order.update({
                status:2,
                updated_at:dateTime,
                completed_at:dateTime
            },{
                where:{
                    id:order.id
                }
            })
    
            const chechdeliveryrecord = await Delivery.findOne({
                where:{
                    status:0,
                    order_id:order.id
                }
            })
            if(chechdeliveryrecord){
                const upd = {
                    status:1,
                    delivery_boy_id:user_id,
                    reach_time:dateTime,
                    updated_at:dateTime
                }
                await Delivery.update(upd,{
                    where:{
                       id: chechdeliveryrecord.id
                    }
                })
            }else{
                const str = {
                    order_id:order.id,
                    user_id:order.user_id,
                    delivery_boy_id:user_id,
                    status:1,
                    reach_time:dateTime,
                    updated_at:dateTime
                }
                await Delivery.update(str)
            }
            if(updated){
                res.json(successRes('success'))
            }else{
                res.json(failedRes('failed'))
            }
        }else{
            res.json(failedRes('insufficient Amount!'))
        }
    }else{
        res.json(successRes('delivered'))
    }
}



const complete_order_online = async (req,res) => {
    const {user_id,order_id} = req.body;
    const order = await Order.scope(['ongoing']).findOne({
        where:{
            id:order_id
        }
    })
    if(order){
        const dateTime = currentTimeStamp()
        const updated = await Order.update({
            status:2,
            updated_at:dateTime,
            completed_at:dateTime
        },{
            where:{
                id:order.id
            }
        })
        res.json(successRes('delivered'))
    }else{
        res.json(successRes('delivered'))
    }
}

const toggle_online_status = async (req,res) => {

    const {user_id} = req.body;
    const user = await User.findOne({
        where:{
            id:user_id
        }
    })
    if(!user){
        res.json(failedRes('user not found'))
    }else{
        const online = user.is_online;
        var online_offline = 0;
        if(online==1){
            online_offline=0
        }else{
            online_offline=1
        }

        console.log('sdcundinxvfd');
        console.log(online_offline);
        const up = await User.update({
            is_online:online_offline
        },{
            where:{
                id:user_id
            }
        })

        res.json(successRes('updated',online_offline))
    }
}


const start_duty = async (req,res) => {

    const {user_id} = req.body;
    const user = await User.findOne({
        where:{
            id:user_id
        }
    })
    if(!user){
        res.json(failedRes('user not found'))
    }else{
        const online = 1;
        const up = await User.update({
            is_online:online
        },{
            where:{
                id:user_id
            }
        })

        res.json(successRes('updated',online))
    }
}


const off_duty = async (req,res) => {

    const {user_id} = req.body;
    const user = await User.findOne({
        where:{
            id:user_id
        }
    })
    if(!user){
        res.json(failedRes('user not found'))
    }else{
        const online = 0;
        const up = await User.update({
            is_online:online
        },{
            where:{
                id:user_id
            }
        })

        res.json(successRes('updated',online))
    }
}

const get_earning_report =async (req,res) => {
    const {user_id} = req.body;
    const count_orders = await Order.count({
        where:{
            delivery_boy_id:user_id,
            status:2
        },
    })
    const cancelled_orders = await Order.count({
        where:{
            delivery_boy_id:user_id,
            status:3
        },
    })

    res.json({
        status:true,
        completed_orders:count_orders,
        cancelled_orders:cancelled_orders

    })


}


const get_floating_cash =async (req,res) => {

    const {user_id} = req.body;
    const get_cash = await Order.findOne({
        where:{
            delivery_boy_id:user_id
        },
        attributes: [[sequelize.fn('sum', sequelize.col('payable_amount')), 'floating_cash']],
      });

      res.json(successRes('',get_cash))
}

const get_shift_timing = async (req,res) => {
    const {user_id} = req.body;
    const user = await User.findOne({
        where:{
            id:user_id
        },
        attributes:['shift_timing']
    })

    res.json(user?successRes('',user):failedRes(''))
}

const new_orders = async (req,res) => {
    const {user_id} = req.body;
    var query = `SELECT ord.* FROM orders ord INNER JOIN deliveries dl ON dl.order_id = ord.id WHERE dl.status=2 AND dl.delivery_boy_id = ${user_id} ORDER BY dl.id DESC`;

    const order = await sequelize.query(query, {
        model: Order,
        mapToModel: true // pass true here if you have any mapped fields
    })
    .then(async (ords) => {
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
    res.json(order?successRes('',order):failedRes(''))
}

const send_confirmation_otp = async (req,res) => {
    const {order_id} = req.body;
    const order = await Order.findOne({
        where:{
            id:order_id,
            status:{
                [Op.ne]:[2,3,4]
            }
        }
    })
    if(order){
        const user = await User.findOne({
            where:{
                id:order.user_id
            }
        })
        send_otp_msg91(user.phone,'91',order.confirmation_otp)
        res.json(successRes('otp sent'))
    }else{
        res.json(failedRes('something went wrong'))
    }
}

const new_order_function = async (user_id) => {

    var query = `SELECT ord.* FROM orders ord INNER JOIN deliveries dl ON dl.order_id = ord.id WHERE dl.status=2 AND dl.delivery_boy_id = ${user_id} ORDER BY dl.id DESC`;

    const order = await sequelize.query(query, {
        model: Order,
        mapToModel: true // pass true here if you have any mapped fields
    })
    .then(async (ords) => {
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
    return order;
}

const floating_cash_history = async (req,res) => {
    const {user_id} = req.body;
    const orders = await Order.findAll({
        where:{
            delivery_boy_id:user_id,
            status:2,
            payment_mode:'cod'
        },
        order:[['id','desc']]
    })
    res.json(orders?successRes('',orders):failedRes(''));
}

const accept_order = async (req,res) => {
    const {user_id,order_id} = req.body;
    const user = await User.findOne({
        where:{
            id:user_id
        }
    })
    const warehouse = await User.findOne({
        where:{
            id:user.warehouse_id
        }
    })
    const dateTime = currentTimeStamp();
    const order = await Order.findOne({
        where:{
            id:order_id,
            status:0
        }
    })
    if(order){

        await Delivery.update({
            status:3,
            updated_at:dateTime
        },{
            where:{
                order_id:order_id,
                delivery_boy_id:{
                    [Op.ne]:[user_id]
                }
            }
        })
        const updatedeliveries = await Delivery.update({
            status:0,
            warehouse_id:user_id.warehouse_id,
           
            updated_at:dateTime
        },{
            where:{
                delivery_boy_id:user_id,
                order_id:order_id
            }
        })
    
        const orderupdate = await Order.update({
            updated_at:dateTime,
            delivery_boy_id:user_id,
            warehouse_id:user.warehouse_id,
            warehouse_lat:warehouse?warehouse.latitude:null,
            warehouse_lng:warehouse?warehouse.longitude:null,
            warehouse_address:warehouse?warehouse.location+' '+warehouse.pincode:null,
            warehouse_name:warehouse?warehouse.name:null,
            assign_time:dateTime,
            status:1
        },{
            where:{
                id:order_id,
                // status:0
            }
        })

        const checkinventry = Inventory.findAll({
            where:{
                order_id:order_id
            }
        })
        .then(async (results)=>{
            for (let reslt of results) {
                await Inventory.update({
                    user_id:user.warehouse_id
                },{
                    where:{
                        id:reslt.id
                    }
                })
                
            }
            return await results
        })
        res.json(orderupdate ? successRes('accepted') : failedRes('failed'))
    }else{
        res.json(failedRes('already assigned'))
    }
  
}

const cancel_order = async (req,res) => {
    const {user_id,order_id,cancel_reason} = req.body

    const order = await Order.findOne({
        where:{
            id:order_id,
            status:1
        }
    });
    if(order){

        const updatedata = await Order.update({
            status:3,
            cancel_reason,
            cancel_by:'deliveryboy'
        },{
            where:{
                id:order_id
            }
        })

        if(updatedata){
            res.json(successRes('success'))
        }else{
            res.json(failedRes('something went wrong!'))
        }


    }else{
        res.json(failedRes('something went wrong!'))
    }
}

const pickup_order = async (req,res) => {
    const {order_id} = req.body;
    const dateTime = currentTimeStamp()
    const order = await Order.findOne({
        where:{
            id:order_id,
            status:{
                [Op.ne]:[3,2]
            }
        }
    })
    if(order){
        await Order.update({
            pickup_time:dateTime,
            is_pickup:1
        },{
            where:{id:order_id}
        })
        res.json(successRes('picked'))
    }else{
        res.json(failedRes('something went wrong!'))
    }
}

const add_review_to_customer = async (req,res) => {
    const {customer_id,user_id,order_id,rate,message} = req.body;
    const dateTime = currentTimeStamp();
    const storedata = {
        user_id:customer_id,
        deliveryboy_id:user_id,
        order_id,
        rate,
        message,
        created_at:dateTime,
        updated_at:dateTime

    }

    await ReviewUser.create(storedata)
    .then((rs)=>{
        res.json(successRes('review added'))
    })
    .catch((err)=>res.json(failedRes('failed',err)))
}
module.exports = {
    my_orders,
    toggle_online_status,
    complete_order,
    complete_order_cod,
    get_earning_report,
    get_floating_cash,
    get_shift_timing,
    new_orders,
    floating_cash_history,
    accept_order,
    cancel_order,
    pickup_order,
    new_order_function,
    completed_order,
    cancelled_order,
    send_confirmation_otp,
    add_review_to_customer,
    complete_order_online,
    start_duty,
    off_duty

}