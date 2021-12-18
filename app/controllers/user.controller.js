const { Op } = require('sequelize');
const { failedRes, successRes } = require('../helpers/response.helper');
const { fetchUserByEmail, fetchUserByPhone, currentTimeStamp, generateReferralCode, generateOTP } = require('../helpers/user.helper');
var bcrypt = require("bcryptjs");
var jwt = require("jsonwebtoken");
const { jwtExpiration, jwtsecret, refreshJwtsecret, refreshJwtExpiration } = require('../config/app.config');
const { UserLogin, User, OtpUser } = require('../models');
const customeruser=2;
const moment = require('moment');
const { send_otp_msg91, forgot_otp_msg91 } = require('../helpers/msg91.helper');
const { katlego_welcome_mail } = require('../helpers/mailer.helper');

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}


const accessToken = (payload) => {
    var token = jwt.sign(payload, jwtsecret, {
      expiresIn: jwtExpiration,
    });
    return token;
};


const refreshTokencreate = (payload) => {
    var token = jwt.sign(payload, refreshJwtsecret, {
      expiresIn: refreshJwtExpiration,
    });
    return token;
};

const resend_otp = async (req,res) => {
    const {username} = req.body;
    var email=false;
    var phone=false;
    if(validateEmail(username)){
        email=true;
    }else{
        phone=true;;
    }

    const checkotpalreadysent = await checkIfOtpAlreadySent(username);
    if(checkotpalreadysent){
        send_otp_msg91(checkotpalreadysent.phone,'91',checkotpalreadysent.otp)
        res.json(successRes("OTP sent succesfully", checkotpalreadysent));
    }else{
        res.json(failedRes("something went wrong", checkotpalreadysent))
    }

}

const checkIfOtpAlreadySent = async (phone,role='customer') =>await OtpUser.scope([role]).findOne({
    where:{
    [Op.or]:[
        {
            phone:phone
        },
        {
            email:phone
        }

    ]},
    order:[['id','desc']]
})


const otp_destroy = async (phone,role='customer') => {
    return OtpUser.scope([role]).destroy({
        where: {
          [Op.or]:[
                {
                    phone:phone
                },
                {
                    email:phone
                }

            ]
        },
    })
}

const user_signup_otp = async (req,res) => {

    const {name,email,phone,referral_code,password} = req.body;
    var checkifExistsUser;
    var checkIfphoneExists;
    if(email){
        checkifExistsUser = await fetchUserByEmail(email,'customer')
        if(checkifExistsUser){
            return res.json(failedRes('Email address already exists',checkifExistsUser))
        }
    }
    if(phone){
        checkIfphoneExists = await fetchUserByPhone(phone,'customer');
        if(checkIfphoneExists){
            return res.json(failedRes('Phone number already exists',checkIfphoneExists))
        }
    }
    if(!email && !phone){
        res.json(failedRes('please add either email or phone'))
    }
    if(phone || email){
        if(referral_code){
            const checkreferralUser = await User.scope(['active']).findOne({where:{
                referral_code:referral_code
            }})
            if(!checkreferralUser){
                return res.json(failedRes('Referral code not found, Please try with other referral code'))
            }
        }
        const otp = generateOTP();
        const dateTime = currentTimeStamp();
        console.log('datetime',dateTime);
        const checksentotp =await checkIfOtpAlreadySent(phone);
        const storedata = {
            name,
            email,
            phone: phone,
            phone_verified: phone?1:0,
            email_verified:email?1:0,
            otp: otp,
            otp_email:otp,
            user_type: customeruser,
            referral_from:referral_code,
            created_at: dateTime,
            updated_at: dateTime,
            password:password
          };
          if(checksentotp){
            await OtpUser.update(storedata,{
                where:{
                    id:checksentotp.id
                }
            })
            .then((r) => {
                send_otp_msg91(phone,'91',checksentotp.otp)
                res.json(successRes("OTP sent succesfully", checksentotp))

            })
            .catch((err) => res.json(failedRes("Something went wrong", err)));
          }else{
            await OtpUser.create(storedata)
                .then((r) => {
                    send_otp_msg91(phone,'91',otp)

                    res.json(successRes("OTP sent succesfully", r))
            })
                .catch((err) => res.json(failedRes("Something went wrong", err)));
          }

    }

}

