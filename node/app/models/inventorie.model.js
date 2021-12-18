const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
  const User = sequelize.define(
    "inventories",
    {
        id: {
            primaryKey: true,
            type: Sequelize.INTEGER,
            autoIncrement: true,
        },
        user_id:{
            type:DataTypes.INTEGER
        },
        product_id:{
            type:DataTypes.INTEGER
        },
        stock:{
            type:DataTypes.INTEGER
        },
        stock_status:{
            type:DataTypes.INTEGER
        },
        status :{
            type:DataTypes.INTEGER
        },
        order_id :{
            type:DataTypes.INTEGER
        },
        added_by :{
            type:DataTypes.INTEGER
        },
        comment :{
            type:DataTypes.TEXT
        },
        created_at:{
            type:DataTypes.DATE
        },
        updated_at:{
            type:DataTypes.DATE
        },

    },
    {
      tableName: "inventories",
      timestamps: false,
      defaultScope: {},
      scopes: {
          in_stock :{
              stock_status:1
          },
          out_stock :{
            stock_status:2
            }
      },
    }
  );

  return User;
};
