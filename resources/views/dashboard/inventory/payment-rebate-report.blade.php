@extends('dashboard.layouts.app')
@section('title','Payment Rebate Report')
@section('content')
<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Payment Rebate</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Payment Rebate</h3>

					<form action="" method="POST" role="form">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="product_company_id">Product Company</label>
									<select class="form-control select2" name="product_company_id" id="product_company_id">
										<option value="">Select Product Company</option>
										@foreach($product_companies as $company)
										<option value="{{$company->id}}" {{isset($product_company_id) && $product_company_id==$company->id ? "selected":""}}>{{$company->name}}</option>
										@endforeach()
									</select>
									@if ($errors->has('product_company_id'))
									<span class="label label-danger">{{ $errors->first('product_company_id') }}</span>
									@endif
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="product_company_id">Dealer</label>
									<select class="form-control select2" name="dealer_id" id="dealer_id">
										<option value="">Select Product Company</option>
										@foreach($dealers as $dealer)
										<option value="{{$dealer->id}}" {{isset($dealer_id) && $dealer_id==$dealer->id ? "selected":""}}>{{$dealer->name}}({{$dealer->address1}})</option>
										@endforeach
									</select>
									@if ($errors->has('dealer_id'))
									<span class="label label-danger">{{ $errors->first('dealer_id') }}</span>
									@endif
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</div>
					</form>


				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">

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

					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Inventory
					</div>

					<div class="table-responsive">

						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Party Name</th>
										<th>Product</th>
										<th>Quantity</br>(M.T.)</th>
										<th>Invoice </br> Number</th>
										<th>Invoice Date</th>
										<th>DC Date</th>
										<th>Due Date</th>
										<th>Invoice </br>Amount</th>
										<th>Reciept No</th>
										<th>Payment Date</th>
										<th>Payment</br> Amount</th>
										
										<th>Approved</br> Credit</th>
										<th>Operation </br>Period</th>
										<th>Balance</br> Days</th>
										<th>Discount <br>
											Day/MTS
										</th>
										<th>Rate </br> per MTS</th>
										<th>Claim</br>Amount(IN Rs.)</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@foreach($company_dis as $invoice)
									<tr>
										<td>
											{{getModelById('Dealer',$invoice->dealer_id)->name}}
											({{getModelById('Dealer',$invoice->dealer_id)->address1}})
										</td>
										<td>{{$invoice->product}}</td>
										<td id="quantity_{{$invoice->id}}">
											{{$invoice->quantity/(1000/getModelById('Product',$invoice->product_id)->weight_in_kg)}}
										</td>
										<td>{{$invoice->invoice_number}}</td>
										<td>{{$invoice->invoice_date}}</td>
										<td>{{$invoice->invoice_date}}</td>
										<td>{{$invoice->due_date}}</td>
										<td id="total_{{$invoice->id}}">{{$invoice->total}}</td>
										<td>{{$invoice->bank_reference_number}}</td>
										<td>{{$invoice->payment_date}}</td>
										<td>{{$invoice->payment_amount}}</td>
										@if(is_null($invoice->approved_credit_days))
										
										<td>
											@php
												$start = strtotime($invoice->invoice_date);
												$end = strtotime($invoice->due_date);
												$no_of_days = ceil(abs($end - $start) / 86400);
											@endphp

											@if($invoice->is_paid)

											<input type="text" size="3" id="approved_credit_days_{{$invoice->id}}" onkeyup="calculateBalanceDays(this.value,{{$invoice->id}})" value="{{$no_of_days}}" readonly>
											
											@endif

										</td>
										<td id="operational_days_{{$invoice->id}}">@if($invoice->is_paid)
											@php

												 $start = strtotime($invoice->invoice_date);
												 $end = strtotime($invoice->payment_date);
												 echo $operational_days = ceil(abs($end - $start) / 86400);

											@endphp
										@endif</td>
										<td id="balance_days_{{$invoice->id}}">
											@if($invoice->is_paid)
												@php
													echo $balance_days = $no_of_days - $operational_days;
												@endphp
											@else


											@endif
										</td>
										<td>
											@if($invoice->is_paid)
											<input type="text" size="3" onkeyup="calculateClaim({{$invoice->id}})" id="discount_{{$invoice->id}}" >
											@endif
										</td>		
										<td id="rate_per_mts_{{$invoice->id}}"></td> 
										<td id="claim_amount_{{$invoice->id}}"></td>
										@else

										
										<td>{{$invoice->approved_credit_days}}</td>
										<td>{{$invoice->operation_period}}</td>
										<td>{{$invoice->balance_days}}</td>
										<td>{{$invoice->discount_perday_permts}}</td>
										<td>{{$invoice->rate_per_mts}}</td>
										<td>{{$invoice->claim_amount}}</td>
										@endif
										
										<td>
											@if(is_null($invoice->approved_credit_days))
											<a href="javascript:;" class="btn btn-primary" onclick="saveDiscountDetails({{$invoice->id}})">Save</a>
											@endif
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>




		</div><!-- /.page-content -->

	</div>
