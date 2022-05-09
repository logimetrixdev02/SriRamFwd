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
				<li class="active">{{__('messages.Home')}}</li>
			</ul>
		</div>

		<div class="page-content">


			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">{{__('messages.Update')}} {{__('messages.Home')}} #{{$token->id}}</h3>
				</div>
				<div class="panel-body">

					<form action="" role="form" id="updateTokenForm">
						<input type="hidden" name="id" value="{{$token->id}}">
						<div class="container">
							<div class="row">

								<div class="col-md-4" style="display: none;">
									<div class="form-group">
										<label for="token_type">{{__('messages.tokentype')}}</label>
										<select class="form-control select2" name="token_type" id="token_type">
											<option value="1" selected>{{__('messages.Rake')}} {{__('messages.Unloading')}}</option>
											<option value="2">{{__('messsages.Stockin')}}</option>
										</select>
									</div>
								</div>



								<div class="clearfix"></div>

							<!-- 	<div class="col-md-4">
									<div class="form-group">
										<label for="company_id">{{__('messages.company')}}</label>
										<select class="form-control select2" name="company_id" id="company_id">
											@foreach($companies as $company)
											<option value="{{$company->id}}" {{$acting_company == $company->id ? "selected":""}}>{{$company->name}}</option>
											@endforeach()
										</select>
									</div>
								</div> -->


								<div class="col-md-4">
									<div class="form-group">
										<label for="to_type">{{__('messages.To')}}</label>
										<select class="form-control select2" name="to_type" id="to_type" onchange="handleToType(this.value)" disabled="">
											<option value="1" {{1 == $token->to_type ? "selected":""}}>Warehouse</option>
											<option value="2" {{2 == $token->to_type ? "selected":""}}>Retailer</option>
											<option value="3" {{3 == $token->to_type ? "selected":""}}>Dealer</option>
										</select>
									</div>
								</div>

								


								<div class="col-md-4" id="warehouses_section" style="display: {{1 == $token->to_type ? 'block':'none'}};">
									<div class="form-group">
										<label for="warehouse_id">{{__('messages.Warehouses')}}</label>
										<select class="form-control select2" name="warehouse_id" id="warehouse_id" disabled="">
											<option value="">{{__('messsages.Warehouses')}} {{__('messsages.Select')}}</option>
											@foreach($warehouses as $warehouse)
											<option value="{{$warehouse->id}}" {{$token->warehouse_id == $warehouse->id ? "selected":""}}>{{$warehouse->name}}</option>
											@endforeach()
											
										</select>
										<span class="label label-danger" id="add_warehouse_id_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4" id="retailer_section" style="display: {{2 == $token->to_type ? 'block':'none'}};">
									<div class="form-group">
										<label for="retailer_id">{{__('messages.Retailers')}}</label>
										<select class="form-control select2" name="retailer_id" id="retailer_id" disabled="">
											<option value="">{{__('messsages.Retailers')}} {{__('messsages.Select')}}</option>
											@foreach($retailers as $retailer)
											<option value="{{$retailer->id}}" {{$token->retailer_id == $retailer->id ? "selected":""}}>{{$retailer->name}}({{$retailer->address}})</option>
											@endforeach()
											
										</select>
										<span class="label label-danger" id="add_warehouse_id_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4" id="dealer_section" style="display: {{3 == $token->to_type ? 'block':'none'}};">
									<div class="form-group">
										<label for="dealer_id">{{__('messages.Dealer')}}</label>
										<select class="form-control select2" name="dealer_id" id="dealer_id" onchange="handleDealer(this.value)" disabled="">
											<option value="">{{__('messsages.Dealer')}} {{__('messages.Select')}}</option>
											@foreach($dealers as $dealer)
											<option value="{{$dealer->id}}" {{$token->dealer_id == $dealer->id ? "selected":""}}>{{$dealer->name}} ({{$dealer->address1}})</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_dealer_id_error" style="display: none;"></span>
									</div>
								</div>


								<div class="clearfix"></div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="date_of_generation">{{__('messages.Date')}}</label>
										<input type="text" class="form-control date-picker" name="date_of_generation" id="date_of_generation" placeholder="Date" value="{{date('m/d/Y',strtotime($token->date_of_generation))}}" disabled="">
										<span class="label label-danger" id="add_date_error" style="display: none;"></span>
									</div>
								</div>


								


								<div class="col-md-4">
									<div class="form-group">
										<label for="product_company_id">{{__('messages.ProductCompany')}}</label>
										<select class="form-control select2" name="product_company_id" id="product_company_id" disabled="">
											<option value=""> {{__('messsages.ProductCompany')}} {{__('messsages.Select')}}</option>
											@foreach($product_companies as $product_company)
											<option value="{{$product_company->id}}" {{$product_company->id == $token->product_company_id ? "selected":""}}>{{$product_company->name}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_product_company_id_error" style="display: none;"></span>
									</div>
								</div>


								<div class="clearfix"></div>





								<div class="col-md-4">
									<div class="form-group">
										<label for="quantity">{{__('messages.Quantity')}}</label>
										<input type="text" class="form-control" name="quantity" id="quantity" value="{{$token->quantity}}" placeholder="Quantity" >
										<span class="label label-danger"  id="add_quantity_error" style="display: none;"></span>
									</div>
								</div>

								<div class="clearfix"></div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="unit_id">{{__('messages.Unit')}}</label>
										<select class="form-control select2" name="unit_id" id="unit_id" disabled="">
											<option value="">{{__('messages.Unit')}} {{__('messages.Select')}}</option>
											@foreach($units as $unit)
											<option value="{{$unit->id}}" {{$unit->id == $token->unit_id ? "selected":""}}>{{$unit->unit}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_unit_id_error" style="display: none;"></span>
									</div>
								</div>




								<div class="col-md-4">
									<div class="form-group">
										<label for="rate">{{__('messages.Rate')}}</label>
										<input type="text" class="form-control" name="rate" id="rate" placeholder="Rate" value="{{$token->rate}}" disabled="">
										<span class="label label-danger" id="add_rate_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="transporter_id">{{__('messages.Transporter')}}</label>
										<select class="form-control select2" name="transporter_id" id="transporter_id" disabled="">
											<option value="">{{__('messages.Transporter')}} {{__('messages.Select')}}</option>
											@foreach($transporters as $transporter)
											<option value="{{$transporter->id}}" {{$transporter->id == $token->transporter_id ? "selected":""}}>{{$transporter->name}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_transporter_id_error" style="display: none;"></span>
									</div>
								</div>

								<div class="clearfix"></div>

								

								<div class="col-md-4">
									<div class="form-group">
										<label for="warehouse_keeper_id">{{__('messages.WarehouseKeeper')}}</label>
										<select class="form-control select2" name="warehouse_keeper_id" id="warehouse_keeper_id" disabled="">
											<option value="">{{__('messages.WarehousesKeeper')}} {{__('messages.Select')}}</option>
											@foreach($warehouse_keepers as $warehouse_keeper)
											<option value="{{$warehouse_keeper->id}}" {{$warehouse_keeper->id == $token->warehouse_keeper_id ? "selected":""}}>{{$warehouse_keeper->name}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_Warehouse_keeper_id_error" style="display: none;"></span>
									</div>
								</div>


								<div class="col-md-4">
									<div class="form-group">
										<label for="truck_number">{{__('messages.TruckNumber')}}</label>
										<input type="text" class="form-control" name="truck_number" id="truck_number" placeholder="Truck Number" value="{{$token->truck_number}}" disabled="">
										<span class="label label-danger" id="add_truck_number_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="delivery_payment_mode">{{__('messages.DeliveryPaymentMode')}}</label>
										<select class="form-control select2" name="delivery_payment_mode" id="delivery_payment_mode" disabled="">
											<option value="EX">EX</option>
											<option value="FOR">FOR</option>
										</select>
										<span class="label label-danger" id="add_delivery_payment_mode_error" style="display: none;"></span>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="description">{{__('messages.Description')}}</label>
										<textarea name="description" id="description" class="form-control" rows="3" required="required" disabled="">{{$token->description}}</textarea>
										<span class="label label-danger" id="add_description_error" style="display: none;"></span>
									</div>
								</div>

							</div>
						</div>
						
						<a href="{{URL('/user/generated-token')}}" class="btn btn-default" >Back</a>
						<button type="button" id="updateTokenBtn" class="btn btn-primary">Submit</button>
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
	$(document).ready(function() {

		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		})
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});



		$('#updateTokenBtn').click(function(e){
			e.preventDefault();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			});
			// $('.loading-bg').show();
			$.ajax({
				url: $('#updateTokenForm').attr('action'),
				method: 'POST',
				data: $('#updateTokenForm').serialize(),
				success: function(data){
					$('.loading-bg').hide();
					if(!data.flag){
						showError('add_rake_id_error',data.errors.master_rake_id);
						showError('add_company_id_error',data.errors.company_id);
						showError('add_dealer_id_error',data.errors.dealer_id);
						showError('add_product_company_id_error',data.errors.product_company_id);
						showError('add_product_id_error',data.errors.product_id);
						showError('add_warehouse_id_error',data.errors.warehouse_id);
						showError('add_quantity_error',data.errors.quantity);
						showError('add_rate_error',data.errors.rate);
						showError('add_unit_id_error',data.errors.unit_id);
						showError('add_date_error',data.errors.date_of_generation);
						showError('add_account_from_id_error',data.errors.account_from_id);
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

	function handleToType(type){
		$('.loading-bg').show();
		if(type == 1){
			$('#warehouses_section').show();
			$('#retailer_section').hide();
			$('#dealer_section').hide();
		}else if(type == 2){
			$('#warehouses_section').hide();
			$('#retailer_section').show();
			$('#dealer_section').hide();
		}else if(type == 3){
			$('#warehouses_section').hide();
			$('#retailer_section').hide();
			$('#dealer_section').show();
		}
		$('.loading-bg').hide();
	}
	function handleDealer(id){
		console.log(id);
		$('.loading-bg').show();
		$('#account_from_id').val(id).trigger('change');
		$('.loading-bg').hide();
	}
	// function getMasterRakeDetails(id){

	// 	if(id == ""){
	// 		swal('Error','Master Rake id is missing','warning');
	// 	}else{
	// 		$('.loading-bg').show();
	// 		$.ajax({
	// 			url: "{{url('/get-master-rake-details/')}}"+"/"+id,
	// 			type: 'GET',
	// 			success:function(data){
	// 				console.log(data);
	// 				$('.loading-bg').hide();
	// 				if(data.flag){
	// 					var company_option = "<option value='"+data.master_rake.product_company.id+"'>"+data.master_rake.product_company.name+"</option>"
	// 					$('#product_company_id').html(company_option).trigger('change');

	// 					var account_from_option = "<option value=''>Select Product</option>";
	// 					$.each(data.master_rake.rake_allotments, function(i, value) {
	// 						account_from_option += "<option value="+value.dealer_id+">"+value.dealer.name+"("+value.dealer.address1+")</option>";

	// 					});
	// 					$('#account_from_id').html(account_from_option);

	// 				}else{
	// 					swal('Error',data.message,'warning');
	// 				}
	// 			}
	// 		});
	// 	}
	// }

	function getRakeAllotmentDetails(dealer_id){
		$('#product_id').html('').trigger('change');
		$('#remaining_quantity').val('');
		$('#quantity').val('');

		var master_rake_id = $('#master_rake_id option:selected').val();
		if(master_rake_id == ""){
			$('#master_rake_id option:selected').focus();
			$('#dealer_id').val('');
			swal('Error','Please Select Master Rake','warning');
		}else if(dealer_id == ""){
			// swal('Error','Dealer is missing','warning');
		}else{
			$('.loading-bg').show();
			$.ajax({
				url: "{{url('/get-dealer-rake-allotment/')}}"+"/"+master_rake_id+"/"+dealer_id,
				type: 'GET',
				success:function(data){
					console.log(data);
					$('.loading-bg').hide();
					if(data.flag){
						$('#product_id').html(data.product_options);
						$('#account_id').val(dealer_id).trigger('change');
					}else{
						$('#company_id').val('');
						swal('Error',data.message,'warning');
					}
				}
			});
		}
	}


	function getAllotedProductDetails(product_id){
		var master_rake_id = $('#master_rake_id option:selected').val();
		var dealer_id = $('#account_from_id option:selected').val();
		if(master_rake_id == ""){
			$('#master_rake_id option:selected').focus();
			$('#dealer_id').val('');
			swal('Error','Please Select Master Rake','warning');
		}else if(dealer_id == ""){
			// swal('Error','Dealer is missing','warning');
		}else if(product_id == ""){
			$('#product_id').val('');
			swal('Error','Product missing','warning');
		}else{
			$('.loading-bg').show();
			$.ajax({
				url: "{{url('/get-alloted-product-details/')}}"+"/"+master_rake_id+"/"+dealer_id+"/"+product_id,
				type: 'GET',
				success:function(data){
					console.log(data);
					$('.loading-bg').hide();
					if(data.flag){
						$('#remaining_quantity').val(data.product_details.remaining_quantity);
						$('#quantity').val(data.product_details.remaining_quantity);
						$('#unit_id').val(data.product_details.unit_id).trigger('change');
					}else{
						$('#product_id').val('');
						swal('Error',data.message,'warning');
					}
				}
			});
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
