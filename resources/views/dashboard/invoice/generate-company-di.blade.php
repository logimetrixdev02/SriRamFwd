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
				<li><a href="{{'/user/generated-invoices'}}">Generate Company DI</a></li>
				<li class="active">Generate Company DI</li>
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

			<div class="page-header">
				<h1>
					Generate Company DI
				</h1>
			</div><!-- /.page-header -->

			
			<form action="{{url('/user/save-company-di')}}" method="post" role="form" id="generateInvoiceForm" enctype="multipart/form-data">
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
										<label for="master_rake_id">Rake</label>
										<select class="form-control select2" name="master_rake_id" id="master_rake_id" onchange="getMasterRakeDetails(this.value)">>
											<option>Select Rake</option>
											@foreach($master_rakes as $master_rake)
											<option value="{{$master_rake->id}}" >{{$master_rake->name}}</option>
											@endforeach()
										</select>
										@if($errors->has('master_rake_id'))
										<span class="label label-danger">{{ $errors->first('master_rake_id') }}</span>
										@endif
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="product_company_id">{{__('messages.ProductCompany')}}</label>
										<select class="form-control select2" name="product_company_id" id="product_company_id">
										</select>
										<span class="label label-danger" id="add_product_company_id_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="dealer_id"> To Dealer</label>
										<select class="form-control select2" name="dealer_id" id="dealer_id" >
											@foreach($dealers as $dealer)
											<option value="{{$dealer->id}}" >{{$dealer->name}}({{$dealer->address1}})</option>
											@endforeach()
										</select>
										@if($errors->has('dealer_id'))
										<span class="label label-danger">{{ $errors->first('dealer_id') }}</span>
										@endif
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="invoice_number"> {{__('messages.Invoices')}}  {{__('messages.Number')}}</label>
										<input type="text" name="invoice_number" id="invoice_number" class="form-control" >
										@if($errors->has('invoice_number'))
										<span class="label label-danger">{{ $errors->first('invoice_number') }}</span>
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
								<div class="col-md-4">
									<div class="form-group">
										<label for="dc_date"> DC Date</label>
										<input type="text" name="dc_date" id="dc_date" class="form-control date-picker" readonly="">
										@if($errors->has('dc_date'))
										<span class="label label-danger">{{ $errors->first('dc_date') }}</span>
										@endif
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="document_no">Document No.</label>
										<input type="text" name="document_no" id="document_no" class="form-control">
										@if($errors->has('document_no'))
										<span class="label label-danger">{{ $errors->first('document_no') }}</span>
										@endif
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="due_date"> Due Date</label>
										<input type="text" name="due_date" id="due_date" class="form-control date-picker" readonly="">
										@if($errors->has('due_date'))
										<span class="label label-danger">{{ $errors->first('due_date') }}</span>
										@endif
									</div>
								</div>

								<div class="clearfix"></div>
								<div class="col-md-12">
									<div class="table table-responsive" style="max-height: 300px;">
										<table class="table table-condensed" id="product_details_table">
											<thead>
												<th>Description of Goods</th>
												<th>HSN/SAC</th>
												<th>Quantity</th>
												<th>Per</th>
												<th>Rate</th>
												<th>Total Amount <br>(Base Price)</th>
												<th></th>
											</thead>
											<tbody>
												<tr>
													<td>
														<select class="form-control select2" name="product_id" id="product_id" onchange="getHsnCodeAndType()">
														</select>
														@if($errors->has('product_id'))
														<span class="label label-danger">{{ $errors->first('product_id') }}</span>
														@endif
														<input type="hidden" name="product_name" id="product_name">
													</td>
													<td>
														<input type="text" class="form-control" name="product_hsn" id="product_hsn" readonly="">
														<input type="hidden" class="form-control" name="cgst" id="cgst">
														<input type="hidden" class="form-control" name="sgst" id="sgst">
														<input type="hidden" class="form-control" name="igst" id="igst">
														<input type="hidden" class="form-control" name="is_igst_applicable" id="is_igst_applicable">
													</td>
													<td>
														<input type="text" class="form-control" name="quantity" id="quantity" placeholder="Enter Quantity" oninput="this.value = this.value.replace(/[^0-9]/g,'')" onkeyup="showProductTotalAmount(1)">
														@if($errors->has('quantity'))
														<span class="label label-danger">{{ $errors->first('quantity') }}</span>
														@endif
													</td>
													<td>
														<select class="form-control select2" name="product_unit" id="product_unit">
															<option value="">{{__('messages.Unit')}} {{__('messages.Select')}}</option>
															@foreach($units as $unit)
															<option value="<?php echo $unit->id; ?>"><?php echo $unit->unit; ?></option>
															@endforeach
														</select>
													</td>
													<td>
														<input type="text" class="form-control" name="product_rate" id="product_rate" placeholder="Enter Product Rate" onkeyup="showProductTotalAmount(1)" oninput="this.value = this.value.replace(/[^0-9.]/g,'')">
													</td>
													<td>
														<input type="text" class="form-control" name="product_base_amount" id="product_base_amount" readonly="">
													</td>
													<td></td>
												</tr>
											</tbody>
										</table>

										<table class="table table-condensed" id="product_details_table">
											<thead>
												<th>Discount</th>
												<th>Taxable <br>Amount</th>
												<th>
													<table>
														<tr>
															<th colspan="2">CGST</th>
														</tr>
														<tr>
															<th>%</th>
															<th></th>
															<th>Amount</th>
														</tr>
													</table>
												</th>

												<th>
													<table>
														<tr>
															<th colspan="2">SGST</th>
														</tr>
														<tr>
															<th>%</th>
															<th></th>
															<th>Amount</th>
														</tr>
													</table>
												</th>
												


												<th id="igst_th" style="display: none;">
													<table>
														<tr>
															<th colspan="2">IGST</th>
														</tr>
														<tr>
															<th>%</th>
															<th></th>
															<th>Amount</th>
														</tr>
													</table>
												</th>

												<th>Secondary Freight</th>
												<th>TCS</th>

												<th>Total Amt</th>
												<th></th>
											</thead>
											<tbody>
												<tr>
													<td>
														<input type="text" class="form-control" name="discount" id="discount" onkeyup="showTaxableAmount()" required="required">
														@if($errors->has('discount'))
														<span class="label label-danger">{{ $errors->first('discount') }}</span>
														@endif
													</td>
													<td>
														<input type="text" class="form-control" name="taxable_amount" id="taxable_amount" readonly="">
													</td>
													
													
													<td>
														<table >
															<tr>
																<th id="cgst_percent" width="30%">%</th>
																<th></th>
																<th id="cgst_total_amount" width="70%"></th>
															</tr>
															<input type="hidden" name="cgst_amount" id="cgst_amount" >
														</table>
													</td>

													<td>
														<table >
															<tr>
																<th id="sgst_percent" width="30%">%</th>
																<th></th>
																<th id="sgst_total_amount" width="70%"></th>
															</tr>
															<input type="hidden" name="sgst_amount" id="sgst_amount">
														</table>
													</td>

													<td id="igst_td" style="display: none;">
														<table >
															<tr>
																<th id="igst_percent" width="30%">%</th>
																<th></th>
																<th id="igst_total_amount" width="70%"></th>
															</tr>
															<input type="hidden" name="igst_amount" id="igst_amount">
														</table>
													</td>

													<td>
														<input type="text" class="form-control" name="secondary_freight" id="secondary_freight" onkeyup="calculateTotal(this.value)" required="required">
														@if($errors->has('secondary_freight'))
														<span class="label label-danger">{{ $errors->first('secondary_freight') }}</span>
														@endif
													</td>

													<td>
														<input type="text" class="form-control" name="tcs" id="tcs" onkeyup="calculateTcs(this.value)" required="required">
														@if($errors->has('tcs'))
														<span class="label label-danger">{{ $errors->first('tcs') }}</span>
														@endif
													</td>

													<td>
														<input type="text" class="form-control" name="product_total_amount" id="product_total_amount" readonly="">
													</td>
													<td></td>
												</tr>
											</tbody>
										</table>

									</div>
								</div>

								<div class="pull-right">
									<a href="{{URL('/user/generate-invoice')}}" class="btn btn-default" >Reset</a>
									<button type="submit" id="saveInvoiceDetails" name="saveGenerateInvoiceForm" class="btn btn-primary">Generate</button>
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
			autoclose: true
		});
	});


	function getMasterRakeDetails(id){
		if(id == ""){
			swal('Error','Master Rake id is missing','warning');
		}else{
			$('.loading-bg').show();
			$.ajax({
				url: "{{url('/get-master-rake-details/')}}"+"/"+id,
				type: 'GET',
				success:function(data){
					console.log(data);
					$('.loading-bg').hide();
					if(data.flag){
						var company_option = "<option value='"+data.master_rake.product_company.id+"'>"+data.master_rake.product_company.name+"</option>"
						$('#product_company_id').html(company_option).trigger('change');

						if(data.master_rake.product_company.is_igst_applicable == 1){
							$('#is_igst_applicable').val(data.master_rake.product_company.is_igst_applicable);
							$('#igst_td').show();
							$('#igst_th').show();

						}else{
							$('#igst_td').hide();
							$('#igst_th').hide();
							$('#is_igst_applicable').val('');
						}


						var product_option = "<option value=''>Select Product</option>";
						$.each(data.master_rake.master_rake_products, function(i, value) {
							product_option += "<option value="+value.product_id+">"+value.product.name+"</option>";

						});
						console.log(product_option);

						$("select[name='product_id']").each(function() {
							$(this).html(product_option).trigger('change');
						});

					}else{
						swal('Error',data.message,'warning');
					}
				}
			});
		}
	}

	function getHsnCodeAndType(count) {
		var product_id = $("#product_id").val();
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
						$("#product_hsn").val(data.product_hsn_code.hsn_code);
						$("#cgst").val(data.product_hsn_code.cgst);
						$("#sgst").val(data.product_hsn_code.sgst);
						$("#igst").val(data.product_hsn_code.igst);

						$("#cgst_percent").text(data.product_hsn_code.cgst);
						$("#sgst_percent").text(data.product_hsn_code.sgst);
						$("#igst_percent").text(data.product_hsn_code.igst);
					} else {
						alert(data.message);
						return false;
					}
				}
			});
		}
	}

	function showProductTotalAmount(count) {
		var product_quantity 	= $("#quantity").val();
		var product_price 		= $("#product_rate").val();
		if (product_quantity == "") {
			$("#product_base_amount").val('');
		} else if (product_price == "") {
			$("#product_base_amount").val('');
		} else {
			total_amount = parseFloat(product_quantity)*parseFloat(product_price);
			$("#product_base_amount").val(total_amount);
		}
	}

	function showTaxableAmount() {
		var discount 	= $("#discount").val();
		var cgst 	= $("#cgst").val();
		var sgst 	= $("#sgst").val();
		var igst 	= $("#igst").val();
		var product_base_amount 		= $("#product_base_amount").val();
		if($("#quantity").val() == ""){
			$("#discount").val('');
			swal('Error','Please Enter Product Quantity','warning');
		}else if($("#product_rate").val() == ""){
			swal('Error','Please Enter Product Rate','warning');
		}else if($("#product_base_amount").val() == ""){
			swal('Error','Total Amount Not Set','warning');
		}else if (discount != "") {
			var taxable_amount = parseInt(product_base_amount) - parseInt(discount);
			var cgst_amount = (taxable_amount * parseFloat(cgst))/100;
			var sgst_amount = (taxable_amount * parseFloat(sgst))/100;
			var igst_amount = (taxable_amount * parseFloat(igst))/100;
			console.log(cgst_amount);
			$("#taxable_amount").val(taxable_amount);
			
			if($("#is_igst_applicable").val() == 1){
				$("#igst_amount").attr('value',igst_amount);
				$("#igst_total_amount").text(igst_amount);
			}else{

				$("#cgst_amount").attr('value',cgst_amount);
				$("#cgst_total_amount").text(cgst_amount);
				$("#sgst_amount").attr('value',sgst_amount);
				$("#sgst_total_amount").text(sgst_amount);

			}
			if($("#is_igst_applicable").val() == 1){
				$("#product_total_amount").val(Math.round(igst_amount + taxable_amount));
			}else{
				$("#product_total_amount").val(Math.round(cgst_amount + sgst_amount + taxable_amount));
			}
		} 
	}

	function calculateTotal(secondary_freght){
		if($("#product_total_amount").val() != "" && secondary_freght > 0){
			
			var discount 	= $("#discount").val();
			var cgst 	= $("#cgst").val();
			var sgst 	= $("#sgst").val();
			var igst 	= $("#igst").val();
			var product_base_amount 		= $("#product_base_amount").val();
			if($("#quantity").val() == ""){
				$("#discount").val('');
				swal('Error','Please Enter Product Quantity','warning');
			}else if($("#product_rate").val() == ""){
				swal('Error','Please Enter Product Rate','warning');
			}else if($("#product_base_amount").val() == ""){
				swal('Error','Total Amount Not Set','warning');
			}else if (discount != "") {
				var taxable_amount = parseInt(product_base_amount) - parseInt(discount);
				var cgst_amount = (taxable_amount * parseFloat(cgst))/100;
				var sgst_amount = (taxable_amount * parseFloat(sgst))/100;
				var igst_amount = (taxable_amount * parseFloat(igst))/100;
				console.log(cgst_amount);
				$("#taxable_amount").val(taxable_amount);

				if($("#is_igst_applicable").val() == 1){
					$("#igst_amount").attr('value',igst_amount);
					$("#igst_total_amount").text(igst_amount);
				}else{

					$("#cgst_amount").attr('value',cgst_amount);
					$("#cgst_total_amount").text(cgst_amount);
					$("#sgst_amount").attr('value',sgst_amount);
					$("#sgst_total_amount").text(sgst_amount);

				}
				if($("#is_igst_applicable").val() == 1){
					$("#product_total_amount").val(Math.round((igst_amount + taxable_amount)-secondary_freght));
				}else{
					$("#product_total_amount").val(Math.round((cgst_amount + sgst_amount + taxable_amount)-secondary_freght));
				}
			} 
		}else if(secondary_freght == "" || secondary_freght == 0){
			showTaxableAmount();

		}
	}



	function calculateTcs(tcs){
		if($("#product_total_amount").val() != ""){
			var discount 	= $("#discount").val();
			var cgst 	= $("#cgst").val();
			var sgst 	= $("#sgst").val();
			var igst 	= $("#igst").val();
			var secondary_freght 	= $("#secondary_freight").val();
			var product_base_amount 		= $("#product_base_amount").val();
			var taxable_amount = parseInt(product_base_amount) - parseInt(discount);
			var cgst_amount = (taxable_amount * parseFloat(cgst))/100;
			var sgst_amount = (taxable_amount * parseFloat(sgst))/100;
			var igst_amount = (taxable_amount * parseFloat(igst))/100;
			if(tcs  == "" ){
				var tcs 	= 0;
			}
			// alert(tcs);
			$("#taxable_amount").val(taxable_amount);

			if($("#is_igst_applicable").val() == 1){
				$("#igst_amount").attr('value',igst_amount);
				$("#igst_total_amount").text(igst_amount);
			}else{

				$("#cgst_amount").attr('value',cgst_amount);
				$("#cgst_total_amount").text(cgst_amount);
				$("#sgst_amount").attr('value',sgst_amount);
				$("#sgst_total_amount").text(sgst_amount);

			}
			if($("#is_igst_applicable").val() == 1){
				$("#product_total_amount").val(Math.round((igst_amount + taxable_amount)-secondary_freght + parseInt(tcs)));
			}else{
				$("#product_total_amount").val(Math.round((cgst_amount + sgst_amount + taxable_amount)-secondary_freght + parseInt(tcs)));
			}
		}
		else if(tcs == "" || tcs == 0){
			showTaxableAmount();
		}
	}
</script>

@endsection
@endsection
