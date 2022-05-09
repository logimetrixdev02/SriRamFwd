<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class UnloadingController extends Controller
{


	public function productUnloadings(Request $request){
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
				return redirect('user/product-unloadings')
				->withErrors($validator)
				->withInput();
			}else{
				$product_unloadings = \App\ProductUnloading::where('master_rake_id',$request->master_rake_id)->with('token')->get();
				$data['product_unloadings'] = $product_unloadings;
				$data['master_rake_id'] = $request->master_rake_id;


			}
		}else{
			$data['product_unloadings'] = array();
		}

		return view('dashboard.unloading.product-unloadings',$data);
	} 

	public function canceledUnloadings(Request $request){
		$data = array();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'warehouse_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/canceled-unloadings')
				->withErrors($validator)
				->withInput();
			}else{
				$product_loading_ids = \App\ProductLoading::where('is_returned',1)->pluck('id');
				if(count($product_loading_ids) > 0){
					$product_unloadings = \App\ProductUnloading::whereIn('product_loading_id',$product_loading_ids)->where('warehouse_id',$request->warehouse_id)->with('product_loading:id,quantity,retailer_id,dealer_id,warehouse_id,loading_slip_type')->get();
					$data['product_unloadings'] = $product_unloadings;
				}else{
					$data['product_unloadings'] = array();
				}
				$data['warehouse_id'] = $request->warehouse_id;
			}
		}else{
			$data['product_unloadings'] = array();
		}

		return view('dashboard.unloading.canceled-unloadings',$data);
	} 

	public function directUnloadings(Request $request){
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
				return redirect('user/direct-unloadings')
				->withErrors($validator)
				->withInput();
			}else{
				$product_unloadings = \App\ProductUnloading::whereNull('master_rake_id')->where('product_loading_id',0)->where('warehouse_id',$request->warehouse_id)->whereDate('created_at', '=', date('Y-m-d',strtotime($request->from_date)))->get();
				$data['product_unloadings'] = $product_unloadings;
				$data['warehouse_id'] = $request->warehouse_id;
				$data['from_date'] = $request->from_date;
			}
		}else{
			$data['product_unloadings'] = \App\ProductUnloading::whereNull('master_rake_id')->where('product_loading_id',0)->get();
			$data['from_date'] = date('m/d/Y');
		}

		return view('dashboard.unloading.direct-unloadings',$data);
	} 


	public function printUnloadingSlip($id){
		$data = array();
		$acting_company = Session::get('acting_company');
		$product_unloading =  \App\ProductUnloading::where('id',$id)->first();
		if(is_null($product_unloading)){
			return redirect('user/product-unloadings')->with('error','Slip Not found');
		}else{
			$data['company'] = \App\Company::where('id',$acting_company)->first();
			$labour_payment =  \App\UnloadingLabourPayment::where('product_unloading_id',$id)->first();
			$data['product_unloading'] = $product_unloading;
			$data['labour_payment'] = $labour_payment;
			return view('dashboard.unloading.print-unloading-slip',$data);
		} 
	}



	public function exportProductUnloadings($master_rake_id){
		$product_loadings = \App\ProductUnloading::where('master_rake_id',$master_rake_id)->with('token')->get();
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
	public function unloadingLabourSlips(Request $request){
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
				$product_loadings = \App\UnloadingLabourPayment::where('master_rake_id',$request->master_rake_id)->with('token')->get();
				$data['labour_payments'] = $product_loadings;
				$data['master_rake_id'] = $request->master_rake_id;
			}
		}else{
			$data['labour_payments'] = array();
		}
		return view('dashboard.unloading.labour-slips',$data);
	}

	public function directUnloadingLabourSlips(Request $request){
		$data = array();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'warehouse_id' =>'required',
				)
			);

			if($validator->fails()){
				return redirect('user/direct-unloading-labour-slips')
				->withErrors($validator)
				->withInput();
			}else{
				$labour_payments = \App\UnloadingLabourPayment::whereNull('master_rake_id')->where('warehouse_id',$request->warehouse_id)->get();
				$data['labour_payments'] = $labour_payments;
				$data['warehouse_id'] = $request->warehouse_id;
			}
		}else{
			$data['labour_payments'] = array();
		}
		return view('dashboard.unloading.direct-unloading-labour-slips',$data);
	}


	public function unloadingLabourPayment()
	{
		return view("dashboard.unloading.pay-labour");
	}

	public function payUnloadingLabour(Request $request){
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
			$labour_payment = \App\UnloadingLabourPayment::where('id',$request->labour_slip_id)->first();
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

	public function getDirectUnloadingFreightPayment()
	{
		return view("dashboard.unloading.direct-freight-payment");
	}


	public function postDirectUnloadingFreightPayment(Request $request)
	{

		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'product_unloading_id' =>'required',
				'freight' 				=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$product_unloading_loading = \App\ProductUnloading::where('id',$request->product_unloading_id)->first();
			if(!is_null($product_unloading_loading)){
				if($product_unloading_loading->is_labour_paid){
					$response['flag'] 			= false;
					$response['message'] = "Payment Already done for this slip";
				}else{
					$product_unloading_loading->is_freight_paid = 1;
					$product_unloading_loading->toll_tax = $request->toll_tax_amount;
					$product_unloading_loading->freight_amount_paid = ($product_unloading_loading->freight * $product_unloading_loading->quantity) + $request->toll_tax_amount;
					$product_unloading_loading->freight_payment_date = date('Y-m-d H:i:s');
					$product_unloading_loading->freight_paid_by = \Auth::user()->id;

					if($product_unloading_loading->save()){
						$response['flag'] 			= true;
						$response['message'] 		= "Payment Details Saved Successfully";
					}else{
						$response['flag'] 			= false;
						$response['message'] 		= "Failed To Save";
					}
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Labour Slip";
			}
		}
		return response()->json($response);

		
	}




	public function getUnloadingFreightPayment()
	{
		return view("dashboard.unloading.cancled-freight-payment");
	}


	public function postUnloadingFreightPayment(Request $request)
	{

		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'product_unloading_id' =>'required',
				'freight' 				=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$product_unloading_loading = \App\ProductUnloading::where('id',$request->product_unloading_id)->first();
			if(!is_null($product_unloading_loading)){
				if($product_unloading_loading->is_labour_paid){
					$response['flag'] 			= false;
					$response['message'] = "Payment Already done for this slip";
				}else{
					$product_unloading_loading->is_freight_paid = 1;
					$product_unloading_loading->toll_tax = $request->toll_tax_amount;
					$product_unloading_loading->freight_amount_paid = ($product_unloading_loading->freight * $product_unloading_loading->quantity) + $request->toll_tax_amount;
					$product_unloading_loading->freight_payment_date = date('Y-m-d H:i:s');
					$product_unloading_loading->freight_paid_by = \Auth::user()->id;

					if($product_unloading_loading->save()){
						$response['flag'] 			= true;
						$response['message'] 		= "Payment Details Saved Successfully";
					}else{
						$response['flag'] 			= false;
						$response['message'] 		= "Failed To Save";
					}
				}
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Labour Slip";
			}
		}
		return response()->json($response);

		
	}

}
