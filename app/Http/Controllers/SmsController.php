<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
/**
 * Sms Controller
 */
Class SmsController extends Controller
{
/**
* Throw an HttpException with the given data.
*
* @param  string   $mobileNumber
* @param  array   $templateData
* @param  array   $MailData
* @param  string  $subject
*
*/
public static function sendSms($mobileNumbers,$message,$sms_sender_id)
{
    foreach($mobileNumbers as $mobileNumber)
    {

        $url = 'HTTP://prioritysms.tulsitainfotech.com/API/MT/SENDSMS?USER=mrsdstp&PASSWORD=Mrsd@19&SENDERID='.$sms_sender_id.'&CHANNEL=Trans&DCS=8&FLASHSMS=0&NUMBER='.$mobileNumber.'&TEXT='.urlencode($message).'&ROUTE=15';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $data = curl_exec($ch);   
        $var =  json_decode($data);
        $error = curl_errno($ch);
        $response = array(
            'error'=>$error,
            'message'=>$var
        );
        return $response;
    }
}

public function retailerSMSReport(Request $request)
{
    $data =array();
    $data['retailerSMSData'] =\App\RetailerSmsReport::get();
    return view('dashboard.Sms.retailer-sms-report',$data);
}

public function dealerSMSReport(Request $request)
{
 $data =array();
 $data['dealerSMSData'] =\App\DealerSmsReport::get();
 return view('dashboard.Sms.dealer-sms-report',$data);
}

public function customSMSReport(Request $request)
{
  $data =array();
  if ($request->isMethod('post')){
    $validator = \Validator::make($request->all(),
        array(
            'mobile_number' =>'required',
            'message'=>'required'
        )
    );
    if($validator->fails()){
        return redirect('user/to-custom-sms-report')
        ->withErrors($validator)
        ->withInput();
    }else{
        $response = SmsController::sendSms(array($request->mobile_number),$request->message);
        if($response['error']==0 && !is_null($response['message']) && $response['message']->ErrorMessage=="Done")
        {
            return redirect('/user/custom-sms-report')->with('success','SMS Sent Successfully');
        }
        else
        {
         $data['messageDelivered']=''; 
     }
 }   
}else{
 $data['messageDelivered']=''; 
}
return view('dashboard.Sms.custom-sms-report',$data);
}


}