<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class TokenController extends Controller
{

	public function generated_orders(Request $request){
		$data = array();
		$data['acting_company'] = Session::get('acting_company');
		
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'master_rake_id' =>'required',
				)
			);
			if($validator->fails()){
				return redirect('user/generated-token')
				->withErrors($validator)
				->withInput();
			}else{
				$data['master_rake_id'] = $request->master_rake_id;

				$data['tokens'] = \App\Token::where('is_active',1)->get();
				$data['invoice_types'] = \App\InvoiceType::where('is_active',1)->get();
				$data['companies'] = \App\Company::where('is_active',1)->where('for_invoice',1)->get();
			} 
		}else{
			$data['tokens'] = \App\Token::where('is_active',1)->orderBy('created_at','desc')->get();
		}
		return view('dashboard.token.generated-tokens',$data);
	}


	public function generated_token_report(Request $request){
		$data = array();
		$data['acting_company'] = Session::get('acting_company');
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->where('is_closed',0)->where('is_closed',0)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'master_rake_id' =>'required',
				)
			);
			if($validator->fails()){
				return redirect('user/generated-token')
				->withErrors($validator)
				->withInput();
			}else{
				$data['master_rake_id'] = $request->master_rake_id;

				$data['tokens'] = \App\Token::where('is_active',1)
				->where('master_rake_id',$request->master_rake_id)
				->get();
				$data['invoice_types'] = \App\InvoiceType::where('is_active',1)->get();
				$data['companies'] = \App\Company::where('is_active',1)->where('for_invoice',1)->get();
			} 
		}else{
			$data['tokens'] = \App\Token::where('is_active',1)->where('token_type',1)->whereDate('created_at', '=', date('Y-m-d'))->get();
		}
		return view('dashboard.token.generated-token-report',$data);
	}


	public function generated_warehouse_tokens(Request $request){
		$data = array();
		$data['acting_company'] = Session::get('acting_company');
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'warehouse_id' =>'required',
				)
			);
			if($validator->fails()){
				return redirect('user/generated-warehouse-token')
				->withErrors($validator)
				->withInput();
			}else{
				$data['warehouse_id'] = $request->warehouse_id;

				$data['tokens'] = \App\Token::where('is_active',1)
				->where('from_warehouse_id',$request->warehouse_id)
				->get();
				$data['companies'] = \App\Company::where('is_active',1)->where('for_invoice',1)->get();
			} 
		}else{
			$data['tokens'] = \App\Token::where('is_active',1)->where('token_type',2)->whereDate('created_at', '=', date('Y-m-d'))->get();
		}
		return view('dashboard.token.generated-warehouse-tokens',$data);
	}
	public function getGenerateOrder(){
		$data = array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->where('is_closed',0)->get();
		$data['accounts'] = \App\Account::where('is_active',1)->get();
		$data['destinations'] = \DB::table('destinations')->get();
		$data['companies'] = \App\Company::where('is_active',1)->get();
		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['retailers'] = \App\Retailer::where('is_active',1)->get();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		$data['units'] = \App\Unit::where('is_active',1)->get();
		$data['warehouse_keepers'] = \App\User::where('is_active',1)->where('role_id',3)->get();
		$data['acting_company'] = Session::get('acting_company');
		return view('dashboard.token.generate-token',$data);
	} 

	public function postGenerateToken(Request $request){

		$acting_company = Session::get('acting_company');
		$response = array();
	
			if($request->token_type == 1){
				$validator = \Validator::make($request->all(),
					array(
						
						'date_of_generation' =>'required',
						'product_company_id' =>'required',
						'account_from_id' =>'required',
						'retailer_id' =>'required',
						'product_id' =>'required',
						'quantity' =>'required|integer',
						'unit_id' =>'required',
						'rate' =>'required',
						'delivery_payment_mode' =>'required',
					)
				);

			}elseif($request->token_type == 2){

				$validator = \Validator::make($request->all(),
					array(
						'from_warehouse_id' =>'required',
						'date_of_generation' =>'required',
						'product_company_id' =>'required',
						'account_from_id' =>'required',
						'warehouse_id' =>'required',
						'product_id' =>'required',
						'quantity' =>'required|integer',
						'unit_id' =>'required',
						'rate' =>'required',
						'delivery_payment_mode' =>'required',
					)
				);
			}

		

		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{

			if($request->token_type == 1){

				$token =  new \App\Token();
				$token->token_type = $request->token_type;
				$token->master_rake_id = $request->master_rake_id;
				$token->to_type = $request->to_type;
				$token->company_id = $request->company_id;
				if($request->to_type == 1){
					$token->warehouse_id = $request->warehouse_id;
				}else if($request->to_type == 2){
					$token->retailer_id = $request->retailer_id;
				}else if($request->to_type == 3){
					$token->dealer_id = $request->dealer_id;
				}

				$token->date_of_generation = date('Y-m-d',strtotime($request->date_of_generation));
				$token->product_company_id = $request->product_company_id;
				$token->account_from_id = $request->account_from_id;
				$token->product_id = $request->product_id;
				$token->quantity = $request->quantity;
				$token->unit_id = $request->unit_id;
				if($request->rate){
					$token->rate = $request->rate;
				}if($request->transporter_id){
					$token->transporter_id = $request->transporter_id;
				}if($request->warehouse_keeper_id){
					$token->warehouse_keeper_id = $request->warehouse_keeper_id;
				}if($request->truck_number){
					$token->truck_number = $request->truck_number;
				}
				$token->delivery_payment_mode = $request->delivery_payment_mode;
				if($request->description){
					$token->description = $request->description;
				}
				$token->user_id = \Auth::user()->id;
				// $total_token = \App\RakeProductAllotment::where('company_id',$request->company_id)->count();
				if($token->save()){
					$token_company = getModelById('Company',$request->company_id);

					$token->unique_id = $token_company->token_abbreviation."/".date('y/m')."/".$token->id;
					$token->save();
					$response['flag'] = true;
					$response['token'] = $token;
					$response['message'] = "Token Generated Successfully";
					$allotment->remaining_quantity = ($allotment->remaining_quantity-$request->quantity);
					$allotment->save();
				}else{
					$response['flag'] = false;
					$response['error'] = "Something Went Wrong";
				}
					

			}elseif($request->token_type == 2){

				
					$token =  new \App\Token();
					$token->token_type = $request->token_type;
					$token->to_type = $request->to_type;
					$token->from_warehouse_id = $request->from_warehouse_id;
					$token->company_id = $request->company_id;
					if($request->to_type == 1){
						$token->warehouse_id = $request->warehouse_id;
					}else if($request->to_type == 2){
						$token->retailer_id = $request->retailer_id;
					}else if($request->to_type == 3){
						$token->dealer_id = $request->dealer_id;
					}

					$token->date_of_generation = date('Y-m-d',strtotime($request->date_of_generation));
					$token->product_company_id = $request->product_company_id;
					$token->account_from_id = $request->account_from_id;
					$token->product_id = $request->product_id;
					$token->quantity = $request->quantity;
					$token->unit_id = $request->unit_id;
					if($request->rate){
						$token->rate = $request->rate;
					}if($request->transporter_id){
						$token->transporter_id = $request->transporter_id;
					}if($request->warehouse_keeper_id){
						$token->warehouse_keeper_id = $request->warehouse_keeper_id;
					}if($request->truck_number){
						$token->truck_number = $request->truck_number;
					}
					$token->delivery_payment_mode = $request->delivery_payment_mode;
					if($request->description){
						$token->description = $request->description;
					}
					$token->user_id = \Auth::user()->id;
					if($token->save()){
						$token_company = getModelById('Company',$request->company_id);
						$token->unique_id = $token_company->token_abbreviation."/".date('y/m')."/".$token->id;
						$token->save();
						$response['flag'] = true;
						$response['token'] = $token;
						$response['message'] = "Token Generated Successfully";
					}else{
						$response['flag'] = false;
						$response['error'] = "Something Went Wrong";
					}
			}
				

		}
		return response()->json($response);

	}

	public function printLoadingSlip($id){
		$data = array();
		$acting_company = Session::get('acting_company');
		$product_loading =  \App\ProductLoading::where('id',$id)->first();
		if(is_null($product_loading)){
			return redirect('user/generated-token')->with('error','Token Not found');
		}else{
			$data['company'] = \App\Company::where('id',$acting_company)->first();
			$labour_payment =  \App\LabourPayments::where('product_loading_id',$id)->first();
			$data['product_loading'] = $product_loading;
			$data['labour_payment'] = $labour_payment;
			return view('dashboard.token.print-loading-slip',$data);
		} 
	}

	public function printToken($id){
		$data = array();
		$acting_company = Session::get('acting_company');
		$token =  \App\Token::where('id',$id)->where('is_active',1)->first();
		if(is_null($token)){
			return redirect('user/generated-token')->with('error','Token Not found');
		}else{
			$data['company'] = \App\Company::where('id',$acting_company)->first();
			$data['token'] = $token;
			return view('dashboard.token.print-token',$data);
		} 
	}

	public function getEditToken($id){
		$acting_company = Session::get('acting_company');
		$data = array();
		$token =  \App\Token::where('id',$id)->where('is_active',1)->first();
		if(is_null($token)){
			return redirect('user/generated-token')->with('error','Token Not found');
		}else{
			$data['token'] = $token;
			// $data['master_rakes'] = \App\MasterRake::where('is_active',1)->where('is_closed',0)->get();
			// $data['master_rake'] = \App\MasterRake::where('id',$token->master_rake_id)->with('rake_allotments', 'master_rake_products')->first();
			// $allotment = \App\RakeProductAllotment::where('master_rake_id',$token->master_rake_id)->where('dealer_id',$token->account_from_id)->where('product_id',$token->product_id)->first();
			//  $data['remaining_quantity'] = $allotment->remaining_quantity;
			// $data['rakes'] = \App\Rake::where('is_active',1)->get();
			$data['accounts'] = \App\Account::where('is_active',1)->get();
			$data['dealers'] = \App\Dealer::where('is_active',1)->get();
			$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
			$data['transporters'] = \App\Transporter::where('is_active',1)->get();
			$data['companies'] = \App\Company::where('is_active',1)->get();
			$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
			$data['products'] = \App\Product::where('is_active',1)->get();
			$data['units'] = \App\Unit::where('is_active',1)->get();
			$data['retailers'] = \App\Retailer::where('is_active',1)->get();
			$data['warehouse_keepers'] = \App\User::where('is_active',1)->where('role_id',3)->get();
			$data['acting_company'] = Session::get('acting_company');
			// dd($data); exit;
			return view('dashboard.token.edit-token',$data);
		}
	} 
	public function postEditToken(Request $request){
		$acting_company = Session::get('acting_company');
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'quantity' =>'required|integer'
			)
		);
		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$token =  \App\Token::where('id',$request->id)->where('is_active',1)->first();
			if(is_null($token)){
				$response['flag'] = false;
				$response['error'] = "Token Not found";
			}else{
				
				$token->quantity = $request->quantity;
				// $token->unit_id = $request->unit_id;
				// if($request->account_id){
				// 	$token->account_id = $request->account_id;
				// }if($request->rate){
				// 	$token->rate = $request->rate;
				// }if($request->transporter_id){
				// 	$token->transporter_id = $request->transporter_id;
				// }if($request->warehouses_keeper_id){
				// 	$token->warehouses_keeper_id = $request->warehouses_keeper_id;
				// }if($request->truck_number){
				// 	$token->truck_number = $request->truck_number;
				// }
				// $token->delivery_payment_mode = $request->delivery_payment_mode;
				// if($request->description){
				// 	$token->description = $request->description;
				// }
				if($token->save()){
					$response['flag'] = true;
					$response['message'] = "Token Updated Successfully";
				}else{
					$response['flag'] = false;
					$response['error'] = "Something Went Wrong";
				}
			}
		}
		return response()->json($response);
	}
	public function getEditTokenQuantity($id){
		$acting_company = Session::get('acting_company');
		$data = array();
		$token =  \App\Token::where('id',$id)->where('is_active',1)->first();
		if(is_null($token)){
			return redirect('user/generated-token')->with('error','Token Not found');
		}else{
			$data['token'] = $token;
			$data['accounts'] = \App\Account::where('is_active',1)->get();
			$data['transporters'] = \App\Transporter::where('is_active',1)->get();
			$data['companies'] = \App\Company::where('is_active',1)->get();
			$data['dealers'] = \App\Dealer::where('is_active',1)->get();
			$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
			$data['retailers'] = \App\Retailer::where('is_active',1)->get();
			$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
			$data['products'] = \App\Product::where('is_active',1)->get();
			$data['units'] = \App\Unit::where('is_active',1)->get();
			$data['warehouse_keepers'] = \App\User::where('is_active',1)->where('role_id',3)->get();
			$data['acting_company'] = Session::get('acting_company');
			return view('dashboard.token.edit-token-quantity',$data);
		}
	} 
	public function postEditTokenQuantity(Request $request){
		$acting_company = Session::get('acting_company');
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'id' =>'required',
				'quantity' =>'required|integer',
			)
		);
		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$token =  \App\Token::where('id',$request->id)->where('is_active',1)->first();
			if(is_null($token)){
				$response['flag'] = false;
				$response['error'] = "Token Not found";
			}else{
				$tokan_quantity = $token->quantity;
				$total_loadings  = \App\ProductLoading::where('token_id',$request->id)
				->where('dealer_id',$token->account_from_id)
				->where('product_id',$token->product_id)
				->sum('quantity');

				
				$allotment = \App\RakeProductAllotment::where('master_rake_id',$token->master_rake_id)
				->where('dealer_id',$token->account_from_id)
				->where('product_id',$token->product_id)
				->first();

				if(!is_null($allotment)){

					if($total_loadings == 0){
						if($request->quantity <= $tokan_quantity){
							$allotment->remaining_quantity = $allotment->remaining_quantity + ($tokan_quantity - $request->quantity);
							if($allotment->save()){
								$token->quantity = $request->quantity;
								$token->save();
								$response['flag'] = true;
								$response['message'] = "Token Updated Successfully";
							}else{
								$response['flag'] = false;
								$response['error'] = "Something Went Wrong";
							}
						}
						else if($tokan_quantity < $request->quantity && ($request->quantity <= ($tokan_quantity+$allotment->remaining_quantity ))){
							$allotment->remaining_quantity = $allotment->remaining_quantity + ($tokan_quantity - $request->quantity);
							if($allotment->save()){
								$token->quantity = $request->quantity;
								$token->save();
								$response['flag'] = true;
								$response['message'] = "Token Updated Successfully";
							}else{
								$response['flag'] = false;
								$response['error'] = "Something Went Wrong";
							}
						}else{
							$response['flag'] = false;
							$response['error'] = "Quantity Shoud not be greater than Remaining Product Allotments (".$allotment->remaining_quantity.")";
						}
					}else{
						if($tokan_quantity < $request->quantity && $request->quantity > ($tokan_quantity +$allotment->remaining_quantity)){
							$response['flag'] = false;
							$response['error'] = "Quantity Should not be greater than Remaining Product Allotment(".$allotment->remaining_quantity.")";

						}else 

						if($total_loadings > $request->quantity){
							$response['flag'] = false;
							$response['error'] = "Quantity Shoud not be less than Total Product Loadings";

						}else{

							$allotment->remaining_quantity = $allotment->remaining_quantity + ($tokan_quantity - $request->quantity);
							if($allotment->save()){
								$token->quantity = $request->quantity;
								$token->save();
								$response['flag'] = true;
								$response['message'] = "Token Updated Successfully";
							}else{
								$response['flag'] = false;
								$response['error'] = "Something Went Wrong";
							}
						}
					}
				}else{
					$response['flag'] = false;
					$response['error'] = "Allotment Not Found";
				}
			}
		}
		return response()->json($response);
	}

	public function dateWiseLoadings(Request $request){
		$data = array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['companies'] = \App\Company::where('is_active',1)->where('for_invoice',1)->get();
		if ($request->isMethod('post')){
			$query = \App\ProductLoading::query();
			if($request->date){
				$query->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)));
				$data['date']  = $request->date;
			}
			// else{
			// 	$query->whereDate('created_at', '=', date('Y-m-d'));
			// 	$data['date']  = date('m/d/Y');
			// }
			if($request->master_rake_id){
				$query->where('master_rake_id',$request->master_rake_id);
				$data['master_rake_id'] = $request->master_rake_id; 
			}

			if($request->from_warehouse_id){
				$query->where('from_warehouse_id',$request->from_warehouse_id);
				$data['from_warehouse_id'] = $request->from_warehouse_id; 
			}
			if($request->dealer_id){
				$query->where('dealer_id',$request->dealer_id);
				$data['dealer_id'] = $request->dealer_id; 
			}
			if($request->product_id){
				$query->where('product_id',$request->product_id);
				$data['product_id'] = $request->product_id; 
			}
			if($request->product_company_id){
				$query->where('product_company_id',$request->product_company_id);
				$data['product_company_id'] = $request->product_company_id; 
			}
			$product_loadings = $query->with('token')->get();
			$data['product_loadings'] = $product_loadings;
			$data['total'] = $query->sum('quantity');
		}else{
			$data['product_loadings'] = $product_loadings = \App\ProductLoading::whereDate('created_at', '=', date('Y-m-d'))->with('token')->get();
			// $data['date']  = date('m/d/Y');
		}
		// dd($data);
		return view('dashboard.token.date-wise-loadings',$data);
	} 


	public function product_loading(Request $request){
		$data = array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		$data['invoice_types'] = \App\InvoiceType::where('is_active',1)->get();
		$data['companies'] = \App\Company::where('is_active',1)->where('for_invoice',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'master_rake_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/product-loading-list')
				->withErrors($validator)
				->withInput();
			}else{
				$master_rake = \App\MasterRake::where('id',$request->master_rake_id)->with('master_rake_products','users:id,name')->first();
				$query = \App\ProductLoading::query();

				if($request->product_id){
					$data['product_id'] = $request->product_id;
					$query->where('product_id',$request->product_id);	
				}

				if($request->user_id){
					$data['user_id'] = $request->user_id;
					$query->where('user_id',$request->user_id)	;
				}


				$product_loadings = $query->where('master_rake_id',$request->master_rake_id)->with('token')->get();
				$data['quantity'] = $query->sum('quantity');
				$data['product_loadings'] = $product_loadings;
				$data['master_rake_id'] = $request->master_rake_id;
				$data['master_rake_products'] = $master_rake->master_rake_products;

				$distict_users = array_unique(\App\ProductLoading::where('master_rake_id',$request->master_rake_id)->pluck('user_id')->toArray());
				$users = array();
				foreach ($distict_users as $distict_user) {
					array_push($users,array('id'=>$distict_user,'name'=>getModelById('User',$distict_user)->name));
				}

				$data['users'] = $users;



			}
		}else{
			$data['product_loadings'] = array();
			$data['quantity'] =0;
			// $product_loadings = \App\ProductLoading::with('token')->where('master_rake_id', '!=', '')->get();
			// $data['product_loadings'] = $product_loadings;

		}



		return view('dashboard.token.product-loading-list',$data);
	} 


	public function exportProductLoadings($master_rake_id){
		$product_loadings = \App\ProductLoading::where('master_rake_id',$master_rake_id)->with('token')->get();
		$export_data = array();
		foreach ($product_loadings as $key => $product_loading) {
			$loading_data = array();
			$loading_data['Loading Slip Number'] = $product_loading->id;
			$loading_data['Token Number'] = $product_loading->token_id;
			$loading_data['Product Company'] = $product_loading->product_company_name;
			$loading_data['Wagon Number'] = $product_loading->wagon_number;
			$loading_data['Product'] = $product_loading->product_name;
			$loading_data['Quantity'] = $product_loading->quantity;
			$loading_data['Transporter'] = $product_loading->transporter_name;
			$loading_data['Retailers'] = !is_null($product_loading->retailer_id) ? $product_loading->retailer_name:"";
			if($product_loading->loading_slip_type ==1){
				$party_name = getModelById('Dealer',$product_loading->dealer_id)->name."(".getModelById('Dealer',$product_loading->dealer_id)->address1.")";
			}else{
				$party_name = getModelById('Warehouse',$product_loading->warehouse_id)->name;
			}
			$loading_data['Party Name'] = $party_name;
			$loading_data['Truck Number'] = $product_loading->truck_number;
			array_push($export_data, $loading_data);

		}
		\Excel::create('Rake Summary', function($excel) use($export_data) {
			$excel->sheet('Rake Summary', function($sheet) use($export_data) {
				$sheet->fromArray($export_data);
			});
		})->export('xls');
	} 

	public function getProductLoading(){
		$data = array();
		$data['tokens'] = \App\Token::where('is_active',1)->get();
		$data['transporters'] = \App\Transporter::where('is_active',1)->get();
		$data['master_rakes'] = \App\MasterRake::all();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['units'] = \App\Unit::where('is_active',1)->get();
		return view('dashboard.token.product-loading',$data);
	} 

	public function postProductLoading(Request $request){
		$response = array();
		// dd($request->all());
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

			if($request->loading_slip_type == 1){
				$validator = \Validator::make($request->all(),
					array(
						'regular_token_id' =>'required',
						'regular_quantity' =>'required|integer',
						'regular_truck_number' =>'required',
						'regular_labour_name' =>'required',
						// 'regular_freight' =>'required',
						'regular_rate' =>'required',
					)
				);

			}else{
				$validator = \Validator::make($request->all(),
					array(
						'direct_master_rake_id' =>'required',
						'direct_product_id' =>'required',
						'direct_unit_id' =>'required',
						'direct_quantity' =>'required|integer',
						'direct_truck_number' =>'required',
						// 'direct_freight' =>'required',
						'direct_labour_name' =>'required',
						'direct_rate' =>'required',
						'direct_transporter_id' =>'required',
						'direct_warehouse_id' =>'required',
					)
				);
			}

			if($validator->fails())
			{
				$response['flag'] = false;
				$response['errors'] = $validator->getMessageBag();
			}else{
				if($request->loading_slip_type == 1){

					$token = \App\Token::where('id',$request->regular_token_id)->first();
					$product_loading = \App\ProductLoading::where('token_id',$request->regular_token_id)->where('product_id',$request->regular_product_id)->sum('quantity');
					$remaining_quantity = $token->quantity - $product_loading;
					if($product_loading && $remaining_quantity < $request->regular_quantity){
						$response['flag'] = false; 
						$response['errors']['regular_quantity'] = "quantity Should not be greater than Token Quantity (".$token->quantity."). Remaining Quantity is ".$remaining_quantity; 
					}
					else if($request->regular_quantity > $token->quantity){
						$response['flag'] = false; 
						$response['errors']['regular_quantity'] = "quantity Should not be greater than Token Quantity (".$token->quantity.")"; 
					}else{
						$product_loading =  new \App\ProductLoading();
						$product_loading->user_id = \Auth::user()->id;
						$product_loading->loading_slip_type = $request->loading_slip_type;
						$product_loading->token_id = $request->regular_token_id;
						if($request->regular_master_rake_id){
							$product_loading->master_rake_id = $request->regular_master_rake_id;
						}
						if($request->from_warehouse_id){
							$product_loading->from_warehouse_id = $request->from_warehouse_id;
						}

						if($token->to_type == 2){
							$product_loading->retailer_id = $token->retailer_id;
							$product_loading->retailer_name = getModelById('Retailer',$token->retailer_id)->name;	
						}

						$product_loading->dealer_id = $request->regular_dealer_id;
						$product_loading->dealer_name = getModelById('Dealer',$request->regular_dealer_id)->name;
						$product_loading->product_company_id = $request->regular_product_company_id;
						$product_loading->product_company_name = getModelById('ProductCompany',$request->regular_product_company_id)->name;
						$product_loading->product_id = $request->regular_product_id;
						if(!is_null($token->warehouse_id)){
							$product_loading->warehouse_id = $token->warehouse_id;
						}
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
						$product_loading->is_approved=0;

						if($product_loading->save()){

							$labour_payment =  new \App\LabourPayments();
							$labour_payment->user_id = \Auth::user()->id;
							$labour_payment->token_id = $request->regular_token_id;
							if($request->regular_master_rake_id){
								$labour_payment->master_rake_id = $request->regular_master_rake_id;
							}
							if($request->from_warehouse_id){
								$labour_payment->from_warehouse_id = $request->from_warehouse_id;
							}

							$labour_payment->product_loading_id = $product_loading->id;
							$labour_payment->product_id = $request->regular_product_id;
							$labour_payment->product_name = getModelById('Product',$request->regular_product_id)->name;
							$labour_payment->quantity = $request->regular_quantity;
							$labour_payment->unit_id = $token->unit_id;
							$labour_payment->unit_name = getModelById('Unit',$token->unit_id)->unit;
							$labour_payment->labour_name = $request->regular_labour_name;
							$labour_payment->rate = $request->regular_rate;
							$labour_payment->truck_number = $request->regular_truck_number;
							$labour_payment->save();

							if($token->token_type == 1){
							//inventory update
								if(!is_null($token->warehouse_id)){

									// $inventory = \App\Inventory::where('dealer_id',$request->regular_dealer_id)->where('warehouse_id',$token->warehouse_id)->where('product_brand_id',$token->product_company_id)->where('product_id',$request->regular_product_id)->first();
									// if(!is_null($inventory)){
									// 	$inventory->quantity = $inventory->quantity + $request->regular_quantity;
									// 	$inventory->save();
									// }else{
									// 	$inventory = new  \App\Inventory();
									// 	$inventory->dealer_id 			= $request->regular_dealer_id;
									// 	$inventory->warehouse_id 		= $token->warehouse_id;
									// 	$inventory->product_brand_id 		= $token->product_company_id;
									// 	$inventory->product_id 			= $request->regular_product_id;
									// 	$inventory->quantity 			= $request->regular_quantity;
									// 	$inventory->unit_id = $token->unit_id;
									// 	$inventory->save();
									// }
								}
							}else if($token->token_type == 2){
								$inventory = \App\Inventory::where('dealer_id',$request->regular_dealer_id)->where('warehouse_id',$token->from_warehouse_id)->where('product_brand_id',$token->product_company_id)->where('product_id',$request->regular_product_id)->first();
								$inventory->quantity  = $inventory->quantity - $request->regular_quantity;
								$inventory->save();
							}
							$response['flag'] = true;
							$response['message'] = "Loading Slip Generated Successfully";
						}else{
							$response['flag'] = false;
							$response['error'] = "Something Went Wrong";
						}

					}
				} else {

					$master_rake_product = \App\MasterRakeProduct::where('master_rake_id',$request->direct_master_rake_id)->where('product_id',$request->direct_product_id)->first();

					if(is_null($master_rake_product)){
						$response['flag'] = false; 
						$response['errors']['direct_product_id'] = "This Rake do not contains this Product";
					}else{

						$product_loading = \App\ProductLoading::where('master_rake_id',$request->direct_master_rake_id)->where('product_id',$request->direct_product_id)->sum('quantity');

						$remaining_quantity = $master_rake_product->quantity - $product_loading;
						if($product_loading && $remaining_quantity < $request->direct_quantity){
							$response['flag'] = false; 
							$response['errors']['direct_quantity'] = "quantity Should not be greater than Alloted Quantity (".$master_rake_product->quantity ."). Remaining Quantity is ".$remaining_quantity; 
						}else if(!$product_loading && $master_rake_product->quantity < $request->direct_quantity){
							$response['flag'] = false; 
							$response['errors']['direct_quantity'] = "quantity Should not be greater than Alloted Quantity (".$master_rake_product->quantity .")."; 
						}else{
							$product_loading =  new \App\ProductLoading();
							$product_loading->user_id = \Auth::user()->id;
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
							$product_loading->freight = $request->direct_freight;

							if($request->direct_wagon_no){
								$product_loading->wagon_number = $request->direct_wagon_no;
							}
							$product_loading->truck_number = $request->direct_truck_number;

							if($product_loading->save()){

								$labour_payment =  new \App\LabourPayments();
								$labour_payment->user_id = \Auth::user()->id;
								$labour_payment->master_rake_id = $request->direct_master_rake_id;
								$labour_payment->product_loading_id = $product_loading->id;
								$labour_payment->warehouse_id = $product_loading->warehouse_id;
								$labour_payment->product_id = $request->direct_product_id;
								$labour_payment->product_name = getModelById('Product',$request->direct_product_id)->name;
								$labour_payment->quantity = $request->direct_quantity;
								$labour_payment->unit_id = $request->direct_unit_id;
								$labour_payment->unit_name = getModelById('Unit',$request->direct_unit_id)->unit;
								$labour_payment->labour_name = $request->direct_labour_name;
								$labour_payment->rate = $request->direct_rate;
								$labour_payment->truck_number = $request->direct_truck_number;

								$labour_payment->save();

						//inventory update


								$inventory = \App\Inventory::where('product_company_id',$request->direct_product_company_id)->where('warehouse_id',$request->direct_warehouse_id)->where('product_id',$request->direct_product_id)->where('product_brand_id',$request->direct_product_company_id)->first();
								if(!is_null($inventory)){
									$inventory->quantity = $inventory->quantity + $request->direct_quantity;
									$inventory->save();
								}else{
									$inventory = new  \App\Inventory();
									$inventory->product_company_id 			= $request->direct_product_company_id;
									$inventory->product_brand_id 			= $request->direct_product_company_id;
									$inventory->warehouse_id 		= $request->direct_warehouse_id;
									$inventory->product_id 			= $request->direct_product_id;
									$inventory->quantity 			= $request->direct_quantity;
									$inventory->unit_id = $request->direct_unit_id;
									$inventory->save();
								}




								$response['flag'] = true;
								$response['message'] = "Loading Slip Generated Successfully";
							}else{
								$response['flag'] = false;
								$response['error'] = "Something Went Wrong";
							}
						}
					}
				}
			}
		}
		return response()->json($response);
	}

	public function printTaxInvoice(Request $request,$id){
		$data = array();
		$token =  \App\Token::where('id',$id)->where('is_active',1)->with('company')->first();
		if(is_null($token)){
			return redirect('user/generated-token')->with('error','Token Not found');
		}else{
			$data['invoice_data'] = array(
				"company_id"=>$request->company_id,
				"invoice_type_id"=>$request->invoice_type_id,
				"invoice_date"=>$this->dateConverter($request->invoice_date),
				"eway_bill_no"=>$request->eway_bill_no,
				"retailer_id"=>$token->retailer_id,
				"invoice_remark"=>$request->invoice_remark,
				"dispatched_through"=>$request->dispatched_through,
				"destination"=>$request->destination,
				"terms_of_delivery"=>$request->terms_of_delivery,
			);
			$products  = array();
			array_push($products, $token->product_id);

			$quantity  = array();
			array_push($quantity, $token->quantity);

			$rate  = array();
			array_push($rate, $token->rate);

			$unit  = array();
			array_push($unit, $token->unit_id);

			$data['product_id'] = $products; 
			$data['quantity'] = $quantity; 
			$data['product_rate'] = $rate; 
			$data['product_unit'] = $unit; 
			$data['company'] = \App\Company::where('id',$request->company_id)->first();
			return view('dashboard.invoice.print-tax-invoice',$data);

		} 
	}


	public function labourSlips(Request $request){
		$data = array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'master_rake_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/labour-slips')
				->withErrors($validator)
				->withInput();
			}else{
				$product_loadings = \App\LabourPayments::where('master_rake_id',$request->master_rake_id)->with('token')->get();
				$data['labour_payments'] = $product_loadings;
				$data['master_rake_id'] = $request->master_rake_id;
			}
		}else{
			$data['labour_payments'] = array();
		}
		return view('dashboard.token.labour-slips',$data);
	}

