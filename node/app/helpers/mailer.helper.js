var nodemailer = require('nodemailer');
const { User } = require('../models');


// const host = {
//     username : 'docalllive@gmail.com',
//     password:'Docall@Mehar'
// }


const host = {
    username : 'developerkatlego2@gmail.com',
    password:'Katlego@1234'
}


var transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
      user: host.username,
      pass: host.password
    }
});


const global_send_mail = async (toEmail,subject,message) => {

    var mailOptions = {
        from: 'KATLEGO',
        to: toEmail,
        subject: subject,
        html: message
      };
      transporter.sendMail(mailOptions, function(error, info){
        if (error) {
          console.log(error);
        } else {
          console.log('Email sent: ' + info.response);
        }
      });
}


const katlego_welcome_mail =async (email,user_id) => {
    const user = await User.findOne({
        where:{
            id:user_id
        }
    })

    const title = `KATLEGO WELCOME`
    const message = `
    <table style="width:100%;max-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    <tbody><tr>
                   <td bgcolor="#F4F4F4" align="center">
                 
                        <table class="row" style="width:600px;max-width:600px;" width="600" cellspacing="0" cellpadding="0" border="0" align="center">
                            <tbody><tr><td bgcolor="#ffffff" align="center">
                                
                                <table class="row" style="width:540px;max-width:540px;" width="540" cellspacing="0" cellpadding="0" border="0" align="center">
                                <tbody><tr><td class="container-padding" align="center">
                                    
                                  
                                    <table width="540" border="0" cellpadding="0" cellspacing="0" align="center" class="row" style="width:540px;max-width:540px;">
                                        <tbody><tr><td align="center">
                                            
                                          <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="width:100%; max-width:100%;">
                                              <tbody><tr><td height="40">&nbsp;</td></tr> 
                                              <tr><td>
                                                  
                                     <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                                     <tbody>
                                        <tr>
                                         <td width="100" align="left">
                                          <img align="left" width="100" style="display:block;width:100%;max-width:100px;" alt="img" src="http://katlego.in/logo.png">
                                         </td>
                                         <td width="30">&nbsp;</td>
                                         
                                         </tr>
                                     </tbody>
                                 </table>
                                    
                                                  </td></tr> 
                                              <tr><td height="40">&nbsp;</td></tr> 
                                         <tr><td align="center"><img width="100" style="display:block;width:100%;max-width:100px;" alt="img" src="http://139.59.67.166/katlego_admin/public/uploads/invoices/pp.png"></td></tr>
                                         <tr><td height="20">&nbsp;</td></tr>
                                 <tr><td align="center" style="font-family:'Josefin Sans', Arial, Helvetica, sans-serif;font-size: 30px;color: #282828;">Hi ${user.name.toUpperCase()}, Your account has been confirmed</td></tr>
                                      
                                <tr><td height="18">&nbsp;</td></tr>
                                 <tr><td align="center" style="font-family:'Open Sans', Arial, Helvetica, sans-serif;font-size: 14px;color: #282828;line-height: 22px">
    You've activated your customer account. Next time you shop with us, log in for faster checkout. </td></tr>
                                        
                                              <tr><td height="20">&nbsp;</td></tr>
                                 <tr><td align="center">
                                     
                                                  <table height="30" border="0" bgcolor="#22003f" cellpadding="0" cellspacing="0"><tbody><tr>
                               
                                <td align="center" height="50" width="220" style="font-family:'Open Sans', Arial, Helvetica, sans-serif;font-size: 13px;color: #ffffff;font-weight: 600;letter-spacing: 0.5px;">
                                
                                
                                    <a href="http://katlego.in" target="_blank" style="color: #ffffff">Order Now</a>
                                </td>
                               
                                </tr></tbody></table>
                                     
                                     </td></tr>
                                              <tr><td height="18">&nbsp;</td></tr>
                                              <tr><td align="center" style="font-family:'Open Sans', Arial, Helvetica, sans-serif;font-size: 14px;color: #282828;">If you have any question. Please feel free to contact us.</td></tr>
                                              <tr><td height="40">&nbsp;</td></tr> 
                                            </tbody></table>
                                            
                                            </td></tr>
                                    </tbody></table>
                                    
                                  
                                    
                                    </td></tr>
                                </tbody></table>
                                
                                </td></tr>
                   </tbody></table>
                 
                 </td>
                   </tr>
            </tbody></table>
    
    
            <table style="width:100%;max-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    <tbody><tr>
                   <td bgcolor="#F4F4F4" align="center">
                 
                        <table class="row" style="width:600px;max-width:600px;" width="600" cellspacing="0" cellpadding="0" border="0" align="center">
                            <tbody><tr><td bgcolor="#22003f" align="center">

                                <table class="row" style="width:540px;max-width:540px;" width="540" cellspacing="0" cellpadding="0" border="0" align="center">
                                <tbody><tr><td class="container-padding" align="center">
                                    

                                    <table width="540" border="0" cellpadding="0" cellspacing="0" align="center" class="row" style="width:540px;max-width:540px;">
                                        <tbody><tr><td align="center">
                                            
                                          <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="width:100%; max-width:100%;">
                                              <tbody><tr><td height="40">&nbsp;</td></tr> 
                                         
                                  <tr><td align="center" style="font-family:'Josefin Sans', Arial, Helvetica, sans-serif;font-size: 18px;color: #dadada;font-weight: 400;">Get in Touch</td></tr>
                               
                                            <tr><td height="20">&nbsp;</td></tr>
                                               <tr><td>
                                                  
                                                   <table cellspacing="0" cellpadding="0" border="0" align="center">
                                                  <tbody>
                                                    <tr>
                                                      <td width="25">
                                                      <a href="#"><img width="25" style="display:block;width:100%;max-width:25px;" src="http://139.59.67.166/katlego_admin/public/uploads/invoices/fb.png"></a>
                                                      </td>
                                                      <td width="10">&nbsp;</td>
                                                      <td width="25">
                                                      <a href="#"><img width="25" style="display:block;width:100%;max-width:25px;" src="http://139.59.67.166/katlego_admin/public/uploads/invoices/in.png"></a>
                                                      </td>
                                                      <td width="10">&nbsp;</td>
                                                      <td width="25">
                                                      <a href="#" target="_blank"><img width="25" style="display:block;width:100%;max-width:25px;" src="http://139.59.67.166/katlego_admin/public/uploads/invoices/tw.png"></a>
                                                      </td>
                                                      <td width="10">&nbsp;</td>
                                                      
                                                      </tr>
                                                  </tbody>
                                              </table>
                                                  
                                                  </td></tr>
                                            <tr><td height="20">&nbsp;</td></tr>
                                            <tr><td align="center" style="font-family:'Roboto', Arial, Helvetica, sans-serif;font-size: 13px;color: #dadada;line-height: 19px;">You are getting this email as a registered user of katlego.<br>
    This is auto genrated email. Please don't reply.<br> Please connect with us at <a href="mailto:support@katlego.in" style="color:#1a9c49;">support@katlego.in</a> for any concerns.
    
    
     </td></tr>
                                              <tr><td>&nbsp;</td></tr>
                                              <tr><td align="center">
                                                  
                                                   <table cellspacing="0" cellpadding="0" border="0">
                                                  <tbody><tr>
                                                      <td align="center" style="font-family:'Roboto', Arial, Helvetica, sans-serif;font-size: 13px;color: #dadada;line-height: 20px;text-decoration: underline"><a href="http://katlego.in/terms-and-conditions" target="_blank" style="color: #dadada">Terms &amp; Condition</a></td>
                                                      <td width="20" align="center" style="font-family:'Roboto', Arial, Helvetica, sans-serif;font-size: 13px;color: #dadada;line-height: 20px;">|</td>
                                                      <td align="center" style="font-family:'Roboto', Arial, Helvetica, sans-serif;font-size: 13px;color: #dadada;line-height: 20px;text-decoration: underline"><a href="http://katlego.in/privacy-policy" target="_blank" style="color: #dadada">Privacy Policy</a></td>
                                                     
                                                      
                                                      </tr>
                                                  </tbody></table>
                                                  
                                                  </td></tr>
                                              <tr><td height="40">&nbsp;</td></tr> 
                                            </tbody></table>
                                            
                                            </td></tr>
                                    </tbody></table>
                                    
                                  
                                    
                                    </td></tr>
                                </tbody></table>
                                
                                </td></tr>
                   </tbody>
               </table>
                 
                 </td>
                   </tr>
            </tbody>
        </table>
    
    `;

    global_send_mail(email,title,message)
}


