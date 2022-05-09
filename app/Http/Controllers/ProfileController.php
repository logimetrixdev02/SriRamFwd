<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class ProfileController extends Controller
{
	
	public function postChangePassword(Request $request)
	{
		$response = array();
		$user = \App\User::find(\Auth::user()->id);
		$old_password = $request->old_password;
		$new_password = $request->new_password;
		if(\Hash::check($old_password, $user->getAuthPassword())){
			$user->password = \Hash::make($new_password);
			$user->plain_password = $new_password;
			if($user->save()){
				$response['flag'] = true;
				/*----------------------Send notification--------------------*/
			}else{
				$response['flag'] = false;
				$response['error'] = "Something Went Wrong!";
			}
		}
		else{
			$response['flag'] = false;
			$response['error'] = "Invalid Old Password";
		}
		return response()->json($response);
	}
	public function updateProfilePic(Request $request)
	{
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'profilePic' =>'image',
			)
		);
		if($validator->fails())
		{
			$response['flag'] = false;
			$response['error'] = "Please Upload valid Image";
		}
		else
		{
			$user = \Auth::user();
			if($request->file('profilePic') != ""){
				$employee_cb_profile =  \App\EmployeeCbProfile::where('user_id',$user->id)->first();
				$image = $request->file('profilePic');
				$filename = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/images/employees');
				$image->move($destinationPath, $filename);
				$employee_cb_profile->employee_pic = url('/').'/images/employees/'.$filename;
				$employee_cb_profile->save();
				$response['flag'] = true;
			} 
		}
		return response()->json($response);
	}
}
