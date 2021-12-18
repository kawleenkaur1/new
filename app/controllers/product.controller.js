
const { Op } = require("sequelize");
const { successRes, failedRes } = require("../helpers/response.helper");
const { get_location_id, currentTimeStamp } = require("../helpers/user.helper");
const { Category,Product, Location, ProductConnection, sequelize, ProductPrice, Cart, Inventory, Review, Wishlist } = require("../models");
// const { check_if_product_cart } = require("./cart.controller");

const fetch_product_price = async (id,location_id) => {
    const data = await ProductPrice.findOne({
        where:{
            product_id:id,
            location_id
        }
    })
    return data
}
const fetch_product_details = async (req,res) => {
    var {user_id,id} = req.body
    if(!user_id){
        user_id=0;
    }
    const location_id = await get_location_id(req);

    var product = await Product.findOne({
        where:{
            id:id
        }
    });

    const price_conf = await fetch_product_price(id,location_id)

    if(product){
        // const cartdata = await check_if_product_cart(user_id,id);
        const cartdata =  await Cart.findOne({
            where:{
                user_id:user_id,
                product_id:product.id
            }
        }); 
        product.dataValues.is_cart = cartdata?1:0
        product.dataValues.cartdata = cartdata;
        product.dataValues.rating = product.rating ? JSON.parse(product.rating) : null;
        if(price_conf){
            product.dataValues.selling_price =price_conf.selling_price ;
            product.dataValues.discount =price_conf.discount ;
            product.dataValues.discount_type =price_conf.discount_type ;
            product.dataValues.location_id =price_conf.location_id ;
            product.dataValues.stock =price_conf.stock ;
        }
      


        res.json(successRes('fetched',product))
    }else{
        res.json(failedRes('failed',product))
    }
}

const get_product_stock =async (product_id,location_id) => {
    //const stock
    const total_earning = await Inventory.scope(['in_stock']).findOne({
        where:{product_id:product_id},
        attributes: [[sequelize.fn('sum', sequelize.col('stock')), 'total_stock']],
    });
}


const fetch_products_by_category = async (req,res) => {
    var {category_id,user_id} = req.body;
    if(!user_id){
        user_id=0;
    }
    const location_id = await get_location_id(req);
    // var query = `SELECT pd.*,ppr.mrp,ppr.selling_price,ppr.subscription_price,ppr.discount_type,ppr.discount FROM products as pd INNER JOIN product_connections pc ON pd.id=pc.product_id INNER JOIN product_prices as ppr ON ppr.product_id = pd.id WHERE pc.category_id=${category_id} AND pd.status=1 AND ppr.location_id= ${location_id} ORDER BY pd.name ASC `;
    var query = `SELECT pd.* FROM products as pd INNER JOIN product_connections pc ON pd.id=pc.product_id WHERE pc.category_id=${category_id} AND pd.status=1 ORDER BY pd.name ASC `;

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
            product.dataValues.cartdata = cartdata;
            product.dataValues.rating = product.rating ? JSON.parse(product.rating) : null;
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
    })

    if(products){
        res.json(successRes('fetched',products))
    }else{
        res.json(failedRes('no records found',products))
    }
}


const averageRatingProduct =async (id) =>
{
    try {
        const rt = await Review.findOne({
            where:{product_id:id},
            attributes: [[sequelize.fn('avg', sequelize.col('rate')), 'average'],[sequelize.fn('count', sequelize.col('id')), 'total_reviews']],
        });
        return rt;
    } catch (error) {
        return false;
    }
}

