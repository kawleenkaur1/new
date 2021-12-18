const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
  const User = sequelize.define(
    "delivery_configs",
    {
        id: {
            primaryKey: true,
            type: Sequelize.INTEGER,
            autoIncrement: true,
        },
        type:{
            type:DataTypes.STRING
        },
        time_take:{
            type:DataTypes.INTEGER
        },
        time_slots:{
            type:DataTypes.STRING
        },
        status:{
            type:DataTypes.INTEGER
        },
        created_at:{
            type:DataTypes.DATE
        },
        updated_at:{
            type:DataTypes.DATE
        },

    },
    {
      tableName: "delivery_configs",
      timestamps: false,
      defaultScope: {},
      scopes: {
      },
    }
  );

  return User;
};
