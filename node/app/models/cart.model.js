const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
  const User = sequelize.define(
    "carts",
    {
      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      user_id: {
        type: DataTypes.INTEGER,
      },
      product_id: {
        type: DataTypes.INTEGER,
      },
      order_type: {
        type: DataTypes.STRING,
      },
      deliveries: {
        type: DataTypes.INTEGER,
      },
      start_date: {
        type: DataTypes.DATE,
      },
      address_id: {
        type: DataTypes.INTEGER,
      },
      qty: {
        type: DataTypes.INTEGER,
      },
      created_at: {
        type: DataTypes.DATE,
      },
      updated_at: {
        type: DataTypes.DATE,
      },
      frequency_id: {
        type: DataTypes.INTEGER,
      },
      skip_days: {
        type: DataTypes.INTEGER,
      }
    },
    {
      tableName: "carts",
      timestamps: false,
      defaultScope: {},
      scopes: {
      },
    }
  );

  return User;
};
