<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DatePeriod;
use DateInterval;
use DateTime;
use DB;

class StockController extends Controller
{

	public function productCompanyRegister(Request $request)
	{
		$data = array();

		$data['products'] = \App\Product::where('is_active',1)->get();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'product_company_id' =>'required',
					'product_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/party-register')
				->withErrors($validator)
				->withInput();
			}else{

				$registers = array();

				$period = new DatePeriod(
					new DateTime('2019-09-01'),
					new DateInterval('P1D'),
					new DateTime(date('Y-m-d',strtotime("tomorrow")))
				);

				$opening_stock = \App\OpeningInventory::where('product_company_id',$request->product_company_id)->where('product_id',$request->product_id)->sum('quantity');

				$balance = $opening_stock;
				$total_rake = 0;
				$total_rake_shortage = 0;
				$total_loading = 0;
				$total_unloading = 0;
				$standard_open = 0;
				$standard_closed = 0;
				$total_out_warehouse_di = 0;
				$total_company_di = 0;
				foreach ($period as $key => $value) {
					$date = $value->format('Y-m-d')  ;  

					/*------in------*/
					$master_rakes = \App\MasterRake::where('product_company_id',$request->product_company_id)->whereDate('created_at','=',$date)->get();
					if(!is_null($master_rakes) && count($master_rakes) > 0){
						foreach ($master_rakes as $master_rake) {
							$master_rake_products = \App\MasterRakeProduct::where('master_rake_id',$master_rake->id)->get();
							if(!is_null($master_rake_products) && count($master_rake_products) > 0){
								foreach ($master_rake_products as $master_rake_product) {
									if($master_rake_product->product_id == $request->product_id){
										$temp = array();
										$temp['source'] = "Rake Arrived";
										$temp['date'] = $date;
										$temp['godown'] = getModelById('Warehouse',24)->name;
										$temp['party'] = getModelById('ProductCompany',$request->product_company_id)->name;
										$temp['product'] = getModelById('Product',$request->product_id)->name;
										$temp['truck_number'] = "";
										$temp['in'] = $master_rake_product->quantity;
										$temp['out'] = 0;
										$balance += $master_rake_product->quantity;
										$temp['balance'] = $balance;
										array_push($registers, $temp);
										$total_rake += $master_rake_product->quantity;

										if($master_rake_product->shortage_from_company > 0){
											$temp = array();
											$temp['source'] = "Rake Shortage";
											$temp['date'] = $date;
											$temp['godown'] = 'R/H';
											$temp['party'] = getModelById('ProductCompany',$request->product_company_id)->name;
											$temp['product'] = getModelById('Product',$request->product_id)->name;
											$temp['truck_number'] = "";
											$temp['in'] = 0;
											$temp['out'] = $master_rake_product->shortage_from_company;
											$balance -= $master_rake_product->shortage_from_company;
											$temp['balance'] = $balance;
											array_push($registers, $temp);
											$total_rake_shortage += $master_rake_product->shortage_from_company;
										}

									}
								}
							}
						}
					}


					/*------in------*/


					/*------out------*/
					// $loading_query = \App\ProductLoading::query();
					// $product_loadings = $loading_query->whereDate('created_at','=',$date)->where('product_company_id',$request->product_company_id)->where('product_id',$request->product_id)->where('loading_slip_type',2)->get();
					// if(!is_null($product_loadings) && count($product_loadings) > 0){
					// 	foreach ($product_loadings as $product_loading) {
					// 		$temp = array();
					// 		$temp['source'] = "Product Loading<br><b>Loading# ".$product_loading->id."</b>";
					// 		$temp['date'] = $date;
					// 		if(is_null($product_loading->master_rake_id)){
					// 			$temp['godown'] = getModelById('Warehouse',$product_loading->from_warehouse_id)->name;
					// 		}else{
					// 			$temp['godown'] = 'R/H';
					// 		}
					// 		$temp['party'] = getModelById('ProductCompany',$request->product_company_id)->name;
					// 		$temp['product'] = $product_loading->product_name;
					// 		$temp['truck_number'] = $product_loading->truck_number;
					// 		$temp['in'] = 0;
					// 		$temp['out'] = $product_loading->quantity;
					// 		$balance -= $product_loading->quantity;
					// 		$temp['balance'] = $balance;
					// 		array_push($registers, $temp);
					// 		$total_loading += $product_loading->quantity;
					// 	}
					// }



					$company_di_query = \App\CompanyDi::query();
					$company_dis = $company_di_query->whereDate('invoice_date','=',$date)->where('product_company_id',$request->product_company_id)->where('product_id',$request->product_id)->get();
					if(!is_null($company_dis) && count($company_dis) > 0){
						foreach ($company_dis as $company_di) {
							$temp = array();
							$temp['date'] = $date;
							$temp['source'] = "Company DI <br><b>".getModelById('Dealer',$company_di->dealer_id)->name.'('.getModelById('Dealer',$company_di->dealer_id)->address1.')'."</b>";
							$temp['godown'] = 'R/H';
							$temp['party'] = getModelById('ProductCompany',$request->product_company_id)->name;
							$temp['truck_number'] = "";
							$temp['product'] = getModelById('Product',$company_di->product_id)->name;
							$temp['in'] = 0;
							$temp['out'] = $company_di->quantity;
							$balance -= $company_di->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$total_company_di += $company_di->quantity;
						}
					}


					$warehouse_di_query = \App\WarehouseDi::query();
					$warehouse_dis = $warehouse_di_query->where('product_company_id',$request->product_company_id)->whereDate('invoice_date','=',$date)->where('product_id',$request->product_id)->get();

					if(!is_null($warehouse_dis) && count($warehouse_dis) > 0){
						foreach ($warehouse_dis as $warehouse_di) {
							$temp = array();
							$temp['date'] = $date;
							$temp['source'] = "Warehouse DI(Out) <br><b>".getModelById('Dealer',$warehouse_di->dealer_id)->name.'('.getModelById('Dealer',$warehouse_di->dealer_id)->address1.')'."</b>";
							$temp['godown'] = getModelById('Warehouse',$warehouse_di->warehouse_id)->name;
							$temp['party'] = getModelById('ProductCompany',$request->product_company_id)->name;
							$temp['product'] = getModelById('Product',$warehouse_di->product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = 0;
							$temp['out'] = $warehouse_di->quantity;
							$balance -= $warehouse_di->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$total_out_warehouse_di += $warehouse_di->quantity;
						}
					}



					$standardization_query = \App\Standardization::query();
					$standardizations = $standardization_query->where('open_product_brand_id',$request->product_company_id)->whereDate('created_at','=',$date)->where('product_company_id',$request->product_company_id)->where('open_product_id',$request->product_id)->get();
					if(!is_null($standardizations) && count($standardizations) > 0){
						foreach ($standardizations as $standardization) {
							$temp = array();
							$temp['source'] = "Standardization";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$standardization->warehouse_id)->name;
							$temp['party'] = getModelById('ProductCompany',$request->product_company_id)->name;
							$temp['product'] =  getModelById('Product',$standardization->closed_product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = 0;
							$temp['out'] = $standardization->open_quantity;
							$balance -= $standardization->open_quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$standard_open += $standardization->open_quantity;
						}
					}


					$StockAdjustment_query = \App\StockAdjustment::query();
					$stock_adjustments = $StockAdjustment_query->whereDate('created_at','=',$date)->where('company_id',$request->product_company_id)->where('adjust_type',2)->where('product_id',$request->product_id)->get();

					if(!is_null($stock_adjustments) && count($stock_adjustments) > 0){
						foreach ($stock_adjustments as $stock_adjustment) {
							$temp['source'] = "Adjustment (Stock out)";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$stock_adjustment->warehouse_id)->name;
							$temp['party'] = getModelById('ProductCompany',$stock_adjustment->company_id)->name;
							$temp['product'] =  getModelById('Product',$stock_adjustment->product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = 0;
							$temp['out'] = $stock_adjustment->quantity;
							$balance -= $stock_adjustment->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);
						}
					}

					/*------out------*/

					/*------in------*/
					$direct_unloading_query = \App\ProductUnloading::query();
					$product_unloadings = $direct_unloading_query->where('product_company_id',$request->product_company_id)->whereDate('created_at','=',$date)->where('product_id',$request->product_id)->where('loading_slip_type',0)->get();
					if(!is_null($product_unloadings) && count($product_unloadings) > 0){
						foreach ($product_unloadings as $product_unloading) {
							if($product_unloading->loading_slip_type == 0){
								$source = "<b>Direct</b>";
							}else{
								$source = "<b>From R/H</b>";
							}
							
							$temp['source'] = "Product Unloading <br>".$source;
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$product_unloading->warehouse_id)->name;
							$temp['party'] = getModelById('ProductCompany',$product_unloading->product_company_id)->name;
							$temp['product'] = $product_unloading->product_name;
							$temp['truck_number'] = $product_unloading->truck_number;
							$temp['in'] = $product_unloading->quantity;
							$temp['out'] = 0;
							$balance += $product_unloading->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);
							$total_unloading += $product_unloading->quantity;
						}
					}

					$standardization_query = \App\Standardization::query();
					$standardizations = $standardization_query->where('closed_product_brand_id',$request->product_company_id)->whereDate('created_at','=',$date)->where('product_company_id',$request->product_company_id)->where('closed_product_id',$request->product_id)->get();

					if(!is_null($standardizations) && count($standardizations) > 0){
						foreach ($standardizations as $standardization) {
							$temp = array();
							$temp['source'] = "Standardization";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$standardization->warehouse_id)->name;
							$temp['party'] = getModelById('ProductCompany',$product_unloading->product_company_id)->name;
							$temp['product'] =  getModelById('Product',$standardization->closed_product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = $standardization->packed_quantity;
							$temp['out'] = 0;
							$balance += $standardization->packed_quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$standard_closed += $standardization->packed_quantity;
						}
					}


					$StockAdjustment_query = \App\StockAdjustment::query();
					$stock_adjustments = $StockAdjustment_query->whereDate('created_at','=',$date)->where('company_id',$request->product_company_id)->where('adjust_type',1)->where('product_id',$request->product_id)->get();

					if(!is_null($stock_adjustments) && count($stock_adjustments) > 0){
						foreach ($stock_adjustments as $stock_adjustment) {
							$temp['source'] = "Adjustment (Stock in)";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$stock_adjustment->warehouse_id)->name;
							$temp['party'] = getModelById('ProductCompany',$stock_adjustment->company_id)->name;
							$temp['product'] =  getModelById('Product',$stock_adjustment->product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = $stock_adjustment->quantity;
							$temp['out'] = 0;
							$balance += $stock_adjustment->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);
						}
					}


					/*------in------*/

				}
				// echo "<pre>";
				// print_r($registers);
				// exit;



				$data['total_rake'] 					= $total_rake;
				$data['total_rake_shortage'] 			= $total_rake_shortage;
				$data['total_loading'] 					= $total_loading;
				$data['total_unloading'] 				= $total_unloading;
				$data['standard_open'] 					= $standard_open;
				$data['standard_closed'] 				= $standard_closed;
				$data['total_company_di'] 				= $total_company_di;
				$data['total_out_warehouse_di'] 		= $total_out_warehouse_di ;

				$data['product_company_id'] 			= $request->product_company_id;
				$data['product_id'] 					= $request->product_id;
				$data['opening_stock'] 					= $opening_stock;
				$data['registers'] 						= $registers;
			}
		}
		else{
			$data['total_rake'] 								= 0;
			$data['total_rake_shortage'] 						= 0;
			$data['total_loading'] 								= 0;
			$data['total_unloading'] 							= 0;
			$data['standard_open'] 								= 0;
			$data['standard_closed'] 							= 0;
			$data['total_out_warehouse_di'] 					= 0;
			$data['total_company_di'] 							= 0;
		}

		return view('dashboard.inventory.product-company-register',$data);
	}

	public function partyRegister(Request $request)
	{
		$data = array();

		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'dealer_id' =>'required',
					'product_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/party-register')
				->withErrors($validator)
				->withInput();
			}else{

				$registers = array();

				$period = new DatePeriod(
					new DateTime('2019-09-01'),
					new DateInterval('P1D'),
					new DateTime(date('Y-m-d',strtotime("tomorrow")))
				);
				if($request->product_brand_id){
					$opening_stock = \App\OpeningInventory::where('dealer_id',$request->dealer_id)->where('product_brand_id',$request->product_brand_id)->where('product_id',$request->product_id)->sum('quantity');

				}else{
					$opening_stock = \App\OpeningInventory::where('dealer_id',$request->dealer_id)->where('product_id',$request->product_id)->sum('quantity');

				}

				$balance = $opening_stock;
				$total_loading = 0;
				$standard_open = 0;
				$standard_closed = 0;
				$total_dealer_returned = 0;
				$total_retailer_returned = 0;
				$total_company_di = 0;
				$total_warehouse_di = 0;
				$total_out_warehouse_di = 0;
				$adjust_stock_in = 0;
				$adjust_stock_out = 0;
				foreach ($period as $key => $value) {
					$temp = array();
					$date = $value->format('Y-m-d')  ;  
					/*------out------*/
					$loading_query = \App\ProductLoading::query();
					if($request->product_brand_id){
						$loading_query->where('product_company_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$product_loadings = $loading_query->whereDate('created_at','=',$date)->where('dealer_id',$request->dealer_id)->where('product_id',$request->product_id)->get();
					if(!is_null($product_loadings) && count($product_loadings) > 0){
						foreach ($product_loadings as $product_loading) {
							$temp['source'] = "Product Loading";
							$temp['date'] = $date;
							if(is_null($product_loading->master_rake_id)){
								$temp['godown'] = getModelById('Warehouse',$product_loading->from_warehouse_id)->name;
							}else{
								$temp['godown'] = 'R/H';
							}
							$temp['party'] = getModelById('Dealer',$product_loading->dealer_id)->name.'('.getModelById('Dealer',$product_loading->dealer_id)->address1.')';
							$temp['product'] = $product_loading->product_name;
							$temp['truck_number'] = $product_loading->truck_number;
							$temp['in'] = 0;
							$temp['out'] = $product_loading->quantity;
							$balance -= $product_loading->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$total_loading += $product_loading->quantity;
						}
					}

					$warehouse_di_query = \App\WarehouseDi::query();
					if($request->product_brand_id){
						$warehouse_di_query->where('product_brand_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$warehouse_dis = $warehouse_di_query->whereDate('invoice_date','=',$date)->where('from_dealer_id',$request->dealer_id)->where('product_id',$request->product_id)->get();

					if(!is_null($warehouse_dis) && count($warehouse_dis) > 0){
						foreach ($warehouse_dis as $warehouse_di) {
							$temp['date'] = $date;
							$temp['source'] = "Warehouse DI";
							$temp['godown'] = getModelById('Warehouse',$warehouse_di->warehouse_id)->name;
							$temp['party'] = getModelById('Dealer',$warehouse_di->dealer_id)->name.'('.getModelById('Dealer',$warehouse_di->dealer_id)->address1.')';
							$temp['product'] = getModelById('Product',$warehouse_di->product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = 0;
							$temp['out'] = $warehouse_di->quantity;
							$balance -= $warehouse_di->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$total_out_warehouse_di += $warehouse_di->quantity;
						}
					}



					$standardization_query = \App\Standardization::query();
					if($request->product_brand_id){
						$standardization_query->where('open_product_brand_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$standardizations = $standardization_query->whereDate('created_at','=',$date)->where('dealer_id',$request->dealer_id)->where('open_product_id',$request->product_id)->get();

					if(!is_null($standardizations) && count($standardizations) > 0){
						foreach ($standardizations as $standardization) {
							$temp['source'] = "Standardization";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$standardization->warehouse_id)->name;
							$temp['party'] = getModelById('Dealer',$standardization->dealer_id)->name.'('.getModelById('Dealer',$standardization->dealer_id)->address1.')';
							$temp['product'] =  getModelById('Product',$standardization->closed_product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = 0;
							$temp['out'] = $standardization->open_quantity;
							$balance -= $standardization->open_quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$standard_open += $standardization->open_quantity;
						}
					}


					$StockAdjustment_query = \App\StockAdjustment::query();
					if($request->product_brand_id){
						$StockAdjustment_query->where('product_brand_id',$request->product_brand_id);
						
					}
					$stock_adjustments = $StockAdjustment_query->whereDate('created_at','=',$date)->where('dealer_id',$request->dealer_id)->where('adjust_type',2)->where('product_id',$request->product_id)->get();

					if(!is_null($stock_adjustments) && count($stock_adjustments) > 0){
						foreach ($stock_adjustments as $stock_adjustment) {
							$temp['source'] = "Adjustment (Stock out)";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$stock_adjustment->warehouse_id)->name;
							if(!is_null($stock_adjustment->dealer_id)){
								$temp['party'] = getModelById('Dealer',$stock_adjustment->dealer_id)->name.'('.getModelById('Dealer',$stock_adjustment->dealer_id)->address1.')';
							}else{
								$temp['party'] = getModelById('ProductCompany',$stock_adjustment->company_id)->name;; 
							}
							$temp['product'] =  getModelById('Product',$stock_adjustment->product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = 0;
							$temp['out'] = $stock_adjustment->quantity;
							$balance -= $stock_adjustment->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$adjust_stock_in += $stock_adjustment->quantity;
						}
					}

					/*------out------*/

					/*------in------*/


					$excess_query = \App\RakeExcessShortage::query();
					if($request->product_brand_id){
						$excess_query->where('product_brand_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$excesses = $excess_query->whereDate('created_at','=',$date)->where('dealer_id',$request->dealer_id)->where('product_id',$request->product_id)->get();

					if(!is_null($excesses) && count($excesses) > 0){
						foreach ($excesses as $excess) {
							if($excess->type == 1){
								$temp['source'] = "Rake Excess <br><b>". getModelById('MasterRake',$excess->master_rake_id)->name."</b>";
							}else{
								$temp['source'] = "Rake Shortage <br><b>". getModelById('MasterRake',$excess->master_rake_id)->name."</b>";
							}
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$excess->warehouse_id)->name;
							$temp['party'] = getModelById('Dealer',$excess->dealer_id)->name.'('.getModelById('Dealer',$excess->dealer_id)->address1.')';
							$temp['product'] = getModelById('Product',$excess->product_id)->name;
							$temp['truck_number'] = 'N/A';
							$temp['in'] = $excess->quantity;
							$temp['out'] = 0;
							$balance += $excess->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);
						}
					}






					$unloading_query = \App\ProductUnloading::query();
					if($request->product_brand_id){
						$unloading_query->where('product_company_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$product_unloadings = $unloading_query->whereDate('created_at','=',$date)->where('dealer_id',$request->dealer_id)->where('product_id',$request->product_id)->get();

					if(!is_null($product_unloadings) && count($product_unloadings) > 0){
						foreach ($product_unloadings as $product_unloading) {
							$temp['source'] = "Dealer Returned";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$product_unloading->warehouse_id)->name;
							$temp['party'] = getModelById('Dealer',$product_unloading->dealer_id)->name.'('.getModelById('Dealer',$product_unloading->dealer_id)->address1.')';
							$temp['product'] = $product_unloading->product_name;
							$temp['truck_number'] = $product_unloading->truck_number;
							$temp['in'] = $product_unloading->quantity;
							$temp['out'] = 0;
							$balance += $product_unloading->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$total_dealer_returned += $product_unloading->quantity;
						}
					}


					$standardization_query = \App\Standardization::query();
					if($request->product_brand_id){
						$standardization_query->where('closed_product_brand_id',$request->product_brand_id);
						
					}
					$standardizations = $standardization_query->whereDate('created_at','=',$date)->where('dealer_id',$request->dealer_id)->where('closed_product_id',$request->product_id)->get();

					if(!is_null($standardizations) && count($standardizations) > 0){
						foreach ($standardizations as $standardization) {
							$temp['source'] = "Standardization";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$standardization->warehouse_id)->name;
							$temp['party'] = getModelById('Dealer',$standardization->dealer_id)->name.'('.getModelById('Dealer',$standardization->dealer_id)->address1.')';
							$temp['product'] =  getModelById('Product',$standardization->closed_product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = $standardization->packed_quantity;
							$temp['out'] = 0;
							$balance += $standardization->packed_quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$standard_closed += $standardization->packed_quantity;
						}
					}



					$return_query = \App\ReturnedProduct::query();
					if($request->product_brand_id){
						$return_query->where('product_brand_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$returns = $return_query->whereDate('created_at','=',$date)->where('dealer_id',$request->dealer_id)->where('product_id',$request->product_id)->get();

					if(!is_null($returns) && count($returns) > 0){
						foreach ($returns as $return) {
							
							$temp['source'] = "Retailer Returned";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$return->warehouse_id)->name;
							$temp['party'] = getModelById('Dealer',$return->dealer_id)->name.'('.getModelById('Dealer',$return->dealer_id)->address1.')';
							$temp['product'] =  getModelById('Product',$request->product_id)->name;
							$temp['truck_number'] = $return->vehicle_number;
							$temp['in'] = $return->returned_quantity;
							$temp['out'] = 0;
							$balance += $return->returned_quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$total_retailer_returned += $return->returned_quantity;
						}
					}


					$company_di_query = \App\CompanyDi::query();
					if($request->product_brand_id){
						$company_di_query->where('product_company_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$company_dis = $company_di_query->whereDate('invoice_date','=',$date)->where('dealer_id',$request->dealer_id)->where('product_id',$request->product_id)->get();

					if(!is_null($company_dis) && count($company_dis) > 0){
						foreach ($company_dis as $company_di) {
							$temp['date'] = $date;
							$product_company = "<br><b>".getModelById('ProductCompany',$company_di->product_company_id)->name."</b>";
							$temp['source'] = "Company DI".$product_company;
							$temp['godown'] = 'R/H';
							$temp['party'] = getModelById('Dealer',$company_di->dealer_id)->name.'('.getModelById('Dealer',$company_di->dealer_id)->address1.')';
							$temp['truck_number'] = "";
							$temp['product'] = getModelById('Product',$company_di->product_id)->name;
							$temp['in'] = $company_di->quantity;
							$temp['out'] = 0;
							$balance += $company_di->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$total_company_di += $company_di->quantity;
						}
					}



					$warehouse_di_query = \App\WarehouseDi::query();
					if($request->product_brand_id){
						$warehouse_di_query->where('product_brand_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$warehouse_dis = $warehouse_di_query->whereDate('invoice_date','=',$date)->where('dealer_id',$request->dealer_id)->where('product_id',$request->product_id)->get();

					if(!is_null($warehouse_dis) && count($warehouse_dis) > 0){
						foreach ($warehouse_dis as $warehouse_di) {
							$temp['date'] = $date;
							$temp['source'] = "Warehouse DI";
							$temp['godown'] = getModelById('Warehouse',$warehouse_di->warehouse_id)->name;
							$temp['party'] = getModelById('Dealer',$warehouse_di->dealer_id)->name.'('.getModelById('Dealer',$warehouse_di->dealer_id)->address1.')';
							$temp['product'] = getModelById('Product',$warehouse_di->product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = $warehouse_di->quantity;
							$temp['out'] = 0;
							$balance += $warehouse_di->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$total_warehouse_di += $warehouse_di->quantity;
						}
					}


					$StockAdjustment_query = \App\StockAdjustment::query();
					if($request->product_brand_id){
						$StockAdjustment_query->where('product_brand_id',$request->product_brand_id);
						
					}
					$stock_adjustments = $StockAdjustment_query->whereDate('created_at','=',$date)->where('dealer_id',$request->dealer_id)->where('adjust_type',1)->where('product_id',$request->product_id)->get();

					if(!is_null($stock_adjustments) && count($stock_adjustments) > 0){
						foreach ($stock_adjustments as $stock_adjustment) {
							$temp['source'] = "Adjustment (Stock in)";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$stock_adjustment->warehouse_id)->name;
							if(!is_null($stock_adjustment->dealer_id)){
								$temp['party'] = getModelById('Dealer',$stock_adjustment->dealer_id)->name.'('.getModelById('Dealer',$stock_adjustment->dealer_id)->address1.')';
							}else{
								$temp['party'] = getModelById('ProductCompany',$stock_adjustment->company_id)->name;; 
							}
							$temp['product'] =  getModelById('Product',$stock_adjustment->product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = $stock_adjustment->quantity;
							$temp['out'] = 0;
							$balance += $stock_adjustment->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$adjust_stock_in += $stock_adjustment->quantity;
						}
					}


					/*------in------*/

				}
				// echo "<pre>";
				// print_r($registers);
				// exit;

				$data['total_loading'] = $total_loading;
				$data['standard_open'] = $standard_open;
				$data['standard_closed'] = $standard_closed;
				$data['total_dealer_returned'] = $total_dealer_returned;
				$data['total_retailer_returned'] = $total_retailer_returned;
				$data['total_company_di'] = $total_company_di;
				$data['total_warehouse_di'] = $total_warehouse_di;
				$data['total_out_warehouse_di'] = $total_out_warehouse_di ;
				$data['dealer_id'] = $request->dealer_id;
				$data['product_id'] = $request->product_id;
				$data['opening_stock'] = $opening_stock;
				$data['registers'] = $registers;
			}
		}
		else{
			$data['total_loading'] = 0;
			$data['standard_open'] = 0;
			$data['standard_closed'] = 0;
			$data['total_dealer_returned'] = 0;
			$data['total_retailer_returned'] = 0;
			$data['total_company_di'] = 0;
			$data['total_warehouse_di'] = 0;
			$data['total_out_warehouse_di'] = 0;
		}

		return view('dashboard.inventory.party-register',$data);
	}


	public function godownRegister(Request $request)
	{
		$data = array();		
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'product_brand_id' =>'required',
					'product_id' =>'required',
					'warehouse_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/godown-register')
				->withErrors($validator)
				->withInput();
			}else{

				$registers = array();


				$opening_stock = \App\OpeningInventory::where('warehouse_id',$request->warehouse_id)->where('product_brand_id',$request->product_brand_id)->where('product_id',$request->product_id)->sum('quantity');

				$period = new DatePeriod(
					new DateTime('2019-09-01'),
					new DateInterval('P1D'),
					new DateTime(date('Y-m-d',strtotime("tomorrow")))
				);
				$balance = $opening_stock;
				$total_loading = 0;
				$total_unloading = 0;
				$total_in_warehouse_di = 0;
				$total_out_warehouse_di = 0;
				$standard_closed = 0;
				$standard_open = 0;
				$total_dealer_returned = 0;
				$total_retailer_returned = 0;
				$total_wt_loading = 0;
				$total_wt_unloading = 0;
				$adjust_stock_out = 0;
				$adjust_stock_in = 0;

				foreach ($period as $key => $value) {


					$temp = array();
					$date = $value->format('Y-m-d')  ;  
					/*------in------*/
					$unloading_query = \App\ProductUnloading::query();
					
					$product_unloadings = $unloading_query->where('product_company_id',$request->product_brand_id)->whereDate('created_at','=',$date)->where('product_id',$request->product_id)->where('loading_slip_type','!=',1)->where('warehouse_id',$request->warehouse_id)->get();
					if(!is_null($product_unloadings) && count($product_unloadings) > 0){
						foreach ($product_unloadings as $product_unloading) {
							if($product_unloading->loading_slip_type == 0){
								$source = "(Direct)";
							}else if($product_unloading->loading_slip_type == 1){
								$source = "(Token)";
							} else if($product_unloading->loading_slip_type == 2){
								$source = "(Without Token from R/H)";
							} 
							$temp['source'] = "Product Unloading ".$source;
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$product_unloading->warehouse_id)->name;
							if(!is_null($product_unloading->dealer_id)){
								$temp['party'] = getModelById('Dealer',$product_unloading->dealer_id)->name.'('.getModelById('Dealer',$product_unloading->dealer_id)->address1.')';
							}else{
								$temp['party'] = getModelById('ProductCompany',$product_unloading->product_company_id)->name;
							}
							$temp['product'] = $product_unloading->product_name;
							$temp['truck_number'] = $product_unloading->truck_number;
							$temp['in'] = $product_unloading->quantity;
							$temp['out'] = 0;
							$balance += $product_unloading->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);
							$total_unloading += $product_unloading->quantity;
						}
					}


					/*-------------------unloaded without unloading system-----------------------*/

					$loading_query = \App\ProductLoading::query();
					$product_loadings = $loading_query->where('product_company_id',$request->product_brand_id)->whereDate('created_at','=',$date)->where('product_id',$request->product_id)->where('loading_slip_type',2)->where('is_approved',1)->where('recieved_quantity',0)->where('warehouse_id',$request->warehouse_id)->get();
					if(!is_null($product_loadings) && count($product_loadings) > 0){
						foreach ($product_loadings as $product_loading) {
							$unloading = \App\ProductUnloading::where('product_loading_id',$product_loading->id)->first();
							if(is_null($unloading)){
								$temp['source'] = "Product Unloading (Without Token from R/H)";
								$temp['date'] = $date;
								$temp['godown'] = getModelById('Warehouse',$product_loading->warehouse_id)->name;
								if(!is_null($product_loading->dealer_id)){
									$temp['party'] = getModelById('Dealer',$product_loading->dealer_id)->name.'('.getModelById('Dealer',$product_loading->dealer_id)->address1.')';
								}else{
									$temp['party'] = getModelById('ProductCompany',$product_loading->product_company_id)->name;
								}
								$temp['product'] = $product_loading->product_name;
								$temp['truck_number'] = $product_loading->truck_number;
								$temp['in'] = $product_loading->quantity;
								$temp['out'] = 0;
								$balance += $product_loading->quantity;
								$temp['balance'] = $balance;
								array_push($registers, $temp);
								$total_unloading += $product_loading->quantity;
							}
						}
					}

					/*-------------------unloaded without unloading system-----------------------*/

					$StockAdjustment_query = \App\StockAdjustment::query();
					if($request->product_brand_id){
						$StockAdjustment_query->where('product_brand_id',$request->product_brand_id);
					}
					$stock_adjustments = $StockAdjustment_query->whereDate('created_at','=',$date)->where('warehouse_id',$request->warehouse_id)->where('adjust_type',1)->where('product_id',$request->product_id)->get();

					if(!is_null($stock_adjustments) && count($stock_adjustments) > 0){
						foreach ($stock_adjustments as $stock_adjustment) {
							$temp['source'] = "Adjustment (Stock IN)";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$stock_adjustment->warehouse_id)->name;
							if(!is_null($stock_adjustment->dealer_id)){
								$temp['party'] = getModelById('Dealer',$stock_adjustment->dealer_id)->name.'('.getModelById('Dealer',$stock_adjustment->dealer_id)->address1.')';
							}else{
								$temp['party'] = getModelById('ProductCompany',$stock_adjustment->company_id)->name;
							}
							$temp['product'] =  getModelById('Product',$stock_adjustment->product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = $stock_adjustment->quantity;
							$temp['out'] = 0;
							$balance += $stock_adjustment->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							// $adjust_stock_in += $stock_adjustment->quantity;
						}
					}


					// $warehouse_di_query = \App\WarehouseDi::query();
					// $warehouse_dis = $warehouse_di_query->where('product_brand_id',$request->product_brand_id)->whereDate('invoice_date','=',$date)->where('warehouse_id',$request->warehouse_id)->where('product_id',$request->product_id)->get();

					// if(!is_null($warehouse_dis) && count($warehouse_dis) > 0){
					// 	foreach ($warehouse_dis as $warehouse_di) {
					// 		$temp['date'] = $date;
					// 		if($warehouse_di->transfer_type == 1){
					// 			$transfer_type = "Company To Dealer";
					// 		}else{
					// 			$transfer_type = "Dealer To Dealer";
					// 		}
					// 		$temp['source'] = $transfer_type." Warehouse DI (IN)";
					// 		$temp['godown'] = getModelById('Warehouse',$warehouse_di->warehouse_id)->name;
					// 		$temp['party'] = getModelById('Dealer',$warehouse_di->dealer_id)->name.'('.getModelById('Dealer',$warehouse_di->dealer_id)->address1.')';
					// 		$temp['product'] = getModelById('Product',$warehouse_di->product_id)->name;
					// 		$temp['truck_number'] = "";
					// 		$temp['out'] = 0;
					// 		$temp['in'] = $warehouse_di->quantity;
					// 		$balance += $warehouse_di->quantity;
					// 		$temp['balance'] = $balance;
					// 		array_push($registers, $temp);
					// 		$total_in_warehouse_di += $warehouse_di->quantity;
					// 	}
					// }



					$standardization_query = \App\Standardization::query();
					$standardizations = $standardization_query->whereDate('created_at','=',$date)->where('warehouse_id',$request->warehouse_id)->where('closed_product_brand_id',$request->product_brand_id)->where('closed_product_id',$request->product_id)->get();

					if(!is_null($standardizations) && count($standardizations) > 0){
						foreach ($standardizations as $standardization) {
							$temp['source'] = "Standardization (Packed)";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$standardization->warehouse_id)->name;
							if(!is_null($standardization->dealer_id)){
								$temp['party'] = getModelById('Dealer',$standardization->dealer_id)->name.'('.getModelById('Dealer',$standardization->dealer_id)->address1.')';
							}else{
								$temp['party'] = getModelById('ProductCompany',$standardization->product_company_id)->name;
							}
							$temp['product'] =  getModelById('Product',$standardization->closed_product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = $standardization->packed_quantity;
							$temp['out'] = 0;
							$balance += $standardization->packed_quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$standard_closed += $standardization->packed_quantity;
						}
					}



					$return_query = \App\ReturnedProduct::query();
					$returns = $return_query->whereDate('created_at','=',$date)->where('product_brand_id',$request->product_brand_id)->where('warehouse_id',$request->warehouse_id)->where('product_id',$request->product_id)->get();

					if(!is_null($returns) && count($returns) > 0){
						foreach ($returns as $return) {
							
							$temp['source'] = "Retailer Returned <br> <b>(".getModelById('Retailer',$return->retailer_id)->name.'('.getModelById('Retailer',$return->retailer_id)->address.')'.")</b>";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$return->warehouse_id)->name;
							$temp['party'] = getModelById('Dealer',$return->dealer_id)->name.'('.getModelById('Dealer',$return->dealer_id)->address1.')';
							$temp['product'] =  getModelById('Product',$request->product_id)->name;
							$temp['truck_number'] = $return->vehicle_number;
							$temp['in'] = $return->returned_quantity;
							$temp['out'] = 0;
							$balance += $return->returned_quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$total_retailer_returned += $return->returned_quantity;
						}
					}


					$dealer_return_query = \App\ProductLoading::query();
					
					$dealer_returns = $dealer_return_query->whereDate('created_at','=',$date)->where('warehouse_id',$request->warehouse_id)->where('product_company_id',$request->product_brand_id)->where('product_id',$request->product_id)->whereNotNull('dealer_id')->where('recieved_quantity','>',0)->get();

					if(!is_null($dealer_returns) && count($dealer_returns) > 0){
						foreach ($dealer_returns as $dealer_return) {
							$unload_record = \App\ProductUnloading::where('product_loading_id',$dealer_return->id)->first();
							$temp['source'] = "Dealer Returned";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$unload_record->warehouse_id)->name;
							$temp['party'] = getModelById('Dealer',$dealer_return->dealer_id)->name.'('.getModelById('Dealer',$dealer_return->dealer_id)->address1.')';
							$temp['product'] = $dealer_return->product_name;
							$temp['truck_number'] = $dealer_return->truck_number;
							$temp['in'] = $dealer_return->recieved_quantity;
							$temp['out'] = 0;
							$balance += $dealer_return->recieved_quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);
							$total_dealer_returned += $dealer_return->recieved_quantity;
						}
					}


					$wt_unloading_query = \App\WarehouseTransferUnloading::query();
					$wt_unloadings = $wt_unloading_query->whereDate('created_at','=',$date)->where('to_warehouse_id',$request->warehouse_id)->where('product_brand_id',$request->product_brand_id)->where('product_id',$request->product_id)->get();

					if(!is_null($wt_unloadings) && count($wt_unloadings) > 0){
						foreach ($wt_unloadings as $wt_unloading) {
							$temp['source'] = "Warehouse Transfer Unloading";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$wt_unloading->to_warehouse_id)->name;
							$temp['party'] ="";
							$temp['product'] = getModelById('Product',$wt_unloading->product_id)->name;
							$temp['truck_number'] = '';
							$temp['in'] = $wt_unloading->quantity;
							$temp['out'] = 0;
							$balance += $wt_unloading->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);
							$total_wt_unloading += $wt_unloading->quantity;
						}
					}



					/*------in------*/

					/*------out------*/

					$loading_query = \App\ProductLoading::query();
					
					$product_loadings = $loading_query->whereDate('created_at','=',$date)->where('product_company_id',$request->product_brand_id)->where('from_warehouse_id',$request->warehouse_id)->where('product_id',$request->product_id)->get();
					if(!is_null($product_loadings) && count($product_loadings) > 0){
						foreach ($product_loadings as $product_loading) {
							$temp['source'] = "Product Loading";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$product_loading->from_warehouse_id)->name;
							if(!is_null($product_loading->retailer_id)){
								$party = getModelById('Retailer',$product_loading->retailer_id)->name.'('.getModelById('Retailer',$product_loading->retailer_id)->address.') <br> <b> '.getModelById('Dealer',$product_loading->dealer_id)->name.'('.getModelById('Dealer',$product_loading->dealer_id)->address1.')'.'</b>';
							}else{
								$party = getModelById('Dealer',$product_loading->dealer_id)->name.'('.getModelById('Dealer',$product_loading->dealer_id)->address1.')';
							}
							$temp['party'] = $party;
							$temp['product'] = $product_loading->product_name;
							$temp['truck_number'] = $product_loading->truck_number;
							$temp['in'] = 0;
							$temp['out'] = $product_loading->quantity;
							$balance -= $product_loading->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							$total_loading += $product_loading->quantity;
						}
					}


					$StockAdjustment_query = \App\StockAdjustment::query();
					if($request->product_brand_id){
						$StockAdjustment_query->where('product_brand_id',$request->product_brand_id);
					}
					$stock_adjustments = $StockAdjustment_query->whereDate('created_at','=',$date)->where('warehouse_id',$request->warehouse_id)->where('adjust_type',2)->where('product_id',$request->product_id)->get();

					if(!is_null($stock_adjustments) && count($stock_adjustments) > 0){
						foreach ($stock_adjustments as $stock_adjustment) {
							$temp['source'] = "Adjustment (Stock out)";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$stock_adjustment->warehouse_id)->name;
							if(!is_null($stock_adjustment->dealer_id)){
								$temp['party'] = getModelById('Dealer',$stock_adjustment->dealer_id)->name.'('.getModelById('Dealer',$stock_adjustment->dealer_id)->address1.')';
							}else{
								$temp['party'] = getModelById('ProductCompany',$stock_adjustment->company_id)->name;; 
							}
							

							$temp['product'] =  getModelById('Product',$stock_adjustment->product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = 0;
							$temp['out'] = $stock_adjustment->quantity;
							$balance -= $stock_adjustment->quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);

							// $adjust_stock_out += $stock_adjustment->quantity;
						}
					}


					// $warehouse_di_query = \App\WarehouseDi::query();
					// $warehouse_dis = $warehouse_di_query->where('product_brand_id',$request->product_brand_id)->whereDate('invoice_date','=',$date)->where('warehouse_id',$request->warehouse_id)->where('product_id',$request->product_id)->get();

					// if(!is_null($warehouse_dis) && count($warehouse_dis) > 0){
					// 	foreach ($warehouse_dis as $warehouse_di) {
					// 		$temp['date'] = $date;
					// 		if($warehouse_di->transfer_type == 1){
					// 			$transfer_type = "Company To Dealer";
					// 		}else{
					// 			$transfer_type = "Dealer To Dealer";
					// 		}
					// 		$temp['source'] = $transfer_type." Warehouse DI (OUT)";
					// 		$temp['godown'] = getModelById('Warehouse',$warehouse_di->warehouse_id)->name;
					// 		if($warehouse_di->transfer_type == 1){
					// 			$party = getModelById('ProductCompany',$warehouse_di->product_company_id)->name;
					// 		}else{
					// 			$party = getModelById('Dealer',$warehouse_di->from_dealer_id)->name.'('.getModelById('Dealer',$warehouse_di->from_dealer_id)->address1.')';
					// 		}

					// 		$temp['party'] = $party;
					// 		$temp['product'] = getModelById('Product',$warehouse_di->product_id)->name;
					// 		$temp['truck_number'] = "";
					// 		$temp['in'] = 0;
					// 		$temp['out'] = $warehouse_di->quantity;
					// 		$balance -= $warehouse_di->quantity;
					// 		$temp['balance'] = $balance;
					// 		array_push($registers, $temp);
					// 		$total_out_warehouse_di += $warehouse_di->quantity;
					// 	}
					// }



					$standardization_query = \App\Standardization::query();
					$standardizations = $standardization_query->whereDate('created_at','=',$date)->where('warehouse_id',$request->warehouse_id)->where('open_product_brand_id',$request->product_brand_id)->where('open_product_id',$request->product_id)->get();

					if(!is_null($standardizations) && count($standardizations) > 0){
						foreach ($standardizations as $standardization) {
							$temp['source'] = "Standardization (Open)";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$standardization->warehouse_id)->name;
							if(!is_null($standardization->dealer_id)){
								$temp['party'] = getModelById('Dealer',$standardization->dealer_id)->name.'('.getModelById('Dealer',$standardization->dealer_id)->address1.')';
							}else{
								$temp['party'] = getModelById('ProductCompany',$standardization->product_company_id)->name;
							}
							$temp['product'] =  getModelById('Product',$standardization->closed_product_id)->name;
							$temp['truck_number'] = "";
							$temp['in'] = 0;
							$temp['out'] = $standardization->open_quantity;
							$balance -= $standardization->open_quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);
							$standard_open += $standardization->open_quantity;
						}
					}

					$wt_loading_query = \App\WarehouseTransferLoading::query();
					$wt_loadings = $wt_loading_query->whereDate('created_at','=',$date)->where('from_warehouse_id',$request->warehouse_id)->where('product_brand_id',$request->product_brand_id)->where('product_id',$request->product_id)->where('received_quantity','>',0)->get();

					if(!is_null($wt_loadings) && count($wt_loadings) > 0){
						foreach ($wt_loadings as $wt_loading) {
							$temp['source'] = "Warehouse Transfer Loading";
							$temp['date'] = $date;
							$temp['godown'] = getModelById('Warehouse',$wt_loading->from_warehouse_id)->name;
							$temp['party'] = "";
							$temp['product'] = getModelById('Product',$wt_loading->product_id)->name;
							$temp['truck_number'] = $wt_loading->truck_number;
							$temp['in'] = $wt_loading->received_quantity;
							$temp['out'] = 0;
							$balance -= $wt_loading->received_quantity;
							$temp['balance'] = $balance;
							array_push($registers, $temp);
							$total_wt_loading += $wt_loading->received_quantity;
						}
					}



					/*------out------*/


				}
				$data['total_loading'] 						= $total_loading;
				$data['total_unloading'] 					= $total_unloading;
				$data['total_in_warehouse_di']				= $total_in_warehouse_di;
				$data['total_out_warehouse_di']				= $total_out_warehouse_di;
				$data['standard_closed'] 					= $standard_closed;
				$data['standard_open'] 						= $standard_open;
				$data['total_dealer_returned'] 				= $total_dealer_returned;
				$data['total_retailer_returned']			= $total_retailer_returned;
				$data['total_wt_loading'] 					= $total_wt_loading;
				$data['total_wt_unloading'] 				= $total_wt_unloading;
				$data['product_brand_id'] 					= $request->product_brand_id;
				$data['warehouse_id'] 						= $request->warehouse_id;
				$data['product_id'] 						= $request->product_id;
				$data['opening_stock'] 						= $opening_stock;
				$data['registers'] 							= $registers;

			}
		}else{


			$data['total_loading'] 							= 0;
			$data['total_unloading'] 						= 0;
			$data['total_in_warehouse_di']					= 0;
			$data['total_out_warehouse_di']					= 0;
			$data['standard_closed'] 						= 0;
			$data['standard_open'] 							= 0;
			$data['total_dealer_returned'] 					= 0;
			$data['total_retailer_returned']				= 0;
			$data['total_wt_loading'] 						= 0;
			$data['total_wt_unloading'] 					= 0;
			$data['opening_stock'] 							= 0;
			$data['registers'] 								= array();
		}
		

		return view('dashboard.inventory.godown-register',$data);
	}



	/*-----------------Company Godown Stock----------------------*/

	public function companyGodownStock(Request $request){
		$data = array();		
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					// 'product_company_id' =>'required',
					// 'warehouse_id' =>'required'
				)
			);

			if($validator->fails()){
				return redirect('user/company-godown-stock')
				->withErrors($validator)
				->withInput();
			}else{
				$stocks = array();
				$total = 0;
				if($request->product_company_id){
					$product_companies = \App\ProductCompany::where('is_active',1)->where('id',$request->product_company_id)->pluck('id');
				}else{
					$product_companies = \App\ProductCompany::where('is_active',1)->pluck('id');
				}

				foreach ($product_companies as $product_company) {
					$request->product_company_id = $product_company;
					if($request->warehouse_id){
						$warehouse_ids = \App\Warehouse::where('id',$request->warehouse_id)->pluck('id');
					}else{
						$warehouse_ids = \App\Warehouse::pluck('id');
					}
					foreach ($warehouse_ids as $warehouse_id) {
						$request->warehouse_id = $warehouse_id;
						$tempArr = array();
						

						if($request->product_id){
							$product_ids = \App\Product::where('id',$request->product_id)->pluck('id');
						}else{
							$product_ids = \App\Product::pluck('id');
						}
						foreach ($product_ids as $product_id) {

							$opening_stock = \App\OpeningInventory::where('warehouse_id',$request->warehouse_id)->where('product_brand_id',$request->product_company_id)->where('product_id',$product_id)->sum('quantity');
							$balance = $opening_stock;
							


							/*------in------*/
							// $unloading_query = \App\ProductUnloading::query();
							$unloading_query = \DB::table('product_unloadings');

							$product_unloadings = $unloading_query->where('product_company_id',$request->product_company_id)->where('product_id',$product_id)->where('loading_slip_type','!=',1)->where('warehouse_id',$request->warehouse_id)->sum('quantity');

							$balance += $product_unloadings;

							/*-------------------unloaded without unloading system-----------------------*/
							// $loading_query = \App\ProductLoading::query();
							$loading_query = \DB::table('product_loadings');
							$product_loadings = $loading_query->where('product_company_id',$request->product_company_id)->where('product_id',$product_id)->where('loading_slip_type',2)->where('is_approved',1)->where('recieved_quantity',0)->where('warehouse_id',$request->warehouse_id)->get();
							if(!is_null($product_loadings) && count($product_loadings) > 0){
								foreach ($product_loadings as $product_loading) {
									$unloading = \App\ProductUnloading::where('product_loading_id',$product_loading->id)->first();
									if(is_null($unloading)){
										$balance += $product_loading->quantity;
									}
								}
							}
							/*-------------------unloaded without unloading system-----------------------*/

							// $warehouse_di_query = \DB::table('warehouse_dis');
							// if($request->product_company_id){
							// 	$warehouse_di_query->where('product_company_id',$request->product_company_id)->where('product_brand_id',$request->product_company_id)->where('warehouse_id',$request->warehouse_id);
							// }
							// $warehouse_dis = $warehouse_di_query->where('product_id',$product_id)->sum('quantity');
							// $balance += $warehouse_dis;

							// $StockAdjustment_query = \App\StockAdjustment::query();
							$StockAdjustment_query = \DB::table('stock_adjustments');
							if($request->product_company_id){
								$StockAdjustment_query->where('product_brand_id',$request->product_company_id);
							}
							$stock_adjustments = $StockAdjustment_query->where('warehouse_id',$request->warehouse_id)->where('adjust_type',1)->where('product_id',$product_id)->sum('quantity');
							$balance += $stock_adjustments;


							// $standardization_query = \App\Standardization::query();
							$standardization_query = \DB::table('standardizations');
							$standardizations = $standardization_query->where('warehouse_id',$request->warehouse_id)->where('closed_product_brand_id',$request->product_company_id)->where('closed_product_id',$product_id)->sum('packed_quantity');
							$balance += $standardizations;


							// $return_query = \App\ReturnedProduct::query();
							$return_query = \DB::table('returned_products');
							$returns = $return_query->where('product_brand_id',$request->product_company_id)->where('warehouse_id',$request->warehouse_id)->where('product_id',$product_id)->sum('returned_quantity');
							$balance += $returns;


							// $dealer_return_query = \App\ProductLoading::query();
							$dealer_return_query = \DB::table('product_loadings');

							$dealer_returns = $dealer_return_query->where('warehouse_id',$request->warehouse_id)->where('product_company_id',$request->product_company_id)->where('product_id',$product_id)->whereNotNull('dealer_id')->where('recieved_quantity','>',0)->sum('recieved_quantity');
							$balance += $dealer_returns;


							// $wt_unloading_query = \App\WarehouseTransferUnloading::query();
							$wt_unloading_query = \DB::table('warehouse_transfer_unloadings');

							$wt_unloadings = $wt_unloading_query->where('to_warehouse_id',$request->warehouse_id)->where('product_brand_id',$request->product_company_id)->where('product_id',$product_id)->sum('quantity');
							$balance += $wt_unloadings;

							/*------in------*/

							/*------out------*/
							// $loading_query = \App\ProductLoading::query();
							$loading_query = \DB::table('product_loadings');
							$product_loadings = $loading_query->where('product_company_id',$request->product_company_id)->where('from_warehouse_id',$request->warehouse_id)->where('product_id',$product_id)->sum('quantity');
							$balance -= $product_loadings;

							// $StockAdjustment_query = \App\StockAdjustment::query();
							$StockAdjustment_query = \DB::table('stock_adjustments');
							if($request->product_company_id){
								$StockAdjustment_query->where('product_brand_id',$request->product_company_id);
							}
							$stock_adjustments = $StockAdjustment_query->where('warehouse_id',$request->warehouse_id)->where('adjust_type',2)->where('product_id',$product_id)->sum('quantity');
							$balance -= $stock_adjustments;

							// $standardization_query = \App\Standardization::query();
							$standardization_query = \DB::table('standardizations');
							$standardizations = $standardization_query->where('warehouse_id',$request->warehouse_id)->where('open_product_brand_id',$request->product_company_id)->where('open_product_id',$product_id)->sum('open_quantity');
							$balance -= $standardizations;

							// $wt_loading_query = \App\WarehouseTransferLoading::query();
							$wt_loading_query = \DB::table('warehouse_transfer_loadings');
							$wt_loadings = $wt_loading_query->where('from_warehouse_id',$request->warehouse_id)->where('product_brand_id',$request->product_company_id)->where('product_id',$product_id)->where('received_quantity','>',0)->sum('received_quantity');
							$balance -= $wt_loadings;

							/*------out------*/
							if($balance != 0){
								$temp = array();
								$temp['party'] = getModelById('ProductCompany',$request->product_company_id)->name;
								$temp['product'] = getModelById('Product',$product_id)->name;
								$temp['balance'] = $balance;
								$temp['warehouse'] = getModelById('Warehouse',$request->warehouse_id)->name;
								array_push($stocks, $temp);

								if($request->product_id){
									$total = $total + $balance;
								}

							}
						}
					}
				}	
				$data['stocks'] = $stocks;
				$data['total'] = $total;
				// exit;

			}
		}else{
			
			$data['stocks'] = array();
		}

		return view('dashboard.inventory.company-godown-stock',$data);
	}

	/*-----------------Company Godown Stock----------------------*/



	/*-----------------Parties Stock----------------------*/



	public function partiesStock(Request $request)
	{
		$data = array();
		$data['products'] = \App\Product::where('is_active',1)->get();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'product_id' =>'required',
					'product_brand_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/parties-stock')
				->withErrors($validator)
				->withInput();
			}else{

				$opening_dealers = \App\OpeningInventory::whereNotNull('dealer_id')->distinct()->pluck('dealer_id')->toArray();
				$loading_dealers = \App\ProductLoading::whereNotNull('dealer_id')->distinct()->pluck('dealer_id')->toArray();
				$WarehouseDi_from_dealers = \App\WarehouseDi::whereNotNull('from_dealer_id')->distinct()->pluck('from_dealer_id')->toArray();
				$WarehouseDi_to_dealers = \App\WarehouseDi::whereNotNull('dealer_id')->distinct()->pluck('dealer_id')->toArray();
				$companyDi_dealers = \App\CompanyDi::whereNotNull('dealer_id')->distinct()->pluck('dealer_id')->toArray();
				$standard_dealers = \App\Standardization::whereNotNull('dealer_id')->distinct()->pluck('dealer_id')->toArray();
				$excess_shortage_dealers = \App\RakeExcessShortage::whereNotNull('dealer_id')->distinct()->pluck('dealer_id')->toArray();
				$distincts_dealers = array();
				$distincts_dealers = array_unique(array_merge($opening_dealers,$distincts_dealers,$loading_dealers,$WarehouseDi_from_dealers,$WarehouseDi_to_dealers,$companyDi_dealers,$standard_dealers,$excess_shortage_dealers));

				$dealers_stock = array();
				$total = 0;
				foreach ($distincts_dealers as $dealer_id) {


					if($request->product_brand_id){
						$opening_stock = \App\OpeningInventory::where('dealer_id',$dealer_id)->where('product_brand_id',$request->product_brand_id)->where('product_id',$request->product_id)->sum('quantity');

					}else{
						$opening_stock = \App\OpeningInventory::where('dealer_id',$dealer_id)->where('product_id',$request->product_id)->sum('quantity');

					}

					$balance = $opening_stock;


					/*------out------*/
					$loading_query = \App\ProductLoading::query();
					if($request->product_brand_id){
						$loading_query->where('product_company_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$product_loadings = $loading_query->where('dealer_id',$dealer_id)->where('product_id',$request->product_id)->sum('quantity');
					$balance -= $product_loadings;


					$warehouse_di_query = \App\WarehouseDi::query();
					if($request->product_brand_id){
						$warehouse_di_query->where('product_brand_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$warehouse_dis = $warehouse_di_query->where('from_dealer_id',$dealer_id)->where('product_id',$request->product_id)->sum('quantity');
					$balance -= $warehouse_dis;



					$standardization_query = \App\Standardization::query();
					if($request->product_brand_id){
						$standardization_query->where('open_product_brand_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$standardizations = $standardization_query->where('dealer_id',$dealer_id)->where('open_product_id',$request->product_id)->sum('open_quantity');
					$balance -= $standardizations;



					$StockAdjustment_query = \App\StockAdjustment::query();
					if($request->product_brand_id){
						$StockAdjustment_query->where('product_brand_id',$request->product_brand_id);

					}
					$stock_adjustments = $StockAdjustment_query->where('dealer_id',$dealer_id)->where('adjust_type',2)->where('product_id',$request->product_id)->sum('quantity');
					$balance -= $stock_adjustments;

					/*------out------*/

					/*------in------*/


					$excess_query = \App\RakeExcessShortage::query();
					if($request->product_brand_id){
						$excess_query->where('product_brand_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$excesses = $excess_query->where('dealer_id',$dealer_id)->where('product_id',$request->product_id)->sum('quantity');
					$balance += $excesses;






					$unloading_query = \App\ProductUnloading::query();
					if($request->product_brand_id){
						$unloading_query->where('product_company_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$product_unloadings = $unloading_query->where('dealer_id',$dealer_id)->where('product_id',$request->product_id)->sum('quantity');
					$balance += $product_unloadings;
					


					$standardization_query = \App\Standardization::query();
					if($request->product_brand_id){
						$standardization_query->where('closed_product_brand_id',$request->product_brand_id);

					}
					$standardizations = $standardization_query->where('dealer_id',$dealer_id)->where('closed_product_id',$request->product_id)->sum('packed_quantity');
					$balance += $standardizations;

					


					$return_query = \App\ReturnedProduct::query();
					if($request->product_brand_id){
						$return_query->where('product_brand_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$returns = $return_query->where('dealer_id',$dealer_id)->where('product_id',$request->product_id)->sum('returned_quantity');
					$balance += $returns;


					$company_di_query = \App\CompanyDi::query();
					if($request->product_brand_id){
						$company_di_query->where('product_company_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$company_dis = $company_di_query->where('dealer_id',$dealer_id)->where('product_id',$request->product_id)->sum('quantity');
					$balance += $company_dis;
					


					$warehouse_di_query = \App\WarehouseDi::query();
					if($request->product_brand_id){
						$warehouse_di_query->where('product_brand_id',$request->product_brand_id);
						$data['product_brand_id'] = $request->product_brand_id;
					}
					$warehouse_dis = $warehouse_di_query->where('dealer_id',$dealer_id)->where('product_id',$request->product_id)->sum('quantity');
					$balance += $warehouse_dis;


					$StockAdjustment_query = \App\StockAdjustment::query();
					if($request->product_brand_id){
						$StockAdjustment_query->where('product_brand_id',$request->product_brand_id);

					}
					$stock_adjustments = $StockAdjustment_query->where('dealer_id',$dealer_id)->where('adjust_type',1)->where('product_id',$request->product_id)->sum('quantity');

					$balance += $stock_adjustments;
					/*------in------*/


					if($balance !=  0){


						$temp = array();
						$temp['party'] = getModelById('Dealer',$dealer_id)->name.'('.getModelById('Dealer',$dealer_id)->address1.')';
						$temp['stock'] = $balance;

						array_push($dealers_stock, $temp);
						$total = $total + $balance;
					}

				}

				$data['dealers_stock'] = $dealers_stock;
				$data['product_id'] = $request->product_id;
				$data['total'] = $total;
			}
		}
		else{
			$data['dealers_stock'] = array();

		}

		return view('dashboard.inventory.parties-stock',$data);
	}


	/*-----------------Parties Stock----------------------*/

	public function warehouse_report(Request $request)
	{
		$data = array();

		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'warehouse_id' =>'required',
					'from_date' =>'required',

				)
			);

			if($validator->fails()){
				return redirect('user/warehouse-report')
				->withErrors($validator)
				->withInput();
			}else{
				$product_loadings = \App\ProductLoading::whereDate('created_at', '=', date('Y-m-d',strtotime($request->from_date)))

				->where(function  ($query) use ($request) {
					$query->where('warehouse_id',$request->warehouse_id)
					->orWhere('from_warehouse_id',$request->warehouse_id);
				})
				->with('token')
				->with('loading_slip_invoice')
				->get();
				$data['warehouse_id'] = $request->warehouse_id;
				$data['product_loadings'] = $product_loadings;
				$data['from_date'] = $request->from_date;
			}
		}
		else{
			$data['from_date'] = date('m/d/Y');
		}

		return view('dashboard.inventory.warehouse-report',$data);
	}

	public function warehouse_daywise_report (Request $request){
		$data['sessions'] = \App\Session::all();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['select_warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['products'] = array();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'warehouse_id' =>'required',
					'from_date' =>'required',

				)
			);
			if($validator->fails()){
				return redirect('user/warehouse-daywise-report')
				->withErrors($validator)
				->withInput();
			}else{
				$products = array();
				$unique_products = \App\ProductLoading::select('product_company_id', 'product_id')
				->where('warehouse_id',$request->warehouse_id)
				->whereDate('created_at', '=', date('Y-m-d',strtotime($request->from_date)))
				->distinct()
				->get();
				if(count($unique_products) > 0){
					foreach ($unique_products as $unique_product) {
						$product = array();
						$product['id'] = $unique_product->product_id;
						$product['name'] = getModelById('Product',$unique_product->product_id)->name;
						$product['product_company_id'] = $unique_product->product_company_id;
						$product['product_company'] = getModelById('ProductCompany',$unique_product->product_company_id)->abbreviation;
						array_push($products, $product);
					}
					$data['products'] = $products;
				}else{
					$data['products'] = $products;
				}
				$data['warehouses'] = \App\Warehouse::where('is_active',1)->where('id',$request->warehouse_id)->get();
				$data['warehouse_id'] = $request->warehouse_id;
				$data['from_date'] = $request->from_date;
			}
		}else{
			$data['from_date'] = date('m/d/Y');
		}

		return view('dashboard.inventory.warehouse-daywise-report',$data);
	}

	public function stock_report (Request $request){
		$data['filter_warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['product_categories'] = \App\ProductCategory::where('is_active',1)->get();

		$products = array();
		$query = \App\Inventory::query();
		if ($request->isMethod('post')){
			if($request->warehouse_id){
				$query->where('warehouse_id',$request->warehouse_id);	
				$data['warehouse_id'] = $request->warehouse_id;
				$data['warehouses'] = \App\Warehouse::where('is_active',1)->where('id',$request->warehouse_id)->get();
			}
		}

		$unique_products = $query->select('product_brand_id', 'product_id')->distinct()->get();
		if(count($unique_products) > 0){
			foreach ($unique_products as $unique_product) {
				$product = array();
				$product['id'] = $unique_product->product_id;
				$product['name'] = getModelById('Product',$unique_product->product_id)->name;
				$product['product_category_id'] = getModelById('Product',$unique_product->product_id)->product_category_id;
				$product['product_brand_id'] = $unique_product->product_brand_id;
				$product['product_company'] = getModelById('ProductCompany',$unique_product->product_brand_id)->abbreviation;
				array_push($products, $product);
			}
			$data['products'] = $products;
		}else{
			$data['products'] = $products;
		}

		return view('dashboard.inventory.stock-report',$data);
	}

	public function buffer_godown_report(Request $request)
	{
		$data['sessions'] = \App\Session::all();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$inventory_products = \App\Inventory::select('product_id','product_brand_id')->where('warehouse_id',24)->distinct()->get();

		if ($request->isMethod('post')) {
			$query = \App\Inventory::query();
			if($request->product_brand_id){
				$query->where('product_brand_id', $request->product_brand_id);
				$data['product_brand_id'] = $request->product_brand_id;
			}
			$inventory_products = 	$query->select('product_id','product_brand_id')->where('warehouse_id',24)->distinct()->get();

		}
		$data['inventory_products'] = $inventory_products;
		$inventories = \App\Inventory::select('dealer_id','product_company_id')->where('warehouse_id',24)->distinct()->get();
		if ($inventories) {
			$inventory_data = array();	
			foreach ($inventories as $inventory) {
				if ($inventory->dealer_id) {
					$pary_data = array();
					$dealer_name = \App\Dealer::where('id',$inventory->dealer_id)->first();
					$pary_data['party_name'] = $dealer_name->name;
					$pary_data['address'] = $dealer_name->address1;
					$pary_data['id'] = $dealer_name->id;
					$pary_data['type'] = 'dealer_id';
					array_push($inventory_data, $pary_data);
				} else if ($inventory->product_company_id) {
					$pary_data = array();
					$product_company_name = \App\ProductCompany::where('id',$inventory->product_company_id)->first();
					$pary_data['party_name'] = $product_company_name->name;
					$pary_data['address'] = $product_company_name->address;
					$pary_data['id'] = $product_company_name->id;
					$pary_data['type'] = 'product_company_id';
					array_push($inventory_data, $pary_data);
				}
			}
		}
		$data['parties'] = $inventory_data;
		return view('dashboard.inventory.buffer-godown-report', $data);
	}
	public function buffer_report(Request $request)
	{
		$data['sessions'] = \App\Session::all();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$inventory_products = \App\Inventory::select('product_id','product_brand_id')->distinct()->get();

		if ($request->isMethod('post')) {
			$query = \App\Inventory::query();
			if($request->product_brand_id){
				$query->where('product_brand_id', $request->product_brand_id);
				$data['product_brand_id'] = $request->product_brand_id;
			}
			if($request->warehouse_id){
				$query->where('warehouse_id', $request->warehouse_id);
				$data['warehouse_id'] = $request->warehouse_id;
			}

			$inventory_products = 	$query->select('product_id','product_brand_id')->distinct()->get();

		}
		$data['inventory_products'] = $inventory_products;
		$inventories = \App\Inventory::select('dealer_id','product_company_id')->distinct()->get();
		if ($inventories) {
			$inventory_data = array();	
			foreach ($inventories as $inventory) {
				if ($inventory->dealer_id) {
					$pary_data = array();
					$dealer_name = \App\Dealer::where('id',$inventory->dealer_id)->first();
					$pary_data['party_name'] = $dealer_name->name;
					$pary_data['address'] = $dealer_name->address1;
					$pary_data['id'] = $dealer_name->id;
					$pary_data['type'] = 'dealer_id';
					array_push($inventory_data, $pary_data);
				} else if ($inventory->product_company_id) {
					$pary_data = array();
					$product_company_name = \App\ProductCompany::where('id',$inventory->product_company_id)->first();
					$pary_data['party_name'] = $product_company_name->name;
					$pary_data['address'] = $product_company_name->address;
					$pary_data['id'] = $product_company_name->id;
					$pary_data['type'] = 'product_company_id';
					array_push($inventory_data, $pary_data);
				}
			}
		}
		$data['parties'] = $inventory_data;
		return view('dashboard.inventory.buffer-report', $data);
	}

	public function daily_stock_report(Request $request)
	{

		\App\Inventory::where('quantity',0)->delete();


		$data = array();
		$data['warehouses'] = \App\Warehouse::where('is_active', 1)->get();
		$data['product_categories'] = \App\ProductCategory::where('is_active', 1)->get();
		$query = \App\Inventory::query();
		$unique_products = $query->select('product_brand_id', 'product_id')->distinct()->get();
		$unique_warehouses = array_unique(\App\Inventory::pluck('warehouse_id')->toArray());
		$unique_product_companies = array_unique(\App\Inventory::pluck('product_company_id')->toArray());

		$products = array();

		if (count($unique_products) > 0) {
			foreach ($unique_products as $unique_product) {
				$inventories = \App\Inventory::select('dealer_id', 'product_company_id')->distinct()->get();
				if ($inventories) {
					$other_parties = array();
					$self_parties = array();
					$product_companies = array();
					foreach ($inventories as $inventory) {
						if ($inventory->dealer_id) {
							$pary_data = array();
							$dealer_name = \App\Dealer::where('id', $inventory->dealer_id)->first();
							$pary_data['party_name'] = $dealer_name->name;
							$pary_data['address'] = $dealer_name->address1;
							$pary_data['id'] = $dealer_name->id;
							$pary_data['type'] = 'dealer_id';
							if ($dealer_name->show_separate_report) {
								array_push($self_parties, $pary_data);
							}
						} else if ($inventory->product_company_id) {
							$pary_data = array();
							$product_company_name = \App\ProductCompany::where('id', $inventory->product_company_id)->whereIn('id',$unique_product_companies)->first();
							$pary_data['party_name'] = $product_company_name->name;
							$pary_data['address'] = $product_company_name->address;
							$pary_data['id'] = $product_company_name->id;
							$pary_data['type'] = 'product_company_id';
							array_push($product_companies, $pary_data);
						}
					}
				}
				$data['self_parties'] = $self_parties;
				$data['product_companies'] = $product_companies;

				$data['warehouses'] = \App\Warehouse::where('is_active', 1)->whereIn('id',$unique_warehouses)->get();
				$product = array();
				$product['id'] = $unique_product->product_id;
				$product['name'] = getModelById('Product', $unique_product->product_id)->name;
				$product['product_brand_id'] = $unique_product->product_brand_id;
				$product['product_category_id'] = getModelById('Product',$unique_product->product_id)->product_category_id;

				$product['product_company'] = getModelById('ProductCompany', $unique_product->product_brand_id)->abbreviation;
				array_push($products, $product);
				$data['products'] = $products;
			}
		}
		else{
			$data['products'] = $products;
		}
		return view('dashboard.inventory.daily-stock-report',$data);
	}


	public function opening_stock(Request $request)
	{

		\App\OpeningInventory::where('quantity',0)->delete();


		$data = array();
		$data['warehouses'] = \App\Warehouse::where('is_active', 1)->get();
		$data['product_categories'] = \App\ProductCategory::where('is_active', 1)->get();
		$query = \App\OpeningInventory::query();
		if ($request->isMethod('post')) {
			if ($request->warehouse_id) {
				$query->where('warehouse_id', $request->warehouse_id);
				$data['warehouse_id'] = $request->warehouse_id;
				$data['warehouses'] = \App\Warehouse::where('is_active', 1)->where('id', $request->warehouse_id)->get();
			}
		}

		$unique_products = $query->select('product_brand_id', 'product_id')->distinct()->get();
		$unique_warehouses = array_unique(\App\OpeningInventory::pluck('warehouse_id')->toArray());
		$unique_product_companies = array_unique(\App\OpeningInventory::pluck('product_company_id')->toArray());

		$products = array();
		$query = \App\OpeningInventory::query();
		if ($request->isMethod('post')) {
			if ($request->warehouse_id) {
				$query->where('warehouse_id', $request->warehouse_id);
				$data['warehouse_id'] = $request->warehouse_id;
				$data['warehouses'] = \App\Warehouse::where('is_active', 1)->where('id', $request->warehouse_id)->get();
			}
		}
		if (count($unique_products) > 0) {
			foreach ($unique_products as $unique_product) {
				$inventories = \App\OpeningInventory::select('dealer_id', 'product_company_id')->distinct()->get();
				if ($inventories) {
					$other_parties = array();
					$self_parties = array();
					$product_companies = array();
					foreach ($inventories as $inventory) {
						if ($inventory->dealer_id) {
							$pary_data = array();
							$dealer_name = \App\Dealer::where('id', $inventory->dealer_id)->first();
							$pary_data['party_name'] = $dealer_name->name;
							$pary_data['address'] = $dealer_name->address1;
							$pary_data['id'] = $dealer_name->id;
							$pary_data['type'] = 'dealer_id';
							if ($dealer_name->show_separate_report) {
								array_push($self_parties, $pary_data);
							}
						} else if ($inventory->product_company_id) {
							$pary_data = array();
							$product_company_name = \App\ProductCompany::where('id', $inventory->product_company_id)->whereIn('id',$unique_product_companies)->first();
							$pary_data['party_name'] = $product_company_name->name;
							$pary_data['address'] = $product_company_name->address;
							$pary_data['id'] = $product_company_name->id;
							$pary_data['type'] = 'product_company_id';
							array_push($product_companies, $pary_data);
						}
					}
				}
				$data['self_parties'] = $self_parties;
				$data['product_companies'] = $product_companies;

				$data['warehouses'] = \App\Warehouse::where('is_active', 1)->whereIn('id',$unique_warehouses)->get();
				$product = array();
				$product['id'] = $unique_product->product_id;
				$product['name'] = getModelById('Product', $unique_product->product_id)->name;
				$product['product_brand_id'] = $unique_product->product_brand_id;
				$product['product_category_id'] = getModelById('Product',$unique_product->product_id)->product_category_id;

				$product['product_company'] = getModelById('ProductCompany', $unique_product->product_brand_id)->abbreviation;
				array_push($products, $product);
				$data['products'] = $products;
			}
		}
		else{
			$data['products'] = $products;
		}
		return view('dashboard.inventory.opening-stock',$data);
	}


	public function export_daily_stock_report(){
		\App\Inventory::where('quantity',0)->delete();

		$data = array();
		$data['product_categories'] = $product_categories = \App\ProductCategory::where('is_active', 1)->get();
		$query = \App\Inventory::query();
		$unique_products = $query->select('product_brand_id', 'product_id')->distinct()->get();

		$products = array();
		$query = \App\Inventory::query();

		$unique_warehouses = array_unique(\App\Inventory::pluck('warehouse_id')->toArray());
		$unique_product_companies = array_unique(\App\Inventory::pluck('product_company_id')->toArray());

		if (count($unique_products) > 0) {
			foreach ($unique_products as $unique_product) {
				$inventories = \App\Inventory::select('dealer_id', 'product_company_id')->distinct()->get();
				if ($inventories) {
					$other_parties = array();
					$self_parties = array();
					$product_companies = array();
					foreach ($inventories as $inventory) {
						if ($inventory->dealer_id) {
							$pary_data = array();
							$dealer_name = \App\Dealer::where('id', $inventory->dealer_id)->first();
							$pary_data['party_name'] = $dealer_name->name;
							$pary_data['address'] = $dealer_name->address1;
							$pary_data['id'] = $dealer_name->id;
							$pary_data['type'] = 'dealer_id';
							if ($dealer_name->show_separate_report) {
								array_push($self_parties, $pary_data);
							}
						} else if ($inventory->product_company_id) {
							$pary_data = array();
							$product_company_name = \App\ProductCompany::where('id', $inventory->product_company_id)->whereIn('id',$unique_product_companies)->first();
							$pary_data['party_name'] = $product_company_name->brand_name;
							$pary_data['address'] = $product_company_name->address;
							$pary_data['id'] = $product_company_name->id;
							$pary_data['type'] = 'product_company_id';
							array_push($product_companies, $pary_data);
						}
					}
				}
				$data['self_parties'] = $self_parties;
				$data['product_companies'] = $product_companies;

				$data['warehouses'] = $warehouses = \App\Warehouse::where('is_active', 1)->whereIn('id',$unique_warehouses)->get();

				$product = array();
				$product['id'] = $unique_product->product_id;
				$product['name'] = getModelById('Product', $unique_product->product_id)->name;
				$product['product_brand_id'] = $unique_product->product_brand_id;
				$product['product_category_id'] = getModelById('Product',$unique_product->product_id)->product_category_id;

				$product['product_company'] = getModelById('ProductCompany', $unique_product->product_brand_id)->abbreviation;
				array_push($products, $product);
				$data['products'] = $products;
			}
		}else{
			$data['products'] = $products;
		}
		$daily_stock_report = array();
		$report_header = array();
		$product_rows = array();

		array_push($report_header,"Product");
		foreach($self_parties as $self_party){
			$party_name = implode("\n",explode(' ', $self_party['party_name']))."\n".'('.implode("\n",explode(' ', $self_party['address'])).')';
			array_push($report_header,$party_name);
		}
		array_push($report_header,"Other \n Party");
		foreach($product_companies as $product_company){
			$party_name = implode("\n",explode(' ', $product_company['party_name']))."\n".'('.$product_company['address'].')';
			array_push($report_header,$party_name);
		}
		array_push($report_header,"Total");
		foreach($warehouses as $warehouse){
			array_push($report_header,implode("\n",explode(' ', $warehouse->name)));
		}
		array_push($report_header,"Total");

		array_push($daily_stock_report,$report_header);

		foreach($product_categories  as $product_cat){
			$category_row = array();
			array_push($category_row,$product_cat->category);
			array_push($daily_stock_report,$category_row);
			if(count($products) > 0){
				foreach($products as $product){
					if($product['product_category_id'] == $product_cat->id){
						$row = array();
						array_push($row,$product['name'].'/'.$product['product_company']);
						array_push($category_row,"");

						$party_total = 0;
						foreach($self_parties as $self_party){
							if(is_null(getPartyInventoryByProductAndBrand($self_party['id'],$product['id'],$product['product_brand_id'],$self_party['type']))){
								array_push($row,'--');
								array_push($category_row,"");
							}else { 
								array_push($row,getPartyInventoryByProductAndBrand($self_party['id'],$product['id'],$product['product_brand_id'],$self_party['type']));
								array_push($category_row,"");

								$party_total = $party_total + getPartyInventoryByProductAndBrand($self_party['id'],$product['id'],$product['product_brand_id'],$self_party['type']);

							}
						}

						array_push($row,getOtherPartyInventoryByProductAndBrand($product['id'],$product['product_brand_id']));
						array_push($category_row,"");

						$party_total = $party_total + getOtherPartyInventoryByProductAndBrand($product['id'],$product['product_brand_id']);
						foreach($product_companies as $product_company){
							if(is_null(getPartyInventoryByProductAndBrand($product_company['id'],$product['id'],$product['product_brand_id'],$product_company['type']))){
								array_push($row,'--');
								array_push($category_row,"");
							}else { 
								array_push($row,getPartyInventoryByProductAndBrand($product_company['id'],$product['id'],$product['product_brand_id'],$product_company['type']));
								array_push($category_row,"");

								$party_total = $party_total + getPartyInventoryByProductAndBrand($product_company['id'],$product['id'],$product['product_brand_id'],$product_company['type']);

							}
						}
						array_push($row,$party_total);


						$warehouse_total = 0;
						foreach($warehouses as $warehouse){
							if(is_null(getInventory($product['product_brand_id'],$warehouse['id'],$product['id']))){
								array_push($row,'--');
								array_push($category_row,"");
							} else { 
								array_push($row,getInventory($product['product_brand_id'],$warehouse['id'],$product['id']));
								array_push($category_row,"");
								$warehouse_total = $warehouse_total + getInventory($product['product_brand_id'],$warehouse['id'],$product['id']);

							}
						}

						array_push($row,$warehouse_total);
						array_push($daily_stock_report,$row);
					}
				}

			}
		}

		array_push($daily_stock_report,$product_rows);
		\Excel::create('Daily Stock Report', function($excel) use($daily_stock_report) {
			$excel->getDefaultStyle()
			->getAlignment()
			->applyFromArray(array(
				'wrap'	 	=> TRUE
			));

			$excel->sheet('Daily Stock Report', function($sheet) use($daily_stock_report) {
				$sheet->fromArray($daily_stock_report);
				$sheet->setFontBold(true);
				$sheet->setAllBorders('thin');
				$sheet->setStyle(array(
					'font' => array(
						'name'      =>  'Calibri',
						'size'      =>  16,
						'bold'      =>  true
					)
				));
			});
		})->export('xls');
	}


	public function dateConverter($date)
	{
		$temp_date = explode('/', $date);
		$new_date = $temp_date[2].'-'.$temp_date[1].'-'.$temp_date[0];
		return $new_date;
	}

	public function paymentRebateReport(Request $request)
	{
		$data = array();
		
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['dealers'] = \App\Dealer::whereIn('id',[1,30,31])->where('is_active',1)->get();
		$data['company_dis'] = array();
		if ($request->isMethod('post')) {
			$validator = \Validator::make($request->all(),
				array(
					'product_company_id' => 'required',
					'dealer_id' => 'required'
				)
			);
			if ($validator->fails()) {
				return redirect('user/payment-rebate-report')
				->withErrors($validator)
				->withInput();
			} else {

				$product_company_id = $request->product_company_id;
				$dealer_id = $request->dealer_id;
				$company_dis = \DB::table('company_dis')->where('product_company_id',$product_company_id)->where('dealer_id',$dealer_id)->get();
			
				$warehouse_dis = \DB::table('warehouse_dis')->where('dealer_id',$dealer_id)->where('product_company_id',$product_company_id)->get();
				$invoices = [];
				
				if($company_dis != null && $warehouse_dis != null){
					$n = 0;
					
					foreach ($company_dis as $key => $company_di) {
						$company_di->invoice_type = 'company_dis';
						$invoices[$n] = $company_di;
						$n++;
					}
					foreach ($warehouse_dis as $key1 => $warehouse_di) {
						$warehouse_di->invoice_type = 'warehouse_dis';
						$invoices[$n] = $warehouse_di;
						$n++;
					}
				}else if($company_dis == null && $warehouse_dis != null){
					$n = 0;
					
					foreach ($warehouse_dis as $key1 => $warehouse_di) {
						$warehouse_di->invoice_type = 'warehouse_dis';
						$invoices[$n] = $warehouse_di;
						$n++;
					}
				}else if($company_dis != null && $warehouse_dis == null){
					$n = 0;
					foreach ($company_dis as $key => $company_di) {
						$company_di->invoice_type = 'company_dis';
						$invoices[$n] = $company_di;
						$n++;
					}
					
				}
				

				if (count($invoices) > 0)
				{
					$data['product_company_id'] = $request->product_company_id;
					$data['dealer_id'] = $request->dealer_id;
					$data['company_dis'] = $invoices;
				}
			}
		}
		return view('dashboard.inventory.payment-rebate-report',$data);
	}

	public function arrived_stock(Request $request)
	{
		$data = array();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$product_loadings = \App\ProductLoading::where('is_approved',0)
		->where('warehouse_id','!=',null)
		->with('token')
		->get();
		$data['product_loadings'] = $product_loadings;
		return view('dashboard.warehouse_management.arrived-stock',$data);
	}
	public function post_arrived_stock(Request $request)
	{

		$validator = \Validator::make($request->all(),
			array(
				'dealer_id' =>'required',
				'product_company_id' =>'required',
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
			$inventory = \App\Inventory::where('dealer_id',$request->dealer_id)->where('warehouse_id',$request->warehouse_id)->where('product_brand_id',$request->product_company_id)->where('product_id',$request->product_id)->first();
			$product_loadings = \App\ProductLoading::where('dealer_id',$request->dealer_id)->where('warehouse_id',$request->warehouse_id)->where('product_company_id',$request->product_company_id)->where('product_id',$request->product_id)->where('id',$request->id)->first();

			if(!is_null($inventory) && !is_null($product_loadings)){
				$inventory->quantity = $inventory->quantity + $request->quantity;
			}else{
				$inventory = new  \App\Inventory();
				$inventory->dealer_id 			= $request->dealer_id;
				$inventory->warehouse_id 		= $request->warehouse_id;
				$inventory->product_brand_id 	= $request->product_company_id;
				$inventory->product_id 			= $request->product_id;
				$inventory->quantity 			= $request->quantity;
				$inventory->unit_id = $request->unit_id;
			}
			$product_loadings->recieved_quantity =$request->quantity;
			$product_loadings->is_approved=1;
			if($inventory->save() && $product_loadings->save())
			{
				$response['flag'] = true;
				$response['message'] = "Quantity approved Successfully";
			} else {
				$response['flag'] = false;
				$response['error'] = "Something Went Wrong";
			}
		}
		return response()->json($response);
	}

	public function warehouseLoadingSlips(Request $request){
		$data = array();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['invoice_types'] = \App\InvoiceType::where('is_active',1)->get();
		$data['companies'] = \App\Company::where('is_active',1)->where('for_invoice',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'from_Warehouse_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/warehouse-loading-slips')
				->withErrors($validator)
				->withInput();
			}else{
				if($request->include_non_freight == 1){
					$product_loadings = \App\ProductLoading::where('from_Warehouse_id',$request->from_Warehouse_id)
					->where('freight','>',0)
					->with('token')->get();
				}elseif($request->include_non_freight == 0){
					$product_loadings = \App\ProductLoading::where('from_Warehouse_id',$request->from_Warehouse_id)
					->with('token')->get();
				}
				$data['include_non_freight'] = $request->include_non_freight;
				$data['product_loadings'] = $product_loadings;
				$data['from_Warehouse_id'] = $request->from_Warehouse_id;


			}
		}else{
			//$data['product_loadings'] = array();
			$product_loadings = \App\ProductLoading::where('from_Warehouse_id', '!=', '')
			->with('token')->get();

			$data['product_loadings'] = $product_loadings;
		}

		return view('dashboard.warehouse_management.product-loading-list',$data);
	} 

	public function warehouseLabourSlips(Request $request){
		$data = array();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'from_Warehouse_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/warehouse-labour-slips')
				->withErrors($validator)
				->withInput();
			}else{

				if(isset($request->date)){
					$product_loadings = \App\ProductLoading::where('from_Warehouse_id',$request->from_Warehouse_id)->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)))->with('token','labour_payment')->get();
					$data['date'] = $request->date;
				}else{
					$product_loadings = \App\ProductLoading::where('from_Warehouse_id',$request->from_Warehouse_id)->whereDate('created_at', '=', date('Y-m-d'))->with('token','labour_payment')->get();
				}
				$data['product_loadings'] = $product_loadings;
				$data['from_Warehouse_id'] = $request->from_Warehouse_id;

			}
		}else{
			$data['product_loadings'] = array();
			$data['date'] = date('m/d/Y');
		}
		return view('dashboard.warehouse_management.labour-slips',$data);
	}

	public function partyInventory(Request $request)
	{
		$data = array();

		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'product_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/party-inventory')
				->withErrors($validator)
				->withInput();
			}else{
				$query = \App\Inventory::query();

				if($request->dealer_id){
					$query->where('dealer_id',$request->dealer_id);
					$data['dealer_id'] = $request->dealer_id;
				}
				if($request->product_company_id){
					$query->where('product_company_id',$request->product_company_id);
					$data['product_company_id'] = $request->product_company_id;
				}
				if($request->product_brand_id){
					$query->where('product_brand_id',$request->product_brand_id);
					$data['product_brand_id'] = $request->product_brand_id;
				}
				if($request->warehouse_id){
					$query->where('warehouse_id',$request->warehouse_id);
					$data['warehouse_id'] = $request->warehouse_id;
				}		
				$total = $query->where('product_id',$request->product_id)->sum('quantity');
				$inventories = $query->where('product_id',$request->product_id)->with('product:id,name','dealer:id,name,address1','product_company:id,name','product_brand:id,name,brand_name','warehouse:id,name','unit:id,unit')->get();
				$data['product_id'] = $request->product_id;
				$data['inventories'] = $inventories;
				$data['total'] = $total;
			}
		}
		else{

		}

		return view('dashboard.inventory.party-inventory',$data);
	}


	public function partyOpeningStock(Request $request)
	{
		$data = array();

		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					// 'product_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/party-opening-stock')
				->withErrors($validator)
				->withInput();
			}else{
				$query = \App\OpeningInventory::query();

				if($request->dealer_id){
					$query->where('dealer_id',$request->dealer_id);
					$data['dealer_id'] = $request->dealer_id;
				}
				if($request->product_company_id){
					$query->where('product_company_id',$request->product_company_id);
					$data['product_company_id'] = $request->product_company_id;
				}
				if($request->product_brand_id){
					$query->where('product_brand_id',$request->product_brand_id);
					$data['product_brand_id'] = $request->product_brand_id;
				}
				if($request->warehouse_id){
					$query->where('warehouse_id',$request->warehouse_id);
					$data['warehouse_id'] = $request->warehouse_id;
				}
				if($request->product_id){
					$query->where('product_id',$request->product_id);
					$data['product_id'] = $request->product_id;
				}		
				$total = $query->sum('quantity');
				$inventories = $query->with('product:id,name','dealer:id,name,address1','product_company:id,name','product_brand:id,brand_name','warehouse:id,name','unit:id,unit')->get();
				$data['inventories'] = $inventories;
				$data['total'] = $total;
			}
		}
		else{

		}

		return view('dashboard.inventory.party-opening-stock',$data);
	}

	public function otherStock(Request $request){
		$data = array();

		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'product_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/other-stock')
				->withErrors($validator)
				->withInput();
			}else{
				$query = \App\OtherInventory::query();

				if($request->dealer_id){
					$query->where('dealer_id',$request->dealer_id);
					$data['dealer_id'] = $request->dealer_id;
				}
				if($request->product_company_id){
					$query->where('product_company_id',$request->product_company_id);
					$data['product_company_id'] = $request->product_company_id;
				}
				if($request->product_brand_id){
					$query->where('product_brand_id',$request->product_brand_id);
					$data['product_brand_id'] = $request->product_brand_id;
				}
				if($request->warehouse_id){
					$query->where('warehouse_id',$request->warehouse_id);
					$data['warehouse_id'] = $request->warehouse_id;
				}		
				$total = $query->where('product_id',$request->product_id)->sum('quantity');
				$inventories = $query->where('product_id',$request->product_id)->with('product:id,name','dealer:id,name,address1','product_company:id,name','product_brand:id,brand_name','warehouse:id,name','unit:id,unit')->get();
				$data['product_id'] = $request->product_id;
				$data['inventories'] = $inventories;
				$data['total'] = $total;
			}
		}
		else{
			$inventories  = \App\OtherInventory::with('product:id,name','dealer:id,name,address1','product_company:id,name','product_brand:id,brand_name','warehouse:id,name','unit:id,unit')->get();
			$data['inventories'] = $inventories;
		}

		return view('dashboard.inventory.other-stock',$data);
	}


	public function monthlyRebateReport(Request $request){
		$data = array();

		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'month' =>'required',
					'year' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/monthly-rebate-report')
				->withErrors($validator)
				->withInput();
			}else{
				$month = $request->month;
				$year = $request->year;
				$start = $year.'-'.$month.'-01';
				$end = $year.'-'.$month.'-31';

				$company_dis_rabate = \DB::table('company_dis')->select('product_company_id','payment_date', \DB::raw('SUM(claim_amount) as total_claim'))->whereBetween('payment_date',[$start,$end])->groupBy('product_company_id')->get();

				$warehouse_dis_rabate = \DB::table('warehouse_dis')->select('product_company_id','payment_date', \DB::raw('SUM(claim_amount) as total_claim'))->whereBetween('payment_date',[$start,$end])->groupBy('product_company_id')->get();
				$rebate = array();
				$n=0;
				foreach ($company_dis_rabate as  $crebate) {
					$rebate[$n] = $crebate;
					$n++;
				}
				foreach ($warehouse_dis_rabate as  $wrebate) {
					$rebate[$n] = $wrebate;
					$n++;
				}
				$data['rebates'] = $rebate;
			}
		}else{
			$data['rebates'] = [];
		}
		return view('dashboard.inventory.monthly-rebate-report',$data);
	}



	public function bankStatements(Request $request){
		$data = array();
		$data['banks'] = \App\BankAccount::where('is_active',1)->with('bank')->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'bank_id' => 'required',
					'month' =>'required',
					'year' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/monthly-rebate-report')
				->withErrors($validator)
				->withInput();
			}else{
				$month = $request->month;
				$year = $request->year;
				$start = $year.'-'.$month.'-01';
				$end = $year.'-'.$month.'-31';
				$bank_id = $request->bank_id;
				$statements = \DB::table('bank_statements')->where('bank_id',$bank_id)->whereBetween('transaction_date',[$start,$end])->get();
				$data['statements'] = $statements;
			}
		}else{
			$data['statements'] = array();
		}

		return view('dashboard.report.bank-statement',$data);

	}

	public function show_warehouse_stock(Request $request){

		$inventorie['inventories'] = \DB::table('inventories')
		->join('product_companies','product_companies.id','inventories.product_company_id')
		->join('warehouses','warehouses.id','inventories.warehouse_id')
		->join('products','products.id','inventories.product_id')
		->join('units','units.id','inventories.unit_id')
		->select('product_companies.name as product_companies_name','product_companies.brand_name as brand_name','warehouses.name as warehouse_name','products.name as product_name','units.unit as units','inventories.quantity as quantity','inventories.damage_qty as damage_qty')
		->get();
		// dd($inventorie);
		return view('dashboard.stock.warehouse-stock',$inventorie);
	}


