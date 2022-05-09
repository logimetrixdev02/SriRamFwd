@extends('dashboard.layouts.app')
@section('title','Punch Invoice')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li><a href="{{'/user/invoices'}}">Invoices</a></li>
				<li class="active">Punch Invoice</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="page-header">
				<h1>
					Punch Invoice
				</h1>
			</div><!-- /.page-header -->

			

			<form action="" role="form" id="punchInvoiceForm" enctype="multipart/form-data">
				<div class="container">
					<div class="row">

						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">Invoice Punching Form</h3>
							</div>
							<div class="panel-body">
								<div class="col-md-4">
									<div class="form-group">
										<label for="company_id">Company</label>
										<select class="form-control select2" name="company_id" id="company_id">
											<option value="">Select Company</option>
											@foreach($companies as $company)
											<option value="{{$company->id}}">{{$company->name}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_company_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="retailer_id">Retailer</label>
										<select class="form-control select2" name="retailer_id" id="retailer_id">
											<option value="">Select Retailer</option>
											@foreach($retailers as $retailer)
											<option value="{{$retailer->id}}">{{$retailer->name." (".$retailer->address.")"}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_retailer_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="invoice_date">Invoice Date</label>
										<input type="text" name="invoice_date" id="invoice_date" class="form-control date-picker" readonly="">
										<span class="label label-danger" id="add_invoice_date_error" style="display: none;"></span>
									</div>
								</div>
								<div class="clearfix"></div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="invoice_number">Invoice Number</label>
										<input type="text" name="invoice_number" id="invoice_number" class="form-control">
										<span class="label label-danger" id="add_invoice_number_error" style="display: none;"></span>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="invoice_amount">Invoice amount</label>
										<input type="text" name="invoice_amount" id="invoice_amount" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g,'')">
										<span class="label label-danger" id="add_invoice_amount_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="invoice_remark">Invoice Remark</label>
										<input type="text" name="invoice_remark" id="invoice_remark" class="form-control">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="received_date">Received Amount Date</label>
										<input type="text" name="received_date" id="received_date" class="form-control date-picker" readonly="">
										<span class="label label-danger" id="add_invoice_number_error" style="display: none;"></span>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="received_amount">Received Amount</label>
										<input type="text" name="received_amount" id="received_amount" class="form-control">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="invoice_document">Upload Invoice</label>
										<input type="file" name="invoice_document" id="invoice_document" class="form-control">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="pull-right">
									<a href="{{URL('/user/punch-invoice')}}" class="btn btn-default" >Reset</a>
									<button type="button" id="saveInvoiceDetails" class="btn btn-primary">Submit</button>

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
		$('.date-picker').datepicker({
			format: 'dd/mm/yyyy',
			endDate: '+0d',
			autoclose: true
		});
		$('#saveInvoiceDetails').click(function(e){
			e.preventDefault();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			});
			var form_data = new FormData(this);
			form_data.append('company_id',$('#company_id').val());
			form_data.append('retailer_id',$('#retailer_id').val());
			form_data.append('invoice_date',$('#invoice_date').val());
			form_data.append('invoice_number',$('#invoice_number').val());
			form_data.append('invoice_amount',$('#invoice_amount').val());
			if ($('#invoice_remark').val() !== '') {
				form_data.append('invoice_remark',$('#invoice_remark').val());
			}
			if ($('#received_date').val() !== '') {
				form_data.append('received_date',$('#received_date').val());
			}
			if ($('#received_amount').val() !== '') {
				form_data.append('received_amount',$('#received_amount').val());
			}
			if ($('#invoice_document').val() !== '') {
				var invoice_doc = $('#invoice_document')[0].files[0];
				form_data.append('invoice_doc', invoice_doc);
			}
			$('.loading-bg').show();
			$.ajax({
				url: $('#punchInvoiceForm').attr('action'),
				method: 'POST',
				data: form_data,
				contentType: false,
				processData: false,
				success: function(data){
					$('.loading-bg').hide();
					console.log(data);
					if(!data.flag){
						showError('add_company_error',data.errors.company_id);
						showError('add_retailer_error',data.errors.retailer_id);
						showError('add_invoice_number_error',data.errors.invoice_number);
						showError('add_invoice_amount_error',data.errors.invoice_amount);
						showError('add_invoice_date_error',data.errors.invoice_date);
					}else{
						swal({
							title: "Success!",
							text: data.message,
							type: "success"
						}, function() {
							window.location.reload();
						});
					}
				}
			});
		});
	});

	function showError(id,error){
		if(typeof(error) === "undefined"){
			$('#'+id).hide();
		}else{
			$('#'+id).show();
			$('#'+id).text(error);
		}
	}

</script>

@endsection
@endsection
