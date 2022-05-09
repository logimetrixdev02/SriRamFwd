@extends('dashboard.layouts.app')
@section('title','Wagon Unloadings')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Backend</a>
				</li>
				<li class="active">Wagon Unloadings</li>
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

			<form action="" method="GET" class="form-inline" role="form">
				<div class="form-group">
					<label class="sr-only" for="wagon_unloading">wagon unloading</label>
					<select class="form-control select2" name="wagon_unloading" id="wagon_unloading" required="required">
						<option value="">Select wagon Unloading </option>
						@foreach($wagon_unloadings as $wagon_unloading)
						<option value="{{$wagon_unloading->id}}" {{isset($current_wagon_unloading) && $current_wagon_unloading->id == $wagon_unloading->id ? "selected":""}}>{{$wagon_unloading->id}}</option>
						@endforeach
					</select>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
			@if(isset($current_wagon_unloading) && !is_null($current_wagon_unloading))
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Update Token</h3>
				</div>
				<div class="panel-body">

					<form action="" role="form" id="" method="post" onsubmit="return validateForm()">
						{{csrf_field()}}
						<div class="container">
							<div class="row">
								
								<input type="hidden" name="wagon_unloading_id" value="{{$wagon_unloading->id}}">

								
								<div class="col-md-4">
									<div class="form-group">
										<label for="quantity">Quantity</label>
										<input type="text" name="quantity" id="quantity" class="form-control" value="{{$current_wagon_unloading->quantity}}">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="wagon_rate">wagon rate</label>
										<input type="text" name="wagon_rate" id="wagon_rate" class="form-control" value="{{$current_wagon_unloading->wagon_rate}}">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="wagon_number">Wagon Number</label>
										<input type="text" name="wagon_number" id="wagon_number" class="form-control" value="{{$current_wagon_unloading->wagon_number}}">
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="labour_name">labour name</label>
										<input type="text" name="labour_name" id="labour_name" class="form-control" value="{{$current_wagon_unloading->labour_name}}">
									</div>
								</div>

							</div>
						</div>
						
						<button type="submit" class="btn btn-primary">Submit</button>
					</form>


				</div>
			</div>
			@endif
			
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