const order_generate_mail = async (req,res) => {


    const user = await User.findOne({
        where:{
            id:user_id
        }
    })

    const title = `KATLEGO WELCOME`
    const message = `
    <table style="width:100%;max-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    <tbody><tr>
                   <td bgcolor="#F4F4F4" align="center">
                 
                        <table class="row" style="width:600px;max-width:600px;" width="600" cellspacing="0" cellpadding="0" border="0" align="center">
                            <tbody><tr><td bgcolor="#ffffff" align="center">
                                
                                <table class="row" style="width:540px;max-width:540px;" width="540" cellspacing="0" cellpadding="0" border="0" align="center">
                                <tbody><tr><td class="container-padding" align="center">
                                    
                                  
                                    <table width="540" border="0" cellpadding="0" cellspacing="0" align="center" class="row" style="width:540px;max-width:540px;">
                                        <tbody><tr><td align="center">
                                            
                                          <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="width:100%; max-width:100%;">
                                              <tbody><tr><td height="40">&nbsp;</td></tr> 
                                              <tr><td>
                                                  
                                     <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                                     <tbody>
                                        <tr>
                                         <td width="100" align="left">
                                          <img align="left" width="100" style="display:block;width:100%;max-width:100px;" alt="img" src="http://katlego.in/logo.png">
                                         </td>
                                         <td width="30">&nbsp;</td>
                                         
                                         </tr>
                                     </tbody>
                                 </table>
                                    
                                                  </td></tr> 
                                              <tr><td height="40">&nbsp;</td></tr> 
                                         <tr><td align="center"><img width="100" style="display:block;width:100%;max-width:100px;" alt="img" src="http://139.59.67.166/katlego_admin/public/uploads/invoices/pp.png"></td></tr>
                                         <tr><td height="20">&nbsp;</td></tr>
                                 <tr><td align="center" style="font-family:'Josefin Sans', Arial, Helvetica, sans-serif;font-size: 30px;color: #282828;">Hi ${user.name.toUpperCase()}, Your account has been confirmed</td></tr>
                                      
                                <tr><td height="18">&nbsp;</td></tr>
                                 <tr><td align="center" style="font-family:'Open Sans', Arial, Helvetica, sans-serif;font-size: 14px;color: #282828;line-height: 22px">
    You've activated your customer account. Next time you shop with us, log in for faster checkout. </td></tr>
                                        
                                              <tr><td height="20">&nbsp;</td></tr>
                                 <tr><td align="center">
                                     
                                                  <table height="30" border="0" bgcolor="#22003f" cellpadding="0" cellspacing="0"><tbody><tr>
                               
                                <td align="center" height="50" width="220" style="font-family:'Open Sans', Arial, Helvetica, sans-serif;font-size: 13px;color: #ffffff;font-weight: 600;letter-spacing: 0.5px;">
                                
                                
                                    <a href="http://katlego.in" target="_blank" style="color: #ffffff">Order Now</a>
                                </td>
                               
                                </tr></tbody></table>
                                     
                                     </td></tr>
                                              <tr><td height="18">&nbsp;</td></tr>
                                              <tr><td align="center" style="font-family:'Open Sans', Arial, Helvetica, sans-serif;font-size: 14px;color: #282828;">If you have any question. Please feel free to contact us.</td></tr>
                                              <tr><td height="40">&nbsp;</td></tr> 
                                            </tbody></table>
                                            
                                            </td></tr>
                                    </tbody></table>
                                    
                                  
                                    
                                    </td></tr>
                                </tbody></table>
                                
                                </td></tr>
                   </tbody></table>
                 
                 </td>
                   </tr>
            </tbody></table>
    
    
            <table style="width:100%;max-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    <tbody><tr>
                   <td bgcolor="#F4F4F4" align="center">
                 
                        <table class="row" style="width:600px;max-width:600px;" width="600" cellspacing="0" cellpadding="0" border="0" align="center">
                            <tbody><tr><td bgcolor="#22003f" align="center">

                                <table class="row" style="width:540px;max-width:540px;" width="540" cellspacing="0" cellpadding="0" border="0" align="center">
                                <tbody><tr><td class="container-padding" align="center">
                                    

                                    <table width="540" border="0" cellpadding="0" cellspacing="0" align="center" class="row" style="width:540px;max-width:540px;">
                                        <tbody><tr><td align="center">
                                            
                                          <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="width:100%; max-width:100%;">
                                              <tbody><tr><td height="40">&nbsp;</td></tr> 
                                         
                                  <tr><td align="center" style="font-family:'Josefin Sans', Arial, Helvetica, sans-serif;font-size: 18px;color: #dadada;font-weight: 400;">Get in Touch</td></tr>
                               
                                            <tr><td height="20">&nbsp;</td></tr>
                                               <tr><td>
                                                  
                                                   <table cellspacing="0" cellpadding="0" border="0" align="center">
                                                  <tbody>
                                                    <tr>
                                                      <td width="25">
                                                      <a href="#"><img width="25" style="display:block;width:100%;max-width:25px;" src="http://139.59.67.166/katlego_admin/public/uploads/invoices/fb.png"></a>
                                                      </td>
                                                      <td width="10">&nbsp;</td>
                                                      <td width="25">
                                                      <a href="#"><img width="25" style="display:block;width:100%;max-width:25px;" src="http://139.59.67.166/katlego_admin/public/uploads/invoices/in.png"></a>
                                                      </td>
                                                      <td width="10">&nbsp;</td>
                                                      <td width="25">
                                                      <a href="#" target="_blank"><img width="25" style="display:block;width:100%;max-width:25px;" src="http://139.59.67.166/katlego_admin/public/uploads/invoices/tw.png"></a>
                                                      </td>
                                                      <td width="10">&nbsp;</td>
                                                      
                                                      </tr>
                                                  </tbody>
                                              </table>
                                                  
                                                  </td></tr>
                                            <tr><td height="20">&nbsp;</td></tr>
                                            <tr><td align="center" style="font-family:'Roboto', Arial, Helvetica, sans-serif;font-size: 13px;color: #dadada;line-height: 19px;">You are getting this email as a registered user of katlego.<br>
    This is auto genrated email. Please don't reply.<br> Please connect with us at <a href="mailto:support@katlego.in" style="color:#1a9c49;">support@katlego.in</a> for any concerns.
    
    
     </td></tr>
                                              <tr><td>&nbsp;</td></tr>
                                              <tr><td align="center">
                                                  
                                                   <table cellspacing="0" cellpadding="0" border="0">
                                                  <tbody><tr>
                                                      <td align="center" style="font-family:'Roboto', Arial, Helvetica, sans-serif;font-size: 13px;color: #dadada;line-height: 20px;text-decoration: underline"><a href="http://katlego.in/terms-and-conditions" target="_blank" style="color: #dadada">Terms &amp; Condition</a></td>
                                                      <td width="20" align="center" style="font-family:'Roboto', Arial, Helvetica, sans-serif;font-size: 13px;color: #dadada;line-height: 20px;">|</td>
                                                      <td align="center" style="font-family:'Roboto', Arial, Helvetica, sans-serif;font-size: 13px;color: #dadada;line-height: 20px;text-decoration: underline"><a href="http://katlego.in/privacy-policy" target="_blank" style="color: #dadada">Privacy Policy</a></td>
                                                     
                                                      
                                                      </tr>
                                                  </tbody></table>
                                                  
                                                  </td></tr>
                                              <tr><td height="40">&nbsp;</td></tr> 
                                            </tbody></table>
                                            
                                            </td></tr>
                                    </tbody></table>
                                    
                                  
                                    
                                    </td></tr>
                                </tbody></table>
                                
                                </td></tr>
                   </tbody>
               </table>
                 
                 </td>
                   </tr>
            </tbody>
        </table>
    
    `;

    global_send_mail(email,title,message)
}


