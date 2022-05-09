<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
class PaymentController extends Controller
{


	public function saveCompanyDiPayment(Request $request){
		$is_valid = true;
		$msg = "";
		if($request->invoice_payment != ""){
			$paid_amount = $request->paid_amount;
			$bank_statement = new \App\BankStatement();
			$bank_statement->bank_id  = $request->bank_account_id;
			$bank_statement->type  = 'payment';
			$bank_statement->status  = 'debit';
			$bank_statement->amount  = $paid_amount;
			$bank_statement->transaction_date  = $this->dateConverter($request->payment_date);
			$bank_statement->reference_number  = $request->bank_reference_number;
			$bank_statement->mode  = $request->payment_mode;
			if($bank_statement->save()){
				$is_receipt = \App\BankStatement::where('type','payment')->orderBy('id','desc')->count();
				
				if($is_receipt == 0){
					$current_receipt_series  = 1;
				}else{
					$receipt = \App\BankStatement::where('type','payment')->skip(1)->take(1)->orderBy('id','desc')->first();
					if($receipt != null){
						$current_receipt_series  = $receipt->series + 1;
					}else{
						$current_receipt_series  = 0 + 1;
					}
					
					
				}

				$bank_statement->series = $current_receipt_series;
				$bank_statement->save();
			
				foreach ($request->invoice_payment as $key => $value) {
					
					if($key != "" && $value !=""){
						$invoice_number = $key;
						$invoicetype = $request->invoice_type[$key];
						if($invoicetype == 'warehouse_dis'){
							$invoice = \App\WarehouseDi::where('invoice_number',$invoice_number)->first();
						}else{
							$invoice = \App\CompanyDi::where('invoice_number',$invoice_number)->first();
						}
						
						if(is_null($invoice)){
							$is_valid = false;
							$msg = "Invoice Not found";
							
						}else if($value > $invoice->remaining_amount ){
							
							$is_valid = false;
							$msg = 'Amount is greater than remaining amount('.$invoice->remaining_amount.')';
						}else if($invoice->remaining_amount == 0){
							
							$is_valid = false;
							$msg = 'Invoice Already Paid';
						}else{

							$invoice->is_paid = 1;
							$invoice->payment_date = $this->dateConverter($request->payment_date);
							$invoice->payment_amount = $value;
							$invoice->remaining_amount = $invoice->remaining_amount - $value;
							$invoice->save();

							$payment = new \App\CompanyInvoicePayment();
							$payment->invoice_id = $invoice->id;
							$payment->invoice_number = $invoice->invoice_number;
							$payment->payment_amount = $request->paid_amount;
							$payment->payment_date = $this->dateConverter($request->payment_date);
							$payment->bank_account_id = $request->bank_account_id;
							$payment->bank_reference_number = $request->bank_reference_number;
							$payment->payment_mode = $request->payment_mode;
							$payment->payment_id = $bank_statement->id;

							$payment->save();

							$is_valid = true;
						}
					}
										
				}

				$bank_account = \App\BankAccount::find($request->bank_account_id);
				
				$ledger = \App\CompanyInvoiceLedger::where('dealer_id',$invoice->dealer_id)->where('product_company_id',$invoice->product_company_id)->orderBy('id','desc')->first();
				$perticular = "By ".$bank_account->bank->name; 
				if(!is_null($ledger)){
					$balance = $ledger->balance;
					$ledger = new \App\CompanyInvoiceLedger();
					$ledger->product_company_id = $invoice->product_company_id;
					$ledger->dealer_id = $invoice->dealer_id;
					$ledger->particular = $perticular;
					$ledger->type = 'Payment';
					$ledger->voucher_no = $bank_statement->series;
					$ledger->credit = 0;
					$ledger->debit = $request->paid_amount;
					$ledger->balance = $balance - $request->paid_amount;
					$ledger->save();


				}else{
					$ledger = new \App\CompanyInvoiceLedger();
					$ledger->product_company_id = $invoice->product_company_id;
					$ledger->dealer_id = $invoice->dealer_id;
					$ledger->particular = $perticular;
					$ledger->type = 'Payment';
					$ledger->voucher_no = $bank_statement->series;
					$ledger->credit = 0;
					$ledger->debit = $request->paid_amount;
					$ledger->balance = 0-$request->paid_amount;
					$ledger->save();
				}
			}

		}else{
			$is_valid = false;
			$msg = 'You have no any invoice.';
		}
			
		if($is_valid == true){
			
			return redirect()->back()->with('success','Payment Details Saved Successfully');
		}else{
			return redirect()->back()->with('error',$msg);
		}
	
		

	}

