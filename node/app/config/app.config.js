const adminBaseUrl = 'http://139.59.67.166/katlego_admin/';

module.exports = {
   customer_user:2,
   astrologer_user:3,
   jwtsecret: 'nodejsappkatlegoappslurewebsolutionsllp',
   refreshJwtsecret: 'nodejsappRefereshkatlegoappslurellpwebslutionharry',
   // jwtExpiration:86400*30,
   // refreshJwtExpiration:(86400*60),
  // jwtExpiration:86400*30, /**in seconds */
   // jwtExpiration:60*15, /**in seconds */
   jwtExpiration:86400*30, /**in seconds */

   refreshJwtExpiration:86400*31,
   adminBaseUrl:adminBaseUrl,
   googleapikey:'AIzaSyD7BIoSvmyufubmdVEdlb2sTr4waQUexHQ',
   imagePaths:{
      user:adminBaseUrl+'public/uploads/user/',
      category:adminBaseUrl+'public/uploads/categories/',
      product:adminBaseUrl+'public/uploads/products/',
      banner:adminBaseUrl+'public/uploads/banners/',
      testimonial:adminBaseUrl+'public/uploads/testimonials/',
      recipe:adminBaseUrl+'public/uploads/recipes/',
      partner:adminBaseUrl+'public/uploads/partners/',
   },
   php_apis : {
      remove_stock_from_location:adminBaseUrl+'api/customer/remove_stock_from_location',
      add_stock_from_location:adminBaseUrl+'api/customer/add_stock_from_location'
   },

   aws:{
      aws_access_key_id :'AKIAWKL6GWKSULRK3RVW',
      aws_secret_access_key :'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',
   }
};
