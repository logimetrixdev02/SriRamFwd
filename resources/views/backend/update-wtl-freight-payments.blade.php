@extends('dashboard.layouts.app')
@section('title','Product Loading')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Backend</a>
				</li>
				<li class="active">Product Loading</li>
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
					<label class="sr-only" for="loading">Loading</label>
					<select class="form-control select2" name="loading" id="loading" required="required">
						<option value="">Select Loading </option>
						@foreach($loadings as $loading)
						<option value="{{$loading->id}}" {{isset($current_loading) && $current_loading->id == $loading->id ? "selected":""}}>{{$loading->id}}</option>
						@endforeach
					</select>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
			@if(isset($current_loading) && !is_null($current_loading))
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Update Token</h3>
				</div>
				<div class="panel-body">

					<form action="" role="form" id="" method="post" onsubmit="return validateForm()">
						{{csrf_field()}}
						<div class="container">
							<div class="row">
								
								<input type="hidden" name="loading_id" value="{{$current_loading->id}}">

								<div class="col-md-4">
									<div class="form-group">
										<label for="freight">Freight</label>
										<input type="text" name="freight" id="freight" class="form-control" value="{{$current_loading->freight}}">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="truck_number">Truck Number</label>
										<input type="text" name="truck_number" id="truck_number" class="form-control" value="{{$current_loading->truck_number}}">
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
