<?php

namespace App\Http\Controllers;
use Mail;
use Illuminate\Http\Request;

class LogController extends Controller
{
	
	public static function userErrorLog($user_id,$module,$error)
	{
		$log = new \App\UserErrorLog();
		$log->user_id = $user_id;
		$log->module = $module;
		$log->error = $error;
		$log->save();
	}

	public function userErrorLogs(Request $request){
		$data = array();
		$data['modules'] = array_unique(\App\UserErrorLog::pluck('module')->toArray());
		$data['logs'] = \App\UserErrorLog::all();
		$data['users'] = \App\User::where('role_id','!=',1)->where('is_active',1)->get();
		if ($request->isMethod('post')){
			$query = \App\UserErrorLog::query();
			if($request->user_id){
				$query->where('user_id',$request->user_id);
				$data['user_id'] = $request->user_id;
			}
			if($request->module){
				$query->where('module',$request->module);
				$data['current_module'] = $request->module;
			}
			$data['logs'] = $query->get();
		}
		return view('log.user-error-logs',$data);
	} 
}
