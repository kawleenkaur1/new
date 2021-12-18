const { Op } = require("sequelize");
const { successRes, failedRes } = require("../helpers/response.helper");
const { currentTimeStamp } = require("../helpers/user.helper");
const { Address, Location, Society } = require("../models");


const add_address =async (req,res) => {
    var {user_id,state,city,flat,landmark,location,lat,lng,address_type,location_id,area_id,society,pincode} = req.body;
    const location_fetch = await Location.findOne({
        where:{
            id:location_id
        }
    })
    if(location_fetch){
        location = location_fetch.name;
        lat = location_fetch.lat
        lng = location_fetch.lon
        pincode=location_fetch.pincode;
    }

    const area = await Society.findOne({
        where:{
            id:area_id
        }
    })
    if(area){
        society=area.name
        lat = area.lat
        lng = area.lon
    }
    const checkIfAlready = await Address.findOne({
        where:{
            user_id,
            address_type
        }
    })
    const dateTime = currentTimeStamp();
    const store_data = {
        address_type,
        location,lat,lng,location_id,area_id,
        main_location:location,
        main_society:society,
        created_at:dateTime,
        updated_at:dateTime,
        pincode:pincode,
        flat:flat,
        landmark:landmark,
        user_id,
    }
    if(checkIfAlready){
        const updatedata = await Address.update({...store_data,is_default:1},{
            where:{
                id:checkIfAlready.id
            }
        });
        await Address.update({
            is_default:0
        },{
            where:{
                id:{
                    [Op.ne]:checkIfAlready.id
                }
            }
        })
        res.json(successRes('created!'))

    }else{
        const storedata = await Address.create({...store_data,is_default:1});
        if(storedata){
            await Address.update({
                is_default:0
            },{
                where:{
                    id:{
                        [Op.ne]:storedata.id
                    }
                }
            })
            res.json(successRes('created!',storedata))
        }else{
            res.json(failedRes('something went wrong! please check & try again',storedata))
        }
    }
}

const fetch_addresses = async (req,res) => {
    const {user_id} = req.body;
    var {location_id} = req.body;
    if(!location_id){
        location_id=0
    }
    const addresses = await Address.scope(['active']).findAll({
        where:{
            location_id,
            user_id:user_id
        }
    })
    res.json(successRes('fetched',addresses))
}


const delete_address =async (req,res) => {
    const {user_id,id} = req.body;
    const deletedata = await Address.destroy({
        where:{
            id:id,
            user_id:user_id
        }
    });
    res.json(successRes('deleted!',deletedata))
}


module.exports = {
    add_address,
    delete_address,
    fetch_addresses
}