const resend_otp_mail = (email,name,otp) => {

    const title = `KATLEGO Verification`
    const message = `
    <!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        @media screen {
            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 400;
                src: local('Lato Regular'), local('Lato-Regular'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 700;
                src: local('Lato Bold'), local('Lato-Bold'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: italic;
                font-weight: 400;
                src: local('Lato Italic'), local('Lato-Italic'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: italic;
                font-weight: 700;
                src: local('Lato Bold Italic'), local('Lato-BoldItalic'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format('woff');
            }
        }

        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        @media screen and (max-width:600px) {
            h1 {
                font-size: 32px !important;
                line-height: 32px !important;
            }
        }

        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>

<body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
    <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: 'Lato', Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;"> We're thrilled to have you here! Get ready to dive into your new account. </div>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td bgcolor="#FFA73B" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td align="center" valign="top" style="padding: 40px 10px 40px 10px;"> </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#FFA73B" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                            <h1 style="font-size: 48px; font-weight: 400; margin: 2;">Hi ${name.toUpperCase()}!</h1> <img src=" https://img.icons8.com/clouds/100/000000/handshake.png" width="125" height="120" style="display: block; border: 0px;" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">Your verification code is <b>${otp}</b>.</p>
                        </td>
                    </tr>

                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">Please do not share this OTP with anyone else.</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">Thanks,<br>KATLEGO</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
</body>

</html>
    `;

    global_send_mail(email,title,message)
}


