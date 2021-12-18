const { customer_user } = require('../config/app.config');
const db=require('../models/index');
const {User,sequelize,Review}=db;
const moment = require("moment");
const { Speciality, Skill, Transaction, Astrologer,Booking, Favourite, Follower } = require('../models/index');
const { Op } = require('sequelize')
let referralCodeGenerator = require('referral-code-generator')



const isPhoneExists =(phone,user_type='customer')=>User.scope([user_type]).count({ where: { phone: phone} });

const isEmailExists =(email,user_type='customer')=>User.scope([user_type]).count({ where: { email: email } });

const fetchUserByPhone =(phone,user_type='customer')=>User.scope([user_type]).findOne({ where: { phone: phone} });

const fetchUserByEmail =(email,user_type='customer')=>User.scope([user_type]).findOne({ where: { email: email} });


const fetchUserByID =(id)=>User.findOne({ where: { id: id} });

const currentTimeStamp = (format='YYYY-MM-DD HH:mm:ss') =>{

    return moment().format(format)

};

const generateReferralCode = async (name='') => referralCodeGenerator.alphaNumeric('uppercase', 2, 2)
// referralCodeGenerator.custom('uppercase', 2, 3, name);


const generateOTP = () => {
    // Declare a digits variable
    // which stores all digits
    var digits = "0123456789";
    let OTP = "";
    for (let i = 0; i < 4; i++) {
      OTP += digits[Math.floor(Math.random() * 10)];
    }
    return OTP;
};


const get_location_id = async (req) => {
    var location_id = parseInt(req.headers['location_id']); 
    return location_id ? location_id : 0
}
module.exports={
    isPhoneExists,
    isEmailExists,
    fetchUserByPhone,
    fetchUserByID,
    currentTimeStamp,
    fetchUserByEmail,
    generateReferralCode,
    generateOTP,
    get_location_id
}
