@extends('dashboard.layouts.app')
@section('title','Product Allotments')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">{{__('messages.Product')}} {{__('messages.Allotment')}}</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="page-header">
				<h1>
					  {{__('messages.Rake')}} {{__('messages.Product')}} {{__('messages.Allotment')}}
				</h1>
			</div><!-- /.page-header -->

			

			<form action="" role="form" id="AllotProductForm">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">
							{{__('messages.Rake')}} {{__('messages.Product')}} {{__('messages.Allotment')}}
						</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="master_rake_id"> {{__('messages.masterrake')}}</label>
									<select class="form-control select2" name="master_rake_id" id="master_rake_id" onchange="getMasterRakeDetails(this.value)">
										<option value="">{{__('messages.SelectMasterRake')}}</option>
										@foreach($master_rakes as $master_rake)
										<option value="{{$master_rake->id}}">{{$master_rake->name}}</option>
										@endforeach()
									</select>
									<span class="label label-danger" id="add_master_rake_error" style="display: none;"></span>
								</div>
							</div>


						

						</div>

						<div class="row">
						    
						    	<div class="col-md-3">
								<div class="form-group">
									<label for="dealer_id">{{__('messages.Dealer')}}</label>
									<select class="form-control select2" name="dealer_id[]" id="dealer_id">
										<option value=""> {{__('messages.Dealer')}} {{__('messages.Select')}}</option>
										@foreach($dealers as $dealer)
										<option value="{{$dealer->id}}">{{$dealer->name}}({{$dealer->address1}})</option>
										@endforeach()
									</select>
									<span class="label label-danger" id="add_dealer_id_error" style="display: none;"></span>
								</div>
							</div>


							<div class="col-md-4">
								<div class="form-group">
									<label for="product_id_1"> {{__('messages.Product')}}</label>
									<select class="form-control checkIfValid select2" name="product_id[]" id="product_id_1">
										<option value="">  {{__('messages.Product')}} {{__('messages.Select')}}</option>
										
									</select>
									<span class="label label-danger" id="add_product_error" style="display: none;"></span>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="unit_id"> {{__('messages.Unit')}}</label>
									<select class="form-control checkIfValid select2" name="unit_id[]">
										<option value="">  {{__('messages.Unit')}} {{__('messages.Select')}}</option>
										@foreach($units as $unit)
										<option value="{{$unit->id}}">{{$unit->unit}}</option>
										@endforeach()
									</select>
									<span class="label label-danger" id="add_unit_error" style="display: none;"></span>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="quantity">  {{__('messages.Alloted')}} {{__('messages.Quantity')}}</label>
									<input type="text" name="quantity[]"  class="form-control checkIfValid">
									<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
								</div>
							</div>
						</div>


						<div id="addMoreProductSection">
							
						</div>

						<div class="pull-left">
							<button type="button" id="addMoreProduct" class="btn btn-danger"><i class="fa fa-plus"></i> Add More </button>

						</div>

						<div class="pull-right">
							<a href="{{URL('/user/allot-product')}}" class="btn btn-default" >Reset</a>
							<button type="button" id="allotProductBtn" class="btn btn-primary">Submit</button>

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
							var product_option = "<option value=''>Select Product</option>";
							 $.each(data.master_rake.master_rake_products, function(i, value) {
							     product_option += "<option value="+value.product_id+">"+value.product.name+"</option>";
							     
							 });
							 console.log(product_option);
							 
							 $("select[name='product_id[]']").each(function() {
							      $(this).html(product_option).trigger('change');
							 });

							
							
					}else{
						swal('Error',data.message,'warning');
					}
				}
			});
		}
	}
	$(document).ready(function() {



	
		$('#allotProductBtn').click(function(e){
			e.preventDefault();

			if(validateField()){
				return false;
			}else{

				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					}
				});
				$('.loading-bg').show();
				$.ajax({
					url: $('#AllotProductForm').attr('action'),
					method: 'POST',
					data: $('#AllotProductForm').serialize(),
					success: function(data){
						$('.loading-bg').hide();
						if(!data.flag){
							showError('add_master_rake_error',data.errors.master_rake_id);
							showError('add_company_error',data.errors.company_id);
							showError('add_dealer_id_error',data.errors.dealer_id);
							showError('add_product_error',data.errors.product_id);
							showError('add_unit_error',data.errors.unit_id);
							showError('add_quantity_error',data.errors.quantity);
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

			}
			
		});


		$('#addMoreProduct').click(function(){
		    var product_count = $("select[name='product_id[]']").length+1;
		    var product_option = $("#product_id_1").html();
			var count = $('.row').length + 1;
			var newRow = `<div class="row" id="newRow`+count+`">
			
			<div class="col-md-3">
								<div class="form-group">
									<label for="dealer_id">{{__('messages.Dealer')}}</label>
									<select class="form-control select2" name="dealer_id[]" id="dealer_id">
										<option value="">{{__('messages.Dealer')}} {{__('messages.Select')}}</option>
										@foreach($dealers as $dealer)
										<option value="{{$dealer->id}}">{{$dealer->name}}({{$dealer->address1}})</option>
										@endforeach()
									</select>
									<span class="label label-danger" id="add_dealer_id_error" style="display: none;"></span>
								</div>
							</div>
							<div class="col-md-4">
			<div class="form-group">
			<label for="product_id">{{__('messages.Product')}}</label>
			<select class="form-control checkIfValid select2" name="product_id[]" id="product_id_`+product_count+`">
			`+product_option+`
			</select>
			<span class="label label-danger" id="add_product_error" style="display: none;"></span>
			</div>
			</div>

			<div class="col-md-2">
			<div class="form-group">
			<label for="unit_id">{{__('messages.Unit')}}</label>
			<select class="form-control checkIfValid select2" name="unit_id[]" >
			<option value="">Select Unit</option>
			@foreach($units as $unit)
			<option value="{{$unit->id}}">{{$unit->unit}}</option>
			@endforeach()
			</select>
			<span class="label label-danger" id="add_unit_error" style="display: none;"></span>
			</div>
			</div>

			<div class="col-md-2">
			<div class="form-group">
			<label for="quantity">{{__('messages.Alloted')}} {{__('messages.Quantity')}}</label>
			<input type="text" name="quantity[]" class="form-control checkIfValid">
			<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
			</div>
			</div>
			<div class="col-md-1" style="margin-top: 30px;">
			<a href="javascript:;" onclick="removeRow(`+count+`)"><i class="fa fa-close fa-2x"></i></a>
			</div>
			</div>
			`;
			$('#addMoreProductSection').append(newRow);
			$('.select2').select2();

		});
	});

	function validateField(){
		var errorCount = 0;
		$('.checkIfValid').each(function(){
			console.log($(this).val());
			if($(this).val() == ""){
				errorCount = errorCount + 1;
				$(this).closest('.form-group').find('.label-danger').text('Required');
				$(this).closest('.form-group').find('.label-danger').show();
			}else{
				$(this).closest('.form-group').find('.label-danger').hide();
			}
		});

		if(errorCount > 0){
			return true;
		}else{
			return false;
		}
	}
	function removeRow(id){
		$('#newRow'+id).remove();
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