	public function saveWarehouseDiPayment(Request $request){
		$invoice = \App\WarehouseDi::where('id',$request->invoice_id)->first();
		if(is_null($invoice)){
			return redirect()->back()->with('error','Invoice Not found');
		}else{
			if($invoice->remaining_amount >= $request->paid_amount){

				$invoice->remaining_amount = $invoice->remaining_amount - $request->paid_amount;


				if($invoice->remaining_amount == 0){
					$invoice->is_paid = 1;
					$invoice->payment_date = $this->dateConverter($request->payment_date);
					$invoice->payment_amount = $request->paid_amount;
				}
				
				if($invoice->save()){
					

					$payment = new \App\CompanyInvoicePayment();
					$payment->invoice_id = $invoice->id;
					$payment->invoice_number = $invoice->invoice_number;
					$payment->payment_amount = $request->paid_amount;
					$payment->payment_date = $this->dateConverter($request->payment_date);
					$payment->bank_account_id = $request->bank_account_id;
					$payment->bank_reference_number = $request->bank_reference_number;
					$payment->payment_mode = $request->payment_mode;
					$payment->save();


					$ledger = \App\CompanyInvoiceLedger::where('dealer_id',$invoice->dealer_id)->where('product_company_id',$invoice->product_company_id)->orderBy('id','desc')->first();
					$perticular = "Credit Against Invoice ".$invoice->invoice_number." ( ".$invoice->product." ) qty ( ".$invoice->quantity." )";
					if(!is_null($ledger)){
						$balance = $ledger->balance;
						$ledger = new \App\CompanyInvoiceLedger();
						$ledger->product_company_id = $invoice->product_company_id;
						$ledger->dealer_id = $invoice->dealer_id;
						$ledger->particular = $perticular;
						$ledger->credit = 0;
						$ledger->debit = $request->paid_amount;
						$ledger->balance = $balance - $request->paid_amount;
						$ledger->save();


					}else{
						$balance = $ledger->balance;
						$ledger = new \App\CompanyInvoiceLedger();
						$ledger->product_company_id = $invoice->product_company_id;
						$ledger->dealer_id = $invoice->dealer_id;
						$ledger->particular = $perticular;
						$ledger->credit = 0;
						$ledger->debit = $request->paid_amount;
						$ledger->balance = $balance - $request->paid_amount;
						$ledger->save();
					}

					return redirect()->back()->with('success','Payment Details Saved Successfully');
				}else{
					return redirect()->back()->with('error','Something Went Wrong');
				}
			}else{
					return redirect()->back()->with('error','The paid amount is greater than the remaining Company Invoice.');
			}
		}
	}

