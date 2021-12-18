const Razorpay = require("razorpay");
const moment = require("moment")
const crypto = require("crypto")
const RAZORPAY_SECRET='LfNbkpEyrHCIK6yXV1N67xNK'
const RAZORPAY_KEY_ID='rzp_test_RwSgFPpvHSua0O'
const { fetchUserByID, currentTimeStamp } = require("../helpers/user.helper");
const { sequelize, Transaction, User, Setting, Plan } = require("../models");

const { failedRes, successRes } = require("../helpers/response.helper");

const generate_order_req = async (req,res) => {
    var {amount,user_id} = req.body;
    if(!amount){
        amount=500
    }
    if(!user_id){
        user_id=0;
    }
    const receipt_id = moment().utc()+user_id

    try {
        const instance = new Razorpay({
            key_id: RAZORPAY_KEY_ID,
            key_secret: RAZORPAY_SECRET,
        });

        const options = {
            amount: amount*100, // amount in smallest currency unit
            currency: "INR",
            receipt: receipt_id,
        };

        const order = await instance.orders.create(options);

        if (!order) return res.status(500).send("Some error occured");

        res.json(order);
    } catch (error) {
        res.status(500).send(error);
    }
}

const recharge_user_wallet = async (req,res) => {
    const {user_id,recharge_amount,gst_perct,gst_amount,
      orderCreationId,
      razorpayPaymentId,
      razorpayOrderId,
      razorpaySignature,
    } = req.body;
    const txn_id = razorpayPaymentId;

    console.log('====================================');
    console.log(req.body);
    console.log('====================================');

      // Creating our own digest
        // The format should be like this:
        // digest = hmac_sha256(orderCreationId + "|" + razorpayPaymentId, secret);
    const shasum = crypto.createHmac("sha256", RAZORPAY_SECRET);

    shasum.update(`${orderCreationId}|${razorpayPaymentId}`);

    const digest = shasum.digest("hex");

    // comaparing our digest with the actual signature
    if (digest !== razorpaySignature)
        return res.status(200).json(failedRes('Transaction not legit!'));


    const amount =parseFloat(recharge_amount);
    const user = await fetchUserByID(user_id);
    if(!user) { return res.json(failedRes('user not found')) }
    var old_wallet = parseFloat(user.wallet);
    var txn_amount =  parseFloat(amount);
    var new_wallet = old_wallet+txn_amount;
    var txn_type = 'credit';
    const dateTime = currentTimeStamp();
    var transactiondata = {
      user_id:user_id,
      txn_name:'Recharge Wallet',
      payment_mode:'online',
      order_txn_id:txn_id,
      txn_for:'wallet_recharge',
      type:txn_type,
      old_wallet:old_wallet,
      txn_amount:txn_amount,
      update_wallet:new_wallet,
      status:1,
      bank_txn_id:orderCreationId,
      created_at:dateTime,
      updated_at:dateTime,
    };
    const t = await sequelize.transaction();
    try {
      await User.update({
        wallet:new_wallet
      },{
        where:{
          id:user_id
        }
      },  { transaction: t })
      const dt = await Transaction.create(transactiondata
        , { transaction: t });
      await t.commit();
      return res.json(successRes('Wallet Recharge Successfully Done!',dt))
    }catch (error) {
      // If the execution reaches this line, an error was thrown.
      // We rollback the transaction.
      await t.rollback();
      return res.json(failedRes('failed!!'));
    }
}

module.exports = {
    generate_order_req,
    recharge_user_wallet
}