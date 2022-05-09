@extends('dashboard.layouts.app')
@section('title','Inventory')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Inventory</li>
			</ul>
		</div>

		<div class="page-content">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Update Inventory</h3>
				</div>
				<div class="panel-body">

					<form action="" role="form" id="" method="post" onsubmit="return validateForm()">
						{{csrf_field()}}
						<div class="container">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="dealer_id">Dealer</label>
										<select class="form-control select2" name="dealer_id" id="dealer_id" onchange="handleDealer(this.value)" >
											<option value="">Select Dealer</option>
											@foreach($dealers as $dealer)
											<option value="{{$dealer->id}}">{{$dealer->name}} ({{$dealer->address1}})</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_dealer_id_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="product_company_id">Product Company</label>
										<select class="form-control select2" name="product_company_id" id="product_company_id" onchange="handleCompany(this.value)" >
											<option value="">Select Product Company</option>
											@foreach($product_companies as $product_company)
											<option value="{{$product_company->id}}">{{$product_company->name}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_product_company_id_error" style="display: none;"></span>
									</div>
								</div>


								
								<div class="col-md-4">
									<div class="form-group">
										<label for="warehouse_id">Warehouses</label>
										<select class="form-control select2" name="warehouse_id" id="warehouse_id" required="required">
											<option value="">Select Warehouse</option>
											@foreach($warehouses as $warehouse)
											<option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
											@endforeach()
											
										</select>
										<span class="label label-danger" id="add_warehouse_id_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="product_brand_id">Product Brand</label>
										<select class="form-control select2" name="product_brand_id" id="product_brand_id" required="required">
											<option value="">Select Product Brand</option>
											@foreach($product_companies as $product_company)
											<option value="{{$product_company->id}}">{{$product_company->name}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_product_company_id_error" style="display: none;"></span>
									</div>
								</div>





								


								<div class="col-md-4">
									<div class="form-group">
										<label for="product_id">Product</label>
										<select class="form-control select2" name="product_id" id="product_id" required="required">
											<option value="">Select Product </option>
											@foreach($products as $product)
											<option value="{{$product->id}}">{{$product->name}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
									</div>
								</div>


								<div class="col-md-4">
									<div class="form-group">
										<label for="product_id">Quantity</label>
										<input type="number" class="form-control" name="quantity" required="required">
										<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
									</div>
								</div>

								<div class="clearfix"></div>


								<div class="col-md-4">
									<div class="form-group">
										<label for="unit_id">Unit</label>
										<select class="form-control select2" name="unit_id" id="unit_id" required="required">
											<option value="">Select Unit </option>
											@foreach($units as $unit)
											<option value="{{$unit->id}}">{{$unit->unit}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_unit_id_error" style="display: none;"></span>
									</div>
								</div>
							</div>
						</div>
						
						<button type="submit" class="btn btn-primary">Submit</button>
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
	function handleCompany(product_company_id){
		$('#product_brand_id').val(product_company_id).trigger('change');
	}
	function validateForm(){
		if($('#product_company_id option:selected').val() == "" && $('#dealer_id option:selected').val() == ""){
			swal('Error','Please select Dealer or Product Company','error');
			return false;
		}else{
			return true;
		}

	}


</script>

@endsection
@endsection