</div>
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
{{ Html::script("assets/js/mdtimepicker.min.js")}}
{{ Html::script("assets/js/bootstrap-datepicker.min.js")}}

<script type="text/javascript">
	function calculateClaim(invoice_id){
		var discount = $('#discount_'+invoice_id).val();
		console.log(discount);
		if(discount != ""){
			discount = parseFloat(discount);
			var rate_per_mts = discount * parseInt($('#balance_days_'+invoice_id).text());
			var claim_amount = rate_per_mts * parseInt($('#quantity_'+invoice_id).text());
			$('#rate_per_mts_'+invoice_id).text(rate_per_mts);
			$('#claim_amount_'+invoice_id).text(claim_amount);
		}else{
			$('#rate_per_mts_'+invoice_id).text('');
			$('#claim_amount_'+invoice_id).text('');
		}

	}
	function calculateBalanceDays(days,invoice_id){
		if(days != ""){
			var days = parseFloat(days);
			var balance_days = days -  parseInt($('#operational_days_'+invoice_id).text());
			$('#balance_days_'+invoice_id).text(balance_days);
			calculateClaim(invoice_id);
		}else{
			$('#balance_days_'+invoice_id).text('');
		}

	}

	function saveDiscountDetails(invoice_id){
		var invoice_type = $('#invoice_type').val();
		var approved_credit_days = $('#approved_credit_days_'+invoice_id).val()
		var operational_days = $('#operational_days_'+invoice_id).text()
		var balance_days = $('#balance_days_'+invoice_id).text();
		var discount = $('#discount_'+invoice_id).val();
		var rate_per_mts =  $('#rate_per_mts_'+invoice_id).text();
		var claim_amount =  $('#claim_amount_'+invoice_id).text();

		if(invoice_type == ""){
			swal('Error','Please Select Invoice Type','error');
		}else if(approved_credit_days == ""){
			swal('Error','Approved Credit Days should not be blank','error');
		}else if(operational_days == ""){
			swal('Error','Operational Days should not be blank','error');
		}else if(balance_days == ""){
			swal('Error','Balance Days should not be blank','error');
		}else if(discount == ""){
			swal('Error','Discount should not be blank','error');
		}else if(rate_per_mts == ""){
			swal('Error','Rate Per MTS should not be blank','error');
		}else if(claim_amount == ""){
			swal('Error','Claim Amount should not be blank','error');
		}else{
			swal({
				title: "Are you sure?",
				text: "You will not be able to edit this details!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'Yes, I am sure!',
				cancelButtonText: "No, cancel it!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm){
				if (isConfirm){
					$.ajaxSetup({
						headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
					});

					$.ajax({
						url: "{{url('/user/save-company-di-discount/')}}",
						type: 'POST',
						data:{invoice_id:invoice_id,approved_credit_days:approved_credit_days,operational_days:operational_days,balance_days:balance_days,discount:discount,rate_per_mts:rate_per_mts,claim_amount:claim_amount,invoice_type:invoice_type},
						success:function(data){
							if(data.flag){
								swal("Success", data.message, "success");
							}else{
								swal("Error", data.message, "error");
							}
						}
					});
				} else {
					swal("Cancelled", "Your Details not Saved :)", "error");
				}
			});
		}
	}
</script>
@endsection
@endsection

