@extends('dashboard.layouts.app')
@section('title','Product-Loading')

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
					<a href="#">Home</a>
				</li>
				<li class="active">Product Loading</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Generate Product Loading</h3>
				</div>
				<div class="panel-body">

					<form action="" role="form" id="generateProductLoadingForm">
						<div class="container">
							<div class="row">
								<legend>Loading Slip</legend>


								<div class="col-md-4">
									<div class="form-group">
										<label for="loading_slip_type">Loading Slip Type</label>
										<select class="form-control select2" name="loading_slip_type" id="loading_slip_type" onchange="handleSlipType(this.value)">
											<option value="">Select Slip Type</option>
											<option value="1">Regular</option>
											<option value="2">Direct</option>
										</select>
										<span class="label label-danger" id="add_loading_slip_type_error" style="display: none;"></span>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="regular_loading" style="display: none;">
									<div class="col-md-4">
										<div class="form-group">
											<label for="regular_token_id">Token</label>
											<select class="form-control select2" name="regular_token_id" id="regular_token_id" onchange="getTokenDetails(this.value)">
												<option value="">Select Token</option>
												@foreach($tokens as $token)
												<option value="{{$token->id}}">{{$token->id}}</option>
												@endforeach()
											</select>
											<span class="label label-danger" id="add_regular_token_id_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="regular_product_company_id">Product Company</label>
											<select class="form-control select2" name="regular_product_company_id" id="regular_product_company_id" onchange="">
												<option value="regular_product_company_id">Select Product Company</option>
											</select>
											<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4" id="rake_div" style="display: none;">
										<div class="form-group">
											<label for="regular_master_rake_id">Master Rake</label>
											<select class="form-control select2" name="regular_master_rake_id" id="regular_master_rake_id" onchange="">
												<option value="">Select master rake</option>
											</select>
											<span class="label label-danger" id="add_regular_master_rake_id_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4" id="warehouse_div" style="display: none;">
										<div class="form-group">
											<label for="from_warehouse_id">Warehouse</label>
											<select class="form-control select2" name="from_warehouse_id" id="from_warehouse_id">
												<option value="">Select Warehouse</option>
												@foreach($warehouses as $warehouse)
												<option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
												@endforeach
											</select>
											<span class="label label-danger" id="add_from_warehouse_id_error" style="display: none;"></span>
										</div>
									</div>



									<div class="clearfix"></div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="regular_wagon_no">Wagon Number</label>
											<input type="text" name="regular_wagon_no" id="regular_wagon_no" class="form-control">
											<span class="label label-danger" id="add_regular_wagon_no_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="regular_product_id">Product</label>
											<select class="form-control select2" name="regular_product_id" id="regular_product_id">
												<option value="">Select Product</option>

											</select>
											<span class="label label-danger" id="add_regular_product_id_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="regular_unit_id">Unit</label>
											<select class="form-control select2" name="regular_unit_id" id="regular_unit_id">
												<option value="">Select Unit</option>
												
											</select>
											<span class="label label-danger" id="add_regular_unit_id_error" style="display: none;"></span>
										</div>
									</div>

									<div class="clearfix"></div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="regular_quantity">Quantity</label>
											<input type="text" class="form-control" name="regular_quantity" id="regular_quantity" >
											<span class="label label-danger" id="add_regular_quantity_error" style="display: none;"></span>
										</div>
									</div>




									<div class="col-md-4">
										<div class="form-group">
											<label for="regular_truck_number">Truck Number</label>
											<input type="text" class="form-control" name="regular_truck_number" id="regular_truck_number" >
											<span class="label label-danger" id="add_regular_truck_number_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="regular_transporter_id">Transporter</label>
											<select class="form-control select2" name="regular_transporter_id" id="regular_transporter_id">
												<option value="">Select Transporter</option>
												@foreach($transporters as $transporter)
												<option value="{{$transporter->id}}" >{{$transporter->name}}</option>

												@endforeach()
											</select>
											<span class="label label-danger" id="add_regular_transporter_id_error" style="display: none;"></span>
										</div>
									</div>
									<div class="clearfix"></div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="regular_freight">Freight</label>
											<input type="number" class="form-control" name="regular_freight" id="regular_freight" >
											<span class="label label-danger" id="add_regular_freight_error" style="display: none;"></span>
										</div>
									</div>


									<div class="col-md-4">
										<div class="form-group">
											<label for="regular_dealer_id">Dealer</label>
											<select class="form-control select2" name="regular_dealer_id" id="regular_dealer_id">
												<option value="">Select Dealer</option>
											</select>
											<span class="label label-danger" id="add_regular_dealer_id_error" style="display: none;"></span>
										</div>
									</div>

									<div class="clearfix"></div>

									<legend>Labour Payment</legend>

									<div class="col-md-4">
										<div class="form-group">
											<label for="regular_labour_name">Labour Name</label>
											<input type="text" name="regular_labour_name" id="regular_labour_name" class="form-control">
											<span class="label label-danger" id="add_regular_labour_name_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="regular_rate">Rate</label>
											<input type="text" name="regular_rate" id="regular_rate" class="form-control">
											<span class="label label-danger" id="add_regular_rate_error" style="display: none;"></span>
										</div>
									</div>

									<div class="clearfix"></div>


								</div>

								<div class="direct_loading" style="display: none;">

									<div class="col-md-4">
										<div class="form-group">
											<label for="direct_master_rake_id">Master Rake</label>
											<select class="form-control select2" name="direct_master_rake_id" id="direct_master_rake_id" onchange="getMasterRakeDetails(this.value)">
												<option value="">Select master rake</option>
												@foreach($master_rakes as $master_rake)
												<option value="{{$master_rake->id}}">{{$master_rake->name}}</option>
												@endforeach
											</select>
											<span class="label label-danger" id="add_direct_master_rake_id_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="direct_product_company_id">Product Company</label>
											<select class="form-control select2" name="direct_product_company_id" id="direct_product_company_id" onchange="">
												<option value="direct_product_company_id">Select Product Company</option>
											</select>
											<span class="label label-danger" id="add_direct_product_company_id_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="direct_warehouse_id">Warehouse</label>
											<select class="form-control select2" name="direct_warehouse_id" id="direct_warehouse_id">
												<option value="">Select Warehouse</option>
												@foreach($warehouses as $warehouse)
												<option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
												@endforeach
											</select>
											<span class="label label-danger" id="add_direct_warehouse_id_error" style="display: none;"></span>
										</div>
									</div>

									<div class="clearfix"></div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="direct_wagon_no">Wagon Number</label>
											<input type="text" name="direct_wagon_no" id="direct_wagon_no" class="form-control" value="Direct">
											<span class="label label-danger" id="add_direct_wagon_no_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="direct_product_id">Product</label>
											<select class="form-control select2" name="direct_product_id" id="direct_product_id">
												<option value="">Select Product</option>

											</select>
											<span class="label label-danger" id="add_direct_product_id_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="direct_unit_id">Unit</label>
											<select class="form-control select2" name="direct_unit_id" id="direct_unit_id">
												<option value="">Select Unit</option>
												@foreach($units as $unit)
												<option value="{{$unit->id}}">{{$unit->unit}}</option>
												@endforeach
											</select>
											<span class="label label-danger" id="add_direct_unit_id_error" style="display: none;"></span>
										</div>
									</div>

									<div class="clearfix"></div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="direct_quantity">Quantity</label>
											<input type="text" class="form-control" name="direct_quantity" id="direct_quantity" >
											<span class="label label-danger" id="add_direct_quantity_error" style="display: none;"></span>
										</div>
									</div>




									<div class="col-md-4">
										<div class="form-group">
											<label for="direct_truck_number">Truck Number</label>
											<input type="text" class="form-control" name="direct_truck_number" id="direct_truck_number" >
											<span class="label label-danger" id="add_direct_truck_number_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="direct_transporter_id">Transporter</label>
											<select class="form-control select2" name="direct_transporter_id" id="direct_transporter_id">
												<option value="">Select Transporter</option>
												@foreach($transporters as $transporter)
												<option value="{{$transporter->id}}" >{{$transporter->name}}</option>

												@endforeach()
											</select>
											<span class="label label-danger" id="add_direct_transporter_id_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="direct_freight">Freight</label>
											<input type="number" class="form-control" name="direct_freight" id="direct_freight" >
											<span class="label label-danger" id="add_direct_freight_error" style="display: none;"></span>
										</div>
									</div>


									<div class="clearfix"></div>
									<legend>Labour Payment</legend>

									<div class="col-md-4">
										<div class="form-group">
											<label for="direct_labour_name">Labour Name</label>
											<input type="text" name="direct_labour_name" id="direct_labour_name" class="form-control">
											<span class="label label-danger" id="add_direct_labour_name_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="direct_rate">Rate</label>
											<input type="text" name="direct_rate" id="direct_rate" class="form-control">
											<span class="label label-danger" id="add_direct_rate_error" style="display: none;"></span>
										</div>
									</div>


									<div class="clearfix"></div>

								</div>

								

								<a href="{{URL('/user/product-loading')}}" class="btn btn-default" >Reset</a>
								<button type="button" id="generateLoadingBtn" class="btn btn-primary">Submit</button>
							</form>


						</div>
					</div>
				</div><!-- /.page-content -->
			</div><!-- /.main-content -->
			@section('script')

			{{ Html::script("assets/js/ace-elements.min.js")}}
			{{ Html::script("assets/js/ace.min.js")}}
			{{ Html::script("assets/js/bootstrap-datepicker.min.js")}}


			<script type="text/javascript">

				function handleSlipType(type){
					if(type == 1){
						$("#rake_div").hide();
						$("#warehouse_div").hide();
						$('.regular_loading').show();
						$('.direct_loading').hide();
					}else if(type == 2){
						$('.regular_loading').hide();
						$('.direct_loading').show();

					}else{
						$('.regular_loading').hide();
						$('.direct_loading').hide();

					}
				}

				$(document).ready(function() {

					$('#generateLoadingBtn').click(function(e){
						e.preventDefault();
						$.ajaxSetup({
							headers: {
								'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
							}
						});
						$('.loading-bg').show();
						$.ajax({
							url: $('#generateProductLoadingForm').attr('action'),
							method: 'POST',
							data: $('#generateProductLoadingForm').serialize(),
							success: function(data){
								$('.loading-bg').hide();
								if(!data.flag){
									var loading_slip_type = $('#loading_slip_type option:selected').val();
									showError('add_loading_slip_type_error',data.errors.loading_slip_type);
									if(loading_slip_type == 1){
										showError('add_regular_token_id_error',data.errors.regular_token_id);
										showError('add_regular_transporter_id_error',data.errors.regular_transporter_id);
										showError('add_regular_freight_error',data.errors.regular_freight);
										showError('add_regular_truck_number_error',data.errors.regular_truck_number);
										showError('add_regular_quantity_error',data.errors.regular_quantity);
										showError('add_regular_labour_name_error',data.errors.regular_labour_name);
										showError('add_regular_rate_error',data.errors.regular_rate);
									}else if(loading_slip_type == 2){
										showError('add_direct_master_rake_id_error',data.errors.direct_master_rake_id);
										showError('add_direct_product_id_error',data.errors.direct_product_id);
										showError('add_direct_unit_id_error',data.errors.direct_unit_id);
										showError('add_direct_transporter_id_error',data.errors.direct_transporter_id);
										showError('add_direct_freight_error',data.errors.direct_freight);
										showError('add_direct_warehouse_id_error',data.errors.direct_warehouse_id);
										showError('add_direct_truck_number_error',data.errors.direct_truck_number);
										showError('add_direct_quantity_error',data.errors.direct_quantity);
										showError('add_direct_labour_name_error',data.errors.direct_labour_name);
										showError('add_direct_rate_error',data.errors.direct_rate);
									}

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

				function getTokenDetails(id){

					if(id == ""){
						swal('Error','Token id is missing','warning');
					}else{
						$('.loading-bg').show();
						$.ajax({
							url: "{{url('/get-token-details/')}}"+"/"+id,
							type: 'GET',
							success:function(data){
								console.log(data);
								$('.loading-bg').hide();
								if(data.flag){
									if(data.token.token_type == 1){
										$("#rake_div").show();
										$("#warehouse_div").hide();
										var master_rake_option = "<option value='"+data.token.master_rake.id+"'>"+data.token.master_rake.name+"</option>"
										var product_option = "<option value='"+data.token.product.id+"'>"+data.token.product.name+"</option>"
										var unit_option = "<option value='"+data.token.unit.id+"'>"+data.token.unit.unit+"</option>"
										var product_company_option = "<option value='"+data.token.product_company.id+"'>"+data.token.product_company.name+"</option>"
										var dealer_option = "<option value='"+data.token.dealer.id+"'>"+data.token.dealer.name+"</option>"
										$('#regular_product_id').html(product_option).trigger('change');
										$('#regular_unit_id').html(unit_option).trigger('change');
										$('#regular_product_company_id').html(product_company_option).trigger('change');
										$('#regular_master_rake_id').html(master_rake_option).trigger('change');
										$('#regular_dealer_id').html(dealer_option).trigger('change');
										$('#regular_quantity').val(data.remaining_quantity);
										$('#regular_truck_no').val(data.token.truck_no);
										$('#regular_transporter_id').val(data.token.transporter_id).trigger('change');
									}else if(data.token.token_type == 2){
										$("#warehouse_div").show();
										$("#rake_div").hide();
										var from_warehouse_option = "<option value='"+data.token.from_warehouse.id+"'>"+data.token.from_warehouse.name+"</option>"
										var product_option = "<option value='"+data.token.product.id+"'>"+data.token.product.name+"</option>"
										var unit_option = "<option value='"+data.token.unit.id+"'>"+data.token.unit.unit+"</option>"
										var product_company_option = "<option value='"+data.token.product_company.id+"'>"+data.token.product_company.name+"</option>"
										var dealer_option = "<option value='"+data.token.dealer.id+"'>"+data.token.dealer.name+"</option>"
										$('#regular_product_id').html(product_option).trigger('change');
										$('#regular_unit_id').html(unit_option).trigger('change');
										$('#regular_product_company_id').html(product_company_option).trigger('change');
										$('#from_warehouse_id').html(from_warehouse_option).trigger('change');
										$('#regular_dealer_id').html(dealer_option).trigger('change');
										$('#regular_quantity').val(data.remaining_quantity);
										$('#regular_truck_no').val(data.token.truck_no);
										$('#regular_transporter_id').val(data.token.transporter_id).trigger('change');

									}
								}else{
									swal('Error',data.message,'warning');
								}
							}
						});
					}
				}

				function getMasterRakeDetails(id){

					if(id == ""){
						swal('Error','Master Rake is missing','warning');
					}else{
						$('.loading-bg').show();
						$.ajax({
							url: "{{url('/get-master-rake-details/')}}"+"/"+id,
							type: 'GET',
							success:function(data){
								console.log(data);
								$('.loading-bg').hide();
								if(data.flag){
									var product_option = "<option value=''>Select Product</option>";
									var product_company_option = "<option value='"+data.master_rake.product_company.id+"'>"+data.master_rake.product_company.name+"</option>";
									$.each(data.master_rake.master_rake_products, function(i, value) {
										product_option += "<option value="+value.product_id+">"+value.product.name+"</option>";

									});
									
									$('#direct_product_company_id').html(product_company_option).trigger('change');
									$('#direct_product_id').html(product_option).trigger('change');
								}else{
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
