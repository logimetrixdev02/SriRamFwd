<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class DirectLabourPaymentController extends Controller
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
				'labour_name' =>'required',
				'amount' =>'required',
			)
		);
		if($validator->fails())
		{
			$response['flag'] = false;
			$response['errors'] = $validator->getMessageBag();
		}else{
			$directlabour = new \App\DirectLabourPayment();
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


	public function directLabourPayments(Request $request){
		$data = array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		$data['warehouses'] = \App\Warehouse::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$query = \App\DirectLabourPayment::query();
			if(isset($request->master_rake_id)){
				$query->where('master_rake_id',$request->master_rake_id);
				$data['master_rake_id'] = $request->master_rake_id;
			}
			if(isset($request->warehouse_id)){
				$query->where('warehouse_id',$request->warehouse_id);
				$data['warehouse_id'] = $request->master_rake_id;
			}
			if(isset($request->date)){
				$query->whereDate('created_at', '=', date('Y-m-d',strtotime($request->date)));
				$data['date'] = $request->master_rake_id;
			}else{
				$data['date'] = date('m/d/Y');
			}
			$direct_labour_payments = $query->with('master_rake','warehouse')->get();
			$data['direct_labour_payments'] = $direct_labour_payments;
			
		}else{
			$direct_labour_payments = \App\DirectLabourPayment::whereDate('created_at', '=', date('Y-m-d'))->with('master_rake','warehouse')->get();
			$data['direct_labour_payments'] = $direct_labour_payments;
			$data['date'] = date('m/d/Y');
		}

		return view('dashboard.direct_labour_payments.direct-labour-payments',$data);
	}

	public function printDirectLabourPlaymetSlip($id){
		$data = array();
		$labour_payment =  \App\DirectLabourPayment::where('id',$id)->first();
		if(is_null($labour_payment)){
			return redirect('user/direct-labour-payments')->with('error','Slip Not found');
		}else{
			$data['labour_payment'] = $labour_payment;
			return view('dashboard.direct_labour_payments.print-direct-labour-payment-slip',$data);
		} 
	}


	/*
 * Function to load freight payment form
 */
	public function labourPayment()
	{
		return view("dashboard.direct_labour_payments.pay-labour");
	}

	public function payLabour(Request $request){
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
			$labour_payment = \App\DirectLabourPayment::where('id',$request->labour_slip_id)->first();
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


}
