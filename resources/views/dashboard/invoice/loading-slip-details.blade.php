@if($status)

@php
$product = getModelById('Product',$product_loading->product_id);
$retailer = getModelById('Retailer',$product_loading->retailer_id);
@endphp

<input type="hidden" name="loading_slip" value="{{$product_loading->id}}">
<input type="hidden" name="token_id" value="{{$product_loading->token_id}}">
<input type="hidden" id="total_quantity" value="{{$product_loading->quantity}}">
<input type="hidden" id="token_rate" value="{{$product_loading->token->rate}}">
<div class="col-md-4">
	<div class="form-group">
		<label for="invoice_type_id">Invoice Type</label>
		<select class="form-control select2" name="invoice_type_id" id="invoice_type_id">
			<option value="">Select Invoice Type</option>
			@foreach($invoice_types as $invoice_type)
			<option value="{{$invoice_type->id}}" {{$invoice_type->id == $product->invoice_type_id ? "selected":""}}>{{$invoice_type->invoice_type}}</option>
			@endforeach()
		</select>
		@if($errors->has('invoice_type_id'))
		<span class="label label-danger">{{ $errors->first('invoice_type_id') }}</span>
		@endif
	</div>
</div>
<div class="col-md-4">
	<div class="form-group">
		<label for="company_id">Company</label>
		<select class="form-control select2" name="company_id" id="company_id">
			@foreach($companies as $company)
			<option value="{{$company->id}}" {{isset($product_loading->token) && $product_loading->token->company_id == $company->id ? "selected":""}}>{{$company->name}}</option>
			@endforeach()
		</select>
		@if($errors->has('company_id'))
		<span class="label label-danger">{{ $errors->first('company_id') }}</span>
		@endif
	</div>
</div>

<div class="col-md-4">
	<div class="form-group">
		<label for="invoice_date">Invoice Date</label>
		<input type="text" name="invoice_date" id="invoice_date" class="form-control date-picker" readonly="" value="{{date('d/m/Y')}}">
		@if($errors->has('invoice_date'))
		<span class="label label-danger">{{ $errors->first('invoice_date') }}</span>
		@endif
	</div>
</div>
<div class="clearfix"></div>

<div class="col-md-4">
	<div class="form-group">
		<label for="invoice_remark">Invoice Remark</label>
		<input type="text" name="invoice_remark" id="invoice_remark" class="form-control">
	</div>
</div>
<div class="col-md-4">
	<div class="form-group">
		<label for="dispatched_through">Dispatched Through</label>
		<input type="text" name="dispatched_through" id="dispatched_through" class="form-control" placeholder="Enter Dispatched Through">
	</div>
</div>
<div class="col-md-4">
	<div class="form-group">
		<label for="destination">Destination</label>
		<input type="text" name="destination" id="destination" class="form-control" placeholder="Enter Destination" value="{{$retailer->address}}">
	</div>
</div>


<div class="col-md-4">
	<div class="form-group">
		<label for="terms_of_delivery">Terms of Delivery</label>
		<input type="text" name="terms_of_delivery" id="terms_of_delivery" class="form-control" placeholder="Terms of Delivery">
	</div>
</div>

<div class="clearfix"></div>


<div class="col-md-8">
	<div class="row">
		<div class="col-xs-4">
			<label>
				Break Invoice?
			</label>
		</div>

		<div class="col-xs-2">
			<label>
				<input class="ace ace-switch ace-switch-4 btn-flat" type="checkbox" value="1" onchange="handleInvoiceBreakChange()" id="breakInvoice" >
				<span class="lbl"></span>
			</label>
		</div>
		<div class="invoice_count_div" style="display: none;">
			<div class="col-xs-4">
				<label>
					No. of Invoices?
				</label>
			</div>

			<div class="col-xs-2">
				<label>
					<select class="form-control select2" name="invoice_count" id="invoice_count" onchange="handleInvoiceNumbers(this.value)">
						<option value="0">Select Number</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
					</select>
				</label>
			</div>
		</div>

	</div>
</div>
<div class="clearfix"></div>
<br>

<div class="single-invoice-div" style="background: rgb(229, 235, 228)">
	<div class="col-md-4">
		<div class="form-group">
			<label for="retailer_id">Retailer</label>
			<select class="form-control select2" name="retailer_id" id="retailer_id">
				<option value="{{$retailer->id}}" selected>{{$retailer->name}} ({{$retailer->address}})</option>
			</select>
			@if($errors->has('retailer_id'))
			<span class="label label-danger">{{ $errors->first('retailer_id') }}</span>
			@endif
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label for="eway_bill_no">e-Way Bill Number</label>
			<input type="text" name="eway_bill_no" id="eway_bill_no" class="form-control">
			@if($errors->has('eway_bill_no'))
			<span class="label label-danger">{{ $errors->first('eway_bill_no') }}</span>
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
					<th>Rate</th>
					<th>Discount/<br>freight</th>
					<th>TCS</th>
					<th>Per</th>
					<th>Amount</th>
					<th></th>
				</thead>
				<tbody>

					<tr id="row_1">
						<td>
							{{$product->name}}
						</td>
						<td>
							{{$product->hsn_code}}
						</td>
						<td>
							{{$product_loading->quantity}}
						</td>
						<td>
							{{$product_loading->token->rate}}
						</td>
						<td><input type="number" name="freight_discount" size="4" value="0"></td>
						<td><input type="number" name="tcs" size="4" value="0" id="tcs_id"  onkeyup="caltcs(this.value)"></td>
						<td>
							{{$product_loading->unit_name}}
						</td>
						<td>
							<!-- {{$product_loading->token->rate * $product_loading->quantity}}>  -->
							<input type="number" value="{{$product_loading->token->rate * $product_loading->quantity}}" id="total" readonly=""  > 
						</td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<div class="pull-right">
				<a href="{{URL('/user/generate-loading-slip-invoice')}}" class="btn btn-default" >Reset</a>
				<button type="button" onclick="submitForm()" class="btn btn-primary">Save & Print Invoice</button>
			</div>
		</div>
	</div>