const user_signup_verify = async (req,res) => {
    const {id,otp,device_id,device_type,device_token,model_name} = req.body;
    const otpuserphone = await OtpUser.findOne({
        where:{id:id,otp:otp},
        order:[['id','desc']]
    });
    const otpuseremail = await OtpUser.findOne({
        where:{id:id,otp_email:otp},
        order:[['id','desc']]
    });
    if(otpuserphone || otpuseremail){
        const otpuser = otpuserphone ? otpuserphone : otpuseremail;
        const phone = otpuser.phone;
        const email = otpuser.email;
        if(otpuser.phone_verified || otpuser.email_verified){
            if(email && email!==''){
                var checkifExistsUser = await fetchUserByEmail(email,'customer')
                if(checkifExistsUser){
                    return res.json(failedRes('Email address already exists',checkifExistsUser))
                }
            }
            if(phone && phone!==''){
                const checkIfphoneExists = await fetchUserByPhone(phone,'customer');
                if(checkIfphoneExists){
                    return res.json(failedRes('Phone number already exists',checkIfphoneExists))
                }
            }
            var checkreferralUser;
            const referral_code = otpuser.referral_from
            if(referral_code && referral_code !== ''){
                checkreferralUser = await User.scope(['active']).findOne({where:{
                    referral_code:otpuser.referral_from
                }})
                if(!checkreferralUser){
                    return res.json(failedRes('Referral code not found, Please try with other referral code'))
                }
            }

            var hashedPassword = bcrypt.hashSync(otpuser.password, 8);
            const dateTime = currentTimeStamp();
            const rfcode= await generateReferralCode();

            const storeData = {
                name:otpuser.name,
                user_type:customeruser,
                email:otpuser.email,
                phone:phone,
                password:hashedPassword,
                referral_code:rfcode,
                referral_from:otpuser.referral_from,
                auth_type:'normal',
                status:1,
                email_verified: otpuseremail ? 1 : 0,
                phone_verified: otpuserphone ? 1 : 0,
                email_verified_at: otpuseremail ? dateTime : null,
                phone_verified_at: otpuserphone ? dateTime : null,
                created_at:dateTime,
                updated_at:dateTime
            }

            var createuser = await User.create(storeData);
            if(createuser){
                const storeData2 = {
                    user_id:createuser.id,
                    device_id: device_id,
                    device_type: device_type,
                    device_token: device_token,
                    model_name,
                    created_at: dateTime,
                    updated_at: dateTime,
                };
                const token_expiry = moment().add(jwtExpiration,'seconds').format('YYYY-MM-DD HH:mm:ss')
                await UserLogin.create(storeData2);
                await otp_destroy(phone)
                var token = accessToken({ id: createuser.id });
                var refreshtoken = refreshTokencreate({ id: createuser.id });
                katlego_welcome_mail(otpuser.email,createuser.id)

                // if(checkreferralUser){
                //     cashback_offer_for_referral(checkreferralUser.id,createuser.id,device_id)
                // }
                res.json({
                    status:true,
                    message:'success',
                    data:createuser,
                    token:token,
                    refreshtoken:refreshtoken,
                    token_expiry
                });
            }
        }else{
            res.json(failedRes('failed'));
        }
    }else{
        res.json(failedRes('Invalid OTP'));

    }
}


