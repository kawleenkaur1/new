const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
  const User = sequelize.define(
    "order_histories",
    {
      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      order_id: {
        type: DataTypes.INTEGER,
      },
      user_id: {
        type: DataTypes.INTEGER,
      },
      product_id: {
        type: DataTypes.INTEGER,
      },
      order_type: {
        type: DataTypes.INTEGER,
      },
      qty: {
        type: DataTypes.INTEGER,
      },
      unit_price: {
        type: DataTypes.FLOAT,
      },
      price: {
        type: DataTypes.FLOAT,
      },

      actual_qty: {
        type: DataTypes.INTEGER,
      },
      unit: {
        type: DataTypes.STRING,
      },

      product_name: {
        type: DataTypes.STRING,
      },
      product_image: {
        type: DataTypes.STRING,
      },
      product_mrp: {
        type: DataTypes.FLOAT,
      },
      product_discount: {
        type: DataTypes.FLOAT,
      },

      created_at: {
        type: DataTypes.DATE,
      },
      updated_at: {
        type: DataTypes.DATE,
      },
    
    //   imageUrl: {
    //     type: DataTypes.VIRTUAL,
    //     get() {
    //       return `${imagePaths.user}${this.image}`;
    //     },
    //   },
    },
    {
      tableName: "order_histories",
      timestamps: false,
      defaultScope: {},
      scopes: {
      },
    }
  );

  return User;
};
