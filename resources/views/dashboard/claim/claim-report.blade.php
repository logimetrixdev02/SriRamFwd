@extends('dashboard.layouts.app')
@section('title','Claims')
@section('style')
{{Html::style("assets/css/bootstrap-datepicker3.min.css")}}
@endsection

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<lui>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</lui>
				<li class="active">
					Claim Formats
				</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">
						Claim Formats
					</h3>

					<form action="" method="POST" role="form">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="product_company_id">Product Company</label>
									<select class="form-control select2" name="product_company_id" id="product_company_id">
										<option value="">Select Product Company</option>
										@foreach($product_companies as $product_company)
										<option value="{{$product_company->id}}">{{$product_company->name}}</option>
										@endforeach()
									</select>
									@if ($errors->has('product_company_id'))
									<span class="label label-danger">{{ $errors->first('product_company_id') }}</span>
									@endif
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


			<div class="clearfix">
				<div class="pull-right tableTools-container">
				</div>
			</div>
			<div class="table-header">
				Claim {{__('messages.Report')}}
				<div class="widget-toolbar no-border">
					<a class="btn btn-xs bigger btn-danger" href="{{URL('/user/generate-claim')}}">
						Add Claim Formats
						<i class="ace-icon fa fa-plus icon-on-right"></i>
					</a>
				</div>
			</div>

			<div class="table-responsive">
				<div class="dataTables_borderWrap">
					<div class="table-responsive">
						<table id="dynamic-table" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<td>Product Company</td>
									<td>Head</td>
									<td>Rate</td>
								</tr>
							</thead>

							<tbody>
								@if(isset($claims))
								@foreach($claims as $claim)
								<tr>
									<td>
										{{getModelById('ProductCompany',$claim->product_company_id)->name}}			
									</td>
									<td>{{$claim->claim_head}}</td>
									<td>{{$claim->rate}}</td>
								</tr>
								@endforeach
								@endif


							</tbody>
						</table>
					</div>
				</div>
			</div>
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


@endsection
@endsection