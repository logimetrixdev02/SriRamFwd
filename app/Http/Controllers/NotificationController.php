<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class NotificationController extends Controller
{
	public static function notify($message,$fire_base_id,$type,$activity = null){

		$admin_server_id = env('ADMIN_SERVER_ID');
		$logistic_manager_server_id = env('LOGISTIC_MANAGER_SERVER_ID');
		$marketing_manager_server_id = env('MARKETING_MANAGER_ID');

		if($type == "admin"){
			$server_id = $admin_server_id;
		}else if($type == "logistic_manager"){
			$server_id = $logistic_manager_server_id;
		}else if($type == "marketing_manager"){
			$server_id = $marketing_manager_server_id;
		}
		
		if(is_null($activity)){
			$msg = array
			(   
				'body' => $message,
				'title' => 'New Notification From IMANAGER',
				'icon'  => 'myicon',
				'sound' => 'mySound'
			);
		}else{
			$msg = array
			(   
				'body' => $message,
				'title' => 'New Notification From IMANAGER',
				'icon'  => 'myicon',
				'sound' => 'mySound',
				'click_action' => $activity
			);
		}
		$fields = array
		(
			'to'        => $fire_base_id,
			'notification'  => $msg
		);
		$headers = array
		(
			'Authorization: key=' . $server_id,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch);
		curl_close($ch);
		$stdObject = json_decode($result);
		if($stdObject->success){
			return true;
		}else{
			return false;
		}
	}


	public static function saveNotification($type,$notification){
		
	}
}