const forgot_otp = async (req,res) => {
    const {username} = req.body;

    var checkifExistsUser=0;
    var email=false;
    var phone=false;
    var type='';
    if(validateEmail(username)){
        email=true;
        type='email';
        checkifExistsUser = await fetchUserByEmail(username,'customer')
    }else{
        phone=true;;
        type='phone';
        checkifExistsUser = await fetchUserByPhone(username,'customer');
    }

    if(checkifExistsUser){
        const otp = generateOTP();
        const dateTime = currentTimeStamp();

        const checksentotp =await checkIfOtpAlreadySent(phone);
        const storedata = {
            name:'',
            email:email?username:'',
            phone: phone?username:'',
            phone_verified: phone?1:0,
            email_verified:email?1:0,
            otp: otp,
            user_type: customeruser,
            created_at: dateTime,
            updated_at: dateTime,
        };


        if(checksentotp){
            await OtpUser.update(storedata,{
                where:{
                    id:checksentotp.id
                }
            })
            .then((r) => {
                // if(phone){
                    forgot_otp_msg91(username,'91',checksentotp.otp)

                // }
                res.json(successRes("OTP sent succesfully on "+type, checksentotp))})
                .catch((err) => res.json(failedRes("Something went wrong", err)));
        }else{
            await OtpUser.create(storedata)
            .then((r) => {
                // if(phone){
                    forgot_otp_msg91(username,'91',otp)

                // }
                res.json(successRes("OTP sent succesfully on "+type, r))
        })
            .catch((err) => res.json(failedRes("Something went wrong", err)));
        }
    }
}

const forgot_verify = async (req,res) => {
    const {id,otp,password,confirm_password} = req.body;
    var checkifExistsUser=0;
    var email=false;
    var phone=false;
    const otpuser =await OtpUser.findOne({
        where:{id:id,otp:otp},
        order:[['id','desc']]
    })

    if(otpuser){
        var username;
        if(otpuser.email_verified){
            username=otpuser.email
        }else{
            username=otpuser.phone;
        }
        if(validateEmail(username)){
            email=true;
            checkifExistsUser = await fetchUserByEmail(username,'customer')
        }else{
            phone=true;;
            checkifExistsUser = await fetchUserByPhone(username,'customer');
        }

        var hashedPassword = bcrypt.hashSync(password, 8);
        const dateTime = currentTimeStamp();
        if(checkifExistsUser){
            var email_verified_at = null;
            if(otpuser.email_verified){
                email_verified_at=dateTime;
            }
            const updateData = {
                password:hashedPassword,
                email_verified_at: email_verified_at,
                updated_at:dateTime
            }
            await User.update(updateData,{
                where:{id:checkifExistsUser.id}
            })
            await otp_destroy(username)


            res.json(successRes('password changed successfully'))
        }else{
            res.json(failedRes('user not found'))
        }


    }else{
        res.json(failedRes('Invalid OTP!'))
    }
}



const forgot_change_password = async (req,res) => {
    const {id,password} = req.body;
    var checkifExistsUser=0;
    var email=false;
    var phone=false;
    const otpuser =await OtpUser.findOne({
        where:{id:id,is_verified:1},
        order:[['id','desc']]
    })

    if(otpuser){
        var username;
        if(otpuser.email_verified){
            username=otpuser.email
        }else{
            username=otpuser.phone;
        }
        if(validateEmail(username)){
            email=true;
            checkifExistsUser = await fetchUserByEmail(username,'customer')
        }else{
            phone=true;;
            checkifExistsUser = await fetchUserByPhone(username,'customer');
        }

        var hashedPassword = bcrypt.hashSync(password, 8);
        const dateTime = currentTimeStamp();
        if(checkifExistsUser){
            var email_verified_at = null;
            if(otpuser.email_verified){
                email_verified_at=dateTime;
            }
            const updateData = {
                password:hashedPassword,
                email_verified_at: email_verified_at,
                updated_at:dateTime
            }
            await User.update(updateData,{
                where:{id:checkifExistsUser.id}
            })
            await otp_destroy(username)


            res.json(successRes('password changed successfully'))
        }else{
            res.json(failedRes('user not found'))
        }


    }else{
        res.json(failedRes('Something went wrong! Please try after sometime'))
    }
}