</div>
<div class="multiple-invoice-div" style="display: none;">
</div>
@else
<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<strong>{{$message}}</strong>
</div>
@endif
<script type="text/javascript">
	$(document).ready(function() {
		$('.select2').select2({
			allowClear: true
		});
	});
	function handleInvoiceBreakChange(){
		if($('#breakInvoice').prop('checked')){
			$('.single-invoice-div').hide();
			$('.invoice_count_div').show();
			$('.multiple-invoice-div').show();
			$('#generateInvoiceForm').attr('action',"{{url('/user/generate-multiple-loading-slip-invoice')}}");

		}else{
			$('.single-invoice-div').show();
			$('.invoice_count_div').hide();
			$('.multiple-invoice-div').hide();
			$('.multiple-invoice-div').html('');
			$('#generateInvoiceForm').attr('action',"{{url('/user/generate-loading-slip-invoice')}}");
		}
	}
	function handleInvoiceNumbers(value){
		if(handleInvoiceNumbers){
			var newHtml = '';
			for (var i = 0; i < value; i++) {
				newHtml+= createSingleInvoice(i);

				$('.select2').select2({
					allowClear: true
				});

			}
			newHtml+= `	<div class="pull-right">
			<a href="{{URL('/user/generate-loading-slip-invoice')}}" class="btn btn-default" >Reset</a>
			<button type="button" onclick="submitForm()" class="btn btn-primary">Save & Print Invoice</button>
			</div>`;
			$('.multiple-invoice-div').html(newHtml);
		}
	}
	function createSingleInvoice(count){
		@if($status)
		var html = `
		<div class="row" id="row`+count+`" style="margin-top:10px;background: rgb(229, 235, 228)">
		<div class="col-md-4">
		<div class="form-group">
		<label for="retailer_id">Retailer</label>
		<select class="form-control select2" name="retailer_id[]" >
		@foreach($retailers as $current_retailer)
		<option value="{{$current_retailer->id}}">{{$current_retailer->name}} ({{$current_retailer->address}})</option>
		@endforeach
		</select>
		<span class="label label-danger"></span>
		</div>
		</div>
		<div class="col-md-4">
		<div class="form-group">
		<label for="eway_bill_no">e-Way Bill Number</label>
		<input type="text" name="eway_bill_no[]" id="eway_bill_no" class="form-control">
		<span class="label label-danger"></span>
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
		<th>Rate</th>
		<th>Discount/<br>freight</th>
		<th>TCS</th>
		<th>Per</th>
		<th>Amount</th>
		<th></th>
		</thead>
		<tbody>
		<tr>
		<td>
		{{$product->name}}
		</td>
		<td>
		{{$product->hsn_code}}
		</td>
		<td>
		<input type="number" id="quantity_`+count+`" name="quantity[]" size="4" value="0" class="quantity_validator" onkeyup="calculateTotal(this.value,`+count+`)">
		</td>
		<td>
		{{$product_loading->token->rate}}
		</td>
		<td><input type="number" id="freight_discount_`+count+`" name="freight_discount[]" size="4" value="0" onchange></td>
		<td><input type="number" id = "tcs_`+count+`" name="tcs[]" size="4" value="0" onkeyup="calculatetcs(this.value,`+count+`)"></td>
		<td>
		{{$product_loading->unit_name}}
		</td>
		<td id="total_amount_`+count+`">
		</td>
		<td></td>
		</tr>
		</tbody>
		</table>
		</div>
		</div>
		</div>
		`;
		return html;
		@endif
	}

	function calculateTotal(quantity,count){
		var rate = parseInt($('#token_rate').val());
		$('#total_amount_'+count).text(parseFloat(parseFloat(rate) * quantity));

	}
	function calculatetcs(tcs,count){
		var rate = parseInt($('#token_rate').val());
		var quantity_ = parseInt($('#quantity_'+count).val());
		$('#total_amount_'+count).text(parseFloat(parseFloat(rate) * quantity_)+parseInt(tcs));
	}

	function caltcs(tcs){
		// var total_amount =
		var quantity_ = parseInt($('#total_quantity').val());
		var rate = parseInt($('#token_rate').val());
		console.log(tcs);
		console.log(rate);
		console.log(quantity_);
		$('#total').val(parseFloat(parseFloat(rate) * quantity_)+parseInt(tcs));
		// $('#total').text(parseFloat(total_amount +tcs));
	}
</script>