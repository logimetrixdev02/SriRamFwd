@extends('dashboard.layouts.app')
@section('title','Labour-Payment')

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
					<a href="#"> {{__('messages.Home')}}</a>
				</li>
				<li class="active"> {{__('messages.LabourPayment')}}</li>
			</ul>
		</div>

		<div class="page-content">


			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"> {{__('messages.GenerateLabourPayment')}}</h3>
				</div>
				<div class="panel-body">

					<form action="" role="form" id="generateLabourPaymentForm">
						<div class="container">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="token_id"> {{__('messages.Token')}}</label>
										<select class="form-control select2" name="token_id" id="token_id" onchange="getTokenDetails(this.value)">
											<option value="">{{__('messages.Token')}} {{__('messages.Select')}}</option>
											@foreach($tokens as $token)
											<option value="{{$token->id}}">{{$token->id}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_token_id_error" style="display: none;"></span>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="labour_name"> {{__('messages.Labour')}} {{__('messages.Name')}}</label>
										<input type="text" name="labour_name" id="labour_name" class="form-control">
										<span class="label label-danger" id="add_labour_name_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="truck_number"> {{__('messages.TruckNumber')}}</label>
										<input type="text" class="form-control" name="truck_number" id="truck_number" >
										<span class="label label-danger" id="add_truck_number_error" style="display: none;"></span>
									</div>
								</div>




								<div class="clearfix"></div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="product_loading_id"> {{__('messages.ProductLoading')}}</label>
										<select class="form-control select2" name="product_loading_id" id="product_loading_id" onchange="">

											<option value=""> {{__('messages.ProductLoading')}} {{__('messages.Select')}}</option>
											@foreach($product_loadings as $product_loading)
											<option value="{{$product_loading->id}}">{{$product_loading->id}}
											</option>

											@endforeach()
										</select>				



										<span class="label label-danger" id="add_product_loading_id_error" style="display: none;"></span>
									</div>
								</div>



								<div class="col-md-4">
									<div class="form-group">
										<label for="product_id"> {{__('messages.Product')}} </label>
										<select class="form-control select2" name="product_id" id="product_id">
											<option value="">{{__('messages.Product')}} {{__('messages.Select')}}</option>

										</select>
										<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="quantity"> {{__('messages.Quantity')}}</label>
										<input type="text" class="form-control" name="quantity" id="quantity" >
										<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
									</div>
								</div>


								<div class="clearfix"></div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="master_rake_id"> {{__('messages.masterrake')}}</label>
										<select class="form-control select2" name="master_rake_id" id="master_rake_id" onchange="">
											<option value=""> {{__('messages.Selectmasterrake')}}</option>
										</select>
										<span class="label label-danger" id="add_master_rake_id_error" style="display: none;"></span>
									</div>
								</div>



								

								<div class="col-md-4">
									<div class="form-group">
										<label for="unit_id"> {{__('messages.Unit')}}</label>
										<select class="form-control select2" name="unit_id" id="unit_id">
											<option value="">  {{__('messages.Unit')}} {{__('messages.Select')}}</option>
										</select>
										<span class="label label-danger" id="add_unit_id_error" style="display: none;"></span>
									</div>
								</div>


								<div class="clearfix"></div>

								<a href="{{URL('/user/labour-payment')}}" class="btn btn-default" >Reset</a>
								<button type="button" id="generatePaymentBtn" class="btn btn-primary">Submit</button>
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

				$(document).ready(function() {

					$('#generatePaymentBtn').click(function(e){
						e.preventDefault();
						$.ajaxSetup({
							headers: {
								'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
							}
						});
						$('.loading-bg').show();
						$.ajax({
							url: $('#generateLabourPaymentForm').attr('action'),
							method: 'POST',
							data: $('#generateLabourPaymentForm').serialize(),
							success: function(data){
								$('.loading-bg').hide();
								if(!data.flag){
									showError('add_token_id_error',data.errors.token_id);
									
									showError('add_truck_number_error',data.errors.truck_number);
									showError('add_labour_name_error',data.errors.labour_name);
									showError('add_quantity_error',data.errors.quantity);
									showError('add_product_loading_id_error',data.errors.product_loading_id);
									showError('add_unit_id_error',data.errors.unit_id);

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
									var master_rake_option = "<option value='"+data.token.master_rake.id+"'>"+data.token.master_rake.name+"</option>"
									var product_option = "<option value='"+data.token.product.id+"'>"+data.token.product.name+"</option>"
									var unit_option = "<option value='"+data.token.unit.id+"'>"+data.token.unit.unit+"</option>"
									

									$('#product_id').html(product_option).trigger('change');
									$('#unit_id').html(unit_option).trigger('change');
									
									$('#master_rake_id').html(master_rake_option).trigger('change');
									$('#unit_id').html(unit_option).trigger('change');
									$('#quantity').val(data.token.quantity);
									$('#truck_no').val(data.token.truck_no);
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
