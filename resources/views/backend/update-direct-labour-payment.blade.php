@extends('dashboard.layouts.app')
@section('title','Direct Labour Payment')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Backend</a>
				</li>
				<li class="active">Direct Labour Payment</li>
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
					<label class="sr-only" for="direct_labour_payment">Direct Labour Payment</label>
					<select class="form-control select2" name="direct_labour_payment" id="direct_labour_payment" required="required">
						<option value="">Select Labour Payment </option>
						@foreach($direct_labour_payments as $direct_labour_payment)
						<option value="{{$direct_labour_payment->id}}" {{isset($current_direct_labour_payment) && $current_direct_labour_payment->id == $direct_labour_payment->id ? "selected":""}}>{{$direct_labour_payment->id}}</option>
						@endforeach
					</select>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
			@if(isset($current_direct_labour_payment) && !is_null($current_direct_labour_payment))
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Update Labour payment</h3>
				</div>
				<div class="panel-body">

					<form action="" role="form" id="" method="post" onsubmit="return validateForm()">
						{{csrf_field()}}
						<div class="container">
							<div class="row">
								
								<input type="hidden" name="labour_payment_id" value="{{$current_direct_labour_payment->id}}">


								<div class="col-md-4">
									<div class="form-group">
										<label for="labour_name">Master Rakes</label>
										
										<select class="form-control select2" name="master_rake_id" id="master_rake_id" required="required">
											<option value="">Select Rake </option>
											@foreach($master_rakes as $master_rake)
											<option value="{{$master_rake->id}}" {{isset($current_direct_labour_payment) && $current_direct_labour_payment->master_rake_id == $master_rake->id ? "selected":""}}>{{$master_rake->name}}</option>
											@endforeach
										</select>

									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="warehouse_id">Warehouses</label>
										
										<select class="form-control select2" name="warehouse_id" id="warehouse_id" required="required">
											<option value="">Select Warehouse </option>
											@foreach($warehouses as $warehouse)
											<option value="{{$warehouse->id}}" {{isset($current_direct_labour_payment) && $current_direct_labour_payment->warehouse_id == $warehouse->id ? "selected":""}}>{{$warehouse->name}}</option>
											@endforeach
										</select>

									</div>
								</div>



								<div class="col-md-4">
									<div class="form-group">
										<label for="labour_name">Labour Name</label>
										<input type="text" name="labour_name" id="labour_name" class="form-control" value="{{$current_direct_labour_payment->labour_name}}">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="amount">Amount</label>
										<input type="text" name="amount" id="amount" class="form-control" value="{{$current_direct_labour_payment->amount}}">
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
