@extends('dashboard.layouts.app')
@section('title','Generate Loading Slip Invoice')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li><a href="{{'/user/generated-invoices'}}">{{__('messages.GeneratedInvoices')}}</a></li>
				<li class="active">{{__('messages.GenerateLoadingSlipInvoice')}}</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="page-header">
				<h1>
					{{__('messages.GenerateLoadingSlipInvoice')}}
				</h1>
			</div><!-- /.page-header -->

			
			<div class="container">
				<div class="row">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">{{__('messages.InvoiceGenerationForm')}}</h3>
						</div>
						<div class="panel-body">

							<div class="col-md-4">
								<div class="form-group">
									<label for="qr_data">{{__('messages.LoadingSlip')}}</label>
									<input type="text" class="form-control" name="qr_data" id="qr_data" onchange="getLoadingSlipDetails(this.value)">
								</div>
							</div>

							<div class="clearfix"></div>

							<form action="{{url('/user/generate-loading-slip-invoice')}}" method="post" role="form" id="generateInvoiceForm"  onsubmit="return validateForm()">
								{{ csrf_field() }}

								<div id="details"></div>



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
		$('#qr_data').focus();
		$('.date-picker').datepicker({
			format: 'dd/mm/yyyy',
			endDate: '+0d',
			autoclose: true
		});
	});

	function submitForm(){
		$('#generateInvoiceForm').submit();
	}
	function validateForm(){
		if($('#breakInvoice').prop('checked')){
			var total_quantity = $('#total_quantity').val();
			debugger;

			var entered_quantity = 0;
			debugger;

			$(".quantity_validator").each(function() {
				entered_quantity = parseInt(entered_quantity) +  parseInt($(this).val());
			});

			debugger;

			if(entered_quantity > total_quantity){
				swal('Error','Entered Quantity Should not be greater than total Quantity ('+total_quantity+')','error');
				return false;
			}else if(entered_quantity < total_quantity){
				swal('Error','Entered Quantity Should not be less than total Quantity ('+total_quantity+')','error');
				return false;
			}else{
				return true;

			}

		}else{
			var total_amount = parseInt($('#total_amount').val());
			if($('#qr_data').val() == ""){
				swal('Error','Please Select Loading Slip','error');
				return false;
			}else if($('#invoice_type_id').val() == ""){
				swal('Error','Please Select Invoice Type','error');
				return false;
			}else if($('#invoice_date').val() == ""){
				swal('Error','Please Enter Invoice Date','error');
				return false;
			}else if(total_amount >= 50000 && $('#eway_bill_no').val() == ""){
				swal('Error','Please Enter Eway Bill','error');
				return false;
			}else{
				return true;
			}
		}
	}

	function getLoadingSlipDetails(qr_data){
		
		if(qr_data != ""){

			$(".loading-bg").show();
			var url ="{{url('/user/loading-slip-details')}}";
			$.ajax({
				url: url+"/"+qr_data,
				type: 'GET',
				success:function(data){
					$(".loading-bg").hide();
					$('#details').html(data);
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