const send_mail_for_approval = async (host_id) => {
    const user = await User.findOne({
        where:{
            id:host_id
        }
    })
    const email = user.email;

    var game_str_arr = user.games_string.split('|');
    var approval = '';
    for (let i = 0; i < game_str_arr.length; i++) {
        const element = game_str_arr[i];
        if(element==='roulette'){
            approval += '<b>Roulette</b><br/>'
        }else if(element === 'sicbo'){
            approval += '<b>Dice</b><br/>'
        }else if(element === 'one_to_one_video'){
            approval += '<b>1-1 Video</b><br/>'
        }else if(element === 'one_to_one_audio'){
            approval += '<b>1-1 Audio</b><br/>'
        }else if(element === 'one_to_one_chat'){
            approval += '<b>1-1 Chat</b><br/>'
        }

    }

    const title = 'KATLEGO Account Approved';
    const message = `Hi ${user.name.toUpperCase()},<br/>
    We are very happy to inform you that your account has been approved.<br/>
    ${approval}


    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
    <td>
    <h3>Your Account Credentials</h3>
    </td>
    </tr>
    <tr>
        <td>
        <b>Username</b>
        </td>
        <td>${user.email} Or ${user.phone}</td>
    </tr>
    <tr>
        <td>
        <b>Password</b>
        </td>
        <td>${user.plain_password}</td>
    </tr>

    </table>
    `
    global_send_mail(email,title,message)

}
module.exports = {
    global_send_mail,
    katlego_welcome_mail
   
}
