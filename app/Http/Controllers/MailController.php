<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function basic_email() {

        $data = array('name'=>"Harpreet Singh");
        Mail::send('mail', $data, function ($message) {
            $message->to('harpreet.appslure@gmail.com', 'Harpreet Singh')->subject
            ('Crimson');
        });

        echo "Basic Email Sent. Check your inbox.";
     }
}
