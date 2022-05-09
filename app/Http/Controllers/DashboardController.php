<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class DashboardController extends Controller
{
	public function index(){
		$data = array();

		return view('dashboard.index',$data);
		
	}

	public function getPartyStock($party){
		$data = array();
		$party_array = explode('-',$party);
		if($party_array[1] == "1"){
			$type = "dealer_id";
		}else{
			$type = "product_company_id";
		}
		$cement_product_ids = \App\Product::where('product_category_id',2)->pluck('id');
		$data['cement_stock'] = \App\Inventory::where($type,$party_array[0])->whereIn('product_id',$cement_product_ids)->sum('quantity');
		$data['dap_stock'] = \App\Inventory::where($type,$party_array[0])->where('product_id',4)->sum('quantity');
		$data['npk_stock'] = \App\Inventory::where($type,$party_array[0])->whereIn('product_id',array(9,10))->sum('quantity');
		$data['urea_stock'] = \App\Inventory::where($type,$party_array[0])->where('product_id',19)->sum('quantity');
		$data['mop_stock'] = \App\Inventory::where($type,$party_array[0])->where('product_id',7)->sum('quantity');
		return view('dashboard.get-party-stock',$data);
		
	}

	function latestSaleActivities(){
		$data = array();
		$activities = "";
		$cement_product_ids = \App\Product::where('product_category_id',2)->pluck('id');
		$cement_sale_activity = \App\LoadingSlipInvoice::whereIn('product_id',$cement_product_ids)->orderBy('id','desc')->first();
		if(!is_null($cement_sale_activity)){
			$activities .= "Sale of <b>".$cement_sale_activity->product."</b> with Invoice <b>".$cement_sale_activity->invoice_number."(Rs $cement_sale_activity->total)</b> to <b>$cement_sale_activity->retailer_name</b>  from Account of <b>".getModelById('Dealer',$cement_sale_activity->dealer_id)->name."(".getModelById('Dealer',$cement_sale_activity->dealer_id)->address1.") </b>".calculateTimeSpan($cement_sale_activity->created_at).". ";
		}
		$dap_sale_activity = \App\LoadingSlipInvoice::where('product_id',4)->orderBy('id','desc')->first();
		if(!is_null($dap_sale_activity)){
			$activities .= "Sale of <b>".$dap_sale_activity->product."</b> with Invoice <b>".$dap_sale_activity->invoice_number."(Rs $dap_sale_activity->total)</b> to <b>$dap_sale_activity->retailer_name</b>  from Account of <b>".getModelById('Dealer',$dap_sale_activity->dealer_id)->name."(".getModelById('Dealer',$dap_sale_activity->dealer_id)->address1.") </b>".calculateTimeSpan($dap_sale_activity->created_at).". ";
		}

		$npk_sale_activity = \App\LoadingSlipInvoice::whereIn('product_id',array(9,10))->orderBy('id','desc')->first();
		if(!is_null($npk_sale_activity)){
			$activities .= "Sale of <b>".$npk_sale_activity->product."</b> with Invoice <b>".$npk_sale_activity->invoice_number."(Rs $npk_sale_activity->total)</b> to <b>$npk_sale_activity->retailer_name</b>  from Account of <b>".getModelById('Dealer',$npk_sale_activity->dealer_id)->name."(".getModelById('Dealer',$npk_sale_activity->dealer_id)->address1.") </b>".calculateTimeSpan($npk_sale_activity->created_at).". ";
		}
		$urea_sale_activity = \App\LoadingSlipInvoice::where('product_id',19)->orderBy('id','desc')->first();
		if(!is_null($urea_sale_activity)){
			$activities .= "Sale of <b>".$urea_sale_activity->product."</b> with Invoice <b>".$urea_sale_activity->invoice_number."(Rs $urea_sale_activity->total)</b> to <b>$urea_sale_activity->retailer_name</b>  from Account of <b>".getModelById('Dealer',$urea_sale_activity->dealer_id)->name."(".getModelById('Dealer',$urea_sale_activity->dealer_id)->address1.") </b>".calculateTimeSpan($urea_sale_activity->created_at).". ";
		}
		$mop_sale_activity = \App\LoadingSlipInvoice::where('product_id',7)->orderBy('id','desc')->first();
		if(!is_null($mop_sale_activity)){
			$activities .= "Sale of <b>".$mop_sale_activity->product."</b> with Invoice <b>".$mop_sale_activity->invoice_number."(Rs $mop_sale_activity->total)</b> to <b>$mop_sale_activity->retailer_name</b>  from Account of <b>".getModelById('Dealer',$mop_sale_activity->dealer_id)->name."(".getModelById('Dealer',$mop_sale_activity->dealer_id)->address1.") </b>".calculateTimeSpan($mop_sale_activity->created_at).". ";
		}
		$data['activities'] = $activities;
		return view('dashboard.latest-sale-activities',$data);
	}

	public function getPartyStockSaleGraph($party,$month,$year){
		$data = array();
		$cement_product_ids = \App\Product::where('product_category_id',2)->pluck('id');
		$data['cement_sale'] = \App\LoadingSlipInvoice::where('dealer_id',$party)->whereIn('product_id',$cement_product_ids)->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->sum('total');

		$data['dap_sale'] = \App\LoadingSlipInvoice::where('dealer_id',$party)->where('product_id',4)->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->sum('total');
		$data['npk_sale'] = \App\LoadingSlipInvoice::where('dealer_id',$party)->whereIn('product_id',array(9,10))->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->sum('total');
		$data['urea_sale'] = \App\LoadingSlipInvoice::where('dealer_id',$party)->where('product_id',19)->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->sum('total');
		$data['mop_sale'] = \App\LoadingSlipInvoice::where('dealer_id',$party)->where('product_id',7)->whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->sum('total');
		return view('dashboard.get-party-stock-sale-graph',$data);
	}

	public function getSalesAndPurchase($date){
		$data = array();
		$sales = array();
		$unique_sale_parties = \App\LoadingSlipInvoice::select('dealer_id')->whereDate('created_at','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->distinct()->get();

		if(!is_null($unique_sale_parties)){
			foreach ($unique_sale_parties as $unique_sale_party) {
				$unique_products = \App\LoadingSlipInvoice::select('product_id')->where('dealer_id',$unique_sale_party->dealer_id)->whereDate('created_at','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->distinct()->get();
				foreach ($unique_products as $unique_product) {
					$tempArr = array();
					$tempArr['party'] = getModelById('Dealer',$unique_sale_party->dealer_id)->name;  
					$tempArr['product'] = getModelById('Product',$unique_product->product_id)->name;  
					$tempArr['total_sale_quantity'] = \App\LoadingSlipInvoice::where('product_id',$unique_product->product_id)->where('dealer_id',$unique_sale_party->dealer_id)->whereDate('created_at','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->sum('quantity');
					$tempArr['total_sale_amount'] = \App\LoadingSlipInvoice::where('product_id',$unique_product->product_id)->where('dealer_id',$unique_sale_party->dealer_id)->whereDate('created_at','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->sum('total');
					array_push($sales, $tempArr);
				}
			}
		}


		$purchase = array();
		$unique_purchase_c_companies = \App\CompanyDi::select('product_company_id')->whereDate('invoice_date','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->distinct()->get();

		$unique_purchase_w_companies = \App\WarehouseDi::select('product_company_id')->where('transfer_type',1)->whereDate('invoice_date','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->distinct()->get();
		$unique_purchase_w_parties = \App\WarehouseDi::select('from_dealer_id')->where('transfer_type',2)->whereDate('invoice_date','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->distinct()->get();


		if(!is_null($unique_purchase_c_companies)){
			foreach ($unique_purchase_c_companies as $unique_purchase_c_company) {
				$unique_products = \App\CompanyDi::select('product_id')->where('product_company_id',$unique_purchase_c_company->product_company_id)->whereDate('invoice_date','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->distinct()->get();
				foreach ($unique_products as $unique_product) {
					$tempArr = array();
					$tempArr['party'] = getModelById('ProductCompany',$unique_purchase_c_company->product_company_id)->name;  
					$tempArr['product'] = getModelById('Product',$unique_product->product_id)->name;  
					$tempArr['total_purchase_quantity'] = \App\CompanyDi::where('product_id',$unique_product->product_id)->where('product_company_id',$unique_purchase_c_company->product_company_id)->whereDate('invoice_date','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->sum('quantity');
					$tempArr['total_purchase_amount'] = \App\CompanyDi::where('product_id',$unique_product->product_id)->where('product_company_id',$unique_purchase_c_company->product_company_id)->whereDate('invoice_date','=',date('Y-m-d',strtotime($date)))->orderBy('id','desc')->sum('total');
					array_push($purchase, $tempArr);
				}
			}
		}


		if(!is_null($unique_purchase_w_companies)){
			foreach ($unique_purchase_w_companies as $unique_company) {
				$unique_products = \App\WarehouseDi::select('product_id')->where('product_company_id',$unique_company->product_company_id)->whereDate('invoice_date','=',date('Y-m-d'))->orderBy('id','desc')->distinct()->get();
				foreach ($unique_products as $unique_product) {
					$tempArr = array();
					$tempArr['party'] = getModelById('ProductCompany',$unique_company->product_company_id)->name;  
					$tempArr['product'] = getModelById('Product',$unique_product->product_id)->name;  
					$tempArr['total_purchase_quantity'] = \App\WarehouseDi::where('product_id',$unique_product->product_id)->where('product_company_id',$unique_company->product_company_id)->whereDate('invoice_date','=',date('Y-m-d'))->orderBy('id','desc')->sum('quantity');
					$tempArr['total_purchase_amount'] = \App\WarehouseDi::where('product_id',$unique_product->product_id)->where('product_company_id',$unique_company->product_company_id)->whereDate('invoice_date','=',date('Y-m-d'))->orderBy('id','desc')->sum('total');
					array_push($purchase, $tempArr);
				}
			}
		}

		if(!is_null($unique_purchase_w_parties)){
			foreach ($unique_purchase_w_parties as $unique_purchase_party) {
				$unique_products = \App\WarehouseDi::select('product_id')->where('from_dealer_id',$unique_purchase_party->from_dealer_id)->whereDate('invoice_date','=',date('Y-m-d'))->orderBy('id','desc')->distinct()->get();
				foreach ($unique_products as $unique_product) {
					$tempArr = array();
					$tempArr['party'] = getModelById('Dealer',$unique_purchase_party->from_dealer_id)->name;  
					$tempArr['product'] = getModelById('Product',$unique_product->product_id)->name;  
					$tempArr['total_purchase_quantity'] = \App\WarehouseDi::where('product_id',$unique_product->product_id)->where('from_dealer_id',$unique_purchase_party->from_dealer_id)->whereDate('invoice_date','=',date('Y-m-d'))->orderBy('id','desc')->sum('quantity');
					$tempArr['total_purchase_amount'] = \App\WarehouseDi::where('product_id',$unique_product->product_id)->where('from_dealer_id',$unique_purchase_party->from_dealer_id)->whereDate('invoice_date','=',date('Y-m-d'))->orderBy('id','desc')->sum('total');
					array_push($purchase, $tempArr);
				}
			}
		}

		$data['today_sales'] = $sales;
		$data['today_purchase'] = $purchase;
		// dd($data);

		$data['date'] = date('m/d/Y',strtotime($date));
		return view('dashboard.get-sales-and-purchase',$data);
		
	}
	public function getRakeGraph($id){
		$data = array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->orderBy('id','desc')->get();
		
		$data['master_rake'] = \App\MasterRake::where('id',$id)->first();
		return view('dashboard.get-rake-graph',$data);
		
	}
	public function getMonthlyAverage($month,$year){
		$data = array();
		$data['month'] = $month;
		$data['year'] = $year;
		return view('dashboard.get-monthly-average',$data);
		
	}

	public function setCompany(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'act_as_company' =>'required',
			)
		);

		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			Session::put('acting_company',$request->act_as_company);
			$response['flag'] = true;
			$response['message'] = "Now Your are acting as ".getModelById('Company', $request->act_as_company)->name;
		}
		return response()->json($response);
	}

	public function changeLanguage(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'language' =>'required',
			)
		);

		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			Session::put('language',$request->language);
			\App::setLocale($request->language);
			$response['flag'] = true;
			$response['message'] = "Language Changed Successfully";
		}
		return response()->json($response);
	}
	public function blank()
	{
		return view('dashboard.blank');
	}
}
