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
					{{__('messages.Update')}} 	{{__('messages.Rake')}} {{__('messages.Product')}} {{__('messages.Allotment')}}
					# {{$allotment->id}}
				</h1>
			</div><!-- /.page-header -->

			

			<form action="" role="form" id="AllotProductForm">
				<input type="hidden" name="id" value="{{$allotment->id}}">
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
									<select class="form-control select2" name="master_rake_id" id="master_rake_id"  disabled="disabled">
										<option value="">{{__('messages.SelectMasterRake')}}</option>
										@foreach($master_rakes as $master_rake)
										<option value="{{$master_rake->id}}" {{$master_rake->id == $allotment->master_rake_id ? "selected":""}}>{{$master_rake->name}}</option>
										@endforeach()
									</select>
									<span class="label label-danger" id="add_master_rake_error" style="display: none;"></span>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="dealer_id">{{__('messages.Dealer')}}</label>
									<select class="form-control select2" name="dealer_id[]" id="dealer_id"  disabled="disabled">
										<option value=""> {{__('messages.Dealer')}} {{__('messages.Select')}}</option>
										@foreach($dealers as $dealer)
										<option value="{{$dealer->id}}" {{$dealer->id == $allotment->dealer_id ? "selected":""}}>{{$dealer->name}}</option>
										@endforeach()
									</select>
									<span class="label label-danger" id="add_dealer_id_error" style="display: none;"></span>
								</div>
							</div>

							

						</div>

						
						<div class="row">

							
							<input type="hidden" name="product" value="{{$allotment->product_id}}">
							<div class="col-md-4">
								<div class="form-group">
									<label for="product_id"> {{__('messages.Product')}}</label>
									<select class="form-control checkIfValid select2" name="product_id[]" disabled="disabled">
										<option value="">  {{__('messages.Product')}} {{__('messages.Select')}}</option>
										@foreach($products as $product)
										<option value="{{$product->id}}" {{$product->id == $allotment->product_id ? "selected":""}}>{{$product->name}}</option>
										@endforeach()
									</select>
									<span class="label label-danger" id="add_product_error" style="display: none;"></span>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="unit_id">{{__('messages.Unit')}}</label>
									<select class="form-control checkIfValid select2" name="unit_id[]"  disabled="disabled">
										<option value=""> {{__('messages.Unit')}} {{__('messages.Select')}}</option>
										@foreach($units as $unit)
										<option value="{{$unit->id}}" {{$unit->id == $allotment->unit_id ? "selected":""}}>{{$unit->unit}}</option>
										@endforeach()
									</select>
									<span class="label label-danger" id="add_unit_error" style="display: none;"></span>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="quantity">{{__('messages.Alloted')}} {{__('messages.Quantity')}}</label>
									<input type="text" name="quantity"  class="form-control checkIfValid" value="{{$allotment->alloted_quantity}}">
									<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
								</div>
							</div>
						</div>

						<div class="pull-right">
							<a href="{{URL('/user/rake-product-allotments')}}" class="btn btn-default" >Back</a>
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

	});

	function validateField(){
		var errorCount = 0;
		$('.checkIfValid').each(function(){
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
