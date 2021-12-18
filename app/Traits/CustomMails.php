<?php
namespace App\Traits;

use App\Jobs\SendEmailJob;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

trait CustomMails{

    public function send_mail($html_blade,$data=[],$email,$username,$subject)
    {
        Mail::send($html_blade, $data, function ($message)  use ($email,$username,$subject) {
            $message->to($email, $username)->subject($subject);
        });
    }

    public function test_mail()
    {
        $order=Order::first();
        SendEmailJob::dispatch('hssaggu567@gmail.com','TEST MAIL','mails.order',  ['order'=>$order])->delay(Carbon::now()->addMinutes(3));;;

        // SendEmailJob::dispatch('hssaggu567@gmail.com','TEST MAIL','mails.test',  [])->delay(Carbon::now()->addMinutes(3));;;
        // $this->send_mail('mails.test',[],'hssaggu567@gmail.com','hssaggu567@gmail.com','TEST MAIL');
    }

    public function create_order_mail($order_id)
    {
        $order=Order::where('id',$order_id)->first();
        if($order){
            if($email=$order->user->email){
                SendEmailJob::dispatch($email,'ORDER CONFIRMATION','mails.order',  ['order'=>$order])->delay(Carbon::now()->addMinutes(3));;;
            }

        }

    }

}
