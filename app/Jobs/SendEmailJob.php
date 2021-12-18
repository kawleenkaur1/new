<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $body;
    public $emailto;
    public $subject;
    public $blade;
    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email,$subject,$blade,$data=[])
    {
        //
        $this->emailto=$email;
        $this->subject=$subject;
        $this->blade=$blade;
        $this->data=$data;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $email=$this->emailto;
        $subject=$this->subject;
        Mail::send($this->blade,$this->data,function($message) use ($subject,$email)
        {
            $message->subject($subject);
            $message->to($email);
        });
    }
}
