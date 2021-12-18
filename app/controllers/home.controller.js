const { katlego_welcome_mail } = require("../helpers/mailer.helper");
const { send_fcm_push_deliveryboy } = require("../helpers/notification.helper");
const { successRes, failedRes } = require("../helpers/response.helper");
const { get_location_id, currentTimeStamp } = require("../helpers/user.helper");
const { Category,Product, Location,sequelize, Cart, Banner, Setting, Support, Notification, Testimonial, Recipe, Career, Partner, Collaborate, Faq } = require("../models");
const { check_if_product_cart } = require("./cart.controller");
const { fetch_product_price } = require("./product.controller");

const fetch_navbar_categories = async (req,res) => {
  const categories = await Category.scope(['active','show_on_homepage_top','orderAsc']).findAll({limit:5});
  res.json(categories ? successRes('',categories) : failedRes('failed'))
}

const fetch_homepage_web = async (req,res) => {
    var {user_id}=req.body;
    const location_id = await get_location_id(req);
    if(!user_id){
      user_id=0;
  }
    const categories = await Category.scope(['active','show_on_homepage_top','orderAsc']).findAll();
    const best_sellers = await Product.scope(['best_sellers','active']).findAll({
      limit:8,
      order:[['position','asc']]
    }).then(async (produs)=>{
      for (let product of produs) {
          const cartdata = await check_if_product_cart(user_id,product.id);
          product.dataValues.is_cart = cartdata?1:0
          product.dataValues.cartdata = cartdata;
          if(location_id){
            const price_conf = await fetch_product_price(product.id,location_id);
            if(price_conf){
                product.dataValues.mrp =price_conf.mrp ;
                product.dataValues.selling_price =price_conf.selling_price ;
                product.dataValues.discount =price_conf.discount ;
                product.dataValues.discount_type =price_conf.discount_type ;
                product.dataValues.location_id =price_conf.location_id ;
                product.dataValues.stock =price_conf.stock ;
            }
          }
      }
      return await produs;
  });
    const combos = await Product.scope(['combos','active']).findAll().then(async (produs)=>{
      for (let product of produs) {
          const cartdata = await check_if_product_cart(user_id,product.id);
          product.dataValues.is_cart = cartdata?1:0
          product.dataValues.cartdata = cartdata

          if(location_id){
            const price_conf = await fetch_product_price(product.id,location_id);
            if(price_conf){
                product.dataValues.mrp =price_conf.mrp ;
                product.dataValues.selling_price =price_conf.selling_price ;
                product.dataValues.discount =price_conf.discount ;
                product.dataValues.discount_type =price_conf.discount_type ;
                product.dataValues.location_id =price_conf.location_id ;
                product.dataValues.stock =price_conf.stock ;
            }
          }
      }
      return await produs;
  });

  const hotselling = await Product.scope(['hotselling','active']).findAll({
    limit:8,
    order:[['position','asc']]
  }).then(async (produs)=>{
    for (let product of produs) {
        const cartdata = await check_if_product_cart(user_id,product.id);
        product.dataValues.is_cart = cartdata?1:0
        product.dataValues.cartdata = cartdata;
        if(location_id){
          const price_conf = await fetch_product_price(product.id,location_id);
          if(price_conf){
              product.dataValues.mrp =price_conf.mrp ;
              product.dataValues.selling_price =price_conf.selling_price ;
              product.dataValues.discount =price_conf.discount ;
              product.dataValues.discount_type =price_conf.discount_type ;
              product.dataValues.location_id =price_conf.location_id ;
              product.dataValues.stock =price_conf.stock ;
          }
        }
    }
    return await produs;
});

  const top_banners = await Banner.scope(['website','active']).findAll({
    order:[['position','asc']]
  });

    res.json({
        status:true,
        message:'fetched!',
        banners:top_banners,
        categories:categories,
        best_sellers:best_sellers,
        hotselling:hotselling,
        combos:combos
    })
}


const fetch_showcase_category = async (req,res) => {
  var {user_id}=req.body;
  if(!user_id){
    user_id=0;
  }
  const categories = await Category.scope(['active','show_on_homepage_top','orderAsc']).findAll();
  res.json(successRes('',categories));
}



const fetch_showcase_products = async (req,res) => {
  var {user_id}=req.body;
  const location_id = await get_location_id(req);
    if(!user_id){
      user_id=0;
  }
  const best_sellers = await Product.scope(['best_sellers','active']).findAll({
    limit:5
  }).then(async (produs)=>{
      for (let product of produs) {
          const cartdata = await check_if_product_cart(user_id,product.id);
          product.dataValues.is_cart = cartdata?1:0
          product.dataValues.cartdata = cartdata

          const price_conf = await fetch_product_price(product.id,location_id)
          if(price_conf){
              product.dataValues.selling_price =price_conf.selling_price ;
              product.dataValues.discount =price_conf.discount ;
              product.dataValues.discount_type =price_conf.discount_type ;
              product.dataValues.location_id =price_conf.location_id ;
              product.dataValues.stock =price_conf.stock ;
          }
      }
      return await produs;
  });
  res.json(successRes('',best_sellers));
}

