@extends('dashboard.layouts.app')
@section('title','Generate Claims')
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
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">Generate Claims</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="page-header">
				<h1>
					Generate Claims
				</h1>
			</div><!-- /.page-header -->

			<form action="{{URL('\user\print-claims')}}" role="form" id="generateClaimForm" method="post">
				{{csrf_field()}}
				<div class="container">
					<div class="row">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">Generate Claim</h3>
							</div>

							<div class="panel-body">
								<div class="col-md-3">
									<div class="form-group">
										<label for="master_rake_id"> {{__('messages.masterrake')}}</label>
										<select class="form-control select2" name="master_rake_id" id="master_rake_id">
											<option value=""> {{__('messages.SelectMasterRake')}}</option>
											@foreach($master_rakes as $master_rake)
											<option value="{{$master_rake->id}}" {{isset($master_rake_id) && $master_rake_id==$master_rake->id ? "selected":""}}>{{$master_rake->name}}</option>
											@endforeach()
										</select>
										@if ($errors->has('master_rake_id'))
										<span class="label label-danger">{{ $errors->first('master_rake_id') }}</span>
										@endif
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="company_id">Company</label>
										<select class="form-control select2" name="company_id" id="company_id">
											<option value="">Select Company</option>
											@foreach($companies as $company)
											<option value="{{$company->id}}" {{isset($company_id) && $company_id==$company->id ? "selected":""}}>{{$company->name}}</option>
											@endforeach()
										</select>
										@if ($errors->has('company_id'))
										<span class="label label-danger">{{ $errors->first('company_id') }}</span>
										@endif
									</div>
								</div>

								<div class="clearfix"></div>
								<div class="col-md-3">
									<div class="form-group">
										<button type="submit" class="btn btn-primary">Submit</button>
									</div>
								</div>
							</div>

						</div>


					</div>
				</div>
			</form>
		</div>
	</div><!-- /.page-content -->
</div><!-- /.main-content -->

@section('script')

{{ Html::script("assets/js/jquery.dataTables.min.js")}}
{{ Html::script("assets/js/jquery.dataTables.bootstrap.min.js")}}
{{ Html::script("assets/js/dataTables.buttons.min.js")}}
{{ Html::script("assets/js/buttons.flash.min.js")}}
{{ Html::script("assets/js/buttons.html5.min.js")}}
{{ Html::script("assets/js/buttons.print.min.js")}}
{{ Html::script("assets/js/buttons.colVis.min.js")}}
{{ Html::script("assets/js/dataTables.select.min.js")}}
{{ Html::script("assets/js/ace-elements.min.js")}}
{{ Html::script("assets/js/ace.min.js")}}
{{ Html::script("assets/js/bootstrap-datepicker.min.js")}}
<script type="text/javascript">
</script>
@endsection
@endsection