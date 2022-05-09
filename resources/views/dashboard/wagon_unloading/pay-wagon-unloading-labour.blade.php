@extends('dashboard.layouts.app')
@section('title','Wagon Unloading Labour Payment')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li><a href="{{'/user/pay-wagon-unloading-labours'}}"> Wagon Unloading Labour Payment</a></li>
				<li class="active"> Wagon Unloading Labour Payment</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="page-header">
				<h1>
					Wagon Unloading Labour Payment
				</h1>
			</div><!-- /.page-header -->

			
			<form action="{{url('/user/pay-wagon-unloading-labours')}}" method="post" role="form" id="labourPaymentForm">
				{{ csrf_field() }}
				<div class="container">
					<div class="row">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title"> Wagon Unloading Labour Payment {{__('messages.Form')}}</h3>
							</div>
							<div class="panel-body">
								<div class="col-md-4">
									<div class="form-group">
										<label for="qr_data"> {{__('messages.LabourSlip')}}</label>
										<input type="text" class="form-control" name="qr_data" id="qr_data" onchange="getWagonUnloadingDetails(this.value)">
									</div>
								</div>

								<div class="clearfix"></div>

								<div id="details" style="display: none;">
									<input type="hidden" name="unloading_slip_id" id="unloading_slip_id">

									<div class="col-md-4">
										<div class="form-group">
											<label for="rate"> Wagon Number</label>
											<input type="text" class="form-control" name="wagon_number" id="wagon_number" readonly="">
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="rate"> Wagon Rate</label>
											<input type="text" class="form-control" name="wagon_rate" id="wagon_rate" readonly="">
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label for="labour_name"> Labour Name</label>
											<input type="text" class="form-control" name="labour_name" id="labour_name" readonly="">
										</div>
									</div>


									
									<div class="clearfix"></div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="rate">Unloaded Quantity</label>
											<input type="text" class="form-control" name="quantity" id="quantity" readonly="">
										</div>
									</div>

									
									<div class="col-md-4">
										<div class="form-group">
											<label for="amount_to_pay">{{__('messages.Amount')}} {{__('messages.To')}} {{__('messages.Pay')}}</label>
											<input type="text" class="form-control" name="amount_to_pay" id="amount_to_pay" readonly="">
										</div>
									</div>
									<div class="clearfix"></div>

									<div class="pull-right">
										<a href="{{URL('/user/pay-wagon-unloading-labours')}}" class="btn btn-default" >Reset</a>
										<button type="button" id="saveLabourPayment" name="savelabourPaymentForm" class="btn btn-primary">Save Labour Payment</button>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="labour-payment-error" style="display: none;">
									<div class="col-md-offset-4">
										<span id="labour_payment_error_span" style="font-size: 30px; color: red; text-align: center;"></span>
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

	function getWagonUnloadingDetails(qr_value){
		var url ="{{url('get-wagon-unloading-details')}}";
		if (qr_value == "") {
			swal({
				title : 'Wagon unloading Slip Missing!',
				text  : 'Please Scan Wagon unloading Slip QR Code',
				type  : 'error'
			}, function(){
				$("#qr_data").focus();
				$("#labour_slip_id").val('');
				$("#amount_to_pay").val('');
				$("#wagon_number").val('');
				$("#wagon_rate").val('');
				$("#quantity").val('');
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
						if(data.unloading_slip.is_paid == "1"){
							$("#details").html(`<div class="labour-payment-error" style="">
								<div class="col-md-offset-4">
								<span id="unloading_slip_error_span" style="font-size: 30px; color: red; text-align: center;">Payment Already Done</span>
								</div>
								</div>`);
							$("#details").show();
						}else{

							$("#qr_data").attr('readonly', true);
							$("#unloading_slip_id").val(data.unloading_slip.id);
							$("#labour_name").val(data.unloading_slip.labour_name);
							$("#wagon_rate").val(data.unloading_slip.wagon_rate);
							$("#quantity").val(data.unloading_slip.quantity);
							$("#wagon_number").val(data.unloading_slip.wagon_number);
							$("#amount_to_pay").val(data.unloading_slip.wagon_rate);
							$("#details").show();
						}
					} else {
						$("#labour_payment_error_span").text(data.message);
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
