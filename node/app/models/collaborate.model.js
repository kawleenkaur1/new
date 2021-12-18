const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
    const User = sequelize.define('collaborates',{

      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      user_id: {
        type: Sequelize.INTEGER
      },

      type: {
        type: DataTypes.STRING
      },

      name: {
        type: DataTypes.STRING
      },
      email: {
        type: DataTypes.STRING
      },
      phone: {
        type: DataTypes.STRING
      },
      city: {
        type: DataTypes.STRING
      },
      state: {
        type: DataTypes.STRING
      },
      message: {
        type: DataTypes.STRING
      },
      created_at: {
        type: DataTypes.DATE
      },

      updated_at: {
        type: DataTypes.DATE
      },

    },{
        tableName: 'collaborates',
        timestamps: false,
        defaultScope:{},
        scopes:{
         
        }
    });

    return User;
};