const you_may_like = async (req,res) => {
    const {user_id,not_id} = req.body;
    const location_id = await get_location_id(req);

    // pd.id != ${not_id} AND 
    // var query = `SELECT pd.*,ppr.mrp,ppr.selling_price,ppr.subscription_price,ppr.discount_type,ppr.discount FROM products as pd INNER JOIN product_prices as ppr ON ppr.product_id = pd.id WHERE pd.status=1 AND ppr.location_id= ${location_id} AND pd.id <> ${not_id} ORDER BY pd.name ASC LIMIT 4`;
    var query = `SELECT pd.* FROM products as pd INNER JOIN product_prices as ppr ON ppr.product_id = pd.id WHERE pd.status=1 AND ppr.location_id= ${location_id} AND pd.id <> ${not_id} ORDER BY pd.name ASC LIMIT 4`;

    const products = await Product.findAll({
        where:{
            id:{
                [Op.ne]:[not_id]
            }
        },
        order:[['name','asc']],
        limit:10
    }).then(async (produs)=>{
        for (let product of produs) {
            // const cartdata = await check_if_product_cart(user_id,product.id);
            const cartdata =  await Cart.findOne({
                where:{
                    user_id:user_id,
                    product_id:product.id
                }
            }); 
            product.dataValues.is_cart = cartdata?1:0
            product.dataValues.cartdata = cartdata;
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
    })

    if(products){
        res.json(successRes('fetched',products))
    }else{
        res.json(failedRes('no records found',products))
    }

    products ? res.json(successRes('fetched',products)) :  res.json(failedRes('failed',products))
}


// const you_may_like_all = async (req,res) => {
//     const {user_id} = req.body;
//     const categories = await Category.scope(['active','show_on_homepage_top','orderAsc']).findAll({
//         limit:10
//     }).then(async (categories) => {
//         for (let cat of categories) {

//             var query = `SELECT pd.* FROM products as pd INNER JOIN product_connections pc ON pd.id=pc.product_id WHERE pc.category_id=${cat.id} AND pd.status=1 ORDER BY pd.name ASC LIMIT 4`;
//             const products = await sequelize.query(query, {
//                 model: Product,
//                 mapToModel: true // pass true here if you have any mapped fields
//             }).then(async (produs)=>{
//                 for (let product of produs) {
//                     const cartdata = await check_if_product_cart(user_id,product.id);
//                     product.dataValues.is_cart = cartdata?1:0
//                     product.dataValues.cartdata = cartdata
//                 }
//                 return await produs;
//             })
//             cat.dataValues.products = products
//         }
//         return await categories
//     })

//     categories ? res.json(successRes('fetched',categories)) :  res.json(failedRes('failed'.categories))
// }


const search_product = async (req,res) => {
    const {user_id,keyword} = req.body;
    if(keyword){
        const products = await Product.findAll({
            where:{
                name: {
                    [Op.like]: '%'+keyword+'%'
                  },
                  status:1
            }
        })
        res.json(successRes('',products))
    }
    else{
        res.json(failedRes(''))
    }
}

const add_to_wishlist = async (req,res) => {
    const {user_id,product_id} = req.body;
    const checkifadded = await Wishlist.findOne({
        where:{
            user_id:user_id,
            product_id:product_id
        }
    }) 
    if(checkifadded){
        res.json(failedRes('already added'))
    }else{
        const dateTime = currentTimeStamp();
        const storedata = {
            user_id,product_id,crated_At:dateTime,
            updated_at:dateTime,
        }
        Wishlist.create(storedata);
        res.json(successRes('added!'))
    }
}


const remove_to_wishlist = async (req,res) => {
    const {user_id,product_id} = req.body;
    const checkifadded = await Wishlist.findOne({
        where:{
            user_id:user_id,
            product_id:product_id
        }
    }) 
    if(checkifadded){
        Wishlist.destroy({
            where:{
                id:checkifadded.id
            }
        })
        res.json(successRes('success'))
        
    }else{
        res.json(failedRes('something went wrong'))
    }
}


const fetch_wishlists = async (req,res) => {
    const {user_id} = req.body;
    const location_id = await get_location_id(req);

    // pd.id != ${not_id} AND 
    // var query = `SELECT pd.*,ppr.mrp,ppr.selling_price,ppr.subscription_price,ppr.discount_type,ppr.discount FROM products as pd INNER JOIN product_prices as ppr ON ppr.product_id = pd.id WHERE pd.status=1 AND ppr.location_id= ${location_id} AND pd.id <> ${not_id} ORDER BY pd.name ASC LIMIT 4`;
    var query = `SELECT pd.* FROM products as pd INNER JOIN wishlists as ws ON ws.product_id = pd.id WHERE pd.status=1 AND ws.user_id=${user_id} ORDER BY pd.name ASC `;

    const products = await sequelize.query(query, {
        model: Product,
        mapToModel: true // pass true here if you have any mapped fields
    }).then(async (produs)=>{
        for (let product of produs) {
            // const cartdata = await check_if_product_cart(user_id,product.id);
            const cartdata =  await Cart.findOne({
                where:{
                    user_id:user_id,
                    product_id:product.id
                }
            }); 
            product.dataValues.is_cart = cartdata?1:0
            product.dataValues.cartdata = cartdata;
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
    })

    if(products){
        res.json(successRes('fetched',products))
    }else{
        res.json(failedRes('no records found',products))
    }

    products ? res.json(successRes('fetched',products)) :  res.json(failedRes('failed',products))
}


module.exports={
    fetch_product_details,
    fetch_products_by_category,
    you_may_like,
    fetch_product_price,
    search_product,
    add_to_wishlist,
    remove_to_wishlist,
    fetch_wishlists

}