public function warehousefilter(REQUEST $request){
    print_r('asdfghjkmnbvcxzxcvb');
     
}

public function master_rakes(){
	$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
	$data['sessions'] = \App\Session::where('is_active',1)->get();
	$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
	$data['rake_points'] = \App\RakePoint::where('is_active',1)->get();
	$data['products'] = \App\Product::where('is_active',1)->get();
	return view('dashboard.rake_management.master_rake',$data);
} 



public function addMasterRake(Request $request){
		// dd($request->all());

	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'session_id' =>'required',
			'product_company_id' =>'required',
			'loading_time' =>'required',
			'rake_point' =>'required',
			'date' =>'required',
		)
	);

	if($validator->fails())
	{
		$response['flag'] = false;
		$response['errors'] = $validator->getMessageBag();
	}else{
		$master_rake =  new \App\MasterRake();
		$master_rake->session_id = $request->session_id;
		$master_rake->rake_point_id = $request->rake_point;
		$master_rake->product_company_id = $request->product_company_id;
		$master_rake->loading_time = $request->loading_time;
		$master_rake->unloading_time = $request->unloading_time;
		$master_rake->demurrage = $request->demurrage;
		$master_rake->wharfage = $request->wharfage;
		$master_rake->cheque_number = $request->cheque_number;
		//$master_rake->payment_date = date('Y-m-d',strtotime($request->payment_date));
		$master_rake->payment_date = '';
		if($request->hasFile('rr_document')) {
			$filenameWithExt = $request->file('rr_document')->getClientOriginalName();
			$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            
			$extension = $request->file('rr_document')->getClientOriginalExtension();
			$fileNameToStore = $filename.'_'.time().'.'.$extension;   
			$destinationPath = public_path().'/rr_document';
			$path = $request->file('rr_document')->move($destinationPath, $fileNameToStore);
			$master_rake->rr_document = str_replace(public_path(), '', $path);
		} 

		if($request->hasFile('warfage_document')) {
			$filenameWithExt = $request->file('warfage_document')->getClientOriginalName();
			$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            
			$extension = $request->file('warfage_document')->getClientOriginalExtension();
			$fileNameToStore = $filename.'_'.time().'.'.$extension;   
			$destinationPath = public_path().'/warfage_document';
			$path = $request->file('warfage_document')->move($destinationPath, $fileNameToStore);
			$master_rake->warfage_document = str_replace(public_path(), '', $path);
		} 

		$master_rake->date = date('Y-m-d',strtotime($request->date));
		if($master_rake->save()){
			$total = 0;
			$i = 0;
			$product_id = explode(',', $request->product_id);
			$quantity = explode(',', $request->quantity);
			foreach ($product_id as $key => $value) {
				$total = $total + $quantity[$i];
				$master_rake_product = \App\MasterRakeProduct::where('master_rake_id',$master_rake->id)->where('product_id',$product_id[$i])->first();
				if(!is_null($master_rake_product)){
					$master_rake_product->quantity 	 	 = $quantity[$i]+$master_rake_product->quantity ;
					$master_rake_product->remaining_quantity 	 	 = $quantity[$i]+$master_rake_product->remaining_quantity;
				}else{

					$master_rake_product = new \App\MasterRakeProduct();
					$master_rake_product->master_rake_id = $master_rake->id;
					$master_rake_product->product_id 	 = $product_id[$i];
					$master_rake_product->quantity 	 	 = $quantity[$i];
					$master_rake_product->remaining_quantity =  $quantity[$i];
				}
				$master_rake_product->save();

				/*----------update inventory-------------------*/
				$buffer_inventory = \App\Inventory::where('product_company_id',$request->product_company_id)->where('warehouse_id',24)->where('product_id',$product_id[$i])->where('product_brand_id',$request->product_company_id)->first();
                
				if(!is_null($buffer_inventory)){
					$buffer_inventory->quantity = $buffer_inventory->quantity + $quantity[$i];
					$buffer_inventory->save();
				}else{
					$buffer_inventory = new  \App\Inventory();
					$buffer_inventory->product_company_id 			= $request->product_company_id;
					$buffer_inventory->product_brand_id 			= $request->product_company_id;
					$buffer_inventory->rake_point_id			    = $request->rake_point;
					$buffer_inventory->warehouse_id 				= 24;
					$buffer_inventory->product_id 					= $product_id[$i];
					$buffer_inventory->quantity 					= $quantity[$i];
					$buffer_inventory->unit_id 					    = 1;
					$buffer_inventory->save();
				}
				/*----------update inventory-------------------*/


				$i++;
			}
			$product_company = getModelById('ProductCompany',$request->product_company_id);
			$session = getModelById('Session',$request->session_id);
			$master_rake->name = $product_company->brand_name.'/'.date('d/m/Y',strtotime($request->date)).'/'.$session->session.'/'.$master_rake->id;
			$master_rake->quantity_alloted = $total;
			$master_rake->save();
			$response['flag'] = true;
			$response['message'] = "Master Rake Added Successfully";
		}else{
			$response['flag'] = false;
			$response['error'] = "Something Went Wrong";
		}
	}
	return response()->json($response);
}

