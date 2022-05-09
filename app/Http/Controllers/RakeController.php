<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class RakeController extends Controller
{
	public function rake_product_allotments(Request $request){
		$data = array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();

		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'master_rake_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/rake-summary')
				->withErrors($validator)
				->withInput();
			}else{
				$data['allotments'] = \App\RakeProductAllotment::where('master_rake_id',$request->master_rake_id)->get();
				$data['master_rake_id'] = $request->master_rake_id;
			}
		}else{
			$data['dealers'] = \App\Dealer::where('is_active',1)->get();
			$data['allotments'] = array();
		}
		return view('dashboard.rake.rake-product-allotments',$data);
	} 

	public function getAllotProduct(){
		$data = array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		$data['units'] = \App\Unit::where('is_active',1)->get();
		return view('dashboard.rake.allot-product',$data);
	}

	public function postAllotProduct(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'master_rake_id' =>'required',
				'dealer_id' =>'required',
			)
		);

		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$status = false;
			$master_rake_product =   \App\MasterRakeProduct::where('master_rake_id',$request->master_rake_id)->sum('quantity');
			
			$product_allotment = \App\RakeProductAllotment::where('master_rake_id',$request->master_rake_id)->sum('alloted_quantity');
			if(!is_null($product_allotment)){

				$count = 0;
				$j = 0;
				foreach ($request->product_id as $key => $value) {
					$count = $count + $request->quantity[$j];
					$j++;
				}
			 // echo $product_allotment;
			  //dd($master_rake_product);
				if($master_rake_product + 1000 < ($product_allotment+$count) ){
					$response['flag'] = false;
					$response['errors']['master_rake_id'] = "You cannot add more that alloted quantity. Remaining Alloted quantity is ".($master_rake_product-$product_allotment);
				}else if($master_rake_product+ 1000 < ($count) ){
					$response['flag'] = false;
					$response['errors']['master_rake_id'] = "Alloted product quantity should not be more than ".$master_rake_product;
				}
				// else if($master_rake_product == $product_allotment ){
				// 	$response['flag'] = false;
				// 	$response['errors']['master_rake_id'] = "All Products are alloted";
				// }
				else{

					$i = 0;
					// $quantity_alloted = 0;

					foreach ($request->product_id as $key => $value) {

						// $quantity_alloted = $quantity_alloted + $request->quantity[$i];

						$allotment_details =  \App\RakeProductAllotment::where('master_rake_id',$request->master_rake_id)->where('dealer_id',$request->dealer_id[$i])->where('product_id',$request->product_id[$i])->first();
						if(!is_null($allotment_details)){
							$allotment_details->alloted_quantity = $request->quantity[$i] + $allotment_details->alloted_quantity;
							$allotment_details->remaining_quantity = $request->quantity[$i] + $allotment_details->remaining_quantity;
						}else{
							$allotment_details =  new \App\RakeProductAllotment();
							$allotment_details->master_rake_id = $request->master_rake_id;
							$allotment_details->dealer_id = $request->dealer_id[$i];
							$allotment_details->product_id = $request->product_id[$i];
							$allotment_details->unit_id = $request->unit_id[$i];
							$allotment_details->alloted_quantity = $request->quantity[$i];
							$allotment_details->remaining_quantity = $request->quantity[$i];
						}
						if($allotment_details->save()){
							$status = true;
						}else{
							$status = false;
						}
						$i++;
					}
					if($status){
						$response['flag'] = true;
						$response['allotment'] = $allotment_details;
						$response['message'] = "Allotment Done Successfully";
					}else{
						$response['flag'] = false;
						$response['error'] = "Something Went Wrong";
					}
				}
			}else{
				$count = 0;
				$j = 0;
				foreach ($request->product_id as $key => $value) {
					$count = $count + $request->quantity[$j];
					$j++;
				}

				if($master_rake_product < $count ){
					$response['flag'] = false;
					$response['errors']['master_rake_id'] = "Alloted product quantity should not be more than ".$master_rake_product;
				}else{
					foreach ($request->product_id as $key => $value) {
						$allotment_details =  \App\RakeProductAllotment::where('master_rake_id',$request->master_rake_id)->where('dealer_id',$request->dealer_id[$i])->where('product_id',$request->product_id[$i])->first();
						if(!is_null($allotment_details)){
							$allotment_details->alloted_quantity = $request->quantity[$i] + $allotment_details->alloted_quantity;
							$allotment_details->remaining_quantity = $request->quantity[$i] + $allotment_details->remaining_quantity;
						}else{
							$allotment_details =  new \App\RakeProductAllotment();
							$allotment_details->master_rake_id = $request->master_rake_id;
							$allotment_details->dealer_id = $request->dealer_id[$i];
							$allotment_details->product_id = $request->product_id[$i];
							$allotment_details->unit_id = $request->unit_id[$i];
							$allotment_details->alloted_quantity = $request->quantity[$i];
							$allotment_details->remaining_quantity = $request->quantity[$i];
						}
						if($allotment_details->save()){
							$status = true;
						}else{
							$status = true;
						}
						$i++;
					}
				}

			}
			


			
			
		}
		return response()->json($response);
	}

	public function getEditAllotment($allotment_id){
		$data = array();
		$allotment = \App\RakeProductAllotment::where('id',$allotment_id)->first();
		if(!is_null($allotment)){
			$data['allotment'] = $allotment;
			$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
			$data['dealers'] = \App\Dealer::where('is_active',1)->get();
			$data['products'] = \App\Product::where('is_active',1)->get();
			$data['units'] = \App\Unit::where('is_active',1)->get();
		}else{
			return redirect()->back()->with('error','Allotment Not found');
		}
		return view('dashboard.rake.edit-allot-product',$data);
	}

	public function postEditAllotment(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'quantity' =>'required',
			)
		);

		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$allotment = \App\RakeProductAllotment::where('id',$request->id)->first();
			
			if($request->quantity > $allotment->alloted_quantity){
				$allotment->remaining_quantity = $request->quantity - $allotment->alloted_quantity;
			}
			$allotment->alloted_quantity = $request->quantity;

			if($allotment->save()){
				$status = true;
			}else{
				$status = true;
			}
			if($status){
				$response['flag'] = true;
				$response['allotment'] = $allotment;
				$response['message'] = "Allotment Updated Successfully";
			}else{
				$response['flag'] = false;
				$response['error'] = "Something Went Wrong";
			}
		}
		return response()->json($response);
	}

	public function rake_summary(Request $request){
		$data = array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'master_rake_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/rake-summary')
				->withErrors($validator)
				->withInput();
			}else{
				if(isset($request->product_id)){
					$allotments = \App\RakeProductAllotment::where('master_rake_id',$request->master_rake_id)->where('product_id',$request->product_id)->get();
				}else{
					$allotments = \App\RakeProductAllotment::where('master_rake_id',$request->master_rake_id)->get();
				}
				if(!is_null($allotments)){
					$dates = array();
					if(isset($request->product_id)){
						$product_loadings = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->where('product_id',$request->product_id)->get();
						$data['product_id'] = $request->product_id;
					}else{
						$product_loadings = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->get();
					}
					if(!is_null($product_loadings)){
						foreach ($product_loadings as $product_loading) {
							array_push($dates, date('Y-m-d',strtotime($product_loading->created_at)));
						}
						$data['date_range'] = array_unique($dates);
					}
					$data['master_rake_products'] = \App\MasterRakeProduct::where('master_rake_id',$request->master_rake_id)->get();
					
				}else{
					$data['date_range'] = array();
				}
				if(isset($request->product_id)){
					$warehouse_allotments = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->where('loading_slip_type',2)->distinct()->select('warehouse_id')->get();
				}else{
					$warehouse_allotments = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)->where('loading_slip_type',2)->distinct()->select('warehouse_id')->get();
				}

				
				$data['allotments'] = $allotments;
				$data['warehouse_allotments'] = $warehouse_allotments;
				$data['master_rake_id'] = $request->master_rake_id;
				$data['current_master_rake'] = \App\MasterRake::where('id',$request->master_rake_id)->first();
				// dd($data);
			}
		}else{
			$data['from_date'] = date('m/d/Y');
			$data['to_date'] = date('m/d/Y');
		}

		return view('dashboard.rake.rake-summary',$data);
	} 

	public function export_rake_summary($master_rake_id){
		$rake = \App\MasterRake::where('id',$master_rake_id)->first();
		$rake_summary_array = array();
		$allotments = \App\RakeProductAllotment::where('master_rake_id',$master_rake_id)->get();


		if(count($allotments)>0){


			$tokens = \App\Token::where('master_rake_id',$master_rake_id)->get();
			if(!is_null($tokens)){
				$dates = array();
				foreach ($tokens as $token) {
					$product_loadings = \App\ProductLoading::where('token_id',$token->id)->get();
					if(!is_null($product_loadings)){
						foreach ($product_loadings as $product_loading) {



							array_push($dates, date('Y-m-d',strtotime($product_loading->created_at)));
						}
						$date_range = array_unique($dates);


					}
				}
			}else{
				$date_range = array();
			}

			foreach($date_range as $key=>$date){
				$total_datewise_quantity[$date] = array();
			}

			$total_company_allotment = array();
			$total_loading_allotment = array();
			$pending_allotment 		 = array();
			foreach($allotments as $allotment){
				$loading = array();
				$loading['Product'] = getModelById('Product',$allotment->product_id)->name;
				$loading['Party Name'] = getModelById('Dealer',$allotment->dealer_id)->name.'('.getModelById('Dealer',$allotment->dealer_id)->address1.')';
				$loading['Allotment from Product Company'] = $allotment->alloted_quantity;
				array_push($total_company_allotment, $allotment->alloted_quantity);
				$total = 0;
				foreach($date_range as $key=>$date){
					$dates = $date;
					$product_loadings = \App\ProductLoading::where('dealer_id',$allotment->dealer_id)->where('product_id',$allotment->product_id)->whereRaw('DATE(created_at) = ?', [$date])->sum('quantity');
					$loading[date('d-m-y',strtotime($date))] = $product_loadings;
					$total_amt = $product_loadings;
					$total = $total + $product_loadings;

					array_push($total_datewise_quantity[$dates], $total_amt);
					
				}
				$loading['Total'] = $total;
				$loading['Pending Quantity'] = $allotment->alloted_quantity - $total;

				array_push($rake_summary_array, $loading);
				@array_push($total_loading_allotment, $total);
				@array_push($pending_allotment, $allotment->alloted_quantity-$total);
			}

			$loading_total = array();
			$loading_total['Party Name'] = 'Total (with allotment)';
			$loading_total['Product'] = '';
			$loading_total['Alloted from Product Company'] = array_sum($total_company_allotment);
			$total = 0;
			foreach($date_range as $key=>$date){
				$product_loadings = \App\ProductLoading::where('warehouse_id',$allotment->warehouse_id)->whereRaw('DATE(created_at) = ?', [$date])->sum('quantity');
				$loading_total[date('d-m-y',strtotime($date))] = array_sum($total_datewise_quantity[$date]);
				$total = $total + $product_loadings;

			}
			$loading_total['Total'] = array_sum($total_loading_allotment);
			$loading_total['Pending Quantity'] = array_sum($pending_allotment);

			array_push($rake_summary_array, $loading_total);

			/* Without allotment*/	
			$warehouse_allotments = \App\ProductLoading::where('master_rake_id',$master_rake_id)->where('warehouse_id','!=',NULL)->groupBy('warehouse_id')->get();

			foreach($warehouse_allotments as $warehouse_allotment){
				$loading = array();
				$loading['Product'] = "";
				$loading['Party Name'] = getModelById('Warehouse',$warehouse_allotment->warehouse_id)->name.'('.getModelById('Warehouse',$warehouse_allotment->warehouse_id)->location.')';
				$loading['Alloted from Product Company'] = $warehouse_allotment->alloted_quantity;
				$total = 0;
				foreach($date_range as $key=>$date){
					$dates = $date;
					$date = date('Y-m-d',strtotime($date));
					$product_loadings = \App\ProductLoading::where('warehouse_id',$warehouse_allotment->warehouse_id)
					->where('product_id',$warehouse_allotment->product_id)
					->whereRaw('DATE(created_at) = ?', [$date])
					->sum('quantity');
					$total_amt = $product_loadings;
					$total = $total + $product_loadings;
					$loading[date('d-m-y',strtotime($date))] = $product_loadings;
					array_push($total_datewise_quantity[$dates], $total_amt);
				}
				$loading['Total'] = $total;
				$loading['Pending Quantity'] = '';

				array_push($rake_summary_array, $loading);
				array_push($total_loading_allotment, $total);
			}
			
			$loading_total = array();
			$loading_total['Product'] = 'Total (with/without allotment)';
			$loading_total['Party Name'] = '';
			$loading_total['Alloted from Product Company'] = array_sum($total_company_allotment);
			$total = 0;
			foreach($date_range as $key=>$date){
				$product_loadings = \App\ProductLoading::where('warehouse_id',$allotment->warehouse_id)->whereRaw('DATE(created_at) = ?', [$date])->sum('quantity');
				$loading_total[date('d-m-y',strtotime($date))] = array_sum($total_datewise_quantity[$date]);
				$total = $total + $product_loadings;

			}
			$loading_total['Total'] = array_sum($total_loading_allotment);
			$loading_total['Pending Quantity'] = array_sum($pending_allotment);

			array_push($rake_summary_array, $loading_total);

		}

		$loading_total = array();
		$loading_total['Product'] = 'RR Quantity';
		$loading_total['Party Name'] = '';
		$loading_total['Alloted from Product Company'] = '';
		$loading_total['Total'] = '';
		$loading_total['Pending Quantity'] = $rake->quantity_alloted;
		array_push($rake_summary_array, $loading_total);

		$loading_total = array();
		$loading_total['Product'] = 'Excess';
		$loading_total['Party Name'] = '';
		$loading_total['Alloted from Product Company'] = '';
		$loading_total['Total'] = '';
		$loading_total['Pending Quantity'] = $rake->quantity_alloted - array_sum($total_loading_allotment);
		array_push($rake_summary_array, $loading_total);

		//dd($rake_summary_array);

		
		\Excel::create('Rake Summary', function($excel) use($rake_summary_array) {
			$excel->sheet('Rake Summary', function($sheet) use($rake_summary_array) {
				$sheet->fromArray($rake_summary_array);
			});
		})->export('xls');
	}

	function date_sort($a, $b) {
		return strtotime($a) - strtotime($b);
	}

	public function getDatesFromRange($start, $end, $format = 'Y-m-d') {
		$array = array();
		$interval = new \DateInterval('P1D');

		$realEnd = new \DateTime($end);
		$realEnd->add($interval);

		$period = new \DatePeriod(new \DateTime($start), $interval, $realEnd);

		foreach($period as $date) { 
			$array[] = $date->format($format); 
		}

		return $array;
	}

	public function rake_daily_report(Request $request){
		$data = array();

		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'master_rake_id' =>'required',
					'from_date' =>'required',

				)
			);

			if($validator->fails()){
				return redirect('user/rake-daily-report')
				->withErrors($validator)
				->withInput();
			}else{
				$product_loadings = \App\ProductLoading::whereDate('created_at', '=', date('Y-m-d',strtotime($request->from_date)))
				->where('from_warehouse_id',null)
				->where('master_rake_id',$request->master_rake_id)
				->with('loading_slip_invoice')
				->get();

				$data['master_rake_id'] = $request->master_rake_id;
				$data['product_loadings'] = $product_loadings;
				$data['from_date'] = $request->from_date;

			}
		}
		else{
			$data['from_date'] = date('m/d/Y');
		}
		// dd($data);
		return view('dashboard.rake.rake-daily-report',$data);
	} 


	public function export_rake_daily_report($master_rake_id,$from_date){
		$product_loadings = \App\ProductLoading::whereDate('created_at', '=', date('Y-m-d',strtotime($from_date)))
		->where('master_rake_id',$master_rake_id)
		->where('from_warehouse_id',null)
		->with('loading_slip_invoice')
		->get();
		$export_data = array();
		foreach ($product_loadings as $key => $product_loading) {
			$loading_data = array();
			$loading_data['Loading Slip Number'] = $product_loading->id;
			if(!is_null($product_loading->token_id)){
				$loading_data['Token Number'] = getModelById('Token',$product_loading->token_id)->unique_id;
			}else{
				$loading_data['Token Number'] = "";
			}
			$loading_data['Date'] = date('d-m-Y',strtotime($product_loading->created_at));
			
			if(!is_null($product_loading->retailer_id)){
				$party_name = $product_loading->retailer_name."(".getModelById('Dealer',$product_loading->dealer_id)->name.")";
			} elseif($product_loading->loading_slip_type ==1 && is_null($product_loading->retailer_id)){
				$party_name = getModelById('Dealer',$product_loading->dealer_id)->name;
			} else{
				$party_name  = getModelById('Warehouse',$product_loading->warehouse_id)->name;
			}
			
			if($product_loading->loading_slip_type ==1){
				if(!is_null($product_loading->retailer_id)){
					$party_address = getModelById('Retailer',$product_loading->retailer_id)->address;
				}else{
					$party_address = getModelById('Dealer',$product_loading->dealer_id)->address1;
				}
			} else{
				$party_address = getModelById('Warehouse',$product_loading->warehouse_id)->location;	
			}			
			

			$loading_data['Party Name'] = $party_name;
			$loading_data['Party Address'] = $party_address;
			$loading_data['Product'] = $product_loading->product_name;
			$loading_data['Truck Number'] = $product_loading->truck_number;
			$loading_data['Transporter'] = $product_loading->transporter_name;
			$loading_data['Freight Payment Mode'] = is_null(getModelById('Token', $product_loading->token_id))?"":getModelById('Token', $product_loading->token_id)->delivery_payment_mode;
			$loading_data['Quantity'] = $product_loading->quantity;
			$loading_data['Rate'] = is_null(getModelById('Token', $product_loading->token_id))?"":getModelById('Token', $product_loading->token_id)->rate;
			if(!is_null($product_loading->loading_slip_invoice)){
				$loading_data['Bill(Invoice #)'] = $product_loading->loading_slip_invoice->invoice_number;
			}else{
				$loading_data['Bill(Invoice #)'] = "";
			}
			array_push($export_data, $loading_data);

		}
		\Excel::create('Rake Summary', function($excel) use($export_data) {
			$excel->sheet('Rake Summary', function($sheet) use($export_data) {
				$sheet->fromArray($export_data);
			});
		})->export('xls');
	} 




	public function lockMasterRake($id){
		// exit;
		$response = array();
		$master_rake = \App\MasterRake::where('id',$id)->first();
		if(!is_null($master_rake)){
			if($master_rake->is_closed == 0){
				$unloadings = \App\ProductLoading::where('master_rake_id',$master_rake->id)
				->where('quantity','>',0)
				->where('recieved_quantity',0)
				->where('is_approved',0)
				->whereNotNull('warehouse_id')
				->count();
				if($unloadings < 1){
					$rake_products = \App\MasterRakeProduct::where('master_rake_id',$master_rake->id)->get();
					foreach ($rake_products as $rake_product) {
						$buffer_inventories = \App\Inventory::where('warehouse_id',24)
						->where('product_brand_id',$master_rake->product_company_id)
						->where('product_id',$rake_product->product_id)
						->get();
						$direct_loading = \App\ProductLoading::where('master_rake_id',$master_rake->id)
						->where('product_company_id',$master_rake->product_company_id)
						->where('product_id',$rake_product->product_id)
						->where('loading_slip_type',2)
						->first();
						if(!is_null($direct_loading)){
							$warehouse_id = $direct_loading->warehouse_id;
						}else{
							$warehouse_id = 1;
						}
						/*----------------remove shortage from company stock----------------------*/
						if($rake_product->shortage_from_company > 0){
							
							$companyStock = \App\Inventory::where('product_company_id',$master_rake->product_company_id)
							->where('product_id',$rake_product->product_id)
							->where('warehouse_id',$warehouse_id)
							->first();
							if(!is_null($companyStock)){
								$companyStock->quantity = $companyStock->quantity - $rake_product->shortage_from_company;
							}else{
								$companyStock = new \App\Inventory();
								$companyStock->product_company_id = $master_rake->product_company_id;
								$companyStock->product_brand_id = $master_rake->product_company_id;
								$companyStock->warehouse_id = $warehouse_id;
								$companyStock->product_id = $rake_product->product_id;
								$companyStock->unit_id = 1;
								$companyStock->quantity = $rake_product->shortage_from_company;
							}
							$companyStock->save();
						}
						/*----------------remove shortage from company stock----------------------*/


						/*------------Add Excess/Shortage----------------------*/

						$total_product_loadings = \App\ProductLoading::where('product_id',$rake_product->product_id)
						->where('master_rake_id',$master_rake->id)
						->whereNull('from_warehouse_id')
						->sum('quantity');

						$excess_shortage =  $total_product_loadings - ($rake_product->quantity - $rake_product->shortage_from_company);

						if($excess_shortage > 0){
							$dealer_id = 114;
							$type = 1;
						}else{
							$dealer_id = 115;
							$type = 2;
						}

						$excessSortageStock = \App\Inventory::where('product_id',$rake_product->product_id)->where('product_brand_id',$rake_product->product_company_id)->where('warehouse_id',$warehouse_id)->first();
						if(!is_null($excessSortageStock)){
							$excessSortageStock->quantity = $excessSortageStock->quantity + $excess_shortage;
						}else{
							$excessSortageStock = new \App\Inventory();
						}
						$excessSortageStock->dealer_id = $dealer_id;
						$excessSortageStock->product_brand_id = $master_rake->product_company_id;
						$excessSortageStock->warehouse_id = $warehouse_id;
						$excessSortageStock->product_id = $rake_product->product_id;
						$excessSortageStock->unit_id = 1;
						$excessSortageStock->quantity = $excess_shortage;
						$excessSortageStock->save();


						$rakeExcessSortage = new \App\RakeExcessShortage();
						$rakeExcessSortage->type = $type;
						$rakeExcessSortage->master_rake_id = $master_rake->id;
						$rakeExcessSortage->dealer_id = $dealer_id;
						$rakeExcessSortage->product_brand_id = $master_rake->product_company_id;
						$rakeExcessSortage->warehouse_id = $warehouse_id;
						$rakeExcessSortage->product_id = $rake_product->product_id;
						$rakeExcessSortage->unit_id = 1;
						$rakeExcessSortage->quantity = $excess_shortage;
						$rakeExcessSortage->save();


						/*------------Add Excess/Shortage----------------------*/

						/*------------------------Adjust buffer godown to 0--------------------------*/

						foreach ($buffer_inventories as $buffer_inventory) {

							if(!is_null($buffer_inventory->product_company_id)){
								if(!is_null($direct_loading)){
									$warehouse_id = $direct_loading->warehouse_id;
								}else{
									$warehouse_id = 1;
								}

								$inventory = \App\Inventory::where('warehouse_id',$warehouse_id)
								->where('product_company_id',$master_rake->product_company_id)->where('product_brand_id',$master_rake->product_company_id)->where('product_id',$rake_product->product_id)
								->first();
								if(!is_null($inventory)){
									$inventory->quantity  =  $inventory->quantity + $buffer_inventory->quantity;
								}else{
									$inventory = new \App\Inventory();
									$inventory->product_company_id  = $master_rake->product_company_id;
									$inventory->product_brand_id  = $master_rake->product_company_id;
									$inventory->product_id  = $buffer_inventory->product_id;
									$inventory->unit_id = $buffer_inventory->product_unit;
									$inventory->quantity  =  $buffer_inventory->quantity;
								}
								$inventory->save();

								$bufferInventory =  \App\Inventory::where('id',$buffer_inventory->id)->first();
								$bufferInventory->quantity = 0;
								$bufferInventory->save();
							}else{

								if(!is_null($direct_loading)){
									$warehouse_id = $direct_loading->warehouse_id;
								}else{
									$warehouse_id = 1;
								}

								$inventory = \App\Inventory::where('warehouse_id',$warehouse_id)
								->where('dealer_id',$buffer_inventory->dealer_id)->where('product_brand_id',$master_rake->product_company_id)->where('product_id',$rake_product->product_id)
								->first();
								if(!is_null($inventory)){
									$inventory->quantity  =  $inventory->quantity + $buffer_inventory->quantity;
								}else{
									$inventory = new \App\Inventory();
									$inventory->dealer_id  = $buffer_inventory->dealer_id;
									$inventory->product_brand_id  = $master_rake->product_company_id;
									$inventory->warehouse_id  = $warehouse_id;
									$inventory->product_id  = $buffer_inventory->product_id;
									$inventory->unit_id = $buffer_inventory->unit_id;
									$inventory->quantity  =  $buffer_inventory->quantity;
								}
								$inventory->save();

								$bufferInventory =  \App\Inventory::where('id',$buffer_inventory->id)->first();
								$bufferInventory->quantity = 0;
								$bufferInventory->save();

							}

						}

						/*------------------------Adjust buffer godown to 0--------------------------*/

					}


					$master_rake->is_closed = 1;
					if($master_rake->save()){
						$response['flag'] = true;
						$response['message'] = "Rake Closed Successfully";
					}else{
						$response['flag'] = false;
						$response['message'] = "Failed To save.";
					}
				}else{
					$response['flag'] = false;
					$response['message'] = "First unload all the arrived stock in godwon";
				}

			}else{
				$response['flag'] = false;
				$response['message'] = "Rake is already Closed";
			}

		}else{
			$response['flag'] = false;
			$response['message'] = "Rake Not Found";

		}
		return response()->json($response);
	}  


}
