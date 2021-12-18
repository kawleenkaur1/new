const { DeliveryConfig } = require("../models");
const { successRes, failedRes } = require("../helpers/response.helper");


const deliveryconfig = async (req,res) => {
    const {user_id,type} = req.body;
    const data = await DeliveryConfig.findAll()
    .then(async (deldata)=>{
        for (let dl of deldata) {
            if(dl.type == 'schedule'){
                dl.dataValues.time_slots = JSON.parse(dl.time_slots)
            }
        }
        return await deldata
    })

    res.json(data?successRes('',data) : failedRes('failed'))
}

module.exports = {
    deliveryconfig
}