public function getEditMasterRake($id){
	$data['master_rake'] = \App\MasterRake::where('id',$id)->where('is_active',1)->with('master_rake_products')->first();
	$data['sessions'] = \App\Session::where('is_active',1)->get();
	$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
	$data['products'] = \App\Product::where('is_active',1)->get();
	$data['rake_points'] = \App\RakePoint::where('is_active',1)->get();
	return view('dashboard.master.edit-master-rake',$data);
} 


public function updateMasterRake(Request $request){

	// dd($request->all());
	// $filenameWithExt = $request->file('warfage_document')->getClientOriginalName();
	// dd( public_path().'/warfage_document');
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'session_id' =>'required',
			'product_company_id' =>'required',
			'loading_time' =>'required',
			'date' =>'required',
			'rake_point' =>'required',
		)
	);
	if($validator->fails())
	{
		$response['flag'] = false;
		$response['errors'] = $validator->getMessageBag();
	}else{
		$master_rake =  \App\MasterRake::where('id',$request->id)->where('is_active',1)->first();
		if(is_null($master_rake)){
			$response['flag'] = false;
			$response['error'] = "Master Rake Not found";
		}else{
			$master_rake->session_id = $request->session_id;
			$master_rake->rake_point_id = $request->rake_point;
			$master_rake->product_company_id = $request->product_company_id;
			$master_rake->loading_time = $request->loading_time;
			$master_rake->unloading_time = $request->unloading_time;
			$master_rake->date = date('Y-m-d',strtotime($request->date));
			$master_rake->demurrage = $request->demurrage;
			$master_rake->wharfage = $request->wharfage;
			$master_rake->cheque_number = $request->cheque_number;
			$master_rake->payment_date = date('Y-m-d',strtotime($request->payment_date));


			if($request->hasFile('rr_document')) {
				$filenameWithExt = $request->file('rr_document')->getClientOriginalName();
				$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            
				$extension = $request->file('rr_document')->getClientOriginalExtension();
				$fileNameToStore = $filename.'_'.time().'.'.$extension;   
				$destinationPath = public_path().'/rr_document';
				$path = $request->file('rr_document')->move($destinationPath, $fileNameToStore);
				$master_rake->rr_document = str_replace(public_path(), '', $path);
			} 

			if($request->hasFile('warfage_document')) {
				$filenameWithExt = $request->file('warfage_document')->getClientOriginalName();
				$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);            
				$extension = $request->file('warfage_document')->getClientOriginalExtension();
				$fileNameToStore = $filename.'_'.time().'.'.$extension;   
				$destinationPath = public_path().'/warfage_document';
				$path = $request->file('warfage_document')->move($destinationPath, $fileNameToStore);
				$master_rake->warfage_document = str_replace(public_path(), '', $path);
			} 

			if($master_rake->save()){
				$product_id = explode(',', $request->product_id);
				$quantity = explode(',', $request->quantity);
				$excess_quantity = explode(',', $request->excess_quantity);
				$shortage_from_company = explode(',', $request->shortage_from_company);


				\App\MasterRakeProduct::where('master_rake_id', $master_rake->id)->delete();
				$i = 0;
				foreach ($product_id as $key => $value) {
					$master_rake_product = \App\MasterRakeProduct::where('master_rake_id',$master_rake->id)->where('product_id',$product_id[$i])->first();
					if(!is_null($master_rake_product)){
						$master_rake_product->quantity 	 	 = $quantity[$i]+$master_rake_product->quantity ;
						$master_rake_product->remaining_quantity 	 	 = $quantity[$i]+$master_rake_product->remaining_quantity;
					}else{
						$master_rake_product = new \App\MasterRakeProduct();
						$master_rake_product->master_rake_id = $master_rake->id;
						$master_rake_product->product_id 	 = $product_id[$i];
						$master_rake_product->quantity 	 	 = $quantity[$i];
						$master_rake_product->remaining_quantity 	 	 = $quantity[$i];
						$master_rake_product->excess_quantity 	 	 = $excess_quantity[$i];
						$master_rake_product->shortage_from_company 	 	 = $shortage_from_company[$i];
					}
					$master_rake_product->save();

					$rake_excess_additions =  \App\RakeExcessAddition::where('master_rake_id',$master_rake->id)->where('product_id',$product_id[$i])->first();
					if($excess_quantity > 0 && is_null($rake_excess_additions)){
						$rake_excess_additions = new \App\RakeExcessAddition();
						$rake_excess_additions->master_rake_id = $master_rake->id;
						$rake_excess_additions->product_id 	 = $product_id[$i];
						$rake_excess_additions->quantity 	 = $excess_quantity[$i];
						$rake_excess_additions->save();
					}
					$i++;
				}
				$response['flag'] = true;
				$response['message'] = "Master Rake Updated Successfully";
			}else{
				$response['flag'] = false;
				$response['error'] = "Something Went Wrong";
			}
		}
	}
	return response()->json($response);
}


