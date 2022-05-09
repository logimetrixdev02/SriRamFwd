<?php 



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Illuminate\Http\Response;

use Session;

/**

 * Invoice Controller

 */

class InvoiceController extends Controller

{


	public function generateInvoice(Request $request)
	{
		if($request->isMethod('post')) {
			dd($request->all());
		} else {
			return view('dashboard.report.generate-invoice');
		}
	}




	public function companyDi(Request $request)

	{

		$data = array();

		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();

		$data['dealers'] = \App\Dealer::where('is_active',1)->get();

		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();

		$data['products'] = \App\Product::where('is_active',1)->get();

		$data['bank_accounts'] = \App\BankAccount::where('is_active',1)->with('bank')->get();



		if ($request->isMethod('post')){

			// $validator = \Validator::make($request->all(),

			// 	array(

			// 		'product_id' =>'required',

			// 	)

			// );



			// if($validator->fails()){

			// 	return redirect('user/company-di')

			// 	->withErrors($validator)

			// 	->withInput();

			// }else{



				$query = \App\CompanyDi::query();

				if($request->dealer_id != ""){

					$query->where('dealer_id',$request->dealer_id);

					$data['dealer_id'] = $request->dealer_id;

				}

				if($request->product_company_id != ""){

					$query->Where('product_company_id',$request->product_company_id);

					$data['product_company_id'] = $request->product_company_id;

				}

				if($request->product_id != ""){

					$query->where('product_id',$request->product_id);

					$data['product_id'] = $request->product_id;

				}

				$query = $query->get();

				$data['total'] = $query->where('product_id',$request->product_id)->sum('quantity');

				$data['invoices'] = $query;

				

			//}

		}else{

			$data['invoices'] = \App\CompanyDi::get();

			$data['total'] = 0;

		}

		return view('dashboard.invoice.company-di',$data);

	}





	public function pendingCompanyDi(Request $request){

		$data = array();

		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();

		$data['products'] = \App\Product::where('is_active',1)->get();

		if ($request->isMethod('post')){

			$validator = \Validator::make($request->all(),

				array(

					'product_company_id' =>'required',

					'product_id' =>'required',

				)

			);

			if($validator->fails()){

				return redirect('user/pending-company-di')

				->withErrors($validator)

				->withInput();

			}else{

				$data['product_company_id'] = $request->product_company_id;

				$data['product_id'] = $request->product_id;

				$master_rake_ids = \App\MasterRake::where('product_company_id',$request->product_company_id)->pluck('id');

				$invoices = array();

				if(!is_null($master_rake_ids)){

					$rake_products = \App\MasterRakeProduct::whereIn('master_rake_id',$master_rake_ids)->where('product_id',$request->product_id)->selectRaw('master_rake_id,sum(quantity) as total_rr')->groupBy('master_rake_id')->with('master_rake:id,name')->get();



					foreach ($rake_products as $rake_product) {

						$companydi = \App\CompanyDi::where('master_rake_id',$rake_product->master_rake_id)->where('product_id',$request->product_id)->sum('quantity');

						$temp = array();

						$temp['rake'] = $rake_product->master_rake->name;

						$temp['total_rr'] = $rake_product->total_rr;

						$temp['total_di'] = $companydi;

						array_push($invoices,$temp);

					}



					$data['invoices'] = $invoices;

				}else{

					$data['invoices'] = array();

				}

				

			} 

		}else{

			$data['invoices'] = array();

		}

		return view('dashboard.invoice.pending-company-di',$data);

	}



	public function getGenerateCompanyDi()

	{

		$data = array();

		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();

		$data['companies'] = \App\Company::where('is_active',1)->where('for_invoice',1)->get();

		$data['dealers'] = \App\Dealer::where('is_active',1)->get();

		$data['products'] = \App\Product::where('is_active',1)->get();

		$data['units'] = \App\Unit::where('is_active',1)->get();



		return view('dashboard.invoice.generate-company-di',$data);

	}

	



	public function postGenerateCompanyDi(Request $request)

	{

		$validator = \Validator::make($request->all(),

			array(

				'master_rake_id'=>'required',

				'dealer_id'=>'required',

				'invoice_date' => 'required',

				'product_id' => 'required',

				'quantity' => 'required',

				'discount' => 'required',

				'secondary_freight' => 'required',

			)

		);

		if ($validator->fails()) {

			return redirect('/user/generate-company-di')

			->withErrors($validator)

			->withInput();

		}else{



			$master_rake_products = \App\MasterRakeProduct::where('master_rake_id',$request->master_rake_id)->sum('quantity');

			$di_amount = \App\CompanyDi::where('master_rake_id',$request->master_rake_id)->sum('quantity');

			$remaining_di_amount = $master_rake_products - $di_amount;



			// dd($master_rake_products);

			// dd($di_amount);

			// dd($remaining_di_amount);



			$product_company_buffer = \App\Inventory::where('product_company_id',$request->product_company_id)->where('warehouse_id',24)->where('product_id',$request->product_id)->where('product_brand_id',$request->product_company_id)->first();



			// dd($product_company_buffer);



			if(!is_null($product_company_buffer)){

				if($remaining_di_amount >= $request->quantity){



					$dealer_buffer = \App\Inventory::where('dealer_id',$request->dealer_id)->where('warehouse_id',24)->where('product_id',$request->product_id)->where('product_brand_id',$request->product_company_id)->first();

					if($dealer_buffer){

						$dealer_buffer->quantity = $dealer_buffer->quantity + $request->quantity;

						if($dealer_buffer->save()){

							$product_company_buffer->quantity = $product_company_buffer->quantity - $request->quantity;

							$product_company_buffer->save();

						}

					}else{



						$product_company_buffer->quantity = $product_company_buffer->quantity - $request->quantity;

						$product_company_buffer->save();



						$inventory = new \App\Inventory();

						$inventory->dealer_id  = $request->dealer_id;

						$inventory->warehouse_id  = 24;

						$inventory->product_brand_id  = $request->product_company_id;

						$inventory->product_id  = $request->product_id;

						$inventory->unit_id = $request->product_unit;

						$inventory->quantity  =  $request->quantity;

						$inventory->save();

					}





					$companydi = new \App\CompanyDi();

					$companydi->master_rake_id		= $request->master_rake_id;

					$companydi->product_company_id	= $request->product_company_id;

					$companydi->document_number		= $request->document_no;

					$companydi->dealer_id			= $request->dealer_id;

					$companydi->invoice_number		= $request->invoice_number;

					if($request->invoice_date){

						$companydi->invoice_date		= date('Y-m-d', strtotime($request->invoice_date));

					}

					if($request->dc_date){

						$companydi->dc_date				= date('Y-m-d', strtotime($request->dc_date));

					}

					if($request->due_date){

						$companydi->due_date			= date('Y-m-d', strtotime($request->due_date));

					}

					$companydi->product_hsn			= $request->product_hsn;

					$companydi->cgst				= $request->cgst;

					$companydi->sgst				= $request->sgst;

					$companydi->igst				= $request->igst;

					$companydi->secondary_freight	= $request->secondary_freight;

					$companydi->taxable_amount		= $request->taxable_amount;

					$companydi->discount			= $request->discount;

					$companydi->total				= $request->product_total_amount ;

					$companydi->remaining_amount	= $request->product_total_amount;

					$companydi->base_price			= $request->product_base_amount;

					$companydi->rate				= $request->product_rate;

					$companydi->unit				= $request->product_unit;

					$companydi->quantity			= $request->quantity;

					$companydi->product 			= getModelById('Product',$request->product_id)->name;

					$companydi->product_id 			= $request->product_id;

					$companydi->tcs				    = $request->tcs;

					$companydi->save();

					return redirect('/user/company-di')->with('success','DI Generated Successfully.');

					

				}else{

					return redirect('/user/company-di')->with('error',"DI Cannot be greater than rake quantity($master_rake_products). Remaining Quantity for DI is $remaining_di_amount. Entered Quantity was $request->quantity");

				}

				

			}else{

				return redirect('/user/generate-company-di')->with('error','quantity not available in buffer');

			}

		}

	}

	





