<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use DataTables;
use DB;
use App\Report;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
             // Sale Reports Controller 
		        public function dealerSaleReportFilter(Request $request){
						$data = array();
						
							$data['acting_company'] = Session::get('acting_company');

								$from = $request->from_date;
								$to = $request->to_date;
								$dealer_id = $request->dealer_id;


						if(!empty($from) && !empty($to) && !empty($dealer_id)){
							$orders = \App\Order::where('is_active',1)->whereBetween('created_at',[$from,$to])->orderBy('created_at','desc')->where('dealer_id',$dealer_id)->where('invoice_status',1)->groupBy(['product_id','retailer_id','created_at'])->select('id','retailer_id','product_id',\DB::raw('(DATE_FORMAT(created_at,"%d/%m/%y")) as date'),\DB::raw("SUM(quantity) as qty"))->get();
                      
								
						}else if(!empty($dealer_id)){
							// echo 'hello';
								// $orders = \App\Order::where('is_active',1)->where('dealer_id',$dealer_id)->groupBy(['product_id','retailer_id','created_at'])
								// ->select('id','retailer_id','product_id',\DB::raw('(DATE_FORMAT(created_at,"%d/%m/%y")) as date'),\DB::raw("SUM(quantity) as qty"))->get();
								$orders = \App\Order::query()->where('is_active',1)->where('dealer_id',$dealer_id)->groupBy(['product_id','retailer_id','created_at'])
								->select('id','retailer_id','product_id',\DB::raw('(DATE_FORMAT(created_at,"%d/%m/%y")) as date'),\DB::raw("SUM(quantity) as qty"))->get();

						}else{
							$orders = \App\Order::where('is_active',1)->whereBetween('created_at',[$from,$to])->orderBy('created_at','desc')->where('invoice_status',1)->groupBy(['product_id','retailer_id','created_at'])->select('id','retailer_id','product_id',\DB::raw('(DATE_FORMAT(created_at,"%d/%m/%y")) as date'),\DB::raw("SUM(quantity) as qty"))->get();

						}

						// dd($orders);
					
						return Datatables::of($orders)
						->addIndexColumn()

					->addColumn('order_date', function($orders){
						
						
							$btn =' <td>';
							$btn=$btn.date('d/m/Y',strtotime($orders->created_at));
							
							$btn=$btn.'</td>';
						
						return $btn;
					})

						->addColumn('retailer_name', function($orders){
						
								$btn =' <td>';
								
							
								$btn=$btn.getretailer($orders->retailer_id)->name."</b> &nbsp;&nbsp;[".$orders->retailer_id."]";
							
								
								$btn=$btn.'</td>';

						return $btn;
					})

					->addColumn('truck_no', function($orders){
					
						$btn =' <td>';
					
				
						$truck_numbers=\App\LoadingSlip::where('order_id',$orders->id)->pluck('vehicle_no');
	
						
	
						
						$tn=[];
						foreach($truck_numbers as $k => $v){
						  array_push($tn,$v);	
						}
						$btn=$btn.implode(',',$tn);
					
						
						$btn=$btn.'</td>';
					
		
					return $btn;
				// print_r($btn);
			})
					

					->addColumn('product_name', function($orders){
					$role_id=Auth::user()->role_id;
					$btn =' <td>';
						
					
						$btn=$btn.getModelById('Product',$orders->product_id)->name;
					
						
						$btn=$btn.'</td>';
					
					return $btn;
					})
					
					
						->rawColumns(['retailer_name','product_name','truck_no'])
						->with('totalBag', function() use ($orders) {
						return $orders->sum('qty');
						})->make(true);
				    }	

			   function dealerSaleReport(){
			   	$data=[];
			   	$data['dealers']= \App\Dealer::where('is_active',1)->get();

			   	return view('dashboard.report.dealer-sale-report',$data);

			   }


			    public function retailerSaleReportFilter(Request $request){
						$data = array();

								$from = $request->from_date;
								$to = $request->to_date;
								$retailer_id = $request->retailer_id;


						if(!empty($from) && !empty($to) && !empty($retailer_id)){
							$orders = \App\Order::where('is_active',1)->whereBetween('created_at',[$from,$to])->orderBy('created_at','desc')->where('retailer_id',$retailer_id)->where('invoice_status',1)->groupBy('product_id')->groupBy('retailer_id')->select('retailer_id','product_id',\DB::raw("SUM(quantity) as qty"))->get();
								
						}else if(!empty($retailer_id)){
							// echo 'hello';
								$orders = \App\Order::where('is_active',1)->where('retailer_id',$retailer_id)->groupBy('product_id')->groupBy('retailer_id')->select('retailer_id','product_id',\DB::raw("SUM(quantity) as qty"))->get();

						}else{
							$orders = \App\Order::where('is_active',1)->whereBetween('created_at',[$from,$to])->orderBy('created_at','desc')->where('invoice_status',1)->groupBy('product_id')->groupBy('retailer_id')->select('retailer_id','product_id',\DB::raw("SUM(quantity) as qty"))->get();

						}
					
						return Datatables::of($orders)
						->addIndexColumn()

					->addColumn('order_date', function($orders){
						
						
							$btn =' <td>';
							$btn=$btn.date('d/m/Y',strtotime($orders->created_at));
							
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
					
					
						->rawColumns(['retailer_name','product_name'])
						->with('totalBag', function() use ($orders) {
						return $orders->sum('qty');
						})->make(true);
				}
			   public function retailerSaleReport(){
			    	$data=[];
			   		$data['retailers']= \App\Retailer::where('is_active',1)->get();
			    	 return view('dashboard.report.retailer-sale-report',$data);
			    }

               public function destinationSaleReportFilter(Request $request){
						$data = array();

								$from = $request->from_date;
								$to = $request->to_date;
								$destination_id = $request->destination_id;


						if(!empty($from) && !empty($to) && !empty($destination_id)){
							$orders = \App\Order::join('dealers','dealers.unique_id','orders.dealer_id')->join('retailers','retailers.unique_code','orders.retailer_id')->where('orders.is_active',1)->whereBetween('orders.created_at',[$from,$to])->orderBy('orders.created_at','desc')->where('orders.invoice_status',1)->where('retailers.destination_code',$destination_id)->orWhere('dealers.destination_code',$destination_id)->groupBy('orders.product_id')->groupBy('orders.retailer_id')->groupBy('orders.dealer_id')->select('orders.dealer_id','orders.retailer_id','orders.product_id',\DB::raw("SUM(orders.quantity) as qty"))->get();
								
						}else if(!empty($destination_id)){
							// echo 'hello';
								$orders = \App\Order::join('dealers','dealers.unique_id','orders.dealer_id')->join('retailers','retailers.unique_code','orders.retailer_id')->where('orders.is_active',1)->orderBy('orders.created_at','desc')->where('orders.invoice_status',1)->where('retailers.destination_code',$destination_id)->orWhere('dealers.destination_code',$destination_id)->groupBy('orders.product_id')->groupBy('orders.retailer_id')->groupBy('orders.dealer_id')->select('orders.dealer_id','orders.retailer_id','orders.product_id',\DB::raw("SUM(orders.quantity) as qty"))->get();

						}else{
							$orders = \App\Order::join('dealers','dealers.unique_id','orders.dealer_id')->join('retailers','retailers.unique_code','orders.retailer_id')->where('orders.is_active',1)->whereBetween('orders.created_at',[$from,$to])->orderBy('orders.created_at','desc')->where('orders.invoice_status',1)->groupBy('orders.product_id')->groupBy('orders.retailer_id')->groupBy('orders.dealer_id')->select('orders.dealer_id','orders.retailer_id','orders.product_id',\DB::raw("SUM(orders.quantity) as qty"))->get();
						}
					
						return Datatables::of($orders)
						->addIndexColumn()

					->addColumn('order_date', function($orders){
						
						
							$btn =' <td>';
							$btn=$btn.date('d/m/Y',strtotime($orders->created_at));
							
							$btn=$btn.'</td>';
						
						return $btn;
					})

						->addColumn('retailer_name', function($orders){
						
								$btn =' <td>';
								
							
								$btn=$btn.getretailer($orders->retailer_id)->name."</b> &nbsp;&nbsp;[".$orders->retailer_id."]";
							
								
								$btn=$btn.'</td>';

						return $btn;
					})
						->addColumn('delear_name', function($orders){
						
								$btn =' <td>';
								
							
								$btn=$btn.getdealer($orders->dealer_id)->name."</b> &nbsp;&nbsp;[".$orders->dealer_id."]";
							
								
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
					
					
						->rawColumns(['retailer_name','product_name','delear_name'])
						->with('totalBag', function() use ($orders) {
						return $orders->sum('qty');
						})->make(true);
				}





			  public function destinationSaleReport(){
			    	$data=[];
			   		$data['destinations']= \App\Location::where('is_active',1)->get();
			   		
			    	return view('dashboard.report.destination-sale-report',$data);

			   }
                
                 public function retailerPotentialSaleReportFilter(Request $request){
						
						// echo $financial_year_start.'<br>'.$financial_year_end;


				}
				 public function retailerPotentialSaleReport(Request $request){
				   	  $data=[];
				   	  	$data['sessions']= \App\Session::where('is_active',1)->get();
							if ($request->isMethod('post')){
									$session_id = $request->session_id;
									$years = explode("-", $session_id);
									$financial_year_start=$years[0].'-04-01';
									$financial_year_end=$years[1].'-03-31';
					             $data['orders'] =  \App\Order::where('is_active',1)->whereBetween('created_at',[$financial_year_start,$financial_year_end])->orderBy('m','asc')->where('invoice_status',1)->groupBy('product_id')->groupBy('retailer_id')->groupBy('y')->groupBy('m')->select(\DB::raw("YEAR(created_at) as y"),\DB::raw("MONTH(created_at) as m"),'retailer_id','product_id',\DB::raw("SUM(quantity) as qty"))->get();

					             $data['month_codes']=[
					             	0=>4,
					             	1=>5,
					             	2=>6,
					             	3=>7,
					             	4=>8,
					             	5=>9,
					             	6=>10,
					             	7=>11,
					             	8=>12,
					             	9=>1,
					             	10=>2,
					             	11=>3,
					             	
					             	
					             ];
					             $data['session_id']=$session_id;
					                // dd($data['orders']);
					       }
						   
						   	return view('dashboard.report.retailer-potential-sale-report',$data);

				   }
				 	
		
				   public function monthlySaleReport(){
					return view('dashboard.report.monthly_sale_reports');
				   }	
		
					public function monthlySaleReports(Request $request){

								if($request->year!=""){
									$year= $request->year;
				
								}else{
									$year=date("Y");
				
								}
								
								$month=($request->month_id);
								
								return Excel::download(new ReportExport($year,$month), 'report.xlsx');
							
								}
								
				    
				    
				    public function generateInvoice() {
				        return view('dashboard.report.generate-invoice');
				    }
				    
				    
				    
				    
}
