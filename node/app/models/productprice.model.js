const { DataTypes } = require("sequelize");
const { sequelize } = require(".");
const { imagePaths } = require("../config/app.config");
const Op = require('sequelize').Op;
const moment = require('moment');
module.exports = (sequelize, Sequelize) => {
    const transaction = sequelize.define("product_prices", {
      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      product_id: {
        type: DataTypes.INTEGER
      },
      location_id: {
        type: DataTypes.INTEGER
      },
      discount_type: {
        type: DataTypes.INTEGER
      },
      discount: {
        type: DataTypes.FLOAT
      },
      mrp: {
        type: DataTypes.FLOAT
      },
      selling_price: {
        type: DataTypes.FLOAT
      },
      stock: {
        type: DataTypes.INTEGER
      },
      subscription_price: {
        type: DataTypes.FLOAT
      },
      created_at: {
        type: DataTypes.DATE
      },
      updated_at: {
        type: DataTypes.DATE
      },

    },{
        timestamps: false,
        tableName: 'product_prices',
        scopes:{

        }
    });

    return transaction;
};