	public function postGenerateCompanyDiOld(Request $request)

	{

		$validator = \Validator::make($request->all(),

			array(

				'master_rake_id'=>'required',

				'dealer_id'=>'required',

				'invoice_date' => 'required',

				'product_id' => 'required',

				'quantity' => 'required',

				'discount' => 'required',

				'secondary_freight' => 'required',

			)

		);

		if ($validator->fails()) {

			return redirect('/user/generate-company-di')

			->withErrors($validator)

			->withInput();

		}else{



			$master_rake_products = \App\MasterRakeProduct::where('master_rake_id',$request->master_rake_id)->sum('quantity');

			$di_amount = \App\CompanyDi::where('master_rake_id',$request->master_rake_id)->sum('quantity');

			$remaining_di_amount = $master_rake_products - $di_amount;



			// dd($master_rake_products);

			// dd($di_amount);

			// dd($remaining_di_amount);



			$product_company_buffer = \App\Inventory::where('product_company_id',$request->product_company_id)->where('warehouse_id',24)->where('product_id',$request->product_id)->where('product_brand_id',$request->product_company_id)->first();



			// dd($product_company_buffer);



			if(!is_null($product_company_buffer)){

				if($remaining_di_amount >= $request->quantity){



					if($product_company_buffer->quantity >= $request->quantity){

						$dealer_buffer = \App\Inventory::where('dealer_id',$request->dealer_id)->where('warehouse_id',24)->where('product_id',$request->product_id)->where('product_brand_id',$request->product_company_id)->first();

						if($dealer_buffer){

							$dealer_buffer->quantity = $dealer_buffer->quantity + $request->quantity;

							if($dealer_buffer->save()){

								$product_company_buffer->quantity = $product_company_buffer->quantity - $request->quantity;

								$product_company_buffer->save();



								$companydi = new \App\CompanyDi();

								$companydi->master_rake_id		= $request->master_rake_id;

								$companydi->product_company_id	= $request->product_company_id;

								$companydi->document_number		= $request->document_no;

								$companydi->dealer_id			= $request->dealer_id;

								$companydi->invoice_number		= $request->invoice_number;

								if($request->invoice_date){

									$companydi->invoice_date		= date('Y-m-d', strtotime($request->invoice_date));

								}

								if($request->dc_date){

									$companydi->dc_date				= date('Y-m-d', strtotime($request->dc_date));

								}

								if($request->due_date){

									$companydi->due_date			= date('Y-m-d', strtotime($request->due_date));;

								}

								$companydi->product_hsn			= $request->product_hsn;

								$companydi->cgst				= $request->cgst;

								$companydi->sgst				= $request->sgst;

								$companydi->igst				= $request->igst;

								$companydi->secondary_freight				= $request->secondary_freight;

								$companydi->taxable_amount		= $request->taxable_amount;

								$companydi->discount			= $request->discount;

								$companydi->total				= $request->product_total_amount;

								$companydi->remaining_amount				= $request->product_total_amount;

								$companydi->base_price			= $request->product_base_amount;

								$companydi->rate				= $request->product_rate;

								$companydi->unit				= $request->product_unit;

								$companydi->quantity			= $request->quantity;

								$companydi->product 			= getModelById('Product',$request->product_id)->name;

								$companydi->product_id = $request->product_id;

								$companydi->save();





								/*--------------Update Ledger------------------*/

								$ledger = \App\CompanyInvoiceLedger::where('dealer_id',$companydi->dealer_id)->where('product_company_id',$companydi->product_company_id)->orderBy('id','desc')->first();

								if(!is_null($ledger)){

									$balance = $ledger->balance;

									$ledger = new \App\CompanyInvoiceLedger();

									$ledger->product_company_id = $companydi->product_company_id;

									$ledger->dealer_id = $companydi->dealer_id;

									$ledger->particular = "Purchase @".($companydi->sgst + $companydi->cgst) ."% (GST)";

									$ledger->credit = $companydi->total;

									$ledger->debit = 0;

									$ledger->balance = $balance + $companydi->total;

									$ledger->save();





								}else{

									$ledger = new \App\CompanyInvoiceLedger();

									$ledger->product_company_id = $companydi->product_company_id;

									$ledger->dealer_id = $companydi->dealer_id;

									$ledger->particular = "Purchase @".($companydi->sgst + $companydi->cgst) ."% (GST)";

									$ledger->credit = $companydi->total;

									$ledger->debit = 0;

									$ledger->balance = $companydi->total;

									$ledger->save();

								}

								/*--------------Update Ledger------------------*/

								

								return redirect('/user/company-di')->with('success','DI Generated Successfully.');

							}

						}else{



							$product_company_buffer->quantity = $product_company_buffer->quantity - $request->quantity;

							$product_company_buffer->save();



							$direct_loading = \App\ProductLoading::where('master_rake_id',$request->master_rake_id)

							->where('product_company_id',$request->product_company_id)

							->where('product_id',$request->product_id)

							->where('loading_slip_type',2)

							->first();



							$inventory = new \App\Inventory();

							$inventory->dealer_id  = $request->dealer_id;

							if(!is_null($direct_loading)){

								$inventory->warehouse_id  = $direct_loading->warehouse_id;

							}else{

								$inventory->warehouse_id  = 24;

							}



							$inventory->product_brand_id  = $request->product_company_id;

							$inventory->product_id  = $request->product_id;

							$inventory->unit_id = $request->product_unit;

							$inventory->quantity  =  $request->quantity;

							$inventory->save();





							$companydi = new \App\CompanyDi();

							$companydi->master_rake_id		= $request->master_rake_id;

							$companydi->product_company_id	= $request->product_company_id;

							$companydi->document_number		= $request->document_no;

							$companydi->dealer_id			= $request->dealer_id;

							$companydi->invoice_number		= $request->invoice_number;

							if($request->invoice_date){

								$companydi->invoice_date		= date('Y-m-d', strtotime($request->invoice_date));

							}

							if($request->dc_date){

								$companydi->dc_date				= date('Y-m-d', strtotime($request->dc_date));

							}

							if($request->due_date){

								$companydi->due_date			= date('Y-m-d', strtotime($request->due_date));

							}

							$companydi->product_hsn			= $request->product_hsn;

							$companydi->cgst				= $request->cgst;

							$companydi->sgst				= $request->sgst;

							$companydi->igst				= $request->igst;

							$companydi->secondary_freight				= $request->secondary_freight;

							$companydi->taxable_amount		= $request->taxable_amount;

							$companydi->discount			= $request->discount;

							$companydi->total				= $request->product_total_amount;

							$companydi->remaining_amount	= $request->product_total_amount;

							$companydi->base_price			= $request->product_base_amount;

							$companydi->rate				= $request->product_rate;

							$companydi->unit				= $request->product_unit;

							$companydi->quantity			= $request->quantity;

							$companydi->product 			= getModelById('Product',$request->product_id)->name;

							$companydi->product_id = $request->product_id;

							$companydi->save();



							/*--------------Update Ledger------------------*/

							$ledger = \App\CompanyInvoiceLedger::where('dealer_id',$companydi->dealer_id)->where('product_company_id',$companydi->product_company_id)->orderBy('id','desc')->first();

							if(!is_null($ledger)){

								$balance = $ledger->balance;

								$ledger = new \App\CompanyInvoiceLedger();

								$ledger->product_company_id = $companydi->product_company_id;

								$ledger->dealer_id = $companydi->dealer_id;

								$ledger->particular = "Purchase @".($companydi->sgst + $companydi->cgst) ."% (GST)";

								$ledger->credit = $companydi->total;

								$ledger->debit = 0;

								$ledger->balance = $balance + $companydi->total;

								$ledger->save();





							}else{

								$ledger = new \App\CompanyInvoiceLedger();

								$ledger->product_company_id = $companydi->product_company_id;

								$ledger->dealer_id = $companydi->dealer_id;

								$ledger->particular = "Purchase @".($companydi->sgst + $companydi->cgst) ."% (GST)";

								$ledger->credit = $companydi->total;

								$ledger->debit = 0;

								$ledger->balance = $companydi->total;

								$ledger->save();

							}

							/*--------------Update Ledger------------------*/



							return redirect('/user/company-di')->with('success','DI Generated Successfully.');

						}

					}else if($product_company_buffer->quantity < $request->quantity){



						$total_product_stock = \App\Inventory::where('product_company_id',$request->product_company_id)->where('product_brand_id',$request->product_company_id)->where('product_id',$request->product_id)->where('unit_id',$request->product_unit)->sum('quantity');

						// dd($total_product_stock);



						if($total_product_stock >= $request->quantity){



							$stocks = \App\Inventory::where('product_id',$request->product_id)

							->where('product_company_id',$request->product_company_id)

							->where('product_brand_id',$request->product_company_id)

							->where('unit_id',$request->product_unit)

							->where('quantity','>',0)

							->orderBy('quantity','desc')

							->get();

							// dd($stocks);





							// $less_qty = $product_company_buffer->quantity;

							// $product_company_buffer->quantity = 0;

							// $product_company_buffer->save();

							// $remaining_quantity = $request->quantity - $less_qty;

							

							$remaining_quantity = $request->quantity;

							$i = 0;

							foreach ($stocks as $stock) {

								if($remaining_quantity > 0){

									

									$transfer_quantity = $stock->quantity;

									if($transfer_quantity <= $remaining_quantity ){

										$stock->quantity = 0;

									}else{

										$stock->quantity = $stock->quantity - $remaining_quantity;

									}

									$stock->save();



									$dealer_buffer = \App\Inventory::where('dealer_id',$request->dealer_id)->where('warehouse_id',$stock->warehouse_id)->where('product_id',$request->product_id)->where('product_brand_id',$request->product_company_id)->first();

									if(!is_null($dealer_buffer)){

										if($transfer_quantity <= $remaining_quantity ){

											$dealer_buffer->quantity = $dealer_buffer->quantity + $transfer_quantity;

										}else{

											$dealer_buffer->quantity = $dealer_buffer->quantity + $remaining_quantity;

										}



										$dealer_buffer->save();

										if($transfer_quantity <= $remaining_quantity){

											$remaining_quantity = $remaining_quantity - $transfer_quantity;

										}else{

											$remaining_quantity = 0;

										}

									}else{



										$inventory = new \App\Inventory();

										$inventory->dealer_id  = $request->dealer_id;

										$inventory->warehouse_id  = $stock->warehouse_id;

										$inventory->product_brand_id  = $request->product_company_id;

										$inventory->product_id  = $request->product_id;

										$inventory->unit_id = $request->product_unit;



										if($transfer_quantity <= $remaining_quantity){

											$inventory->quantity  =  $transfer_quantity;

										}else{

											$inventory->quantity  =  $remaining_quantity;

										}





										$inventory->save();



										if($transfer_quantity <= $remaining_quantity){

											$remaining_quantity = $remaining_quantity - $transfer_quantity;

										}else{

											$remaining_quantity = 0;

										}

									}



								}

								$i++;

							}



							// exit;

							$companydi = new \App\CompanyDi();

							$companydi->master_rake_id		= $request->master_rake_id;

							$companydi->product_company_id	= $request->product_company_id;

							$companydi->document_number		= $request->document_no;

							$companydi->dealer_id			= $request->dealer_id;

							$companydi->invoice_number		= $request->invoice_number;

							if($request->invoice_date){

								$companydi->invoice_date		= date('Y-m-d', strtotime($request->invoice_date));

							}

							if($request->dc_date){

								$companydi->dc_date				= date('Y-m-d', strtotime($request->dc_date));

							}

							if($request->due_date){

								$companydi->due_date			= date('Y-m-d', strtotime($request->due_date));

							}

							$companydi->product_hsn			= $request->product_hsn;

							$companydi->cgst				= $request->cgst;

							$companydi->sgst				= $request->sgst;

							$companydi->igst				= $request->igst;

							$companydi->secondary_freight				= $request->secondary_freight;

							$companydi->taxable_amount		= $request->taxable_amount;

							$companydi->discount			= $request->discount;

							$companydi->total				= $request->product_total_amount;

							$companydi->remaining_amount	= $request->product_total_amount;

							$companydi->base_price			= $request->product_base_amount;

							$companydi->rate				= $request->product_rate;

							$companydi->unit				= $request->product_unit;

							$companydi->quantity			= $request->quantity;

							$companydi->product 			= getModelById('Product',$request->product_id)->name;

							$companydi->product_id = $request->product_id;

							$companydi->save();



							/*--------------Update Ledger------------------*/

							$ledger = \App\CompanyInvoiceLedger::where('dealer_id',$companydi->dealer_id)->where('product_company_id',$companydi->product_company_id)->orderBy('id','desc')->first();

							if(!is_null($ledger)){

								$balance = $ledger->balance;

								$ledger = new \App\CompanyInvoiceLedger();

								$ledger->product_company_id = $companydi->product_company_id;

								$ledger->dealer_id = $companydi->dealer_id;

								$ledger->particular = "Purchase @".($companydi->sgst + $companydi->cgst) ."% (GST)";

								$ledger->credit = $companydi->total;

								$ledger->debit = 0;

								$ledger->balance = $balance + $companydi->total;

								$ledger->save();





							}else{

								$ledger = new \App\CompanyInvoiceLedger();

								$ledger->product_company_id = $companydi->product_company_id;

								$ledger->dealer_id = $companydi->dealer_id;

								$ledger->particular = "Purchase @".($companydi->sgst + $companydi->cgst) ."% (GST)";

								$ledger->credit = $companydi->total;

								$ledger->debit = 0;

								$ledger->balance = $companydi->total;

								$ledger->save();

							}

							/*--------------Update Ledger------------------*/



							return redirect('/user/company-di')->with('success','DI Generated Successfully.');



						}else{



							exit;

						}



					}

					else{

						return redirect('/user/generate-company-di')->with('error','Something went wrong');

					}



				}else{

					return redirect('/user/company-di')->with('error',"DI Cannot be greater than rake quantity($master_rake_products). Remaining Quantity for DI is $remaining_di_amount. Entered Quantity was $request->quantity");

				}

				

			}else{

				return redirect('/user/generate-company-di')->with('error','quantity not available in buffer');

			}

		}

	}





