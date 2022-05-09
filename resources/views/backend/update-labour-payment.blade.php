@extends('dashboard.layouts.app')
@section('title','Labour Payment')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Backend</a>
				</li>
				<li class="active">Labour Payment</li>
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
					<label class="sr-only" for="labour_payment">Labour Payment</label>
					<select class="form-control select2" name="labour_payment" id="labour_payment" required="required">
						<option value="">Select Labour Payment </option>
						@foreach($labour_payments as $labour_payment)
						<option value="{{$labour_payment->id}}" {{isset($current_labour_payment) && $current_labour_payment->id == $labour_payment->id ? "selected":""}}>{{$labour_payment->id}}</option>
						@endforeach
					</select>
				</div>

				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
			@if(isset($current_labour_payment) && !is_null($current_labour_payment))
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Update Labour payment</h3>
				</div>
				<div class="panel-body">

					<form action="" role="form" id="" method="post" onsubmit="return validateForm()">
						{{csrf_field()}}
						<div class="container">
							<div class="row">
								
								<input type="hidden" name="labour_payment_id" value="{{$current_labour_payment->id}}">

								<div class="col-md-4">
									<div class="form-group">
										<label for="labour_name">Labour Name</label>
										<input type="text" name="labour_name" id="labour_name" class="form-control" value="{{$current_labour_payment->labour_name}}">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="rate">Rate</label>
										<input type="text" name="rate" id="rate" class="form-control" value="{{$current_labour_payment->rate}}">
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
