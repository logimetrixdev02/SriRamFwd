<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;


class AjaxController extends Controller
{

	public function masterRakeDetails($id){
		$response = array();
		$acting_company = Session::get('acting_company');
		$master_rake = \App\MasterRake::where('id',$id)->where('is_active',1)->with('product_company','master_rake_products','rake_allotments')->first();
		if(is_null($master_rake)){
			$response['flag'] = false;
			$response['message'] = "Master Rake Not Found";
		}else{
			$response['flag'] = true;
			$response['master_rake'] = $master_rake;
		}
		return response()->json($response);
	} 

	public function productCompanyDetails($id){
		$response = array();
		$product_company = \App\ProductCompany::where('id',$id)->first();
		if(is_null($product_company)){
			$response['flag'] = false;
			$response['message'] = "Product Company Not Found";
		}else{
			$response['flag'] = true;
			$response['product_company'] = $product_company;
		}
		return response()->json($response);
	} 

	public function companyStockDetails($product_company,$warehouse,$product){
		$response = array();
		$stock = \App\Inventory::where('warehouse_id',$warehouse)->where('product_company_id',$product_company)->where('product_id',$product)->sum('quantity');
		$product_stock = \App\Inventory::where('product_company_id',$product_company)->where('product_brand_id',$product_company)->where('product_id',$product)->sum('quantity');
		if(is_null($stock)){
			$response['flag'] = false;
			$response['message'] = "Not Stock Found";
		}else{
			$response['flag'] = true;
			$response['stock'] = $stock;
			$response['product_stock'] = $product_stock;
		}
		return response()->json($response);
	} 

