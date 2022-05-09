@extends('dashboard.layouts.app')
@section('title','Freight Payment')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li><a href="{{'/user/freight-payments'}}"> {{__('messages.FreightPayments')}} </a></li>
				<li class="active"> {{__('messages.FreightPayment')}}</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="page-header">
				<h1>
					{{__('messages.FreightPayment')}}
				</h1>
			</div><!-- /.page-header -->

			
			<form action="{{url('/user/pay-unloading-freight')}}" method="post" role="form" id="freightPaymentForm" onsubmit="return validateForm()">
				{{ csrf_field() }}
				<div class="container">
					<div class="row">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">  {{__('messages.FreightPayment')}} {{__('messages.Form')}} </h3>
							</div>
							<div class="panel-body">
								<div class="col-md-4">
									<div class="form-group">
										<label for="qr_data"> Unloading Slip</label>
										<input type="text" class="form-control" name="qr_data" id="qr_data" onchange="getLoadingSlipDetails(this.value)">
									</div>
								</div>

								<div class="clearfix"></div>

								<div id="details" style="display: none;">
									<input type="hidden" name="product_unloading_id" id="product_unloading_id">
									<div class="col-md-4">
										<div class="form-group">
											<label for="product_company"> {{__('messages.ProductCompany')}}</label>
											<input type="text" class="form-control" name="product_company" id="product_company" readonly="">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="product">{{__('messages.Product')}}</label>
											<input type="text" class="form-control" name="product" id="product" readonly="">
										</div>
									</div>
									
									<div class="clearfix"></div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="transporter"> {{__('messages.Transporter')}}</label>
											<input type="text" class="form-control" name="transporter" id="transporter" readonly="">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="truck_number"> {{__('messages.TruckNumber')}}</label>
											<input type="text" class="form-control" name="truck_number" id="truck_number" readonly="">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="quantity"> {{__('messages.Quantity')}}</label>
											<input type="text" class="form-control" name="quantity" id="quantity" readonly="">
										</div>
									</div>
									<div class="clearfix"></div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label for="freight_rate">{{__('messages.Freight')}}</label>
											<input type="text" class="form-control" name="freight_rate" id="freight_rate" readonly="">
										</div>
									</div>

									<div class="col-md-4" id="toll_tax">
										<div class="form-group">
											<label for="toll_tax_amount">Toll Tax Amount</label>
											<input type="text" class="form-control" name="toll_tax_amount" id="toll_tax_amount" value="0" onkeyup="reCalculateFreight(this.value)">
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="freight">  {{__('messages.Total')}} {{__('messages.Freight')}}</label>
											<input type="text" class="form-control" name="freight" id="freight" readonly="">
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="pull-right">
										<a href="{{URL('/user/generate-loading-slip-invoice')}}" class="btn btn-default" >Reset</a>
										<button type="button" id="saveInvoiceDetails" name="saveFreightPaymentForm" class="btn btn-primary">Save Freight Payment</button>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="freight-error" style="display: none;">
									<div class="col-md-offset-4">
										<span id="freight_error_span" style="font-size: 30px; color: red; text-align: center;"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div><!-- /.page-content -->
</div><!-- /.main-content -->
@section('script')

{{ Html::script("assets/js/ace-elements.min.js")}}
{{ Html::script("assets/js/ace.min.js")}}
{{ Html::script("assets/js/bootstrap-datepicker.min.js")}}

<script type="text/javascript">
	$(document).ready(function() {
		$("#qr_data").focus();
		$('.date-picker').datepicker({
			format: 'dd/mm/yyyy',
			endDate: '+0d',
			autoclose: true
		});
		
		$("#saveInvoiceDetails").click(function(event) {
			event.preventDefault();
			$(".loading-bg").show();
			$.ajax({
				url: $("#freightPaymentForm").attr('action'),
				type: 'POST',
				data: $("#freightPaymentForm").serialize(),
				success:function(data){
					console.log(data);
					$(".loading-bg").show();
					if (data.flag) {
						swal({
							title : "Success",
							text  : data.message,
							type  : "success"
						}, function(){
							window.location.reload();
						});
					} else {
						swal({
							title : "Error",
							text  : data.message,
							type  : "error"
						}, function(){
							window.location.reload();
						});
					}
				}
			})
			.done(function() {
				console.log("success");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
			
		});
	});

	function validateForm(){
		return true;
	}

	function getLoadingSlipDetails(qr_value){
		var url ="{{url('get-unloading-slip-details')}}";
		if (qr_value == "") {
			swal({
				title : 'Loading Slip Missing!',
				text  : 'Please Scan Unloading Slip QR Code',
				type  : 'error'
			}, function(){
				$("#qr_data").focus();
				$("#token_id").val('');
				$("#product_company").val('');
				$("#product").val('');
				$("#transporter").val('');
				$("#truck_number").val('');
				$("#quantity").val('');
				$("#freight").val('');
			});
		} else {
			$(".loading-bg").show();
			$.ajax({
				url: url+"/"+qr_value,
				type: 'GET',
				success:function(data){
					console.log(data);
					$(".loading-bg").hide();
					if (data.flag) {
						if(data.product_unloading.loading_slip_type == "1"){

							if(data.product_unloading.freight > 0){

								if(data.product_unloading.is_freight_paid == 0){
									$("#qr_data").attr('readonly', true);
									$("#product_unloading_id").val(data.product_unloading.id);
									$("#product_company").val(data.product_unloading.product_company_name);
									$("#product").val(data.product_unloading.product_name);
									$("#transporter").val(data.product_unloading.transporter_name);
									$("#truck_number").val(data.product_unloading.truck_number);
									$("#quantity").val(data.product_unloading.quantity);
									$("#freight_rate").val(data.product_unloading.freight);
									if(data.product_unloading.master_rake_id !== null){
										if(data.product_unloading.warehouse_id !== null){
											var freight = parseFloat(data.product_unloading.freight) * parseInt(data.product_unloading.recieved_quantity);
										}else{
											var freight = parseFloat(data.product_unloading.freight) * parseInt(data.product_unloading.quantity);

										}
									}else{
										var freight = parseFloat(data.product_unloading.freight) * parseInt(data.product_unloading.quantity);
									}
									$("#freight").val(freight);
									if(data.product_unloading.loading_slip_type == "2"){
										$("#toll_tax").show();
									}
									$("#details").show();
								}else{
									$("#freight_error_span").text("Payment Already Done");
									$(".freight-error").show();
								}
								

							}else{
								$("#freight_error_span").text("Freight is Not added in this slip");
								$(".freight-error").show();

							}
							
						}else{
							$("#freight_error_span").text("Invalid Direct Unloading slip");
							$(".freight-error").show();
						}
					} else {
						$("#freight_error_span").text(data.message);
						$(".freight-error").show();
					}
				}
			});
		}

		$('.date-picker').datepicker({
			format: 'dd/mm/yyyy',
			endDate: '+0d',
			autoclose: true
		});

	}
	function reCalculateFreight(value){
		if(value == ""){
			var toll_tax = 0;
		}else{
			var toll_tax = parseFloat(value);
			var freight = parseFloat($("#freight_rate").val()) * parseInt($("#quantity").val());
			$("#freight").val(freight + toll_tax);
		}

	}
</script>

@endsection
@endsection
