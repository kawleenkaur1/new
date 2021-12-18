const { DataTypes } = require("sequelize");
module.exports = (sequelize, Sequelize) => {
    const User = sequelize.define('user_logins',{

      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      user_id: {
        type: Sequelize.INTEGER
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
      ip_address:{
        type: Sequelize.STRING
      },
      is_login: {
        type: Sequelize.STRING
      },
      created_at: {
        type: DataTypes.DATE
      },
      updated_at: {
        type: DataTypes.DATE
      },
    },{
        tableName: 'user_logins',
        timestamps: false,
        defaultScope:{},
        scopes:{
        }
    });

    return User;
};