	public function dealerStockDetails($dealer,$warehouse,$product_brand,$product){
		$response = array();
		$stock = \App\Inventory::where('warehouse_id',$warehouse)->where('dealer_id',$dealer)->where('product_id',$product)->sum('quantity');
		$product_stock = \App\Inventory::where('product_brand_id',$product_brand)->where('dealer_id',$dealer)->where('product_id',$product)->sum('quantity');
		if(is_null($stock)){
			$response['flag'] = false;
			$response['message'] = "Not Stock Found";

		}else{
			$response['flag'] = true;
			$response['stock'] = $stock;
			$response['product_stock'] = $product_stock;
		}
		return response()->json($response);
	}  
	public function warehouseParties($warehouse_id){
		$response = array();
		$parties = \App\Dealer::where('is_active',1)->get();
		$product_companies = \App\Inventory::select('product_brand_id')->distinct()->where('warehouse_id',$warehouse_id)->with('product_brand')->get();

		if(count($parties) > 0){
			$response['flag'] = true;
			$response['parties'] = $parties;
			$response['product_companies'] = $product_companies;
		}else{
			$response['flag'] = false;
			$response['message'] = "This warehouse Don't have any Parties's products";
		}
		return response()->json($response);
	}
	public function getPartyProducts($warehouse_id,$product_company_id,$dealer_id){
		$response = array();
		// $products = \App\Inventory::select('product_id')->distinct()->where('warehouse_id',$warehouse_id)->where('product_brand_id',$product_company_id)->with('product')->get();
		$products = \App\Inventory::select('product_id')->distinct()->where('product_brand_id',$product_company_id)->with('product')->get();
		if(count($products) > 0){
			$response['flag'] = true;
			$response['products'] = $products;
		}else{
			$response['flag'] = false;
			$response['message'] = "This Party don't have any products";
		}
		return response()->json($response);
	}
	public function warehouseInventoryProductDetails($warehouse_id,$product_company_id,$product_id){
		$response = array();
		// $product = \App\Inventory::where('warehouse_id',$warehouse_id)->where('product_brand_id',$product_company_id)->where('product_id',$product_id)->first();
		$product = \App\Inventory::where('product_brand_id',$product_company_id)->where('product_id',$product_id)->first();
		if(is_null($product)){
			$response['flag'] = false;
			$response['message'] = "Inventory Not Found";
		}else{
			$response['flag'] = true;
			$response['product_details'] = $product;
		}
		return response()->json($response);
	}
	public function tokenDetails($id){
		$response = array();
		$token = \App\Token::where('id',$id)->with('dealer','master_rake','from_warehouse','product','product_company','unit','warehouse','product_loadings')->first();
		if(is_null($token)){
			$response['flag'] = false;
			$response['message'] = "Token Not Found";
		}else{
			$remaining_quantity = \App\ProductLoading::where('token_id',$id)->sum('quantity');
			if(!$remaining_quantity){
				$remaining_quantity = $token->quantity;
			}else{
				$remaining_quantity = $token->quantity - $remaining_quantity;
			}
			$response['flag'] = true;
			$response['token'] = $token;
			$response['remaining_quantity'] = $remaining_quantity;
		}
		return response()->json($response);
	}
	public function loadingSlipDetails($loading_slip_id){
		$response = array();
		$decoded_value = base64_decode($loading_slip_id);
		$loadingSlipDetailsArray = explode(',', $decoded_value);
		if ($loadingSlipDetailsArray[0] == "loading_slip") {
			$product_loading = \App\ProductLoading::where('id',$loadingSlipDetailsArray[1])->with('token')->first();
			if(is_null($product_loading)){
				$response['flag'] = false;
				$response['message'] = "Loading Slip Not Found";
			}else{
				// if ($product_loading->loading_slip_type == 1 && $product_loading['qr_scan_count'] == '0') {
				// 	$response['flag'] = false;
				// 	$response['message'] = "Invoice Not Generated yet.";
				// }else 

				if ($product_loading->loading_slip_type == 1 && $product_loading['is_approved'] == '0') {
					$response['flag'] = false;
					$response['message'] = "Product is Not Unloaded yet.";
				}elseif ($product_loading->loading_slip_type == 1 && $product_loading['qr_scan_count'] == '2') {
					$response['flag'] = false;
					$response['message'] = "Freight has already been paid.";
				}elseif ($product_loading->is_freight_paid == 1) {
					$response['flag'] = false;
					$response['message'] = "Freight has already been paid.";
				}
				// else if ($product_loading['transporter_id'] == 22) {
				// 	$response['flag'] = false;
				// 	$response['message'] = "Self transpoter is used in this loading";
				// }
				else if (is_null($product_loading['freight'])) {
					$response['flag'] = false;
					$response['message'] = "Freight is not mentioned in this slip";
				} else {
					$response['flag'] = true;
					$response['product_loading'] = $product_loading;
				}
			}
		} else {
			$response['flag'] = false;
			$response['message'] = "Invalid QR Code.";
		}
		return response()->json($response);
	} 



	public function warehouseTransferLoadingDetails($loading_id){
		$response = array();
		$decoded_value = base64_decode($loading_id);
		$loadingSlipDetailsArray = explode(',', $decoded_value);
		if ($loadingSlipDetailsArray[0] == "warehouse_transfer_loading_slip" || $loadingSlipDetailsArray[0] == "warehouse_transfer_loading_labour_slip") {
			$warehouse_loading = \App\WarehouseTransferLoading::where('id',$loadingSlipDetailsArray[1])->with('product:id,name','unit:id,unit','from_warehouse:id,name','to_warehouse:id,name','transporter:id,name','product_brand:id,name,brand_name')->first();
			if(is_null($warehouse_loading)){
				$response['flag'] = false;
				$response['message'] = "Loading Slip Not Found";
			}else{
				$response['flag'] = true;
				$response['warehouse_loading'] = $warehouse_loading;
			}
		} else {
			$response['flag'] = false;
			$response['message'] = "Invalid QR Code.";
		}
		return response()->json($response);
	}

