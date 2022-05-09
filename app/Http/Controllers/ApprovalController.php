<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class ApprovalController extends Controller
{


	public function rakePaymentApprovals(Request $request){
		$data = array();
		if ($request->isMethod('post')){
			$query = \App\RakeExpenseReport::query();
			if($request->month){
				$query->whereMonth('created_at', $request->month);
				$data['month'] = $request->month;
			}
			if($request->year){
				$query->whereYear('created_at', $request->year);
				$data['year'] =  $request->year;
			}
			$data['daily_expense_reports'] = $query->get();
		}else{
			$data['daily_expense_reports'] = \App\RakeExpenseReport::all();
		}
		return view('dashboard.approvals.rake-expense-reports',$data);
	}

	public function approveRakePaymentReport($id){
		$data = array();
		$response = array();
		$daily_expense_report = \App\RakeExpenseReport::where('id',$id)->first();
		if(is_null($daily_expense_report)){
			$response['flag'] = false;
			$response['message'] = "daily_expense_report Not Found";
		}else{
			$user_id = \Auth::user()->id;
			if($daily_expense_report->first_approval_by == $user_id || $daily_expense_report->second_approval_by == $user_id || $daily_expense_report->third_approval_by == $user_id){
				$response['flag'] = true;
				$response['message'] = "You have already approved";
			}else{
				if($daily_expense_report->first_approval == 0){
					$daily_expense_report->first_approval = 1;
					$daily_expense_report->first_approval_by = $user_id;
					$msg = "First Approval of Daily Expense Report (".date('d-m-Y',strtotime($daily_expense_report->generated_at)).") is Done";
				}else if($daily_expense_report->second_approval == 0){
					$daily_expense_report->second_approval = 1;
					$daily_expense_report->second_approval_by = $user_id;
					$msg = "Second Approval of Daily Expense Report (".date('d-m-Y',strtotime($daily_expense_report->generated_at)).") is Done";
				}else if($daily_expense_report->third_approval == 0){
					$daily_expense_report->third_approval = 1;
					$daily_expense_report->third_approval_by = $user_id;
					$msg = "Third Approval of Daily Expense Report (".date('d-m-Y',strtotime($daily_expense_report->generated_at)).") is Done";
				}



				if($daily_expense_report->save()){

					/*-----push notification---*/
					$msg.=" By ".\Auth::user()->name;
					$recepients = \App\User::whereIn('role_id',array(1,5,6))->get();
					foreach ($recepients as $recepient) {
						if(!is_null($recepient->firebase_token) || $recepient->firebase_token != ""){
							if($recepient->role_id == 1){
								$type = "admin";
							}else if($recepient->role_id == 5){
								$type = "logistic_manager";
							}else if($recepient->role_id == 6){
								$type = "marketing_manager";
							}
							NotificationController::notify($msg,$recepient->firebase_token,$type);
						}	
					}
					/*-----push notification---*/


					$response['flag'] = true;
					$response['message'] = "Daily Expense Report Approved";
				}else{
					$response['flag'] = false;
					$response['message'] = "Failed to update";
				}
			}
		}
		return response()->json($response);

	}


	public function rejectRakePaymentReport(Request $request){
		$data = array();
		$response = array();
		$daily_expense_report = \App\RakeExpenseReport::where('id',$request->id)->first();
		if(is_null($daily_expense_report)){
			$response['flag'] = false;
			$response['message'] = "report Not Found";
		}else{
			$user_id = \Auth::user()->id;
			$report_rejections = new \App\ReportRejection();
			$report_rejections->type = "rake-payments";
			$report_rejections->report_id = $request->id;
			$report_rejections->reason = $request->reason;
			$report_rejections->rejected_by = $user_id;
			if($report_rejections->save()){
				$daily_expense_report->first_approval = 0;
				$daily_expense_report->first_approval_by = NULL;
				$daily_expense_report->second_approval = 0;
				$daily_expense_report->second_approval_by = NULL;
				$daily_expense_report->third_approval = 0;
				$daily_expense_report->third_approval_by = NULL;
				$daily_expense_report->final_approval = 0;
				$daily_expense_report->save();


				/*-----push notification---*/
				$msg = "Rake Expense Report (".date('d-m-Y',strtotime($daily_expense_report->generated_at)).") is Rejected";
				$msg.=" By ".\Auth::user()->name;
				$recepients = \App\User::whereIn('role_id',array(1,5,6))->get();
				foreach ($recepients as $recepient) {
					if(!is_null($recepient->firebase_token) || $recepient->firebase_token != ""){
						if($recepient->role_id == 1){
							$type = "admin";
						}else if($recepient->role_id == 5){
							$type = "logistic_manager";
						}else if($recepient->role_id == 6){
							$type = "marketing_manager";
						}
						NotificationController::notify($msg,$recepient->firebase_token,$type);
					}	
				}
				/*-----push notification---*/

				$response['flag'] = true;
				$response['message'] = "Daily Expense Report Approved";
			}else{
				$response['flag'] = false;
				$response['message'] = "Failed to update";
			}
		}
		return response()->json($response);

	}

	public function rakePaymentReportRejection($id){
		$data = array();
		$data['report_rejections'] = \App\ReportRejection::where('report_id',$id)->where('type','rake-payments')->get();
		return view('dashboard.approvals.rake-expense-report-rejections',$data);
	}


	public function dailyWarehousePaymentApprovals(Request $request){
		$data = array();
		if ($request->isMethod('post')){
			$query = \App\DailyWarehouseExpenseReport::query();
			if($request->month){
				$query->whereMonth('created_at', $request->month);
				$data['month'] = $request->month;
			}
			if($request->year){
				$query->whereYear('created_at', $request->year);
				$data['year'] =  $request->year;
			}
			$data['daily_expense_reports'] = $query->get();
		}else{
			$data['daily_expense_reports'] = \App\DailyWarehouseExpenseReport::all();
		}
		return view('dashboard.approvals.daily-warehouse-expense-reports',$data);
	}

	public function approveDailyWarehousePaymentReport($id){
		$data = array();
		$response = array();
		$daily_expense_report = \App\DailyWarehouseExpenseReport::where('id',$id)->first();
		if(is_null($daily_expense_report)){
			$response['flag'] = false;
			$response['message'] = "daily_expense_report Not Found";
		}else{
			$user_id = \Auth::user()->id;
			if($daily_expense_report->first_approval_by == $user_id || $daily_expense_report->second_approval_by == $user_id || $daily_expense_report->third_approval_by == $user_id){
				$response['flag'] = true;
				$response['message'] = "You have already approved";
			}else{
				if($daily_expense_report->first_approval == 0){
					$daily_expense_report->first_approval = 1;
					$daily_expense_report->first_approval_by = $user_id;
					$msg = "First Approval of Daily Expense Report (".date('d-m-Y',strtotime($daily_expense_report->generated_at)).") is Done";
				}else if($daily_expense_report->second_approval == 0){
					$daily_expense_report->second_approval = 1;
					$daily_expense_report->second_approval_by = $user_id;
					$msg = "Second Approval of Daily Expense Report (".date('d-m-Y',strtotime($daily_expense_report->generated_at)).") is Done";
				}else if($daily_expense_report->third_approval == 0){
					$daily_expense_report->third_approval = 1;
					$daily_expense_report->third_approval_by = $user_id;
					$msg = "Third Approval of Daily Expense Report (".date('d-m-Y',strtotime($daily_expense_report->generated_at)).") is Done";
				}



				if($daily_expense_report->save()){

					/*-----push notification---*/
					$msg.=" By ".\Auth::user()->name;
					$recepients = \App\User::whereIn('role_id',array(1,5,6))->get();
					foreach ($recepients as $recepient) {
						if(!is_null($recepient->firebase_token) || $recepient->firebase_token != ""){
							if($recepient->role_id == 1){
								$type = "admin";
							}else if($recepient->role_id == 5){
								$type = "logistic_manager";
							}else if($recepient->role_id == 6){
								$type = "marketing_manager";
							}
							NotificationController::notify($msg,$recepient->firebase_token,$type);
						}	
					}
					/*-----push notification---*/


					$response['flag'] = true;
					$response['message'] = "Daily Expense Report Approved";
				}else{
					$response['flag'] = false;
					$response['message'] = "Failed to update";
				}
			}
		}
		return response()->json($response);

	}


	public function rejectDailyWarehousePaymentReport(Request $request){
		$data = array();
		$response = array();
		$daily_expense_report = \App\DailyWarehouseExpenseReport::where('id',$request->id)->first();
		if(is_null($daily_expense_report)){
			$response['flag'] = false;
			$response['message'] = "daily_expense_report Not Found";
		}else{
			$user_id = \Auth::user()->id;
			$report_rejections = new \App\ReportRejection();
			$report_rejections->type = "daily-payments";
			$report_rejections->report_id = $request->id;
			$report_rejections->reason = $request->reason;
			$report_rejections->rejected_by = $user_id;
			if($report_rejections->save()){
				$daily_expense_report->first_approval = 0;
				$daily_expense_report->first_approval_by = NULL;
				$daily_expense_report->second_approval = 0;
				$daily_expense_report->second_approval_by = NULL;
				$daily_expense_report->third_approval = 0;
				$daily_expense_report->third_approval_by = NULL;
				$daily_expense_report->final_approval = 0;
				$daily_expense_report->save();


				/*-----push notification---*/
				$msg = "Daily Expense Report (".date('d-m-Y',strtotime($daily_expense_report->generated_at)).") is Rejected";
				$msg.=" By ".\Auth::user()->name;
				$recepients = \App\User::whereIn('role_id',array(1,5,6))->get();
				foreach ($recepients as $recepient) {
					if(!is_null($recepient->firebase_token) || $recepient->firebase_token != ""){
						if($recepient->role_id == 1){
							$type = "admin";
						}else if($recepient->role_id == 5){
							$type = "logistic_manager";
						}else if($recepient->role_id == 6){
							$type = "marketing_manager";
						}
						NotificationController::notify($msg,$recepient->firebase_token,$type);
					}	
				}
				/*-----push notification---*/

				$response['flag'] = true;
				$response['message'] = "Daily Expense Report Approved";
			}else{
				$response['flag'] = false;
				$response['message'] = "Failed to update";
			}
		}
		return response()->json($response);

	}

	public function dailyWarehousePaymentReportRejection($id){
		$data = array();
		$data['report_rejections'] = \App\ReportRejection::where('report_id',$id)->where('type','daily-warehouse-payments')->get();
		return view('dashboard.approvals.daily-warehouse-expense-report-rejections',$data);
	}

}