	public function postGenerateWarehouseDi(Request $request)

	{

		$response = array();



		$validator = \Validator::make($request->all(),

			array(

				'to_dealer'=>'required',

				'product_id' => 'required',

				'unit_id' => 'required',

				'product_brand_id' => 'required',

				'secondary_freight' => 'required',

			)

		);

		if ($validator->fails()) {

			return redirect('/user/generate-warehouse-di')

			->withErrors($validator)

			->withInput();

		}else{



			$total_quantity = array_sum($request->quantity);

			if($request->transfer_type == 1){



				$product_stock = \App\Inventory::where('product_company_id',$request->product_brand_id)->where('product_brand_id',$request->product_brand_id)->where('product_id',$request->product_id)->where('unit_id',$request->unit_id)->sum('quantity');

			}else{

				$product_stock = \App\Inventory::where('product_brand_id',$request->product_brand_id)->where('dealer_id',$request->from_dealer)->where('product_id',$request->product_id)->where('unit_id',$request->unit_id)->sum('quantity');



			}



			if($product_stock >= $total_quantity){

				$i = 0;

				foreach ($request->warehouse as $warehouse) {

					if($request->transfer_type == 1){

						$inventory = \App\Inventory::where('product_id',$request->product_id)->where('product_company_id',$request->product_company_id)->where('product_brand_id',$request->product_brand_id)->where('warehouse_id',$request->warehouse[$i])->first();

					}else if($request->transfer_type == 2){

						$inventory = \App\Inventory::where('product_id',$request->product_id)->where('dealer_id',$request->from_dealer)->where('product_brand_id',$request->product_brand_id)->where('warehouse_id',$request->warehouse[$i])->first();

					}

					if(!is_null($inventory)){

						// if($inventory->quantity >= $request->quantity[$i]){

						$inventory->quantity  = $inventory->quantity - $request->quantity[$i];

						$inventory->save();



						$dealer_inventory = \App\Inventory::where('product_id',$request->product_id)->where('dealer_id',$request->to_dealer)->where('product_brand_id',$request->product_brand_id)->where('warehouse_id',$request->warehouse[$i])->first();

						if(!is_null($dealer_inventory)){

							$dealer_inventory->quantity  = $dealer_inventory->quantity + $request->quantity[$i];

							$dealer_inventory->save();

						}else{

							$dealer_inventory = new \App\Inventory();

							$dealer_inventory->dealer_id  = $request->to_dealer;

							$dealer_inventory->warehouse_id  = $request->warehouse[$i];

							if($request->transfer_type == 1){

								$dealer_inventory->product_brand_id  = $request->product_company_id;

							}else{

								$dealer_inventory->product_brand_id  = $request->product_brand_id;

							}

							$dealer_inventory->product_id  = $request->product_id;

							$dealer_inventory->unit_id = $request->unit_id;

							$dealer_inventory->quantity  =  $request->quantity[$i];

							$dealer_inventory->save();

						}

						// }



						$warehouse_id = new \App\WarehouseDi();

						$warehouse_id->transfer_type		= $request->transfer_type;

						$warehouse_id->warehouse_id		= $request->warehouse[$i];

						if($request->transfer_type == 1){

							$warehouse_id->product_company_id	= $request->product_company_id;

						}else{

							$warehouse_id->from_dealer_id			= $request->from_dealer;

						}

						$warehouse_id->document_number		= $request->document_no;

						$warehouse_id->dealer_id			= $request->to_dealer;

						$warehouse_id->invoice_number		= $request->invoice_number;

						if($request->invoice_date){

							$warehouse_id->invoice_date		= date('Y-m-d', strtotime($request->invoice_date));

						}

						if($request->dc_date){

							$warehouse_id->dc_date			= date('Y-m-d', strtotime($request->dc_date));

						}

						if($request->due_date){

							$warehouse_id->due_date			= date('Y-m-d', strtotime($request->due_date));;

						}

						$warehouse_id->product_hsn			= $request->product_hsn;

						$warehouse_id->cgst				= $request->cgst;

						$warehouse_id->sgst				= $request->sgst;

						$warehouse_id->igst				= $request->igst;

						$warehouse_id->secondary_freight				= $request->secondary_freight;

						$warehouse_id->taxable_amount	= $request->taxable_amount;

						$warehouse_id->discount			= $request->discount;

						$warehouse_id->total			= $request->product_total_amount;

						$warehouse_id->remaining_amount	= $request->product_total_amount;

						$warehouse_id->base_price		= $request->product_base_amount;

						$warehouse_id->rate				= $request->product_rate;

						$warehouse_id->unit				= $request->unit[$i];

						$warehouse_id->quantity			= $request->quantity[$i];

						$warehouse_id->product 			= getModelById('Product',$request->product_id)->name;

						$warehouse_id->product_brand_id 		= $request->product_brand_id;

						$warehouse_id->product_id 		= $request->product_id;

						$warehouse_id->tcs				    = $request->tcs;

						$warehouse_id->save();



					}

					$i++;

				}



				return redirect('/user/generate-warehouse-di')->with('success',"DI Done Successfully");





			}else{

				return redirect('/user/generate-warehouse-di')->with('error',"Total Enter quantity should be less than or equal to total product stock $product_stock. Enter Quantity was $total_quantity  ");

			}

		}

	}







