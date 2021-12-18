const { isPhoneExists } = require('../helpers/user.helper.js');
const validator=require('../helpers/validate.js');

// err.first('phone')
const validationErrorObj = (err,message='Validation failed') => {
   return { status: false,
    message: message ,
    data: err
   }
}


const register_login_otp_valid = (req, res, next) => {
    const validationRule = {
        "phone": "required|min:10|max:10",
    }

    validator(req.body, validationRule, {},(err,status) => {
        if (!status) {
            res.send(validationErrorObj(err));
        } else {
            next();
        }
    });
}


const edit_profile_details = (req, res, next) => {
    const validationRule = {
        "name": "required",
        "birth_time": "required",
        "dob": "required",
        "place_of_birth": "required",
        "address": "required",
    }

    validator(req.body, validationRule, {},(err,status) => {
        if (!status) {
            res.send(validationErrorObj(err));
        } else {
            next();
        }
    });
}



const register_otp_valid = (req, res, next) => {
    const validationRule = {
        "phone": "required|min:10|max:10",
        "email":"email",
        "name":"required"
    }

    validator(req.body, validationRule, {},(err,status) => {
        if (!status) {
            res.send(validationErrorObj(err));
        } else {
            next();
        }
    });
}


const verify_register_valid = (req, res, next) => {
    const validationRule = {
        "id":"required",
        "phone": "required|min:10|max:10",
    }

    validator(req.body, validationRule, {},(err,status) => {
        if (!status) {
            res.send(validationErrorObj(err));
        } else {
            next();
        }
    });
}



const login_otp_valid = (req, res, next) => {
    const validationRule = {
        "phone": "required|min:10|max:10",
    }

    validator(req.body, validationRule, {},(err,status) => {
        if (!status) {
            res.send(validationErrorObj(err));
        } else {
            next();
        }
    });
}


const verify_login_valid = (req, res, next) => {
    const validationRule = {
        "id":"required",
        "phone": "required|min:10|max:10",
    }

    validator(req.body, validationRule, {},(err,status) => {
        if (!status) {
            res.send(validationErrorObj(err));
        } else {
            next();
        }
    });
}


const edit_password_valid = (req, res, next) => {
    const validationRule = {
        "name":"required",
        "password": "required|min:7",
    }

    validator(req.body, validationRule, {},(err,status) => {
        if (!status) {
            res.send(validationErrorObj(err));
        } else {
            next();
        }
    });
}


const login_with_password_valid = (req, res, next) => {
    const validationRule = {
        "password":"required",
        "phone": "required|min:10|max:10",
    }

    validator(req.body, validationRule, {},(err,status) => {
        if (!status) {
            res.send(validationErrorObj(err));
        } else {
            next();
        }
    });
}
// const otp_user_valid = (req, res, next) => {
//     const validationRule = {
//         "phone": "required|min:10|max:10",
//         "email":"email",
//         "name":"required"
//     }

//     validator(req.body, validationRule, {},(err,status) => {
//         if (!status) {
//             res.send(validationErrorObj(err));
//         } else {
//             next();
//         }
//     });
// }

module.exports = {
    register_otp_valid,
    verify_register_valid,
    login_otp_valid,
    verify_login_valid,
    login_with_password_valid,
    validationErrorObj,
    register_login_otp_valid,
    edit_password_valid,
    edit_profile_details
}