const { googleapikey } = require("../config/app.config");
const { makeRequest } = require("../helpers/axios.helper");
const { successRes, failedRes } = require("../helpers/response.helper");
const axios=require('axios');
const { Location, Society } = require("../models");

const fetch_location_by_lat_lon_fun = async(lat,lon) =>{

    const url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lon}&key=${googleapikey}`;
    console.log(url);

    const response = await axios({
        url:url
    });


    return response.data;

}

const fetch_location_by_lat_lon = async (req,res) => {
    const {latitude,longitude}=req.body
    const data = await fetch_location_by_lat_lon_fun(latitude,longitude);

    res.send({
        status:true,
        formatted_address:data.results[0].formatted_address,
        pincode:data.results[0].address_components[(data.results[0].address_components.length - 1)]['long_name'],
        data:data
    })
}

const check_if_service_location = async (req,res) => {
    const {user_id,latitude,longitude} = req.body;

    const data = await fetch_location_by_lat_lon_fun(latitude,longitude);
    const pincode = data.results[0].address_components[(data.results[0].address_components.length - 1)]['long_name'];
    const state = data.results[0].address_components[(data.results[0].address_components.length - 3)]['long_name'];
    const city = data.results[0].address_components[(data.results[0].address_components.length - 4)]['long_name'];

    const check1 = await Location.scope(['active']).findOne({
        where:{
            pincode:pincode
        }
    });
    const check2 = await Society.scope(['active']).findOne({
        where:{
            pincode:pincode
        }
    });
    if(check1 || check2){
        var check;
        if(check1){
            check=check1
        }else{
            check=check2
        }

        res.json({
            status:true,
            message:"valid",
            data:check,
            formatted_address:data.results[0].formatted_address,
            pincode:pincode,
            state:state,
            city:city
        })
    }else{
        res.json({
            status:false,
            message:"not valid",
            data:check,
            formatted_address:data.results[0].formatted_address,
            pincode:pincode,
            state:state,
            city:city
        })
    }
}



const fetch_location_by_id = async (req,res) => {
    const {user_id,id} = req.body;

    const check = await Location.scope(['active']).findOne({
        where:{
            id:id
        }
    });
  
    if(check){
       
        res.json({
            status:true,
            message:"valid",
            data:check,
        })
    }else{
        res.json({
            status:false,
            message:"not valid",
            data:check,
        })
    }
}



const fetch_society_by_id = async (req,res) => {
    const {user_id,id} = req.body;

    const check = await Society.scope(['active']).findOne({
        where:{
            id:id
        }
    });
  
    if(check){
       
        res.json({
            status:true,
            message:"valid",
            data:check,
        })
    }else{
        res.json({
            status:false,
            message:"not valid",
            data:check,
        })
    }
}




const fetch_locations = async(req,res) => {
    const {user_id,latitude,longitude} = req.body;
    var pincode;
    if(latitude && longitude){
        const data = await fetch_location_by_lat_lon_fun(latitude,longitude);
        pincode=data.results[0].address_components[(data.results[0].address_components.length - 1)]['long_name'];
    }
    const locations = await Location.scope(['active','orderAsc']).findAll().then(async(results)=>{
        for (let location of results) {
            location.dataValues.is_selected = pincode==location.pincode ? 1 : 0
        }
        return await results
    });
    res.json(successRes('fetched',locations))
}

const fetch_areas = async (req,res) => {
    const {user_id,location_id} = req.body;
    const socities = await Society.scope(['active']).findAll({
        where:{
            location_id:location_id
        }
    });
    if(socities){
        res.json(successRes('fetched',socities))
    }else{

        res.json(failedRes('faild'))
    }
}
  

module.exports={
    fetch_location_by_lat_lon,
    fetch_locations,
    check_if_service_location,
    fetch_location_by_id,
    fetch_areas,
    fetch_society_by_id
}
