// Load the AWS SDK for Node.js
var AWS = require('aws-sdk');
const { aws } = require('../config/app.config');

// Set region
AWS.config.update({region: 'ap-south-1',
accessKeyId: aws.aws_access_key_id,      // should be:  process.env.AWS_ACCESS_ID
secretAccessKey:aws.aws_secret_access_key,
});
// getSMSTypePromise.then(
//     function(data) {
//       console.log(data);
//     }).catch(
//       function(err) {
//       console.error(err, err.stack);
//     });


const send_forgot_otp = async (otp,phone_number) => {
    // Create publish parameters
    console.log(phone_number);
    var params = {
        Message: 'Your verification code for forgot password is <'+otp+'>. Please do not share this with anyone.', /* required */
        PhoneNumber: '+91'+phone_number,
    };

    // Create promise and SNS service object
    var prom= await new AWS.SNS({apiVersion: '2010-03-31'}).publish(params).promise();
     console.log(prom);
     return true;
}




const send_otp = async (otp,phone_number) => {
    // Create publish parameters
    console.log(phone_number);
    var params = {
        Message: 'Your verification code is '+otp, /* required */
        PhoneNumber: '+91'+phone_number,
    };

    // Create promise and SNS service object
    var prom= await new AWS.SNS({apiVersion: '2010-03-31'}).publish(params).promise();
     console.log(prom);
     return true;
}


// const 
module.exports = {
    send_otp,
    send_forgot_otp
}