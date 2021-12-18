const { DataTypes } = require("sequelize");
const { imagePaths } = require("../config/app.config");
module.exports = (sequelize, Sequelize) => {
    const User = sequelize.define('products',{

      id: {
        primaryKey: true,
        type: Sequelize.INTEGER,
        autoIncrement: true,
      },
      code: {
        type: Sequelize.STRING
      },
      name: {
        type: Sequelize.STRING
      },
      image: {
        type: Sequelize.STRING
      },
      hover_image: {
        type: Sequelize.STRING
      },
      description: {
        type: DataTypes.TEXT,
      },
      mrp: {
        type: DataTypes.FLOAT,
      },
      discount: {
        type: DataTypes.FLOAT,
      },
      discount_type: {
        type: DataTypes.INTEGER,
      },
      selling_price: {
        type: DataTypes.FLOAT,
      },
      subscription_price: {
        type: DataTypes.FLOAT,
      },
      show_in_subscriptions: {
        type: DataTypes.INTEGER,
      },
      stock: {
        type: DataTypes.INTEGER,
      },
      // qty: {
      //   type: DataTypes.INTEGER,
      // },
      no_of_pieces: {
        type: DataTypes.STRING,
      },
      serves: {
        type: DataTypes.STRING,
      },
      cooking_time: {
        type: DataTypes.STRING,
      },
      net_wt: {
        type: DataTypes.STRING,
      },
      gross_wt: {
        type: DataTypes.STRING,
      },
      tags: {
        type: DataTypes.STRING,
      },
      unit: {
        type: DataTypes.STRING,
      },

      position: {
        type: DataTypes.INTEGER,
      },

      hifen_name: {
        type: DataTypes.STRING,
      },
      status: {
        type: DataTypes.INTEGER,
      },
      mark_as_new: {
        type: DataTypes.INTEGER,
      },
      mark_as_hotselling: {
        type: DataTypes.INTEGER,
      },
      category_ids_string: {
        type: DataTypes.STRING,
      },

      created_at: {
        type: DataTypes.DATE
      },
      updated_at: {
        type: DataTypes.DATE
      },
      is_combo: {
        type: DataTypes.INTEGER,
      },
      rating: {
        type: DataTypes.STRING,
      },
      gallery: {
        type: DataTypes.STRING,
      },
      is_deal: {
        type: DataTypes.INTEGER,
      },

      start_date: {
        type: DataTypes.DATE,
      },
      end_date: {
        type: DataTypes.DATE,
      },

      imagePath: {
        type: DataTypes.VIRTUAL,
        get() {
          return `${imagePaths.product}`;
        }
    },
      imageUrl: {
          type: DataTypes.VIRTUAL,
          get() {
            return `${imagePaths.product}${this.image}`;
          }
      },
      hoverimageUrl: {
        type: DataTypes.VIRTUAL,
        get() {
          return `${imagePaths.product}${this.hover_image}`;
        }
      }
    },{
        tableName: 'products',
        timestamps: false,
        defaultScope:{},
        scopes:{
          active:{
            where:{status:1}
          },
          best_sellers:{
            where:{
              mark_as_bestoffers:1
            }
          },
          hotselling:{
            where:{
              mark_as_hotselling:1
            }
          },
          combos:{
            where:{
              is_combo:1
            }
          },
        }
    });

    return User;
};