const forgot_verify_otp = async (req,res) => {
    const {id,otp} = req.body;
    var checkifExistsUser=0;
    var email=false;
    var phone=false;
    const otpuser =await OtpUser.findOne({
        where:{id:id,otp:otp},
        order:[['id','desc']]
    })

    if(otpuser){
        var username;
        if(otpuser.email_verified){
            username=otpuser.email
        }else{
            username=otpuser.phone;
        }
        if(validateEmail(username)){
            email=true;
            checkifExistsUser = await fetchUserByEmail(username,'customer')
        }else{
            phone=true;;
            checkifExistsUser = await fetchUserByPhone(username,'customer');
        }

        
        if(checkifExistsUser){
            await OtpUser.update({
                is_verified:1
            },{
                where:{
                    id:id
                }
            })

            res.json(successRes('verified',otpuser))
        }else{
            res.json(failedRes('user not found'))
        }


    }else{
        res.json(failedRes('Invalid OTP!'))
    }
}

const user_login = async (req,res) => {
    // const {username,password}=req.body;
    console.log(req.body);
    const {username,password,device_id,device_type,device_token} = req.body;

    var checkifExistsUser=0;
    if(validateEmail(username)){
        checkifExistsUser = await fetchUserByEmail(username,'customer')
    }else{
        checkifExistsUser = await fetchUserByPhone(username,'customer');
    }

    if(checkifExistsUser){
        if(checkifExistsUser.status==1){

            const dateTime =await currentTimeStamp();
            const storeData = {
                user_id:checkifExistsUser.id,
                device_id: device_id,
                device_type: device_type,
                device_token: device_token,
                created_at: dateTime,
                updated_at: dateTime,
            };
            const verified = bcrypt.compareSync(password, checkifExistsUser.password);
            if (verified) {
                // checkifExistsUser.dataValues.token_expiry=token_expiry;
                const token_expiry = moment().add(jwtExpiration,'seconds').format('YYYY-MM-DD HH:mm:ss')
                await UserLogin.create(storeData)
                var token = accessToken({ id: checkifExistsUser.id });
                var refreshtoken = refreshTokencreate({ id: checkifExistsUser.id });
                res.json({
                    status:true,
                    message:'success',
                    data:checkifExistsUser,
                    token:token,
                    token_expiry,
                    refreshtoken:refreshtoken
                });
            }else{
                res.json(failedRes('Invalid Password'))
            }

        }else{
            res.json(failedRes('Your account is not active, Please contact to admin support !!'))
        }
    }else{
        res.json(failedRes('User Not Found'))
    }
}

const user_profile = async (req,res) => {
    res.json(successRes('fetched',req.body.user))
}


const get_user_id_by_token = async (token) => {
    let promise = new Promise(function (resolve, reject) {
      jwt.verify(token, jwtsecret, (err, decoded) => {
            if (err) {
                reject(0);
              } else {
                resolve(decoded.id);
              }

       
      });
    });
  
    return promise;
};


const get_user_id_by_token_refresh = async (token) => {
    let promise = new Promise(function (resolve, reject) {
      jwt.verify(token, refreshJwtsecret, (err, decoded) => {
            if (err) {
                reject(0);
              } else {
                resolve(decoded.id);
              }

       
      });
    });
  
    return promise;
};

const regenerateToken = async (req,res) => {
    const {refreshToken}=req.body;
    var user_id;
    try {
        user_id = await get_user_id_by_token_refresh(refreshToken);
        if(user_id){
            var token = accessToken({ id: user_id });
            var refreshtoken = refreshTokencreate({ id: user_id });
            var user = await User.findOne({where:{id:user_id}});

            const token_expiry = moment().add(jwtExpiration,'seconds').format('YYYY-MM-DD HH:mm:ss')
          
            res.json({
                status:user ? true : false,
                message:"",
                data:user,
                token:token,
                token_expiry:token_expiry,
                refreshtoken:refreshtoken
            })
        }else{
            res.json({
                status:false,
                message:"",
            })
        }
    } catch (error) {
        res.json({
            status:false,
            message:"",
        })
    }
    
  
}


