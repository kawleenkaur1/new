const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
  const User = sequelize.define(
    "addresses",
    {
      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      address_type:{
        type: DataTypes.STRING,
      },
      location_id: {
        type: DataTypes.INTEGER,
      },
      area_id: {
        type: DataTypes.INTEGER,
      },
      user_id: {
        type: DataTypes.INTEGER,
      },
      name: {
        type: DataTypes.STRING,
      },
      phone: {
        type: DataTypes.STRING,
      },
      flat: {
        type: DataTypes.STRING,
      },
      city: {
        type: DataTypes.STRING,
      },
      landmark: {
        type: DataTypes.STRING,
      },
      state: {
        type: DataTypes.STRING,
      },
      country: {
        type: DataTypes.STRING,
      },
      pincode: {
        type: DataTypes.STRING,
      },
      lat: {
        type: DataTypes.STRING,
      },
      lng: {
        type: DataTypes.STRING,
      },
      main_location: {
        type: DataTypes.STRING,
      },
      main_society: {
        type: DataTypes.STRING,
      },
      location: {
        type: DataTypes.STRING,
      },
      status: {
        type: DataTypes.INTEGER,
      },
      is_default: {
        type: DataTypes.INTEGER,
      },
      created_at: {
        type: DataTypes.DATE,
      },
      updated_at: {
        type: DataTypes.DATE,
      },
    },
    {
      tableName: "addresses",
      timestamps: false,
      defaultScope: {},
      scopes: {
        active: {
          where: { status: 1 },
        },
      },
    }
  );

  return User;
};
