const { DataTypes } = require("sequelize");
const { sequelize } = require(".");
const { imagePaths } = require("../config/app.config");
const Op = require('sequelize').Op;
const moment = require('moment');
module.exports = (sequelize, Sequelize) => {
    const transaction = sequelize.define("transactions", {
      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      user_id: {
        type: Sequelize.INTEGER.UNSIGNED
      },
      order_id: {
        type: Sequelize.INTEGER.UNSIGNED
      },
      order_txn_id: {
        type: Sequelize.STRING
      },
      bank_txn_id: {
        type: Sequelize.STRING
      },
      txn_name: {
        type: Sequelize.STRING
      },
      payment_mode: {
        type: Sequelize.STRING
      },
      type: {
        type: Sequelize.STRING
      },
      old_wallet: {
        type: Sequelize.FLOAT
      },
      txn_amount: {
        type: Sequelize.FLOAT
      },
      update_wallet: {
        type: Sequelize.FLOAT
      },
      status: {
        type: Sequelize.INTEGER
      },
      txn_for: {
        type: Sequelize.STRING
      },
     
      txn_mode: {
        type: Sequelize.STRING
      },
      created_at: {
        type: Sequelize.STRING
      },
      updated_at: {
        type: Sequelize.STRING
      },
 
    },{
        timestamps: false,
        tableName: 'transactions',
        scopes:{
          wallet:{
            where: {
              [Op.or]: [{payment_mode: "wallet"}, {txn_for: "wallet_recharge"}]
            }
            // or: [
            //   {
            //     payment_mode: "wallet",
            //   },
            //   {
            //     txn_for: "wallet",
            //   }
            // ]
            
              // $or: [
              //   {
              //     payment_mode: {
              //       $like: 'Boat%'
              //     }
              //   },
              //   {
              //     txn_for: {
              //       $like: '%boat%'
              //     }
              //   }
              // ]
            
          },
          newest:{
            order: [
              ['id','desc'],
            ]
          }
        }
    });

    return transaction;
};