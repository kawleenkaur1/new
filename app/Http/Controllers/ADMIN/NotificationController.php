<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{


    public function send_notification_to_customer()
    {
        $template['users'] = User::customerUser()->orderBy('name','Asc')->get();
        $template['page_title'] = 'Send Notifications to Customers';
        $template['user_type'] = 2;
        return view('admin.notifications.create',$template);
    }

    public function send_notification_to_deliveryboy()
    {
        $template['users'] = User::deliveryBoy()->orderBy('name','Asc')->get();
        $template['user_type'] = 3;
        $template['page_title'] = 'Send Notifications to Delivery Boy';
        return view('admin.notifications.create',$template);
    }

    public function store_send_notification(Request $request)
    {
        $request->validate([
            // 'image' => 'mimes:jpeg,png,jpg,gif,svg|max:15000',
            'title' => 'required',
            'body' => 'required',
        ]);
        $arr = [];
        $input = $request->all();
        $title = $input['title'];
        $body = $input['body'];
        $user_id = $input['user_id'];
        $data = yt_notify_msg($title,$body);

        if(!empty($user_id)){
            $user = User::where('id',$user_id)->first();
            if($user){

                yt_notification($data,$user_id,'single',[],$input['user_type']);
                return redirect()->back()->with('success', 'Notification Sent Successfully!');
            }else{
                return redirect()->back()->with('warning', 'User not found! Please again with another User');
            }

        }else{
            yt_notification($data,0,'multiple',[],$input['user_type']);
            return redirect()->back()->with('success', 'Notification Sent Successfully!');
        }

        return redirect()->back()->with('error', 'Something went wrong!');
        //
    }
}
