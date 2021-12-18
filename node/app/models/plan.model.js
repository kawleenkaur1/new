
const { DataTypes } = require("sequelize");

module.exports = (sequelize, Sequelize) => {
    const Otpuser = sequelize.define("plans", {
      id: {
        primaryKey: true,
        type: DataTypes.INTEGER,
        autoIncrement: true,
      },
      name: {
        type: DataTypes.STRING
      },
      amount: {
        type: DataTypes.FLOAT
      },
      benefit: {
        type: DataTypes.FLOAT
      },
      position: {
        type: DataTypes.INTEGER
      },
      status: {
        type: DataTypes.INTEGER
      },
      created_at: {
            type: DataTypes.DATE,
        },
        updated_at: {
            type: DataTypes.DATE,
        },
    },{
        timestamps: false,
        tableName: "plans",
        defaultScope:{},
        scopes:{
          active : {
              where:{
                  status:1
              }
          },
          orderAsc:{
              order:[['amount','asc']]
          }
        }
    });

    return Otpuser;
};
