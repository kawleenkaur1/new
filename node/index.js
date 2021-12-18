/**import packages */
process.env.TZ = 'Asia/Calcutta'
const express=require('express');
const bodyParser=require('body-parser');
const {  ValidationError  } = require('express-validation')
const http = require('http')
// var multer = require('multer');
// var upload = multer();


const moment = require('moment');


const cors=require('cors');

/**intialize express server app */
const app=express();

var server = http.createServer(app);

var io = require('socket.io')(server);

//console.log('CURRENT',moment().format('YYYY-MM-DD HH:mm:ss'));

app.use(cors());

/**create again table */
//db.sequelize.sync();
// db.sequelize.sync({ force: true }).then(() => {
//     //console.log("Drop and re-sync db.");
// });

/**parse requests as application/json */
app.use(bodyParser.json());

// parse requests of content-type - application/x-www-form-urlencoded
app.use(bodyParser.urlencoded({extended:true}));


/**validation error */
app.use(function(err, req, res, next) {

    if (err instanceof ValidationError) {
    return res.status(err.statusCode).json(err)
    // return res.json({status:false,message:err.message})

    }

    return res.status(500).json(err)
})


// process.on('unhandledRejection', function(e) {
//    //console.log(e);
//    process. exit();
// });


function postTrimmer(req, res, next) {
    if (req.method === 'POST') {
        for (const [key, value] of Object.entries(req.body)) {
            req.body[key] = Number.isNaN() ? value.trim() : value;
        }
    }
    next();
}

app.use(postTrimmer);


// simple route
app.get("/", (req, res) => {
    res.json({ 'headers' : req.headers,message: "Welcome to Katlego application." });
});


app.use(function(req, res, next){
    res.io = io;
    // //console.log('io',io);
    next();
});
require("./app/socket/socket")(io);

require("./app/routes/main.routes")(app,io);



/**set port, listen requests */
const PORT=3030;
server.listen(PORT,()=>{
    console.log(`Server is running on port ${PORT}.`);
})
