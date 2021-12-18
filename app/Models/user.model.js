const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
    const User = sequelize.define('users',{

      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      user_type: {
        type: Sequelize.STRING
      },
      name: {
        type: Sequelize.STRING
      },
      email: {
        type: Sequelize.STRING
      },
      phone: {
        type: Sequelize.STRING
      },
      image: {
        type: Sequelize.STRING
      },
      password: {
        type: Sequelize.STRING
      },
      email_verified: {
        type: DataTypes.INTEGER
      },
      email_verified: {
        type: DataTypes.INTEGER
      },
      email_verified_at: {
        type: DataTypes.DATE
      },
      phone_verified_at: {
        type: DataTypes.DATE
      },
      referral_code: {
        type: Sequelize.STRING
      },
      referral_from: {
        type: Sequelize.STRING
      },
      auth_type: {
        type: Sequelize.STRING
      },
      device_id: {
        type: Sequelize.STRING
      },
      device_type: {
        type: Sequelize.STRING
      },
      device_token: {
        type: Sequelize.STRING
      },
      model_name: {
        type: Sequelize.STRING
      },
      social_id: {
        type: Sequelize.STRING
      },
      status: {
        type: Sequelize.INTEGER
      },
      is_online: {
        type: Sequelize.INTEGER
      },
      cod: {
        type: Sequelize.INTEGER
      },
      warehouse_id: {
        type: Sequelize.INTEGER
      },
      wallet: {
        type: Sequelize.FLOAT
      },
      created_at: {
        type: DataTypes.DATE
      },
      updated_at: {
        type: DataTypes.DATE
      },
      shift_timing:{
        type: DataTypes.STRING

      },
      latitude:{
        type: DataTypes.STRING
      },
      longitude:{
        type: DataTypes.STRING

      },
      location:{
        type: DataTypes.STRING

      },
      pincode:{
        type: DataTypes.STRING

      },

      imageUrl: {
          type: DataTypes.VIRTUAL,
          get() {
            return `${imagePaths.user}${this.image}`;
          }
      }
    },{
        tableName: 'users',
        timestamps: false,
        defaultScope:{},
        scopes:{
          active:{
            where:{status:1}
          },
          delivery_boy:{
              where:{
                user_type:3
              }
          },
          customer:{
            where:{
                user_type:2
            }
            }
        }
    });

    return User;
};
