const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
    const User = sequelize.define('partners',{

      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      image: {
        type: Sequelize.STRING
      },
      position: {
        type: Sequelize.INTEGER
      },
      type: {
        type: Sequelize.INTEGER
      },

      imageUrl: {
          type: DataTypes.VIRTUAL,
          get() {
            return `${imagePaths.partner}${this.image}`;
          }
      }
    },{
        tableName: 'partners',
        timestamps: false,
        defaultScope:{},
        scopes:{
        
        }
    });

    return User;
};
