const AUTHKEY = '365839A15HpgpSWk6142e220P1';
const { makeRequest } = require("./axios.helper");



const send_otp_msg91 = async (phone,phone_code='91', otp) => {

    // const url = `https://control.msg91.com/api/sendotp.php?country=${phone_code}&otp=${otp}&sender=ASTHLP&mobile=${phone}&authkey=${authkey}`
    const headers = {
        "authkey": AUTHKEY,
        "content-type": "application/JSON"
    }

    const url = `https://api.msg91.com/api/v5/flow/`
    const data = {
        "flow_id": "616d1b988527ee2d0038aa12",
        "sender": "KATLGO",
        "mobiles": phone_code+''+phone,
        "otp": otp
    }
    const respo =await makeRequest('post',url,headers,data);
    console.log(respo);
    // console.log('====================================');
    // console.log(respo);
    // console.log('====================================');
    return 1;

};



const forgot_otp_msg91 = async (phone,phone_code='91', otp) => {

    // const headers = {
    //     "authkey": AUTHKEY,
    //     "content-type": "application/JSON"
    // }

    // const url = `https://api.msg91.com/api/v5/flow/`
    // const data = {
    //     "flow_id": "616d1d58e8f35e6f437d6ba5",
    //     "sender": "KATLGO",
    //     "mobiles": phone_code+''+phone,
    //     "otp": otp
    // }
    // const respo =await makeRequest('post',url,headers,data);
    // return 1;

    const headers = {
        "authkey": AUTHKEY,
        "content-type": "application/JSON"
    }

    const url = `https://api.msg91.com/api/v5/flow/`
    const data = {
        "flow_id": "616d1b988527ee2d0038aa12",
        "sender": "KATLGO",
        "mobiles": phone_code+''+phone,
        "otp": otp
    }
    const respo =await makeRequest('post',url,headers,data);
    console.log(respo);
    // console.log('====================================');
    // console.log(respo);
    // console.log('====================================');
    return 1;

};

module.exports = {
    send_otp_msg91,
    forgot_otp_msg91
}