const fetch_navbar_category_product = async (req,res) => {
  const categories = await Category.scope(['active','orderAsc']).findAll({
    limit:4
  })
  .then(async (cts)=>{
    for (let ct of cts) {
      var query = `SELECT pd.* FROM products as pd INNER JOIN product_connections pc ON pd.id=pc.product_id WHERE pc.category_id=${ct.id} AND pd.status=1  ORDER BY pd.name ASC LIMIT 4`;

      const products = await sequelize.query(query, {
          model: Product,
          mapToModel: true // pass true here if you have any mapped fields
      })
      ct.dataValues.products = products
    }
    return await cts
  })

  res.json(successRes('',categories))
}

const get_products_by_filters = async (req,res) => {
  const {sort_by,user_id,category_id} = req.body;
  const location_id = await get_location_id(req);
  var query = '';
  if(sort_by==='cost_high_to_low'){
    query = `SELECT pd.* FROM products as pd INNER JOIN product_connections pc ON pd.id=pc.product_id INNER JOIN product_prices as ppr ON ppr.product_id = pd.id WHERE pd.status=1 AND pc.category_id=${category_id} AND ppr.location_id = ${location_id} ORDER BY pd.selling_price DESC`;
  }else if(sort_by==='cost_low_to_high'){
    query = `SELECT pd.* FROM products as pd INNER JOIN product_connections pc ON pd.id=pc.product_id INNER JOIN product_prices as ppr ON ppr.product_id = pd.id WHERE pd.status=1 AND pc.category_id=${category_id} AND ppr.location_id = ${location_id} ORDER BY pd.selling_price ASC`;

  }

  const products = await sequelize.query(query, {
    model: Product,
    mapToModel: true // pass true here if you have any mapped fields
    }).then(async (produs)=>{
        for (let product of produs) {
            const cartdata =  await Cart.findOne({
                where:{
                    user_id:user_id,
                    product_id:product.id
                }
            }); 
            //check_if_product_cart(user_id,product.id);
            product.dataValues.is_cart = cartdata?1:0
            product.dataValues.cartdata = cartdata
        }
        return await produs;
    })

    if(products){
      res.json(successRes('fetched',products))
    }else{
      res.json(failedRes('no records found',products))
    }
}

const get_settings =async (req,res) => {
  const set = await Setting.findOne();
  if(set){
    res.json(successRes('',set))
  }else{
    res.json(failedRes(''))
  }
}

const get_notifications = async (req,res) => {
  const {user_id} = req.body;
  const data = await Notification.findAll({
    where:{
      user_id:user_id
    },
    order:[['id','desc']]
  })

  res.json(data?successRes('',data):failedRes('',data))
}

const add_support_data = async (req,res) => {
  const {user_id,name,email,phone,message,subject} = req.body
  const dateTime = currentTimeStamp()
  const stordata = await Support.create({
    user_id,name,phone,email,message,subject,created_at:dateTime,updated_at:dateTime
  })
  res.json(stordata?successRes('created',stordata):failedRes('failed'))
}

const add_collaborate_data = async (req,res) => {
  const {user_id,name,email,phone,message,city,state,type} = req.body
  const dateTime = currentTimeStamp()
  const stordata = await Collaborate.create({
    user_id,name,phone,email,message,created_at:dateTime,updated_at:dateTime,city,state,type
  })
  res.json(stordata?successRes('created',stordata):failedRes('failed'))
}

const add_career_data = async (req,res) => {
  const {user_id,name,email,phone,message,subject} = req.body
  const dateTime = currentTimeStamp()
  const stordata = await Career.create({
    user_id,name,phone,email,message,subject,created_at:dateTime,updated_at:dateTime
  })
  res.json(stordata?successRes('created',stordata):failedRes('failed'))
}

const get_testimonials = async (req,res) => {
  const data = await Testimonial.scope(['active','orderAsc']).findAll();
  res.json(data?successRes('',data):failedRes(''));
}

const get_recipes = async (req,res) => {
  const data = await Recipe.scope(['active','orderAsc']).findAll();
  res.json(data?successRes('',data):failedRes(''));

}

const send_test_notification = async (req,res) => {
  send_fcm_push_deliveryboy('dnHK7VoST96cz1ie18Tvyu:APA91bG9uKqYZjWh1tsJWkh8LtDdoHHTgCd1H7XbKrhfudoG26oro5iRpHRh41TOHEv2g7NjJ9G3JZzcwfYd8Y1sj0WsGWC6iZhOrYaGOHb-qE2utLtW733Q4TLczqMC-RoJdLWdVJeC','test','test','high')

  res.json(successRes('send'))
}

const fetch_press_release = async (req,res) => {
  const data = await Partner.findOne({
    where:{
      type:1
    }
  })

  res.json(data?successRes('',data):failedRes(''))
}


const fetch_partners = async (req,res) => {
  const data = await Partner.findOne({
    where:{
      type:2
    }
  })

  res.json(data?successRes('',data):failedRes(''))
}


const send_mail_test = async (req,res) => {
  katlego_welcome_mail('hssaggu567@gmail.com',6)
  res.json(successRes(''))
}

const fetch_faqs = async (req,res) => {
  const data = await Faq.scope(['active','orderAsc']).findAll();
  data ? res.json(successRes('',data)) : res.json(failedRes('failed'));
}
module.exports={
  fetch_homepage_web,
  fetch_showcase_category,
  fetch_showcase_products,
  fetch_navbar_category_product,
  get_products_by_filters,
  get_settings,
  add_support_data,
  fetch_navbar_categories,
  get_notifications,
  get_testimonials,
  get_recipes,
  send_test_notification,
  add_career_data,
  fetch_press_release,
  fetch_partners,
  send_mail_test,
  add_collaborate_data,
  fetch_faqs
}
