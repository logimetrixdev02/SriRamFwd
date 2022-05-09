<?php

namespace App\Http\Controllers;
use Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
	/**
     * Throw an HttpException with the given data.
     *
     * @param  string   $mailTemplate
     * @param  array   $templateData
     * @param  array   $MailData
     * @param  string  $subject
     *
     */
	public static function sendMail($mailTemplate,$templateData,$MailData)
	{
		Mail::send('emails.'.$mailTemplate, $templateData, function ($message) use ($MailData) {
			$message->from($MailData->sender_email, $MailData->sender_name);
			$message->to($MailData->receiver_email, $MailData->receiver_email);
			$message->subject($MailData->subject);
		});
	}
}
