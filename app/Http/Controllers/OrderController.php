<?php



namespace App\Http\Controllers;

use Mail;

use Illuminate\Http\Request;

use Session;

use Auth;

use DataTables;

use DB;

use Symfony\Component\HttpFoundation\RequestStack;



class OrderController extends Controller

{

	

	public function orders_filter(Request $request){

		ini_set('memory_limit', '-1');

				$data = array();

					$data['acting_company'] = Session::get('acting_company');



						$from = $request->from_date;

						$to = $request->to_date;

				if(!empty($from) && !empty($to)){

				$orders = \App\Order::query()->where('is_active',1)->whereBetween('created_at',[$from,$to])->orderBy('created_at','desc');	

				}else{ 



					// $orders = \App\Order::where('is_active',1)->orderBy('created_at','desc')->get();

					$orders = \App\Order::query()->where('is_active',1)->orderBy('created_at','desc');

					// $orders = \App\Order::join('loading_slips','orders.id','loading_slips.order_id')->select('orders.*','loading_slips.vehicle_no')->where('orders.is_active',1)->orderBy('created_at','desc')->get();

                    //$orders = \App\Order::query()->where('is_active',1)->orderBy('created_at','desc')

								//->select('id','retailer_id','product_id',\DB::raw('(DATE_FORMAT(created_at,"%d/%m/%y")) as date'));



				}

			



                // \DB::disconnect('orders');

				return Datatables::of($orders)

				->addIndexColumn()

					->addColumn('action', function($orders){

					

							$btn ='<td>';

							if($orders->order_status == 'requested'){

								$btn=$btn.	'<a  onclick="get_edit_order_form('.$orders->id.')" class="btn btn-xs btn-info">

							<i class="ace-icon fa fa-pencil bigger-120"></i>

						</a>';

						}

						if(Auth::user()->role_id==1 && $orders->order_status == 'approved' ){

							$btn=$btn.'<a  onclick="get_edit_order_form('.$orders->id.')" class="btn btn-xs btn-info">

								<i class="ace-icon fa fa-pencil bigger-120"></i>

							</a>';

						}



						if(Auth::user()->role_id==1 && $orders->order_status == 'cencel' ){

							$btn=$btn.'<a  onclick="get_edit_order_form('.$orders->id.')" class="btn btn-xs btn-info">

								<i class="ace-icon fa fa-pencil bigger-120"></i>

							</a>';

						}



						if($orders->order_status == 'approved'){

							$btn=$btn.'<a href="/user/print-order-token/'.$orders->id.'" class="btn btn-xs btn-info" >

								<i class="ace-icon fa fa-print bigger-120"></i>

							</a>';

							}

												

						$btn=$btn.'</td>';

					



						return $btn;

				})



			->addColumn('order_status', function($orders){

			$role_id=Auth::user()->role_id;

			

				$btn =' <td id="status_'.$orders->id.'">';

				if($role_id != 1){

					if($orders->order_status == 'approved'){

						$btn=$btn.'<span class="badge badge-success">Approved</span>';

					}else{

						$btn=$btn.'<span class="badge badge-danger">No Approve</span>';

					}



					



				}else{

					if($orders->order_status == 'requested')

					

					{

						$btn=$btn.'<a class="btn btn-sm btn-warning" onclick="approved_order_now('.$orders->id.')">Approve</a>';

						

					}  

					if($orders->order_status == 'approved'){

					$btn=$btn.' <span class="badge badge-success">Approved</span>';

					}

					if($orders->order_status == 'cencel'){

						$btn=$btn.'<span class="badge badge-danger">Cencel</span>';

					}

				

				}

				



				$btn=$btn.'</td>';		

				



			return $btn;

			})



			->addColumn('loading_status', function($orders){



			    $role_id=Auth::user()->role_id;
			    $loadingSlips = \App\LoadingSlip::where('order_id',$orders->id)->first();


				$btn =' <td id="status_'.$orders->id.'">';









				if($role_id !=11){

					if($orders->order_status == 'approved'  && $orders->remaining_qty>0 ){

						$btn=$btn.'<a href="javascript:void(0);" onclick="get_add_loading_slip_form('.$orders->id.')">

						<span class="badge badge-info">Generate Loading Slip</span>

					</a>';
				
					

					}

					if($orders->order_status == 'approved' &&  $orders->remaining_qty==0 ){

					$btn=$btn.'<span class="badge badge-success">Generated</span>';
					$loadingSlips = \App\LoadingSlip::where('order_id',$orders->id)->first();
					if($loadingSlips != null) {
						if($loadingSlips->bilti_status==1)
						{
							$btn=$btn.'<a href="/user/print-bilti/'.$loadingSlips->id.'" target="_blank">
			
							<span class="badge badge-success">Print Bilti</span>
			
							</a>';	
						}
					}	

					}



				}else{



					if($orders->order_status == 'approved'  && $orders->remaining_qty>0 ){

						$btn=$btn.'<span class="badge badge-info">Generate Loading Slip</span>

					</a>';

					}

					if($orders->order_status == 'approved' &&  $orders->remaining_qty==0 ){

					$btn=$btn.'<span class="badge badge-success">Generated</span>';
					$loadingSlips = \App\LoadingSlip::where('order_id',$orders->id)->first();
					if($loadingSlips != null) {
						if($loadingSlips->bilti_status==1)
						{
							$btn=$btn.'<a href="/user/print-bilti/'.$loadingSlips->id.'" target="_blank">
			
							<span class="badge badge-success">Print Bilti</span>
			
							</a>';	
						}
					}	

					}

				}

					



				



				$btn=$btn.'</td>';		

				





			return $btn;

			})
			

		
			// 	->addColumn('truk_no', function($orders){



			

			// 	$btn =' <td>';

				

					

				

			// 		$btn=$btn.$btn=$btn.truckNoGet('ProductLoading',$orders->id)->vehicle_no;

					

					



				



			// 	$btn=$btn.'</td>';		





			// return $btn;

			// })



			->addColumn('invoice_status', function($orders){

			

			    $role_id=Auth::user()->role_id;

				$btn =' <td id="status_'.$orders->id.'">';





				if($role_id !=11){

					if($orders->order_status == 'approved' && $orders->remaining_qty == 0 && $orders->loading_status==1 ){

						$btn=$btn.' <a href="javascript:void(0);" onclick="get_loading_slips('.$orders->id.')">

						<span class="badge badge-warning">Genrate Invoice</span>

					</a>';

					

		

					}

					

				

					if($orders->order_status == 'approved' && $orders->remaining_qty==0 && $orders->invoice_status==1){

					$btn=$btn.' <a href="javascript:void(0);" onclick="get_loading_slips('.$orders->id.')">

					<span class="badge badge-success">Invoice Generated</span>

				</a>';

					}



				}else{

					if($orders->order_status == 'approved' && $orders->remaining_qty == 0 && $orders->loading_status==1 ){

						$btn=$btn.'

						<span class="badge badge-warning">Genrate Invoice</span>

					</a>';

					

		

					}

					

				

					if($orders->order_status == 'approved' && $orders->remaining_qty==0 && $orders->invoice_status==1){

					$btn=$btn.'

					<span class="badge badge-success">Invoice Generated</span>

				</a>';

					}



				}

				

					

				

				



						

					$btn=$btn.'</td>';

				

			

			return $btn;

		})



			->addColumn('order_date', function($orders){

				

				

					$btn =' <td>';

					$btn=$btn.date('d/m/Y',strtotime($orders->created_at));

					

					$btn=$btn.'</td>';

				

				return $btn;

			})



			->addColumn('rake_godown', function($orders){

			

				$btn ='<td>';

				$btn=$btn.'<input type="hidden" name="id" id="id_'. $orders->id.'" value="'.$orders->id.'">';

			if($orders->order_from == 1 ){

				$btn=$btn.'Rake ('.getModelById("RakePoint",$orders->rake_point)->rake_point.')';

			}else{

				$btn=$btn.'Godown ('.getModelById("Warehouse",$orders->from_warehouse_id)->name.')';

			}

				

				$btn=$btn.'</td>';

			return $btn;

			})



			->addColumn('dealer_name', function($orders){

			$role_id=Auth::user()->role_id;

			

				$btn =' <td>';

				

			

				$btn=$btn."<b>".getdealer($orders->dealer_id)->name."</b>";

			

				

				$btn=$btn.'</td>';

			



			return $btn;

			})

				->addColumn('retailer_name', function($orders){

				

						$btn =' <td>';

						

					

						$btn=$btn.getretailer($orders->retailer_id)->name."</b> &nbsp;&nbsp;[".$orders->retailer_id."]";

					

						

						$btn=$btn.'</td>';

				





				return $btn;

			})



			->addColumn('product_name', function($orders){

			$role_id=Auth::user()->role_id;

			$btn =' <td>';

				

			

				$btn=$btn.getModelById('Product',$orders->product_id)->name;

			

				

				$btn=$btn.'</td>';

			



			



			return $btn;

			})



			// ->addColumn('destination', function($orders){

			

			

			// 	$btn =' <td>';

			// 	if($orders->dealer_id !=null && $orders->retailer_id ==""  ){

			// 		$location_name=\App\Location::where('location_id', getdealer($orders->dealer_id)->destination_code)->first();





			// 	}else{

            //    $location_name=\App\Location::where('location_id', getretailer($orders->retailer_id)->destination_code)->first();



			// 	}





			// 	if($location_name !=null){

			// 			$location=$location_name->name;

			// 		}else{

			// 			$location="";

			// 		}

			

			// 	$btn=$btn."<b>".$location."</b>";

			

				

			// 	$btn=$btn.'</td>';

			



			// return $btn;

			// })

			->addColumn('destination', function($orders){

			

			

				$btn =' <td>';

				if($orders->retailer_address !=null){

					$location=$orders->retailer_address;

				}else{

                    if($orders->dealer_id !=null && $orders->retailer_id ==""  ){

						$location_name=\App\Location::where('location_id', getdealer($orders->dealer_id)->destination_code)->first();

						if($location_name !=null){

							$location=$location_name->name;

						}else{

							$location="";

						}

	

					}else{

				   $location_name=\App\Location::where('location_id', getretailer($orders->retailer_id)->destination_code)->first();

				   if($location_name !=null){

					$location=$location_name->name;

				}else{

					$location="";

				}

					}     

				}

			

				$btn=$btn."<b>".$location."</b>";

			

				

				$btn=$btn.'</td>';

			



			return $btn;

			})

			->addColumn('unit', function($orders){

				$role_id=Auth::user()->role_id;

				$btn =' <td>';

					

				

					$btn=$btn.getModelById('Unit',$orders->unit_id)->unit;

				

					

					$btn=$btn.'</td>';

				



				



				return $btn;

			})





			->addColumn('truck_no', function($orders){

				//$role_id=Auth::user()->role_id;

				

					$btn =' <td>';

					

				

					$truck_numbers=\App\LoadingSlip::where('order_id',$orders->id)->pluck('vehicle_no');



					



					

                    $tn=[];

					foreach($truck_numbers as $k => $v){

					  array_push($tn,$v);	

					}

					$btn=$btn.implode(',',$tn);

				

					

					$btn=$btn.'</td>';

				

	

				return $btn;

				})





			->addColumn('invoice_date', function($orders){

				

				

					$btn =' <td>';

					

				

					// $invoice_date=\App\LoadingSlip::where('order_id',$orders->id)->pluck('invoice_date');

					$invoice_date=\App\LoadingSlip::where('order_id',$orders->id)->where('slip_status','dispatched')->groupBy('order_id')->pluck('invoice_date');





					



					

                    $tn=[];

					foreach($invoice_date as $k => $v){

					  array_push($tn,$v);	

					}

					$btn=$btn.implode(',',$tn);

				

					

					$btn=$btn.'</td>';

				

	

				return $btn;

				})

			

				->rawColumns(['action','order_status','loading_status','invoice_status','invoice_date','order_date','rake_godown','dealer_name','retailer_name','product_name','unit','destination','truck_no'])

				

				

				->make(true);

	}	