/*
 * Function to load freight payment form
 */
public function freightPayment()
{
	$data = array();
	$data['loading_slips'] = \App\ProductLoading::where('is_freight_paid',0)->with('token')->get();
	$data['freight_list'] = \App\FreightList::all();
	return view("dashboard.token.freight-payment", $data);
}
/*
 * Function to update freight payment data in product loadings table
 */
public function updateFreightPayment(Request $request)
{
	$response = array();
	$product_loading =  \App\ProductLoading::find($request->product_loading_id);
	$product_loading->qr_scan_count = 2;
	$product_loading->processing_step = 2;
	$product_loading->is_freight_paid = 1;
	$product_loading->toll_tax = $request->toll_tax_amount;
	$product_loading->freight_paid_amount = ($product_loading->freight * $product_loading->quantity) + $request->toll_tax_amount;
	$product_loading->freight_pay_date = date('Y-m-d H:i:s');
	$product_loading->freight_paid_by = \Auth::user()->id;
	if($product_loading->save()){
		$response['flag'] = true;
		$response['message'] = "Freight Paid Successfully.";
	}else{
		$response['flag'] = false;
		$response['error'] = "Something Went Wrong";
	}
	return response()->json($response);
}

/*
 * Function to load freight payment form
 */
public function labourPayment()
{
	return view("dashboard.token.pay-labour");
}

