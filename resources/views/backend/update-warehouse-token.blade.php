@extends('dashboard.layouts.app')
@section('title','Token')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Backend</a>
				</li>
				<li class="active">Token</li>
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
					<label class="sr-only" for="token">Token</label>
					<select class="form-control select2" name="token" id="token" required="required">
						<option value="">Select Token </option>
						@foreach($tokens as $token)
						<option value="{{$token->id}}" {{isset($current_token) && $current_token->id == $token->id ? "selected":""}}>{{$token->id}}</option>
						@endforeach
					</select>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
			@if(isset($current_token) && !is_null($current_token))
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Update Token</h3>
				</div>
				<div class="panel-body">

					<form action="" role="form" id="" method="post" onsubmit="return validateForm()">
						{{csrf_field()}}
						<div class="container">
							<div class="row">
								
								<input type="hidden" name="token_id" value="{{$current_token->id}}">
								<div class="col-md-4">
									<div class="form-group">
										<label for="company_id">Company</label>
										<select class="form-control select2" name="company_id" id="company_id" required="required">
											<option value="">Select Company </option>
											@foreach($companies as $company)
											<option value="{{$company->id}}" {{$company->id == $current_token->company_id ? "selected":""}}>{{$company->name}}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="unit_id">Unit</label>
										<select class="form-control select2" name="unit_id" id="unit_id" required="required">
											<option value="">Select Unit </option>
											@foreach($units as $unit)
											<option value="{{$unit->id}}" {{$unit->id == $current_token->unit_id ? "selected":""}}>{{$unit->unit}}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="rate">Rate</label>
										<input type="text" name="rate" id="rate" class="form-control" value="{{$current_token->rate}}">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="delivery_payment_mode">Freight Payment Mode</label>
										<select class="form-control select2" name="delivery_payment_mode" id="delivery_payment_mode" required="required">
											<option value="EX" {{$current_token->delivery_payment_mode == "EX" ? "selected":""}}>EX </option>
											<option value="FOR" {{$current_token->delivery_payment_mode == "FOR" ? "selected":""}}>FOR </option>
										</select>	
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