	public function orders(Request $request){



		return view('dashboard.order.orders');

	}



	public function change_status(Request $request){

		$order = \App\Order::where('id',$request->order_id)->first();

		if($order != ""){

			$order->order_status = 'approved';

			$order->save();

			$response['flag'] = true;

			$response['message'] = "Order Approved Successfully !!";

		}else{

			$response['flag'] = false;

			$response['message'] = "Invalid Order Id !!";

		}

		return response()->json($response);

	}





	public function new_order(){

		$data = array();

		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->where('is_closed',0)->get();

		$data['rake_points'] = \App\RakePoint::where('is_active',1)->get();

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

		return view('dashboard.order.add-order',$data);

	} 



	public function get_product_qty(Request $request) {	

		// if($request->name == 'rake_point') {

		// 	$product_qtys = \App\Inventory::where('rake_point_id', $request->warehouse_id)->with('product')->with('unit')->get();

		// }	

		// if($request->name == 'warehouse') {

		// 	$product_qtys = \App\Inventory::where('warehouse_id', $request->warehouse_id)->with('product')->with('unit')->get();

		// }



		if($request->name == 'rake_point') {

			// $rake_point_id=$request->warehouse_id;

			

			$products= \App\Product::where('is_active',1)->get();



		    $product_array=[];



		   foreach($products as $key=>$value){

			$qty=\App\Inventory::where('rake_point_id', $request->warehouse_id)->where('product_id',$value->id)->sum('quantity');

			

			$product_array[$key]['id']=$value->id;

			$product_array[$key]['product_name']=$value->name;

			$product_array[$key]['qty']=$qty;

			$product_array[$key]['demage_qty']='';

			$product_array[$key]['unit']='Bag';







		   }

		

		}	

		if($request->name == 'warehouse') {

			

		

			$products= \App\Product::where('is_active',1)->get();



		    $product_array=[];



		   foreach($products as $key=>$value){

			$qty=\App\Inventory::where('warehouse_id', $request->warehouse_id)->where('product_id',$value->id)->sum('quantity');

			$damage_qty=\App\Inventory::where('warehouse_id', $request->warehouse_id)->where('product_id',$value->id)->sum('damage_qty');

			$product_array[$key]['id']=$value->id;

			$product_array[$key]['product_name']=$value->name;

			$product_array[$key]['qty']=$qty;

			$product_array[$key]['demage_qty']=$damage_qty;



			$product_array[$key]['unit']='Bag';







		   }

		}



// dd($product_array);

		

		// print_r(json_decode($product_qtys));

		// exit;

		// // foreach($product_qtys as $product_qty) {

		// 	$product_qty->quantity; 

		// 	$product_qty->product_id; 

		// 	$product_qty->product->name; 

		// }

		$response = array();

		if($product_array != null) {

			$response['product_qtys'] = $product_array;

			$response['success'] = true;

		} else {

			$response['error'] = true;

			$response['msg'] = "No Product found.";

		}

		



		// // print_r(json_decode($product_qty));

		// dd($product_qtys);

	  //return view('dashboard.order.add-order',$data);

		return response()->json($response);

	}



	