public function deleteMasterRake($id){
	$response = array();
	$master_rake = \App\MasterRake::where('id',$id)->where('is_active',1)->first();
	if(is_null($master_rake)){
		$response['flag'] = false;
		$response['message'] = "Master Rake Not Found";
	}else{
		$master_rake->is_active = 0;
		if($master_rake->save()){
			$response['flag'] = true;
			$response['message'] = "Master Rake Deleted";
		}else{
			$response['flag'] = false;
			$response['message'] = "Failed to delete";
		}
	}
	return response()->json($response);
} 


public function masterRakeDetails($id){
	$response = array();
	$master_rake = \App\MasterRake::where('id',$id)->where('is_active',1)->with('session','product_company')->first();
	if(is_null($master_rake)){
		$response['flag'] = false;
		$response['message'] = "Master Rake Not Found";
	}else{
		$response['flag'] = true;
		$response['master_rake'] = $master_rake;
	}
	return response()->json($response);
} 
public function lockMasterRake($id){
	$response = array();
	$master_rake = \App\MasterRake::where('id',$id)->first();
	if(!is_null($master_rake)){
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
		$response['message'] = "Rake Not Found";

	}
	return response()->json($response);
}  

public function standardization(){
	$data['warehouse_list'] = \App\Inventory::join('warehouses','warehouses.id','inventories.warehouse_id')
	->join('products','products.id','inventories.product_id')
	->select('warehouses.id as wh_id','warehouses.name as wh_name')
	->where('inventories.damage_qty','!=',0)
	->groupBy(['inventories.warehouse_id'])
	//->where('is_active',1)
	->get();

	$data['from_warehouse_list']=\App\Warehouse::where('is_active',1)->get();
	return view('dashboard.standardizations.make_standardization',$data);
}
 
