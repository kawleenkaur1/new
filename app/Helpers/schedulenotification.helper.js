

const schedule = require('node-schedule');
const { send_fcm_push } = require('./notification.helper');



const send_schedule_notification = async (date,token,title,message,data={}) => {
    console.log('schedule notification'+date);
    schedule.scheduleJob(date, function(){
        console.log('The world is going to end today. Job is executing :p');
        send_fcm_push(token,title,message,data,'high')
    });
}



module.exports = {
    send_schedule_notification
}
