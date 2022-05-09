<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class ReturnProductController extends Controller
{

	public function index(Request $request)
	{
		$data= array();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		if($request->isMethod('post')){
			$query = \App\ReturnedProduct::query();
			if($request->receiving_warehouse_id){
				$query->where('warehouse_id',$request->receiving_warehouse_id);
				$data['receiving_warehouse_id']  = $request->receiving_warehouse_id;
			}
			if($request->dealer_id){
				$query->where('dealer_id',$request->dealer_id);
				$data['dealer_id']  = $request->dealer_id;
			}
			if($request->product_brand_id){
				$query->where('product_brand_id',$request->product_brand_id);
				$data['product_brand_id']  = $request->product_brand_id;
			}
			if($request->product_id){
				$query->where('product_id',$request->product_id);
				$data['product_id']  = $request->product_id;
			}
			$data['returned_products'] = $query->with('product:id,name','unit:id,unit','warehouse:id,name','transporter:id,name','product_brand:id,name,brand_name')->get();

		}else{
			$data['returned_products'] = array();
		}
		return view('dashboard.returned_products.index',$data);
	}




	public function printReturnedProductSlip ($id){
		$data = array();
		$returned_product =  \App\ReturnedProduct::where('id',$id)->first();
		if(is_null($returned_product)){
			return redirect('user/returned-products')->with('error','Slip Not found');
		}else{
			$data['returned_product'] = $returned_product;
			return view('dashboard.returned_products.print-returned-product-slip',$data);
		} 
	}




	public function getLabourPayment()
	{
		return view("dashboard.returned_products.pay-labour");
	}

	public function postLabourPayment(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'return_id' =>'required',
				'amount_to_pay' 				=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$return_product = \App\ReturnedProduct::where('id',$request->return_id)->first();
			if(!is_null($return_product)){
				if($return_product->is_labour_paid){
					$response['flag'] 			= false;
					$response['message'] = "Payment Already done for this slip";
				}else{
					$return_product->is_labour_paid = 1;
					$return_product->labour_paid_by = \Auth::id();
					$return_product->paid_labour_amount = $return_product->paid_labour_amount + $request->amount_to_pay;
					$return_product->labour_payment_date = date('Y-m-d H:i:s');
					if($return_product->save()){
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
		return view("dashboard.returned_products.freight-payment");
	}


	public function postFreightPayment(Request $request)
	{

		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'return_id' =>'required',
				'freight' 				=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$return_product = \App\ReturnedProduct::where('id',$request->return_id)->first();
			if(!is_null($return_product)){
				if($return_product->is_freight_paid){
					$response['flag'] 			= false;
					$response['message'] = "Payment Already done for this slip";
				}else{
					$return_product->is_freight_paid = 1;
					$return_product->toll_tax = $request->toll_tax_amount;
					$return_product->freight_paid_amount = ($return_product->freight * $return_product->quantity) + $request->toll_tax_amount;
					$return_product->freight_payment_date = date('Y-m-d H:i:s');
					$return_product->freight_paid_by = \Auth::user()->id;

					if($return_product->save()){
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
