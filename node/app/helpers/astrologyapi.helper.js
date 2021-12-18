// const axios = require('axios');

const { User } = require("../models");
const { makeRequest } = require("./axios.helper");

// $userId = "609418";
// $apiKey = "3f44385e5c9a4a5febc2e9ea7872bacf";

// const userId = 615647;
// const apiKey = '81da3f696782d5358afaaa01d9d647a0';


const userId = 609418;
const apiKey = '3f44385e5c9a4a5febc2e9ea7872bacf';
const BASE_URL = 'https://json.astrologyapi.com/v1/';
const headers = {
    "authorization": "Basic " + Buffer.from(userId+":"+apiKey).toString('base64'),
    "Content-Type":'application/json'
}

const hitAstrologyPostApi = async (endpoint,data) => {
    const url = BASE_URL+endpoint;
    return await makeRequest('post',url,headers,data)
}

const reqAstroObj =async (user_id) => {

    var res;
    const user =await User.findOne({
        where:{
            id:user_id
        }
    }).then((rs)=>rs);
    if(!user){
        res = {
            status:false,
            message:'user not found',
            data:null
        }
        return res
    }
    if(!user.dob){
        res = {
            status:false,
            message:'dob not found',
            data:null
        }
        return res
    }
    if(!user.birth_time){
        res = {
            status:false,
            message:'birth_time not found',
            data:null
        }
        return res
    }

    const dob_arr =await user.dob.split('-')
    if(dob_arr.length < 1){
        res = {
            status:false,
            message:'dob not found',
            data:null
        }
        return res
    }
    const day = dob_arr[2];
    const month = dob_arr[1];
    const year = dob_arr[0];

    const time_arr =await user.birth_time.split(':')
    if(time_arr.length < 1){
        res = {
            status:false,
            message:'birth time not found',
            data:null
        }
        return res
    }
    const min = time_arr[1];
    const hour = time_arr[0];
    const obj ={
        day:day,
        month:month,
        year:year,
        hour:hour,
        min:min,
        lat:user.latitude,
        lon:user.longitude,
        tzone:5.5
    };
    console.log(obj);
    res = {
        status:true,
        message:'user not found',
        data:obj
    }
    return res
}


/**Daily Sun sign Predection Prediction */
const sun_sign_prediction_daily = async (horoscope='',data) => {
    const url = BASE_URL+'sun_sign_prediction/daily/'+horoscope;
    // console.log(headers);
    return await makeRequest('post',url,headers,data)
}


const match_making_detailed_report = async (data) => {
    const url = BASE_URL+'match_making_detailed_report';
    // console.log(headers);
    return await makeRequest('post',url,headers,data)
}


const auto_reject_request = async (id) => {
    // CallRe
    const get_request = await CallRequest.findOne({
        where:{
            id:request_id
        },
        status:0
    })
    if(get_request){
        CallRequest.update({
            reject_time:dateTime,
            updated_at:dateTime,
            status:2
        },{
            where:{
                id:get_request.id
            }
        })
        res.io.sockets.in(get_request.host_id).emit('accept_reject_request',successRes('rejected'))
    }
  
}



// const 
module.exports = {
    reqAstroObj,
    hitAstrologyPostApi,
    sun_sign_prediction_daily,
    match_making_detailed_report
}