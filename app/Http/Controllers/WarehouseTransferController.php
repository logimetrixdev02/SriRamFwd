<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class WarehouseTransferController extends Controller
{


	public function warehouse_transfer_loadings(Request $request)
	{
		$data= array();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		if($request->isMethod('post')){
			$query = \App\WarehouseTransferLoading::query();
			if($request->from_warehouse_id){
				$query->where('from_warehouse_id',$request->from_warehouse_id);
				$data['from_warehouse_id']  = $request->from_warehouse_id;
			}
			if($request->to_warehouse_id){
				$query->where('to_warehouse_id',$request->to_warehouse_id);
				$data['to_warehouse_id']  = $request->to_warehouse_id;
			}
			if($request->product_brand_id){
				$query->where('product_brand_id',$request->product_brand_id);
				$data['product_brand_id']  = $request->product_brand_id;
			}
			if($request->product_id){
				$query->where('product_id',$request->product_id);
				$data['product_id']  = $request->product_id;
			}
			$data['warehouse_transfer_loadings'] = $query->with('product:id,name','unit:id,unit','from_warehouse:id,name','to_warehouse:id,name','transporter:id,name','product_brand:id,name,brand_name')->get();

		}else{
			$data['warehouse_transfer_loadings'] = array();
		}
		return view('dashboard.warehouse_transfers.warehouse_transfer_loadings',$data);
	}




	public function printWarehouseTransferLoadingSlip ($id){
		$data = array();
		$warehouse_transfer_loading =  \App\WarehouseTransferLoading::where('id',$id)->first();
		if(is_null($warehouse_transfer_loading)){
			return redirect('user/warehouse-transfer-loadings')->with('error','Slip Not found');
		}else{
			$data['warehouse_transfer_loading'] = $warehouse_transfer_loading;
			return view('dashboard.warehouse_transfers.print-warehouse-transfers-loading-slip',$data);
		} 
	}


	public function warehouse_transfer_unloadings(Request $request)
	{
		$data= array();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		if($request->isMethod('post')){
			$query = \App\WarehouseTransferUnloading::query();
			if($request->from_warehouse_id){
				$query->where('from_warehouse_id',$request->from_warehouse_id);
				$data['from_warehouse_id']  = $request->from_warehouse_id;
			}
			if($request->to_warehouse_id){
				$query->where('to_warehouse_id',$request->to_warehouse_id);
				$data['to_warehouse_id']  = $request->to_warehouse_id;
			}
			if($request->product_brand_id){
				$query->where('product_brand_id',$request->product_brand_id);
				$data['product_brand_id']  = $request->product_brand_id;
			}
			if($request->product_id){
				$query->where('product_id',$request->product_id);
				$data['product_id']  = $request->product_id;
			}
			$data['warehouse_transfer_unloadings'] = $query->with('product:id,name','unit:id,unit','from_warehouse:id,name','to_warehouse:id,name','transporter:id,name','product_brand:id,name,brand_name')->get();

		}else{
			$data['warehouse_transfer_unloadings'] = array();
		}
		return view('dashboard.warehouse_transfers.warehouse_transfer_unloadings',$data);
	}



	public function printWarehouseTransferUnloadingSlip ($id){
		$data = array();
		$warehouse_transfer_unloading =  \App\WarehouseTransferUnloading::where('id',$id)->first();
		if(is_null($warehouse_transfer_unloading)){
			return redirect('user/warehouse-transfer-unloadings')->with('error','Slip Not found');
		}else{
			$data['warehouse_transfer_unloading'] = $warehouse_transfer_unloading;
			return view('dashboard.warehouse_transfers.print-warehouse-transfers-unloading-slip',$data);
		} 
	}




	public function getLoadingLabourPayment()
	{
		return view("dashboard.warehouse_transfers.pay-loading-labour");
	}

	public function postLoadingLabourPayment(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'warehouse_transfer_loading_id' =>'required',
				'amount_to_pay' 				=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$warehouse_transfer_loading = \App\WarehouseTransferLoading::where('id',$request->warehouse_transfer_loading_id)->first();
			if(!is_null($warehouse_transfer_loading)){
				if($warehouse_transfer_loading->is_labour_paid){
					$response['flag'] 			= false;
					$response['message'] = "Payment Already done for this slip";
				}else{
					$warehouse_transfer_loading->is_labour_paid = 1;
					$warehouse_transfer_loading->labour_paid_by = \Auth::id();
					$warehouse_transfer_loading->labour_paid_amount = $warehouse_transfer_loading->labour_paid_amount + $request->amount_to_pay;
					$warehouse_transfer_loading->labour_payment_date = date('Y-m-d H:i:s');
					if($warehouse_transfer_loading->save()){
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


	public function getUnloadingLabourPayment()
	{
		return view("dashboard.warehouse_transfers.pay-unloading-labour");
	}

	public function postUnloadingLabourPayment(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'warehouse_transfer_unloading_id' =>'required',
				'amount_to_pay' 				=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$warehouse_transfer_unloading = \App\WarehouseTransferUnloading::where('id',$request->warehouse_transfer_unloading_id)->first();
			if(!is_null($warehouse_transfer_unloading)){
				if($warehouse_transfer_unloading->is_labour_paid){
					$response['flag'] 			= false;
					$response['message'] = "Payment Already done for this slip";
				}else{
					$warehouse_transfer_unloading->is_labour_paid = 1;
					$warehouse_transfer_unloading->labour_paid_by = \Auth::id();
					$warehouse_transfer_unloading->labour_paid_amount = $warehouse_transfer_unloading->labour_paid_amount + $request->amount_to_pay;
					$warehouse_transfer_unloading->labour_payment_date = date('Y-m-d H:i:s');
					if($warehouse_transfer_unloading->save()){
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



	public function getFreightPayment()
	{
		return view("dashboard.warehouse_transfers.freight-payment");
	}


	public function postFreightPayment(Request $request)
	{

		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'warehouse_transfer_loading_id' =>'required',
				'freight' 				=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$warehouse_transfer_loading = \App\WarehouseTransferLoading::where('id',$request->warehouse_transfer_loading_id)->first();
			if(!is_null($warehouse_transfer_loading)){
				if($warehouse_transfer_loading->is_freight_paid){
					$response['flag'] 			= false;
					$response['message'] = "Payment Already done for this slip";
				}else{
					$warehouse_transfer_loading->is_freight_paid = 1;
					$warehouse_transfer_loading->toll_tax = $request->toll_tax_amount;
					$warehouse_transfer_loading->freight_amount_paid = ($warehouse_transfer_loading->freight * $warehouse_transfer_loading->quantity) + $request->toll_tax_amount;
					$warehouse_transfer_loading->freight_payment_date = date('Y-m-d H:i:s');
					$warehouse_transfer_loading->freight_paid_by = \Auth::user()->id;

					if($warehouse_transfer_loading->save()){
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
