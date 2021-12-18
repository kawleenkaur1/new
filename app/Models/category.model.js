const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
  const User = sequelize.define(
    "categories",
    {
      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      name: {
        type: DataTypes.STRING,
      },
      image: {
        type: DataTypes.STRING,
      },
      image_with_bg: {
        type: DataTypes.STRING,
      },
      position: {
        type: DataTypes.INTEGER,
      },
      any_discount: {
        type: DataTypes.INTEGER,
      },
      discount: {
        type: DataTypes.INTEGER,
      },
      show_homepage_top: {
        type: DataTypes.INTEGER,
      },
      show_homepage_bottom: {
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
      imageUrl: {
        type: DataTypes.VIRTUAL,
        get() {
          return `${imagePaths.category}${this.image}`;
        },
      },
      imagewithbgUrl: {
        type: DataTypes.VIRTUAL,
        get() {
          return `${imagePaths.category}${this.image_with_bg}`;
        },
      },
    },
    {
      tableName: "categories",
      timestamps: false,
      defaultScope: {},
      scopes: {
        active: {
          where: { status: 1 },
        },
        orderAsc: {
            order: [['position','asc']],
        },
        show_on_homepage_top: {
            where:{
                show_homepage_top:1
            }
        },
      },
    }
  );

  return User;
};
