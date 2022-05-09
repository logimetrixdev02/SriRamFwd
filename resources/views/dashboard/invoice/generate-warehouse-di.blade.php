@extends('dashboard.layouts.app')
@section('title','Generate Warehouse Invoice')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li><a href="{{'/user/generated-invoices'}}">Generate Warehouse DI</a></li>
				<li class="active">Generate Warehouse DI</li>
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
					Generate Warehouse DI
				</h1>
			</div><!-- /.page-header -->

			
			<form action="{{url('/user/save-warehouse-di')}}" method="post" role="form" id="generateInvoiceForm" enctype="multipart/form-data" onsubmit="showLoader()">
				{{ csrf_field() }}
				<div class="container">
					<div class="row">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">Warehouse DI</h3>
							</div>
							<div class="panel-body">

								<div class="col-md-4">
									<div class="form-group">
										<label for="transfer_type">Transfer Type</label>
										<select class="form-control select2" name="transfer_type" id="transfer_type" onchange="handleTranferType(this.value)">
											<option value="1">Company To Dealer</option>
											<option value="2">Dealer To Dealer</option>
										</select>
										@if($errors->has('transfer_type'))
										<span class="label label-danger">{{ $errors->first('transfer_type') }}</span>
										@endif
									</div>
								</div>

								


								<div class="col-md-4" id="from_company_div">
									<div class="form-group">
										<label for="product_company_id">{{__('messages.ProductCompany')}}</label>
										<select class="form-control select2" name="product_company_id" id="product_company_id" onchange="handleProductCompany(this.value)">
											<option value="">Select Product Company</option>
											@foreach($product_companies as $product_company)
											<option value="{{$product_company->id}}" >{{$product_company->name}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_product_company_id_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4" id="from_dealer_div" style="display: none;">
									<div class="form-group">
										<label for="from_dealer">From Dealer</label>
										<select class="form-control select2" name="from_dealer" id="from_dealer">
											@foreach($dealers as $dealer)
											<option value="{{$dealer->id}}" >{{$dealer->name}} ({{$dealer->address1}})</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_from_dealer_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="product_brand_id">Product Brand</label>
										<select class="form-control select2" name="product_brand_id" id="product_brand_id" >
											<option value="">Select Product Company</option>
											@foreach($product_companies as $product_company)
											<option value="{{$product_company->id}}" >{{$product_company->name}}</option>
											@endforeach()
										</select>
										@if($errors->has('product_brand_id'))
										<span class="label label-danger">{{ $errors->first('product_brand_id') }}</span>
										@endif
									</div>
								</div>



								<div class="col-md-4">
									<div class="form-group">
										<label for="to_dealer"> To Dealer</label>
										<select class="form-control select2" name="to_dealer" id="to_dealer" >
											@foreach($dealers as $dealer)
											<option value="{{$dealer->id}}" >{{$dealer->name}} ({{$dealer->address1}})</option>
											@endforeach()
										</select>
										@if($errors->has('to_dealer'))
										<span class="label label-danger">{{ $errors->first('to_dealer') }}</span>
										@endif
									</div>
								</div>

								<div class="clearfix"></div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="transfer_type">Product</label>
										<select class="form-control select2" name="product_id" id="product_id" onchange="getHsnCodeAndType()" required="required">
											<option value="">Select Product</option>
											@foreach($products as $product)
											<option value="{{$product->id}}" >{{$product->name}}</option>
											@endforeach()
										</select>
										@if($errors->has('transfer_type'))
										<span class="label label-danger">{{ $errors->first('transfer_type') }}</span>
										@endif
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="transfer_type">HSN/SAC</label>
										<input type="text" class="form-control" name="product_hsn" id="product_hsn" readonly="">
									</div>
								</div>


								<div class="col-md-4">
									<div class="form-group">
										<label for="transfer_type">Unit</label>
										<select class="form-control select2" name="unit_id" id="unit_id" required="required">
											<option value="">Select Unit</option>
											@foreach($units as $unit)
											<option value="{{$unit->id}}" >{{$unit->unit}}</option>
											@endforeach()
										</select>
										@if($errors->has('unit_id'))
										<span class="label label-danger">{{ $errors->first('unit_id') }}</span>
										@endif
									</div>
								</div>

								

								<div class="clearfix"></div>

								<div class="col-md-12">
									<div class="row warehouse_div" id="row_1">
										<div class="col-md-4">
											<div class="form-group">
												<label for="warehouse_1"> Warehouse</label>
												<select class="form-control select2" name="warehouse[]" required="required" id="warehouse_1" onchange="getWarehouseDetails(this.value,1)">
													@foreach($warehouses as $warehouse)
													<option value="{{$warehouse->id}}" >{{$warehouse->name}}</option>
													@endforeach()
												</select>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="to_dealer"> Available Stock</label>
												<input type="text" class="form-control" name="" id="available_stock_1" placeholder="Enter Quantity" oninput="this.value = this.value.replace(/[^0-9]/g,'')" readonly="readonly">
											</div>
										</div>

										<div class="col-md-4">
											<div class="form-group">
												<label for="quantity_1"> Quantity</label>
												<input type="text" class="form-control quantity" name="quantity[]" id="quantity_1" placeholder="Enter Quantity" oninput="this.value = this.value.replace(/[^0-9]/g,'')" onkeyup="checkQuatity(this.value,1)">
											</div>
										</div>
									</div>
									
								</div>

								<div class="clearfix"></div>
								<div class="col-md-12" id="newWarehouseRow">
								</div>

								<div class="col-md-2">
									<a href="javascript:;" onclick="addMoreWarehouse()"><i class="fa fa-plus fa-2x"></i></a>
								</div>

								<div class="clearfix"></div>

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


								<input type="hidden" name="product_name" id="product_name">
								<input type="hidden" class="form-control" name="cgst" id="cgst">
								<input type="hidden" class="form-control" name="sgst" id="sgst">
								<input type="hidden" class="form-control" name="igst" id="igst">
								<input type="hidden" class="form-control" name="is_igst_applicable" id="is_igst_applicable">
								<input type="hidden" class="form-control" name="product_stock" id="product_stock">

								<div class="clearfix"></div>
								<div class="col-md-12">
									<div class="table table-responsive" style="max-height: 300px;">
										<table class="table table-condensed" id="product_details_table">
											<thead>
												<th>Rate</th>
												<th>Total Amount <br>(Base Price)</th>
												<th></th>
											</thead>
											<tbody>
												<tr>
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
														<input type="text" class="form-control" name="discount" id="discount" onkeyup="showTaxableAmount()">
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
									<a href="{{URL('/user/generate-warehouse-invoice')}}" class="btn btn-default" >Reset</a>
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

	function showLoader(){
		$('.loading-bg').show();
	}
	function handleTranferType(type) {
		if(type == 1){
			$('#from_dealer_div').hide();
			$('#from_company_div').show();
		}else{
			$('#from_dealer_div').show();
			$('#from_company_div').hide();
		}
	}
	function addMoreWarehouse(){
		count = $('.warehouse_div').length + 1;
		var html = `
		<div class="row warehouse_div" id="row_`+count+`">
		<div class="col-md-4">
		<div class="form-group">
		<label for="warehouse_1"> Warehouse</label>
		<select class="form-control select2" name="warehouse[]" required="required" id="warehouse_`+count+`" onchange="getWarehouseDetails(this.value,`+count+`)">
		@foreach($warehouses as $warehouse)
		<option value="{{$warehouse->id}}" >{{$warehouse->name}}</option>
		@endforeach()
		</select>
		</div>
		</div>

		<div class="col-md-2">
		<div class="form-group">
		<label for="to_dealer"> Available Stock</label>
		<input type="text" class="form-control" name="" id="available_stock_`+count+`" placeholder="Enter Quantity" oninput="this.value = this.value.replace(/[^0-9]/g,'')"  readonly="readonly">
		</div>
		</div>

		<div class="col-md-4">
		<div class="form-group">
		<label for="quantity_`+count+`"> Quantity</label>
		<input type="text" class="form-control quantity" name="quantity[]" id="quantity_`+count+`" placeholder="Enter Quantity" oninput="this.value = this.value.replace(/[^0-9]/g,'')" onkeyup="checkQuatity(this.value,`+count+`)">
		</div>
		</div>
		<div class="col-md-2">
		<div class="form-group">
		<a href="javascript:;" onclick="removeRow(`+count+`)"><i class="fa fa-minus fa-2x"></i></a>
		</div>
		</div>
		</div>
		`;

		$('#newWarehouseRow').append(html);
	}

	function removeRow(count){
		$('#row_'+count).remove();
	}

	function getWarehouseDetails(id,count){
		var transfer_type = $('#transfer_type').val();
		if(transfer_type == 1){
			if(id == ""){
				swal('Error','Warehouse id is missing','warning');
			}else if($('#product_company_id option:selected').val() == ""){
				swal('Error','Please Select Product Company','warning');
			}else if($('#product_id option:selected').val() == ""){
				swal('Error','Please Select Product','warning');
			}else{
				$('.loading-bg').show();
				$.ajax({
					url: "{{url('/get-company-stock-details/')}}"+"/"+$('#product_company_id option:selected').val()+'/'+id+"/"+$('#product_id option:selected').val(),
					type: 'GET',
					success:function(data){
						console.log(data);
						$('.loading-bg').hide();
						if(data.flag){
							$('#available_stock_'+count).val(data.stock);
							$('#product_stock').val(data.product_stock);
						}else{
							swal('Error',data.message,'warning');
						}
					}
				});
			}
		}else{
			if(id == ""){
				swal('Error','Warehouse id is missing','warning');
			}else if($('#from_dealer option:selected').val() == ""){
				swal('Error','Please Source Dealer','warning');
			}else if($('#product_id option:selected').val() == ""){
				swal('Error','Please Select Product','warning');
			}else{
				$('.loading-bg').show();
				$.ajax({
					url: "{{url('/get-dealer-stock-details/')}}"+"/"+$('#from_dealer option:selected').val()+'/'+id+"/"+$('#product_brand_id option:selected').val()+"/"+$('#product_id option:selected').val(),
					type: 'GET',
					success:function(data){
						console.log(data);
						$('.loading-bg').hide();
						if(data.flag){
							$('#available_stock_'+count).val(data.stock);
							// $('#available_stock_'+count).val(data.product_stock);
							$('#product_stock').val(data.product_stock);
						}else{
							swal('Error',data.message,'warning');
						}
					}
				});
			}
		}
	}

	function checkQuatity(value,count){
		console.log(value);
		// console.log($('#available_stock_'+count).val());
		$('#product_stock').val();
		if(parseInt(value) > parseInt($('#product_stock').val())){
			$('#quantity_'+count).val('');
			swal('Error','Entered Quantity('+parseInt(value)+') cannot be greater than total product available stock('+$('#product_stock').val()+')','error');
			return false;
		}

	}
	function handleProductCompany(id) {
		if (id == "") {
			$('#igst_td').hide();
			$('#igst_th').hide();
			$('#is_igst_applicable').val('');
			swal('Error','Please select Product Company','error');
			return false;
		} else {

			$('#product_brand_id').val(id).trigger('change');
			$(".loading-bg").show();
			$.ajax({
				url: '{{url("get-product-company-details")}}/'+id,
				type: 'GET',
				success:function(data){
					$(".loading-bg").hide();
					console.log(data);
					if (data.flag) {
						if(data.product_company.is_igst_applicable == 1){
							$('#is_igst_applicable').val(data.product_company.is_igst_applicable);
							$('#igst_td').show();
							$('#igst_th').show();

						}else{
							$('#igst_td').hide();
							$('#igst_th').hide();
							$('#is_igst_applicable').val('');
						}
					} else {
						alert(data.message);
						return false;
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
		var product_quantity = 0;
		$(".quantity").each(function() {
			product_quantity = parseInt(product_quantity) + parseInt($(this).val());
			console.log($(this).val());
		});

		console.log(product_quantity);
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
		var product_quantity = 0;
		$(".quantity").each(function() {
			product_quantity = parseInt(product_quantity) + parseInt($(this).val());
			console.log($(this).val());
		});

		if(product_quantity == 0){
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
			console.log(cgst_amount);
			$("#taxable_amount").val(taxable_amount);
			if($("#is_igst_applicable").val() == 1){
				$("#igst_amount").attr('value',igst_amount);
				$("#igst_total_amount").text("-"+igst_amount);
			}else{
				$("#cgst_amount").attr('value',cgst_amount);
				$("#cgst_total_amount").text('-'+cgst_amount);
				$("#sgst_amount").attr('value',sgst_amount);
				$("#sgst_total_amount").text("-"+sgst_amount);
			}
			if($("#is_igst_applicable").val() == 1){
				$("#product_total_amount").val(igst_amount + taxable_amount);
			}else{
				$("#product_total_amount").val(cgst_amount + sgst_amount + taxable_amount);
			}
		} 
	}

	function calculateTotal(secondary_freight){
		if($("#product_total_amount").val() != "" && secondary_freight > 0){
			
			var discount 	= $("#discount").val();
			var cgst 	= $("#cgst").val();
			var sgst 	= $("#sgst").val();
			var igst 	= $("#igst").val();
			var product_base_amount 		= $("#product_base_amount").val();
			var product_quantity = 0;
			$(".quantity").each(function() {
				product_quantity = parseInt(product_quantity) + parseInt($(this).val());
				console.log($(this).val());
			});

			if(product_quantity == 0){
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
				console.log(cgst_amount);
				$("#taxable_amount").val(taxable_amount);

				if($("#is_igst_applicable").val() == 1){
					$("#igst_amount").attr('value',igst_amount);
					$("#igst_total_amount").text("-"+igst_amount);
				}else{
					$("#cgst_amount").attr('value',cgst_amount);
					$("#cgst_total_amount").text('-'+cgst_amount);
					$("#sgst_amount").attr('value',sgst_amount);
					$("#sgst_total_amount").text("-"+sgst_amount);
				}
				if($("#is_igst_applicable").val() == 1){
					$("#product_total_amount").val(Math.round((igst_amount + taxable_amount)-secondary_freight));
				}else{
					$("#product_total_amount").val(Math.round((cgst_amount + sgst_amount + taxable_amount)-secondary_freight));
				}
			} 
		}else if(secondary_freight > $("#product_total_amount").val()){
			$('#secondary_freight').val(0);
			showTaxableAmount();
			swal('Error',"secondary freight cannot be greater than amount ",'error');

		}else if(secondary_freight == "" || secondary_freight == 0){
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