public function addStandardization(Request $request){
// dd($request->all());
$response = array();
$validator = \Validator::make($request->all(),
	array(
		'warehouse_id' =>'required',
		'from_warehouse_id' =>'required',
		'product_id' =>'required',
		'damage_qty' =>'required',
	)
);

if($validator->fails())
{
	$response['flag'] = false;
	$response['errors'] = $validator->getMessageBag();
}else{
	$is_get_inventroy =  \App\Inventory::where('warehouse_id',$request->warehouse_id);
	$is_add_inventroy =  \App\Inventory::where('warehouse_id',$request->from_warehouse_id);


	foreach($request->product_id as $key=>$product_id){
		
		// dd($request->damage_qty[$key]);
		$product_id=(int)$product_id;
		
		$demage_qty=(int)$request->damage_qty[$key];
		if($demage_qty!='0'){
		$is_get_inventroy =  \App\Inventory::where(['warehouse_id'=>$request->warehouse_id,'product_brand_id'=>1,'product_id'=>$product_id])->first();
	    $is_add_inventroy =  \App\Inventory::where(['warehouse_id'=>$request->from_warehouse_id,'product_brand_id'=>1,'product_id'=>$product_id])->first();
		if($is_get_inventroy!=''){

			$is_get_inventroy->damage_qty=$is_get_inventroy->damage_qty-$demage_qty;
	
			 if($is_add_inventroy!=''){
	
				$is_add_inventroy->quantity=$is_add_inventroy->quantity+$demage_qty;

				$is_add_inventroy->save();
	
			 }else{

				$add_inventroy=new \App\Inventory();
				$add_inventroy->warehouse_id=$request->from_warehouse_id;
				$add_inventroy->product_company_id=1;
				$add_inventroy->product_brand_id=1;
				$add_inventroy->product_id=$product_id;
				$add_inventroy->unit_id=1;
				$add_inventroy->quantity=$demage_qty;
				$add_inventroy->save();

			 }
			 $is_get_inventroy->save();
			
		
	
		}

	        	$standardization_history =new \App\StandardizationHistory();
				
				$standardization_history->warehouse_id = $request->warehouse_id;
				$standardization_history->from_warehouse_id = $request->from_warehouse_id;
				$standardization_history->product_brand_id = 1;
				$standardization_history->date=date("Y-m-d");
				$standardization_history->product_id = $key;
				$standardization_history->qty = $demage_qty;
				
				$standardization_history->save();

				



	}


		
	}
	
	
	
		$response['flag'] = true;
		$response['msg'] = "Standrized Successful";
	
}
return response()->json($response);
}