const edit_user_profile = async (req,res) => {
    const {user_id,name,email} = req.body;
    if(email){
        const checkemail = await User.findOne({
            where:{
                email:email,
                id:{
                    [Op.ne]:user_id
                }
            }
        })

        if(checkemail){
            res.json(failedRes('email address already been taken!'));
        }else{

            User.update({
                name:name,
                email:email
            },{
                where:{
                    id:user_id
                }
            })
            res.json(successRes('updated'))
        }


    }else{
        User.update({
            name:name,
        },{
            where:{
                id:user_id
            }
        })
        res.json(successRes('updated'))
    }
}

const change_password_by_old_password = async (req,res) => {
    const {user_id,old_password,new_password} = req.body;
    const user = await User.findOne({
        where:{
            id:user_id
        }
    })
    if(user){

        const verified = bcrypt.compareSync(old_password, user.password);
        if(!verified){
            res.json(failedRes('Old password does not match'))
        }else{

            var hashedPassword = bcrypt.hashSync(new_password, 8);
            const update = await User.update({
                password:hashedPassword
            },{
                where:{
                    id:user_id
                }
            })
            res.json(successRes('Password updated successfully'))
        }

    }else{
        res.json(failedRes('something went wrong!'));
    }

}

const get_website_banners = async (req,res) => {
    const {user_id} = req.body;

    const banners = await Banner.scope(['website']).findOne();
    return res.json(banners ? successRes('',banners) : failedRes('',banners))
}




/** login otp user */
const login_otp_app = async (req, res) => {
    const { phone } = req.body;
    const dateTime =  currentTimeStamp();
    const otp = generateOTP();
    const check = await fetchUserByPhone(phone);
    if(check){
        if(check.status != 1){
            return res.json(failedRes('Your account is blocked by admin,Please contact to admin support'))
        }else{
            await OtpUser.create({
                phone: phone,
                phone_verified: 1,
                email_verified:0,
                otp: otp,
                user_type: 2,
                created_at: dateTime,
                updated_at: dateTime,
            })
            .then((r) => {
                send_otp_msg91(phone,'91',otp)
                res.json(successRes("OTP sent succesfully", {id:r.id}))
        })
            .catch((err) => res.json(failedRes("Something went wrong", err)));
        }
    }else{
      return res.json(failedRes('Mobile number not exists!',check));
    }
};

const verify_login_otp = async (req,res) => {
    const {id,otp,device_id,device_type,device_token,model_name} = req.body;
    var checkifExistsUser=0;
    var email=false;
    var phone=false;
    const otpuser =await OtpUser.findOne({
        where:{id:id,otp:otp},
        order:[['id','desc']]
    })

    if(otpuser){
        var username;
        if(otpuser.email_verified){
            username=otpuser.email
        }else{
            username=otpuser.phone;
        }
        if(validateEmail(username)){
            email=true;
            checkifExistsUser = await fetchUserByEmail(username,'customer')
        }else{
            phone=true;;
            checkifExistsUser = await fetchUserByPhone(username,'customer');
        }

        const dateTime = currentTimeStamp();
        if(checkifExistsUser){
            
            const storeData2 = {
                user_id:checkifExistsUser.id,
                device_id: device_id,
                device_type: device_type,
                device_token: device_token,
                model_name,
                created_at: dateTime,
                updated_at: dateTime,
            };
            const token_expiry = moment().add(jwtExpiration,'seconds').format('YYYY-MM-DD HH:mm:ss')
            await UserLogin.create(storeData2);
            await otp_destroy(username)
            var token = accessToken({ id: checkifExistsUser.id });
            var refreshtoken = refreshTokencreate({ id: checkifExistsUser.id });
            User.update({
                device_id: device_id,
                device_type: device_type,
                device_token: device_token,
                model_name,
                updated_at: dateTime,
            },{
                where:{
                    id:checkifExistsUser.id
                }
            })

            // if(checkreferralUser){
            //     cashback_offer_for_referral(checkreferralUser.id,createuser.id,device_id)
            // }
            res.json({
                status:true,
                message:'success',
                data:checkifExistsUser,
                token:token,
                refreshtoken:refreshtoken,
                token_expiry
            });

            


            // res.json(successRes('password changed successfully'))
        }else{
            res.json(failedRes('user not found'))
        }


    }else{
        res.json(failedRes('Invalid OTP!'))
    }

}