	public function warehouseTransferUnloadingDetails($unloading_id){
		$response = array();
		$decoded_value = base64_decode($unloading_id);
		$loadingSlipDetailsArray = explode(',', $decoded_value);
		if ($loadingSlipDetailsArray[0] == "warehouse_transfer_unloading_labour_slip") {
			$warehouse_unloading = \App\WarehouseTransferUnloading::where('id',$loadingSlipDetailsArray[1])->with('product:id,name','unit:id,unit','from_warehouse:id,name','to_warehouse:id,name','product_brand:id,name,brand_name')->first();
			if(is_null($warehouse_unloading)){
				$response['flag'] = false;
				$response['message'] = "Loading Slip Not Found";
			}else{
				$response['flag'] = true;
				$response['warehouse_unloading'] = $warehouse_unloading;
			}
		} else {
			$response['flag'] = false;
			$response['message'] = "Invalid QR Code.";
		}
		return response()->json($response);
	}

	public function dealerRakeAllotmentDetails($master_rake_id,$dealer_id){
		$acting_company = Session::get('acting_company');
		$response = array();
		$allotments = \App\RakeProductAllotment::where('master_rake_id',$master_rake_id)->where('dealer_id',$dealer_id)->get();
		if(is_null($allotments)){
			$response['flag'] = false;
			$response['message'] = "Selected Dealer Don't have Allotment For selected Rake";
		}else{
			$product_options = '<option value="">Select Product</option>';
			foreach ($allotments as $allotment) {
				$product_options .= '<option value="'.$allotment->product_id.'">'.getModelById('Product',$allotment->product_id)->name.'</option>';
			}
			$response['flag'] = true;
			$response['allotment'] = $allotment;
			$response['product_options'] = $product_options;
		}

		return response()->json($response);
	}
	public function allotedProductDetails($master_rake_id,$dealer_id,$product_id){
		$acting_company = Session::get('acting_company');
		$response = array();
		$allotment = \App\RakeProductAllotment::where('master_rake_id',$master_rake_id)->where('dealer_id',$dealer_id)->where('product_id',$product_id)->first();
		if(is_null($allotment)){
			$response['flag'] = false;
			$response['message'] = "Allotment Not Found";
		}else{
			$response['flag'] = true;
			$response['product_details'] = $allotment;
		}
		return response()->json($response);
	}
	public function labourSlipDetails($labour_slip_id){
		$response = array();
		$decoded_value = base64_decode($labour_slip_id);
		$labourSlipDetailsArray = explode(',', $decoded_value);
		if ($labourSlipDetailsArray[0] == "labour_slip") {

			$labour_payment = \App\LabourPayments::where('id',$labourSlipDetailsArray[1])->first();
			if(!is_null($labour_payment)){
				$response['flag'] 			= true;
				$response['labour_payment'] = $labour_payment;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Labour Payment Id";
			}
		} else{
			$response['flag'] 			= false;
			$response['message'] 		= "Invalid QR Code";
		}

		
		return response()->json($response);
	}
	public function unloadingSlipDetails($unloading_slip_id){
		$response = array();
		$decoded_value = base64_decode($unloading_slip_id);

		$slipDetailsArray = explode(',', $decoded_value);
		if ($slipDetailsArray[0] == "unloading_slip") {

			$product_unloading = \App\ProductUnloading::where('id',$slipDetailsArray[1])->first();
			if(!is_null($product_unloading)){
				$response['flag'] 			= true;
				$response['product_unloading'] = $product_unloading;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Unloading Slip";
			}
		} else{
			$response['flag'] 			= false;
			$response['message'] 		= "Invalid QR Code";
		}

		
		return response()->json($response);
	}

	public function unloadingLabourSlipDetails($labour_slip_id){
		$response = array();
		$decoded_value = base64_decode($labour_slip_id);
		$labourSlipDetailsArray = explode(',', $decoded_value);
		if ($labourSlipDetailsArray[0] == "unloading_labour_slip") {

			$labour_payment = \App\UnloadingLabourPayment::where('id',$labourSlipDetailsArray[1])->first();
			if(!is_null($labour_payment)){
				$response['flag'] 			= true;
				$response['labour_payment'] = $labour_payment;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Labour Payment Id";
			}
		} else{
			$response['flag'] 			= false;
			$response['message'] 		= "Invalid QR Code";
		}

		
		return response()->json($response);
	}
	
