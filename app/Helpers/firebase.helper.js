var admin = require("firebase-admin");

var serviceAccount = require("./firebase.json");

const FIREBASE_DATABASE_URL="https://shaktipeethapp-default-rtdb.firebaseio.com";

admin.initializeApp({
  credential: admin.credential.cert(serviceAccount),
  databaseURL: FIREBASE_DATABASE_URL
});


const firebaseAdmin = {};
firebaseAdmin.sendMulticastNotification = function(payload) {
    const message = {
        notification: {
            title: payload.title,
            body: payload.body
        },
        tokens: payload.tokens,
        data: payload.data || {}
    };
    return admin.messaging().sendMulticast(message);
};
module.exports = firebaseAdmin;