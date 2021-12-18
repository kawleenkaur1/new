process.env.TZ = "Asia/Calcutta";

// var app = require("express")();
// var http = require("http").createServer(app);
// var io = require("socket.io")(http);
var moment = require("moment");
const { jwtsecret } = require("../config/app.config");
const jwt = require('jsonwebtoken');
const { currentTimeStamp } = require("../helpers/user.helper");
const { new_order_function } = require("../controllers/deliveryboy/order.controller");
const { successRes } = require("../helpers/response.helper");

const get_user_id_by_token = async (token) => {
  let promise = new Promise(function (resolve, reject) {
    jwt.verify(token, jwtsecret, (err, decoded) => {
      if (err) {
        reject(0);
      } else {
        resolve(decoded.id);
      }
    });
  });

  return promise;
};

const get_json = async (ob) => {
    console.log('ob',ob);
  var data =ob;
  // if (typeof ob === 'string'){
  //      data = JSON.parse(ob)
  //   }
  // if (data.token) {
  //   data.user_id = await get_user_id_by_token(data.token);
  //   data.host_id = await get_user_id_by_token(data.token);
  // }
  data.host_id=data.user_id;
  return data;
};


module.exports = (io) => {
  io.on("connection", (socket) => {
    io.send("User connected");
    console.log('User Connected');




    socket.on('new_orders',async (req)=>{
      const {user_id} = req;
      const data = await new_order_function(user_id)
      socket.join(user_id)
      io.sockets.in(user_id).emit('new_orders',successRes('',data))
    })


  });
}



// const PORT = 4000;

// http.listen(PORT, () => {
//   console.log(`listening on *:${PORT}`);
// });