	public function post_order(Request $request){

     //dd($request->all());

		$acting_company = Session::get('acting_company');

		$response = array();

		if($request->order_from == 1){

			$validator = \Validator::make($request->all(),

				array(

					'rake_point' => 'required',

				//	'despatch_location' =>'required',

					//'retailer_id' =>'required',

					'dealer_id' =>'required',

					'product_id' =>'required',

					'quantity' =>'required|integer'

				)

			);



		}elseif($request->order_from == 2){



			$validator = \Validator::make($request->all(),

				array(

					'from_warehouse_id' =>'required',

					//'despatch_location' =>'required',

					'dealer_id' =>'required',

					//'retailer_id' =>'required',

					'product_id' =>'required',

					'quantity' =>'required|integer'

				)

			);

		}



		if($validator->fails()){

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$acting_company = Session::get('acting_company');

			$order = new \App\Order();

			$order->company_id = $acting_company;

			$order->order_from = $request->order_from;

			$order->dealer_id = $request->dealer_id;

			//$order->despatch_location = $request->despatch_location;

			if($request->retailer_id != ""){

				$order->retailer_id = $request->retailer_id;

			}else{

				$order->retailer_id = 0;

			}

			

			if($request->order_from == 1){

				$order->rake_point = $request->rake_point;

				$order->from_warehouse_id = null;

			}else{

				$order->rake_point = null;

				$order->from_warehouse_id = $request->from_warehouse_id;

			}

			$order->product_company_id = 1;

			$order->product_id = $request->product_id;

			$order->unit_id = 1;

			$order->quantity = $request->quantity;

			$order->remaining_qty = $request->quantity;

			$order->retailer_address = $request->retailer_address;

			$order->phone_number = $request->phone_number;

			$order->remark = $request->remark;

			$order->user_id = Auth::user()->id;

			$order->save();



			



			$response['flag'] = true;

			$response['order_id'] = $order->id;

			$response['message'] = "Order Added Successfully !!";

		}



		return response()->json($response);





	}



	public function get_address_of_retailer(Request $request){

		



		$retailer = \App\Retailer::where('unique_code',$request->retailer_id)->first();

		

		return json_encode(['success'=>true,'retailer'=>$retailer]);

	}