	public function warehouseDi(Request $request)

	{

		$data = array();

		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();

		$data['dealers'] = \App\Dealer::where('is_active',1)->get();

		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();

		$data['products'] = \App\Product::where('is_active',1)->get();

		$data['bank_accounts'] = \App\BankAccount::where('is_active',1)->with('bank')->get();

		if ($request->isMethod('post')){

			$validator = \Validator::make($request->all(),

				array(

					'type' =>'required',

					'product_id' =>'required',

				)

			);



			if($validator->fails()){

				return redirect('user/warehouse-di')

				->withErrors($validator)

				->withInput();

			}else{

				$query = \App\WarehouseDi::query();



				if($request->dealer_id){

					$query->where('dealer_id',$request->dealer_id);

					$data['dealer_id'] = $request->dealer_id;

				}

				if($request->product_company_id){

					$query->where('product_company_id',$request->product_company_id);

					$data['product_company_id'] = $request->product_company_id;

				}

				if($request->from_dealer_id){

					$query->where('from_dealer_id',$request->from_dealer_id);

					$data['from_dealer_id'] = $request->from_dealer_id;

				}

				if($request->warehouse_id){

					$query->where('warehouse_id',$request->warehouse_id);

					$data['warehouse_id'] = $request->warehouse_id;

				}

				if($request->product_id){

					$data['total'] = $query->where('product_id',$request->product_id)->sum('quantity');

					$data['product_id'] = $request->product_id;

				}

				

				$data['invoices'] = $query->orderBy('id','desc')->get();

				

				$data['type'] = $request->type;

			}

		}else{

			$data['invoices'] = \App\WarehouseDi::orderBy('id','desc')->get();

			$data['total'] = 0;

			$data['type'] = 1;

		}



		return view('dashboard.invoice.warehouse-di',$data);

	}



	public function getGenerateWarehouseDi()

	{

		$data = array();

		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();

		$data['companies'] = \App\Company::where('is_active',1)->where('for_invoice',1)->get();

		$data['dealers'] = \App\Dealer::where('is_active',1)->get();

		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();

		$data['products'] = \App\Product::where('is_active',1)->get();

		$data['units'] = \App\Unit::where('is_active',1)->get();

		return view('dashboard.invoice.generate-warehouse-di',$data);

	}