public function payLabour(Request $request){
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'labour_slip_id' 			=>'required',
			'amount_to_pay' 			=>'required'
		)
	);
	if($validator->fails()){
		$response['flag'] = false;
		$response['errors'] = $validator->getMessageBag();
	}else{
		$labour_payment = \App\LabourPayments::where('id',$request->labour_slip_id)->first();
		if(!is_null($labour_payment)){
			if($labour_payment->is_paid){
				$response['flag'] 			= false;
				$response['message'] = "Payment Already done for this slip";
			}else{
				
				$labour_payment->is_paid = 1;
				$labour_payment->paid_by = \Auth::id();
				$labour_payment->paid_amount = $labour_payment->paid_amount + $request->amount_to_pay;
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
	}
	return response()->json($response);
}


public function liftingReport(Request $request){
	$data = array();
	$data['acting_company'] = Session::get('acting_company');
	$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
	$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
	if ($request->isMethod('post')){
		
		$data['master_rake_id'] = $request->master_rake_id;
		$data['from_warehouse_id'] = $request->from_warehouse_id;

		$query = \App\ProductLoading::query();
		if($request->master_rake_id != 0){
			$query->where('master_rake_id',$request->master_rake_id);
		}

		if($request->from_warehouse_id != 0){
			$query->where('from_warehouse_id',$request->from_warehouse_id);
		}

		$loading  = $query->get();

		dd($loading);

	}else{
		$data['master_rake_id'] = 0;
		$data['from_warehouse_id'] = 0;
		$data['loading'] = array();
	}
	return view('dashboard.token.lifting-report',$data);
}

public function dateConverter($date)
{
	$temp_date = explode('/', $date);
	$new_date = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];
	return $new_date;
}


