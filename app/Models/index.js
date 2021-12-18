const dbConfig=require('../config/db.config');
const Sequelize=require('sequelize');
const sequelize=new Sequelize(dbConfig.DB,dbConfig.USER,dbConfig.PASSWORD, {
    host: dbConfig.HOST,
    dialect: dbConfig.dialect,
    timezone: '+05:30', // for writing to database
    dialectOptions: {
        useUTC: false, //for reading from database
    },
    dialectOptions: {
        // useUTC: false, //for reading from database
        dateStrings: true,
        typeCast: function (field, next) { // for reading from database
          if (field.type === 'DATETIME') {
            return field.string()
          }
            return next()
          },
    },
    pool: {
      max: dbConfig.pool.max,
      min: dbConfig.pool.min,
      acquire: dbConfig.pool.acquire,
      idle: dbConfig.pool.idle
    }
});

sequelize
  .authenticate()
  .then(() => {
    console.log('Connection has been established successfully.');
  })
  .catch(err => {
    console.error('Unable to connect to the database:', err);
  });

const db = {};

db.Sequelize = Sequelize;
db.sequelize = sequelize;
db.User = require("./user.model.js")(sequelize, Sequelize);
db.UserLogin = require("./userlogin.model")(sequelize, Sequelize);
db.OtpUser = require("./otpuser.model")(sequelize, Sequelize);
db.Category = require("./category.model")(sequelize, Sequelize);
db.Product = require("./product.model")(sequelize, Sequelize);
db.Location = require("./location.model")(sequelize, Sequelize);
db.ProductConnection = require("./product_connection.model")(sequelize, Sequelize);
db.Cart = require("./cart.model")(sequelize, Sequelize);
db.Society = require("./society.model")(sequelize, Sequelize);
db.Address = require("./address.model")(sequelize, Sequelize);
db.DeliveryConfig = require("./deliveryconfig.model")(sequelize, Sequelize);
db.Order = require("./order.model")(sequelize, Sequelize);
db.OrderHistory = require("./orderhistory.model")(sequelize, Sequelize);
db.ProductPrice = require("./productprice.model")(sequelize, Sequelize);
db.Inventory = require("./inventorie.model")(sequelize, Sequelize);
db.Plan = require("./plan.model")(sequelize, Sequelize);
db.Transaction = require("./transaction.model")(sequelize, Sequelize);

db.Banner = require("./banner.mode")(sequelize, Sequelize);
db.Setting = require("./setting.model")(sequelize, Sequelize);
db.Support = require("./support.model")(sequelize, Sequelize);
db.Review = require("./review.model")(sequelize, Sequelize);
db.Delivery = require("./delivery.model")(sequelize, Sequelize);
db.Notification = require("./notification.model")(sequelize, Sequelize);

db.Testimonial = require("./testimonial.model")(sequelize, Sequelize);
db.Recipe = require("./recipes.model")(sequelize, Sequelize);
db.Career = require("./career.model")(sequelize, Sequelize);
db.Partner = require("./partner.model")(sequelize, Sequelize);
db.Wishlist = require("./wishlist.model")(sequelize, Sequelize);
db.ReviewUser = require("./reviewuser.model")(sequelize, Sequelize);
db.Collaborate = require("./collaborate.model")(sequelize, Sequelize);
db.Faq = require("./faq.model")(sequelize, Sequelize);















module.exports = db;
