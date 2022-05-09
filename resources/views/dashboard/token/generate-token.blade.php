@extends('dashboard.layouts.app')
@section('title','Token')

@section('style')
{{Html::style("assets/css/bootstrap-datepicker3.min.css")}}
@endsection

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">{{__('messages.Token')}}</li>
			</ul>
		</div>

		<div class="page-content">


			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">New Order</h3>
				</div>
				<div class="panel-body">

					<form action="" role="form" id="generateTokenForm">
						
						<div class="container" style="width:100% !important;">
							<div class="row">
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="token_type">Rake / Godown</label>
										<select class="form-control select2" name="token_type" id="token_type" onchange="handleTokenType(this.value)">
											<option value="1" selected>Rake</option>
											<option value="2">Godown</option>
										</select>
									</div>
								</div>

								<div class="col-md-4" id="warehouse_div" style="display: none;">
									<div class="form-group">
										<label for="from_warehouse_id">{{__('messages.From')}} {{__('messages.Warehouses')}}</label>
										<select class="form-control select2" name="from_warehouse_id" id="from_warehouse_id" >
											<option value="">{{__('messages.Select')}} {{__('messages.Warehouses')}}</option>
											@foreach($warehouses as $warehouse)
											<option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
											@endforeach()
											
										</select>
										<span class="label label-danger" id="add_from_warehouse_id_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4" id="despatch_location_div" >
									<div class="form-group">
										<label for="from_warehouse_id">Despatch Location</label>
										<select class="form-control select2" name="despatch_location" id="despatch_location" >
											<option value="">Select Location</option>
											@foreach($destinations as $destination)
											<option value="{{$destination->code}}">{{$destination->name}}</option>
											@endforeach()
											
										</select>
										<span class="label label-danger" id="add_from_warehouse_id_error" style="display: none;"></span>
									</div>
								</div>

								

								<div class="clearfix"></div>

								<div class="col-md-4" id="retailer_section" >
									<div class="form-group">
										<label for="retailer_id">Retailer Name</label>
										<select class="form-control select2" name="retailer_id" id="retailer_id" onchange="get_address_of_retailer(this)">
											<option value="">{{__('messages.Retailers')}} {{__('messages.Select')}}</option>
											@foreach($retailers as $retailer)
											<option value="{{$retailer->id}}">{{$retailer->name}}({{$retailer->address}})</option>
											@endforeach()
											
										</select>
										<span class="label label-danger" id="add_retailer_id_error" style="display: none;"></span>
									</div>
								</div>


								<div class="col-md-4" id="dealer_section">
									<div class="form-group">
										<label for="dealer_id"> Dealer Name</label>
										<select class="form-control select2" name="dealer_id" id="dealer_id">
											<option value="">{{__('messages.Dealer')}} {{__('messages.Select')}}</option>
											@foreach($dealers as $dealer)
											<option value="{{$dealer->id}}">{{$dealer->name}} ({{$dealer->address1}})</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_dealer_id_error" style="display: none;"></span>
									</div>
								</div>


								<div class="clearfix"></div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="product_company_id">{{__('messages.ProductCompany')}}</label>
										<select class="form-control select2" name="product_company_id" id="product_company_id">
											<option value=""> {{__('messages.ProductCompany')}} {{__('messages.Select')}}</option>
											@foreach($product_companies as $product_company)
											<option value="{{$product_company->id}}">{{$product_company->name}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_product_company_id_error" style="display: none;"></span>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="product_id">{{__('messages.Product')}}</label>
										<select class="form-control select2" name="product_id" id="product_id" >
											<option value="">{{__('messages.Product')}} {{__('messages.Select')}}</option>
											
											@foreach($products as $product)
											<option value="{{$product->id}}">{{$product->name}}</option>
											@endforeach()
											
										</select>
										<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="unit_id">{{__('messages.Unit')}}</label>
										<select class="form-control select2" name="unit_id" id="unit_id">
											<option value="">{{__('messages.Unit')}} {{__('messages.Select')}}</option>
											@foreach($units as $unit)
											<option value="{{$unit->id}}">{{$unit->unit}}</option>
											@endforeach()

										</select>
										<span class="label label-danger" id="add_unit_id_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="quantity">{{__('messages.Quantity')}}</label>
										<input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity" onkeyup="checkQuantity(this.value)">
										<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
									</div>
								</div>


								
								<div class="clearfix"></div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="address">Address</label>
										<input type="text" class="form-control" name="address" id="address" placeholder="address">
										<span class="label label-danger" id="add_address_error" style="display: none;"></span>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="phone_number">Phone Number</label>
										<input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="phone_number">
										<span class="label label-danger" id="add_address_error" style="display: none;"></span>
									</div>
								</div>
								

								<div class="clearfix"></div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="description">Remark</label>
										<textarea name="description" id="description" class="form-control" rows="3" required="required"></textarea>
										<span class="label label-danger" id="add_description_error" style="display: none;"></span>
									</div>
								</div>

							</div>
						</div>
						
						<a href="{{URL('/user/generate-token')}}" class="btn btn-default" >Reset</a>
						<button type="button" id="generateTokenBtn" class="btn btn-primary">Submit</button>
					</form>


				</div>
			</div>

			
		</div>
	</div><!-- /.page-content -->
</div><!-- /.main-content -->
@section('script')

{{ Html::script("assets/js/ace-elements.min.js")}}
{{ Html::script("assets/js/ace.min.js")}}
{{ Html::script("assets/js/bootstrap-datepicker.min.js")}}


<script type="text/javascript">
	function get_address_of_retailer(t){
		var retailer_id = $(t).val();
		console.log(retailer_id);
	}
	$(document).ready(function() {

		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		})
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});



		$('#quantity').click(function(){
				var from_warehouse = $('#from_warehouse_id').val();
				var to_type = $('#to_type').val();
				var warehouse_id = $('#warehouse_id').val();
				var retailer_id = $('#retailer_id').val();
				var dealer_id = $('#dealer_id').val();
				var product_company_id = $('#product_company_id').val();
				var product_id = $('#product_id').val();
				var unit_id = $('#unit_id').val();
				var quantity = $('#quantity').val();
				$('#quantity').html(product_id);
				//alert(from_warehouse);

			});

		


				$('#generateTokenBtn').click(function(e){
					$('.loading-bg').show();
					e.preventDefault();
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					});
					$.ajax({
						url: $('#generateTokenForm').attr('action'),
						method: 'POST',
						data: $('#generateTokenForm').serialize(),
						success: function(data){
					$('.loading-bg').hide();
					if(!data.flag){
						showError('add_rake_id_error',data.errors.master_rake_id);
						showError('add_from_warehouse_id_error',data.errors.from_warehouse_id);
						showError('add_company_id_error',data.errors.company_id);
						showError('add_dealer_id_error',data.errors.dealer_id);
						showError('add_product_company_id_error',data.errors.product_company_id);
						showError('add_product_id_error',data.errors.product_id);
						showError('add_warehouse_id_error',data.errors.warehouse_id);
						showError('add_retailer_id_error',data.errors.retailer_id);
						showError('add_quantity_error',data.errors.quantity);
						//showError('add_rate_error',data.errors.rate);
						showError('add_unit_id_error',data.errors.unit_id);
						showError('add_date_error',data.errors.date_of_generation);
						//showError('add_account_from_id_error',data.errors.account_from_id);
						showError('add_delivery_payment_mode_error',data.errors.delivery_payment_mode);
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




		$('#generateTokenBtn1').click(function(e){
			e.preventDefault();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			});
			$('.loading-bg').show();
			$.ajax({
				url: $('#generateTokenForm1').attr('action'),
				method: 'POST',
				data: $('#generateTokenForm').serialize(),
				success: function(data){
					$('.loading-bg').hide();
					if(!data.flag){
						showError('add_rake_id_error',data.errors.master_rake_id);
						showError('add_from_warehouse_id_error',data.errors.from_warehouse_id);
						showError('add_company_id_error',data.errors.company_id);
						showError('add_dealer_id_error',data.errors.dealer_id);
						showError('add_product_company_id_error',data.errors.product_company_id);
						showError('add_product_id_error',data.errors.product_id);
						showError('add_warehouse_id_error',data.errors.warehouse_id);
						showError('add_retailer_id_error',data.errors.retailer_id);
						showError('add_quantity_error',data.errors.quantity);
						//showError('add_rate_error',data.errors.rate);
						showError('add_unit_id_error',data.errors.unit_id);
						showError('add_date_error',data.errors.date_of_generation);
						showError('add_account_from_id_error',data.errors.account_from_id);
						showError('add_delivery_payment_mode_error',data.errors.delivery_payment_mode);
					}else{
						swal({
							title: "Success!",
							text: data.message,
							type: "success"
						}, function() {
							var print_url = "{{URL('/')}}"+'/user/print-token/'+data.token.id;
							window.location.href = print_url;
						});
					}
				}

			});
		});

	});

	function handleTokenType(id){
		if(id==1){
			$('#rake_div').show();
			$('#warehouse_div').hide();
			
		}else if(id==2){
			$('#rake_div').hide();
			$('#warehouse_div').show();
		}
	}
	
function handleToType(type){
		$('.loading-bg').show();
		if(type == 1){
			$('#warehouses_section').show();
			$('#retailer_section').hide();
		}else if(type == 2){
			$('#warehouses_section').hide();
			$('#retailer_section').show();
		}else if(type == 3){
			$('#warehouses_section').hide();
			$('#retailer_section').hide();
		}
		$('.loading-bg').hide();
	}
	

	function checkQuantity(value){
		var token_type = $('#token_type option:selected').val();
		if(token_type == 2){
			var remaining_quantity = parseInt($('#remaining_quantity').val());
			if(remaining_quantity < parseInt(value)){
				('#quantity').val('');
				swal('Error','Entered Quantity ('+parseInt(value)+') should not be greater tha remaining quantity('+remaining_quantity+')','error');
			}

		}

	}
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
