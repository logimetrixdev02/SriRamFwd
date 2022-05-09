<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class NotificationController extends Controller
{
	public static function notify($message,$fire_base_id,$type,$activity = null){

		$admin_server_id = "AAAAbmo6rBM:APA91bHAGrunuuB0Yons_ljuGmrH-JkTkWpyXtlsfKNgo2VO_XL2LAs4oh5P_qEKaIWsjFHiINnhjPxXKHyYrJnPafGW7pzkSdxrpwBNVv329_k_Ocavp-RmQUc2nXafMv5d7gZZToAE";
		$logistic_manager_server_id = "AAAA_jzY9jY:APA91bFKv3heYrkwCgINFILdG3JUybEWpym_EPaoor1LNE0PvaUX83PjeI1gmZxz0lZ8LdM5TV0GkqEql1_GUU2T2uwJ6pkXSf5cwpWUNIoHn3g0S2S5OyT29zPS00Vf4hj2EdEdEm6P";
		$marketing_manager_server_id = "AAAAvsoq428:APA91bGK8Ukkg1yThnT3tpBzAAocoZvfTQvgXpQwkH8Crfp46cgaAVE9lFttq4SlOXTkrbNB_8b1md164q7gF5nndJ6e39b0_CvMFbmy3qnZGT9Br5AST3QKEzn8DzZU0zmkFHdSsYsd";

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