	public function get_retailer(Request $request){



		$retailers = \App\Retailer::where(['dealer_id'=>$request->dealer_id,'is_active'=>1])->get();

		

		return json_encode(['success'=>true,'retailers'=>$retailers]);

	}





	public function get_edit_order($id){



		$acting_company = Session::get('acting_company');

		$data = array();

		$order =  \App\Order::where('id',$id)->where('is_active',1)->first();

		if(is_null($order)){

			return redirect('user/orders')->with('error','Order Not found');

		}else{

			$data['order'] = $order;

			$retailer = \App\Retailer::where('id',$order->retailer_id)->first();

			$data['retailer'] = $retailer;

			$data['master_rakes'] = \App\MasterRake::where('is_active',1)->where('is_closed',0)->get();

			$data['rake_points'] = \App\RakePoint::where('is_active',1)->get();

			$data['destinations'] = \DB::table('destinations')->get();

			$data['companies'] = \App\Company::where('is_active',1)->get();

			$data['dealers'] = \App\Dealer::where('is_active',1)->get();

			$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();

			$data['retailers'] = \App\Retailer::where('is_active',1)->where('dealer_id',$order->dealer_id)->get();

			// dd($data['retailers']);

			// die;

			$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();

			$data['products'] = \App\Product::where('is_active',1)->get();

			$data['units'] = \App\Unit::where('is_active',1)->get();

			$data['warehouse_keepers'] = \App\User::where('is_active',1)->where('role_id',3)->get();

			$data['acting_company'] = Session::get('acting_company');

        

			return view('dashboard.order.edit-order',$data);

		}

	}

