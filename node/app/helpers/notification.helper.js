var FCM = require('fcm-node');
const { Notification, User } = require('../models');
const { currentTimeStamp } = require('./user.helper');
// var serverKey = 'AAAASiCBx_0:APA91bEFg-o01tvj86QiCtxS7L9OwP_FJ0n7vaB-HHGucY2QC3Ixm9ZUWfhxVeXsZAEo0GgLbxuf4jn0Ms-94YSneZQxEqsUCXURzUjdqg_GAV0NXDoxKcuqTPLRmgKWEU2BPgJ9sb2C'; //put your server key here
var serverKey = 'AAAAOJks228:APA91bFKBvTdMpyKiAFWSc7z4vIpAZjV0oBbWP-BT6-kPw84Bt0H81ep_rHu26GtLnbTFjx8tr6B-Q9dc9dFSPPKUBgGLRmnKbScXARdd63_RE1pUdx835FovwvgWV_aWUar_IXrFSrI'; //put your server key here


var fcm = new FCM(serverKey);
var collapseKey = '';

// zCUnwWUcG4MwTmkF1fI4HVm3C4Ym3Hdm1OxEx7cm
const send_fcm_push =async (token,title,message,data={},priority='normal') => {
    const dateTime = currentTimeStamp();
    const storedata = {
        user_id:data.user_id ? data.user_id : 0,
        user_type:1,
        title:title,
        notification:message,
        added_on:dateTime
    }

    await Notification.create(storedata)

    var message = { //this may vary according to the message type (single recipient, multicast, topic, et cetera)
        to: token, 
        // registration_ids
        collapse_key: collapseKey,
        // "data": {}, "from": "846252768029", "messageId": "0:1615101213059689%35fb841e35fb841e", 
        notification: {
            title: title, 
            body: message,
            icon: 'ic_notification',
            color: '#18d821',
            sound: 'default',
            priority: priority
            // priority:'high'
        },
        
        data: data
    };

    fcm.send(message, function(err, response){
        if (err) {
            console.log("Something has gone wrong!");
        } else {
            console.log("Successfully sent with response: ", response);
        }
    });
   
}


const send_booking_request_notification = async (type,user_id,astrologer_id) => {
    console.log('send_booking_request_notification');
    var booking_type = '';
    const b_type=type;
    if(b_type==1){
        booking_type='Video';
    }else if(b_type == 2){
        booking_type='Audio'
    }else if(b_type == 3){
        booking_type='Chat'
    }else if(b_type == 4){
        booking_type='Report'
    }else if(b_type == 5){
        booking_type='Broadcast'
    }

    const astrologer = await Astrologer.findOne({
        where:{
            id:astrologer_id
        }
    })

    const user = await User.findOne({
        where:{
            id:user_id
        }
    })

    const title = `${booking_type} Request Sent!`
    const message = `Your request has been sent to the ${astrologer.name.toUpperCase()}, You will be notified when astrologer accepts your request, Your request will rejected automatically after 2 minutes if astrologer does not respond to the request.`;

    send_fcm_push(user.device_token,title,message,{user_id:user_id})
}





const send_booking_complete_notification = async (type,user_id,astrologer_id,order_id,txn_amount) => {
    console.log('send_booking_complete_notification');
    var booking_type = '';
    const b_type=type;
    if(b_type==1){
        booking_type='Video Call';
    }else if(b_type == 2){
        booking_type='Audio Call'
    }else if(b_type == 3){
        booking_type='Chat'
    }else if(b_type == 4){
        booking_type='Report'
    }else if(b_type == 5){
        booking_type='Broadcast'
    }

    const astrologer = await Astrologer.findOne({
        where:{
            id:astrologer_id
        }
    })

    const user = await User.findOne({
        where:{
            id:user_id
        }
    })

    
    const title2 = `Wallet debit against Order ID #${order_id}`
    const message2 = `Your wallet amount is debited with Rs.${txn_amount}.`;

    send_fcm_push(user.device_token,title2,message2,{user_id:user_id})

    const title = `${booking_type} Complete Order ID #${order_id}`
    const message = `Your ${booking_type} with ${astrologer.name.toUpperCase()} has been completed successfully.`;

    send_fcm_push(user.device_token,title,message,{user_id:user_id})



}



const send_fcm_push_deliveryboy =async (token,title,message,data={},priority='normal') => {
    const SERVER_KEY = `AAAAOJks228:APA91bFKBvTdMpyKiAFWSc7z4vIpAZjV0oBbWP-BT6-kPw84Bt0H81ep_rHu26GtLnbTFjx8tr6B-Q9dc9dFSPPKUBgGLRmnKbScXARdd63_RE1pUdx835FovwvgWV_aWUar_IXrFSrI`
    const dateTime = currentTimeStamp();
    const storedata = {
        user_id:data.user_id ? data.user_id : 0,
        user_type:3,
        title:title,
        body:message,
        created_at:dateTime,
        updated_at:dateTime

    }

    await Notification.create(storedata)

    var fcm2 = new FCM(SERVER_KEY);

    var msg_obj =  {
        title: title, 
        body: message,
        icon: 'ic_notification',
        color: '#18d821',
        sound: 'default',
        priority: priority
    };

    var message = { //this may vary according to the message type (single recipient, multicast, topic, et cetera)
        to: token, 
        // registration_ids
        collapse_key: 'com.katlegoDeliveryBoy',
        // "data": {}, "from": "846252768029", "messageId": "0:1615101213059689%35fb841e35fb841e", 
        notification:msg_obj,

        data: msg_obj
    };

    fcm2.send(message, function(err, response){
        if (err) {
            console.log("Something has gone wrong!");
        } else {
            console.log("Successfully sent deliveryboy app with response: ", response);
        }
    });

}

const send_new_order_notification = async (user_id,deliveryboy_id,order_id,pincode) => {
    // const user = await User.findOne({
    //     where:{
    //         id:user_id
    //     }
    // })

    const deliveryboy = await User.findOne({
        where:{
            id:deliveryboy_id
        }
    })

    const title = `New Order (order ID - #${order_id})`;
    const message = `You have one new order from pincode : ${pincode}`;
    send_fcm_push_deliveryboy(deliveryboy.device_token,title,message,{user_id:deliveryboy.id},'high')
}



module.exports = {
    send_fcm_push,
    send_booking_request_notification,
    send_booking_complete_notification,
    send_fcm_push_deliveryboy,
    send_new_order_notification
}
