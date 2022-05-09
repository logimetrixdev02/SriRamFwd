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

			
			<form action="{{url('/user/pay-returned-product-freight')}}" method="post" role="form" id="freightPaymentForm" onsubmit="return validateForm()">
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
										<label for="qr_data"> {{__('messages.LoadingSlip')}}</label>
										<input type="text" class="form-control" name="qr_data" id="qr_data" onchange="getLoadingSlipDetails(this.value)">
									</div>
								</div>

								<div class="clearfix"></div>

								<div id="details" style="display: none;">
									<input type="hidden" name="return_id" id="return_id">
									<div class="col-md-4">
										<div class="form-group">
											<label for="product_brand"> Product Brand</label>
											<input type="text" class="form-control" name="product_brand" id="product_brand" readonly="">
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
										<a href="{{URL('/user/pay-returned-product-freight')}}" class="btn btn-default" >Reset</a>
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
		var url ="{{url('get-return-slip-details')}}";
		if (qr_value == "") {
			swal({
				title : 'Loading Slip Missing!',
				text  : 'Please Scan Loading Slip QR Code',
				type  : 'error'
			}, function(){
				$("#qr_data").focus();
				$("#product_brand").val('');
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
						if(data.return_product.is_freight_paid == "1"){
							$("#freight_error_span").text("Freight Already Paid");
							$(".freight-error").show();
							return false;
						}else if(data.return_product.transporter_id == null){
							$("#freight_error_span").text("Transporter Not Available");
							$(".freight-error").show();
							return false;
						}else{

							$("#qr_data").attr('readonly', true);
							$("#return_id").val(data.return_product.id);
							$("#product_brand").val(data.return_product.product_brand.name);
							$("#product").val(data.return_product.product.name);
							$("#transporter").val(data.return_product.transporter.name);
							$("#truck_number").val(data.return_product.vehicle_number);
							$("#quantity").val(data.return_product.returned_quantity);
							$("#freight_rate").val(data.return_product.freight);
							var freight = parseFloat(data.return_product.freight) * parseInt(data.return_product.returned_quantity);
							$("#freight").val(freight);
							$("#toll_tax").show();
							$("#details").show();
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
