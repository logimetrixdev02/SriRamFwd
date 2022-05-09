<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Faker;
use Auth;
use App\User;
// use Carbon\Carbon;


class ApiController extends Controller
{
	public function checkApiAuth($data){
		if($data->api_token){
			$user = \App\User::where('api_token',$data->api_token)->first();
			if(!is_null($user)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}




	public function login(Request $request){
		$response = array();
		$internals = Faker\Factory::create('en_US');
		$validator = \Validator::make($request->all(),
			array(
				'email' 	=>'required|email',
				'password' 	=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] 	= false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			$creds = ['email'=>$request->email,'password'=>$request->password];
			if(Auth::attempt($creds)){
				$auth_user 				= User::find(Auth::user()->id);
				$auth_user->api_token 	= bcrypt($internals->uuid);
				if($request->firebase_token){
					$auth_user->firebase_token 	= $request->firebase_token;
				}
				$auth_user->save();
				$response['flag'] 		= true;
				$response['user'] 		= $auth_user;
			}else{
				$response['flag'] 		= false;
				$response['message'] 	= "Invalid credentials.";
			}
		}
		return response()->json($response);
	}



	public function orders(Request $request){
		$response = array();
		//$internals = Faker\Factory::create('en_US');
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			if($this->checkApiAuth($request)){

				// dd($request->all());
			
				// $orders = \DB::table('orders')->join('dealers','dealers.id','orders.dealer_id')->join('retailers','retailers.id','orders.retailer_id')->join('product_companies','product_companies.id','orders.product_company_id')->join('products','products.id','orders.product_id')->join('units','units.id','orders.unit_id')->leftjoin('rake_points','rake_points.id','orders.rake_point')->leftjoin('warehouses','warehouses.id','orders.from_warehouse_id')->select('orders.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.brand_name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name')->where('orders.order_status','approved')->where('orders.is_active',1)->orderBy('orders.id','desc')->get();

				$orders = \DB::table('orders')->join('dealers','dealers.unique_id','orders.dealer_id')->join('retailers','retailers.unique_code','orders.retailer_id')->join('product_companies','product_companies.id','orders.product_company_id')->join('products','products.id','orders.product_id')->join('units','units.id','orders.unit_id')->leftjoin('rake_points','rake_points.id','orders.rake_point')->leftjoin('warehouses','warehouses.id','orders.from_warehouse_id')->select('orders.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.brand_name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name')->where('orders.order_status','approved')->where('orders.is_active',1)->orderBy('orders.id','desc')->get();
				// dd($orders);
				$transport_modes = \DB::table('transport_modes')->get();
				$transporters = \DB::table('transporters')->where('is_active',1)->get();

				if(!is_null($orders)){
					$response['flag'] 			 = true;
					$response['orders'] 		 = $orders;
					$response['transport_modes'] = $transport_modes;
					$response['transporters'] = $transporters;
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Invalid Order Token";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}

	public function approved_orders(Request $request){
		$response = array();
		//$internals = Faker\Factory::create('en_US');
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			if($this->checkApiAuth($request)){
			
				$orders = \DB::table('orders')->join('dealers','dealers.id','orders.dealer_id')->join('retailers','retailers.id','orders.retailer_id')->join('product_companies','product_companies.id','orders.product_company_id')->join('products','products.id','orders.product_id')->join('units','units.id','orders.unit_id')->leftjoin('rake_points','rake_points.id','orders.rake_point')->leftjoin('warehouses','warehouses.id','orders.from_warehouse_id')->select('orders.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.brand_name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name')->where('orders.order_status','approved')->where('orders.is_active',1)->orderBy('orders.id','desc')->get();

				if(!is_null($orders)){
					$response['flag'] 			 = true;
					$response['orders'] 		 = $orders;
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Invalid Order Token";
				}

			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}



	public function order_details(Request $request){

		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
				'order_id' 		=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			if($this->checkApiAuth($request)){
				$order_id = $request->order_id;
				// dd($order_id);

				$order = \DB::table('orders')->join('dealers','dealers.unique_id','orders.dealer_id')->join('retailers','retailers.unique_code','orders.retailer_id')->join('product_companies','product_companies.id','orders.product_company_id')->join('products','products.id','orders.product_id')->join('units','units.id','orders.unit_id')->leftjoin('rake_points','rake_points.id','orders.rake_point')->leftjoin('warehouses','warehouses.id','orders.from_warehouse_id')->select('orders.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.brand_name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name')->where('orders.id',$order_id)->first();


				$total_loading = \DB::table('loading_slips')->where('order_id',$order_id)->sum('quantity');
				//dd($total_loading);

				$remaining_qty = $order->quantity - $total_loading;
				if($order != null){
					$response['flag'] 			 = true;
					$response['order'] 		 = $order;
					$response['remaining_qty'] 		 = $remaining_qty;
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Invalid Order Id";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}

		return response()->json($response);
	}


	public function add_loading(Request $request){	

	// dd($request->all());	
		
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
				'order_id'			=>'required',
				'transport_mode'			=>'required',
				'vehicle_no'			=>'required',
				'transporter_id'			=>'required',
				'loading_location'			=>'required',
			)
		);

		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			if($this->checkApiAuth($request)){				
				$order = \App\Order::where('id',$request->order_id)->first();

				$user = \App\User::where('api_token',$request->api_token)->first();

			
				if($order != null){
					if($order->remaining_qty >0){

                    $loading_slip = new \App\LoadingSlip(); 

					}else{
						 $loading_slip =\App\LoadingSlip::where('order_id',$order->id)->first();

					}
				   // $loading_slip =\App\LoadingSlip::where('order_id',$order->id)->first();
				    



				   // if($loading_slip==''){
				   // 	$loading_slip = new \App\LoadingSlip(); 

				   // }

				    if($user->role_id!=1){


				    
					if($loading_slip->vehicle_no==''){


					$loading_slip->order_id = $order->id;
					$loading_slip->company_id = $order->company_id;
					$loading_slip->dealer_id = $order->dealer_id;
					$loading_slip->retailer_id = $order->retailer_id;
					$loading_slip->order_from = $order->order_from;
					$loading_slip->rake_point = $order->rake_point;
					$loading_slip->despatch_location = $order->despatch_location;
					$loading_slip->product_company_id = $order->product_company_id;
					$loading_slip->product_id = $order->product_id;
					$loading_slip->unit_id = $order->unit_id;
					
					$loading_slip->transport_mode = $request->transport_mode;
					
						$loading_slip->vehicle_no = $request->vehicle_no;
                   
					
					$loading_slip->transporter_id = $request->transporter_id;
					$loading_slip->loading_location = $request->loading_location;
					$loading_slip->slip_status = 'slip_generated';

						 if($loading_slip->quantity==''){
						 	$loading_slip->quantity = $request->quantity;
					      $loading_slip->save();

					if($order->remaining_qty >0){
						$order->remaining_qty = $order->remaining_qty - $request->quantity;
						$order->loading_status = 1 ;
				      


					}

					
					if($order->loading_status >0){
						$order->loading_status = $order->loading_status ;
					}

					if($order->invoice_status >0){
						$order->invoice_status = $order->invoice_status ;
					}

					


                    
					
					$order->save();
					$response['flag'] 			 = true;
					$response['loading_slip_id'] = $loading_slip->id;
					$response['message'] 		 = "loading Slip Generated Successfully!";

                    }else{
                    	$response['flag'] 			= false;
					 $response['message'] 		= "Loading Already Created!";

                    }
                   
					
					
				}else{
					$response['flag'] 			= true;
					$response['loading_slip_id'] = $loading_slip->id;
					$response['message'] 		= "Vehicle No Already Updated !!";


				}


				 }else{

					$loading_slip->order_id = $order->id;
					$loading_slip->company_id = $order->company_id;
					$loading_slip->dealer_id = $order->dealer_id;
					$loading_slip->retailer_id = $order->retailer_id;
					$loading_slip->order_from = $order->order_from;
					$loading_slip->rake_point = $order->rake_point;
					$loading_slip->despatch_location = $order->despatch_location;
					$loading_slip->product_company_id = $order->product_company_id;
					$loading_slip->product_id = $order->product_id;
					$loading_slip->unit_id = $order->unit_id;
					
					$loading_slip->transport_mode = $request->transport_mode;
					
						$loading_slip->vehicle_no = $request->vehicle_no;
                   
					
					$loading_slip->transporter_id = $request->transporter_id;
					$loading_slip->loading_location = $request->loading_location;
					$loading_slip->slip_status = 'slip_generated';

				
				   $loading_slip->quantity = $request->quantity;
					$loading_slip->save();
					//$order->quantity= $request->quantity;

					if($order->remaining_qty >0){
						$order->remaining_qty = $order->remaining_qty - $request->quantity;
						$order->loading_status = 1 ;

					}

					$order->save();
					$response['flag'] 			 = true;
					$response['loading_slip_id'] = $loading_slip->id;
					$response['message'] 		 = "loading Slip Generated Successfully!";

				    }
					

				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Invalid Order Id";
				}

			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें";
				$response['is_token_expired'] = true;
			}
		}

		return response()->json($response);
	}



	public function print_loading_slip(Request $request){
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
				'loading_slip_id' 		=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{

			if($this->checkApiAuth($request)){

				$loading_slip = \DB::table('loading_slips')->join('dealers','dealers.unique_id','loading_slips.dealer_id')->join('retailers','retailers.unique_code','loading_slips.retailer_id')->join('product_companies','product_companies.id','loading_slips.product_company_id')->join('products','products.id','loading_slips.product_id')->join('units','units.id','loading_slips.unit_id')->join('transport_modes','transport_modes.id','loading_slips.transport_mode')->join('transporters','transporters.id','loading_slips.transporter_id')->leftjoin('rake_points','rake_points.id','loading_slips.rake_point')->leftjoin('warehouses','warehouses.id','loading_slips.from_warehouse_id')->where('loading_slips.id',$request->loading_slip_id)->select('loading_slips.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.brand_name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name','transporters.name as transporter_name','transport_modes.name as transport_mode_name')->first();
				if($loading_slip != null){
					$response['flag'] 			= true;
					$response['loading_slip'] 		= $loading_slip;
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Invalid Order Id";
				}

			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}

		return response()->json($response);

	}

	public function get_loading_slips(Request $request){
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{

			if($this->checkApiAuth($request)){

				$loading_slips = \DB::table('loading_slips')->join('dealers','dealers.id','loading_slips.dealer_id')->join('retailers','retailers.id','loading_slips.retailer_id')->join('product_companies','product_companies.id','loading_slips.product_company_id')->join('products','products.id','loading_slips.product_id')->join('units','units.id','loading_slips.unit_id')->join('transport_modes','transport_modes.id','loading_slips.transport_mode')->join('transporters','transporters.id','loading_slips.transporter_id')->leftjoin('rake_points','rake_points.id','loading_slips.rake_point')->leftjoin('warehouses','warehouses.id','loading_slips.from_warehouse_id')->select('loading_slips.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.brand_name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name','transporters.name as transporter_name','transport_modes.name as transport_mode_name')->get();
				if($loading_slips != null){
					$response['flag'] 			= true;
					$response['loading_slips'] 		= $loading_slips;
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "no data";
				}

			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}

		return response()->json($response);

	}

	public function adminTokens(Request $request){
		$response = array();
		$internals = Faker\Factory::create('en_US');
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			// if($this->checkApiAuth($request)){
			if(1){
				if ($request->date) {
					$date = $request->date;
				}
				else{
					$date = date('Y-m-d');
				}
				$token = \App\Token::with('dealer','master_rake','product','product_company','unit','warehouse','from_warehouse')
				->where('token_type',2)
				->whereDate('created_at',$date)
				->orderBy('id','desc')->get();
				if(!is_null($token)){
					$response['flag'] 			= true;
					$response['tokens'] 		= $token;
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Invalid Token";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}


	public function tokenList(Request $request){
		$response = array();
		$internals = Faker\Factory::create('en_US');
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			// if($this->checkApiAuth($request)){
			if(1){
				$token = \App\Token::select('id','unique_id')->orderBy('id','desc')->get();
				if(!is_null($token)){
					$response['flag'] 			= true;
					$response['tokens'] 		= $token;
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Invalid Token";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}

	public function tokenDetails(Request $request){
		$response = array();
		$internals = Faker\Factory::create('en_US');
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
				'token_id' 			=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			if($this->checkApiAuth($request)){
				$token = \App\Token::with('dealer','master_rake','product','product_company','unit','warehouse','transporter','from_warehouse','retailer')->where('id',$request->token_id)->first();
				if(!is_null($token)){
					$remaining_quantity = \App\ProductLoading::where('token_id',$request->token_id)->sum('quantity');
					if(!$remaining_quantity){
						$remaining_quantity = $token->quantity;
					}else{
						$remaining_quantity = $token->quantity - $remaining_quantity;
					}
					$response['flag'] = true;
					if($token->to_type == 1){
						$token->to = getModelById('Warehouse',$token->warehouse_id)->name;
					}elseif($token->to_type == 2){
						$token->to = getModelById('Retailer',$token->retailer_id)->name."(".getModelById('Retailer',$token->retailer_id)->address.")";
					}else{
						$token->to = getModelById('Dealer',$token->dealer_id)->name."(".getModelById('Dealer',$token->dealer_id)->address1.")";
					}

					if($token->token_type == 1){
						$token->godown = getModelById('RakePoint',$token->master_rake->rake_point_id)->rake_point;
					}else{
						$token->godown = getModelById('Warehouse',$token->from_warehouse_id)->name;
					}
					// $token->date_of_generation = ('d/m/Y',strtotime($token->date_of_generation));
					$token->product_company = getModelById('ProductCompany',$token->product_company_id)->brand_name;
					$token->token_quantity = $token->quantity." ".getModelById('Unit',$token->unit_id)->unit;
					$token->godown_keeper = is_null(getModelById('User',$token->warehouse_keeper_id)) ? "":getModelById('User',$token->warehouse_keeper_id)->name;
					$company = \App\Company::where('id',$token->company_id)->first();
					$token->company = $company;
					$current_time = date('Y-m-d H:i:s'); 
					$token_time = $token->created_at; 

					$timediff = strtotime($current_time) - strtotime($token_time);
					$is_expired = false;
					if($token->token_type == 1){
						if($timediff > 172800){ 
							$is_expired = true;
						}
					}else{
						if($timediff > 86400){ 
							$is_expired = true;
						}
					}
					$response['token'] = $token;
					$response['remaining_quantity'] = $remaining_quantity;
					$response['is_expired'] = $is_expired;
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Invalid Token";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}


	public function master_rakes(Request $request){
		$response = array();
		$internals = Faker\Factory::create('en_US');
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			// if($this->checkApiAuth($request)){
			if(1){
				$master_rakes = \App\MasterRake::where('is_active',1)->orderBy('id','desc')->get();
				if(count($master_rakes) > 0){
					$response['flag'] 			= true;
					$response['master_rakes'] 		= $master_rakes;
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "No Rakes Found";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}

	public function masterRakeDetails(Request $request){
		$response = array();
		$internals = Faker\Factory::create('en_US');
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
				'master_rake_id' 			=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			// if($this->checkApiAuth($request)){
			if(1){
				$master_rake = \App\MasterRake::where('id',$request->master_rake_id)->where('is_active',1)->with('product_company','master_rake_products','rake_allotments')->first();
				if(!is_null($master_rake)){
					$response['flag'] = true;
					$response['master_rake'] = $master_rake;

					$distict_users = array_unique(\App\ProductLoading::where('master_rake_id',$request->master_rake_id)->pluck('user_id')->toArray());
					$users = array();
					foreach ($distict_users as $distict_user) {
						$user = new \stdClass;
						$user->id = $distict_user;
						$user->name = getModelById('User',$distict_user)->name;
						array_push($users,$user);
					}
					$response['users'] = $users;

				}else{
					$response['flag'] 			= false;
					$response['message'] = "Master Rake Not Found";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}



	public function productLoading(Request $request){
		$response = array();
		if($this->checkApiAuth($request)){

			$validator = \Validator::make($request->all(),
				array(
					'loading_slip_type' =>'required',
				)
			);

			if($validator->fails())
			{
				$response['flag'] = false;
				$response['errors'] = $validator->getMessageBag();
			}else{

				$user = \App\User::where('api_token',$request->api_token)->first();

				if($request->loading_slip_type == 1){
					$token_details = \App\Token::where('id',$request->regular_token_id)->first();
					if($token_details->token_type == 1){
						$validator = \Validator::make($request->all(),
							array(
								'api_token' 		=>'required',
								'regular_token_id' =>'required',
								'regular_quantity' =>'required|integer',
								'regular_truck_number' =>'required',
								'regular_master_rake_id' =>'required',
								'regular_product_company_id' =>'required',
								'regular_product_id' =>'required',
								'regular_unit_id' =>'required',
								'regular_dealer_id' =>'required',
								'regular_transporter_id' =>'required',
								//'regular_freight' =>'required',
								'regular_labour_name' =>'required',
								'regular_labour_rate' =>'required',
							)
						);
					}else{
						$validator = \Validator::make($request->all(),
							array(
								'api_token' 		=>'required',
								'regular_token_id' =>'required',
								'regular_quantity' =>'required|integer',
								'regular_truck_number' =>'required',
								'from_warehouse_id' =>'required',
								'regular_product_company_id' =>'required',
								'regular_product_id' =>'required',
								'regular_unit_id' =>'required',
								'regular_dealer_id' =>'required',
								'regular_transporter_id' =>'required',
								//'regular_freight' =>'required',
								'regular_labour_name' =>'required',
								'regular_labour_rate' =>'required',
							)
						);
					}
					

				}else{
					$validator = \Validator::make($request->all(),
						array(
							'api_token' 		=>'required',
							'direct_master_rake_id' =>'required',
							'direct_product_company_id' =>'required',
							'direct_product_id' =>'required',
							'direct_unit_id' =>'required',
							'direct_quantity' =>'required|integer',
							'direct_truck_number' =>'required',
							'direct_labour_name' =>'required',
							'direct_labour_rate' =>'required',
							'direct_transporter_id' =>'required',
							//'direct_freight' =>'required',
							'direct_warehouse_id' =>'required',
						)
					);
				}

				if($validator->fails())
				{
					$response['flag'] = false;
					$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
				}else{
					if($request->loading_slip_type == 1){

						$token = \App\Token::where('id',$request->regular_token_id)->first();
						$product_loading = \App\ProductLoading::where('token_id',$request->regular_token_id)->sum('quantity');
						$remaining_quantity = $token->quantity - $product_loading;
						if($product_loading && $remaining_quantity  < $request->regular_quantity){
							$response['flag'] = false; 
							$response['errors']['regular_quantity'] = "quantity Should not be greater than Token Quantity (".$token->quantity."). Remaining Quantity is ".$remaining_quantity; 
						}
						else if($request->regular_quantity > $token->quantity){
							$response['flag'] = false; 
							$response['errors']['regular_quantity'] = "quantity Should not be greater than Token Quantity (".$token->quantity.")"; 
						}else{
							$product_loading =  new \App\ProductLoading();
							$product_loading->user_id = $user->id;
							$product_loading->loading_slip_type = $request->loading_slip_type;
							$product_loading->token_id = $request->regular_token_id;
							$product_loading->master_rake_id = $request->regular_master_rake_id;
							$product_loading->from_warehouse_id = $request->from_warehouse_id;

							
							if($token->to_type == 2){
								$product_loading->retailer_id = $token->retailer_id;
								$product_loading->retailer_name = getModelById('Retailer',$token->retailer_id)->name;	
							}
							if($token->to_type == 1){
								$product_loading->is_approved = 0;
							}
							$product_loading->dealer_id = $request->regular_dealer_id;
							$product_loading->dealer_name = getModelById('Dealer',$request->regular_dealer_id)->name;
							$product_loading->product_company_id = $request->regular_product_company_id;
							$product_loading->product_company_name = getModelById('ProductCompany',$request->regular_product_company_id)->name;
							$product_loading->product_id = $request->regular_product_id;
							$product_loading->product_name = getModelById('Product',$request->regular_product_id)->name;
							$product_loading->quantity = $request->regular_quantity;
							$product_loading->unit_id = $token->unit_id;
							$product_loading->unit_name = getModelById('Unit',$token->unit_id)->unit;
							$product_loading->transporter_id = $request->regular_transporter_id;
							if($request->regular_transporter_id){
								$product_loading->transporter_name = getModelById('Transporter',$request->regular_transporter_id)->name;
							}
							if($request->regular_wagon_no){
								$product_loading->wagon_number = $request->regular_wagon_no;
							}
							$product_loading->truck_number = $request->regular_truck_number;
							$product_loading->freight = $request->regular_freight;

							if($product_loading->save()){




								$labour_payment =  new \App\LabourPayments();
								$labour_payment->user_id = $user->id;
								$labour_payment->token_id = $request->regular_token_id;
								$labour_payment->master_rake_id = $request->regular_master_rake_id;
								$labour_payment->from_warehouse_id = $request->from_warehouse_id;
								$labour_payment->product_loading_id = $product_loading->id;
								$labour_payment->product_id = $request->regular_product_id;
								$labour_payment->product_name = getModelById('Product',$request->regular_product_id)->name;
								$labour_payment->quantity = $request->regular_quantity;
								$labour_payment->unit_id = $token->unit_id;
								$labour_payment->unit_name = getModelById('Unit',$token->unit_id)->unit;
								$labour_payment->labour_name = $request->regular_labour_name;
								$labour_payment->rate = $request->regular_labour_rate;
								$labour_payment->truck_number = $request->regular_truck_number;
								$product_loading->driver_number = $request->driver_number;
								$labour_payment->save();

								/*-----push notification---*/
								$recepients = \App\User::whereIn('role_id',array(1,5,6))->get();

								if($product_loading->loading_slip_type == 1){
									$msg = "Product Loading (".$product_loading->id.") of ".$product_loading->product_name ." ".$product_loading->quantity ." ".$product_loading->unit_name; 

									if(!is_null($product_loading->token_id)){
										$msg.= " against Token ".getModelById('Token',$product_loading->token_id)->unique_id." To ";
										if($product_loading->loading_slip_type ==1){
											$msg .= getModelById('Dealer',$product_loading->dealer_id)->name."(".getModelById('Dealer',$product_loading->dealer_id)->address1.")";					
										}else{
											$msg .= getModelById('Warehouse',$product_loading->warehouse_id)->name;					
										}
									}else{ 
										if($product_loading->loading_slip_type ==1){
											$msg .= getModelById('Dealer',$product_loading->dealer_id)->name."(".getModelById('Dealer',$product_loading->dealer_id)->address1.")";				
										}else{
											$msg .= getModelById('Warehouse',$product_loading->warehouse_id)->name;		
										}

									}
								}else{


									$msg = "Direct Product Loading ".$product_loading->id." of ".$product_loading->product_name." ".$product_loading->quantity." ".$product_loading->unit_name." To ". getModelById('Warehouse',$product_loading->warehouse_id)->name;	

								}

								$msg.=" By ".$user->name;
								foreach ($recepients as $recepient) {
									if(!is_null($recepient->firebase_token) || $recepient->firebase_token != ""){
										if($recepient->role_id == 1){
											$type = "admin";
										}else if($recepient->role_id == 5){
											$type = "logistic_manager";
										}else if($recepient->role_id == 6){
											$type = "marketing_manager";
										}
										NotificationController::notify($msg,$recepient->firebase_token,$type);
									}	
								}
								/*-----push notification---*/



								if($token->token_type == 1){
									$inventory = \App\Inventory::where('dealer_id',$request->regular_dealer_id)
									->where('warehouse_id',24)
									->where('product_brand_id',$token->product_company_id)
									->where('product_id',$request->regular_product_id)
									->first();

									if(!is_null($inventory)){
										$inventory->quantity = $inventory->quantity - $request->regular_quantity;
										$inventory->save();
									}else{
										$inventory = new  \App\Inventory();
										$inventory->dealer_id 			= $request->regular_dealer_id;
										$inventory->warehouse_id 		= 24;
										$inventory->product_brand_id 		= $token->product_company_id;
										$inventory->product_id 			= $request->regular_product_id;
										$inventory->quantity 			= 0 - $request->regular_quantity;
										$inventory->unit_id = $token->unit_id;
										$inventory->save();
									}

								}

								if($token->token_type == 2){
									$inventory = \App\Inventory::where('dealer_id',$request->regular_dealer_id)->where('warehouse_id',$token->from_warehouse_id)->where('product_brand_id',$token->product_company_id)->where('product_id',$request->regular_product_id)->first();
									if($inventory){
										$inventory->quantity  = $inventory->quantity - $request->regular_quantity;
										$inventory->save();
									}else{
										$inventory = new \App\Inventory();
										$inventory->dealer_id  = $request->regular_dealer_id;
										$inventory->warehouse_id  = $request->from_warehouse_id;
										$inventory->product_brand_id  = $token->product_company_id;
										$inventory->product_id  = $request->regular_product_id;
										$inventory->unit_id = $token->unit_id;
										$inventory->quantity  = 0 - $request->regular_quantity;
										$inventory->save();
									}
								}
								$response['flag'] = true;
								$response['message'] = "Loading Slip Generated Successfully";
								$response['loading_slip_id'] = $product_loading->id;
							}else{
								$response['flag'] = false;
								$response['error'] = "Something Went Wrong";
							}

						}
					}

					else {


						$master_rake_product = \App\MasterRakeProduct::where('master_rake_id',$request->direct_master_rake_id)->where('product_id',$request->direct_product_id)->first();

						if(is_null($master_rake_product)){
							$response['flag'] = false;
							$error = new \stdClass();
							$error->message = 'This Rake do not contains this Product';
							$response['errors'][] = $error;
						}else{

							$product_loading = \App\ProductLoading::where('master_rake_id',$request->direct_master_rake_id)->where('product_id',$request->direct_product_id)->sum('quantity');

							$remaining_quantity = ($master_rake_product->quantity + $master_rake_product->excess_quantity + 1000) - $product_loading;
							if($product_loading && $remaining_quantity < $request->direct_quantity){
								$response['flag'] = false; 

								$error = new \stdClass();
								$error->message = "quantity Should not be greater than Alloted Quantity (".$master_rake_product->quantity ."). Remaining Quantity is ".$remaining_quantity; 
								$response['errors'][] = $error;

							}else
							if(!$product_loading && $master_rake_product->quantity < $request->direct_quantity){
								$response['flag'] = false; 
								$error = new \stdClass();
								$error->message = "quantity Should not be greater than Alloted Quantity (".$master_rake_product->quantity .")."; 
								$response['errors'][] = $error;

							}else{

								$product_loading =  new \App\ProductLoading();
								$product_loading->user_id = $user->id;
								$product_loading->loading_slip_type = $request->loading_slip_type;
								$product_loading->master_rake_id = $request->direct_master_rake_id;
								$product_loading->product_company_id = $request->direct_product_company_id;
								$product_loading->product_company_name = getModelById('ProductCompany',$request->direct_product_company_id)->name;
								$product_loading->warehouse_id = $request->direct_warehouse_id;
								$product_loading->product_id = $request->direct_product_id;
								$product_loading->product_name = getModelById('Product',$request->direct_product_id)->name;
								$product_loading->quantity = $request->direct_quantity;
								$product_loading->unit_id = $request->direct_unit_id;
								$product_loading->unit_name = getModelById('Unit',$request->direct_unit_id)->unit;
								$product_loading->transporter_id = $request->direct_transporter_id;
								if($request->direct_transporter_id){
									$product_loading->transporter_name = getModelById('Transporter',$request->direct_transporter_id)->name;
								}
								if($request->direct_wagon_no){
									$product_loading->wagon_number = $request->direct_wagon_no;
								}
								$product_loading->truck_number = $request->direct_truck_number;
								$product_loading->freight = $request->direct_freight;
								$product_loading->is_approved = 0;

								if($product_loading->save()){


									$labour_payment =  new \App\LabourPayments();
									$labour_payment->user_id = $user->id;
									$labour_payment->master_rake_id = $request->direct_master_rake_id;
									$labour_payment->product_loading_id = $product_loading->id;
									$labour_payment->warehouse_id = $product_loading->warehouse_id;
									$labour_payment->product_id = $request->direct_product_id;
									$labour_payment->product_name = getModelById('Product',$request->direct_product_id)->name;
									$labour_payment->quantity = $request->direct_quantity;
									$labour_payment->unit_id = $request->direct_unit_id;
									$labour_payment->unit_name = getModelById('Unit',$request->direct_unit_id)->unit;
									$labour_payment->labour_name = $request->direct_labour_name;
									$labour_payment->rate = $request->direct_labour_rate;
									$labour_payment->truck_number = $request->direct_truck_number;
									$product_loading->driver_number = $request->driver_number;

									$labour_payment->save();


									/*-----push notification---*/
									$recepients = \App\User::whereIn('role_id',array(1,5,6))->get();

									if($product_loading->loading_slip_type == 1){
										$msg = "Product Loading (".$product_loading->id.") of ".$product_loading->product_name ." ".$product_loading->quantity ." ".$product_loading->unit_name; 

										if(!is_null($product_loading->token_id)){
											$msg.= " against Token ".getModelById('Token',$product_loading->token_id)->unique_id." To ";
											if($product_loading->loading_slip_type ==1){
												$msg .= getModelById('Dealer',$product_loading->dealer_id)->name."(".getModelById('Dealer',$product_loading->dealer_id)->address1.")";					
											}else{
												$msg .= getModelById('Warehouse',$product_loading->warehouse_id)->name;					
											}
										}else{ 
											if($product_loading->loading_slip_type ==1){
												$msg .= getModelById('Dealer',$product_loading->dealer_id)->name."(".getModelById('Dealer',$product_loading->dealer_id)->address1.")";				
											}else{
												$msg .= getModelById('Warehouse',$product_loading->warehouse_id)->name;		
											}

										}
									}else{


										$msg = "Direct Product Loading ".$product_loading->id." of ".$product_loading->product_name." ".$product_loading->quantity." ".$product_loading->unit_name." To ". getModelById('Warehouse',$product_loading->warehouse_id)->name;	

									}

									$msg.=" By ".$user->name;
									foreach ($recepients as $recepient) {
										if(!is_null($recepient->firebase_token) || $recepient->firebase_token != ""){
											if($recepient->role_id == 1){
												$type = "admin";
											}else if($recepient->role_id == 5){
												$type = "logistic_manager";
											}else if($recepient->role_id == 6){
												$type = "marketing_manager";
											}
											NotificationController::notify($msg,$recepient->firebase_token,$type);
										}	
									}
									/*-----push notification---*/
									

									
									//remove from Buffer STP
									$inventory = \App\Inventory::where('product_company_id',$request->direct_product_company_id)->where('warehouse_id',24)->where('product_id',$request->direct_product_id)->where('product_brand_id',$request->direct_product_company_id)->first();

									if(!is_null($inventory)){
										$inventory->quantity = $inventory->quantity - $request->direct_quantity;
										$inventory->save();
									}


									$response['flag'] = true;
									$response['message'] = "Loading Slip Generated Successfully";
									$response['loading_slip_id'] = $product_loading->id;
								}else{
									$response['flag'] = false;
									$response['error'] = "Something Went Wrong";
								}
							}
						}
					}
				}
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
		return response()->json($response);
	}




	public function productUnloading(Request $request){
		$response = array();
		if($this->checkApiAuth($request)){
			$validator = \Validator::make($request->all(),
				array(
					'unloading_slip_type' =>'required',
				),
				array(
					'unloading_slip_type.required'=>"अनलोडिंग स्लिप टाइप चुनें",
				)
			);

			if($validator->fails())
			{
				$response['flag'] = false;
				$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
			}else{

				// regular unloading
				if($request->unloading_slip_type == 1){


					$validator = \Validator::make($request->all(),
						array(
							'loading_slip_id' =>'required',
							'warehouse_id' =>'required',
							'quantity' =>'required|min:1',
							'labour_name' =>'required',
							'labour_rate' =>'required',
						)
					);

					if($validator->fails())
					{
						$response['flag'] = false;
						$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
					}else{
						if($request->quantity != 0){

							$product_loading = \App\ProductLoading::where('id',$request->loading_slip_id)->with('token')->first();

							$user = \App\User::where('api_token',$request->api_token)->first();
							if(!is_null($product_loading)){
								if($product_loading->recieved_quantity == 0){

									$product_unloading =  new \App\ProductUnloading();
									$product_unloading->user_id = $user->id;
									$product_unloading->loading_slip_type = $product_loading->loading_slip_type;
									if(isset($product_loading->token)){
										$product_unloading->token_id = $product_loading->token->id;
									}
									$product_unloading->product_loading_id = $product_loading->id;
									$product_unloading->master_rake_id = $product_loading->master_rake_id;

								// if($product_loading->loading_slip_type == 2){
									$product_unloading->product_company_id = $product_loading->product_company_id;
									$product_unloading->product_company_name = getModelById('ProductCompany',$product_loading->product_company_id)->name;
								// }
									$product_unloading->warehouse_id = $request->warehouse_id;
									$product_unloading->product_id = $product_loading->product_id;
									$product_unloading->product_name = getModelById('Product',$product_loading->product_id)->name;
									$product_unloading->quantity = $request->quantity;
									$product_unloading->unit_id = $product_loading->unit_id;
									$product_unloading->unit_name = getModelById('Unit',$product_loading->unit_id)->unit;
									$product_unloading->transporter_id = $product_loading->transporter_id;

									if($product_loading->transporter_id){
										$product_unloading->transporter_name = getModelById('Transporter',$product_loading->transporter_id)->name;
									}


									$product_unloading->truck_number = $product_loading->truck_number;

									if($product_loading->loading_slip_type == 1){
										$product_unloading->dealer_id = $product_loading->dealer_id;
										$product_unloading->dealer_name = getModelById('Dealer',$product_loading->dealer_id)->name;
									}


									if($product_unloading->save()){

										$product_loading->is_approved = 1;
										$product_loading->recieved_quantity = $request->quantity;
										$product_loading->save();

										$unloading_labour_payment =  new \App\UnloadingLabourPayment();
										$unloading_labour_payment->user_id = $user->id;
										if(isset($product_loading->token)){
											$unloading_labour_payment->token_id = $product_loading->token->id;
										}
										$unloading_labour_payment->master_rake_id = $product_loading->master_rake_id;
										$unloading_labour_payment->product_unloading_id = $product_unloading->id;
										$unloading_labour_payment->warehouse_id = $request->warehouse_id;
										$unloading_labour_payment->product_id = $product_loading->product_id;
										$unloading_labour_payment->product_name = getModelById('Product',$product_loading->product_id)->name;
										$unloading_labour_payment->quantity = $request->quantity;
										$unloading_labour_payment->unit_id = $product_loading->unit_id;
										$unloading_labour_payment->unit_name = getModelById('Unit',$product_loading->unit_id)->unit;
										$unloading_labour_payment->labour_name = $request->labour_name;
										$unloading_labour_payment->rate = $request->labour_rate;
										$unloading_labour_payment->truck_number = $product_loading->truck_number;
										$unloading_labour_payment->save();



										if(isset($product_loading->token)){


											$token = \App\Token::where('id',$product_loading->token_id)->first(); 
											$inventory = \App\Inventory::where('dealer_id',$product_loading->dealer_id)->where('warehouse_id',$request->warehouse_id)->where('product_brand_id',$product_loading->product_company_id)->where('product_id',$product_loading->product_id)->first();

											if(!is_null($inventory)){
												$inventory->quantity = $inventory->quantity + $request->quantity;
												$inventory->save();
											}else{
												$inventory = new  \App\Inventory();
												$inventory->dealer_id 			= $product_loading->dealer_id;
												$inventory->warehouse_id 		= $request->warehouse_id;
												$inventory->product_brand_id 		= $product_loading->token->product_company_id;
												$inventory->product_id 			= $product_loading->product_id;
												$inventory->quantity 			= $request->quantity;
												$inventory->unit_id = $product_loading->token->unit_id;
												$inventory->save();
											}

											if(!is_null($product_loading->token->warehouse_id)){
												if($product_loading->token->warehouse_id != $request->warehouse_id){
													$token->warehouse_id = $request->warehouse_id;
													$token->save();
													$product_loading->warehouse_id = $request->warehouse_id;
													$product_loading->save();
												}
											}else{
												$token->warehouse_id = $request->warehouse_id;
												$token->save();
												$product_loading->warehouse_id = $request->warehouse_id;

												if(!is_null($product_loading->retailer_id) || !is_null($product_loading->dealer_id)){
													$product_loading->is_returned = 1;
												}

												$product_loading->save();

											}



										}else if($product_loading->loading_slip_type == 2){
											$inventory = \App\Inventory::where('product_company_id',$product_loading->product_company_id)->where('warehouse_id',$request->warehouse_id)->where('product_brand_id',$product_loading->product_company_id)->where('product_id',$product_loading->product_id)->first();
											if(!is_null($inventory)){
												$inventory->quantity  = $inventory->quantity + $request->quantity;
												$inventory->save();
											}else{
												$inventory = new \App\Inventory();
												$inventory->product_company_id  = $product_loading->product_company_id;
												$inventory->warehouse_id  = $request->warehouse_id;
												$inventory->product_brand_id  = $product_loading->product_company_id;
												$inventory->product_id  = $product_loading->product_id;
												$inventory->unit_id = $product_loading->unit_id;
												$inventory->quantity  =  $request->quantity;
												$inventory->save();
											}
											if($product_loading->warehouse_id != $request->warehouse_id){
												$product_loading->warehouse_id = $request->warehouse_id;
												$product_loading->save();
											}

										}

										$response['flag'] = true;
										$response['message'] = "अनलोडिंग सफलतापूर्वक हो चुकी है !";
										$response['unloading_slip_id'] = $product_unloading->id;
									}else{
										$response['flag'] = false;
										$response['error'] = "Something Went Wrong";
									}


								}else{
									$response['flag'] 			= false;
									$response['message'] 		= "ये लोडिंग पहले ही अनलोड हो चुकी है !";
								}

							}else{
								$response['flag'] 			= false;
								$response['message'] 		= "Loading details not found";
							}
						}else{
							$response['flag'] = false;
							$response['error'] = "आमद मात्रा 0 से ज्यादा होनी चाहिए !";
						}

					}

				}else{
// direct unloading



					$validator = \Validator::make($request->all(),
						array(
							'direct_invoice_number' =>'required',
							// 'direct_invoice_date' =>'required',
							'direct_product_company_id' =>'required',
							'direct_product_id' =>'required|min:1',
							'direct_quantity' =>'required|min:1',
							'direct_unit_id' =>'required',
							'direct_warehouse_id' =>'required',
							'direct_transporter_id' =>'required',
							'direct_truck_number' =>'required',
							'direct_labour_name' =>'required',
							'direct_labour_rate' =>'required',
						),
						array(
							'direct_invoice_number.required'=>'कृपया इनवॉइस/चालान नम्बर डालें',
							// 'direct_invoice_date.required'=>'कृपया इनवॉइस डेट डालें',
							'direct_product_company_id.required'=>'कृपया कंपनी चुनें',
							'direct_product_id.required'=>'कृपया प्रोडक्ट चुनें',
							'direct_quantity.required'=>'कृपया मात्रा डालें',
							'direct_unit_id.required'=>'कृपया यूनिट चुनें',
							'direct_warehouse_id.required'=>'कृपया वेयरहाउस चुनें',
							'direct_transporter_id.required'=>'कृपया ट्रांसपोर्टर चुनें',
							'direct_truck_number.required'=>'कृपया ट्रक नंबर डालें',
							'direct_labour_name.required'=>'कृपया लेबर का नाम डालें',
							'direct_labour_rate.required'=>'कृपया लेबर रेट डालें',
						)
					);

					if($validator->fails())
					{
						$response['flag'] = false;
						$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
					}else{

						$current_date = date('Y-m-d');
						$date1 = strtr($request->direct_invoice_date, '/', '-');
						// $invoiceDateArr = explode('/', $request->direct_invoice_date); 
						// $timediff = strtotime($current_date) - strtotime($invoiceDateArr[2].'-'.$invoiceDateArr[1].'-'.$invoiceDateArr[0]);
						// if($timediff <= 432000){ 
						if(1){ 

							$user = \App\User::where('api_token',$request->api_token)->first();
							$product_unloading =  new \App\ProductUnloading();
							$product_unloading->user_id = $user->id;
							$product_unloading->product_company_id = $request->direct_product_company_id;
							$product_unloading->product_company_name = getModelById('ProductCompany',$request->direct_product_company_id)->name;

							$product_unloading->warehouse_id = $request->direct_warehouse_id;
							$product_unloading->product_id = $request->direct_product_id;
							$product_unloading->product_name = getModelById('Product',$request->direct_product_id)->name;
							$product_unloading->quantity = $request->direct_quantity;
							$product_unloading->unit_id = $request->direct_unit_id;
							$product_unloading->unit_name = getModelById('Unit',$request->direct_unit_id)->unit;
							$product_unloading->transporter_id = $request->direct_transporter_id;
							$product_unloading->freight = $request->direct_freight;
							$product_unloading->invoice_callan_number = $request->direct_invoice_number;
							if($request->direct_invoice_date){
								$product_unloading->invoice_date = date('Y-m-d', strtotime($date1));
							}
							if($request->direct_transporter_id){
								$product_unloading->transporter_name = getModelById('Transporter',$request->direct_transporter_id)->name;
							}
							$product_unloading->truck_number = $request->direct_truck_number;
							if($product_unloading->save()){

								$unloading_labour_payment =  new \App\UnloadingLabourPayment();
								$unloading_labour_payment->user_id = $user->id;							
								$unloading_labour_payment->product_unloading_id = $product_unloading->id;
								$unloading_labour_payment->warehouse_id = $request->direct_warehouse_id;
								$unloading_labour_payment->product_id = $request->direct_product_id;
								$unloading_labour_payment->product_name = getModelById('Product',$request->direct_product_id)->name;
								$unloading_labour_payment->quantity = $request->direct_quantity;
								$unloading_labour_payment->unit_id = $request->direct_unit_id;
								$unloading_labour_payment->unit_name = getModelById('Unit',$request->direct_unit_id)->unit;
								$unloading_labour_payment->labour_name = $request->direct_labour_name;
								$unloading_labour_payment->rate = $request->direct_labour_rate;
								$unloading_labour_payment->truck_number = $request->direct_truck_number;
								$unloading_labour_payment->save();

								$inventory = \App\Inventory::where('product_company_id',$request->direct_product_company_id)->where('warehouse_id',$request->direct_warehouse_id)->where('product_brand_id',$request->direct_product_company_id)->where('product_id',$request->direct_product_id)->first();
								if(!is_null($inventory)){
									$inventory->quantity  = $inventory->quantity + $request->direct_quantity;
									$inventory->save();
								}else{
									$inventory = new \App\Inventory();
									$inventory->product_company_id  = $request->direct_product_company_id;
									$inventory->warehouse_id  = $request->direct_warehouse_id;
									$inventory->product_brand_id  = $request->direct_product_company_id;
									$inventory->product_id  = $request->direct_product_id;
									$inventory->unit_id = $request->direct_unit_id;
									$inventory->quantity  =  $request->direct_quantity;
									$inventory->save();
								}

								$response['flag'] = true;
								$response['message'] = "अनलोडिंग सफलतापूर्वक हो चुकी है !";
								$response['unloading_slip_id'] = $product_unloading->id;
							}else{
								$response['flag'] = false;
								$response['message'] = "Something Went Wrong";
							}

						}else{

							$response['flag'] = false;
							$response['message'] = "इनवॉइस डेट 5 से अधिक पुरानी नहीं होनी चाहिए $timediff";

						}



					}

				}

			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
		return response()->json($response);
	}

	public function DirectlabourPaymentSlips(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());

		}else{
			if($this->checkApiAuth($request)){
				$date = date('Y-m-d');
				if($request->date){
					$date = $request->date;
				}
				$labour_slips = \App\DirectLabourPayment::orderBy('id','desc')->with('master_rake','warehouse')->get();
				$response['flag'] 			= true;
				$response['labour_slips'] = $labour_slips;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}

	public function labourSlips(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());

		}else{
			// if($this->checkApiAuth($request)){
			if(1){
				$date = date('Y-m-d');
				if($request->date){
					$date = $request->date;
				}
				$labour_slips = \App\LabourPayments::orderBy('id','desc')->whereDate('created_at', '=', date('Y-m-d'))->get();
				$response['flag'] 			= true;
				$response['labour_slips'] = $labour_slips;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}

	public function loadingSlips(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());

		}else{
			// if($this->checkApiAuth($request)){
			if(1){
				$date = date('Y-m-d');
				if($request->date){
					$date = $request->date;
				}
				$last_date = date('Y-m-d',strtotime('-5 days'));
				$loading_slips = \App\ProductLoading::with('token','master_rake','product:id,name,hindi_name','warehouse:id,name,hindi_name','labour_payment','from_warehouse:id,name,hindi_name','retailer:id,name,hindi_name,address','userinfo')
				->whereDate('created_at', '>=',$last_date)
				->orderBy('id','desc')
				->get();
				$response['flag'] 			= true;
				$response['loading_slips'] = $loading_slips;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}

	public function new_loading_slips(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());

		}else{
			if($this->checkApiAuth($request)){
		
				$date = date('Y-m-d');
				if($request->date){
					$date = $request->date;
				}
				$last_date = date('Y-m-d',strtotime('-5 days'));
				$loading_slips = \DB::table('loading_slips')->join('dealers','dealers.id','loading_slips.dealer_id')->join('retailers','retailers.id','loading_slips.retailer_id')->join('product_companies','product_companies.id','loading_slips.product_company_id')->join('products','products.id','loading_slips.product_id')->join('units','units.id','loading_slips.unit_id')->join('transport_modes','transport_modes.id','loading_slips.transport_mode')->join('transporters','transporters.id','loading_slips.transporter_id')->leftjoin('rake_points','rake_points.id','loading_slips.rake_point')->leftjoin('warehouses','warehouses.id','loading_slips.from_warehouse_id')->where('loading_slips.slip_status','saved')->select('loading_slips.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name','transporters.name as transporter_name','transport_modes.name as transport_mode_name')->get();
				$response['flag'] 			= true;
				$response['loading_slips'] = $loading_slips;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}

	public function loadingSlipList(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());

		}else{
			// if($this->checkApiAuth($request)){
			if(1){
				$date = date('Y-m-d');
				if($request->date){
					$date = $request->date;
				}
				$open_rake_ids = \App\MasterRake::where('is_closed',0)->pluck('id');
				if(!is_null($open_rake_ids)){
					$loading_slips = \App\ProductLoading::select('id','transporter_name')->orderBy('id','desc')
					->whereDate('created_at', '>=','2019-09-15')
					->get();
					$response['loading_slips'] = $loading_slips;
				}
				$response['flag'] 			= true;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}


	public function unloadingSlips(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());

		}else{
			if($this->checkApiAuth($request)){
				$date = date('Y-m-d');
				if($request->date){
					$date = $request->date;
				}
				$unloading_slips = \App\ProductUnloading::with('token','master_rake','warehouse','unloading_labour_payment')->orderBy('id','desc')
				->get();
				$response['flag'] 			= true;
				$response['unloading_slips'] = $unloading_slips;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}



	public function loadingSlipDetails(Request $request){
		$response = array();
		$internals = Faker\Factory::create('en_US');
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
				'loading_slip_id' 			=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			if($this->checkApiAuth($request)){
				$loading_slip = \App\ProductLoading::with('token','master_rake','warehouse','product','labour_payment','from_warehouse','retailer')->where('id',$request->loading_slip_id)->first();
				if(!is_null($loading_slip)){
					$response['flag'] 		=   true;
					if($loading_slip->loading_slip_type == 1){
						$company = \App\Company::where('id',$loading_slip->token->company_id)->first();
						$company = $company;
					}else{
						$company = null;
					}
					$loading_slip->product_company_name = getModelById('ProductCompany',$loading_slip->product_company_id)->brand_name;
					$loading_slip->slip_generator_name = getModelById('User',$loading_slip->user_id)->name;
					$loading_slip->company = $company;
					$response['loading_slip'] = $loading_slip;
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Invalid Loading Slip Id";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}



	public function unloadingSlipDetails(Request $request){
		$response = array();
		$internals = Faker\Factory::create('en_US');
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
				'unloading_slip_id' 			=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());

		}else{
			if($this->checkApiAuth($request)){
				$loading_slip = \App\ProductUnloading::with('token','master_rake','product','unloading_labour_payment','warehouse')->where('id',$request->unloading_slip_id)->first();
				if(!is_null($loading_slip)){
					$response['flag'] 			= true;
					if($loading_slip->loading_slip_type == 1){
						$company = \App\Company::where('id',$loading_slip->token->company_id)->first();
						$company = $company;
					}else{
						$company = null;
					}
					$loading_slip->company = $company;
					$response['unloading_slip'] = $loading_slip;
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Invalid Unloading Slip Id";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}



	public function labourSlipDetails(Request $request){
		$response = array();
		$internals = Faker\Factory::create('en_US');
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
				'labour_payment_id' 			=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			if($this->checkApiAuth($request)){
				$labour_payment = \App\LabourPayments::with('token','master_rake','product')->where('id',$request->labour_payment_id)->first();
				if(!is_null($labour_payment)){
					$response['flag'] 			= true;
					$response['labour_payment'] = $labour_payment;
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Invalid Labour Payment Id";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}

	public function payLabour(Request $request){
		$response = array();
		$internals = Faker\Factory::create('en_US');
		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
				'labour_payment_id' 			=>'required',
				'paid_amount' 			=>'required',
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		}else{
			if($this->checkApiAuth($request)){
				$labour_payment = \App\LabourPayments::with('token','master_rake','product')->where('id',$request->labour_payment_id)->first();
				if(!is_null($labour_payment)){
					if($labour_payment->is_paid){
						$response['flag'] 			= true;
						$response['message'] = "Payment Already done for this slip";
					}else{
						$user = \App\User::where('api_token',$request->api_token)->first();
						$labour_payment->is_paid = 1;
						$labour_payment->paid_by = $user->id;
						$labour_payment->paid_amount = $labour_payment->paid_amount + $request->paid_amount;
						$labour_payment->payment_date = date('Y-m-d H:i:s');
						if($labour_payment->save()){
							$response['flag'] 			= true;
							$response['message'] 		= "Payment Details Saved Successfully";
						}else{
							$response['flag'] 			= false;
							$response['message'] 		= "Invalid Labour Slip";
						}
					}
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Invalid Labour Slip";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
				$response['is_token_expired'] = true;
			}
		}
		return response()->json($response);
	}

/*
 * Function to pay freight
 */
public function payFreight(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'loading_slip_id' 			=>'required',
			'paid_amount' 			=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$product_loading =  \App\ProductLoading::find($request->loading_slip_id);
			if(!is_null($product_loading)){
				if($product_loading->is_freight_paid){
					$response['flag'] 			= true;
					$response['message'] = "Payment Already done for this slip";
				}else{
					$user = \App\User::where('api_token',$request->api_token)->first();
					$product_loading->qr_scan_count = 2;
					$product_loading->processing_step = 2;
					$product_loading->is_freight_paid = 1;
					$product_loading->freight_paid_amount = $request->paid_amount;
					$product_loading->freight_pay_date = date('Y-m-d H:i:s');
					$product_loading->freight_paid_by = $user->id;
					if($product_loading->save()){
						$response['flag'] = true;
						$response['message'] = "Freight Paid Successfully.";
					}else{
						$response['flag'] = false;
						$response['error'] = "Something Went Wrong";
					}
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Loading Slip Not found";
			}
		} else {
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function directLabourPayment(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' =>'required',
			'labour_name'=>'required',
			'amount'=>'required',
			'description'=> 'required'
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			if($request->master_rake_id || $request->warehouse_id){

				$user = \App\User::where('api_token',$request->api_token)->first();

				$directlabour = new \App\DirectLabourPayment();
				$directlabour->master_rake_id=$request->master_rake_id;
				$directlabour->warehouse_id=$request->warehouse_id;
				$directlabour->labour_name=$request->labour_name;
				$directlabour->amount=$request->amount;
				$directlabour->description=$request->description;
				$directlabour->user_id = $user->id;
				if($directlabour->save())
				{
					$response['flag'] = true;
					$response['message'] = "Payment Generated Successfully";
					$direct_labour_payment = \App\DirectLabourPayment::with('master_rake','warehouse')->where('id',$directlabour->id)->first();
					$direct_labour_payment->slip_generator_name = getModelById('User',$direct_labour_payment->user_id)->name;
					$response['direct_labour_payment'] = $direct_labour_payment;

				}
				else{
					$response['flag'] = false;
					$response['errors'] = "Something Went Wrong";
				}
			}else
			{
				$response['flag'] = false;
				$response['errors'] = "Please Select Rake or Warehouse first";
			}
		} else
		{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}



public function directLabourPaymentSlipDetails(Request $request){
	$response = array();
	$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'labour_payment_id' 			=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$direct_labour_payment = \App\DirectLabourPayment::with('master_rake','warehouse')->where('id',$request->labour_payment_id)->first();
			if(!is_null($direct_labour_payment)){
				$response['flag'] 			= true;
				$direct_labour_payment->slip_generator_name = getModelById('User',$direct_labour_payment->user_id)->name;
				$response['direct_labour_payment'] = $direct_labour_payment;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Labour Payment Id";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function applicationModules(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());

	}else{
		if($this->checkApiAuth($request)){
			$date = date('Y-m-d');
			if($request->date){
				$date = $request->date;
			}
			$modules = \App\ApplicationModule::where('is_active',1)->get();
			$response['flag'] 			= true;
			$response['modules'] = $modules;
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function parseErrorResponse($errors){
	$response = []; 
	foreach ($errors->toArray() as $key => $value) { 
		$obj = new \stdClass(); 
		$obj->message = $value[0];
		array_push($response, $obj); 
	}
	return $response;
}

public function checkDealerSmSData(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		$companyDis = \App\CompanyDi::where('is_paid', 0)->get();
		foreach ($companyDis as $companyDi) {
			$date_diff=date_diff($companyDi->due_date ,date('Y-m-d'));
			if($date_diff==7)
			{
				$mobile_number = \App\Dealer::where('id', $companyDi->dealer_id)->first('mobile_number');
				$message = "Your Payment is pending from  " . $companyDi->due_date . " the amount is " . $companyDi->total . "Please  pay  it";
				$response = SmsController::sendSms($mobile_number, $message);
				$response['flag']=true;
				$response['message']=$response;
			} else {
				$response['flag']=false;
				$response['message']='Something Went Wrong';
			}
		}
	}
	return response()->json($response);
}




public function wagonUnloading(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'master_rake_id' 		=>'required',
			'wagon_number' 		=>'required',
			'product_id' 		=>'required',
			'quantity' 		=>'required',
			'labour_name' 		=>'required',
			'labour_rate' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$master_rake = \App\MasterRake::where('id',$request->master_rake_id)->first();
			if(!$master_rake->is_closed){
				$user = \App\User::where('api_token',$request->api_token)->first();
				$wagon_unloading = new \App\WagonUnloading();
				$wagon_unloading->master_rake_id = $request->master_rake_id;
				$wagon_unloading->product_id = $request->product_id;
				$wagon_unloading->quantity = $request->quantity;
				$wagon_unloading->wagon_number = $request->wagon_number;
				$wagon_unloading->labour_name = $request->labour_name;
				$wagon_unloading->wagon_rate = $request->labour_rate;
				$wagon_unloading->unloaded_by = $user->id;
				if($wagon_unloading->save()){
					$response['flag'] = true;
					$response['message'] = "unloaded successfully";
					$response['wagon_unloading_id'] = $wagon_unloading->id;
				}else{
					$response['flag'] = false;
					$response['message'] = "Something Went Wrong";
				}

			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "रेक बंद हो चुकी है ";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function wagonUnloadings(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
// if($this->checkApiAuth($request)){
		if(1){
			$query = \App\WagonUnloading::query();
			if($request->master_rake_id){
				$query->where('master_rake_id',$request->master_rake_id);
			}
			$wagon_unloadings = $query->select('id','master_rake_id','product_id','wagon_number','labour_name','wagon_rate','quantity','unloaded_by','is_paid','paid_amount')->with('master_rake:id,name','product:id,name,hindi_name','user:id,name')->get();
			$response['flag'] = true;
			$response['wagon_unloadings'] = $wagon_unloadings;
			if(count($wagon_unloadings) == 0){
				$response['error_image'] = url("assets/mobile/empty_result.png");
			}

		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}

	}
	return response()->json($response);
}


public function wagonUnloadingDetails(Request $request){
	$response = array();
	$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'wagon_unloading_id' 			=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$wagon_loading = \App\WagonUnloading::with('master_rake','product')->where('id',$request->wagon_unloading_id)->first();
			if(!is_null($wagon_loading)){
				$response['flag'] 			= true;
				$wagon_loading->slip_generator_name = getModelById('User',$wagon_loading->unloaded_by)->name;
				$response['wagon_loading'] = $wagon_loading;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid  Id";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function rakeTotalTokenLoading(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			// 'api_token' 		=>'required',
			'master_rake_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		$products = array();
		$updated_companies = array();
		$companies = \App\Company::where('is_active', 1)->get();
		foreach ($companies as $company) {
			$product_ids = \App\MasterRakeProduct::where('master_rake_id',$request->master_rake_id)->pluck('product_id');
			$count_data = array();
			foreach ($product_ids as $product_id) {
				$product = array();	
				$tokens = \App\Token::where('master_rake_id',$request->master_rake_id)
				->where('company_id',$company->id)
				->where('product_id',$product_id)
				->sum('quantity');

				$token_ids = \App\Token::where('master_rake_id',$request->master_rake_id)
				->where('product_id',$product_id)
				->where('company_id',$company->id)
				->pluck('id');

				$loadings = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)
				->whereIn('token_id',$token_ids)
				->sum('quantity');
				$product['product'] = getModelById('Product',$product_id)->name;
				$product['product_id'] = $product_id;
				$product['total_token_quantity'] = $tokens;
				$product['total_loading_quantity'] = $loadings;
				array_push($count_data , $product);
				array_push($products  , getModelById('Product',$product_id)->name);
			}
			$company->count_data = $count_data;
			array_push($updated_companies, $company);
		}
		$response['flag'] = true;
		$response['companies'] = $updated_companies;
		$response['products'] = array_unique($products) ;

	}
	return response()->json($response);
}


public function getRakeTokens(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'master_rake_id' 		=>'required',
			'company_id' 		=>'required',
			'product_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		$tokens = \App\Token::where('master_rake_id',$request->master_rake_id)->where('company_id',$request->company_id)->where('product_id',$request->product_id)->with('dealer:id,name,address1,hindi_name,hindi_address1','product:id,name,hindi_name','unit:id,unit,hindi_unit','transporter:id,name,hindi_name','retailer:id,name,hindi_name')->get();
		$response['flag'] = true;
		$response['tokens'] = $tokens;

	}
	return response()->json($response);
}

public function getRakeLoadings(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'master_rake_id' 		=>'required',
			'company_id' 		=>'required',
			'product_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		$token_ids = \App\Token::where('master_rake_id',$request->master_rake_id)->where('company_id',$request->company_id)->where('product_id',$request->product_id)->pluck('id');
		$loadings = \App\ProductLoading::whereIn('token_id',$token_ids)->select('id','loading_slip_type','product_company_id','product_company_name','product_id','quantity','unit_id','retailer_id','dealer_id','warehouse_id','is_approved','recieved_quantity','transporter_id','truck_number','driver_number')->with('retailer:id,hindi_name,hindi_address','dealer:id,hindi_name,hindi_address1','product:id,hindi_name','unit:id,hindi_unit','warehouse:id,name,hindi_name','transporter:id,hindi_name','product_company:id,hindi_name,hindi_brand_name')->get();
		$response['flag'] = true;
		$response['loadings'] = $loadings;
		if(count($loadings) == 0){
			$response['error_image'] = url("assets/mobile/empty_result.png");
		}

	}
	return response()->json($response);
}


public function warehouseTotalTokenLoading(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'from_warehouse_id' 		=>'required',
			'date' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		$products = array();
		$updated_companies = array();
		$companies = \App\Company::where('is_active', 1)->get();
		foreach ($companies as $company) {
			$product_ids = \App\Token::whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->where('from_warehouse_id',$request->from_warehouse_id)->pluck('product_id')->toArray();
			$count_data = array();
			if(count($product_ids)){
				$product_ids = array_unique($product_ids);
				foreach ($product_ids as $product_id) {
					$product = array();	
					$tokens = \App\Token::whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->where('from_warehouse_id',$request->from_warehouse_id)
					->where('company_id',$company->id)
					->where('product_id',$product_id)
					->sum('quantity');

					$token_ids = \App\Token::whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->where('from_warehouse_id',$request->from_warehouse_id)
					->where('product_id',$product_id)
					->where('company_id',$company->id)
					->pluck('id');

					$loadings = \App\ProductLoading::whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->where('from_warehouse_id',$request->from_warehouse_id)
					->whereIn('token_id',$token_ids)
					->sum('quantity');
					$product['product'] = getModelById('Product',$product_id)->name;
					$product['product_id'] = $product_id;
					$product['total_token_quantity'] = $tokens;
					$product['total_loading_quantity'] = $loadings;
					array_push($count_data , $product);
					array_push($products  , getModelById('Product',$product_id)->name);
				}
			}
			$company->count_data = $count_data;
			array_push($updated_companies, $company);
		}
		$response['flag'] = true;
		$response['companies'] = $updated_companies;
		$response['products'] = array_unique($products) ;

	}
	return response()->json($response);
}



public function getWarehouseTokens(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'from_warehouse_id' 		=>'required',
			'company_id' 		=>'required',
			'product_id' 		=>'required',
			'date' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		$tokens = \App\Token::whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->where('from_warehouse_id',$request->from_warehouse_id)->where('company_id',$request->company_id)->where('product_id',$request->product_id)->with('dealer:id,name,address1,hindi_name,hindi_address1','product:id,name,hindi_name','unit:id,unit,hindi_unit','transporter:id,name,hindi_name','retailer:id,name,hindi_name')->get();
		$response['flag'] = true;
		$response['tokens'] = $tokens;

	}
	return response()->json($response);
}



public function getWarehouseLoadings(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'from_warehouse_id' 		=>'required',
			'company_id' 		=>'required',
			'product_id' 		=>'required',
			'date' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		$token_ids = \App\Token::whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->where('from_warehouse_id',$request->from_warehouse_id)->where('company_id',$request->company_id)->where('product_id',$request->product_id)->pluck('id');
		$loadings = \App\ProductLoading::whereIn('token_id',$token_ids)->select('id','loading_slip_type','product_company_id','product_company_name','product_id','quantity','unit_id','retailer_id','dealer_id','warehouse_id','is_approved','recieved_quantity','transporter_id','truck_number','driver_number')->with('retailer:id,hindi_name,hindi_address','dealer:id,hindi_name,hindi_address1','product:id,hindi_name','unit:id,hindi_unit','warehouse:id,name,hindi_name','transporter:id,hindi_name','product_company:id,hindi_name,hindi_brand_name')->get();
		$response['flag'] = true;
		$response['loadings'] = $loadings;
		if(count($loadings) == 0){
			$response['error_image'] = url("assets/mobile/empty_result.png");
		}

	}
	return response()->json($response);
}

public function rakeTokens(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'master_rake_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		$totals = 0;
		$rake_tokens = array();
		$query = \App\Token::query();
		$query->where('token_type',1)->where('master_rake_id',$request->master_rake_id)->select('id','unique_id','warehouse_id','retailer_id','dealer_id','rate','product_company_id','account_from_id','product_id','quantity','unit_id','transporter_id')->with('dealer:id,hindi_name,hindi_address1','product:id,hindi_name','product_company:id,hindi_brand_name','unit:id,hindi_unit','warehouse:id,hindi_name','transporter:id,hindi_name','retailer:id,hindi_name,hindi_name');
		if($request->date){
			$query->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)));
		}
		if($request->product_id){
			$query->where('product_id', $request->product_id);
		}
		if($request->user_id){
			$query->where('warehouse_keeper_id', $request->user_id);
		}
		$tokens = $query->orderBy('id','desc')->get();
		if(count($tokens)){
			foreach ($tokens as $token) {
				$token->total_loadings = \App\ProductLoading::where('token_id',$token->id)->sum('quantity');
				$totals = $totals+ $token->total_loadings ;
				array_push($rake_tokens, $token);
			}
		}
		$response['flag'] = true;
		$response['tokens'] = $rake_tokens;
		$response['totals'] = $totals;
		if(count($rake_tokens) == 0){
			$response['error_image'] = url("assets/mobile/empty_result.png");
		}

	}
	return response()->json($response);
}

public function warehouseTokens(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'warehouse_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		$warehouse_tokens = array();
		$query = \App\Token::query();
		$query->where('token_type',2)->where('from_warehouse_id',$request->warehouse_id)->select('id','unique_id','warehouse_id','retailer_id','dealer_id','rate','product_company_id','account_from_id','product_id','quantity','unit_id','transporter_id')->with('dealer:id,hindi_name,hindi_address1','product:id,hindi_name','product_company:id,hindi_brand_name','unit:id,hindi_unit','warehouse:id,hindi_name','transporter:id,hindi_name','retailer:id,hindi_name,hindi_name');
		if($request->date){
			$query->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)));
		}
		$tokens = $query->orderBy('id','desc')->get();

		if(count($tokens)){
			foreach ($tokens as $token) {
				$total_loadings = \App\ProductLoading::where('token_id',$token->id)->sum('quantity');
				$token->total_loadings = $total_loadings;
				if(!is_null($total_loadings)){
					array_push($warehouse_tokens, $token);
				}
			}
		}
		$response['flag'] = true;
		$response['tokens'] = $warehouse_tokens;
		if(count($warehouse_tokens) == 0){
			$response['error_image'] = url("assets/mobile/empty_result.png");
		}
	}
	return response()->json($response);
}

public function tokenLoadings(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'token_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{

		$loadings = \App\ProductLoading::where('token_id',$request->token_id)->with('dealer:id,hindi_name,hindi_address1','product:id,hindi_name','unit:id,hindi_unit','warehouse:id,hindi_name','transporter:id,hindi_name','retailer:id,hindi_name,hindi_name')->get();
		$response['flag'] = true;
		$response['loadings'] = $loadings;
		if(count($loadings) == 0){
			$response['error_image'] = url("assets/mobile/empty_result.png");
		}

	}
	return response()->json($response);
}


public function rakeUnloadings(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'master_rake_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{

		$loadings = \App\ProductLoading::whereNotNull('warehouse_id')->where('master_rake_id',$request->master_rake_id)->select('id','loading_slip_type','product_company_id','product_company_name','product_id','quantity','unit_id','dealer_id','warehouse_id','is_approved','recieved_quantity','transporter_id','truck_number','driver_number')->with('dealer:id,hindi_name,hindi_address1','product:id,hindi_name','unit:id,hindi_unit','warehouse:id,name,hindi_name','transporter:id,hindi_name','product_company:id,hindi_name,hindi_brand_name')->get();
		$response['flag'] = true;
		$response['loadings'] = $loadings;
		if(count($loadings) == 0){
			$response['error_image'] = url("assets/mobile/empty_result.png");
		}

	}
	return response()->json($response);
}



public function standardization(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'warehouse_id' 		=>'required',
			'open_product_brand_id' 		=>'required',
			'closed_product_brand_id' 		=>'required',
			'open_product_id' 		=>'required',
			'closed_product_id' 		=>'required',
			'open_quantity' 		=>'required',
			'packed_quantity' 		=>'required',
			'labour_name' 		=>'required',
			'labour_rate' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{

		if($this->checkApiAuth($request)){


			$open_query = \App\Inventory::query();
			if($request->dealer_id){
				$open_query->where('dealer_id',$request->dealer_id);
			}else{
				$open_query->where('product_company_id',$request->product_company_id);
			}
			$open_inventory = $open_query->where('warehouse_id',$request->warehouse_id)->where('product_brand_id',$request->open_product_brand_id)->where('product_id',$request->open_product_id)->first();

			if($open_inventory){

				$user = \App\User::where('api_token',$request->api_token)->first();
				$standardization = new \App\Standardization();
				$standardization->warehouse_id = $request->warehouse_id;
				$standardization->dealer_id = $request->dealer_id;
				$standardization->product_company_id = $request->product_company_id;
				if($request->product_company_id){
					$standardization->open_product_brand_id = $request->product_company_id;
				}else{
					$standardization->open_product_brand_id = $request->open_product_brand_id;
				}
				$standardization->closed_product_brand_id = $request->closed_product_brand_id;
				$standardization->open_product_id = $request->open_product_id;
				$standardization->closed_product_id = $request->closed_product_id;
				$standardization->open_quantity = $request->open_quantity;
				$standardization->packed_quantity = $request->packed_quantity;
				if($request->shooping_quantity){
					$standardization->shooping_quantity = $request->shooping_quantity;
				}
				$standardization->labour_name = $request->labour_name;
				$standardization->labour_rate = $request->labour_rate;
				$standardization->user_id = $user->id;
				if($standardization->save()){

					$open_inventory->quantity  = $open_inventory->quantity - $request->open_quantity;
					$open_inventory->save();


					$close_stock_query = \App\Inventory::query();
					if($request->dealer_id){
						$close_stock_query->where('dealer_id',$request->dealer_id);
					}else{
						$close_stock_query->where('product_company_id',$request->product_company_id);
					}

					$close_stock_inventory = $close_stock_query->where('warehouse_id',$request->warehouse_id)->where('product_brand_id',$request->closed_product_brand_id)->where('product_id',$request->closed_product_id)->first();

					if(!is_null($close_stock_inventory)){
						$close_stock_inventory->quantity  = $close_stock_inventory->quantity + $request->packed_quantity;
						$close_stock_inventory->save();
					}else{
						$inventory = new \App\Inventory;
						if($request->dealer_id){
							$inventory->dealer_id = $request->dealer_id;
						}else{
							$inventory->product_company_id = $request->product_company_id;
						}
						$inventory->product_brand_id = $request->closed_product_brand_id;
						$inventory->product_id = $request->closed_product_id;
						$inventory->warehouse_id = $request->warehouse_id;
						$inventory->quantity = $request->packed_quantity;
						$inventory->unit_id = $open_inventory->unit_id;
						$inventory->save();

					}
					/*------------update Excess-------------*/
					if(isset($request->shooping_quantity) && $request->shooping_quantity > 0){
						$excess = ($request->packed_quantity + $request->shooping_quantity) - $request->open_quantity ;
						if($excess > 0){
							$excess_inventory = new \App\OtherInventory;
							$excess_inventory->type = "Excess";
							$excess_inventory->dealer_id = 114;
							$excess_inventory->product_brand_id = $request->closed_product_brand_id;
							$excess_inventory->product_id = $request->closed_product_id;
							$excess_inventory->warehouse_id = $request->warehouse_id;
							$excess_inventory->quantity = $excess;
							$excess_inventory->unit_id = $open_inventory->unit_id;
							$excess_inventory->save();
						}
					}
					/*------------update Excess-------------*/

					/*------------update sweeping-------------*/
					if($request->shooping_quantity > 0){
						$temp_excess = ($request->packed_quantity + $request->shooping_quantity) - $request->open_quantity ;
						$remining_sweeping = $request->shooping_quantity-$temp_excess;
						$other_inventory = \App\OtherInventory::where('type','sweeping')->where('warehouse_id',$request->warehouse_id)->where('product_brand_id',$request->closed_product_brand_id)->where('product_id',$request->closed_product_id)->first();
						if(!is_null($other_inventory)){
							$other_inventory->quantity = $other_inventory->quantity + $remining_sweeping;
							$other_inventory->save();
						}else{
							$other_inventory = new \App\OtherInventory;
							$other_inventory->type = "sweeping";
							if($request->dealer_id){
								$other_inventory->dealer_id = $request->dealer_id;
							}else{
								$other_inventory->product_company_id = $request->product_company_id;
							}
							$other_inventory->product_brand_id = $request->closed_product_brand_id;
							$other_inventory->product_id = $request->closed_product_id;
							$other_inventory->warehouse_id = $request->warehouse_id;
							$other_inventory->quantity = $remining_sweeping;
							$other_inventory->unit_id = $open_inventory->unit_id;
							$other_inventory->save();
						}
						
					}
					/*------------update sweeping-------------*/
					
					$response['flag'] = true;
					$response['message'] = "Done successfully";
					$response['standardization_id'] = $standardization->id;
				}else{
					$response['flag'] = false;
					$response['message'] = "Something Went Wrong";
				}

			}else{
				$response['flag'] = false;
				$response['message'] = "Selected Party do not have Selected product of selected brand in selected warehouse";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}



public function standardizationDetails(Request $request){
	$response = array();
	$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'standardization_id' 			=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$standardization = \App\Standardization::with('product','warehouse','dealer','product_company','product_brand')->where('id',$request->standardization_id)->first();
			if(!is_null($standardization)){
				$response['flag'] 			= true;
				$response['standardization'] = $standardization;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid  Id";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}




public function invoice_types(Request $request){
	$response = array();
	$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$invoice_types = \App\InvoiceType::where('is_active',1)->get();
			if(!is_null($invoice_types)){
				$response['flag'] 			= true;
				$response['invoice_types'] 		= $invoice_types;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "No Invoice types";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}



public function loadingSlipInvoices(Request $request){
	$response = array();
	$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			if(isset($request->invoice_type)){
				$loading_slip_invoices = \App\LoadingSlipInvoice::select('id','invoice_number')->where('invoice_type',$request->invoice_type)->orderBy('id','desc')->get();
			}else{
				$loading_slip_invoices = \App\LoadingSlipInvoice::select('id','invoice_number')->orderBy('id','desc')->get();
			}
			if(!is_null($loading_slip_invoices)){
				$response['flag'] 			= true;
				$response['loading_slip_invoices'] 		= $loading_slip_invoices;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Invoice";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}



public function loadingSlipInvoiceDetails(Request $request){
	$response = array();
	$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'invoice_id' 			=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			$invoice = \App\LoadingSlipInvoice::where('id',$request->invoice_id)->select('id','invoice_number','retailer_id','retailer_name','dealer_id','product_id','loading_slip_id')->with('dealer:id,name,hindi_name,address1,hindi_address1','product:id,name,hindi_name','product_loading:id,transporter_id,unit_id,product_company_id')
			// ->where('is_paid',1)
			->first();
			if(!is_null($invoice)){
				$response['flag'] = true;
				$response['invoice'] = $invoice;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Invoice";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function receivedReturnedProduct(Request $request)
{
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'invoice_number' 	=>'required',
			'retailer_id' 		=>'required',
			'dealer_id' 		=>'required',
			'product_brand_id' 	=>'required',
			'product_id' 		=>'required',
			'returned_quantity' =>'required',
			'unit_id' 		=>'required',
			'warehouse_id' 		=>'required',
			'vehicle_number' 		=>'required',
			'labour_name' 		=>'required',
			'labour_rate' 		=>'required',
		),
		array(
			'invoice_number.required'=>'इनवॉइस नंबर चुनें ',
			'retailer_id.required'=>'रिटेलर चुनें',
			'dealer_id.required'=>'डीलर चुनें',
			'product_brand_id.required'=>'प्रोडक्ट ब्रांड चुनें',
			'product_id.required'=>'प्रोडक्ट चुनें',
			'returned_quantity.required'=>'वापस मात्रा डालें',
			'unit_id.required'=>'यूनिट चुनें ',
			'warehouse_id.required'=>'वेयरहाउस चुनें',
			'vehicle_number.required'=>'ट्रक नंबर डालें',
			'labour_name.required'=>'लेबर नाम डालें',
			'labour_rate.required'=>'लेबर रेट डालें',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($request->returned_quantity > 0){
			$invoice = \App\LoadingSlipInvoice::where('id',$request->invoice_number)->select('id','invoice_number','retailer_id','retailer_name','dealer_id','product_id','loading_slip_id','rate')->with('product_loading:id,quantity,transporter_id,unit_id,product_company_id')
			->first();
			if($request->returned_quantity <= $invoice->product_loading->quantity){
				$user = \App\User::where('api_token',$request->api_token)->first();
				if(!is_null($user)){
					$return_product = new \App\ReturnedProduct();
					$return_product->invoice_number = $invoice->invoice_number;
					$return_product->retailer_id = $request->retailer_id;
					$return_product->dealer_id = $request->dealer_id;
					$return_product->product_company_id = $request->product_company_id;
					$return_product->product_brand_id = $request->product_brand_id;
					$return_product->product_id = $request->product_id;
					$return_product->returned_quantity = $request->returned_quantity;
					$return_product->warehouse_id = $request->warehouse_id;
					$return_product->vehicle_number = $request->vehicle_number;
					$return_product->transporter_id = $request->transporter_id;
					$return_product->unit_id = $invoice->product_loading->unit_id;
					$return_product->freight = $request->freight;
					$return_product->labour_name = $request->labour_name;
					$return_product->labour_rate = $request->labour_rate;
					$return_product->user_id = $user->id;
					if($return_product->save()){


						$inventory = \App\Inventory::where('dealer_id',$request->dealer_id)->where('warehouse_id',$request->warehouse_id)->where('product_brand_id',$request->product_brand_id)->where('product_id',$request->product_id)->first();
						if(!is_null($inventory)){
							$inventory->quantity  = $inventory->quantity + $request->returned_quantity;
							$inventory->save();
						}else{
							$inventory = new \App\Inventory();
							$inventory->dealer_id  = $request->dealer_id;
							$inventory->warehouse_id  = $request->warehouse_id;
							$inventory->product_brand_id  = $request->product_brand_id;
							$inventory->product_id  = $request->product_id;
							$inventory->unit_id = $request->unit_id;
							$inventory->quantity  =  $request->returned_quantity;
							$inventory->save();
						}

						$ledger = \App\PartyInvoiceLedger::where('retailer_id',$invoice->retailer_id)->orderBy('id','desc')->first();
						$perticular = "Returned Product Credit Against Invoice ".$invoice->invoice_number." ( ".$invoice->product->name." ) qty ( ".$request->returned_quantity." ) ";
						$return_amount = $request->returned_quantity*$invoice->rate;
						if(!is_null($ledger)){
							$balance = $ledger->balance;

							$ledger = new \App\PartyInvoiceLedger();
							$ledger->retailer_id = $invoice->retailer_id;
							$ledger->particular = $perticular;
							$ledger->credit = $return_amount;
							$ledger->debit = 0;
							$ledger->balance = $balance - $return_amount;
							$ledger->against = $invoice->invoice_number;
							$ledger->save();


						}else{
							$ledger = new \App\PartyInvoiceLedger();
							$ledger->retailer_id = $invoice->retailer_id;
							$ledger->particular = $perticular;
							$ledger->credit = $return_amount;
							$ledger->debit = 0;
							$ledger->balance = $return_amount;
							$ledger->against = $invoice->invoice_number;
							$ledger->save();
						}



						$response['flag'] = true;
						$response['message'] = "Done successfully";
						$response['return_id'] = $return_product->id;
					}else{
						$response['flag'] = false;
						$response['message'] = "Something Went Wrong";

					}
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
					$response['is_token_expired'] = true;
				}
			}else{
				$response['flag'] = false;
				$response['message'] = "रिटर्न्ड मात्रा लोडिंग मात्रा (".$invoice->product_loading->quantity ." ) से ज्यादा नहीं होनी चाहिए";
			}

		}else{
			$response['flag'] = false;
			$response['message'] = "वापस मात्रा 0 से ज्यादा होनी चाहिए ";
		}
		
	}
	return response()->json($response);
}


public function returnedProductDetails(Request $request){
	$response = array();
	$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'return_id' 			=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$returned_product = \App\ReturnedProduct::with('retailer','dealer','warehouse','product','product_brand','product_company')->where('id',$request->return_id)->first();
			if(!is_null($returned_product)){
				$response['flag'] 			= true;
				$response['returned_product'] = $returned_product;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid  Id";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function rakeFinancialDetails(Request $request){
	$response = array();
	$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 	=>'required',
			'master_rake_id' 	=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){

			$user = \App\User::where('api_token',$request->api_token)->first();

			$master_rake = \App\MasterRake::where('id',$request->master_rake_id)->first();
			if(!is_null($master_rake)){

				$totals = array();


				$total_tokens['name'] = $user->role_id == 1 ? "Total Token":'टोटल टोकन ';
				$total_tokens['key'] = 'tokens';
				$total_tokens['total'] = \App\Token::where('master_rake_id',$request->master_rake_id)->sum('quantity');

				$total_loadings['name'] = $user->role_id == 1 ? "Total Loadings":'टोटल लोडिंग्स';
				$total_loadings['key'] = 'loadings';
				$total_loadings['total'] = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->sum('quantity');

				$total_direct_labour_payments = array();
				$total_direct_labour_payments['icon'] = URL('/assets/mobile/rupee.png');
				$total_direct_labour_payments['name'] = $user->role_id == 1 ? "Total Direct Labour Payment":'टोटल डायरेक्ट लेबर पेमेंट्स';
				$total_direct_labour_payments['key'] = 'direct_labour_payments';
				$total_direct_labour_payments['total'] = \App\DirectLabourPayment::where('master_rake_id',$request->master_rake_id)->sum('amount');

				$total_wagon_unloadings = array();
				$total_wagon_unloadings['icon'] = URL('/assets/mobile/rupee.png');

				$total_wagon_unloadings['name'] = $user->role_id == 1 ? "Total wagon unloading":"टोटल वैगन अनलोडिंग";
				$total_wagon_unloadings['key'] = "wagon_unloadings";
				$total_wagon_unloadings['total'] = \App\WagonUnloading::where('master_rake_id',$request->master_rake_id)->sum('paid_amount');

				$total_freight_payments = array();
				$total_freight_payments['icon'] = URL('/assets/mobile/rupee.png');

				$total_freight_payments['name'] = $user->role_id == 1 ? "Total Freight Payment":"टोटल फ्रेट पेमेंट्स";
				$total_freight_payments['key'] = "freight_payments";
				$total_freight_payments['total'] = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->sum('freight_paid_amount');

				$total_labour_payments = array();
				$total_labour_payments['icon'] = URL('/assets/mobile/rupee.png');

				$total_labour_payments['name'] = $user->role_id == 1 ? "Total Labour Payment":"टोटल लेबर पेमेंट्स";
				$total_labour_payments['key'] ="labour_payments";
				$total_labour_payments['total'] = \App\LabourPayments::where('master_rake_id',$request->master_rake_id)->sum('paid_amount');	

				$total_unloading_labour_payments = array();
				$total_unloading_labour_payments['icon'] = URL('/assets/mobile/rupee.png');

				$total_unloading_labour_payments['name'] = $user->role_id == 1 ? "Unloading Labour Payment":"अनलोडिंग  लेबर पेमेंट्स";
				$total_unloading_labour_payments['key'] ="unloading_labour_payments";
				$total_unloading_labour_payments['total'] = \App\UnloadingLabourPayment::where('master_rake_id',$request->master_rake_id)->sum('paid_amount');

				$total_rr = array();
				$total_rr['icon'] = URL('/assets/mobile/wagon.png');

				$total_rr['name'] = $user->role_id == 1 ? "RR Quantity":"RR मात्रा";

				$total_rake_quantity  = \App\MasterRakeProduct::where('master_rake_id',$master_rake->id)->sum('quantity');
				$total_rake_shortage  = \App\MasterRakeProduct::where('master_rake_id',$master_rake->id)->sum('shortage_from_company');
				$total_product_loadings = \App\ProductLoading::where('master_rake_id',$master_rake->id)
				->whereNull('from_warehouse_id')
				->sum('quantity');

				$excess_shortage =  $total_product_loadings - ($total_rake_quantity - $total_rake_shortage);

				$total_rr['total'] = $total_rake_quantity;


				$total_wharfage = array();
				$total_wharfage['icon'] = URL('/assets/mobile/rupee.png');

				$total_wharfage['name'] = $user->role_id == 1 ? "wharfage Charges":"व्हारफेज चार्जेज ";
				$total_wharfage['total'] = is_null($master_rake->wharfage) ? 0 : $master_rake->wharfage;

				$total_demurrage = array();
				$total_demurrage['icon'] = URL('/assets/mobile/rupee.png');

				$total_demurrage['name'] = $user->role_id == 1 ? "demurrage Charges":"डेम्रेज चार्जेज";
				$total_demurrage['total'] = is_null($master_rake->demurrage) ? 0 : $master_rake->demurrage;

				$total_expense['name'] = "Total";
				$total_expense['icon'] = URL('/assets/mobile/rupee.png');
				$total_expense['total'] = $total_direct_labour_payments['total'] + $total_wagon_unloadings['total'] + $total_freight_payments['total'] + $total_labour_payments['total'] + $total_unloading_labour_payments['total'] + $total_wharfage['total'] + $total_demurrage['total'];;

				array_push($totals,$total_expense);
				array_push($totals,$total_tokens);
				array_push($totals,$total_loadings);
				array_push($totals,$total_direct_labour_payments);
				array_push($totals,$total_wagon_unloadings);
				array_push($totals,$total_freight_payments);
				array_push($totals,$total_labour_payments);
				array_push($totals,$total_unloading_labour_payments);
				array_push($totals,$total_rr);
				array_push($totals,$total_wharfage);
				array_push($totals,$total_demurrage);

				$response['flag'] 			= true;
				$response['master_rake'] = $master_rake;
				$response['totals'] = $totals;
				$response['user'] = $user;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Rake Id";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function rakeSummaryDetails(Request $request){
	$response = array();
	$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 	=>'required',
			'master_rake_id' 	=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){

			$user = \App\User::where('api_token',$request->api_token)->first();

			$master_rake = \App\MasterRake::where('id',$request->master_rake_id)->first();
			if(!is_null($master_rake)){

				$totals = array();


				$totals['total_tokens']['name'] = $user->role_id == 1 ? "Total Token":'टोटल टोकन ';
				$totals['total_tokens']['key'] = 'tokens';
				$totals['total_tokens']['total'] = \App\Token::where('master_rake_id',$request->master_rake_id)->sum('quantity');

				$totals['total_loadings']['name'] = $user->role_id == 1 ? "Total Loadings":'टोटल लोडिंग्स';
				$totals['total_loadings']['key'] = 'loadings';
				$totals['total_loadings']['total'] = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->sum('quantity');


				
				$totals['total_direct_labour_payments']['icon'] = URL('/assets/mobile/rupee.png');
				$totals['total_direct_labour_payments']['name'] = $user->role_id == 1 ? "Total Direct Labour Payment":'टोटल डायरेक्ट लेबर पेमेंट्स';
				$totals['total_direct_labour_payments']['key'] = 'direct_labour_payments';
				$totals['total_direct_labour_payments']['total'] = \App\DirectLabourPayment::where('master_rake_id',$request->master_rake_id)->sum('amount');

	
				$totals['total_wagon_unloadings']['icon'] = URL('/assets/mobile/rupee.png');

				$totals['total_wagon_unloadings']['name'] = $user->role_id == 1 ? "Total wagon unloading":"टोटल वैगन अनलोडिंग";
				$totals['total_wagon_unloadings']['key'] = "wagon_unloadings";
				$totals['total_wagon_unloadings']['total'] = \App\WagonUnloading::where('master_rake_id',$request->master_rake_id)->sum('paid_amount');

				
				$totals['total_freight_payments']['icon'] = URL('/assets/mobile/rupee.png');

				$totals['total_freight_payments']['name'] = $user->role_id == 1 ? "Total Freight Payment":"टोटल फ्रेट पेमेंट्स";
				$totals['total_freight_payments']['key'] = "freight_payments";
				$totals['total_freight_payments']['total'] = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->sum('freight_paid_amount');

			
				$totals['total_labour_payments']['icon'] = URL('/assets/mobile/rupee.png');

				$totals['total_labour_payments']['name'] = $user->role_id == 1 ? "Total Labour Payment":"टोटल लेबर पेमेंट्स";
				$totals['total_labour_payments']['key'] ="labour_payments";
				$totals['total_labour_payments']['total'] = \App\LabourPayments::where('master_rake_id',$request->master_rake_id)->sum('paid_amount');	

			
				$totals['total_unloading_labour_payments']['icon'] = URL('/assets/mobile/rupee.png');

				$totals['total_unloading_labour_payments']['name'] = $user->role_id == 1 ? "Unloading Labour Payment":"अनलोडिंग  लेबर पेमेंट्स";
				$totals['total_unloading_labour_payments']['key'] ="unloading_labour_payments";
				$totals['total_unloading_labour_payments']['total'] = \App\UnloadingLabourPayment::where('master_rake_id',$request->master_rake_id)->sum('paid_amount');

			

				$totals['total_rr']['icon'] = URL('/assets/mobile/wagon.png');

				$totals['total_rr']['name'] = $user->role_id == 1 ? "RR Quantity":"RR मात्रा";

				$total_rake_quantity  = \App\MasterRakeProduct::where('master_rake_id',$master_rake->id)->sum('quantity');
				$total_rake_shortage  = \App\MasterRakeProduct::where('master_rake_id',$master_rake->id)->sum('shortage_from_company');
				$total_product_loadings = \App\ProductLoading::where('master_rake_id',$master_rake->id)
				->whereNull('from_warehouse_id')
				->sum('quantity');

				$excess_shortage =  $total_product_loadings - ($total_rake_quantity - $total_rake_shortage);

				$totals['total_rr']['total'] = $total_rake_quantity;


			
				$totals['total_wharfage']['icon'] = URL('/assets/mobile/rupee.png');

				$totals['total_wharfage']['name'] = $user->role_id == 1 ? "wharfage Charges":"व्हारफेज चार्जेज ";
				$totals['total_wharfage']['total'] = is_null($master_rake->wharfage) ? 0 : $master_rake->wharfage;

		
				$totals['total_demurrage']['icon'] = URL('/assets/mobile/rupee.png');

				$totals['total_demurrage']['name'] = $user->role_id == 1 ? "demurrage Charges":"डेम्रेज चार्जेज";
				$totals['total_demurrage']['total'] = is_null($master_rake->demurrage) ? 0 : $master_rake->demurrage;

				//$total_expense['name'] = "Total";
				//$total_expense['icon'] = URL('/assets/mobile/rupee.png');
				//$total_expense['total'] = $total_direct_labour_payments['total'] + $total_wagon_unloadings['total'] + $total_freight_payments['total'] + $total_labour_payments['total'] + $total_unloading_labour_payments['total'] + $total_wharfage['total'] + $total_demurrage['total'];;

				
				
				

				$response['flag'] 			= true;
				$response['master_rake'] = $master_rake;
				$response['totals'] = $totals;
				$response['user'] = $user;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Rake Id";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function newRakeSummaryDetails(Request $request){
	$response = array();
	$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 	=>'required',
			'master_rake_id' 	=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){

			$user = \App\User::where('api_token',$request->api_token)->first();

			$master_rake = \App\MasterRake::where('id',$request->master_rake_id)->first();
			if(!is_null($master_rake)){

				$total_rake_quantity  = \App\MasterRakeProduct::where('master_rake_id',$master_rake->id)->sum('quantity');
				$total_tokens = \App\Token::where('master_rake_id',$request->master_rake_id)->sum('quantity');

				$total_direct_labour_payments = \App\DirectLabourPayment::where('master_rake_id',$request->master_rake_id)->sum('amount');

				$total_wagon_unloadings = \App\WagonUnloading::where('master_rake_id',$request->master_rake_id)->sum('paid_amount');

				$total_labour_payments = \App\LabourPayments::where('master_rake_id',$request->master_rake_id)->sum('paid_amount');
			
				$total_unloading_labour_payments = \App\UnloadingLabourPayment::where('master_rake_id',$request->master_rake_id)->sum('paid_amount');
			
				$total_freight_payments = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->sum('freight_paid_amount');

				$total_expenses = $total_direct_labour_payments + $total_labour_payments + $total_unloading_labour_payments +  $total_wagon_unloadings + $total_freight_payments;
				$total_loaded_bags =  \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->sum('quantity');
				$stock_moved_to_warehouse =  \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->where('loading_slip_type',2)->sum('quantity');

				$total_loading_done = \DB::table('product_unloadings')->where('master_rake_id',$request->master_rake_id)->sum('quantity');

				$total_sale_quantity =  \App\Token::where('master_rake_id',$request->master_rake_id)->where('to_type',2)->sum('quantity');
				$total_sale_price =  \App\Token::where('master_rake_id',$request->master_rake_id)->where('to_type',2)->sum('rate');

				$average_rate_of_sale = $total_sale_price/$total_sale_quantity;

				$totals = array();
				$totals['total_rr']['name'] = $user->role_id == 1 ? "Total RR":'टोटल RR ';
				$totals['total_rr']['key'] = 'total_rr';
				$totals['total_rr']['total'] = $total_rake_quantity;

				$totals['total_tokens']['name'] = $user->role_id == 1 ? "Total Token":'टोटल टोकन ';
				$totals['total_tokens']['key'] = 'total_tokens';
				$totals['total_tokens']['total'] = $total_tokens;

				$totals['total_loaded_bags']['name'] = $user->role_id == 1 ? "Total Loaded Bags":'लोडेड बैग';
				$totals['total_loaded_bags']['key'] = 'total_loaded_bags';
				$totals['total_loaded_bags']['total'] = $total_loaded_bags;

				$totals['total_loading_done']['name'] = $user->role_id == 1 ? "Total Loading Done":'लोडिंग हो चुकी है';
				$totals['total_loading_done']['key'] = 'total_loading_done';
				$totals['total_loading_done']['total'] = $total_loading_done;


				$totals['stock_moved_to_warehouse']['name'] = $user->role_id == 1 ? "Stock Moved to warehouse (direct)":'वेयरहाउस पहुँचा हुआ स्टॉक (डायरेक्ट)';
				$totals['stock_moved_to_warehouse']['key'] = 'stock_moved_to_warehouse';
				$totals['stock_moved_to_warehouse']['total'] = $stock_moved_to_warehouse;


				$totals['average_rate_of_sale']['name'] = $user->role_id == 1 ? "Average Rate of Sale":'बिक्री की औसत दर';
				$totals['average_rate_of_sale']['key'] = 'average_rate_of_sale';
				$totals['average_rate_of_sale']['total'] = round($average_rate_of_sale);

				$totals['total_expenses']['name'] = $user->role_id == 1 ? "Total Expenses":'कुल खर्च';
				$totals['total_expenses']['key'] = 'total_expenses';
				$totals['total_expenses']['total'] = $total_expenses;
				

				$response['flag'] 			= true;

				$response['totals'] = $totals;

			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Rake Id";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function tokenListRake(Request $request){
	$response = array();
	$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 	=>'required',
			'master_rake_id' 	=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){

			$tokens = \DB::table('tokens')->join('transporters','transporters.id','tokens.transporter_id')->join('users','users.id','tokens.user_id')->join('products','products.id','tokens.product_id')->join('retailers','retailers.id','tokens.retailer_id')->where('tokens.is_active',1)->where('tokens.master_rake_id',$request->master_rake_id)->select('tokens.*','retailers.name as retailer_name','products.name as product_name','users.name as token_generate_by','transporters.name as transporter_name')
				->get();

			$response['flag'] 			= true;
			$response['tokens'] 		= $tokens;

		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}

	return response()->json($response);
}


public function rakeFinancialReport(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 	=>'required',
			'master_rake_id' =>'required',
			'type' 			=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			$master_rake = \App\MasterRake::where('id',$request->master_rake_id)->first();
			if(!is_null($master_rake)){
				if($request->type == "direct_labour_payments"){
					$response['direct_labour_payments'] = \App\DirectLabourPayment::where('master_rake_id',$request->master_rake_id)->select('labour_name','description','is_paid','paid_amount')->get();
				}else if($request->type == "wagon_unloadings"){
					$response['wagon_unloadings'] = \App\WagonUnloading::where('master_rake_id',$request->master_rake_id)->select('wagon_number','quantity','labour_name','wagon_rate','is_paid','paid_amount')->get();
				}else if($request->type == "freight_payments"){
					$response['freight_payments'] = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->select('transporter_name','truck_number','product_name','product_id','recieved_quantity','unit_name','is_freight_paid','freight_paid_amount')->with('product:id,hindi_name')->get();
				}else if($request->type == "labour_payments"){
					$response['labour_payments'] = \App\LabourPayments::where('master_rake_id',$request->master_rake_id)->select('labour_name','product_name','product_id','quantity','unit_name','is_paid','paid_amount')->with('product:id,hindi_name')->get();
				}else if($request->type == "unloading_labour_payments"){
					$response['unloading_labour_payments'] = \App\UnloadingLabourPayment::where('master_rake_id',$request->master_rake_id)->select('labour_name','product_name','product_id','quantity','unit_name','is_paid','paid_amount')->with('product:id,hindi_name')->get();
				}

			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Rake Id";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}



public function warehouseFinancialDetails(Request $request){
	$response = array();
	$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'warehouse_id' 			=>'required',
			'date' 			=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			$warehouse = \App\Warehouse::where('id',$request->warehouse_id)->first();
			if(!is_null($warehouse)){

				$totals = array();
				$total_direct_labour_payments = array();
				$total_direct_labour_payments['icon'] = URL('/assets/mobile/rupee.png');
				$total_direct_labour_payments['name'] = 'टोटल डायरेक्ट लेबर पेमेंट्स';
				$total_direct_labour_payments['key'] = 'direct_labour_payments';
				$total_direct_labour_payments['total'] = \App\DirectLabourPayment::where('warehouse_id',$request->warehouse_id)->where('is_paid',1)->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->sum('amount');


				$total_freight_payments = array();
				$total_freight_payments['icon'] = URL('/assets/mobile/rupee.png');
				$total_freight_payments['name'] = "टोटल फ्रेट पेमेंट्स";
				$total_freight_payments['key'] = 'freight_payments';
				$total_freight_payments['total'] = \App\ProductLoading::where('from_warehouse_id',$request->warehouse_id)->where('is_freight_paid',1)->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->sum('freight_paid_amount');

				$total_labour_payments = array();
				$total_labour_payments['icon'] = URL('/assets/mobile/rupee.png');
				$total_labour_payments['name'] ="टोटल लेबर पेमेंट्स";
				$total_labour_payments['key'] = 'labour_payments';
				$total_labour_payments['total'] = \App\LabourPayments::where('from_warehouse_id',$request->warehouse_id)->where('is_paid',1)->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->sum('paid_amount');	

				$total_unloading_labour_payments = array();
				$total_unloading_labour_payments['icon'] = URL('/assets/mobile/rupee.png');
				$total_unloading_labour_payments['name'] ="अनलोडिंग लेबर पेमेंट्स";
				$total_unloading_labour_payments['key'] = 'unloading_labour_payments';
				$total_unloading_labour_payments['total'] = \App\UnloadingLabourPayment::where('warehouse_id',$request->warehouse_id)->where('is_paid',1)->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->sum('paid_amount');

				array_push($totals,$total_direct_labour_payments);
				array_push($totals,$total_freight_payments);
				array_push($totals,$total_labour_payments);
				array_push($totals,$total_unloading_labour_payments);

				$response['flag'] 			= true;
				$response['warehouse'] = $warehouse;
				$response['totals'] = $totals;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Warehouse Id";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}



public function warehouseFinancialReport(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 	=>'required',
			'warehouse_id' =>'required',
			'type' 			=>'required',
			'date' 			=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			$warehouse = \App\Warehouse::where('id',$request->warehouse_id)->first();
			if(!is_null($warehouse)){
				if($request->type == "direct_labour_payments"){
					$response['direct_labour_payments'] = \App\DirectLabourPayment::where('warehouse_id',$request->warehouse_id)->select('labour_name','description','is_paid','paid_amount')->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->get();
				}else if($request->type == "freight_payments"){
					$response['freight_payments'] = \App\ProductLoading::where('from_warehouse_id',$request->warehouse_id)->where('is_freight_paid',1)->select('transporter_name','truck_number','product_name','product_id','recieved_quantity','unit_name','freight_paid_amount')->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->with('product:id,hindi_name')->get();
				}else if($request->type == "labour_payments"){
					$response['labour_payments'] = \App\LabourPayments::where('from_warehouse_id',$request->warehouse_id)->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->select('labour_name','product_name','product_id','quantity','unit_name','is_paid','paid_amount')->with('product:id,hindi_name')->get();
				}else if($request->type == "unloading_labour_payments"){
					$response['unloading_labour_payments'] = \App\UnloadingLabourPayment::where('warehouse_id',$request->warehouse_id)->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->select('labour_name','product_name','product_id','quantity','unit_name','is_paid','paid_amount')->with('product:id,hindi_name')->get();
				}

			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Rake Id";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function WarehouseTransferLoading(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'product_brand_id' 	=>'required',
			'product_id' 		=>'required',
			'quantity' 			=>'required',
			'unit_id' 			=>'required',
			'from_warehouse_id' =>'required',
			'to_warehouse_id' 	=>'required|different:from_warehouse_id',
			'transporter_id' 	=>'required',
			'truck_number' 		=>'required',
			// 'freight' 			=>'required',
			'labour_name' 		=>'required',
			'labour_rate' 		=>'required',
		),
		array(
			'api_token.required'=>'सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ',
			'product_brand_id.required'=>'प्रोडक्ट ब्रांड चुनें',
			'product_id.required'=>'प्रोडक्ट चुनें',
			'quantity.required'=>'वापस मात्रा डालें',
			'unit_id.required'=>'यूनिट चुनें ',
			'from_warehouse_id.required'=>'फ्रॉम वेयरहाउस चुनें',
			'to_warehouse_id.required'=>'टू वेयरहाउस चुनें',
			'to_warehouse_id.different'=>'दोनों गोडाउन अलग अलग होना चाहिए',
			'transporter_id.required'=>'ट्रांसपोर्टर चुनें',
			'truck_number.required'=>'ट्रक नंबर डालें',
			// 'freight.required'=>'फ्रेट डालें',
			'labour_name.required'=>'लेबर नाम डालें',
			'labour_rate.required'=>'लेबर रेट डालें',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$query = \App\Inventory::where('warehouse_id',$request->from_warehouse_id)
			->where('product_id',$request->product_id)
			->where('product_brand_id',$request->product_brand_id)
			->where('unit_id',$request->unit_id);
			$inventory = $query->first();
			$total_stock = $query->sum('quantity');

			if(!is_null($inventory)){
				if($total_stock >= $request->quantity){
					$user = \App\User::where('api_token',$request->api_token)->first();
					$warehouse_transfer_loading = new \App\WarehouseTransferLoading();
					$warehouse_transfer_loading->product_brand_id = $request->product_brand_id;
					$warehouse_transfer_loading->product_id = $request->product_id;
					$warehouse_transfer_loading->quantity = $request->quantity;
					$warehouse_transfer_loading->unit_id = $request->unit_id;
					$warehouse_transfer_loading->from_warehouse_id = $request->from_warehouse_id;
					$warehouse_transfer_loading->to_warehouse_id = $request->to_warehouse_id;
					$warehouse_transfer_loading->transporter_id = $request->transporter_id;
					$warehouse_transfer_loading->freight = $request->freight;
					$warehouse_transfer_loading->truck_number = $request->truck_number;
					$warehouse_transfer_loading->labour_name = $request->labour_name;
					$warehouse_transfer_loading->labour_rate = $request->labour_rate;
					$warehouse_transfer_loading->user_id = $user->id;
					if($warehouse_transfer_loading->save()){
						$response['flag'] 			= true;
						$response['message'] 		= "ट्रांसफर सफलतापूर्वक हो चूका है";
						$response['warehouse_transfer_loading_id'] 		= $warehouse_transfer_loading->id;
					}else{
						$response['flag'] 			= false;
						$response['message'] 		= "कुछ गलत हुआ है ! कृपया दुबारा से प्रयास करें ";
					}

				}else{

					$response['flag'] 			= false;
					$response['message'] 		= "डाली गयी मात्रा (".$request->quantity.") गोडाउन स्टॉक (".$total_stock.") से ज्यादा नहीं होनी चाहिए ";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= getModelById('Warehouse',$request->from_warehouse_id)->hindi_name." में ".getModelById('Product',$request->product_id)->hindi_name." का कोई स्टॉक नहीं है ";
			}
			
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function WarehouseTransferLoadingList(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			$warehouse_transfer_loadings = \App\WarehouseTransferLoading::select("id")->get();
			$response['flag'] = true;
			$response['warehouse_transfer_loadings'] = $warehouse_transfer_loadings;
			
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function warehouseTransferLoadings(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'loading_warehouse_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			$query = \App\WarehouseTransferLoading::query();
			if($request->unloading_warehouse_id){
				$query->where('to_warehouse_id',$request->unloading_warehouse_id);
			}
			if($request->date){
				$query->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)));
			}
			$warehouse_transfer_unloadings = $query->where('from_warehouse_id',$request->loading_warehouse_id)->select("id","from_warehouse_id","to_warehouse_id","product_brand_id","product_id","quantity","unit_id","transporter_id","truck_number","labour_name","labour_rate","is_approved")->with('product:id,name,hindi_name','unit:id,unit','from_warehouse:id,hindi_name','to_warehouse:id,hindi_name','transporter:id,hindi_name','product_brand:id,hindi_brand_name')->get();
			
			$response['flag'] = true;
			$response['warehouse_transfer_unloadings'] = $warehouse_transfer_unloadings;
			if(count($warehouse_transfer_unloadings) == 0){
				$response['error_image'] = url("assets/mobile/empty_result.png");
			}
			
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}



public function warehouseTransferLoadingDetails(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'warehouse_transfer_loading_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			$warehouse_transfer_loading = \App\WarehouseTransferLoading::where('id',$request->warehouse_transfer_loading_id)->select("id","from_warehouse_id","to_warehouse_id","product_brand_id","product_id","quantity","unit_id","transporter_id","truck_number","labour_name","labour_rate")->with('product:id,name,hindi_name','unit:id,unit','from_warehouse:id,hindi_name','to_warehouse:id,hindi_name','transporter:id,hindi_name','product_brand:id,hindi_brand_name')->first();
			if(!is_null($warehouse_transfer_loading)){
				$response['flag'] = true;
				$response['warehouse_transfer'] = $warehouse_transfer_loading;
			}else{
				$response['flag'] 			= false;
				$response['message'] = "ऐसा कोई ट्रांसफर नहीं हुआ है|";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function WarehouseTransferUnloading(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'product_brand_id' 	=>'required',
			'warehouse_transfer_loading_id' 	=>'required',
			'product_id' 		=>'required',
			'quantity' 			=>'required',
			'unit_id' 			=>'required',
			'from_warehouse_id' =>'required',
			'to_warehouse_id' 	=>'required|different:from_warehouse_id',
			'labour_name' 		=>'required',
			'labour_rate' 		=>'required',
		),
		array(
			'api_token.required'=>'सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ',
			'warehouse_transfer_loading_id.required'=>'वेयरहाउस लोडिंग चुनें',
			'product_brand_id.required'=>'प्रोडक्ट ब्रांड चुनें',
			'product_id.required'=>'प्रोडक्ट चुनें',
			'quantity.required'=>'वापस मात्रा डालें',
			'unit_id.required'=>'यूनिट चुनें ',
			'from_warehouse_id.required'=>'फ्रॉम वेयरहाउस चुनें',
			'to_warehouse_id.required'=>'टू वेयरहाउस चुनें',
			'to_warehouse_id.different'=>'दोनों गोडाउन अलग अलग होना चाहिए',
			'labour_name.required'=>'लेबर नाम डालें',
			'labour_rate.required'=>'लेबर रेट डालें',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){


			$warehouse_transfer_loading = \App\WarehouseTransferLoading::where('id',$request->warehouse_transfer_loading_id)->first();
			if(!is_null($warehouse_transfer_loading)){

				if($warehouse_transfer_loading->is_approved == 0){

					$query = \App\Inventory::where('warehouse_id',$warehouse_transfer_loading->from_warehouse_id)
					->where('product_id',$warehouse_transfer_loading->product_id)
					->where('product_brand_id',$warehouse_transfer_loading->product_brand_id)
					->where('unit_id',$warehouse_transfer_loading->unit_id);
					$inventory = $query->first();
					$total_stock = $query->sum('quantity');

					if(!is_null($inventory)){
						if($total_stock >= $request->quantity){

							$higher_source_stock = \App\Inventory::where('warehouse_id',$warehouse_transfer_loading->from_warehouse_id)
							->where('product_id',$warehouse_transfer_loading->product_id)
							->where('product_brand_id',$warehouse_transfer_loading->product_brand_id)
							->where('unit_id',$warehouse_transfer_loading->unit_id)
							->where('quantity','>=',$request->quantity)
							->first();
							if(!is_null($higher_source_stock)){
								$higher_source_stock->quantity = $higher_source_stock->quantity - $request->quantity;
								$higher_source_stock->save();

								$destination_stock_query = \App\Inventory::query();
								$destination_stock_query->where('warehouse_id',$request->to_warehouse_id)
								->where('product_id',$warehouse_transfer_loading->product_id)
								->where('product_brand_id',$warehouse_transfer_loading->product_brand_id)
								->where('unit_id',$warehouse_transfer_loading->unit_id)
								->where('quantity','>=',$request->quantity);
								if(!is_null($higher_source_stock->dealer_id)){
									$destination_stock_query->where('dealer_id',$higher_source_stock->dealer_id);
								}else{
									$destination_stock_query->where('product_company_id',$higher_source_stock->product_company_id);
								}
								$destination_stock = $destination_stock_query->first();

								if(!is_null($destination_stock)){
									$destination_stock->quantity = $destination_stock->quantity + $request->quantity;
									$destination_stock->save();
								}else{

									$destination_stock = new \App\Inventory();
									if(!is_null($higher_source_stock->dealer_id)){
										$destination_stock->dealer_id = $higher_source_stock->dealer_id;
									}else{
										$destination_stock->product_company_id = $higher_source_stock->product_company_id;
									}

									$destination_stock->warehouse_id = $request->to_warehouse_id;
									$destination_stock->product_brand_id = $warehouse_transfer_loading->product_brand_id;
									$destination_stock->product_id = $warehouse_transfer_loading->product_id;
									$destination_stock->quantity = $request->quantity;
									$destination_stock->unit_id = $warehouse_transfer_loading->unit_id;
									$destination_stock->save();

								}

							}else{
								$stocks = \App\Inventory::where('warehouse_id',$warehouse_transfer_loading->from_warehouse_id)
								->where('product_id',$warehouse_transfer_loading->product_id)
								->where('product_brand_id',$warehouse_transfer_loading->product_brand_id)
								->where('unit_id',$warehouse_transfer_loading->unit_id)
								->where('quantity','>',0)
								->orderBy('quantity','desc')
								->get();

								$remaining_quantity = $request->quantity;
								$i = 0;
								foreach ($stocks as $stock) {
									if($remaining_quantity > 0){
										if($i == 0){
											$transfer_quantity = $stock->quantity;
										}else{
											$transfer_quantity = $remaining_quantity;
										}
										$stock->quantity = $stock->quantity - $transfer_quantity;
										$stock->save();

										$destination_stock_query = \App\Inventory::query();
										$destination_stock_query->where('warehouse_id',$request->to_warehouse_id)
										->where('product_id',$warehouse_transfer_loading->product_id)
										->where('product_brand_id',$warehouse_transfer_loading->product_brand_id)
										->where('unit_id',$warehouse_transfer_loading->unit_id)
										->where('quantity','>=',$transfer_quantity);
										if(!is_null($stock->dealer_id)){
											$destination_stock_query->where('dealer_id',$stock->dealer_id);
										}else{
											$destination_stock_query->where('product_company_id',$stock->product_company_id);
										}
										$destination_stock = $destination_stock_query->first();

										if(!is_null($destination_stock)){
											$destination_stock->quantity = $destination_stock->quantity + $transfer_quantity;
											$destination_stock->save();

											$remaining_quantity = $remaining_quantity - $transfer_quantity;
										}else{

											$destination_stock = new \App\Inventory();
											if(!is_null($stock->dealer_id)){
												$destination_stock->dealer_id = $stock->dealer_id;
											}else{
												$destination_stock->product_company_id = $stock->product_company_id;
											}

											$destination_stock->warehouse_id = $request->to_warehouse_id;
											$destination_stock->product_brand_id = $warehouse_transfer_loading->product_brand_id;
											$destination_stock->product_id = $warehouse_transfer_loading->product_id;
											$destination_stock->quantity = $transfer_quantity;
											$destination_stock->unit_id = $warehouse_transfer_loading->unit_id;
											$destination_stock->save();
											$remaining_quantity = $remaining_quantity - $transfer_quantity;
										}
									}
									$i++;
								}

							}

							$user = \App\User::where('api_token',$request->api_token)->first();
							$warehouse_transfer_unloading = new \App\WarehouseTransferUnloading();
							$warehouse_transfer_unloading->warehouse_transfer_loading_id = $warehouse_transfer_loading->id;
							$warehouse_transfer_unloading->product_brand_id = $warehouse_transfer_loading->product_brand_id;
							$warehouse_transfer_unloading->product_id = $warehouse_transfer_loading->product_id;
							$warehouse_transfer_unloading->quantity = $request->quantity;
							$warehouse_transfer_unloading->unit_id = $warehouse_transfer_loading->unit_id;
							$warehouse_transfer_unloading->from_warehouse_id = $warehouse_transfer_loading->from_warehouse_id;
							$warehouse_transfer_unloading->to_warehouse_id = $request->to_warehouse_id;
							$warehouse_transfer_unloading->labour_name = $request->labour_name;
							$warehouse_transfer_unloading->labour_rate = $request->labour_rate;
							$warehouse_transfer_unloading->user_id = $user->id;
							if($warehouse_transfer_unloading->save()){

								$warehouse_transfer_loading->is_approved = 1;
								$warehouse_transfer_loading->received_quantity = $request->quantity;
								$warehouse_transfer_loading->save();

								$response['flag'] 			= true;
								$response['message'] 		= "ट्रांसफर सफलतापूर्वक हो चूका है";
								$response['warehouse_transfer_unloading_id'] 		= $warehouse_transfer_unloading->id;
							}else{
								$response['flag'] 			= false;
								$response['message'] 		= "कुछ गलत हुआ है ! कृपया दुबारा से प्रयास करें ";
							}



						}else{

							$response['flag'] 			= false;
							$response['message'] 		= "डाली गयी मात्रा (".$request->quantity.") गोडाउन स्टॉक (".$total_stock.") से ज्यादा नहीं होनी चाहिए ";
						}
					}else{
						$response['flag'] 			= false;
						$response['message'] 		= getModelById('Warehouse',$request->from_warehouse_id)->hindi_name." में ".getModelById('Product',$request->product_id)->hindi_name." का कोई स्टॉक नहीं है ";
					}
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "यह लोडिंग पहले ही अनलोड हो चुकी है";
				}
				
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "कृपया सही वेयरहाउस लोडिंग चुनें";
			}
			
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function warehouseTransferUnloadingDetails(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'warehouse_transfer_unloading_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			$warehouse_transfer_unloading = \App\WarehouseTransferUnloading::where('id',$request->warehouse_transfer_unloading_id)->select("id","warehouse_transfer_loading_id","from_warehouse_id","to_warehouse_id","product_brand_id","product_id","quantity","unit_id","labour_name","labour_rate")->with('warehouse_transfer_loading:id,truck_number','product:id,name,hindi_name','unit:id,unit','from_warehouse:id,hindi_name','to_warehouse:id,hindi_name','product_brand:id,hindi_brand_name')->first();
			if(!is_null($warehouse_transfer_unloading)){
				$response['flag'] = true;
				$response['warehouse_transfer'] = $warehouse_transfer_unloading;
			}else{
				$response['flag'] 			= false;
				$response['message'] = "ऐसा कोई ट्रांसफर नहीं हुआ है|";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function warehouseTransferUnloadingList(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			$warehouse_transfer_unloading = \App\WarehouseTransferUnloading::select("id")->get();
			$response['flag'] = true;
			$response['warehouse_transfer'] = $warehouse_transfer_unloading;
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function warehouseTransferUnloadings(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'unloading_warehouse_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			$query = \App\WarehouseTransferUnloading::query();
			if($request->loading_warehouse_id){
				$query->where('from_warehouse_id',$request->loading_warehouse_id);
			}
			if($request->date){
				$query->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)));
			}
			$warehouse_transfer_unloadings = $query->where('to_warehouse_id',$request->unloading_warehouse_id)->select("id","warehouse_transfer_loading_id","from_warehouse_id","to_warehouse_id","product_brand_id","product_id","quantity","unit_id","labour_name","labour_rate")->with('warehouse_transfer_loading:id,truck_number','product:id,name,hindi_name','unit:id,unit','from_warehouse:id,hindi_name','to_warehouse:id,hindi_name','product_brand:id,hindi_brand_name')->get();
			
			$response['flag'] 			= true;
			$response['warehouse_transfer_unloadings'] = $warehouse_transfer_unloadings;
			if(count($warehouse_transfer_unloadings) == 0){
				$response['error_image'] = url("assets/mobile/empty_result.png");
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function dailySalesPurchase(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			if($request->date){
				$date = date('Y-m-d',strtotime($request->date));
			}else{
				$date = date('Y-m-d');
			}
			$sales = array();
			$unique_sale_parties = \App\LoadingSlipInvoice::select('dealer_id')->whereDate('created_at','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->distinct()->get();

			if(!is_null($unique_sale_parties)){
				foreach ($unique_sale_parties as $unique_sale_party) {
					$unique_products = \App\LoadingSlipInvoice::select('product_id')->where('dealer_id',$unique_sale_party->dealer_id)->whereDate('created_at','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->distinct()->get();
					foreach ($unique_products as $unique_product) {
						$tempArr = array();
						$tempArr['party'] = getModelById('Dealer',$unique_sale_party->dealer_id)->name;  
						$tempArr['product'] = getModelById('Product',$unique_product->product_id)->name;  
						$tempArr['total_sale_quantity'] = \App\LoadingSlipInvoice::where('product_id',$unique_product->product_id)->where('dealer_id',$unique_sale_party->dealer_id)->whereDate('created_at','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->sum('quantity');
						$tempArr['total_sale_amount'] = \App\LoadingSlipInvoice::where('product_id',$unique_product->product_id)->where('dealer_id',$unique_sale_party->dealer_id)->whereDate('created_at','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->sum('total');
						array_push($sales, $tempArr);
					}
				}
			}


			$purchase = array();
			$unique_purchase_c_companies = \App\CompanyDi::select('product_company_id')->whereDate('invoice_date','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->distinct()->get();

			$unique_purchase_w_companies = \App\WarehouseDi::select('product_company_id')->where('transfer_type',1)->whereDate('invoice_date','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->distinct()->get();
			$unique_purchase_w_parties = \App\WarehouseDi::select('from_dealer_id')->where('transfer_type',2)->whereDate('invoice_date','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->distinct()->get();


			if(!is_null($unique_purchase_c_companies)){
				foreach ($unique_purchase_c_companies as $unique_purchase_c_company) {
					$unique_products = \App\CompanyDi::select('product_id')->where('product_company_id',$unique_purchase_c_company->product_company_id)->whereDate('invoice_date','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->distinct()->get();
					foreach ($unique_products as $unique_product) {
						$tempArr = array();
						$tempArr['party'] = getModelById('ProductCompany',$unique_purchase_c_company->product_company_id)->name;  
						$tempArr['product'] = getModelById('Product',$unique_product->product_id)->name;  
						$tempArr['total_purchase_quantity'] = \App\CompanyDi::where('product_id',$unique_product->product_id)->where('product_company_id',$unique_purchase_c_company->product_company_id)->whereDate('invoice_date','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->sum('quantity');
						$tempArr['total_purchase_amount'] = \App\CompanyDi::where('product_id',$unique_product->product_id)->where('product_company_id',$unique_purchase_c_company->product_company_id)->whereDate('invoice_date','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->sum('total');
						array_push($purchase, $tempArr);
					}
				}
			}


			if(!is_null($unique_purchase_w_companies)){
				foreach ($unique_purchase_w_companies as $unique_company) {
					$unique_products = \App\WarehouseDi::select('product_id')->where('product_company_id',$unique_company->product_company_id)->whereDate('invoice_date','=',date('Y-m-d'))->orderBy('id','desc')->distinct()->get();
					foreach ($unique_products as $unique_product) {
						$tempArr = array();
						$tempArr['party'] = getModelById('ProductCompany',$unique_company->product_company_id)->name;  
						$tempArr['product'] = getModelById('Product',$unique_product->product_id)->name;  
						$tempArr['total_purchase_quantity'] = \App\WarehouseDi::where('product_id',$unique_product->product_id)->where('product_company_id',$unique_company->product_company_id)->whereDate('invoice_date','=',date('Y-m-d'))->orderBy('id','desc')->sum('quantity');
						$tempArr['total_purchase_amount'] = \App\WarehouseDi::where('product_id',$unique_product->product_id)->where('product_company_id',$unique_company->product_company_id)->whereDate('invoice_date','=',date('Y-m-d'))->orderBy('id','desc')->sum('total');
						array_push($purchase, $tempArr);
					}
				}
			}

			if(!is_null($unique_purchase_w_parties)){
				foreach ($unique_purchase_w_parties as $unique_purchase_party) {
					$unique_products = \App\WarehouseDi::select('product_id')->where('from_dealer_id',$unique_purchase_party->from_dealer_id)->whereDate('invoice_date','=',date('Y-m-d'))->orderBy('id','desc')->distinct()->get();
					foreach ($unique_products as $unique_product) {
						$tempArr = array();
						$tempArr['party'] = getModelById('Dealer',$unique_purchase_party->from_dealer_id)->name;  
						$tempArr['product'] = getModelById('Product',$unique_product->product_id)->name;  
						$tempArr['total_purchase_quantity'] = \App\WarehouseDi::where('product_id',$unique_product->product_id)->where('from_dealer_id',$unique_purchase_party->from_dealer_id)->whereDate('invoice_date','=',date('Y-m-d'))->orderBy('id','desc')->sum('quantity');
						$tempArr['total_purchase_amount'] = \App\WarehouseDi::where('product_id',$unique_product->product_id)->where('from_dealer_id',$unique_purchase_party->from_dealer_id)->whereDate('invoice_date','=',date('Y-m-d'))->orderBy('id','desc')->sum('total');
						array_push($purchase, $tempArr);
					}
				}
			}
			$response['flag'] 			= true;
			$response['sales'] = $sales;
			$response['purchase'] = $purchase;

		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function dailyWarehousePaymentReports(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			
			$daily_expense_reports =\App\DailyWarehouseExpenseReport::with('user:id,name')->get();
			
			$response['flag'] 			= true;
			$response['daily_expense_reports'] = $daily_expense_reports;
			if(count($daily_expense_reports) == 0){
				$response['error_image'] = url("assets/mobile/empty_result.png");
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}



public function rakePaymentReports(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			
			$daily_expense_reports =\App\RakeExpenseReport::with('user:id,name')->get();
			
			$response['flag'] 			= true;
			$response['daily_expense_reports'] = $daily_expense_reports;
			if(count($daily_expense_reports) == 0){
				$response['error_image'] = url("assets/mobile/empty_result.png");
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function getPartyStock(Request $request){
	try{
		$response = array();

		$validator = \Validator::make($request->all(),
			array(
				'api_token' 		=>'required',
				'status' 		=>'required',
				'id' 		=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
		} else{
			if(1){
				$cement_product_ids = \App\Product::where('product_category_id',2)->pluck('id');
				$response['cement_stock'] = \App\Inventory::where($request->status,$request->id)->whereIn('product_id',$cement_product_ids)->sum('quantity');
				$response['dap_stock'] = \App\Inventory::where($request->status,$request->id)->where('product_id',4)->sum('quantity');
				$response['npk_stock'] = \App\Inventory::where($request->status,$request->id)->whereIn('product_id',array(9,10))->sum('quantity');
				$response['urea_stock'] = \App\Inventory::where($request->status,$request->id)->where('product_id',19)->sum('quantity');
				$response['mop_stock'] = \App\Inventory::where($request->status,$request->id)->where('product_id',7)->sum('quantity');
			}
			return response()->json($response);
		}
	}
//catch exception
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
public function approveRakeExpenseReport(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'report_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			$daily_expense_report = \App\RakeExpenseReport::where('id',$request->report_id)->first();
			if(!is_null($daily_expense_report)){
				$daily_expense_report->first_approval = 0;
				$daily_expense_report->second_approval = 0;
				$daily_expense_report->third_approval = 0;
				$daily_expense_report->final_approval = 1;
				$daily_expense_report->save();
				if($daily_expense_report->save()){
					$response['flag'] 			= true;
					$response['message'] 		= "Report Approved Successfully ";
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Something Went Wrong ";
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Report Not found";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function approveDailyWarehouseExpenseReport(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'report_id' 		=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		// if($this->checkApiAuth($request)){
		if(1){
			$daily_expense_report = \App\DailyWarehouseExpenseReport::where('id',$request->report_id)->first();
			if(!is_null($daily_expense_report)){
				$daily_expense_report->first_approval = 0;
				$daily_expense_report->second_approval = 0;
				$daily_expense_report->third_approval = 0;
				$daily_expense_report->final_approval = 1;
				$daily_expense_report->save();


				if($daily_expense_report->save()){
					
					$response['flag'] 			= true;
					$response['message'] 		= "Report Approved Successfully ";
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Something Went Wrong ";
				}

			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Report Not found";
			}
			
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function rejectReport(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'type' 				=>'required',
			'report_id' 		=>'required',
			'reason' 			=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['message'] = "Required parameters missing";
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$user = \App\User::where('api_token',$request->api_token)->first();
			$report_rejections = \App\ReportRejection::where('report_id',$request->report_id)->first();
			if(is_null($report_rejections)){
				$report_rejections = new \App\ReportRejection();
				$report_rejections->type = $request->type;
				$report_rejections->report_id = $request->report_id;
				$report_rejections->reason = $request->reason;
				$report_rejections->rejected_by = $user->id;

				if($report_rejections->save()){
					if($request->type == "daily-warehouse-payments"){
						$daily_expense_report = \App\DailyWarehouseExpenseReport::where('id',$request->report_id)->first();
						if(!is_null($daily_expense_report)){
							$daily_expense_report->first_approval = 0;
							$daily_expense_report->second_approval = 0;
							$daily_expense_report->third_approval = 0;
							$daily_expense_report->final_approval = 0;
							$daily_expense_report->save();
						}
					}else if($request->type == "rake-payments"){

						$daily_expense_report = \App\RakeExpenseReport::where('id',$request->report_id)->first();
						if(!is_null($daily_expense_report)){
							$daily_expense_report->first_approval = 0;
							$daily_expense_report->second_approval = 0;
							$daily_expense_report->third_approval = 0;
							$daily_expense_report->final_approval = 0;
							$daily_expense_report->save();
						}

					}

					$response['flag'] 			= true;
					$response['message'] 		= "Report Rejected Successfully ";
				}else{
					$response['flag'] 			= false;
					$response['message'] 		= "Something Went Wrong ";
				}

			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Already Rejected";
			}
			
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function expenseListRake(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'master_rake_id' 	=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){

			$rake =\App\MasterRake::where('is_active',1)->where('id',$request->master_rake_id)->with('master_rake_products')->first();

			$total_direct_labour_payments = \App\DirectLabourPayment::where('master_rake_id',$request->master_rake_id)->sum('amount');

			$total_wagon_unloadings = \App\WagonUnloading::where('master_rake_id',$request->master_rake_id)->sum('paid_amount');

			$total_freight_payments = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->sum('freight_paid_amount');

			$total_labour_payments = \App\LabourPayments::where('master_rake_id',$request->master_rake_id)->sum('paid_amount');
			
			$total_unloading_labour_payments = \App\UnloadingLabourPayment::where('master_rake_id',$request->master_rake_id)->sum('paid_amount');
			
			$rate_id = $request->master_rake_id;
			$sql = "SELECT sum(freight_paid_amount) as warehouse_freight FROM `product_loadings` WHERE master_rake_id=".$rate_id." and warehouse_id != '' and is_freight_paid=1";
			$sql1 = "SELECT sum(freight_paid_amount) as party_freight FROM `product_loadings` WHERE master_rake_id=".$rate_id." and retailer_id != '' and is_freight_paid=1";
			$result = \DB::select($sql);
			$result1 = \DB::select($sql1);

			$total_expenses = $total_direct_labour_payments + $total_labour_payments + $total_unloading_labour_payments +  $total_wagon_unloadings + $total_freight_payments + $rake->demurrage + $rake->wharfage;

		
			$data['direct_expence'] = $total_direct_labour_payments;
			$data['freight_payments'] = $total_freight_payments;
			$data['wagon_unloadings'] = $total_wagon_unloadings;
			$data['warehouse_unloading_labour_payments'] = $total_unloading_labour_payments;
			$data['labour_payments'] = $total_labour_payments;
			$data['demurrage_charges'] = $rake->demurrage;
			$data['wharfage_charges'] = $rake->wharfage;
			$data['party_freight'] = $result1[0]->party_freight;
			$data['warehouse_freight'] = $result[0]->warehouse_freight;
			$data['total'] = $total_expenses;

			$response['flag'] = true;
			$response['data'] = $data;
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}


public function freightPaymentInfo(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'master_rake_id' 	=>'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$rate_id = $request->master_rake_id;
			$sql = "SELECT sum(freight_paid_amount) as warehouse_freight FROM `product_loadings` WHERE master_rake_id=".$rate_id." and warehouse_id != '' and is_freight_paid=1";
			$sql1 = "SELECT sum(freight_paid_amount) as party_freight FROM `product_loadings` WHERE master_rake_id=".$rate_id." and retailer_id != '' and is_freight_paid=1";
			$result = \DB::select($sql);
			$result1 = \DB::select($sql1);
			$data['party_freight'] = $result1[0]->party_freight;
			$data['warehouse_freight'] = $result[0]->warehouse_freight;

			$response['flag'] = true;
			$response['data'] = $data;
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function get_total_loading($company_id,$date_of_generation,$product_category_id){
	$sql ="SELECT t.id, t.token_type, t.master_rake_id, t.company_id, t.quantity, t.date_of_generation,  pc.category , p.product_category_id FROM `tokens` as t join products as p on p.id = t.product_id JOIN product_categories as pc on pc.id = p.product_category_id where t.date_of_generation = '".$date_of_generation."' and t.company_id = ".$company_id." and p.product_category_id =".$product_category_id;
	$result = \DB::select($sql);
	$loading_token = 0;
	foreach ($result as $value) {
		$sql2 = "SELECT SUM(quantity) as total   FROM product_loadings WHERE token_id =".$value->id." and date(created_at)='".$date_of_generation."'";
		
		$total = \DB::select($sql2)[0]->total;
		
		if($total != null){ 

			$loading_token = $loading_token + $total;
		}
		
	}

	return $loading_token;
	
}


public function tokenCompanyWise(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'report_date'  => 'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$date = $request->report_date;

			$sql_kcf = "SELECT sum(t.quantity) as total_token, sum(t.rate*t.quantity) as total_rate,t.company_id, t.date_of_generation,  pc.category , p.product_category_id FROM `tokens` as t join products as p on p.id = t.product_id JOIN product_categories as pc on pc.id = p.product_category_id where t.date_of_generation = '".$date."' and t.company_id = 2 GROUP BY p.product_category_id";

			$result_kcf = \DB::select($sql_kcf);
			$kcf_data = array();
			foreach ($result_kcf as $key => $value_kcf) {

				$kcf_data[$key]['product_category'] = $value_kcf->category; 
				$kcf_data[$key]['company_id'] = $value_kcf->company_id; 
				$kcf_data[$key]['product_category_id'] = $value_kcf->product_category_id; 
				$kcf_data[$key]['date_of_generation'] = $value_kcf->date_of_generation; 
				$kcf_data[$key]['total_token'] = $value_kcf->total_token; 
				$kcf_avg_price = $value_kcf->total_rate/$value_kcf->total_token;
				$kcf_data[$key]['avg_price'] = number_format($kcf_avg_price,2); 
				$kcf_data[$key]['blance_loading'] = $value_kcf->total_token - $this->get_total_loading($value_kcf->company_id,$value_kcf->date_of_generation,$value_kcf->product_category_id);
			}

			$sql_msrd = "SELECT sum(t.quantity) as total_token, sum(t.rate) as total_rate,t.company_id, t.date_of_generation,  pc.category , p.product_category_id FROM `tokens` as t join products as p on p.id = t.product_id JOIN product_categories as pc on pc.id = p.product_category_id where t.date_of_generation = '".$date."' and t.company_id = 3 GROUP BY p.product_category_id";

			$result_msrd = \DB::select($sql_msrd);
			$msrd_data = array();
			foreach ($result_msrd as $key => $value_msrd) {

				$msrd_data[$key]['product_category'] = $value_msrd->category; 
				$msrd_data[$key]['company_id'] = $value_msrd->company_id; 
				$msrd_data[$key]['product_category_id'] = $value_msrd->product_category_id;
				$msrd_data[$key]['date_of_generation'] = $value_msrd->date_of_generation;
				$msrd_data[$key]['total_token'] = $value_msrd->total_token; 
				$avg_price = $value_msrd->total_rate/$value_msrd->total_token;
				$msrd_data[$key]['avg_price'] = number_format($avg_price,2); 
				$msrd_data[$key]['blance_loading'] = $value_msrd->total_token - $this->get_total_loading($value_msrd->company_id,$value_msrd->date_of_generation,$value_msrd->product_category_id);
			}

			$sql_mcpl = "SELECT sum(t.quantity) as total_token, sum(t.rate) as total_rate,t.company_id, t.date_of_generation,  pc.category , p.product_category_id FROM `tokens` as t join products as p on p.id = t.product_id JOIN product_categories as pc on pc.id = p.product_category_id where t.date_of_generation = '".$date."' and t.company_id = 5 GROUP BY p.product_category_id";

			$result_mcpl = \DB::select($sql_mcpl);
			$mcpl_data = array();
			foreach ($result_mcpl as $key => $value_mcpl) {

				$mcpl_data[$key]['product_category'] = $value_mcpl->category; 
				$mcpl_data[$key]['company_id'] = $value_mcpl->company_id; 
				$mcpl_data[$key]['product_category_id'] = $value_mcpl->product_category_id;
				$mcpl_data[$key]['date_of_generation'] = $value_mcpl->date_of_generation;
				$mcpl_data[$key]['total_token'] = $value_mcpl->total_token; 
				$avg_price = $value_mcpl->total_rate/$value_mcpl->total_token;
				$mcpl_data[$key]['avg_price'] = number_format($avg_price,2); 
				$mcpl_data[$key]['blance_loading'] = $value_mcpl->total_token - $this->get_total_loading($value_mcpl->company_id,$value_mcpl->date_of_generation,$value_mcpl->product_category_id);
			}


			$response['flag'] = true;
			
			$response['kcf_data'] = $kcf_data;
			$response['msrd_data'] = $msrd_data;
			$response['mcpl_data'] = $mcpl_data;
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function get_total_loading_product_wise($company_id,$date_of_generation,$product_id){
	$sql ="SELECT t.id, t.token_type, t.master_rake_id, t.company_id, t.quantity, t.date_of_generation,  pc.category , p.product_category_id FROM `tokens` as t join products as p on p.id = t.product_id JOIN product_categories as pc on pc.id = p.product_category_id where t.date_of_generation = '".$date_of_generation."' and t.company_id = ".$company_id." and p.id =".$product_id;
	$result = \DB::select($sql);
	$loading_token = 0;
	foreach ($result as $value) {
		$sql2 = "SELECT SUM(quantity) as total   FROM product_loadings WHERE token_id =".$value->id." and date(created_at)='".$date_of_generation."'";
		
		$total = \DB::select($sql2)[0]->total;
		
		if($total != null){ 

			$loading_token = $loading_token + $total;
		}
		
	}

	return $loading_token;
}

public function tokenOfCompanyProductsWise(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'company_id' 	=>'required',
			'product_category_id' => 'required',
			'report_date'  => 'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$date = $request->report_date;
			$company_id = $request->company_id;
			$product_category_id = $request->product_category_id;

			$sql_kcf = "SELECT sum(t.quantity) as total_token, sum(t.rate*t.quantity) as total_rate,t.company_id, t.date_of_generation,t.product_id,  p.name , p.product_category_id FROM `tokens` as t join products as p on p.id = t.product_id JOIN product_categories as pc on pc.id = p.product_category_id where t.date_of_generation = '".$date."' and t.company_id = ".$company_id." and p.product_category_id=".$product_category_id." GROUP BY t.product_id";

			$result_kcf = \DB::select($sql_kcf);
			$mydata = array();
			foreach ($result_kcf as $key => $value_kcf) {

				$mydata[$key]['product_name'] = $value_kcf->name; 
				$mydata[$key]['company_id'] = $value_kcf->company_id; 
				$mydata[$key]['product_id'] = $value_kcf->product_id; 
				$mydata[$key]['product_category_id'] = $value_kcf->product_category_id; 
				$mydata[$key]['date_of_generation'] = $value_kcf->date_of_generation; 
				$mydata[$key]['total_token'] = $value_kcf->total_token; 
				$kcf_avg_price = $value_kcf->total_rate/$value_kcf->total_token;
				$mydata[$key]['avg_price'] = number_format($kcf_avg_price,2); 
				$mydata[$key]['blance_loading'] = $value_kcf->total_token - $this->get_total_loading_product_wise($value_kcf->company_id,$value_kcf->date_of_generation,$value_kcf->product_id);
			}

			$data['tokens'] = $mydata;

			$response['flag'] = true;
			$response['data'] = $data;
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function get_total_loading_party_wise($company_id,$date_of_generation,$product_id,$retailer_id){
	$sql ="SELECT t.id, t.token_type, t.master_rake_id, t.company_id, t.quantity, t.date_of_generation,  pc.category , p.product_category_id FROM `tokens` as t join products as p on p.id = t.product_id JOIN product_categories as pc on pc.id = p.product_category_id where t.date_of_generation = '".$date_of_generation."' and t.company_id = ".$company_id." and p.id =".$product_id." and t.retailer_id=".$retailer_id;
	$result = \DB::select($sql);
	$loading_token = 0;
	foreach ($result as $value) {
		$sql2 = "SELECT SUM(quantity) as total   FROM product_loadings WHERE token_id =".$value->id." and date(created_at)='".$date_of_generation."'";
		
		$total = \DB::select($sql2)[0]->total;
		
		if($total != null){ 

			$loading_token = $loading_token + $total;
		}
		
	}

	return $loading_token;
}

public function tokenOfCompanyPartyWise(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'company_id' 	=>'required',
			'product_id' => 'required',
			'report_date'  => 'required',
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$date = $request->report_date;
			$company_id = $request->company_id;
			$product_id = $request->product_id;

			$sql_kcf = "SELECT sum(t.quantity) as total_token, sum(t.rate*t.quantity) as total_rate,t.company_id, t.date_of_generation,t.product_id,  p.name , r.name as retailer_name, t.retailer_id FROM `tokens` as t join products as p on p.id = t.product_id  join retailers as r on r.id=t.retailer_id where t.date_of_generation = '".$date."' and t.company_id = ".$company_id." and t.product_id=".$product_id." GROUP BY t.retailer_id";

			$result_kcf = \DB::select($sql_kcf);
			$mydata = array();
			foreach ($result_kcf as $key => $value_kcf) {

				$mydata[$key]['retailer_name'] = $value_kcf->retailer_name; 
				$mydata[$key]['company_id'] = $value_kcf->company_id; 
				
				$mydata[$key]['date_of_generation'] = $value_kcf->date_of_generation; 
				$mydata[$key]['total_token'] = $value_kcf->total_token; 
				$kcf_avg_price = $value_kcf->total_rate/$value_kcf->total_token;
				$mydata[$key]['avg_price'] = number_format($kcf_avg_price,2); 
				$mydata[$key]['blance_loading'] = $value_kcf->total_token - $this->get_total_loading_party_wise($value_kcf->company_id,$value_kcf->date_of_generation,$value_kcf->product_id,$value_kcf->retailer_id);
			}

			$data['tokens'] = $mydata;

			$response['flag'] = true;
			$response['data'] = $data;
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}

public function my_product_list(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
		)
	);

	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{

		if($this->checkApiAuth($request)){

			 $cement_product_ids = \App\Product::where('product_category_id',2)->pluck('id');
			// $cement_stock = \DB::select('SELECT  sum(i.quantity) as stocks  FROM inventories as i join products as p on p.id = i.product_id WHERE i.quantity > 0 and p.product_category_id=2')[0]->stocks;
			// $cement_stock_dealer = \App\Inventory::whereIn('product_id',$cement_product_ids)->whereIn('dealer_id',[1,3,30,31])->sum('quantity');
			$cement_stock_company = \App\Inventory::whereIn('product_id',$cement_product_ids)->whereIn('product_company_id',[1,3,30,31])->sum('quantity');
			$cement_stock = \DB::select('SELECT  sum(i.quantity) as stocks  FROM inventories as i join products as p on p.id = i.product_id WHERE  p.product_category_id=2 and i.dealer_id in(1,3,30,31)')[0]->stocks;	


			$dap_stock_dealer = \App\Inventory::where('product_id',4)->whereIn('dealer_id',[1,3,30,31])->sum('quantity');
			$dap_stock_company = \App\Inventory::where('product_id',4)->whereIn('product_company_id',[4,5,6])->sum('quantity');
			$dap_stock = $dap_stock_dealer + $dap_stock_company;

			$mop_stock_dealer = \App\Inventory::where('product_id',7)->whereIn('dealer_id',[1,3,30,31])->sum('quantity');
			$mop_stock_company = \App\Inventory::where('product_id',7)->whereIn('product_company_id',[4,5,6])->sum('quantity');
			$mop_stock = $mop_stock_dealer + $mop_stock_company;


			$npk_stock_dealer = \App\Inventory::whereIn('product_id',array(9,10,11,12,86))->whereIn('dealer_id',[1,3,30,31])->sum('quantity');
			$npk_stock_company = \App\Inventory::whereIn('product_id',array(9,10,11,12,86))->whereIn('product_company_id',[4,5,6])->sum('quantity');
			$npk_stock = $npk_stock_dealer + $npk_stock_company;

			


			$urea_stock_dealer = \App\Inventory::where('product_id',19)->whereIn('dealer_id',[1,3,30,31])->sum('quantity');
			$urea_stock_company = \App\Inventory::where('product_id',19)->whereIn('product_company_id',[4,5,6])->sum('quantity');
			$urea_stock = $urea_stock_dealer + $urea_stock_company;




			$data[0]['name']='DAP';
			$data[0]['stocks']= $dap_stock;
			
			$data[1]['name']='MOP';
			$data[1]['stocks']= $mop_stock;

			$data[2]['name']='UREA';
			$data[2]['stocks']= $urea_stock;

			$data[3]['name']='NPK';
			$data[3]['stocks']= $npk_stock;


			$data[4]['name']='CEMENT';
			$data[4]['stocks']= $cement_stock ;

			$response['flag'] = true;
			$response['data'] = $data;

		}else{

			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;

		}
	}
	return response()->json($response);
} 

public function dealer_list(Request $request){

	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'product_name' 		=>'required',
		)
	);

	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){

			if($request->product_name == 'DAP'){

				$MRSD_STP = \App\Inventory::where('dealer_id',1)->where('product_id',4)->sum('quantity');	
				$MRSD_LRP = \App\Inventory::where('dealer_id',3)->where('product_id',4)->sum('quantity');	
				$MCPL_STP = \App\Inventory::where('dealer_id',30)->where('product_id',4)->sum('quantity');	
				$MCPL_DEVI_KALI_RD = \App\Inventory::where('dealer_id',31)->where('product_id',4)->sum('quantity');	
				$CFCL = \App\Inventory::where('product_company_id',4)->where('product_id',4)->sum('quantity');	
				$IPL = \App\Inventory::where('product_company_id',5)->where('product_id',4)->sum('quantity');	
				$PPL = \App\Inventory::where('product_company_id',6)->where('product_id',4)->sum('quantity');	
				$response['product_name'] = 'DAP';
			}

			if($request->product_name == 'MOP'){
				$MRSD_STP = \App\Inventory::where('dealer_id',1)->where('product_id',7)->sum('quantity');	
				$MRSD_LRP = \App\Inventory::where('dealer_id',3)->where('product_id',7)->sum('quantity');	
				$MCPL_STP = \App\Inventory::where('dealer_id',30)->where('product_id',7)->sum('quantity');	
				$MCPL_DEVI_KALI_RD = \App\Inventory::where('dealer_id',31)->where('product_id',7)->sum('quantity');	
				$CFCL = \App\Inventory::where('product_company_id',4)->where('product_id',7)->sum('quantity');	
				$IPL = \App\Inventory::where('product_company_id',5)->where('product_id',7)->sum('quantity');	
				$PPL = \App\Inventory::where('product_company_id',6)->where('product_id',7)->sum('quantity');	
				$response['product_name'] = 'MOP';
			}
			if($request->product_name == 'UREA'){
				$MRSD_STP = \App\Inventory::where('dealer_id',1)->where('product_id',19)->sum('quantity');	
				$MRSD_LRP = \App\Inventory::where('dealer_id',3)->where('product_id',19)->sum('quantity');	
				$MCPL_STP = \App\Inventory::where('dealer_id',30)->where('product_id',19)->sum('quantity');	
				$MCPL_DEVI_KALI_RD = \App\Inventory::where('dealer_id',31)->where('product_id',19)->sum('quantity');	
				$CFCL = \App\Inventory::where('product_company_id',4)->where('product_id',19)->sum('quantity');	
				$IPL = \App\Inventory::where('product_company_id',5)->where('product_id',19)->sum('quantity');	
				$PPL = \App\Inventory::where('product_company_id',6)->where('product_id',19)->sum('quantity');	
				$response['product_name'] = 'UREA';
			}
			if($request->product_name == 'NPK'){
				$MRSD_STP = \App\Inventory::where('dealer_id',1)->whereIn('product_id',[9,10,11,12,86])->sum('quantity');	
				$MRSD_LRP = \App\Inventory::where('dealer_id',3)->whereIn('product_id',[9,10,11,12,86])->sum('quantity');	
				$MCPL_STP = \App\Inventory::where('dealer_id',30)->whereIn('product_id',[9,10,11,12,86])->sum('quantity');	
				$MCPL_DEVI_KALI_RD = \App\Inventory::where('dealer_id',31)->whereIn('product_id',[9,10,11,12,86])->sum('quantity');	
				$CFCL = \App\Inventory::where('product_company_id',4)->whereIn('product_id',[9,10,11,12,86])->sum('quantity');	
				$IPL = \App\Inventory::where('product_company_id',5)->whereIn('product_id',[9,10,11,12,86])->sum('quantity');	
				$PPL = \App\Inventory::where('product_company_id',6)->whereIn('product_id',[9,10,11,12,86])->sum('quantity');	
				$response['product_name'] = 'NPK';
			}
			if($request->product_name == 'CEMENT'){
				$cement_product_ids = \App\Product::where('product_category_id',2)->pluck('id');

				$MRSD_STP = \DB::select('SELECT  sum(i.quantity) as stocks  FROM inventories as i join products as p on p.id = i.product_id WHERE  p.product_category_id=2 and i.dealer_id=1')[0]->stocks;	
				$MRSD_LRP = \DB::select('SELECT  sum(i.quantity) as stocks  FROM inventories as i join products as p on p.id = i.product_id WHERE  p.product_category_id=2 and i.dealer_id=3')[0]->stocks;	
					
				$MCPL_STP = \DB::select('SELECT  sum(i.quantity) as stocks  FROM inventories as i join products as p on p.id = i.product_id WHERE  p.product_category_id=2 and i.dealer_id=30')[0]->stocks;		
					
				$MCPL_DEVI_KALI_RD = \DB::select('SELECT  sum(i.quantity) as stocks  FROM inventories as i join products as p on p.id = i.product_id WHERE  p.product_category_id=2 and i.dealer_id=31')[0]->stocks;	
					
				
				$CFCL = \DB::select('SELECT  sum(i.quantity) as stocks  FROM inventories as i join products as p on p.id = i.product_id WHERE  p.product_category_id=2 and i.product_company_id=4')[0]->stocks;		
				
				$IPL = \DB::select('SELECT  sum(i.quantity) as stocks  FROM inventories as i join products as p on p.id = i.product_id WHERE  p.product_category_id=2 and i.product_company_id=5')[0]->stocks;	

				$PPL = \DB::select('SELECT  sum(i.quantity) as stocks  FROM inventories as i join products as p on p.id = i.product_id WHERE  p.product_category_id=2 and i.product_company_id=6')[0]->stocks;
				$response['product_name'] = 'CEMENT';	
			}
			
			

			$data[0]['name']='MRSD-STP';
			$data[0]['stocks']= $MRSD_STP;
			$data[0]['dealer_id']= 1;
			$data[0]['is_dealer'] = true;
			
			$data[1]['name']='MRSD-LRP';
			$data[1]['stocks']= $MRSD_LRP;
			$data[1]['dealer_id']= 3;
			$data[1]['is_dealer'] = true;

			$data[2]['name']='MCPL-STP';
			$data[2]['stocks']= $MCPL_STP ;
			$data[2]['dealer_id']= 30;
			$data[2]['is_dealer'] = true;

			$data[3]['name']='MCPL-DEVI KALI RD';
			$data[3]['stocks']= $MCPL_DEVI_KALI_RD;
			$data[3]['dealer_id']= 31;
			$data[3]['is_dealer'] = true;

			$data[4]['name']='CFCL';
			$data[4]['stocks']=$CFCL;
			$data[4]['dealer_id']= 4;
			$data[4]['is_dealer'] = false;

			$data[5]['name']='IPL';
			$data[5]['stocks']=$IPL;
			$data[5]['dealer_id']= 5;
			$data[5]['is_dealer'] = false;

			$data[6]['name']='PPL';
			$data[6]['stocks']= $PPL;
			$data[6]['dealer_id']= 6;
			$data[6]['is_dealer'] = false;

			$response['flag'] = true;
			$response['data'] = $data;

		}else{

			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;

		}
	}
	return response()->json($response);
}

public function product_stock_list(Request $request){

	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'company_id' 		=>'required',
			'dealer_id' 		=>'required',
		)
	);

	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			if($request->company_id == 1){
				$dealer_id = $request->dealer_id;
				$sql = "select t1.product_category_id as category_id, sum(t1.quantity) as stocks,t1.category as name, t1.product_id from (SELECT  i.quantity, p.name,pc.category, p.product_category_id, i.product_id FROM inventories as i join products as p on p.id=i.product_id join product_categories as pc on pc.id = p.product_category_id WHERE i.dealer_id=".$dealer_id." ) as t1 GROUP by t1.product_category_id";
			}else{
				$company_id = $request->dealer_id;
				$sql = "select t1.product_category_id as category_id, sum(t1.quantity) as stocks,t1.category as name, t1.product_id from (SELECT i.quantity, p.name,pc.category, p.product_category_id, i.product_id FROM inventories as i join products as p on p.id=i.product_id join product_categories as pc on pc.id = p.product_category_id WHERE i.product_company_id=".$company_id." ) as t1 GROUP by t1.product_category_id";
			}

			

			$stocks = \DB::select($sql);

			

			$response['flag'] = true;
			$response['data'] = $stocks;
			$response['company_id'] = $request->company_id;
			$response['dealer_id'] = $request->dealer_id;


		}else{

			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;

		}
	}
	return response()->json($response);
}

public function company_product_stock_list(Request $request){

	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'company_id' 		=>'required',
			'dealer_id' 		=>'required',
			'category_id' 		=>'required',
		)
	);

	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){
			$cat_id = $request->category_id;
			if($request->company_id == 1){
				$dealer_id = $request->dealer_id;
				$sql = "select t1.product_category_id as category_id, sum(t1.quantity) as stocks,t1.name , t1.product_id from (SELECT i.quantity,i.product_company_id, p.name,pc.category, p.product_category_id, i.product_id FROM inventories as i join products as p on p.id=i.product_id join product_categories as pc on pc.id = p.product_category_id WHERE i.dealer_id=".$dealer_id." and pc.id = ".$cat_id.") as t1 GROUP by t1.product_id";
			}else{
				$company_id = $request->dealer_id;

				$sql = "select t1.product_category_id as category_id, sum(t1.quantity) as stocks,t1.name , t1.product_id from (SELECT i.quantity,i.product_company_id, p.name,pc.category, p.product_category_id, i.product_id FROM inventories as i join products as p on p.id=i.product_id join product_categories as pc on pc.id = p.product_category_id WHERE i.product_company_id=".$company_id." and pc.id = ".$cat_id.") as t1 GROUP by t1.product_id";
			}


			$stocks = \DB::select($sql);
			$response['flag'] = true;
			$response['data'] = $stocks;
			$response['company_id'] = $request->company_id;
			$response['dealer_id'] = $request->dealer_id;


		}else{

			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;

		}
	}
	return response()->json($response);
}
public function company_product_in_warehouse(Request $request){

	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'product_id' 		=>'required',
			'category_id' 		=>'required',
			'company_id' 		=>'required',
			'dealer_id' 		=>'required',
		)
	);

	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){

			$product_id = $request->product_id;
			$cat_id = $request->category_id;
			if($request->company_id == 1){
				$dealer_id = $request->dealer_id;
				

				$sql ="select sum(t1.quantity) as stocks,t1.name , t1.product_id, t1.warehouse_id from (SELECT i.quantity,i.product_company_id,i.warehouse_id, w.name, p.product_category_id, i.product_id FROM inventories as i join products as p on p.id=i.product_id join warehouses as won w.id = i.warehouse_id WHERE i.dealer_id=".$dealer_id." and p.product_category_id = ".$cat_id." and i.product_id = ".$product_id.") as t1 GROUP BY t1.warehouse_id";
			}else{
				$company_id = $request->dealer_id;

				$sql ="select sum(t1.quantity) as stocks,t1.name , t1.product_id, t1.warehouse_id from (SELECT i.quantity,i.product_company_id,i.warehouse_id, w.name, p.product_category_id, i.product_id FROM inventories as i join products as p on p.id=i.product_id join warehouses as w on w.id = i.warehouse_id WHERE i.product_company_id=".$company_id." and p.product_category_id = ".$cat_id." and i.product_id = ".$product_id.") as t1 GROUP BY t1.warehouse_id";
			}

			
			$stocks = \DB::select($sql);
			$response['flag'] = true;
			$response['data'] = $stocks;

		}else{

			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;

		}
	}
	return response()->json($response);
}


public function product_company_list(Request $request){

	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'product_name' 		=>'required',
		)
	);

	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{

		if($this->checkApiAuth($request)){
			if($request->product_name == 'DAP'){
				$sql="SELECT  pc.abbreviation as company_name, sum(i.quantity) as stocks  FROM inventories as i join product_companies as pc on pc.id=i.product_company_id WHERE i.quantity > 0 and i.product_id in(4) GROUP BY i.product_company_id";
				$response['product_name'] = 'DAP';
			}

			if($request->product_name == 'MOP'){
				$sql="SELECT  pc.abbreviation as company_name, sum(i.quantity) as stocks  FROM inventories as i join product_companies as pc on pc.id=i.product_company_id WHERE i.quantity > 0 and i.product_id in(7) GROUP BY i.product_company_id";	
				$response['product_name'] = 'MOP';
			}
			if($request->product_name == 'UREA'){
				$sql="SELECT  pc.abbreviation as company_name, sum(i.quantity) as stocks  FROM inventories as i join product_companies as pc on pc.id=i.product_company_id WHERE i.quantity > 0 and i.product_id in(19) GROUP BY i.product_company_id";	
				$response['product_name'] = 'UREA';
			}
			if($request->product_name == 'NPK'){
				$sql="SELECT  pc.abbreviation as company_name, sum(i.quantity) as stocks  FROM inventories as i join product_companies as pc on pc.id=i.product_company_id WHERE i.quantity > 0 and i.product_id in(9,10,11,12,86) GROUP BY i.product_company_id";	
				$response['product_name'] = 'NPK';
			}
			if($request->product_name == 'CEMENT'){
				$sql="SELECT  pc.abbreviation as company_name, sum(i.quantity) as stocks  FROM inventories as i join product_companies as pc on pc.id=i.product_company_id join products as p on p.id = i.product_id WHERE i.quantity > 0 and p.product_category_id=2 GROUP BY i.product_company_id";
				$response['product_name'] = 'CEMENT';
				
			}
				

			$data = \DB::select($sql);
			$response['flag'] = true;
			$response['data'] = $data;

		}else{

			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;

		}
	}
	return response()->json($response);
} 

public function product_company_list_dealer_wise(Request $request){

	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'product_name' 		=>'required',
			'dealer_id' 		=>'required',
			'is_dealer'			=> 'required'
		)
	);

	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{

		if($this->checkApiAuth($request)){
			
			if($request->product_name == 'DAP' && $request->is_dealer == 'true'){
				$dealer_id = $request->dealer_id;
				 $sql = "SELECT pc.abbreviation as company_name, i.product_brand_id as company_id, sum(i.quantity) as stocks FROM inventories as i join product_companies as pc on pc.id=i.product_brand_id WHERE  i.product_id in(4) and i.dealer_id = ".$dealer_id." group by i.product_brand_id";
				
			}
			if($request->product_name == 'DAP' && $request->is_dealer == 'false'){
				$company_id = $request->dealer_id;
				$sql = "SELECT pc.abbreviation as company_name, i.product_brand_id as company_id, sum(i.quantity) as stocks FROM inventories as i join product_companies as pc on pc.id=i.product_brand_id WHERE  i.product_id in(4) and i.product_company_id = ".$company_id." group by i.product_brand_id";
				
				
			}


			if($request->product_name == 'MOP' && $request->is_dealer == 'true'){
				$dealer_id = $request->dealer_id;
				$sql = "SELECT pc.abbreviation as company_name, i.product_brand_id as company_id, sum(i.quantity) as stocks FROM inventories as i join product_companies as pc on pc.id=i.product_brand_id WHERE  i.product_id in(7) and i.dealer_id = ".$dealer_id." group by i.product_brand_id";
				
			}
			if($request->product_name == 'MOP' && $request->is_dealer == 'false'){
				$company_id = $request->dealer_id;
				$sql = "SELECT pc.abbreviation as company_name, i.product_brand_id as company_id, sum(i.quantity) as stocks FROM inventories as i join product_companies as pc on pc.id=i.product_brand_id WHERE  i.product_id in(7) and i.product_company_id = ".$company_id." group by i.product_brand_id";
				
			}


			if($request->product_name == 'UREA' && $request->is_dealer == 'true'){
				$dealer_id = $request->dealer_id;
				$sql = "SELECT pc.abbreviation as company_name, i.product_brand_id as company_id, sum(i.quantity) as stocks FROM inventories as i join product_companies as pc on pc.id=i.product_brand_id WHERE  i.product_id in(19) and i.dealer_id = ".$dealer_id." group by i.product_brand_id";
				
			}
			if($request->product_name == 'UREA' && $request->is_dealer == 'false'){
				$company_id = $request->dealer_id;
				$sql = "SELECT pc.abbreviation as company_name, i.product_brand_id as company_id, sum(i.quantity) as stocks FROM inventories as i join product_companies as pc on pc.id=i.product_brand_id WHERE  i.product_id in(19) and i.product_company_id = ".$company_id." group by i.product_brand_id";
				
			}

			
			
			if($request->product_name == 'NPK' && $request->is_dealer == 'true'){
				$dealer_id = $request->dealer_id;
				$sql = "SELECT pc.abbreviation as company_name, i.product_brand_id as company_id, sum(i.quantity) as stocks FROM inventories as i join product_companies as pc on pc.id=i.product_brand_id WHERE  i.product_id in(9,10,11,12,86) and dealer_id = ".$dealer_id." group by i.product_brand_id";
				
			}

			if($request->product_name == 'UREA' && $request->is_dealer == 'false'){
				$company_id = $request->dealer_id;
				$sql = "SELECT pc.abbreviation as company_name, i.product_brand_id as company_id, sum(i.quantity) as stocks FROM inventories as i join product_companies as pc on pc.id=i.product_brand_id WHERE  i.product_id in(9,10,11,12,86) and i.product_company_id = ".$company_id." group by i.product_brand_id";
				
			}


			if($request->product_name == 'CEMENT' && $request->is_dealer == 'true'){
				$dealer_id = $request->dealer_id;
				$sql="SELECT  pc.abbreviation as company_name, i.product_brand_id as company_id, sum(i.quantity) as stocks  FROM inventories as i join product_companies as pc on pc.id=i.product_brand_id join products as p on p.id = i.product_id WHERE  p.product_category_id=2 and i.dealer_id in(".$dealer_id.") GROUP BY i.product_brand_id";
				
				
			}
			if($request->product_name == 'CEMENT' && $request->is_dealer == 'false'){
				$company_id = $request->dealer_id;
				$sql="SELECT  pc.abbreviation as company_name, i.product_brand_id as company_id, sum(i.quantity) as stocks  FROM inventories as i join product_companies as pc on pc.id=i.product_brand_id join products as p on p.id = i.product_id WHERE  p.product_category_id=2 and i.product_company_id in(".$company_id.") GROUP BY i.product_brand_id";
				
			}
				

			$data = \DB::select($sql);
			$response['product_name'] = $request->product_name;
			$response['dealer_id'] = $request->dealer_id;
			$response['is_dealer'] = $request->is_dealer;
			$response['flag'] = true;
			$response['data'] = $data;

		}else{

			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;

		}
	}
	return response()->json($response);
} 
public function product_warehouse_list(Request $request){

	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'product_name' 		=>'required',
		)
	);

	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{

		if($this->checkApiAuth($request)){
			if($request->product_name == 'DAP'){
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id WHERE  i.product_id in(4) and dealer_id in(1,3,30,31) GROUP BY i.warehouse_id";
				$response['product_name'] = 'DAP';
			}

			if($request->product_name == 'MOP'){
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id WHERE  i.product_id in(7) and dealer_id in(1,3,30,31) GROUP BY i.warehouse_id";
				$response['product_name'] = 'MOP';	
			}
			if($request->product_name == 'UREA'){
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id WHERE  i.product_id in(19) and dealer_id in(1,3,30,31) GROUP BY i.warehouse_id";	
				$response['product_name'] = 'UREA';
			}
			if($request->product_name == 'NPK'){
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id WHERE  i.product_id in(9,10,11,12,86) and dealer_id in(1,3,30,31) GROUP BY i.warehouse_id";	
				$response['product_name'] = 'NPK';
			}
			if($request->product_name == 'CEMENT'){
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id join products as p on p.id = i.product_id WHERE  p.product_category_id=2 and dealer_id in(1,3,30,31) GROUP BY i.warehouse_id";
				$response['product_name'] = 'CEMENT';
			}
				

			$data = \DB::select($sql);
			$response['flag'] = true;
			$response['data'] = $data;

		}else{

			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;

		}
	}
	return response()->json($response);
} 

public function product_warehouse_list_dealer_wise(Request $request){

	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'product_name' 		=>'required',
			'dealer_id' 		=>'required',
			'company_id'			=> 'required',
			'is_dealer'			=> 'required',
		)
	);

	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{

		if($this->checkApiAuth($request)){
			$company_brand_id = $request->company_id;
			if($request->product_name == 'DAP' && $request->is_dealer == 'true'){
				$dealer_id = $request->dealer_id;
				 $sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id WHERE i.product_brand_id=".$company_brand_id." and i.product_id in(4) and i.dealer_id=".$dealer_id." GROUP BY i.warehouse_id";
				
			}

			if($request->product_name == 'DAP' && $request->is_dealer == 'false'){
				$company_id = $request->dealer_id;
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id WHERE i.product_brand_id=".$company_brand_id." and i.product_id in(4) and i.product_company_id = ".$company_id." GROUP BY i.warehouse_id";
				
			}

			if($request->product_name == 'MOP' && $request->is_dealer == 'true'){
				$dealer_id = $request->dealer_id;
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id WHERE i.product_brand_id=".$company_brand_id." and i.product_id in(7) and i.dealer_id=".$dealer_id." GROUP BY i.warehouse_id";
				
			}
			if($request->product_name == 'MOP' && $request->is_dealer == 'false'){
				$company_id = $request->dealer_id;
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id WHERE i.product_brand_id=".$company_brand_id." and i.product_id in(7) and i.product_company_id = ".$company_id." GROUP BY i.warehouse_id";
				
			}

			if($request->product_name == 'UREA'  && $request->is_dealer == 'true'){
				$dealer_id = $request->dealer_id;
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id WHERE i.product_brand_id=".$company_brand_id." and i.product_id in(19) and i.dealer_id=".$dealer_id." GROUP BY i.warehouse_id";	
				
			}
			if($request->product_name == 'UREA' && $request->is_dealer == 'false'){
				$company_id = $request->dealer_id;
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id WHERE i.product_brand_id=".$company_brand_id." and i.product_id in(19) and i.product_company_id = ".$company_id." GROUP BY i.warehouse_id";
				
			}

			if($request->product_name == 'NPK' && $request->is_dealer == 'true'){
				$dealer_id = $request->dealer_id;
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id WHERE i.product_brand_id=".$company_brand_id." and i.product_id in(9,10,11,12,86) and i.dealer_id=".$dealer_id." GROUP BY i.warehouse_id";	
			
			}
			if($request->product_name == 'NPK' && $request->is_dealer == 'false'){
				$dealer_id = $request->dealer_id;
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id WHERE i.product_brand_id=".$company_brand_id." and i.product_id in(9,10,11,12,86) and i.product_company_id = ".$company_id." GROUP BY i.warehouse_id";
			}


			if($request->product_name == 'CEMENT' && $request->is_dealer == 'true'){
				$dealer_id = $request->dealer_id;
				
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id join products as p on p.id = i.product_id WHERE i.product_brand_id=".$company_brand_id." and p.product_category_id=2  and i.dealer_id=".$dealer_id." GROUP BY i.warehouse_id";
				
			}

			if($request->product_name == 'CEMENT' && $request->is_dealer == 'false'){
				$sql="SELECT  w.name as warehouse_name, sum(i.quantity) as stocks  FROM inventories as i join warehouses as w on w.id=i.warehouse_id join products as p on p.id = i.product_id WHERE i.product_brand_id=".$company_brand_id." and p.product_category_id=2  and i.product_company_id = ".$company_id." GROUP BY i.warehouse_id";
			}
				

			$data = \DB::select($sql);

			$response['product_name'] = $request->product_name;
			$response['dealer_id'] = $request->dealer_id;
			$response['is_dealer'] = $request->is_dealer;
			$response['flag'] = true;
			$response['data'] = $data;

		}else{

			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;

		}
	}
	return response()->json($response);
} 

public function warehousesList(Request $request){

	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			
		)
	);

	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{

		if($this->checkApiAuth($request)){


			$warehouse=\App\Warehouse::where('is_active',1)->get();

			$response['flag'] = true;
			$response['warehouse'] = $warehouse;

		}else{

			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;

		}
	}
		return response()->json($response);

}



public function loadingapprovedwarehousesList(Request $request){

	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			
		)
	);

	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{

		if($this->checkApiAuth($request)){

            $warehouse_id=$request->id;
			$loading_slips = \DB::table('loading_slips')
			->join('dealers','dealers.unique_id','loading_slips.dealer_id')
			->join('retailers','retailers.unique_code','loading_slips.retailer_id')
			->join('product_companies','product_companies.id','loading_slips.product_company_id')
			->join('products','products.id','loading_slips.product_id')
			->join('units','units.id','loading_slips.unit_id')
			->join('orders','orders.id','loading_slips.order_id')
			->join('transport_modes','transport_modes.id','loading_slips.transport_mode')
			->join('transporters','transporters.id','loading_slips.transporter_id')
			->leftjoin('rake_points','rake_points.id','loading_slips.rake_point')
			->leftjoin('warehouses','warehouses.id','loading_slips.from_warehouse_id')
			->where('loading_slips.slip_status','slip_generated')
			->where('warehouses.id',$warehouse_id)
			->where('orders.order_status','approved')
			->where('orders.loading_status','1')
			// ->where('loading_slips.created_at',Carbon::today())
			//->where('loading_slips.loading_status','1')
			->select('loading_slips.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name','transporters.name as transporter_name','transport_modes.name as transport_mode_name')
			->orderBy('loading_slips.id','desc')
			->groupBy('loading_slips.id')->get();
			$data['loading_slips'] = $loading_slips;
			$response['flag'] = true;
			$response['warehouse'] = $loading_slips;

		}else{

			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;

		}
	}
		return response()->json($response);

}


public function warehousesOrderList(Request $request){

	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'warehouse_id'     =>'required'
			
		)
	);

	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{

		if($this->checkApiAuth($request)){


			// $warehousesOrderList=\App\Order::where(['order_from'=>2,'from_warehouse_id'=>$request->warehouse_id,'is_active'=>1])->get();

			$warehousesOrderList = \DB::table('orders')->join('dealers','dealers.unique_id','orders.dealer_id')->join('retailers','retailers.unique_code','orders.retailer_id')->join('product_companies','product_companies.id','orders.product_company_id')->join('products','products.id','orders.product_id')->join('units','units.id','orders.unit_id')->leftjoin('rake_points','rake_points.id','orders.rake_point')->leftjoin('warehouses','warehouses.id','orders.from_warehouse_id')->select('orders.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.brand_name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name')->where('orders.order_status','approved')->where('orders.order_from',2)->where('orders.from_warehouse_id',$request->warehouse_id)->where('orders.is_active',1)->orderBy('orders.id','desc')->get();

			$response['flag'] = true;
			$response['warehouse-orderlist'] = $warehousesOrderList;

		}else{

			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;

		}
	}
		return response()->json($response);

}

public function loadingorders(Request $request){
	$response = array();
	//$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required'
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){

			// dd($request->all());
		
			// $orders = \DB::table('orders')->join('dealers','dealers.id','orders.dealer_id')->join('retailers','retailers.id','orders.retailer_id')->join('product_companies','product_companies.id','orders.product_company_id')->join('products','products.id','orders.product_id')->join('units','units.id','orders.unit_id')->leftjoin('rake_points','rake_points.id','orders.rake_point')->leftjoin('warehouses','warehouses.id','orders.from_warehouse_id')->select('orders.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.brand_name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name')->where('orders.order_status','approved')->where('orders.is_active',1)->orderBy('orders.id','desc')->get();

			$orders = \DB::table('orders')->join('loading_slips','loading_slips.order_id','orders.id')->join('dealers','dealers.unique_id','orders.dealer_id')->join('retailers','retailers.unique_code','orders.retailer_id')->join('product_companies','product_companies.id','orders.product_company_id')->join('products','products.id','orders.product_id')->join('units','units.id','orders.unit_id')->leftjoin('rake_points','rake_points.id','orders.rake_point')->leftjoin('warehouses','warehouses.id','orders.from_warehouse_id')->select('orders.*','loading_slips.id as loading_id','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.brand_name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name')->where('orders.order_status','approved')->where('orders.loading_status','1')->where('orders.is_active',1)->orderBy('orders.id','desc')->get();
			// dd($orders);
			$transport_modes = \DB::table('transport_modes')->get();
			$transporters = \DB::table('transporters')->where('is_active',1)->get();

			if(!is_null($orders)){
				$response['flag'] 			 = true;
				$response['orders'] 		 = $orders;
				$response['transport_modes'] = $transport_modes;
				$response['transporters'] = $transporters;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Order Token";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}





public function loadingslipprint(Request $request){
	// dd($request->all());
	$response = array();
	//$internals = Faker\Factory::create('en_US');
	$validator = \Validator::make($request->all(),
		array(
			'api_token' 		=>'required',
			'loading_id'          =>'required'
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $this->parseErrorResponse($validator->getMessageBag());
	}else{
		if($this->checkApiAuth($request)){

			// $loading_slip =  \App\LoadingSlip::where('id',$request->loading_id)->first();
              
			
			//   $loading_slip =\App\LoadingSlip::where('id',$request->loading_id)
			//   ->join('retailers','retailers.unique_code','loading_slips.retailer_id')
			//   ->select('loading_slips.*','loading_slips.id as loading_id','retailers.name')
			//   ->first();

			$loading_slips = \DB::table('loading_slips')->join('users','users.id','loading_slips.user_id')->join('dealers','dealers.unique_id','loading_slips.dealer_id')->join('retailers','retailers.unique_code','loading_slips.retailer_id')->join('product_companies','product_companies.id','loading_slips.product_company_id')->join('products','products.id','loading_slips.product_id')->join('units','units.id','loading_slips.unit_id')->join('transport_modes','transport_modes.id','loading_slips.transport_mode')->join('transporters','transporters.id','loading_slips.transporter_id')->join('orders', 'orders.id', 'loading_slips.order_id')->leftjoin('rake_points','rake_points.id','loading_slips.rake_point')->leftjoin('warehouses','warehouses.id','loading_slips.from_warehouse_id')->select('loading_slips.*','users.name as user_name','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name','transporters.name as transporter_name','transport_modes.name as transport_mode_name')->where('loading_slips.id', $request->loading_id)->first();
			 // dd($loading_slips);
             
			  $encodedLoadingSlipQr = base64_encode('loading_slip,'.$loading_slips->id);
          


			if(!is_null($loading_slips)){
				$response['flag'] 			 = true;
				$response['loading_slip'] 		 = $loading_slips;
				$response['encodedLoadingSlipQr'] 		 = $encodedLoadingSlipQr;
			
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid loading slip";
			}
		}else{
			$response['flag'] 			= false;
			$response['message'] 		= "सेशन एक्सपायर हो चूका है| कृपया दुबारा लॉगिन करें ";
			$response['is_token_expired'] = true;
		}
	}
	return response()->json($response);
}








public function print_invoice_detail(Request $request){

$validator = \Validator::make($request->all(),
			array(
				'loading_slip_id' => 'required',
				'quantity' => 'required',
				'referance_invoice_id' =>'required',
				'order_status' =>'required',
			)
		);

		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			
			$loading_slip_id = $request->loading_slip_id;
			// $acting_company = Session::get('acting_company');

			$loading_slip =  \App\LoadingSlip::where('id',$loading_slip_id)->first();

			

			if($loading_slip != null){
				$loading_slip->invoice_no = $request->referance_invoice_id;
				//$loading_slip_id->invoice_amount = $request->referance_invoice_id;
				$loading_slip->invoice_date = date('Y-m-d');
				$loading_slip->quantity = $request->quantity;
				$loading_slip->slip_status = $request->order_status;
				$loading_slip->save();
				$incoice_status=\App\Order::where('id',$loading_slip->order_id)->update(['invoice_status'=>1,'loading_status'=>2]);
				$response['flag'] = true;
				$response['message'] = "Invoice Generated Successfully !!";
			}else{
				$response['flag'] = true;
				$response['message'] = "Somthing Went Wrong !!";
			}
		}

		return response()->json($response);

}


}


