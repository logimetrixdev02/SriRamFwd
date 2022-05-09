<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackendController extends Controller
{

	public function index(Request $request){
		return view('backend.index');
	}



	public function updateRakeToken(Request $request){
		$data = array();
		$data['tokens'] = \App\Token::where('token_type',1)->get();
		$data['companies'] = \App\Company::where('is_active',1)->get();
		$data['units'] = \App\Unit::where('is_active',1)->get();
		if($request->token){
			$data['current_token'] = \App\Token::where('id',$request->token)->first();
		}
		if ($request->isMethod('post')){
			$token = \App\Token::where('token_type',1)->where('id',$request->token_id)->first();
			if(!is_null($token)){

				/*------------------Log Error--------------*/
				$message = "Token# $token->unique_id . ";
				if($token->company_id != $request->company_id){
					$message .= "Company Changed from ".getModelById('Company',$token->company_id)->token_abbreviation." to ".getModelById('Company',$request->company_id)->token_abbreviation.". ";
				}
				if($token->rate != $request->rate){
					$message .= "Rate Changed from $token->rate to $request->rate. ";
				}
				if($token->delivery_payment_mode != $request->delivery_payment_mode){
					$message .= "Freight Paid Mode Changed from $token->delivery_payment_mode to $request->delivery_payment_mode. ";
				}
				if($message != ""){
					LogController::userErrorLog($token->user_id,'Rake Token',$message);
				}
				/*------------------Log Error--------------*/


				$token->company_id = $request->company_id;
				$token_year_month = date('y/m',strtotime($token->date_of_generation));
				$token_company = getModelById('Company',$request->company_id);
				$token->unique_id = $token_company->token_abbreviation."/".$token_year_month."/".$token->id;
				if($request->rate){
					$token->rate = $request->rate;
				}
				$token->delivery_payment_mode = $request->delivery_payment_mode;

				if($token->save()){
					return redirect('/backend/update-rake-token?token='.$request->token_id)->with('success','Saved Successfully');
				}else{
					return redirect('/backend/update-rake-token')->with('error','Failed to save');
				}
			}else{
				return redirect('/backend/update-rake-token')->with('error','token_not found');
			}
		}
		return view('backend.update-rake-token',$data);
	} 

	public function updateWarehouseToken(Request $request){
		$data = array();
		$data['tokens'] = \App\Token::where('token_type',2)->get();
		$data['companies'] = \App\Company::where('is_active',1)->get();
		$data['units'] = \App\Unit::where('is_active',1)->get();
		if($request->token){
			$data['current_token'] = \App\Token::where('id',$request->token)->first();
		}
		if ($request->isMethod('post')){
			$token = \App\Token::where('token_type',2)->where('id',$request->token_id)->first();
			if(!is_null($token)){

				/*------------------Log Error--------------*/
				$message = "Token# $token->unique_id . ";
				if($token->company_id != $request->company_id){
					$message .= "Company Changed from ".getModelById('Company',$token->company_id)->token_abbreviation." to ".getModelById('Company',$request->company_id)->token_abbreviation.". ";
				}
				if($token->rate != $request->rate){
					$message .= "Rate Changed from $token->rate to $request->rate. ";
				}
				if($token->delivery_payment_mode != $request->delivery_payment_mode){
					$message .= "Freight Paid Mode Changed from $token->delivery_payment_mode to $request->delivery_payment_mode. ";
				}
				if($message != ""){
					LogController::userErrorLog($token->user_id,'Warehouse Token',$message);
				}
				/*------------------Log Error--------------*/

				$token->company_id = $request->company_id;
				$token_year_month = date('y/m',strtotime($token->date_of_generation));
				$token_company = getModelById('Company',$request->company_id);
				$token->unique_id = $token_company->token_abbreviation."/".$token_year_month."/".$token->id;
				if($request->rate){
					$token->rate = $request->rate;
				}
				$token->delivery_payment_mode = $request->delivery_payment_mode;

				if($token->save()){
					return redirect('/backend/update-warehouse-token?token='.$request->token_id)->with('success','Saved Successfully');
				}else{
					return redirect('/backend/update-warehouse-token')->with('error','Failed to save');
				}
			}else{
				return redirect('/backend/update-warehouse-token')->with('error','token_not found');
			}
		}
		return view('backend.update-warehouse-token',$data);
	} 


	public function updateLoading(Request $request){
		$data = array();
		$data['loadings'] = \App\ProductLoading::all();
		if($request->loading){
			$data['current_loading'] = \App\ProductLoading::where('id',$request->loading)->first();
		}
		if ($request->isMethod('post')){
			$loading = \App\ProductLoading::where('id',$request->loading_id)->first();
			if(!is_null($loading)){

				/*------------------Log Error--------------*/
				$message = "Loading# $loading->id . ";
				if($loading->wagon_number != $request->wagon_number){
					$message .= "wagon number Changed from $loading->wagon_number to $request->wagon_number. ";
				}
				if($loading->freight != $request->freight){
					$message .= "Freight Changed from $loading->freight to $request->freight . ";
				}
				if($loading->truck_number != $request->truck_number){
					$message .= "Truck number Changed from $loading->truck_number to $request->truck_number. ";
				}
				if($loading->driver_number != $request->driver_number){
					$message .= "driver number Changed from $loading->driver_number to $request->driver_number. ";
				}
				if($message != ""){
					LogController::userErrorLog($loading->user_id,'Product Loading',$message);
				}
				/*------------------Log Error--------------*/


				if($request->wagon_number){
					$loading->wagon_number = $request->wagon_number;
				}
				$loading->truck_number = $request->truck_number;
				$loading->driver_number = $request->driver_number;
				$loading->freight = $request->freight;

				if($loading->save()){
					$labour_payment = \App\LabourPayments::where('product_loading_id',$request->loading_id)->first();
					$labour_payment->truck_number = $request->truck_number;
					$labour_payment->save();

					

					return redirect('/backend/update-loadings?loading='.$request->loading_id)->with('success',"Saved Successfully.");
				}else{
					return redirect('/backend/update-loadings')->with('error','Failed to save');
				}
			}else{
				return redirect('/backend/update-loadings')->with('error','not found');
			}
		}
		return view('backend.update-loading',$data);
	} 



	public function updateLabourPayment(Request $request){
		$data = array();
		$data['labour_payments'] = \App\LabourPayments::where('is_paid',0)->get();
		if($request->labour_payment){
			$data['current_labour_payment'] = \App\LabourPayments::where('id',$request->labour_payment)->first();
		}
		if ($request->isMethod('post')){
			$labour_payment = \App\LabourPayments::where('is_paid',0)->where('id',$request->labour_payment_id)->first();
			if(!is_null($labour_payment)){

				/*------------------Log Error--------------*/
				$message = "Loading Labour # $labour_payment->id . ";
				if($labour_payment->labour_name != $request->labour_name){
					$message .= "Labour Name Changed from $labour_payment->labour_name to $request->labour_name. ";
				}
				if($labour_payment->rate != $request->rate){
					$message .= "Labour Rate Changed from $labour_payment->rate to $request->rate. ";
				}
				if($message != ""){
					LogController::userErrorLog($labour_payment->user_id,'Product Loading Labour Payment',$message);
				}
				/*------------------Log Error--------------*/



				$labour_payment->labour_name = $request->labour_name;
				$labour_payment->rate = $request->rate;
				if($labour_payment->save()){
					return redirect('/backend/update-labour-payments?labour_payment='.$request->labour_payment_id)->with('success',"Saved Successfully.");
				}else{
					return redirect('/backend/update-labour-payments')->with('error','Failed to save');
				}
			}else{
				return redirect('/backend/update-labour-payments')->with('error','_not found');
			}
		}
		return view('backend.update-labour-payment',$data);
	} 



	public function updateUnloadingLabourPayment(Request $request){
		$data = array();
		$data['labour_payments'] = \App\UnloadingLabourPayment::where('is_paid',0)->get();
		if($request->labour_payment){
			$data['current_labour_payment'] = \App\UnloadingLabourPayment::where('id',$request->labour_payment)->first();
		}
		if ($request->isMethod('post')){
			$labour_payment = \App\UnloadingLabourPayment::where('is_paid',0)->where('id',$request->labour_payment_id)->first();
			if(!is_null($labour_payment)){

				/*------------------Log Error--------------*/
				$message = "Unloading Labour # $labour_payment->id . ";
				if($labour_payment->labour_name != $request->labour_name){
					$message .= "Labour Name Changed from $labour_payment->labour_name to $request->labour_name. ";
				}
				if($labour_payment->rate != $request->rate){
					$message .= "Labour Rate Changed from $labour_payment->rate to $request->rate. ";
				}
				if($message != ""){
					LogController::userErrorLog($labour_payment->user_id,'Unloading Labour Payment',$message);
				}
				/*------------------Log Error--------------*/



				$labour_payment->labour_name = $request->labour_name;
				$labour_payment->rate = $request->rate;
				if($labour_payment->save()){
					return redirect('/backend/update-unloading-labour-payments?labour_payment='.$request->labour_payment_id)->with('success',"Saved Successfully.");
				}else{
					return redirect('/backend/update-unloading-labour-payments')->with('error','Failed to save');
				}
			}else{
				return redirect('/backend/update-unloading-labour-payments')->with('error','_not found');
			}
		}
		return view('backend.update-unloading-labour-payment',$data);
	} 

	public function updateDirectLabourPayment(Request $request){
		$data = array();
		$data['direct_labour_payments'] = \App\DirectLabourPayment::where('is_paid',0)->get();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		if($request->direct_labour_payment){
			$data['current_direct_labour_payment'] = \App\DirectLabourPayment::where('id',$request->direct_labour_payment)->first();
		}
		if ($request->isMethod('post')){
			$direct_labour_payment = \App\DirectLabourPayment::where('is_paid',0)->where('id',$request->direct_labour_payment)->first();
			if(!is_null($direct_labour_payment)){
				// dd($direct_labour_payment);
				/*------------------Log Error--------------*/
				$message = "Direct Labour # $direct_labour_payment->id . ";
				if($direct_labour_payment->labour_name != $request->labour_name){
					$message .= "Labour Name Changed from $direct_labour_payment->labour_name to $request->labour_name. ";
				}
				if($direct_labour_payment->amount != $request->amount){
					$message .= "Labour Amount Changed from $direct_labour_payment->amount to $request->amount. ";
				}
				if($message != ""){
					LogController::userErrorLog($direct_labour_payment->user_id,'Product Direct Labour Payment',$message);
				}
				/*------------------Log Error--------------*/


				$direct_labour_payment->labour_name = $request->labour_name;
				$direct_labour_payment->master_rake_id = $request->master_rake_id;
				$direct_labour_payment->warehouse_id = $request->warehouse_id;
				$direct_labour_payment->amount = $request->amount;
				
				if($direct_labour_payment->save()){
					return redirect('/backend/update-direct-labour-payments?direct_labour_payment='.$request->labour_payment_id)->with('success',"Saved Successfully.");
				}else{
					return redirect('/backend/update-direct-labour-payments')->with('error','Failed to save');
				}
			}else{
				return redirect('/backend/update-direct-labour-payments')->with('error','not found');
			}
		}
		return view('backend.update-direct-labour-payment',$data);
	} 

	public function updateWagonUnloading(Request $request){
		$data = array();
		$data['wagon_unloadings'] = \App\WagonUnloading::where('is_paid',0)->get();
		if($request->wagon_unloading){
			$data['current_wagon_unloading'] = \App\WagonUnloading::where('id',$request->wagon_unloading)->first();
		}
		if ($request->isMethod('post')){
			$wagon_unloading = \App\WagonUnloading::where('is_paid',0)->where('id',$request->wagon_unloading_id)->first();
			if(!is_null($wagon_unloading)){

				/*------------------Log Error--------------*/
				$message = "Wagon unloading # $wagon_unloading->id . ";
				if($wagon_unloading->quantity != $request->quantity){
					$message .= "Quantity Changed from $wagon_unloading->quantity to $request->quantity. ";
				}
				if($wagon_unloading->wagon_number != $request->wagon_number){
					$message .= "wagon number Changed from $wagon_unloading->wagon_number to $request->wagon_number. ";
				}
				if($wagon_unloading->wagon_rate != $request->wagon_rate){
					$message .= "Wagon Rate Changed from $wagon_unloading->wagon_rate to $request->wagon_rate. ";
				}
				if($wagon_unloading->labour_name != $request->labour_name){
					$message .= "Labour Name Changed from $wagon_unloading->labour_name to $request->labour_name. ";
				}
				if($message != ""){
					LogController::userErrorLog($wagon_unloading->unloaded_by,'Wagon Unloading',$message);
				}
				/*------------------Log Error--------------*/


				$wagon_unloading->quantity = $request->quantity;
				$wagon_unloading->wagon_number = $request->wagon_number;
				$wagon_unloading->wagon_rate = $request->wagon_rate;
				$wagon_unloading->labour_name = $request->labour_name;
				$wagon_unloading->save();
				if($wagon_unloading->save()){
					return redirect('/backend/update-wagon-unloadings?wagon_unloading='.$request->wagon_unloading_id)->with('success',"Saved Successfully.");
				}else{
					return redirect('/backend/update-wagon-unloadings')->with('error','Failed to save');
				}
			}else{
				return redirect('/backend/update-wagon-unloadings')->with('error','_not found');
			}
		}
		return view('backend.update-wagon-unloadings',$data);
	} 


	public function updateInvetory(Request $request){
		$data = array();
		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['retailers'] = \App\Retailer::where('is_active',1)->get();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		$data['units'] = \App\Unit::where('is_active',1)->get();


		if ($request->isMethod('post')){
			$inventory = \App\Inventory::where('dealer_id',$request->dealer_id)
			->where('warehouse_id',$request->warehouse_id)
			->where('product_brand_id',$request->product_company_id)
			->where('product_id',$request->product_id)
			->first();
			if(!is_null($inventory)){
				$inventory->quantity = $inventory->quantity + $request->quantity;
				$inventory->save();
			}else{
				$inventory = new  \App\Inventory();
				$inventory->dealer_id 			= $request->dealer_id;
				$inventory->warehouse_id 		= $request->warehouse_id;
				$inventory->product_company_id 		= $request->product_company_id;
				$inventory->product_brand_id 		= $request->product_brand_id;
				$inventory->product_id 			= $request->product_id;
				$inventory->quantity 			= $request->quantity;
				$inventory->unit_id = $request->unit_id;
				$inventory->save();
			}
			// exit;

		}
		return view('backend.update-inventory',$data);
	}

	public function updateOpeningInvetory(Request $request){
		$data = array();
		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['retailers'] = \App\Retailer::where('is_active',1)->get();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		$data['units'] = \App\Unit::where('is_active',1)->get();


		if ($request->isMethod('post')){
			$inventory = \App\OpeningInventory::where('dealer_id',$request->dealer_id)
			->where('warehouse_id',$request->warehouse_id)
			->where('product_brand_id',$request->product_company_id)
			->where('product_id',$request->product_id)
			->first();
			if(!is_null($inventory)){
				$inventory->quantity = $inventory->quantity + $request->quantity;
				$inventory->save();
			}else{
				$inventory = new  \App\OpeningInventory();
				$inventory->dealer_id 			= $request->dealer_id;
				$inventory->warehouse_id 		= $request->warehouse_id;
				$inventory->product_company_id 		= $request->product_company_id;
				$inventory->product_brand_id 		= $request->product_brand_id;
				$inventory->product_id 			= $request->product_id;
				$inventory->quantity 			= $request->quantity;
				$inventory->unit_id = $request->unit_id;
				$inventory->save();
			}
			// exit;

		}
		return view('backend.update-opening-inventory',$data);
	} 




	public function updateWtloadingFreightPayment(Request $request){
		$data = array();
		$data['loadings'] = \App\WarehouseTransferLoading::all();
		if($request->loading){
			$data['current_loading'] = \App\WarehouseTransferLoading::where('id',$request->loading)->first();
		}
		if ($request->isMethod('post')){
			$loading = \App\WarehouseTransferLoading::where('id',$request->loading_id)->first();
			if(!is_null($loading)){

				/*------------------Log Error--------------*/
				$message = "Warehouse Transfer Loading# $loading->id . ";
				
				if($loading->freight != $request->freight){
					$message .= "Freight Changed from $loading->freight to $request->freight . ";
				}
				if($loading->truck_number != $request->truck_number){
					$message .= "Truck number Changed from $loading->truck_number to $request->truck_number. ";
				}
				
				if($message != ""){
					LogController::userErrorLog($loading->user_id,'Warehouse Transfer Product Loading',$message);
				}
				/*------------------Log Error--------------*/


				
				$loading->truck_number = $request->truck_number;
				$loading->freight = $request->freight;

				if($loading->save()){
					return redirect('/backend/update-wtl-freight-payments?loading='.$request->loading_id)->with('success',"Saved Successfully.");
				}else{
					return redirect('/backend/update-wtl-freight-payments')->with('error','Failed to save');
				}
			}else{
				return redirect('/backend/update-wtl-freight-payments')->with('error','not found');
			}
		}
		return view('backend.update-wtl-freight-payments',$data);
	} 



	public function updateWtloadingLabourPayment(Request $request){
		$data = array();
		$data['labour_payments'] = \App\WarehouseTransferLoading::where('is_labour_paid',0)->get();
		if($request->labour_payment){
			$data['current_labour_payment'] = \App\WarehouseTransferLoading::where('id',$request->labour_payment)->first();
		}
		if ($request->isMethod('post')){
			$labour_payment = \App\WarehouseTransferLoading::where('is_labour_paid',0)->where('id',$request->labour_payment_id)->first();
			if(!is_null($labour_payment)){

				/*------------------Log Error--------------*/
				$message = "Warehouse Transfer Unloading Labour # $labour_payment->id . ";
				if($labour_payment->labour_name != $request->labour_name){
					$message .= "Labour Name Changed from $labour_payment->labour_name to $request->labour_name. ";
				}
				if($labour_payment->labour_rate != $request->rate){
					$message .= "Labour Rate Changed from $labour_payment->rate to $request->rate. ";
				}
				if($message != ""){
					LogController::userErrorLog($labour_payment->user_id,'Warehouse Transfer Loading Labour Payment',$message);
				}
				/*------------------Log Error--------------*/



				$labour_payment->labour_name = $request->labour_name;
				$labour_payment->labour_rate = $request->rate;
				if($labour_payment->save()){
					return redirect('/backend/update-wtl-labour-payments?labour_payment='.$request->labour_payment_id)->with('success',"Saved Successfully.");
				}else{
					return redirect('/backend/update-wtl-labour-payments')->with('error','Failed to save');
				}
			}else{
				return redirect('/backend/update-wtl-labour-payments')->with('error','_not found');
			}
		}
		return view('backend.update-wtl-labour-payments',$data);
	} 

	

	public function updateWtUnloadingLabourPayment(Request $request){
		$data = array();
		$data['labour_payments'] = \App\WarehouseTransferUnloading::where('is_labour_paid',0)->get();
		if($request->labour_payment){
			$data['current_labour_payment'] = \App\WarehouseTransferUnloading::where('id',$request->labour_payment)->first();
		}
		if ($request->isMethod('post')){
			$labour_payment = \App\WarehouseTransferUnloading::where('is_labour_paid',0)->where('id',$request->labour_payment_id)->first();
			if(!is_null($labour_payment)){

				/*------------------Log Error--------------*/
				$message = "Warehouse Transfer Unloading Labour # $labour_payment->id . ";
				if($labour_payment->labour_name != $request->labour_name){
					$message .= "Labour Name Changed from $labour_payment->labour_name to $request->labour_name. ";
				}
				if($labour_payment->labour_rate != $request->rate){
					$message .= "Labour Rate Changed from $labour_payment->rate to $request->rate. ";
				}
				if($message != ""){
					LogController::userErrorLog($labour_payment->user_id,'Warehouse Transfer Unloading Labour Payment',$message);
				}
				/*------------------Log Error--------------*/



				$labour_payment->labour_name = $request->labour_name;
				$labour_payment->labour_rate = $request->rate;
				if($labour_payment->save()){
					return redirect('/backend/update-wtul-labour-payments?labour_payment='.$request->labour_payment_id)->with('success',"Saved Successfully.");
				}else{
					return redirect('/backend/update-wtul-labour-payments')->with('error','Failed to save');
				}
			}else{
				return redirect('/backend/update-wtul-labour-payments')->with('error','_not found');
			}
		}
		return view('backend.update-wtul-labour-payments',$data);
	} 

	public function adjustStock(Request $request){
		$data = array();
		$data['dealers'] = \App\Dealer::where('is_active',1)->get();
		$data['products'] = \App\Product::where('is_active',1)->get();
		$data['product_companies'] = \App\ProductCompany::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		$data['units'] = \App\Unit::where('is_active',1)->get();
		if ($request->isMethod('post')){
			
			$request->validate([
				'dealer_id'     =>    'required',
				'product_id'     =>    'required',
				'product_brand_id'     =>    'required',
				'adjust_from'     =>    'required',
				'quantity'     =>    'required',
				'unit_id'     =>    'required',
			]);

			$stock_adjustments = new \App\StockAdjustment();
			$stock_adjustments->adjust_type = $request->adjust_type;
			$stock_adjustments->dealer_id = $request->dealer_id;
			$stock_adjustments->product_id = $request->product_id;
			$stock_adjustments->product_brand_id = $request->product_brand_id;
			$stock_adjustments->product_company_id = $request->adjust_from;
			if($request->warehouse_id){
				$stock_adjustments->warehouse_id = $request->warehouse_id;
			}else{
				$stock_adjustments->warehouse_id = 1;
			}
			$stock_adjustments->quantity = $request->quantity;
			$stock_adjustments->unit_id = $request->unit_id;
			$stock_adjustments->save();

			$inventory = new  \App\Inventory();
			$inventory->dealer_id 			= $request->dealer_id;
			if($request->warehouse_id){
				$inventory->warehouse_id = $request->warehouse_id;
			}else{
				$inventory->warehouse_id = 1;
			}
			// $inventory->product_company_id 		= $request->adjust_from;
			$inventory->product_brand_id 		= $request->product_brand_id;
			$inventory->product_id 			= $request->product_id;
			if($request->adjust_type == 1){
				$inventory->quantity 			= $request->quantity;
			}else{
				$inventory->quantity 			= 0 - $request->quantity;
			}
			$inventory->unit_id = $request->unit_id;
			$inventory->save();


			return redirect('/backend/adjust-stock')->with('success','Save');

		}
		return view('backend.adjust-stock',$data);
	} 

	
}