/** login otp user */
const login_otp_delivery_app = async (req, res) => {
    const { phone } = req.body;
    const dateTime =  currentTimeStamp();
    const otp = generateOTP();
    const check = await fetchUserByPhone(phone,'delivery_boy');
    if(check){
        if(check.status != 1){
            return res.json(failedRes('Your account is blocked by admin,Please contact to admin support'))
        }else{
            await OtpUser.create({
                phone: phone,
                phone_verified: 1,
                email_verified:0,
                otp: otp,
                user_type: 3,
                created_at: dateTime,
                updated_at: dateTime,
            })
            .then((r) => {
                send_otp_msg91(phone,'91',otp)
                res.json(successRes("OTP sent succesfully", {id:r.id}))
        })
            .catch((err) => res.json(failedRes("Something went wrong", err)));
        }
    }else{
      return res.json(failedRes('Mobile number not exists!',check));
    }
};


const verify_login_deliveryapp = async (req,res) => {
    console.log(req.body);
    const {id,otp,device_id,device_type,device_token,model_name} = req.body;
    var checkifExistsUser=0;
    var email=false;
    var phone=false;
    const otpuser =await OtpUser.findOne({
        where:{id:id,otp:otp,user_type:3},

        order:[['id','desc']]
    })

    if(otpuser){
        var username;
        if(otpuser.email_verified){
            username=otpuser.email
        }else{
            username=otpuser.phone;
        }
        if(validateEmail(username)){
            email=true;
            checkifExistsUser = await fetchUserByEmail(username,'delivery_boy')
        }else{
            phone=true;;
            checkifExistsUser = await fetchUserByPhone(username,'delivery_boy');
        }

        const dateTime = currentTimeStamp();
        if(checkifExistsUser){
            
            const storeData2 = {
                user_id:checkifExistsUser.id,
                device_id: device_id,
                device_type: device_type,
                device_token: device_token,
                model_name,
                created_at: dateTime,
                updated_at: dateTime,
            };
            const token_expiry = moment().add(jwtExpiration,'seconds').format('YYYY-MM-DD HH:mm:ss')
            await UserLogin.create(storeData2);
            await otp_destroy(username)
            var token = accessToken({ id: checkifExistsUser.id });
            var refreshtoken = refreshTokencreate({ id: checkifExistsUser.id });

            User.update({
                device_id: device_id,
                device_type: device_type,
                device_token: device_token,
                model_name,
                updated_at: dateTime,
            },{
                where:{
                    id:checkifExistsUser.id
                }
            })

            // if(checkreferralUser){
            //     cashback_offer_for_referral(checkreferralUser.id,createuser.id,device_id)
            // }
            res.json({
                status:true,
                message:'success',
                data:checkifExistsUser,
                token:token,
                refreshtoken:refreshtoken,
                token_expiry
            });

            // res.json(successRes('password changed successfully'))
        }else{
            res.json(failedRes('user not found'))
        }


    }else{
        res.json(failedRes('Invalid OTP!'))
    }

}
module.exports={
    user_signup_otp,
    user_signup_verify,
    user_login,
    resend_otp,
    forgot_otp,
    forgot_verify,
    user_profile,
    regenerateToken,
    edit_user_profile,
    change_password_by_old_password,
    get_website_banners,
    login_otp_app,
    verify_login_otp,
    login_otp_delivery_app,
    verify_login_deliveryapp,
    forgot_verify_otp,
    forgot_change_password
}