public function showMasterRakeStock(Request $request){

	$MasterRakeStock['MasterRakeStock'] = \DB::table('inventories') 
	    ->join('rake_points','rake_points.id','inventories.rake_point_id')
		->join('product_companies','product_companies.id','inventories.product_company_id')
		->join('products','products.id','inventories.product_id')
		->join('units','units.id','inventories.unit_id')
		->select('product_companies.name as product_companies_name','product_companies.brand_name as brand_name','products.name as product_name','units.unit as units','inventories.quantity as quantity','rake_points.rake_point as rake_point')
		->get();
		// dd($inventorie);

	return view('dashboard.stock.master_rake_stock',$MasterRakeStock);
}



public function stocktransferwarehouse(){
	$data = array();
	$data['rake_points'] = \App\Inventory::join('rake_points','rake_points.id','inventories.rake_point_id')
	->join('products','products.id','inventories.product_id')
	->select('rake_points.id','rake_points.rake_point')
	->where('inventories.quantity','!=',0)
	->groupBy(['inventories.rake_point_id'])
	//->where('is_active',1)
	->get();
	// $data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
	$data['warehouse'] = \App\Warehouse::where('is_active',1)->get();
	$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
	// $data['rake_points'] = \App\RakePoint::where('is_active',1)->get();
	$data['products'] = \App\Product::where('is_active',1)->get();
	return view('dashboard.stock.stock_transfer_warehouse',$data);
}

