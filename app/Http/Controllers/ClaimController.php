<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class ClaimController extends Controller
{
	public function claimReport(Request $request)
	{
		$data =array();
		$data['product_companies']=\App\ProductCompany::where('is_active',1)->get();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'product_company_id' =>'required',
				)
			);
			if($validator->fails()){
				return redirect('user/claims')
				->withErrors($validator)
				->withInput();
			}else{
				$data['claims']=\App\ClaimFormat::where('is_active',1)->where('product_company_id',$request->product_company_id)->get();
			}
		}
		return view('dashboard.claim.claim-report',$data);
	}
	public function claimsData(Request $request)
	{
		$data = array();
		$data['companies']=\App\ProductCompany::get();
		return view('dashboard.claim.claim-form',$data);
	}
	public function postCLaimData( Request $request)
	{
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'company_id' =>'required',
				)
			);
			if($validator->fails()){
				return redirect('user/claims')
				->withErrors($validator)
				->withInput();
			}else{
				$heads = explode(',', $request->head);
				$rate = explode(',', $request->rate);
				foreach ($heads as $key => $head) {
					$claimformat = new \App\ClaimFormat();
					$claimformat->product_company_id=$request->company_id;
					$claimformat->claim_head=$head;
					$claimformat->rate=$rate[$key];
					$claimformat->save();
				}
				$response['flag'] = true;
				$response['message'] = "Claim Added Successfully";
			}
		}else{
			$response['flag'] = false;
			$response['error'] = "Something Went Wrong";
		}
		return response()->json($response);
	}

	public function generateClaim(Request $request)
	{
		$data=array();
		$data['master_rakes']=\App\MasterRake::where('is_active',1)->get();
		$data['companies']=\App\Company::where('is_active',1)->where('id',2)->get();
		return view('dashboard.claim.generate-claim',$data);
	}

	public function printClaim(Request $request)
	{
		$data=array();
		if ($request->isMethod('post')){
			$validator = \Validator::make($request->all(),
				array(
					'master_rake_id' =>'required',
				)
			);
			if($validator->fails()){
				return redirect('user/claims')
				->withErrors($validator)
				->withInput();
			}else{
				$product_company_id = \App\MasterRake::where('is_active',1)->where('id',$request->master_rake_id)->first('product_company_id');
				$data['product_company']=\App\ProductCompany::where('is_active',1)->where('id',$product_company_id->product_company_id)->first();
				$data['company'] =\App\Company::where('is_active',1)->where('id',$request->company_id)->first();
				$data['product']=\App\ProductLoading::where('master_rake_id',$request->master_rake_id)->first();
				$data['claims'] =\App\ClaimFormat::where('is_active',1)->where('product_company_id',$product_company_id->product_company_id)->get();
			}
		}
		// dd($data);
		return view('dashboard.claim.print-claim',$data);
	}
}