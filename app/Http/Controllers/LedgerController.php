<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
/**
 * Invoice Controller
 */
class LedgerController extends Controller
{

	public function partyLedger(Request $request){

		$data = array();
		$data['retailers'] = \App\Retailer::where('is_active',1)->get();
		$data['allsessions'] = \App\Session::where('is_active',1)->get();
		
		if ($request->isMethod('post')){

			if($request->current_session != ""){
				$years = explode('-', $request->current_session);
				$start = $years[0].'-04-01';
				$end = $years[1].'-03-31';
				
			}else{
				$start = date('Y').'-04-01';
				$end = (date('Y')+1).'-03-31';
			}
			
			$data['ledgers'] = \App\PartyInvoiceLedger::where('retailer_id',$request->retailer_id)->whereBetween('created_at',[$start,$end])->orderBy('id','asc')->get();
			$opening = \App\PartyInvoiceLedger::where('retailer_id',$request->retailer_id)->whereDate('created_at','<',$start)->orderBy('created_at','desc')->first();
			if($opening != null){
				$opening_balance = $opening->balance;
			}else{
				$opening_balance = 0.00;
			}
			$data['current_retailer'] = \App\Retailer::where('id',$request->retailer_id)->where('is_active',1)->first();
			$data['retailer_id'] = $request->retailer_id;
			$data['current_session'] = $request->current_session;
			$data['start'] = $start;
			$data['end'] = $end;
			$data['opening_balance'] = $opening_balance;

		}
		
		return view('dashboard.ledgers.party-ledger',$data);
	}
}