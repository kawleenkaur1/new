const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
  const User = sequelize.define(
    "banners",
    {
      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      link_id: {
        type: DataTypes.INTEGER,
      },
      link_type: {
        type: DataTypes.INTEGER,
      },
      banner_type: {
        type: DataTypes.STRING,
      },
      name: {
        type: DataTypes.STRING,
      },
      image: {
        type: DataTypes.STRING,
      },
      type: {
        type: DataTypes.INTEGER,
      },
      position: {
        type: DataTypes.INTEGER,
      },
      status: {
        type: DataTypes.INTEGER,
      },
      created_at: {
        type: DataTypes.DATE,
      },
      updated_at: {
        type: DataTypes.DATE,
      },
      link_parent_id: {
        type: DataTypes.INTEGER,
      },
      imageUrl: {
        type: DataTypes.VIRTUAL,
        get() {
          return `${imagePaths.banner}${this.image}`;
        },
      },
    },
    {
      tableName: "banners",
      timestamps: false,
      defaultScope: {
      },
      scopes: {
        active: {
          where: { status: 1 },
        },
        website : {
          where:{
            banner_type:'website'
          }
        },

        app : {
          where:{
            banner_type:'app'
          }
        }
      },
    }
  );

  return User;
};