	public function update_order(Request $request){



		

		

		$acting_company = Session::get('acting_company');

		$response = array();

		if($request->order_from == 1){

			$validator = \Validator::make($request->all(),

				array(

					'rake_point' => 'required',

					// 'despatch_location' =>'required',

					//'retailer_id' =>'required',

					'dealer_id' =>'required',

					'product_id' =>'required',

					'quantity' =>'required|integer'

				)

			);



		}elseif($request->order_from == 2){



			$validator = \Validator::make($request->all(),

				array(

					'from_warehouse_id' =>'required',

					// 'despatch_location' =>'required',

					'dealer_id' =>'required',

					//'retailer_id' =>'required',

					'product_id' =>'required',

					'quantity' =>'required|integer'

				)

			);

		}



		if($validator->fails()){

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			$acting_company = Session::get('acting_company');

			$order =  \App\Order::where('id',$request->order_id)->first();

			if($order != null){

			



				if(Auth::user()->role_id==1){

						$order->company_id = $acting_company;

						$order->order_from = $request->order_from;

						$order->dealer_id = $request->dealer_id;

						$order->despatch_location = $request->despatch_location;

						if($request->retailer_id != ""){

							$order->retailer_id = $request->retailer_id;

						}else{

							$order->retailer_id = 0;

						}

						if($request->order_from == 1){

							$order->rake_point = $request->rake_point;

							$order->from_warehouse_id = null;

						}else{

							$order->rake_point = null;

							$order->from_warehouse_id = $request->from_warehouse_id;

						}

						$order->product_id = $request->product_id;



						if($order->loading_status=='1'|| $order->invoice_status=='1'){

						$order->remaining_qty = $order->remaining_qty;	

						}else{

							$order->remaining_qty = $request->quantity;

						}





						$order->quantity = $request->quantity;

						

						$order->retailer_address = $request->retailer_address;

						$order->phone_number = $request->phone_number;

						$order->remark = $request->remark;

						$is_loading=\App\LoadingSlip::where('order_id',$order->id)->first();



					if($is_loading !=null){



						if($request->order_from == 1){

							\DB::table('loading_slips')->where('order_id',$request->order_id)->update(['rake_point'=> $request->rake_point,'from_warehouse_id'=>null,'dealer_id'=>$order->dealer_id,'retailer_id'=>$order->retailer_id,'quantity'=>$request->quantity,'order_from'=>$order->order_from,'despatch_location'=>$order->despatch_location,'product_company_id'=>$order->product_company_id,'product_id'=>$order->product_id,'unit_id'=>$order->unit_id,'user_id'=>Auth::user()->id]);

							   // $is_loading->order_from = $order->order_from;



							  

						}else{

							\DB::table('loading_slips')->where('order_id',$request->order_id)->update(['rake_point'=> null,'from_warehouse_id'=>$request->from_warehouse_id,'dealer_id'=>$order->dealer_id,'retailer_id'=>$order->retailer_id,'quantity'=>$request->quantity,'order_from'=>$order->order_from,'despatch_location'=>$order->despatch_location,'product_company_id'=>$order->product_company_id,'product_id'=>$order->product_id,'unit_id'=>$order->unit_id,'user_id'=>Auth::user()->id]);

							 // $is_loading->order_from = $order->order_from;



						}

						//  $is_loading->despatch_location = $order->despatch_location;

						//  $is_loading->product_company_id = $order->product_company_id;

					 //      $is_loading->product_id = $order->product_id;

					 //     $is_loading->unit_id = $order->unit_id;

					 //     $is_loading->user_id =Auth::user()->id;

						// $is_loading->save();



					}



					   if($request->order_status==''){

		                	$order->order_status = trim($order->order_status);

		                	

		                }else{





		                	$order->order_status = trim($request->order_status);



							if($request->order_status=='cencel' && $is_loading !=''){

								if($order->order_from==1){

									$is_inventroy=\App\Inventory::where(['rake_point_id'=>$order->rake_point,'product_brand_id'=>$order->product_company_id,'product_id'=>$order->product_id,'unit_id'=>$order->unit_id])->first();

									if($is_inventroy!=''){

										$is_inventroy->quantity=$is_inventroy->quantity+$is_loading->quantity;

										$is_inventroy->save();



									}else{

										$inventroy= new \App\Inventory();

										$inventroy->rake_point_id=$order->rake_point;

										$inventroy->product_company_id=$order->product_company_id;

										$inventroy->product_brand_id=$order->product_company_id;

										$inventroy->product_id=$order->product_id;

										$inventroy->unit_id=$order->unit_id;

										$inventroy->quantity=$is_loading->quantity;

										$inventroy->save();



									}



								}else{



									$is_inventroy=\App\Inventory::where(['warehouse_id'=>$order->from_warehouse_id,'product_brand_id'=>$order->product_company_id,'product_id'=>$order->product_id,'unit_id'=>$order->unit_id])->first();

									// dd($is_loading);

									if($is_inventroy!=''){

										$is_inventroy->quantity=$is_inventroy->quantity+$is_loading->quantity;

										$is_inventroy->save();



									}else{

										$inventroy= new \App\Inventory();

										$inventroy->warehouse_id=$order->from_warehouse_id;

										$inventroy->product_company_id=$order->product_company_id;

										$inventroy->product_brand_id=$order->product_company_id;

										$inventroy->product_id=$order->product_id;

										$inventroy->unit_id=$order->unit_id;

										$inventroy->quantity=$is_loading->quantity;

										$inventroy->save();



									}



								}

								

							}

		                }



						

						$order->save();

						$response['flag'] = true;

				        $response['message'] = "Order Updated Successfully !!";



				}else{



					if($order->loading_status!=1 || $order->invoice_status!=1){



						$order->company_id = $acting_company;

						$order->order_from = $request->order_from;

						$order->dealer_id = $request->dealer_id;

						$order->despatch_location = $request->despatch_location;

						if($request->retailer_id != ""){

							$order->retailer_id = $request->retailer_id;

						}else{

							$order->retailer_id = 0;

						}

						if($request->order_from == 1){

							$order->rake_point = $request->rake_point;

							$order->from_warehouse_id = null;

						}else{

							$order->rake_point = null;

							$order->from_warehouse_id = $request->from_warehouse_id;

						}

						$order->product_id = $request->product_id;



					

						

		                $order->remaining_qty = $request->quantity;

		                if($request->order_status==''){

		                	$order->order_status = trim($order->order_status);

		                	

		                }

						





						$order->quantity = $request->quantity;

						

						$order->retailer_address = $request->retailer_address;

						$order->phone_number = $request->phone_number;

						$order->remark = $request->remark;

						$is_loading=\App\LoadingSlip::where('order_id',$request->order_id);



					if($is_loading !=null){



						if($request->order_from == 1){

							\DB::table('loading_slips')->where('order_id',$request->order_id)->update(['rake_point'=> $request->rake_point,'from_warehouse_id'=>null,'dealer_id'=>$order->dealer_id,'retailer_id'=>$order->retailer_id,'order_from'=>$order->order_from,'despatch_location'=>$order->despatch_location,'product_company_id'=>$order->product_company_id,'product_id'=>$order->product_id,'unit_id'=>$order->unit_id,'user_id'=>Auth::user()->id]);

							 // $is_loading->order_from = $order->order_from;

							// $is_loading->rake_point = $request->rake_point;

							// $is_loading->from_warehouse_id = null;

						}else{

							\DB::table('loading_slips')->where('order_id',$request->order_id)->update(['rake_point'=> null,'from_warehouse_id'=>$request->from_warehouse_id,'dealer_id'=>$order->dealer_id,'retailer_id'=>$order->retailer_id,'order_from'=>$order->order_from,'despatch_location'=>$order->despatch_location,'product_company_id'=>$order->product_company_id,'product_id'=>$order->product_id,'unit_id'=>$order->unit_id,'user_id'=>Auth::user()->id]);

							 // $is_loading->order_from = $order->order_from;



						}

					

						//   $is_loading->despatch_location = $order->despatch_location;

						//  $is_loading->product_company_id = $order->product_company_id;

					 //      $is_loading->product_id = $order->product_id;

					 //     $is_loading->unit_id = $order->unit_id;

					 //     $is_loading->user_id =Auth::user()->id;

						// $is_loading->save();



						}



					

					$order->save();

					$response['flag'] = true;

			    	$response['message'] = "Order Updated Successfully !!";



			     }else{

			     	$response['flag'] = false;

			    	$response['message'] = "Not Update !!";





			     }



				}

				

				

			}else{

				$response['flag'] = false;

				$response['message'] = "Invalid Order Id !!";

			}

		}







		 return response()->json($response);





	}





	// public function update_order(Request $request){

		

	// 	$acting_company = Session::get('acting_company');

	// 	$response = array();

	// 	if($request->order_from == 1){

	// 		$validator = \Validator::make($request->all(),

	// 			array(

	// 				'rake_point' => 'required',

	// 				// 'despatch_location' =>'required',

	// 				//'retailer_id' =>'required',

	// 				'dealer_id' =>'required',

	// 				'product_id' =>'required',

	// 				'quantity' =>'required|integer'

	// 			)

	// 		);



	// 	}elseif($request->order_from == 2){



	// 		$validator = \Validator::make($request->all(),

	// 			array(

	// 				'from_warehouse_id' =>'required',

	// 				// 'despatch_location' =>'required',

	// 				'dealer_id' =>'required',

	// 				//'retailer_id' =>'required',

	// 				'product_id' =>'required',

	// 				'quantity' =>'required|integer'

	// 			)

	// 		);

	// 	}



	// 	if($validator->fails()){

	// 		$response['flag'] = false;

	// 		$response['errors'] = $validator->getMessageBag();

	// 	}else{

	// 		$acting_company = Session::get('acting_company');

	// 		$order =  \App\Order::where('id',$request->order_id)->first();

	// 		if($order != null){

	// 			$order->company_id = $acting_company;

	// 			$order->order_from = $request->order_from;

	// 			$order->dealer_id = $request->dealer_id;

	// 			// $order->despatch_location = $request->despatch_location;

