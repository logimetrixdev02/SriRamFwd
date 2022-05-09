@extends('dashboard.layouts.app')
@section('title','Buffer Report')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">{{__('messages.Buffer')}} {{__('messages.Report')}}</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Monthly Rebate Report</h3>

					<form action="" method="POST" role="form">
						<div class="row">
							{{csrf_field()}}
							
							<div class="col-md-3">
								<div class="form-group">
								<label>Month</label>
								<select name="month" class="form-control select2">
									<option value="01">January</option>
									<option value="02">February</option>
									<option value="03">March</option>
									<option value="04">April</option>
									<option value="05">May</option>
									<option value="06">June</option>
									<option value="07">July</option>
									<option value="08">August</option>
									<option value="09">September</option>
									<option value="010">October</option>
									<option value="11">November</option>
									<option value="12">December</option>
								</select>
							</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
								<label>Year</label>
								<select name="year" class="form-control select2">
									<option value="2019">2019</option>
									<option value="2020">2020</option>
									<option value="2021">2021</option>
									<option value="2022">2022</option>
									
								</select>
							</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</div>
					</form>


				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">

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

					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Total Rebate Calculation Of Month : Year : 
					</div>

					<div class="table-responsive">
						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Company name</th>
										<th>Rebate Amount</th>
										<th>Rebate Get From Company</th>
									</tr>
								</thead>

								<tbody>
									@php
									$total = 0;
									@endphp
									@foreach($rebates as $rebate)
										<tr>
											<td>{{getModelById('ProductCompany',$rebate->product_company_id)->name}}</td>
										
											<td>{{$rebate->total_claim}}</td>
											<td><input type="text" name="company_rebate"/></td>
										</tr>
										@php
									$total = $total + $rebate->total_claim;
									@endphp
									@endforeach
									<tr>
										<td>Total Cliam Of The Month</td>
										<td>{{$total}}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>




		</div><!-- /.page-content -->
	</div>
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



	@endsection
@endsection