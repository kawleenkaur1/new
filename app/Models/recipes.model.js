const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
    const User = sequelize.define('recipes',{

      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      title: {
        type: Sequelize.STRING
      },
      gallery: {
        type: Sequelize.STRING
      },
      image: {
        type: Sequelize.STRING
      },
      description: {
        type: DataTypes.TEXT,
      },
      rating: {
        type: DataTypes.INTEGER,
      },
      time: {
        type: DataTypes.STRING,
      },
      status: {
        type: DataTypes.INTEGER,
      },
      position: {
        type: DataTypes.INTEGER,
      },
      difficulty: {
        type: DataTypes.STRING,
      },
      
      created_at: {
        type: DataTypes.DATE
      },
      updated_at: {
        type: DataTypes.DATE
      },
      base_path_image: {
        type: DataTypes.VIRTUAL,
        get() {
          return `${imagePaths.recipe}`;
        }
    },
      imageUrl: {
          type: DataTypes.VIRTUAL,
          get() {
            return `${imagePaths.recipe}${this.image}`;
          }
      }
    },{
        tableName: 'recipes',
        timestamps: false,
        defaultScope:{},
        scopes:{
          active:{
            where:{status:1}
          },
          orderAsc:{
            order:[['position','asc']]
          },
        }
    });

    return User;
};
