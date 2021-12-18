const successRes = (message='...',data=undefined,token=undefined) => {
    const obj = {
        status:true,
        message:message,
        data:data,
        token
    }
    return obj;
}

const failedRes = (message='...',data) => {
    const obj = {
        status:false,
        message:message,
        data:data
    }
    return obj;
}

exports.successRes=successRes;
exports.failedRes=failedRes;