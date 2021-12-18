const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
  const User = sequelize.define(
    "orders",
    {
      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      order_type: {
        type: DataTypes.INTEGER,
      },
      delivery_type: {
        type: DataTypes.STRING,
      },
      schedule_date: {
        type: DataTypes.DATE,
      },
      schedule_time: {
        type: DataTypes.STRING,
      },
      user_id: {
        type: DataTypes.INTEGER,
      },
      subtotal: {
        type: DataTypes.FLOAT,
      },
      discount: {
        type: DataTypes.FLOAT,
      },
      coupon_discount: {
        type: DataTypes.FLOAT,
      },
      cashback_discount: {
        type: DataTypes.FLOAT,
      },
      gst: {
        type: DataTypes.FLOAT,
      },
      delivery_charges: {
        type: DataTypes.FLOAT,
      },
      payable_amount: {
        type: DataTypes.FLOAT,
      },
      coupon_id: {
        type: DataTypes.INTEGER,
      },
      shipping_name: {
        type: DataTypes.STRING,
      },
      shipping_address_type:{
        type: DataTypes.STRING,
      },
      shipping_phone: {
        type: DataTypes.STRING,
      },
      shipping_flat: {
        type: DataTypes.STRING,
      },
      shipping_pincode: {
        type: DataTypes.STRING,
      },
      shipping_landmark: {
        type: DataTypes.STRING,
      },
      shipping_location: {
        type: DataTypes.STRING,
      },
      shipping_area: {
        type: DataTypes.STRING,
      },
      lat: {
        type: DataTypes.STRING,
      },
      lng: {
        type: DataTypes.STRING,
      },
      warehouse_lat: {
        type: DataTypes.STRING,
      },
      warehouse_lng: {
        type: DataTypes.STRING,
      },
      txn_id: {
        type: DataTypes.STRING,
      },
      payment_mode: {
        type: DataTypes.STRING,
      },
      delivery_boy_id: {
        type: DataTypes.INTEGER,
      },
      status: {
        type: DataTypes.INTEGER,
      },
      is_paid: {
        type: DataTypes.INTEGER,
      },
      is_pickup:{
        type: DataTypes.INTEGER,
      },
      txn_id: {
        type: DataTypes.STRING,
      },
      cancel_reason: {
        type: DataTypes.STRING,
      },
      warehouse_address:{
        type: DataTypes.STRING,

      },
      warehouse_name:{
        type: DataTypes.STRING,

      },
      confirmation_otp:{
        type: DataTypes.STRING,
      },
      delivery_date: {
        type: DataTypes.DATE,
      },

      warehouse_id: {
        type: DataTypes.INTEGER,
      },

      assign_time: {
        type: DataTypes.DATE,
      },
      pickup_time: {
        type: DataTypes.DATE,
      },
      reach_time: {
        type: DataTypes.DATE,
      },
      completed_at: {
        type: DataTypes.DATE,
      },

      created_at: {
        type: DataTypes.DATE,
      },
    
      updated_at: {
        type: DataTypes.DATE,
      },

      cancel_by: {
        type: DataTypes.STRING,
      },
    
    //   imageUrl: {
    //     type: DataTypes.VIRTUAL,
    //     get() {
    //       return `${imagePaths.user}${this.image}`;
    //     },
    //   },
    },
    {
      tableName: "orders",
      timestamps: false,
      defaultScope: {},
      scopes: {
        pending:{
          where:{
            status:0
          }
        },
        ongoing:{
          where:{
            status:1
          }
        },
        completed:{
          where:{
            status:2
          }
        },
        cancelled:{
          where:{
            status:3
          }
        }
      },
    }
  );

  return User;
};
