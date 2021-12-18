const ValidatorJs = require('validatorjs');

const validator = (body, rules, customMessages, callback) => {

    // ValidatorJs.register('customer_phone_exists', function(value, requirement, attribute) { // requirement parameter defaults to null
    //     return isPhoneExists(value);
    //   }, 'The :attribute phone number is already exists');
    const validation = new ValidatorJs(body, rules, customMessages);
    validation.passes(() => callback(null, true));
    validation.fails(() => callback(validation.errors, false));
};

module.exports = validator;