	// 			if($request->retailer_id != ""){

	// 				$order->retailer_id = $request->retailer_id;

	// 			}else{

	// 				$order->retailer_id = 0;

	// 			}

	// 			if($request->order_from == 1){

	// 				$order->rake_point = $request->rake_point;

	// 				$order->from_warehouse_id = null;

	// 			}else{

	// 				$order->rake_point = null;

	// 				$order->from_warehouse_id = $request->from_warehouse_id;

	// 			}

	// 			$order->product_id = $request->product_id;

	// 			$order->quantity = $request->quantity;

	// 			// $order->remaining_qty = $request->quantity;

	// 			$order->retailer_address = $request->retailer_address;

	// 			$order->phone_number = $request->phone_number;

	// 			$order->remark = $request->remark;

	// 			$is_loading=\App\LoadingSlip::where('order_id',$request->order_id);



	// 		if($is_loading !=null){



	// 			if($request->order_from == 1){

	// 				\DB::table('loading_slips')->where('order_id',$request->order_id)->update(['rake_point'=> $request->rake_point,'from_warehouse_id'=>null]);

	// 				// $is_loading->rake_point = $request->rake_point;

	// 				// $is_loading->from_warehouse_id = null;

	// 			}else{

	// 				\DB::table('loading_slips')->where('order_id',$request->order_id)->update(['rake_point'=> null,'from_warehouse_id'=>$request->from_warehouse_id]);



	// 			}

	// 			// $is_loading->save();



	// 		}



	// 			$order->order_status = trim($request->order_status);

	// 			$order->save();

	// 			$response['flag'] = true;

	// 			$response['message'] = "Order Updated Successfully !!";

	// 		}else{

	// 			$response['flag'] = false;

	// 			$response['message'] = "Invalid Order Id !!";

	// 		}

	// 	}







	// 	return response()->json($response);





	// }





	public function print_order_token($order_id){



		$data = array();

		$acting_company = Session::get('acting_company');

		$order =  \App\Order::where('id',$order_id)->where('is_active',1)->first();

		if(is_null($order)){

			return redirect('user/orders')->with('error','Order Not found');

		}else{

			$data['company'] = \App\Company::where('id',$acting_company)->first();

			$data['order'] = $order;

			return view('dashboard.order.print-order-token',$data);

		} 

	}

	public function print_bilti($slip_id){

	
		
		$data = array();

		$acting_company = Session::get('acting_company');

		$loadingSlip = \App\LoadingSlip::where('id',$slip_id)->first();

		if(is_null($loadingSlip)){

			return redirect('user/orders')->with('error','Order Not found');

		}else{

			$order = \App\Order::where('id',$loadingSlip->order_id)->first();
			$data['order']=$order;
			$company = \App\ProductCompany::where('id',$order->company_id)->first();
			$data['company']=$company;
			$dealer = \App\Dealer::where('unique_id',$order->dealer_id)->first();
			$data['dealer']=$dealer;
			$bilti = \App\Bilti::where('loading_slip_id',$loadingSlip->id)->first();
			$data['bilti']=$bilti;
			$product = \App\Product::where('id',$order->product_id)->first();
			$data['product']=$product;
			$driverName = \App\Transporter::where('id',$loadingSlip->transporter_id)->first();
			$data['driverName']=$driverName;
			$data['loadingSlip']=$loadingSlip;
			return view('dashboard.order.print-bilti',$data);

		} 

	}






	public function loading_slips(){

		$loading_slips = \DB::table('loading_slips')->join('dealers','dealers.unique_id','loading_slips.dealer_id')->join('retailers','retailers.unique_code','loading_slips.retailer_id')->join('product_companies','product_companies.id','loading_slips.product_company_id')->join('products','products.id','loading_slips.product_id')->join('units','units.id','loading_slips.unit_id')->join('transport_modes','transport_modes.id','loading_slips.transport_mode')->join('transporters','transporters.id','loading_slips.transporter_id')->leftjoin('rake_points','rake_points.id','loading_slips.rake_point')->leftjoin('warehouses','warehouses.id','loading_slips.from_warehouse_id')->where('loading_slips.slip_status','slip_generated')->select('loading_slips.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name','transporters.name as transporter_name','transport_modes.name as transport_mode_name')->orderBy('id','desc')->get();

		$data['loading_slips'] = $loading_slips;

		return view('dashboard.order.loading-slips',$data);

	}



	public function create_invoice($loading_slip_id){

	

		$loading_slip = \DB::table('loading_slips')->join('dealers','dealers.unique_id','loading_slips.dealer_id')->join('retailers','retailers.unique_code','loading_slips.retailer_id')->join('product_companies','product_companies.id','loading_slips.product_company_id')->join('products','products.id','loading_slips.product_id')->join('units','units.id','loading_slips.unit_id')->join('transport_modes','transport_modes.id','loading_slips.transport_mode')->join('transporters','transporters.id','loading_slips.transporter_id')->leftjoin('rake_points','rake_points.id','loading_slips.rake_point')->leftjoin('warehouses','warehouses.id','loading_slips.from_warehouse_id')->where('loading_slips.id',$loading_slip_id)->where('loading_slips.slip_status','slip_generated')->select('loading_slips.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name','transporters.name as transporter_name','transport_modes.name as transport_mode_name')->first();

		

		if($loading_slip != null){

			$data['loading_slip'] = $loading_slip;

			return view('dashboard.order.create-invoice',$data);

		}else{

			return redirect('user/loading-slips')->with('error','Invalid loading Slip Id !');

		}

		

	}



