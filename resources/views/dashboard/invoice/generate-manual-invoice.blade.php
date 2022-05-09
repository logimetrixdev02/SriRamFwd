@extends('dashboard.layouts.app')
@section('title','Generate Invoice')

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
				<li class="active">{{__('messages.GeneratedInvoices')}}</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="page-header">
				<h1>
					{{__('messages.GeneratedInvoices')}}
				</h1>
			</div><!-- /.page-header -->

			
			<form action="{{url('/user/generated-invoice')}}" method="post" role="form" id="generateInvoiceForm" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="container">
					<div class="row">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">{{__('messages.InvoiceGenerationForm')}}</h3>
							</div>
							<div class="panel-body">
								<div class="col-md-4">
									<div class="form-group">
										<label for="invoice_type_id">{{__('messages.Invoices')}} {{__('messages.Type')}}</label>
										<select class="form-control select2" name="invoice_type_id" id="invoice_type_id">
											<option value="">{{__('messages.Invoices')}} {{__('messages.Type')}}  {{__('messages.Select')}}</option>
											@foreach($invoice_types as $invoice_type)
											<option value="{{$invoice_type->id}}">{{$invoice_type->invoice_type}}</option>
											@endforeach()
										</select>
										@if($errors->has('invoice_type_id'))
										<span class="label label-danger">{{ $errors->first('invoice_type_id') }}</span>
										@endif
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="company_id"> {{__('messages.company')}}</label>
										<select class="form-control select2" name="company_id" id="company_id">
											@foreach($companies as $company)
											<option value="{{$company->id}}"<?php echo $company->id == $acting_company?"selected":""; ?>>{{$company->name}}</option>
											@endforeach()
										</select>
										@if($errors->has('company_id'))
										<span class="label label-danger">{{ $errors->first('company_id') }}</span>
										@endif
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="invoice_date"> {{__('messages.Invoices')}}  {{__('messages.Date')}}</label>
										<input type="text" name="invoice_date" id="invoice_date" class="form-control date-picker" readonly="">
										@if($errors->has('invoice_date'))
										<span class="label label-danger">{{ $errors->first('invoice_date') }}</span>
										@endif
									</div>
								</div>
								<div class="clearfix"></div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="retailer_id"> {{__('messages.Retailers')}}</label>
										<select class="form-control select2" name="retailer_id" id="retailer_id">
											<option value="">{{__('messages.Retailers')}} {{__('messages.Select')}}</option>
											@foreach($retailers as $retailer)
											<option value="{{$retailer->id}}">{{$retailer->name." (".$retailer->address.")"}}</option>
											@endforeach()
										</select>
										@if($errors->has('retailer_id'))
										<span class="label label-danger">{{ $errors->first('retailer_id') }}</span>
										@endif
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="eway_bill_no">{{__('messages.ewayBillNo')}}</label>
										<input type="text" name="eway_bill_no" id="eway_bill_no" class="form-control">
										@if($errors->has('eway_bill_no'))
										<span class="label label-danger">{{ $errors->first('eway_bill_no') }}</span>
										@endif
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="invoice_remark">{{__('messages.Invoices')}}  {{__('messages.Remark')}}</label>
										<input type="text" name="invoice_remark" id="invoice_remark" class="form-control">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="dispatched_through">{{__('messages.DispatchedThrough')}}</label>
										<input type="text" name="dispatched_through" id="dispatched_through" class="form-control" placeholder="Enter Dispatched Through">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="destination">{{__('messages.Destination')}}</label>
										<input type="text" name="destination" id="destination" class="form-control" placeholder="Enter Destination">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="terms_of_delivery">{{__('messages.TermsOfDelivery')}}</label>
										<input type="text" name="terms_of_delivery" id="terms_of_delivery" class="form-control">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-12">
									<div class="table table-responsive" style="max-height: 300px;">
										<table class="table table-condensed" id="product_details_table">
											<thead>
												<!-- <th>#</th> -->
												<th>Description of Goods</th>
												<th>HSN/SAC</th>
												<th>Quantity</th>
												<th>Rate</th>
												<th>Per</th>
												<th>Amount</th>
												<th></th>
											</thead>
											<tbody>
												<tr id="row_1">
													<!-- <td>1</td> -->
													<td>
														<select class="form-control select2" name="product_id[]" id="product_id_1" onchange="getHsnCodeAndType(1)">
															<option value="">{{__('messages.Product')}} {{__('messages.Select')}}</option>
															@foreach($products as $product)
															<option value="{{$product->id}}">{{$product->name}}</option>
															@endforeach()
														</select>
													</td>
													<td>
														<input type="text" class="form-control" name="product_hsn[]" id="product_hsn_1" readonly="">
													</td>
													<td>
														<input type="text" class="form-control" name="quantity[]" id="quantity_1" placeholder="Enter Quantity" oninput="this.value = this.value.replace(/[^0-9]/g,'')" onkeyup="showProductTotalAmount(1)">
													</td>
													<td>
														<input type="text" class="form-control" name="product_rate[]" id="product_rate_1" placeholder="Enter Product Rate" onkeyup="showProductTotalAmount(1)" oninput="this.value = this.value.replace(/[^0-9.]/g,'')">
													</td>
													<td>
														<select class="form-control select2" name="product_unit[]" id="product_unit_1">
															<option value="">{{__('messages.Unit')}} {{__('messages.Select')}}</option>
															@foreach($units as $unit)
															<option value="<?php echo $unit->id; ?>"><?php echo $unit->unit; ?></option>
															@endforeach
														</select>
													</td>
													<td>
														<input type="text" class="form-control" name="product_total_amount[]" id="product_total_amount_1" readonly="">
													</td>
													<td></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-md-12">
									<input type="button" class="btn btn-success" value="Add More Product" onclick="addMoreRow()">
								</div>
								<div class="pull-right">
									<a href="{{URL('/user/generate-invoice')}}" class="btn btn-default" >Reset</a>
									<button type="submit" id="saveInvoiceDetails" name="saveGenerateInvoiceForm" class="btn btn-primary">{{__('messages.GenerateInvoices')}}</button>
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
	});

	function addMoreRow() {
		var length = $("#product_details_table tr").length;
		var count = length + 1;
		var newRow = `<tr id="row_`+count+`">
		<td>
		<select class="form-control select2" name="product_id[]" id="product_id_`+count+`" onchange="getHsnCodeAndType(`+count+`)">
		<option value="">Select Product</option>
		@foreach($products as $product)
		<option value="{{$product->id}}">{{$product->name}}</option>
		@endforeach()
		</select>
		</td>
		<td>
		<input type="text" class="form-control" name="product_hsn[]" id="product_hsn_`+count+`" readonly="">
		</td>
		<td>
		<input type="text" class="form-control" name="quantity[]" id="quantity_`+count+`" placeholder="Enter Quantity" oninput="this.value = this.value.replace(/[^0-9]/g,'')" onkeyup="showProductTotalAmount(`+count+`)">
		</td>
		<td>
		<input type="text" class="form-control" name="product_rate[]" id="product_rate_`+count+`" placeholder="Enter Product Rate" onkeyup="showProductTotalAmount(`+count+`)">
		</td>
		<td>
		<select class="form-control select2" name="product_unit[]" id="product_unit_`+count+`">
		<option value="">Select Unit</option>
		@foreach($units as $unit)
		<option value="<?php echo $unit->id; ?>"><?php echo $unit->unit; ?></option>
		@endforeach
		</select>
		</td>
		<td>
		<input type="text" class="form-control" name="product_total_amount[]" id="product_total_amount_`+count+`" readonly="">
		</td>
		<td><a href="javascript:void()" title="Delete Row" onclick="deleteRow(`+count+`)"><i class="fa fa-trash fa-2x" style="color:red;"></i></a></td>
		</tr>`;
		$('#product_details_table').append(newRow);
		$('.select2').select2();
	}

	function deleteRow(count) {
		$('#row_'+count).remove();
	}

	function getHsnCodeAndType(count) {
		var product_id = $("#product_id_"+count).val();
		if (product_id == "") {

		} else {
			$(".loading-bg").show();
			$.ajax({
				url: '{{url("get-hsn-code-by-product")}}/'+product_id,
				type: 'GET',
				success:function(data){
					$(".loading-bg").hide();
					console.log(data);
					if (data.flag) {
						$("#product_hsn_"+count).val(data.product_hsn_code.hsn_code);
					} else {
						alert(data.message);
						return false;
					}
				}
			});
		}
	}

	function showProductTotalAmount(count) {
		var product_quantity 	= $("#quantity_"+count).val();
		var product_price 		= $("#product_rate_"+count).val();
		if (product_quantity == "") {
			$("#product_total_amount_"+count).val('');
		} else if (product_price == "") {
			$("#product_total_amount_"+count).val('');
		} else {
			total_amount = parseFloat(product_quantity)*parseFloat(product_price);
			$("#product_total_amount_"+count).val(total_amount);
		}
	}

</script>

@endsection
@endsection