	public function directLabourSlipDetails($labour_slip_id){
		$response = array();
		$decoded_value = base64_decode($labour_slip_id);
		$labourSlipDetailsArray = explode(',', $decoded_value);
		if ($labourSlipDetailsArray[0] == "direct_labour_slip") {
			$labour_payment = \App\DirectLabourPayment::where('id',$labourSlipDetailsArray[1])->first();
			if(!is_null($labour_payment)){
				$response['flag'] 			= true;
				$response['labour_payment'] = $labour_payment;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Labour Payment Id";
			}
		} else{
			$response['flag'] 			= false;
			$response['message'] 		= "Invalid QR Code";
		}

		
		return response()->json($response);
	}

	public function standardizationSlipDetails($labour_slip_id){
		$response = array();
		$decoded_value = base64_decode($labour_slip_id);
		$labourSlipDetailsArray = explode(',', $decoded_value);
		if ($labourSlipDetailsArray[0] == "standardization_slip") {
			$labour_payment = \App\Standardization::where('id',$labourSlipDetailsArray[1])->first();
			if(!is_null($labour_payment)){
				$response['flag'] 			= true;
				$response['labour_payment'] = $labour_payment;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Labour Payment Id";
			}
		} else{
			$response['flag'] 			= false;
			$response['message'] 		= "Invalid QR Code";
		}

		
		return response()->json($response);
	}


	public function returnSlipDetails($labour_slip_id){
		$response = array();
		$decoded_value = base64_decode($labour_slip_id);
		$labourSlipDetailsArray = explode(',', $decoded_value);
		if ($labourSlipDetailsArray[0] == "return_slip") {
			$return_product = \App\ReturnedProduct::where('id',$labourSlipDetailsArray[1])->with('product:id,name','unit:id,unit','warehouse:id,name','transporter:id,name','product_brand:id,name,brand_name')->first();
			if(!is_null($return_product)){
				$response['flag'] 			= true;
				$response['return_product'] = $return_product;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Labour Payment Id";
			}
		} else{
			$response['flag'] 			= false;
			$response['message'] 		= "Invalid QR Code";
		}

		
		return response()->json($response);
	}


	public function wagonUnloadingSlipDetails($unloading_slip_id){
		$response = array();
		$decoded_value = base64_decode($unloading_slip_id);
		$unloadingSlipDetailsArray = explode(',', $decoded_value);
		if ($unloadingSlipDetailsArray[0] == "wagon_labour_slip") {
			$unloading_slip = \App\WagonUnloading::where('id',$unloadingSlipDetailsArray[1])->first();
			if(!is_null($unloading_slip)){
				$response['flag'] 			= true;
				$response['unloading_slip'] = $unloading_slip;
			}else{
				$response['flag'] 			= false;
				$response['message'] 		= "Invalid Labour Payment Id";
			}
		} else{
			$response['flag'] 			= false;
			$response['message'] 		= "Invalid QR Code";
		}

		
		return response()->json($response);
	}
	public function hsnCodeByProduct($product_id)
	{
		$response = array();
		$product_hsn_code = \App\Product::where('id',$product_id)->first();
		if(is_null($product_hsn_code)){
			$response['flag'] = false;
			$response['message'] = "HSN Code Not Found!";
		}else{
			$response['flag'] = true;
			$response['product_hsn_code'] = $product_hsn_code;
		}
		return response()->json($response);
	}
	public function companyInvoiceDetails($invoice_id)
	{
		$response = array();
		$invoice = \App\ComppanyDi::where('id',$invoice_id)->with('master_rake','dealer','product_company')->first();
		if(is_null($invoice)){
			$response['flag'] = false;
			$response['message'] = "Invoice Not Found!";
		}else{
			$response['flag'] = true;
			$response['invoice'] = $invoice;
		}
		return response()->json($response);
	}
}
