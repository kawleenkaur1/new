const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
  const User = sequelize.define(
    "f_a_q_s",
    {
        id: {
            primaryKey: true,
            type: Sequelize.INTEGER,
            autoIncrement: true,
        },
        title: {
            type: DataTypes.VIRTUAL,
            get() {
              return `${this.question}`;
            }
        },
        content: {
            type: DataTypes.VIRTUAL,
            get() {
              return `${this.answer}`;
            }
        },
        question:{
            type:DataTypes.STRING
        },
        answer:{
            type:DataTypes.STRING
        },
        position:{
            type:DataTypes.INTEGER
        },
    
        status :{
            type:DataTypes.INTEGER
        },
        position :{
            type:DataTypes.INTEGER
        },
      
    },
    {
      tableName: "f_a_q_s",
      timestamps: false,
      defaultScope: {},
      scopes: {
          orderAsc :{
              order:[['position','asc']]
          },
          active :{
            where: { status: 1 },
            }
      },
    }
  );

  return User;
};