public function get_product(Request $request){
	//dd($request->all());
	if($request->name == 'rake_point') {
		$product_qtys = \App\Inventory::where('rake_point_id', $request->warehouse_id)->with('product')->with('unit')->get();
	}	
	if($request->name == 'warehouse') {
		$product_qtys = \App\Inventory::where('warehouse_id', $request->warehouse_id)->with('product')->with('unit')->get();
	}
	//dd($product_qtys);
	$response = array();
	if($product_qtys != null) {
		$response['product_qtys'] = $product_qtys;
		$response['success'] = true;
	} else {
		$response['error'] = true;
		$response['msg'] = "No Product found.";
	}
	return response()->json($response);
}

	public function get_damage_product_qty(Request $request) {	
		if($request->name == 'rake_point') {
			$product_qtys = \App\Inventory::where('rake_point_id', $request->warehouse_id)->with('product')->with('unit')->get();
		}	
		if($request->name == 'warehouse') {
			$product_qtys = \App\Inventory::where('warehouse_id', $request->warehouse_id)->where('damage_qty','!=',0)->with('product')->with('unit')->get();
		}
		
		// print_r(json_decode($product_qtys));
		// exit;
		// // foreach($product_qtys as $product_qty) {
		// 	$product_qty->quantity; 
		// 	$product_qty->product_id; 
		// 	$product_qty->product->name; 
		// }
		$response = array();
		if($product_qtys != null) {
			$response['product_qtys'] = $product_qtys;
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
	
	

public function addstocktransferwarehouse(Request $request){
	//  dd($request->all());
	$response = array();
	$validator = \Validator::make($request->all(),
		array(
			'rake_point' =>'required',
			'warehouse_id' =>'required',
			'product_name' =>'required',
			'fresh_qty' =>'required',
			'demage_qty' =>'required',
		)
	);

	if($validator->fails())
	{
		$response['flag'] = false;
		$response['errors'] = $validator->getMessageBag();
	}else{
		
		// $is_rake_inventroy = \App\Inventory::where('rake_point_id', $request->rake_point)->where('warehouse_id', 24);
		// $is_warehouse_inventroy=\App\Inventory::where('warehouse_id', $request->warehouse_id);
		// dd($)
        // $i=;
		// foreach($request->fresh_qty as $v) {
		// 	print $v;
		// 	echo $request->demage_qty[$i];
		// 	echo $request->rake_point;
		// 	echo $request->warehouse_id;
        //  $i++;
		// }
		// die();

		foreach($request->product_name as $key=>$value){

		

			if($request->fresh_qty[$key]!='' || $request->demage_qty[$key]!=''){

				
				$fresh_qty=(int)$request->fresh_qty[$key];
				$demage_qty=(int)$request->demage_qty[$key];
				if($fresh_qty==''){
					$fresh_qty=0;
					
				}
				if($demage_qty==''){
					$demage_qty=0;
					
				}
				$product_id=$key;
				
		     	$is_rake_inventroy= \App\Inventory::where('rake_point_id', $request->rake_point)->where('warehouse_id', 24)->where('product_id', $product_id)->first();

				 if($is_rake_inventroy!=''){
					$is_rake_inventroy->quantity=$is_rake_inventroy->quantity-($fresh_qty+$demage_qty);
					$is_rake_inventroy->save();
	
				}

		    	$is_warehouse_inventroy=  \App\Inventory::where('warehouse_id', $request->warehouse_id)->where('product_id', $product_id)->first();

            //    dd($is_rake_inventroy);
			// 	dd($is_warehouse_inventroy);
			
			


				
			

				// else{
				// 	$rake_inventroy=new \App\Inventory();
				// 	$rake_inventroy->product_company_id=1;
				// 	$rake_inventroy->product_brand_id=1;
				// 	$rake_inventroy->product_id=$key;
				// 	$rake_inventroy->rake_point_id=$request->rake_point;
				// 	$rake_inventroy->unit_id=1;
				// 	$rake_inventroy->quantity=$fresh_qty+$demage_qty;
				// 	$rake_inventroy->save();


				// }

				if($is_warehouse_inventroy!=''){

					$is_warehouse_inventroy->quantity = $is_warehouse_inventroy->quantity + $fresh_qty;
					$is_warehouse_inventroy->damage_qty = $demage_qty + $is_warehouse_inventroy->damage_qty;
					$is_warehouse_inventroy->save();

				}else{

					$warehouse_inventroy=new \App\Inventory();
					$warehouse_inventroy->product_company_id=1;
					$warehouse_inventroy->product_brand_id=1;
					$warehouse_inventroy->product_id=$key;
					$warehouse_inventroy->warehouse_id=$request->warehouse_id;
					$warehouse_inventroy->unit_id=1;
					$warehouse_inventroy->quantity=$fresh_qty;
					$warehouse_inventroy->damage_qty=$demage_qty;
					$warehouse_inventroy->save();

				}

				$warehousestocktransfer =new \App\WarehouseStockTransfer();
				$warehousestocktransfer->rake_point_id = $request->rake_point;
				$warehousestocktransfer->product_brand_id = 1;
				$warehousestocktransfer->transfer_date=date("Y-m-d");
				$warehousestocktransfer->warehouse_id = $request->warehouse_id;
				$warehousestocktransfer->product_id = $key;
				$warehousestocktransfer->fresh_qty = $fresh_qty;
				$warehousestocktransfer->demage_qty =$demage_qty;
				// $warehousestocktransfer->save();

				if($warehousestocktransfer->save()){
					$response['flag'] = true;
					$response['message'] = "Warehouse Added Successfully";

				}
			}


		}


	
		
		

   }
   return response()->json($response);
   
}
}
