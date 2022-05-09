@extends('dashboard.layouts.app')
@section('title','Labour Payment')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li><a href="{{'/user/standardizations'}}"> Warehouse Transfer</a></li>
				<li class="active"> Loading {{__('messages.LabourPayment')}}</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="page-header">
				<h1>
					{{__('messages.LabourPayment')}}
				</h1>
			</div><!-- /.page-header -->

			
			<form action="{{url('/user/pay-warehouse-transfer-loading-labour')}}" method="post" role="form" id="labourPaymentForm">
				{{ csrf_field() }}
				<div class="container">
					<div class="row">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title"> {{__('messages.LabourPayment')}} {{__('messages.Form')}}</h3>
							</div>
							<div class="panel-body">
								<div class="col-md-4">
									<div class="form-group">
										<label for="qr_data"> {{__('messages.LabourPayment')}}</label>
										<input type="text" class="form-control" name="qr_data" id="qr_data" onchange="getLoadingSlipDetails(this.value)">
									</div>
								</div>

								<div class="clearfix"></div>

								<div id="details" style="display: none;">
									<input type="hidden" name="warehouse_transfer_loading_id" id="warehouse_transfer_loading_id">

									<div class="col-md-3">
										<div class="form-group">
											<label for="labour_name"> Labour Name</label>
											<input type="text" class="form-control" name="labour_name" id="labour_name" readonly="">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="uantity">Quantity</label>
											<input type="text" class="form-control" name="quantity" id="quantity" readonly="">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="labour_rate">Rate</label>
											<input type="text" class="form-control" name="labour_rate" id="labour_rate" readonly="">
										</div>
									</div>


									<div class="clearfix"></div>
									
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="amount_to_pay">{{__('messages.Amount')}} {{__('messages.To')}} {{__('messages.Pay')}}</label>
											<input type="text" class="form-control" name="amount_to_pay" id="amount_to_pay" readonly="">
										</div>
									</div>
									<div class="clearfix"></div>

									<div class="pull-right">
										<a href="{{URL('/user/pay-standardization')}}" class="btn btn-default" >Reset</a>
										<button type="button" id="saveLabourPayment" name="savelabourPaymentForm" class="btn btn-primary">Save Labour Payment</button>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="labour-payment-error" style="display: none;">
									<div class="col-md-offset-4">
										<span id="warehouse_loading_error_span" style="font-size: 30px; color: red; text-align: center;"></span>
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
		$("#saveLabourPayment").click(function(event) {
			event.preventDefault();
			$(".loading-bg").show();
			$.ajax({
				url: $("#labourPaymentForm").attr('action'),
				type: 'POST',
				data: $("#labourPaymentForm").serialize(),
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

	function getLoadingSlipDetails(qr_value){
		var url ="{{url('get-warehouse-transfer-loading-details')}}";
		if (qr_value == "") {
			swal({
				title : 'Loading Slip Missing!',
				text  : 'Please Scan Loading Slip QR Code',
				type  : 'error'
			}, function(){
				$("#qr_data").focus();
				$("#warehouse_transfer_loading_id").val('');
				$("#quantity").val('');
				$("#labour_rate").val('');
				$("#amount_to_pay").val('');
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
						
						$("#qr_data").attr('readonly', true);
						if(data.warehouse_loading.is_labour_paid == "1"){
							$("#details").html(`<div class="labour-payment-error" style="">
								<div class="col-md-offset-4">
								<span id="warehouse_loading_error_span" style="font-size: 30px; color: red; text-align: center;">Payment Already Done</span>
								</div>
								</div>`);
							$("#details").show();
						}else{

							$("#qr_data").attr('readonly', true);
							$("#warehouse_transfer_loading_id").val(data.warehouse_loading.id);
							$("#labour_name").val(data.warehouse_loading.labour_name);
							$("#quantity").val(data.warehouse_loading.quantity);
							$("#labour_rate").val(data.warehouse_loading.labour_rate);
							$("#amount_to_pay").val(parseFloat(data.warehouse_loading.labour_rate * data.warehouse_loading.quantity));
							$("#details").show();
						}
					} else {
						$("#warehouse_loading_error_span").text(data.message);
						$(".labour-payment-error").show();
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
</script>

@endsection
@endsection
