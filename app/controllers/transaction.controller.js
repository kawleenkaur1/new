const { fetchUserByID, currentTimeStamp } = require("../helpers/user.helper");
const { sequelize, Transaction, User, Setting, Plan } = require("../models");

const moment = require("moment");
const { failedRes, successRes } = require("../helpers/response.helper");


const wallet_plans = async (req,res) => {
  const {user_id} = req.body;
  const data = await Plan.scope(['active','orderAsc']).findAll();
  res.json(data?successRes('',data):failedRes);
}

const fetch_payable_amount_wallet = async (req,res) => {
  const {user_id,amount}=req.body;
  var gst_perct = 18

  const settings =await Setting.findOne()
  if(settings){
    gst_perct =parseInt(settings.gst_prct_for_wallet);
  }

  const gst_amount = Math.round(parseFloat(amount)*(gst_perct/100));

  const total_amount = amount + gst_amount;

  return res.json({
    status:true,
    gst_perct,
    gst_amount,
    recharge_amount:amount,
    total_amount
  })
}

const recharge_user_wallet = async (req,res) => {
    const {user_id,recharge_amount,txn_id,gst_perct,gst_amount} = req.body;
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
      booking_txn_id:txn_id,
      txn_for:'wallet',
      type:txn_type,
      old_wallet:old_wallet,
      txn_amount:txn_amount,
      update_wallet:new_wallet,
      status:1,
      created_at:dateTime,
      updated_at:dateTime,
      gst_perct,
      gst_amount
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


const user_wallet_balance = async (req,res) => {
  const {user_id} = req.body;
  var plans = [];
  var cashback_perct=0;

  const user = await fetchUserByID(user_id);
   await Setting.findOne().then((st)=>{
    //  console.log(st);
    if(st){
      // console.log(st.rechargeplans);
        // var dt = JSON.parse(st.rechargeplans);
        // plans = dt.plans.split('|');
        // cashback_perct = dt.cashback_perct
        // console.log(dt);
    }
  })
  const wallet_balance = user.wallet ? user.wallet : 0;
  return res.json({
    status:true,
    wallet_balance,
    plans:[1000,2000,5000,8000],
    cashback_perct:5,
    data:user
  })
}



const wallet_history = async (req,res) => {
  const {user_id} = req.body;
  var {limit,offset}=req.body;
  if(!limit){
    limit=10;
  }
  if(!offset){
    offset=0;
  }
  // console.log(user_id);

  // const user = await fetchUserByID(user_id);
  // const wallet_balance = user.wallet ? user.wallet : 0;
  const transactions =await Transaction.scope(['wallet','newest']).findAll({
    limit:parseInt(limit),
    offset:parseInt(offset),
    where:{
      user_id:user_id
    }
  });
  return res.json({
    status:true,
    // wallet_balance,
    data:transactions
  })
}


module.exports = {
    recharge_user_wallet,
    wallet_history,
    user_wallet_balance,
    fetch_payable_amount_wallet,
    wallet_plans
};
