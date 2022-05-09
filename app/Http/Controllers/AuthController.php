<?php

namespace App\Http\Controllers;
use Faker;
use App\User;
use Illuminate\Http\Request;
use Session;

class AuthController extends Controller
{
	public function index()
	{
		\App::setLocale('hi');
		return view('login');
	}

	public function login(Request $request)
	{
		$validator = \Validator::make(
			array(
				'email' =>$request->email,
				'password' =>$request->password,
			),
			array(
				'email' =>'required',
				'password' =>'required',
			)
		);
		if($validator->fails())
		{
			return redirect('/')
			->withErrors($validator)
			->withInput();
		}
		else
		{
			$creds = ['email'=>$request->email,'password'=>$request->password];
			if(\Auth::attempt($creds)){
				if(\Auth::user()->is_active == 0){
					\Auth::logout();
					return redirect('/')->with('error',"Your Account is Deactivated. Please Contact Admin");
				}else{
					$menus = \App\RoleModuleAssociation::with('module')
					->where('role_id',\Auth::user()->role_id)
					->groupBy('module_id')
					->get();
					Session::put('menus',$menus);
					return redirect('/user');
				}
			}
			else{
				return redirect('/')->with('error',"Invalid Email or Password");
			}
		}
	}
	public function getForgetPassword()
	{
		return view('forget-password');
	}
	public function postForgetPassword(Request $request)
	{
		$response = array();
		$validator = \Validator::make(
			array(
				'email' =>$request->email,
			),
			array(
				'email' =>'required|email',
			)
		);
		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}
		else
		{
			$internals = Faker\Factory::create('en_US');
			$auth_pass = $internals->uuid;
			$user = User::where('email',$request->email)->first();
			if(!is_null($user)){
				if (\DB::table('password_resets')->where('email', $request->email)->insert(array('email'=>$request->email,'token' => $auth_pass))) {
					$templateData['confirmation_code'] = $auth_pass;
					$MailData = new \stdClass();
					$MailData->receiver_email = $request->email;
					$MailData->receiver_name = $request->email;
					$MailData->sender_email = $user->email;
					$MailData->sender_name = $user->first_name.' '.$user->last_name;
					$MailData->subject = 'Password Reset';
					MailController::sendMail('forget_password',$templateData,$MailData);

					$response['flag'] = true;
					$response['message'] = "We have sent an email to your Email";
				}
			}else{
				$response['flag'] = false;
				$response['errors']['email'] = "This email is not registered With Us";
			}
		}

		return response()->json($response);
	}
	public function verify_reset_token($token)
	{
		$response = array();
		$result = \DB::table('password_resets')->where('token', '=', $token)->first();
		if (count($result)>0) {
			\Session::put('email',$result->email);
			return view('reset_password');
		}else{
			return redirect('/')->with('error','invalid Or expired token');
		}
	}
	public function post_reset_password(Request $request)
	{
		$validator = \Validator::make(
			array(
				'new_password' =>$request->new_password,
				'confirm_password' =>$request->confirm_password,
			),
			array(
				'new_password' =>'required',
				'confirm_password' =>'required|same:new_password',
			)
		);
		if($validator->fails())
		{
			return redirect()->back()
			->withErrors($validator)
			->withInput();
		}
		else
		{
			$email = \Session::pull('email');
			$user = \App\User::where('email',$email)->first();
			if(!is_null($user)){
				$user->password = \Hash::make($request->new_password);
				$user->plain_password = $request->new_password;
				if($user->save()){
					\Session::forget('email');
					\DB::table('password_resets')->where('email', '=', $email)->delete();
					return redirect('/')->with('success','New Password has been set.');
				}else{
					\Session::forget('email');
					return redirect()->back()->with('error','Session Expired');
				}
			}else{
				\Session::forget('email');
				return redirect()->back()->with('error','Session Expired');
			}

		}
	}

	public function logout(){
		\Auth::logout();
		Session()->forget('menus');
		Session()->flush();
		return redirect('/');
	}
}
