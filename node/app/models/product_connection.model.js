const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
  const User = sequelize.define(
    "product_connections",
    {
      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      product_id: {
        type: DataTypes.STRING,
      },
      category_id: {
        type: DataTypes.STRING,
      },
      created_at: {
        type: DataTypes.DATE,
      },
      updated_at: {
        type: DataTypes.DATE,
      },
    },
    {
      tableName: "product_connections",
      timestamps: false,
      defaultScope: {},
      scopes: {
      },
    }
  );

  return User;
};
