<?php

namespace App\Http\Controllers;
use Mail;
use Illuminate\Http\Request;

class TestController extends Controller
{
	
	public  function send_mail()
	{
		Mail::send('emails.test',['email'=>'bipinyadav18390@gmail.com'], function($message) {
		    $message->from('logimetrix@gmail.com','web title');
		    $message->subject("Testing Email Logimetrix");
		    $message->to('bipinyadav18390@gmail.com');
		});
	}
	
	// public function test()
	// {

	// }

	
}
