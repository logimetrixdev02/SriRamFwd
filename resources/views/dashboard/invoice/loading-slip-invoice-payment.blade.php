@extends('dashboard.layouts.app')
@section('title','Party Invoice Payment')
@section('style')
{{Html::style("assets/css/bootstrap-datepicker3.min.css")}}
@endsection

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active"> Party Invoice Payment</li>
			</ul>
		</div>

		<div class="page-content">


			@if (session('success'))
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
			@endif
			@if (session('error'))
			<div class="alert alert-danger">
				{{ session('error') }}
			</div>
			@endif
			
			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue"> Party Invoice Payment</h3>

					<form action="" method="POST" role="form" id="FilterForm">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="dealer_id"> {{__('messages.Dealer')}}</label>
									<select class="form-control select2" name="dealer_id" id="dealer_id" >
										<option value=""> Select Dealer</option>
										@foreach($dealers as $dealer)
										<option value="{{$dealer->id}}" {{isset($dealer_id) && $dealer_id==$dealer->id ? "selected":""}}>{{$dealer->name}} ({{$dealer->address1}})</option>
										@endforeach()
									</select>
									@if ($errors->has('dealer_id'))
									<span class="label label-danger">{{ $errors->first('dealer_id') }}</span>
									@endif
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="retailer_id"> {{__('messages.Retailers')}}</label>
									<select class="form-control select2" name="retailer_id" id="retailer_id" >
										<option value=""> Select retailer </option>
										@foreach($retailers as $retailer)
										<option value="{{$retailer->id}}" {{isset($retailer_id) && $retailer_id==$retailer->id ? "selected":""}}>{{$retailer->name}} ({{$retailer->address}})</option>
										@endforeach()
									</select>
									@if ($errors->has('retailer_id'))
									<span class="label label-danger">{{ $errors->first('retailer_id') }}</span>
									@endif
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</form>


					</div>
				</div>


				<div class="clearfix">
					<div class="pull-right tableTools-container">
					</div>
				</div>
				<div class="table-header">
					Loading Slip Invoice Payment 
				</div>
				@if( (isset($dealer_id) && !is_null($dealer_id)) && (isset($retailer_id) && !is_null($retailer_id)))
				<div class="panel panel-primary">
					<div class="panel-body">
						<form action="{{URL('/user/save-loading-invoice-payment')}}" id="save_loading_invoice_payment" method="post">
							{{csrf_field()}}
							<input type="hidden" name="dealer_id" value="{{$dealer_id}}">
							<input type="hidden" name="retailer_id" value="{{$retailer_id}}">
							<input type="hidden" name="retailer_advance_balance" id="retailer_advance_balance" value="">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="">Party</label>
										<input type="text" class="form-control"  value="{{$retailerInfo->name}} ({{$retailerInfo->address}})" readonly="readonly">
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<span class="label label-danger"  style="display: none;"></span>
										<label for="">Bank Account Number</label>
										<select class="form-control checkIfValid select2" name="bank_account_id" id="bank_account_id">
											
											@foreach($bank_accounts as $bank_account)
											<option value="{{$bank_account->id}}">{{$bank_account->bank->name}} - {{$bank_account->account_number}}</option>
											@endforeach
										</select>
										<span class="label label-danger"  style="display: none;"></span>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="">Payment Mode</label>
										<select class="form-control checkIfValid select2" name="payment_mode" id="payment_mode" required="required">
											<option value="Cash">Cash</option>
											<option value="Cheque">Cheque</option>
											<option value="DD">DD</option>
											<option value="NEFT">NEFT</option>
											<option value="RTGS">RTGS</option>
										</select>
										<span class="label label-danger"  style="display: none;"></span>
									</div>
								</div>
							</div>
							<div class="row">
								
								<div class="col-md-2">
									<div class="form-group">
										<label for="">Payment Date</label>
										<input type="text" class="form-control checkIfValid date-picker" name="payment_date" id="payment_date" placeholder="Payment Date" required="required">
										<span class="label label-danger"  style="display: none;"></span>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="">Party Advance Blance</label>
										<input type="text" class="form-control " name="party_advance_blance" id="party_advance_blance" placeholder="Party Advance Blance" value="{{$retailer_advance_balances}}" readonly="readonly">
										<span class="label label-danger"  style="display: none;"></span>
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<label for="">Paid Amount</label>
										<input type="text" class="form-control checkIfValid" name="paid_amount" id="paid_amount" placeholder="Paid Amount" value="" required="required">
										<span class="label label-danger"  style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="">Payment Reference Number</label>
										<input type="text" class="form-control checkIfValid" name="bank_reference_number" id="bank_reference_number" placeholder="Payment Reference Number" required="required">
										<span class="label label-danger"  style="display: none;"></span>
									</div>
								</div>

								
							</div>
							<div class="row">
								<div class="col-md-12">
									<h4>Invoice List Of Party</h4>
									<table class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>SNo</th>
												<th>Invoice No</th>
												<th>Invoice Date</th>
												<th>Invoice Amount</th>
												<th>Remaining invoice Amount</th>
												<th>Amount Adjust in Invoice</th>
												<th>--</th>
											</tr>
											
										</thead>
										<tbody>
											@foreach($invoices as $key => $invoice)
											<tr>
												<td>{{$key+1}}</td>
												<td>{{$invoice->invoice_number}}</td>
												<td>{{$invoice->invoice_date}}</td>
												<td>{{$invoice->total}}</td>
												<td class="remaining_amount" id="remaining_amount_{{$invoice->id}}" data-id="{{$invoice->id}}">{{$invoice->remaining_amount}}</td>
												<td><input type="text" class="invoice_payment" name="invoice_payment[{{$invoice->id}}]" id="invoice_id_{{$invoice->id}}" value="" onkeyup="current_amount({{$invoice->id}})" onkeypress="return onlyCurrency(event)" /></td>
												<td></td>
											</tr>
											@endforeach
											<tr>
												<td colspan="3" id="invoices_complete">Total Remailning Invoice Amount</td>
												<td id="totalRemaining"></td>
												<td id="current_payments"></td>
												<td></td>
											</tr>
											<tr>
												<td colspan="3">Party Balance In Advance</td>
												<td id="partyAdvance"></td>
												<td></td>
												<td><input type="submit" name="submit" class="btn btn-primary" /></td>
											</tr>
										</tbody>
									</table>
								</div>
								
							</div>
						</form>
					</div>
				</div>
				@endif
				
				</div>




			</div><!-- /.page-content -->
		</div>
	</div><!-- /.main-content -->


	@section('script')

	{{ Html::script("assets/js/jquery.dataTables.min.js")}}
	{{ Html::script("assets/js/jquery.dataTables.bootstrap.min.js")}}
	{{ Html::script("assets/js/dataTables.buttons.min.js")}}
	{{ Html::script("assets/js/buttons.flash.min.js")}}
	{{ Html::script("assets/js/buttons.html5.min.js")}}
	{{ Html::script("assets/js/buttons.print.min.js")}}
	{{ Html::script("assets/js/buttons.colVis.min.js")}}
	{{ Html::script("assets/js/dataTables.select.min.js")}}
	{{ Html::script("assets/js/ace-elements.min.js")}}
	{{ Html::script("assets/js/ace.min.js")}}
	{{ Html::script("assets/js/bootstrap-datepicker.min.js")}}


	<script type="text/javascript">

		function current_amount(id){
			var camount = parseFloat($('#invoice_id_'+id).val());
			var ramount = parseFloat($('#remaining_amount_'+id).text());
			if(camount > ramount){
				$('#invoice_id_'+id).css({'border':'1px solid rgb(224 82 5)'});
			}else{
				$('#invoice_id_'+id).css({'border':'1px solid #b5b5b5'});
			}

		}
		function onlyCurrency(event){
	      if(event.which == 8 || event.which == 0){
	          return true;
	      }
	      if(event.which < 46 || event.which > 59) {
	          return false;
	          //event.preventDefault();
	      } // prevent if not number/dot
	      
	      if(event.which == 46 && $(this).val().indexOf('.') != -1) {
	          return false;
	          //event.preventDefault();
	      } // preven
	    }
		jQuery(function($) {
			var totalRemainingInvoice = 0;
			$('.remaining_amount').each(function(k,v){
				let invoice_amount = parseFloat($(v).text());
				totalRemainingInvoice = totalRemainingInvoice + invoice_amount; 	    	    
			});
			$('#totalRemaining').text(totalRemainingInvoice);

			$('.date-picker').datepicker({
				autoclose: true,
				todayHighlight: true
			})
			.next().on(ace.click_event, function(){
				$(this).prev().focus();
			});
			$('#paid_amount').keyup(function(){
  				var paid_amount = parseFloat($('#paid_amount').val());
  				var partyAdvance = parseFloat($('#party_advance_blance').val());
  				
  				if(!isNaN(paid_amount)){
  					let mypaid_amount = paid_amount + partyAdvance;
					$('.remaining_amount').each(function(k,v){
					   var invoice_amount = parseFloat($(v).text());
					    var invoice_id = $(v).data('id');
					    if(invoice_amount <= mypaid_amount && mypaid_amount != 0){
					        $('#invoice_id_'+invoice_id).val(invoice_amount);
					        mypaid_amount = mypaid_amount - invoice_amount;
					        //$('#invoice_id_'+invoice_id).attr('readonly',true);
					    }else if(invoice_amount > mypaid_amount && mypaid_amount != 0){

					       		$('#invoice_id_'+invoice_id).val(mypaid_amount);
					        	mypaid_amount = 0;

					       
					    }else if(mypaid_amount == 0){

					       		$('#invoice_id_'+invoice_id).val('');
					        	//$('#invoice_id_'+invoice_id).attr('readonly',false);
					       
					    }

					    var remaining_amount = parseFloat($('#totalRemaining').text());
					    let all_paid_amount = paid_amount + partyAdvance ;
						if(all_paid_amount >= remaining_amount){
							var advance =  all_paid_amount - remaining_amount;
							$('#partyAdvance').text(advance);
							$('#retailer_advance_balance').val(advance);
						}else{
							$('#partyAdvance').text(0);
							$('#retailer_advance_balance').val(0);
						}
					});

					

				}else{

					$('.remaining_amount').each(function(k,v){
					    var invoice_id = $(v).data('id');
					    $('#invoice_id_'+invoice_id).val('');
					    $('#invoice_id_'+invoice_id).attr('disabled',false);
					});

				}
				
				
			});


			$('#save_loading_invoice_payment').submit(function(){
				var is_valid = true;
				var paid_amount = parseFloat($('#paid_amount').val());
				var total = 0;

				$('.remaining_amount').each(function(k,v){
					var invoice_id = $(v).data('id');
					var remaining_amount = parseFloat($(v).text());
					var current_amount = parseFloat($('#invoice_id_'+invoice_id).val());
					console.log(current_amount);
					if(current_amount > remaining_amount ){
						is_valid = false;
						$('#invoice_id_'+invoice_id).css({'border':'1px solid rgb(224 82 5)'});
					}else{
						if(!isNaN(current_amount)){
							total = total  + current_amount;
						}
					}
				});


				if(is_valid == false){
					
					return false;
				}else if(total > paid_amount){

				swal('Oops',"paid amount not matched with invoice amount total.",'error');
				return false;
				}else{
					return true;
				}
			});
			

		});
	</script>
	<script type="text/javascript">
		function filter(){
			$('#invoice_id').val('');
			$('#FilterForm').submit();
		}
	</script>
	@endsection
	@endsection
