<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class StandardizationController extends Controller
{


	public function getDirectLabourPayment()
	{
		$data= array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		return view('dashboard.standardizations.standardization',$data);
	}

	public function postDirectLabourPayment(Request $request)
	{
		$validator = \Validator::make($request->all(),
			array(
				'labour_name' =>'required',
				'amount' =>'required',
			)
		);
		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$directlabour = new \App\Standardization();
			$directlabour->master_rake_id=$request->master_rake_id;
			$directlabour->warehouse_id=$request->warehouse_id;
			$directlabour->labour_name=$request->labour_name;
			$directlabour->amount=$request->amount;
			$directlabour->description=$request->description;
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


	public function standardizations(Request $request){
		$data = array();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$query = \App\Standardization::query();
			if(isset($request->warehouse_id)){
				$query->where('warehouse_id',$request->warehouse_id);
				$data['warehouse_id'] = $request->warehouse_id;
			}
			$standardizations = $query->with('warehouse')->get();
			$data['standardizations'] = $standardizations;
			
		}else{
			$standardizations = \App\Standardization::whereDate('created_at', '=', date('Y-m-d'))->with('warehouse')->get();
			$data['standardizations'] = $standardizations;
		}

		return view('dashboard.standardizations.standardizations',$data);
	}


	public function labourPayments(Request $request){
		$data = array();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$query = \App\Standardization::query();
			if(isset($request->warehouse_id)){
				$query->where('warehouse_id',$request->warehouse_id);
				$data['warehouse_id'] = $request->warehouse_id;
			}
			$standardizations = $query->with('warehouse')->get();
			$data['standardizations'] = $standardizations;
			
		}else{
			$standardizations = \App\Standardization::whereDate('created_at', '=', date('Y-m-d'))->with('warehouse')->get();
			$data['standardizations'] = $standardizations;
		}

		return view('dashboard.standardizations.labour-payments',$data);
	}


	public function printStandardizationSlip($id){
		$data = array();
		$labour_payment =  \App\Standardization::where('id',$id)->first();
		if(is_null($labour_payment)){
			return redirect('user/standardizations')->with('error','Slip Not found');
		}else{
			$data['labour_payment'] = $labour_payment;
			return view('dashboard.standardizations.print-standardization-slip',$data);
		} 
	}


	public function standardization()
	{
		return view("dashboard.standardizations.pay-labour");
	}

	public function payStandardization(Request $request){
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
			$labour_payment = \App\Standardization::where('id',$request->labour_slip_id)->first();
			if(!is_null($labour_payment)){
				if($labour_payment->is_paid){
					$response['flag'] 			= false;
					$response['message'] = "Payment Already done for this slip";
				}else{

					$labour_payment->is_paid = 1;
					$labour_payment->paid_by = \Auth::id();
					$labour_payment->payment_amount = $request->packed_quantity * $request->labour_rate;
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


}