	public function saveLoadingInvoicePayment(Request $request){
		$is_valid = true;
		$msg = "";
		if($request->invoice_payment != ""){
			$paid_amount = $request->paid_amount;
			$bank_statement = new \App\BankStatement();
			$bank_statement->bank_id  = $request->bank_account_id;
			$bank_statement->type  = 'receipt';
			$bank_statement->status  = 'credit';
			$bank_statement->amount  = $paid_amount;
			$bank_statement->transaction_date  = $this->dateConverter($request->payment_date);
			$bank_statement->reference_number  = $request->bank_reference_number;
			$bank_statement->mode  = $request->payment_mode;
			if($bank_statement->save()){
				
				$is_receipt = \App\BankStatement::where('type','receipt')->orderBy('id','desc')->count();
				
				if($is_receipt == 0){
					$current_receipt_series  = 1;
				}else{
					$receipt = \App\BankStatement::where('type','payment')->skip(1)->take(1)->orderBy('id','desc')->first();
					if($receipt != null){
						$current_receipt_series  = $receipt->series + 1;
					}else{
						$current_receipt_series  = 0 + 1;
					}
				}
				
				
				$bank_statement->series = $current_receipt_series;
				$bank_statement->save();
				foreach ($request->invoice_payment as $key => $value) {
					
					if($key != "" && $value !=""){
						$invoice_id = $key;
						$invoice = \App\LoadingSlipInvoice::where('id',$invoice_id)->first();
						if(is_null($invoice)){
							$is_valid = false;
							$msg = "Invoice Not found";
							
						}else if($value > $invoice->remaining_amount ){
							
							$is_valid = false;
							$msg = 'Amount is greater than remaining amount('.$invoice->remaining_amount.')';
						}else if($invoice->remaining_amount == 0){
							
							$is_valid = false;
							$msg = 'Invoice Already Paid';
						}else{
							$invoice->is_paid = 1;
							$invoice->remaining_amount = $invoice->remaining_amount - $value;
							if($invoice->save()){
								$payment = new \App\PartyInvoicePayment();
								$payment->invoice_id = $invoice_id;
								$payment->payment_amount = $value;
								$payment->payment_date = $this->dateConverter($request->payment_date);
								$payment->bank_account_id = $request->bank_account_id;
								$payment->bank_reference_number = $request->bank_reference_number;
								$payment->payment_mode = $request->payment_mode;
								$payment->receipt_id = $bank_statement->id;
								$payment->save();
								$is_valid = true;
							}else{
								$is_valid = false;
								$msg = 'Something Went Wrong';
							}
						}
					
					}
				}

				$bank_account = \App\BankAccount::find($request->bank_account_id);
				
				$ledger = \App\PartyInvoiceLedger::where('retailer_id',$request->retailer_id)->orderBy('id','desc')->first();
				$perticular = "By ".$bank_account->bank->name;
				if(!is_null($ledger)){
					$balance = $ledger->balance;
					$ledger = new \App\PartyInvoiceLedger();
					$ledger->retailer_id = $invoice->retailer_id;
					$ledger->particular = $perticular;
					$ledger->credit = $paid_amount;
					$ledger->type = 'Receipt';
					$ledger->debit = 0;
					$ledger->balance = $balance - $paid_amount;
					$ledger->against = $bank_statement->series;
					$ledger->save();


				}else{
					$ledger = new \App\PartyInvoiceLedger();
					$ledger->retailer_id = $invoice->retailer_id;
					$ledger->particular = $perticular;
					$ledger->credit = $paid_amount;
					$ledger->type = 'Receipt';
					$ledger->debit = 0;
					$ledger->balance = 0 - $paid_amount;
					$ledger->against = $bank_statement->series;
					$ledger->save();
				}

			}else{
				$is_valid = false;
				$msg = 'You have no any invoice.';
			}
			
			if($is_valid == true){
				$new_retailer_advance_balance = $request->retailer_advance_balance;
				if($new_retailer_advance_balance != ""){
					
					$retailer_advance_balances = \DB::table('retailer_advance_balances')->updateOrInsert(['retailer_id'=> $request->retailer_id, 'dealer_id'=>$request->dealer_id,'amount'=>$new_retailer_advance_balance]);
					
					
				}
				return redirect()->back()->with('success','Payment Details Saved Successfully');
			}else{
				return redirect()->back()->with('error',$msg);
			}
		}else{
			return redirect()->back()->with('error','Something went Wrong !!');
		}
		
		

	}

	public function loadingSlipInvoicePaymentDetails($invoice_id)
	{
		$data = array();
		$data['payments'] = \App\PartyInvoicePayment::where('invoice_id',$invoice_id)->with('bank_account')->get();
		return view('dashboard.invoice.loading-slip-invoice-payment-details',$data);
	}

	public function saveCompanyDiDiscount(Request $request){
		$response = array();
		if($request->invoice_type == 'company_dis'){
			$invoice = \App\CompanyDi::where('id',$request->invoice_id)->first();
		}else{
			$invoice = \App\WarehouseDi::where('id',$request->invoice_id)->first();
		}
		
		if(is_null($invoice)){
			$response['flag'] = false;
			$response['message'] = "Invoice Not found";
		}else if(!is_null($invoice->approved_credit_days)){
			$response['flag'] = false;
			$response['message'] = "Invoice Discount Saved Already";
		}else{
			$invoice->is_paid = 1;
			$invoice->approved_credit_days = $request->approved_credit_days;
			$invoice->operation_period = $request->operational_days;
			$invoice->balance_days = $request->balance_days;
			$invoice->discount_perday_permts = $request->discount;
			$invoice->rate_per_mts = $request->rate_per_mts;
			$invoice->claim_amount = $request->claim_amount;

			if($invoice->save()){
				$response['flag'] = true;
				$response['message'] = "Discount Details Saved Successfully";
			}else{
				$response['flag'] = false;
				$response['message'] = "Something Went Wrong";
			}
		}
		return response()->json($response);

	}

	public function dateConverter($date)
	{
		$temp_date = explode('/', $date);
		$new_date = $temp_date[2]."-".$temp_date[0]."-".$temp_date[1];
		return $new_date;
	}
}

?>