const { DataTypes } = require("sequelize");
module.exports = (sequelize, Sequelize) => {
    const User = sequelize.define('wishlists',{

      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      user_id: {
        type: DataTypes.INTEGER
      },
      product_id:{
          type:DataTypes.INTEGER
      },
      created_at: {
        type: DataTypes.DATE
      },
      updated_at: {
        type: DataTypes.DATE
      },
    },{
        tableName: 'wishlists',
        timestamps: false,
        defaultScope:{},
        scopes:{
        }
    });

    return User;
};