	public function create_invoice_now(Request $request){

 

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

			$acting_company = Session::get('acting_company');



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





	public function all_invoices(){

		$invoices = \DB::table('loading_slips')->join('dealers','dealers.unique_id','loading_slips.dealer_id')->join('retailers','retailers.unique_code','loading_slips.retailer_id')->join('product_companies','product_companies.id','loading_slips.product_company_id')->join('products','products.id','loading_slips.product_id')->join('units','units.id','loading_slips.unit_id')->join('transport_modes','transport_modes.id','loading_slips.transport_mode')->join('transporters','transporters.id','loading_slips.transporter_id')->leftjoin('rake_points','rake_points.id','loading_slips.rake_point')->leftjoin('warehouses','warehouses.id','loading_slips.from_warehouse_id')->where('loading_slips.slip_status','dispatched')->select('loading_slips.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name','transporters.name as transporter_name','transport_modes.name as transport_mode_name')->get();

		$data['invoices'] = $invoices;

		return view('dashboard.order.invoices',$data);

	}





	public function print_loading_slip_invoice($id){



		return view('dashboard.order.print-invoice');

	}





	public function add_loading(Request $request){

		

		$id = $request->id;

		// dd($id);

		

		// $data['orders'] = \App\Order::where('is_active',1)->where('id', $id)->orderBy('created_at','desc')->get();

		$data['orders'] = \DB::table('orders')->join('dealers','dealers.unique_id','orders.dealer_id')->join('retailers','retailers.unique_code','orders.retailer_id')->join('product_companies','product_companies.id','orders.product_company_id')->join('products','products.id','orders.product_id')->join('units','units.id','orders.unit_id')->leftjoin('rake_points','rake_points.id','orders.rake_point')->leftjoin('warehouses','warehouses.id','orders.from_warehouse_id')->select('orders.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.brand_name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name')->where('orders.order_status','approved')->where('orders.id',$id)->get();

		// dd($data);

		$data['transport_modes'] = \DB::table('transport_modes')->get();

		$data['transporters'] = \DB::table('transporters')->where('is_active',1)->get();



		return view('dashboard.order.add-loading',$data);

	}



	public function get_order_details(Request $request){

        

		$order = \DB::table('orders')->join('dealers','dealers.unique_id','orders.dealer_id')->join('retailers','retailers.unique_code','orders.retailer_id')->join('product_companies','product_companies.id','orders.product_company_id')->join('products','products.id','orders.product_id')->join('units','units.id','orders.unit_id')->leftjoin('rake_points','rake_points.id','orders.rake_point')->leftjoin('warehouses','warehouses.id','orders.from_warehouse_id')->select('orders.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.brand_name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name')->where('orders.order_status','approved')->where('orders.id',$request->order_id)->first();



		return json_encode(['success'=> true, 'order'=>$order]);

	}



	public function edit_loading($loading_id){

		

		$loading_slip = \DB::table('loading_slips')->join('dealers','dealers.unique_id','loading_slips.dealer_id')->join('retailers','retailers.unique_code','loading_slips.retailer_id')->join('product_companies','product_companies.id','loading_slips.product_company_id')->join('products','products.id','loading_slips.product_id')->join('units','units.id','loading_slips.unit_id')->join('transport_modes','transport_modes.id','loading_slips.transport_mode')->join('transporters','transporters.id','loading_slips.transporter_id')->leftjoin('rake_points','rake_points.id','loading_slips.rake_point')->leftjoin('warehouses','warehouses.id','loading_slips.from_warehouse_id')->where('loading_slips.id',$loading_id)->select('loading_slips.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name','transporters.name as transporter_name','transport_modes.name as transport_mode_name')->first();

           





		if($loading_slip != null){

			$data['loading_slip'] = $loading_slip;

			$data['transport_modes'] = \DB::table('transport_modes')->get();

			$data['transporters'] = \DB::table('transporters')->where('is_active',1)->get();



			return view('dashboard.order.edit-loading',$data);

		}else{

			return json_encode(['success'=>false,'msg'=>'Loading slip not found']);

		}



		

	}



	public function add_loading_slip(Request $request){

    //   dd($request->all());

		$validator = \Validator::make($request->all(),

			array(

				

				'quantity'			=>'required',

				'transport_mode'			=>'required',

				'vehicle_no'			=>'required',

				'transporter_id'			=>'required',

				'driver_no'			=>'required',

			)

		);



		if($validator->fails()){

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			

			$order = \App\Order::where('id',$request->order_id)->first();

              //dd($order);

		         



					

				



			if($order != null){

				if($order->remaining_qty >= $request->quantity){

					$loading_slip = new \App\LoadingSlip();

					$loading_slip->order_id = $order->id;

					$loading_slip->company_id = $order->company_id;

					$loading_slip->dealer_id = $order->dealer_id;

					$loading_slip->retailer_id = $order->retailer_id;

					$loading_slip->order_from = $order->order_from;

					if($order->order_form==1){

						$loading_slip->rake_point = $order->rake_point;

						$loading_slip->from_warehouse_id = null;

					}



					if($order->order_from==2){

						$loading_slip->rake_point = null;

						$loading_slip->from_warehouse_id = $order->from_warehouse_id;

					}

					

					$loading_slip->despatch_location = $order->despatch_location;

					$loading_slip->product_company_id = $order->product_company_id;

					$loading_slip->product_id = $order->product_id;

					$loading_slip->unit_id = $order->unit_id;

					$loading_slip->user_id =Auth::user()->id;

					$loading_slip->transport_mode = $request->transport_mode;

					$loading_slip->vehicle_no = $request->vehicle_no;

					$loading_slip->driver_no = $request->driver_no;

					$loading_slip->transporter_id = $request->transporter_id;

					$loading_slip->slip_status = 'slip_generated';
					if($request->bilti==1)
					{
					    $loading_slip->bilti_status = $request->bilti;
					}
					$loading_slip->quantity = $request->quantity;


					$loading_slip->save();


					if($request->bilti==1)
					{
					   $bilits = new \App\Bilti();
					   $bilits->remark=$request->bilti_remark;
					   if($bilits->save())
					   {
					   	$bilits->loading_slip_id=$loading_slip->id;
					   	$bilits->bilti_no="0".$request->warehouseId."".$bilits->id;
					   	$bilits->save();
					   }
					}


					$order->remaining_qty = $order->remaining_qty - $request->quantity;

					$order->save();

					$orders_status=new \App\Order();

					$orders_status->where('id',$order->id)->update(['loading_status'=>1]);



					if($order->save()) {

						if($order->order_from == 1) {


							$rakes = \App\Inventory::where('rake_point_id', $order->rake_point)->where('product_id', $order->product_id)->where('warehouse_id', 24)->where('product_brand_id', 1)->first();					

							if($rakes->quantity >= $request->quantity) {

								$rakes->quantity = $rakes->quantity - $request->quantity;

								$rakes->save();

							}				

						}

						if($order->order_from == 2) {

							$rakes = \App\Inventory::where('warehouse_id', $order->from_warehouse_id)->where('product_id', $order->product_id)->where('product_brand_id', 1)->first();					
                            if(!is_null($rakes)){
							if($rakes->quantity >= $request->quantity) {

								$rakes->quantity = $rakes->quantity - $request->quantity;

								$rakes->save();

							}
                            }

						}				

					}

					$response['loading_slip'] = \App\LoadingSlip::latest()->first();

					$response['flag'] 			 = true;

					$response['loading_slip_id'] = $loading_slip->id;

					$response['message'] 		 = "loading Slip Generated Successfully!";

				}else{

					$response['flag'] 			 = false;

					$response['loading_slip_id'] = 0;

					$response['errors']['qty'] 		 = $request->quantity." Loading quantity should be smaller or equal to remaining quantity ".$order->remaining_qty;

				}



			}else{

				$response['flag'] 			= false;

				$response['message'] 		= "Invalid Order Id";

			}



			

		}





		return response()->json($response);

	}



	public function update_loading_slip(Request $request){

		$validator = \Validator::make($request->all(),

			array(

				'loading_id'			=>'required',

				'quantity'			=>'required',

				'transport_mode'	=>'required',

				'vehicle_no'		=>'required',

				'transporter_id'	=>'required',

			)

		);



		if($validator->fails()){

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		}else{

			

			$loading_slip = \App\LoadingSlip::where('id',$request->loading_id)->first();



			if($loading_slip != null){

					$loading_slip->transport_mode = $request->transport_mode;

					$loading_slip->vehicle_no = $request->vehicle_no;

					$loading_slip->transporter_id = $request->transporter_id;

					$loading_slip->quantity = $request->quantity;

					$loading_slip->driver_no = $request->driver_no;

					$loading_slip->save();



					$response['flag'] 			 = true;

					$response['loading_slip_id'] = $loading_slip->id;

					$response['message'] 		 = "loading Slip Updated Successfully!";

				

			}else{

				$response['flag'] 			= false;

				$response['message'] 		= "Invalid Loading Slip Id";

			}



			

		}



		return response()->json($response);

	}



	public function printLoadingSlip($id){

		$data = array();

		$acting_company = Session::get('acting_company');

		$loading_slip =  \App\LoadingSlip::where('id',$id)->first();

		if(is_null($loading_slip)){

			return redirect('user/loading-slips')->with('error','Token Not found');

		}else{

			$data['company'] = \App\Company::where('id',$acting_company)->first();

			$data['loading_slip'] = $loading_slip;



			//dd($data);

			return view('dashboard.order.print-loading-slip',$data); 

		} 

	}

	public function show_loading(Request $request){

		$id = $request->id;

		$loading_slips = \DB::table('loading_slips')->join('users','users.id','loading_slips.user_id')->join('dealers','dealers.unique_id','loading_slips.dealer_id')->join('retailers','retailers.unique_code','loading_slips.retailer_id')->join('product_companies','product_companies.id','loading_slips.product_company_id')->join('products','products.id','loading_slips.product_id')->join('units','units.id','loading_slips.unit_id')->join('transport_modes','transport_modes.id','loading_slips.transport_mode')->join('transporters','transporters.id','loading_slips.transporter_id')->join('orders', 'orders.id', 'loading_slips.order_id')->leftjoin('rake_points','rake_points.id','loading_slips.rake_point')->leftjoin('warehouses','warehouses.id','loading_slips.from_warehouse_id')->select('loading_slips.*','users.name as user_name','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name','transporters.name as transporter_name','transport_modes.name as transport_mode_name')->where('loading_slips.order_id', $id)->orderBy('id','desc')->get();

		$data['loading_slips'] = $loading_slips;

		

		return view('dashboard.order.show-loading',$data);

	}

	public function create_invoice_modal(Request $request){

		$id = $request->id;

		$loading_slip = \DB::table('loading_slips')->join('dealers','dealers.unique_id','loading_slips.dealer_id')->join('retailers','retailers.unique_code','loading_slips.retailer_id')->join('product_companies','product_companies.id','loading_slips.product_company_id')->join('products','products.id','loading_slips.product_id')->join('units','units.id','loading_slips.unit_id')->join('transport_modes','transport_modes.id','loading_slips.transport_mode')->join('transporters','transporters.id','loading_slips.transporter_id')->leftjoin('rake_points','rake_points.id','loading_slips.rake_point')->leftjoin('warehouses','warehouses.id','loading_slips.from_warehouse_id')->where('loading_slips.id',$id)->where('loading_slips.slip_status','slip_generated')->select('loading_slips.*','dealers.name as dealer_name','retailers.name as retailer_name','product_companies.name as product_company_name','products.name as product_name','units.unit as unit_name','rake_points.rake_point as rake_point_name','warehouses.name as from_warehouse_name','transporters.name as transporter_name','transport_modes.name as transport_mode_name')->first();

		

			$data['loading_slip'] = $loading_slip;

			return view('dashboard.order.create-invoice-modal',$data);	

	}



}



