	public function invoices(Request $request)

	{

		$data = array();

		$data['dealers'] = \App\Dealer::where('is_active',1)->get();

		$data['retailer'] = \App\Retailer::where('is_active',1)->get();



		if ($request->isMethod('post')){

			$query = \App\LoadingSlipInvoice::query();

			if($request->dealer_id != ""){

				$query = $query->where('dealer_id',$request->dealer_id);

				$date['dealer_id'] = $request->dealer_id;

			}



			if ($request->retailer_id != "") {

				$query = $query->where('retailer_id',$request->retailer_id);

				$date['retailer_id'] = $request->retailer_id;

			}

			$query = $query->orderBy('id','desc')->paginate(100);

			$data['loading_slip_invoices'] = $query;

		}else{

			$data['loading_slip_invoices'] = \App\LoadingSlipInvoice::with('retailer_invoice_payments')->orderBy('id','desc')->paginate(100);

		}

		return view('dashboard.invoice.loading_slip_invoices',$data);

	}

	public function getPunchInvoice()

	{

		$data['companies'] = \App\Company::where('is_active',1)->where('for_invoice',1)->get();

		$data['retailers'] = \App\Retailer::where('is_active',1)->get();

		return view('dashboard.invoice.punchInvoice',$data);

	}

	public function postPunchInvoice(Request $request)

	{

		$response = array();

		$validator = \Validator::make($request->all(),

			array(

				'company_id' => 'required',

				'retailer_id' => 'required',

				'invoice_number' => 'required',

				'invoice_date' => 'required',

				'invoice_amount' => 'required',

			)

		);

		if ($validator->fails()) {

			$response['flag'] = false;

			$response['errors'] = $validator->getMessageBag();

		} else {

			$invoice = new \App\Invoice();

			$invoice->company_id = $request->company_id;

			$invoice->retailer_id = $request->retailer_id;

			$invoice->invoice_date = $request->invoice_date;

			$invoice->invoice_number = $request->invoice_number;

			$invoice->invoice_amount = $request->invoice_amount;

			$invoice->invoice_remarks = $request->invoice_remark;

			$invoice->received_date = $request->received_date;

			$invoice->received_amount = $request->received_amount;

			$duplicateInvoice = \App\Invoice::where('invoice_number',$request->invoice_number)->first();

			if ($duplicateInvoice) {

				$response['flag'] = false;

				$response['message'] = 'Invoice number already exists.';

			} else {

				if ($request->file('invoice_doc') !== '' || !empty($request->file('invoice_doc'))) {

					$destinationPath = 'uploads';

					$moved_file_status = $file->move($destinationPath,$file->getClientOriginalName());

					if ($moved_file_status) {

						

					}

				}

				if ($invoice->save()) {

					$response['flag'] = true;

					$response['message'] = "Invoice Punched Successfully.";

				} else {

					$response['flag'] = false;

					$response['error'] = "Something Went Wrong";

				}

			}

		}

		return response()->json($response);

	}



	// Function to get generated invoices

	public function getGeneratedInvoices()

	{

		

	}



	// Function to generate  invoice

	public function generateLoadingSlipInvoice()

	{

		$data['acting_company'] = Session::get('acting_company');

		$data['invoice_types'] = \App\InvoiceType::where('is_active',1)->get();

		$data['companies'] = \App\Company::where('is_active',1)->where('for_invoice',1)->get();

		$data['loading_slips'] = \App\ProductLoading::where('is_invoice_generated',0)->with('token')->get();

		$data['retailers'] = \App\Retailer::where('is_active',1)->get();

		$data['products']	= \App\Product::where('is_active',1)->get();

		$data['units']	= \App\Unit::where('is_active',1)->get();

		return view('dashboard.invoice.generate-loading-slip-invoice', $data);

	}



	public function loadingSlipDetails($loading_slip_id){

		$data = array();

		$decoded_value = base64_decode($loading_slip_id);

		$loadingSlipDetailsArray = explode(',', $decoded_value);

		if ($loadingSlipDetailsArray[0] == "loading_slip") {

			$data['retailers'] = \App\Retailer::where('is_active',1)->get();

			$data['acting_company'] = Session::get('acting_company');

			$data['invoice_types'] = \App\InvoiceType::where('is_active',1)->get();

			$data['companies'] = \App\Company::where('is_active',1)->where('for_invoice',1)->get();

			$product_loading = \App\ProductLoading::where('id',$loadingSlipDetailsArray[1])->with('token')->first();

			if(is_null($product_loading)){

				$data['status'] = false;

				$data['message'] = "Loading Slip Not found";

			}else if(is_null($product_loading->retailer_id)){

				$data['status'] = false;

				$data['message'] = "Invalid Loading Slip";

			}else if(is_null($product_loading->token_id) && $loading_slip->token->to_type != 2){

				$data['status'] = false;

				$data['message'] = "Scanning Wrong QR Code";

			}else if($product_loading->qr_scan_count > 0){

				$data['status'] = false;

				$data['message'] = "Invoice already Generated";

			}else{

				$data['status'] = true;

				$data['product_loading'] = $product_loading;

			}

		}else{

			$data['status'] = false;

			$data['message'] = "Invalid QR Code";

		}

		return view('dashboard.invoice.loading-slip-details', $data);

	} 





	public function saveLoadingSlipInvoice(Request $request)

