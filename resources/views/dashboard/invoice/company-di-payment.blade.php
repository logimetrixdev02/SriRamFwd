@extends('dashboard.layouts.app')
@section('title','Company DI Payment')
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
				<li class="active"> Company DI Payment</li>
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
					<h3 class="header smaller lighter blue"> Company DI Payment</h3>

					<form action="" method="POST" role="form" id="FilterForm">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="product_company_id"> {{__('messages.ProductCompany')}}</label>
									<select class="form-control select2" name="product_company_id" id="product_company_id" >
										<option value=""> Select Product Company</option>
										@foreach($product_companies as $product_company)
										<option value="{{$product_company->id}}" {{isset($product_company_id) && $product_company_id==$product_company->id ? "selected":""}}>{{$product_company->name}}</option>
										@endforeach
									</select>
									@if ($errors->has('product_company_id'))
									<span class="label label-danger">{{ $errors->first('product_company_id') }}</span>
									@endif
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="dealer_id">Dealers</label>
									<select class="form-control select2" name="dealer_id" id="dealer_id" >
										<option value=""> Select dealer </option>
										@foreach($dealers as $dealer)
										<option value="{{$dealer->id}}" {{isset($dealer_id) && $dealer_id==$dealer->id ? "selected":""}}>{{$dealer->name}} ({{$dealer->address1}})</option>
										@endforeach
									</select>		
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

	@if(isset($invoices))
				<div class="clearfix">
					<div class="pull-right tableTools-container">
					</div>
				</div>
				<div class="table-header">
					Company DI Payment
				</div>



				
			
				<div class="panel panel-primary">
					<div class="panel-body">
						<form action="{{URL('/user/save-company-di-payment')}}" method="post" id="save_company_di_payment">
							{{csrf_field()}}
							
							<input type="hidden" name="product_company_id" value="{{$product_company_id}}">
							
							<div class="row">
								<div class="col-md-4">
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
								<div class="col-md-4">
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

								<div class="col-md-4">
									<div class="form-group">
										<label for="">Payment Date</label>
										<input type="text" class="form-control checkIfValid date-picker" name="payment_date" id="payment_date" placeholder="Payment Date" required="required">
										<span class="label label-danger"  style="display: none;"></span>
									</div>
								</div>
							</div>
							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="">Payment Reference Number</label>
										<input type="text" class="form-control checkIfValid" name="bank_reference_number" id="bank_reference_number" placeholder="Payment Reference Number" required="required">
										<span class="label label-danger"  style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="">Paid Amount</label>
										<input type="text" class="form-control checkIfValid" name="paid_amount" id="paid_amount" onkeypress="return onlyCurrency(event)" placeholder="Paid Amount" value="" required="required">
										<span class="label label-danger"  style="display: none;"></span>
									</div>
								</div>
								

								
								<div class="clearfix"></div>
							

							</div>
							
						
							
							<div class="row">
							<div class="col-md-12">
								<h4>Invoice List Of Party</h4>
								<table id="dynamic-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>SNo</th>
											<th>Invoice No</th>
											<th>Invoice Date</th>
											<th>Due Date</th>
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
												<td>{{$invoice->due_date}}</td>
												<td>{{$invoice->total}}</td>
												<td class="remaining_amount" id="remaining_amount_{{$invoice->id}}" data-id="{{$invoice->id}}">{{$invoice->remaining_amount}}</td>
												<td><input type="text" class="invoice_payment" name="invoice_payment[{{$invoice->invoice_number}}]" id="invoice_id_{{$invoice->id}}" value="" onkeyup="current_amount({{$invoice->id}})" onkeypress="return onlyCurrency(event)" /> <input type="hidden" name="invoice_type[{{$invoice->invoice_number}}]" value="{{$invoice->invoice_type}}"></td>
												<td></td>
										</tr>
										@endforeach
									</tbody>
								</table>
									
							</div>
							</div>
							
						
							
							<button type="submit" class="btn btn-primary">Save changes</button>
						</div>
					</form>

				</div>
				<!-- /.page-content -->
			</div>
			@endif
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
			


			$('#save_company_di_payment').submit(function(){
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
				}else if(total != paid_amount){
					swal('Oops',"paid amount not matched with invoice amount total.",'error');
					return false;
				}else{
					return true;
				}
			});
			

		});

			jQuery(function($) {
				$('#dynamic-table').DataTable( {
				"paging":   false,
					bAutoWidth: false,
					"aaSorting": [],
				} );

				$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true
				})
				.next().on(ace.click_event, function(){
					$(this).prev().focus();
				});

			})
		</script>
		<script type="text/javascript">
			function filter(){
				$('#invoice_id').val('');
				$('#FilterForm').submit();
			}
		</script>
		@endsection
		@endsection
