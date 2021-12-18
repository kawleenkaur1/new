const { DataTypes } = require("sequelize");

module.exports = (sequelize, Sequelize) => {
    const Otpuser = sequelize.define("otp_users", {
      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
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
      password: {
        type: Sequelize.STRING
      },
      email_verified: {
        type: Sequelize.INTEGER.UNSIGNED
      },
      user_type: {
        type: Sequelize.INTEGER.UNSIGNED
      },
      phone_verified: {
        type: Sequelize.INTEGER.UNSIGNED
      },
      otp: {
        type: Sequelize.STRING
      },
      is_verified:{
        type: Sequelize.INTEGER

      },
      otp_email: {
        type: Sequelize.STRING
      },
      referral_from	:{
          type:DataTypes.STRING
      },
      created_at: {
            type: DataTypes.DATE,
        },
        updated_at: {
            type: DataTypes.DATE,
        },
    },{
        timestamps: false,
        defaultScope:{},
        scopes:{
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

    return Otpuser;
};
