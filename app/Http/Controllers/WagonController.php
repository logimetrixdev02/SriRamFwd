<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class WagonController extends Controller
{


	public function getDirectLabourPayment()
	{
		$data= array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		return view('dashboard.direct_labour_payments.direct_labour_payment',$data);
	}

	public function postDirectLabourPayment(Request $request)
	{
		$validator = \Validator::make($request->all(),
			array(
				'master_rake_id' =>'required',
				'labour_name' =>'required',
				'wagon_rate' =>'required',
				'quantity' =>'required',
				'amount' =>'required',
			)
		);
		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$directlabour = new \App\WagonUnloading();
			$directlabour->master_rake_id	= $request->master_rake_id;
			$directlabour->wagon_number 		= $request->wagon_number;
			$directlabour->labour_name		= $request->labour_name;
			$directlabour->wagon_rate		= $request->wagon_rate;
			$directlabour->quantity			= $request->quantity;
			if($directlabour->save())
			{
				$response['flag'] = true;
				$response['message'] = "Payment Generated Successfully";
			}
			else{
				$response['flag'] = false;
				$response['errors'] = "Something Went Wrong";
			}
		}
		return response()->json($response);

	}


	public function wagonUnloadings(Request $request){
		$data = array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$query = \App\WagonUnloading::query();
			if(isset($request->master_rake_id)){
				$query->where('master_rake_id',$request->master_rake_id);
				$data['master_rake_id'] = $request->master_rake_id;
			}
			$wagon_unloadings = $query->with('master_rake')->get();
			$data['wagon_unloadings'] = $wagon_unloadings;
			
		}else{
			$wagon_unloadings = \App\WagonUnloading::whereDate('created_at', '=', date('Y-m-d'))->with('master_rake')->get();
			$data['wagon_unloadings'] = $wagon_unloadings;
			$data['date'] = date('m/d/Y');
		}

		return view('dashboard.wagon_unloading.wagon_unloadings',$data);
	}

	public function printWagonUnloadingSlip($id){
		$data = array();
		$wagon_unloading =  \App\WagonUnloading::where('id',$id)->first();
		if(is_null($wagon_unloading)){
			return redirect('user/wagon-unloadings')->with('error','Slip Not found');
		}else{
			$data['wagon_unloading'] = $wagon_unloading;
			return view('dashboard.wagon_unloading.print-wagon-unloading-slip',$data);
		} 
	}


	/*
 * Function to load freight payment form
 */
	public function wagonUnloadingPayment()
	{
		return view("dashboard.wagon_unloading.pay-wagon-unloading-labour");
	}

	public function payWagonUnloadingLabour(Request $request){
		$response = array();
		$validator = \Validator::make($request->all(),
			array(
				'unloading_slip_id' 			=>'required',
				'amount_to_pay' 			=>'required'
			)
		);
		if($validator->fails()){
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$unloading_payment = \App\WagonUnloading::where('id',$request->unloading_slip_id)->first();
			if(!is_null($unloading_payment)){
				if($unloading_payment->is_paid){
					$response['flag'] 			= false;
					$response['message'] = "Payment Already done for this slip";
				}else{

					$unloading_payment->is_paid = 1;
					$unloading_payment->paid_by = \Auth::id();
					$unloading_payment->paid_amount = $unloading_payment->wagon_rate;
					$unloading_payment->payment_date = date('Y-m-d H:i:s');
					if($unloading_payment->save()){
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


}
