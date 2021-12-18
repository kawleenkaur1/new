const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
  const User = sequelize.define(
    "settings",
    {
      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      app_name:{
          type:DataTypes.STRING
      },
      support_phone:{
        type:DataTypes.STRING
      },
      support_email:{
        type:DataTypes.STRING
      },
      terms:{
          type:DataTypes.STRING
      },
      privacy_policy:{
          type:DataTypes.STRING
      },
      about_us:{
          type:DataTypes.STRING
      },
      about_us_video:{
        type:DataTypes.STRING
      },
      shipping_policy:{
        type:DataTypes.STRING
      },
      payment_policy:{
        type:DataTypes.STRING
      },
      address:{
        type:DataTypes.STRING
      },
      office_week:{
        type:DataTypes.STRING
      },
      office_time:{
        type:DataTypes.STRING
      },
      sunday_time:{
        type:DataTypes.STRING
      },
      delivery_policy:{
        type:DataTypes.STRING
      },
      promise:{
        type:DataTypes.STRING
      },

    },
    {
      tableName: "settings",
      timestamps: false,
      defaultScope: {
      },
      scopes: {
      
      },
    }
  );

  return User;
};