	{

		$data = array();

		// dd($request->all());

		$loading_slip = \App\ProductLoading::where('id',$request->loading_slip)->first();

		if($loading_slip->is_invoice_generated){

			echo "invoice is already generated";exit;

		}else if(is_null($loading_slip->retailer_id)){

			echo "Invalid Loading Slip";exit;

		}else{

			$loading_slip->qr_scan_count = 1;

			$loading_slip->processing_step = 1;

			$loading_slip->is_invoice_generated = 1;

			$loading_slip->invoice_generated_date = date('Y-m-d');

			if($loading_slip->save()){

			// if(1){

				$token =  \App\Token::where('id',$request->token_id)->with('company')->first();



				$product_details = getModelById('Product',$loading_slip->product_id);

				$cgst_percentage = $product_details->cgst;

				$sgst_percentage = $product_details->sgst;

				$rate = $token->rate - $request->freight_discount;

				$rate = $rate * 100 / (100 + $product_details->cgst + $product_details->sgst);

				$amount =  $rate * $loading_slip->quantity;

				$cgst_amount = ($cgst_percentage / 100) * $amount;

				$sgst_amount = ($sgst_percentage / 100) * $amount;

				$total = $amount + $cgst_amount + $sgst_amount + $request->tcs;



				$total_invoice = \App\LoadingSlipInvoice::where('invoice_type_id',$request->invoice_type_id)->count();

				if(is_null($total_invoice) || ($total_invoice == 0)){

					$next_number = 1;

				}else{

					$last_invoice = \App\LoadingSlipInvoice::where('invoice_type_id',$request->invoice_type_id)->orderBy('id','desc')->first();

					$arr = explode('/', $last_invoice->invoice_number);

					$next_number = $arr[2] + 1;

				}

				$loading_slip_invoice = new \App\LoadingSlipInvoice();

				$loading_slip_invoice->loading_slip_id = $request->loading_slip;

				$loading_slip_invoice->invoice_type = getModelById('InvoiceType',$request->invoice_type_id)->invoice_type;

				$loading_slip_invoice->invoice_type_id = getModelById('InvoiceType',$request->invoice_type_id)->id;

				$loading_slip_invoice->invoice_number = getModelById('InvoiceType',$request->invoice_type_id)->invoice_type.$next_number;

				$loading_slip_invoice->eway_bill_number = $request->eway_bill_no;

				$loading_slip_invoice->company_id = $request->company_id;

				$loading_slip_invoice->invoice_date = $this->dateConverter($request->invoice_date);

				$loading_slip_invoice->dealer_id = $token->account_from_id;

				$loading_slip_invoice->retailer_id = $token->retailer_id;

				$loading_slip_invoice->retailer_name = getModelById('Retailer',$token->retailer_id)->name;

				$loading_slip_invoice->delivery_note = $request->invoice_remark;

				$loading_slip_invoice->despatched_through = $request->dispatched_through;

				$loading_slip_invoice->destination = $request->destination;

				$loading_slip_invoice->terms_of_delivery = $request->terms_of_delivery;

				$loading_slip_invoice->product_id = $token->product_id;

				$loading_slip_invoice->product = $loading_slip->product_name;

				$loading_slip_invoice->product_hsn = $product_details->hsn_code;

				$loading_slip_invoice->quantity = $loading_slip->quantity;

				$loading_slip_invoice->unit = $loading_slip->unit_name;

				$loading_slip_invoice->rate = $token->rate;

				$loading_slip_invoice->freight_discount = $request->freight_discount;

				$loading_slip_invoice->cgst = $cgst_amount;

				$loading_slip_invoice->sgst = $sgst_amount;

				$loading_slip_invoice->tcs = $request->tcs;

				$loading_slip_invoice->total = $total;

				$loading_slip_invoice->remaining_amount = $total;

				$loading_slip_invoice->invoice_number;



				if($loading_slip_invoice->save()){

					$message ="Your Invoice has been Generated for Loading slip-".$loading_slip->id.". Invoice Number is ".$loading_slip_invoice->invoice_number;

					// if(!is_null($token->retailer_id)){

					// 	$retailer = \App\Retailer::where('id',$token->retailer_id)->first();

					// 	if(!is_null($retailer->mobile_number) && strlen($retailer->mobile_number) == 10){

					// 		$company = \App\Company::where('id',$request->company_id)->with('company_sms_setting')->first();

					// 		SmsController::sendSms(array($retailer->mobile_number),$message,$company->company_sms_setting->sms_sender_id);

					// 		$sms_report 					= 	new \App\RetailerSmsReport();

					// 		$sms_report->company_id 		=	$request->company_id;

					// 		$sms_report->sms_sender_id 		=	$company->company_sms_setting->sms_sender_id ;

					// 		$sms_report->retailer_id 		=	 $token->retailer_id;

					// 		$sms_report->retailer_name 		=	 $loading_slip_invoice->retailer_name;

					// 		$sms_report->company_name 		=	 $company->name;

					// 		$sms_report->mobile_number 		=	 $retailer->mobile_number;

					// 		$sms_report->message 			=	 $message;

					// 		$sms_report->save();



					// 	}

					// }



					$loading_slip->invoice_number = $loading_slip_invoice->invoice_number;

					$loading_slip->save();





					/*------------Update Party Ledger--------------*/

					$ledger = \App\PartyInvoiceLedger::where('retailer_id',$loading_slip_invoice->retailer_id)->orderBy('id','desc')->first();

					if(!is_null($ledger)){

						$balance = $ledger->balance;

						$ledger = new \App\PartyInvoiceLedger();

						$ledger->retailer_id = $loading_slip_invoice->retailer_id;

						$ledger->particular = "Purchase of ".$loading_slip_invoice->product." from ".getModelById('Dealer',$loading_slip_invoice->dealer_id)->name."(".getModelById('Dealer',$loading_slip_invoice->dealer_id)->address1.")";

						$ledger->type = 'Invoice';

						$ledger->credit = 0;

						$ledger->debit = $loading_slip_invoice->total;

						$ledger->balance = $balance + $loading_slip_invoice->total;

						$ledger->against = $loading_slip_invoice->invoice_number;

						$ledger->save();





					}else{

						$ledger = new \App\PartyInvoiceLedger();

						$ledger->retailer_id = $loading_slip_invoice->retailer_id;

						$ledger->particular = "Purchase of ".$loading_slip_invoice->product." from ".getModelById('Dealer',$loading_slip_invoice->dealer_id)->name."(".getModelById('Dealer',$loading_slip_invoice->dealer_id)->address1.")";

						$ledger->credit = 0;

						$ledger->type = 'Invoice';

						$ledger->debit = $loading_slip_invoice->total;

						$ledger->balance = $loading_slip_invoice->total;

						$ledger->against = $loading_slip_invoice->invoice_number;

						$ledger->save();

					}



					/*------------Update Party Ledger--------------*/





					$data['invoice_data'] = array(

						"company_id"=>$request->company_id,

						"invoice_type_id"=>$request->invoice_type_id,

						"invoice_number"=>$next_number,

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

					array_push($quantity, $loading_slip->quantity);



					$rate  = array();

					array_push($rate, $token->rate);



					$freight_discount  = array();

					array_push($freight_discount, $request->freight_discount);



					$unit  = array();

					array_push($unit, $token->unit_id);



					$data['product_id'] = $products; 

					$data['quantity'] = $quantity; 

					$data['product_rate'] = $rate; 

					$data['freight_discount'] = $freight_discount; 

					$data['product_unit'] = $unit; 

					$data['tcs'] = $loading_slip_invoice->tcs;

					$data['company'] = \App\Company::where('id',$request->company_id)->first();

					return view('dashboard.invoice.print-loading-slip-invoice',$data);

				}else{

					echo "something went wrong"; exit;

				}

				//}else {

				//	echo "something went wrong"; exit;

				//}



			}else{

				echo "something went wrong"; exit;

			}



		}



	}







	public function saveMultipleLoadingSlipInvoice(Request $request)

	{

		$data = array();

		// dd($request->all());

		$loading_slip = \App\ProductLoading::where('id',$request->loading_slip)->first();

		if($loading_slip->is_invoice_generated){

			echo "invoice is already generated";exit;

		}else if(is_null($loading_slip->retailer_id)){

			echo "Invalid Loading Slip";exit;

		}else{

			$loading_slip->qr_scan_count = 1;

			$loading_slip->processing_step = 1;

			$loading_slip->is_invoice_generated = 1;

			$loading_slip->invoice_generated_date = date('Y-m-d');

			if($loading_slip->save()){

				$invoice_numbers = "";

				$i = 0;

				foreach ($request->retailer_id as $key => $value) {

					$token =  \App\Token::where('id',$request->token_id)->with('company')->first();

					$product_details = getModelById('Product',$loading_slip->product_id);

					$cgst_percentage = $product_details->cgst;

					$sgst_percentage = $product_details->sgst;

					$rate = $token->rate - $request->freight_discount[$i];

					$rate = $rate * 100 / (100 + $product_details->cgst + $product_details->sgst);

					$amount =  $rate * $request->quantity[$i];

					$cgst_amount = ($cgst_percentage / 100) * $amount;

					$sgst_amount = ($sgst_percentage / 100) * $amount;

					$total = $amount + $cgst_amount + $sgst_amount + $request->tcs[$i];





					$total_invoice = \App\LoadingSlipInvoice::where('invoice_type_id',$request->invoice_type_id)->count();

					if(is_null($total_invoice) || ($total_invoice == 0)){

						$next_number = 1;

					}else{

						$last_invoice = \App\LoadingSlipInvoice::where('invoice_type_id',$request->invoice_type_id)->orderBy('id','desc')->first();

						$arr = explode('/', $last_invoice->invoice_number);

						$next_number = $arr[2] + 1;

					}

					$loading_slip_invoice = new \App\LoadingSlipInvoice();

					$loading_slip_invoice->loading_slip_id = $request->loading_slip;

					$loading_slip_invoice->invoice_type = getModelById('InvoiceType',$request->invoice_type_id)->invoice_type;

					$loading_slip_invoice->invoice_type_id = getModelById('InvoiceType',$request->invoice_type_id)->id;

					$loading_slip_invoice->invoice_number = getModelById('InvoiceType',$request->invoice_type_id)->invoice_type.$next_number;

					$loading_slip_invoice->eway_bill_number = $request->eway_bill_no[$i];

					$loading_slip_invoice->company_id = $request->company_id;

					$loading_slip_invoice->invoice_date = $this->dateConverter($request->invoice_date);

					$loading_slip_invoice->dealer_id = $token->account_from_id;

					$loading_slip_invoice->retailer_id = $request->retailer_id[$i];

					$loading_slip_invoice->retailer_name = getModelById('Retailer',$request->retailer_id[$i])->name;

					$loading_slip_invoice->delivery_note = $request->invoice_remark;

					$loading_slip_invoice->despatched_through = $request->dispatched_through;

					$loading_slip_invoice->destination = $request->destination;

					$loading_slip_invoice->terms_of_delivery = $request->terms_of_delivery;

					$loading_slip_invoice->product_id = $token->product_id;

					$loading_slip_invoice->product = $loading_slip->product_name;

					$loading_slip_invoice->product_hsn = $product_details->hsn_code;

					$loading_slip_invoice->quantity = $request->quantity[$i];

					$loading_slip_invoice->unit = $loading_slip->unit_name;

					$loading_slip_invoice->rate = $token->rate;

					$loading_slip_invoice->freight_discount = $request->freight_discount[$i];

					$loading_slip_invoice->cgst = $cgst_amount;

					$loading_slip_invoice->sgst = $sgst_amount;

					$loading_slip_invoice->tcs = $request->tcs[$i];;

					$loading_slip_invoice->total = $total;

					$loading_slip_invoice->remaining_amount = $total;

					$loading_slip_invoice->invoice_number;



					if($loading_slip_invoice->save()){



						$invoice_numbers.= $loading_slip_invoice->invoice_number.", ";

						$message ="Your Invoice has been Generated for Loading slip-".$loading_slip->id.". Invoice Number is ".$loading_slip_invoice->invoice_number;

					// if(!is_null($token->retailer_id)){

					// 	$retailer = \App\Retailer::where('id',$token->retailer_id)->first();

					// 	if(!is_null($retailer->mobile_number) && strlen($retailer->mobile_number) == 10){

					// 		$company = \App\Company::where('id',$request->company_id)->with('company_sms_setting')->first();

					// 		SmsController::sendSms(array($retailer->mobile_number),$message,$company->company_sms_setting->sms_sender_id);

					// 		$sms_report 					= 	new \App\RetailerSmsReport();

					// 		$sms_report->company_id 		=	$request->company_id;

					// 		$sms_report->sms_sender_id 		=	$company->company_sms_setting->sms_sender_id ;

					// 		$sms_report->retailer_id 		=	 $token->retailer_id;

					// 		$sms_report->retailer_name 		=	 $loading_slip_invoice->retailer_name;

					// 		$sms_report->company_name 		=	 $company->name;

					// 		$sms_report->mobile_number 		=	 $retailer->mobile_number;

					// 		$sms_report->message 			=	 $message;

					// 		$sms_report->save();



					// 	}

					// }



						$loading_slip->invoice_number = $invoice_numbers;

						$loading_slip->save();



					}else{

						echo "something went wrong"; exit;

					}



					$i++;

				}

				return redirect('/user/loading-slip-invoices')->with("success","invoices $invoice_numbers are generated.");

			}else{

				echo "something went wrong"; exit;

			}



		}



	}





	public function getLoadingSlipInvoicePayment(Request $request){

		$data = array();

		$data['acting_company'] = Session::get('acting_company');

		$data['dealers'] = \App\Dealer::where('is_active',1)->get();

		$data['retailers'] = \App\Retailer::where('is_active',1)->get();

		if ($request->isMethod('post')){

			$validator = \Validator::make($request->all(),

				array(

					'dealer_id' =>'required',

					'retailer_id' => 'required'

				)

			);

			if($validator->fails()){

				return redirect('user/loading-slip-invoice-payment')

				->withErrors($validator)

				->withInput();

			}else{

				$data['dealer_id'] = $request->dealer_id;

				$data['retailer_id'] = $request->retailer_id;

				$data['retailer_advance_balances'] = \DB::table('retailer_advance_balances')->where('retailer_id',$request->retailer_id)->where('dealer_id',$request->dealer_id)->sum('amount');

				$data['retailerInfo'] = \App\Retailer::where('is_active',1)->where('id',$request->retailer_id)->first();

				$data['bank_accounts'] = \App\BankAccount::where('is_active',1)->with('bank')->get();

				$data['invoices'] = \App\LoadingSlipInvoice::where('remaining_amount','>',0)

				->where('dealer_id',$request->dealer_id)->where('retailer_id', $request->retailer_id)

				->get();

			} 

		}

		return view('dashboard.invoice.loading-slip-invoice-payment',$data);

	}

	





	public function getCompanyDiPayment(Request $request){

		$data = array();

		$data['acting_company'] = Session::get('acting_company');

		$data['bank_accounts'] = \App\BankAccount::where('is_active',1)->with('bank')->get();

		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();

		$data['dealers'] = \App\Dealer::where('is_active',1)->get();

		if ($request->isMethod('post')){

			$validator = \Validator::make($request->all(),

				array(

					'product_company_id' =>'required',

					'dealer_id' => 'required'

				)

			);

			if($validator->fails()){

				return redirect('user/comany-di-payment')

				->withErrors($validator)

				->withInput();

			}else{

				$product_company_id = $request->product_company_id;

				$dealer_id = $request->dealer_id;

				$company_dis = \DB::table('company_dis')->where('product_company_id',$product_company_id)->where('dealer_id',$dealer_id)->where('remaining_amount','>',0)->get();

			

				$warehouse_dis = \DB::table('warehouse_dis')->where('dealer_id',$dealer_id)->where('product_company_id',$product_company_id)->where('remaining_amount','>',0)->get();

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



				$data['product_company_id'] = $request->product_company_id;

				$data['dealer_id'] = $request->dealer_id;

				$data['invoices'] = $invoices;

				

			} 

		}

		return view('dashboard.invoice.company-di-payment',$data);

	}



	// Function to generate new invoice

	public function generateManualInvoice()

	{

		$data['acting_company'] = Session::get('acting_company');

		$data['invoice_types'] = \App\InvoiceType::where('is_active',1)->get();

		$data['companies'] = \App\Company::where('is_active',1)->where('for_invoice',1)->get();

		$data['retailers'] = \App\Retailer::where('is_active',1)->get();

		$data['products']	= \App\Product::where('is_active',1)->get();

		$data['units']	= \App\Unit::where('is_active',1)->get();

		return view('dashboard.invoice.generate-manual-invoice', $data);

	}



	// Function to show the invoice details to print in a specified format

	public function printTaxInvoice(Request $request){

		$data = array();

		$validator = \Validator::make($request->all(),

			array(

				"invoice_type_id"=>"required",

				"company_id"=>"required",

				"invoice_date"=>"required",

				"eway_bill_no"=>"required",

				"retailer_id"=>"required",

			)

		);

		if ($validator->fails()) {

			return redirect('user/generate-manual-invoice')

			->withErrors($validator)

			->withInput();

		} else {

			$data['invoice_data'] = array(

				"company_id"=>$request->company_id,

				"invoice_type_id"=>$request->invoice_type_id,

				"invoice_date"=>$this->dateConverter($request->invoice_date),

				"eway_bill_no"=>$request->eway_bill_no,

				"retailer_id"=>$request->retailer_id,

				"invoice_remark"=>$request->invoice_remark,

				"dispatched_through"=>$request->dispatched_through,

				"destination"=>$request->destination,

				"terms_of_delivery"=>$request->terms_of_delivery,

			);

			$data['product_id'] = $request->product_id; 

			$data['product_hsn'] = $request->product_hsn; 

			$data['quantity'] = $request->quantity; 

			$data['product_rate'] = $request->product_rate; 

			$data['product_unit'] = $request->product_unit; 

			$data['product_amount'] = $request->product_amount; 

			$acting_company = Session::get('acting_company');

			$data['company'] = \App\Company::where('id',$request->company_id)->first();

			return view('dashboard.invoice.print-tax-invoice',$data);

			// dd($data);

		}

	}

	// Function to save generated invoice

	public function postGeneratedInvoice(Request $request)

	{



	}



	//

	public function dateConverter($date)

	{

		$temp_date = explode('/', $date);

		$new_date = $temp_date[2]."-".$temp_date[1]."-".$temp_date[0];

		return $new_date;

	}



	public function loadingSlipInvoiceDetails($id)

	{

		$data = array();

		$last_invoice = \App\LoadingSlipInvoice::where('id',$id)->first();

		// dd($last_invoice);

		$product_id = \App\ProductLoading::where('id',$last_invoice->loading_slip_id)->first('product_id');

		$product_details=\App\Product::where('id',$product_id->product_id)->first();

		$cgst_amount = $last_invoice->cgst;

		$sgst_amount = $last_invoice->sgst;

		if(is_null($last_invoice)){

			$next_number = 1;

		}else{

			$next_number = $last_invoice->id + 1;

		}

		$data['invoice_data'] = array(

			"company_id"=>$last_invoice->company_id,

			"invoice_type_id"=>$last_invoice->invoice_type_id,

			"invoice_number"=>$last_invoice->invoice_number,

			"invoice_date"=> $last_invoice->invoice_date,

			"eway_bill_no"=>$last_invoice->eway_bill_no,

			"retailer_id"=>$last_invoice->retailer_id,

			"invoice_remark"=>$last_invoice->invoice_remark,

			"dispatched_through"=>$last_invoice->dispatched_through,

			"destination"=>$last_invoice->destination,

			"terms_of_delivery"=>$last_invoice->terms_of_delivery,

		);

		$products  = array();

		array_push($products, $product_details->id);

		$quantity  = array();

		array_push($quantity, $last_invoice->quantity);

		$rate  = array();

		array_push($rate, $last_invoice->rate);

		$freight_discount  = array();

		array_push($freight_discount, $last_invoice->freight_discount);

		$data['product_id'] = $products;

		$data['quantity'] = $quantity;

		$data['product_rate'] = $rate;

		$data['freight_discount'] = $freight_discount;

		$data['product_unit'] = $last_invoice->unit;

		$data['tcs'] = $last_invoice->tcs;

		$data['company'] = \App\Company::where('id',$last_invoice->company_id)->first();

		return view('dashboard.invoice.loading-slip-invoice-details',$data);

	}





	public function exportAsXml($id){

		$data = array();

		$invoice = \App\LoadingSlipInvoice::where('id',$id)->with('company','retailer','product_details')->first();

		if(!is_null($invoice)){

			$content = \View::make('invoice')->with('invoice', $invoice);

			$xml = $content;



			$response = Response::create($xml, 200);

			$response->header('Content-Type', 'text/xml');

			$response->header('Cache-Control', 'public');

			$response->header('Content-Description', 'File Transfer');

			$response->header('Content-Disposition', 'attachment; filename='.$invoice->invoice_number.'.xml');

			$response->header('Content-Transfer-Encoding', 'binary');

			return $response;

		}else{

			echo "invalid invoice";

		}



	}





	public function partyInvoiceLedger(Request $request)

	{

		$data = array();

		$retailers = array();

		$distinct_retailers = array_unique(\App\PartyInvoiceLedger::pluck('retailer_id')->toArray());

		foreach ($distinct_retailers as $retailer_id) {

			array_push($retailers, array('id'=>$retailer_id,'name'=>getModelById('Retailer',$retailer_id)->name,'address'=>getModelById('Retailer',$retailer_id)->address));

		}

		$data['retailers'] = $retailers;



		if ($request->isMethod('post')){

			$data['ledgers'] = \App\PartyInvoiceLedger::where('retailer_id',$request->retailer_id)->orderBy('id','desc')->get();

			$data['retailer_id'] = $request->retailer_id;

		}

		return view('dashboard.invoice.party-invoice-ledger',$data);

	}



	public function companyInvoiceLedger(Request $request)

	{

		$data = array();

		$dealers = array();

		$product_companies = array();

		$distinct_product_companies = array_unique(\App\CompanyDi::where('total','>',0)->pluck('product_company_id')->toArray());

		foreach ($distinct_product_companies as $product_company_id) {

			array_push($product_companies, array('id'=>$product_company_id,'name'=>getModelById('ProductCompany',$product_company_id)->name));

		}

		



		$data['product_companies'] = $product_companies;

		$data['dealers'] = $dealers;

		$data['allsessions'] = \App\Session::where('is_active',1)->get();

		if ($request->isMethod('post')){

			$validator = \Validator::make($request->all(),

				array(

					'product_company_id' =>'required',

				)

			);



			if($validator->fails()){

				return redirect('user/company-invoice-ledger')

				->withErrors($validator)

				->withInput();

			}else{

				if($request->current_session != ""){

					$years = explode('-', $request->current_session);

					$start = $years[0].'-04-01';

					$end = $years[1].'-03-31';

					

				}else{

					$start = date('Y').'-04-01';

					$end = (date('Y')+1).'-03-31';

				}



				$query = \App\CompanyInvoiceLedger::query();

				

				$query->whereBetween('created_at', [$start, $end]);





				if($request->dealer_id){

					$query->where('dealer_id',$request->dealer_id);

					$data['dealer_id'] = $request->dealer_id;

				}

				$data['ledgers'] = $query->where('product_company_id',$request->product_company_id)->get();

				$data['product_company_id'] = $request->product_company_id;



				$distinct_dealers = array_unique(\App\CompanyDi::where('total','>',0)->where('product_company_id',$request->product_company_id)->pluck('dealer_id')->toArray());

				foreach ($distinct_dealers as $dealer_id) {

					array_push($dealers, array('id'=>$dealer_id,'name'=>getModelById('Dealer',$dealer_id)->name,'address'=>getModelById('Dealer',$dealer_id)->address1));

				}

				$data['company_info'] =  \App\ProductCompany::find($request->product_company_id);

				$data['dealers'] = $dealers;

				$data['current_session'] = $request->current_session;

			}

		}

		return view('dashboard.invoice.company-invoice-ledger',$data);

	}

}



?>