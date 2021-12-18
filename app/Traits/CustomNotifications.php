<?php
namespace App\Traits;


trait CustomNotifications{

   public function create_order_notification($user,$order_id=0,$data=[])
   {
       $title="Hi ".$user->name.", Thanks for placing an order with ROZANA.";
       $body="Your order has been accepted, we will deliver the order shortly. Your order id is #".$order_id;
        $message=yt_notify_msg($title,$body,$data);

        yt_notification($message,$user->id);
   }

   public function cancel_order_by_admin_notification($user,$order,$data=[])
   {
       $title="Order Cancelled!!";
       $body="Hi ".$user->name." Your order #".$order->id." has been cancelled by the ROZANA,\nCancellation Reason :- ".$order->cancel_reason;
        $message=yt_notify_msg($title,$body,$data);

        yt_notification($message,$user->id);
   }

   public function order_complete_notification($user,$order,$data=[])
   {
    $title="Order Delivered!!";
    $body="Hi ".$user->name." Your order #".$order->id." has been delivered successfully, Thanks for the shopping with us!!";
     $message=yt_notify_msg($title,$body,$data);
     yt_notification($message,$user->id);
   }


   public function assign_deliveryboy_notification($user,$order,$data=[])
   {
       $del_date=date('d-M-Y',strtotime($order->delivery_date));
    $title="Assigned Deliver Boy!!";
    $body="Hi ".$user->name." Your order #".$order->id." has been confirmed!!\nAssigned Delivery Boy Details : \nName: ".$order->deliveryBoy->name."\nPhone no : ".$order->deliveryBoy->phone."\nExpected Delivery Date : ".$del_date;
    $message=yt_notify_msg($title,$body,$data);
    yt_notification($message,$user->id);

    $title="NEW ORDER #".$order->id;
    $body="You have one new order having delivery on ".$del_date;
    $message=yt_notify_msg($title,$body,$data);
    yt_notification($message,$order->deliveryBoy->id,'single',[],3);
   }

   public function wallet_txn_notification($user,$amount,$txn_type,$custom_message='')
   {
        $title="Wallet ".$txn_type;
        if($txn_type=='credit'){
            $body="Hi ".$user->name.", Your wallet is credited with Rs.".$amount;
            $message=yt_notify_msg($title,$body);

            yt_notification($message,$user->id);
        }elseif($txn_type=='debit'){
            $body="Hi ".$user->name.", Your wallet is debited with Rs.".$amount;
            $message=yt_notify_msg($title,$body);

            yt_notification($message,$user->id);
        }

        if(!empty(trim($custom_message))){
            $cmessage=yt_notify_msg("Hi ".$user->name."",$custom_message);

            yt_notification($cmessage,$user->id);
        }

   }

   public function refferal_notification($refer_from,$refer_to,$amount)
   {
       $title="Referral Reward";
       $body1="Hi ".$refer_from->name.", Your wallet is credited with Rs.".$amount." for referring ".$refer_to->name;
       $body2="Hi ".$refer_to->name.", Your wallet is credited with Rs.".$amount."";;
       $message1=yt_notify_msg($title,$body1);
       $message2=yt_notify_msg($title,$body2);


       yt_notification($message1,$refer_from->id);
       yt_notification($message2,$refer_to->id);

   }

   public function cashback_wallet_notification($user,$amount)
   {
       $title="Cashback Reward";
       $body1="Hi ".$user->name.", Your cashback wallet is credited with Rs.".$amount." for signup with us.";
       $message1=yt_notify_msg($title,$body1);
       yt_notification($message1,$user->id);

   }
}
