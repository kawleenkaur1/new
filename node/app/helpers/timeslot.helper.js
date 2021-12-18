const moment = require("moment");

const timeSlots = (cb) => {

    var duration = 60;
    var freeSlots = [];

    var workingStartTime = '2018-05-12T08:00:00.000Z'; 
    var workingEndTime = '2018-05-12T22:00:00.000Z';

    var busySlots = [];
    busySlots.push({ start_time: "2018-05-12T14:30:00.000Z",end_time: "2018-05-12T17:45:00.000Z" }) ;
    busySlots.push({ start_time: "2018-05-12T20:30:00.000Z",end_time: "2018-05-12T21:45:00.000Z" }) ;

    var beginAt = workingStartTime;
    var overAt = workingEndTime;
    var count = 0;
    var last = busySlots.length;

    async.forEach(busySlots, function (item, callback){

        /*** Library funcition to gind difference between two dates (Minutes) ***/
        var diff = libFunc.getRange(beginAt, item.start_time,TIME_UNITS.MINUTES);
        if(diff > duration){
          let free = {"start_time":beginAt , "end_time":item.start_time};
          freeSlots.push(free);
          beginAt = item.end_time;
          count += 1;

          /** Process for end slot **/
          if(last == count){
            var diff = libFunc.getRange(item.end_time, overAt, TIME_UNITS.MINUTES);
            if(diff > duration){
              let free = {"start_time":item.end_time , "end_time":overAt};
              freeSlots.push(free);
              callback();
            }else{
              callback();
            }
          }else{
            callback();
          }
          /** Process for end slot **/

        }else{
          beginAt = item.end_time;
          count += 1;

          /** Process for end slot **/
          if(last == count){
            var diff = libFunc.getRange(item.end_time, overAt, TIME_UNITS.MINUTES);
            if(diff > duration){
              let free = {"start_time":item.end_time , "end_time":overAt};
              freeSlots.push(free);
              callback();
            }else{
              callback();
            }
          }else{
            callback();
          }
          /** Process for end slot **/
        }

    }, function(err) {
      // console.log(freeSlots);
        cb(null);
    });
}


const getTimeStops = () => {
    const start = '00:00';
    const end = '23:59';
    var startTime = moment(start, 'HH:mm a');
    var endTime = moment(end, 'HH:mm a');
    console.log(startTime);

    if( endTime.isBefore(startTime) ){
      endTime.add(1, 'day');
    }
    var timeStops = [];
    var morning = [];
    var afternoon=[];
    var evening = [];
    var night = [];
    while(startTime <= endTime){
        var currentHour = startTime.format("HH");
        // console.log('time',currentHour);
        if (currentHour >= 3 && currentHour < 12){
            morning.push({slot:new moment(startTime).format('HH:mm a')});
        } else if (currentHour >= 12 && currentHour < 15){
            afternoon.push({slot:new moment(startTime).format('HH:mm a')});
        }   else if (currentHour >= 15 && currentHour < 20){
            evening.push({slot:new moment(startTime).format('HH:mm a')});
        } else if (currentHour >= 20 && currentHour < 3){
            night.push({slot:new moment(startTime).format('HH:mm a')});
        } else {
            // night.push({slot:new moment(startTime).format('HH:mm a')});
        }

        // timeStops.push({slot:new moment(startTime).format('HH:mm a')});
        startTime.add(15, 'minutes');
    }
    return {
        morning,
        afternoon,
        evening,
        night
    };

    
    // var currentHour = moment().format("HH");

    // if (currentHour >= 3 && currentHour < 12){
    //     return "Good Morning";
    // } else if (currentHour >= 12 && currentHour < 15){
    //     return "Good Afternoon";
    // }   else if (currentHour >= 15 && currentHour < 20){
    //     return "Good Evening";
    // } else if (currentHour >= 20 && currentHour < 3){
    //     return "Good Night";
    // } else {
    //     return "Hello"
    // }
}


module.exports = {
    timeSlots,
    getTimeStops
}