public function saveToken(Request $request){
		$acting_company = Session::get('acting_company');
		$response = array();
	
			
				$validator = \Validator::make($request->all(),
					array(
						
						'date_of_generation' =>'required',
						'product_company_id' =>'required',
						'dealer_id' =>'required',
						'product_id' =>'required',
						'quantity' =>'required|integer',
						'unit_id' =>'required',
					)
				);

				if($validator->fails())
				{
					$response['flag'] = false;
					$response['errors'] = $validator->getMessageBag();
				}else{
					$token =  new \App\Token();
					$token->company_id = $request->company_id;
					$token->token_type = $request->token_type;
					$token->from_warehouse_id = $request->from_warehouse_id;
					$token->to_type = $request->to_type;
					$token->warehouse_id = $request->warehouse_id;
					$token->dealer_id = $request->dealer_id;
					$token->retailer_id = $request->retailer_id;
					$token->product_company_id = $request->product_company_id;
					$token->product_id = $request->product_id;
					$token->unit_id = $request->unit_id;
					$token->date_of_generation = $request->date_of_generation;
					$token->quantity = $request->quantity;

					if($token->save()){
						$response['flag'] = true;
						$response['message'] = "Token Added Successfully";
					}else{
						$response['flag'] = false;
						$response['error'] = "Something Went Wrong";
					}
				}
				return response()->json($response);


			}



}
