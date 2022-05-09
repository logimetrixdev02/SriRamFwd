@extends('dashboard.layouts.app')
@section('title','Dealer SMS  Report')
@section('content')
<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">Dealer SMS {{__('messages.Report')}}</li>
			</ul>
		</div>
		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Dealer SMS {{__('messages.Report')}}</h3>
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
						SMS Send to Dealer
					</div>

					<div class="table-responsive">
						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<td>Dealer</td>
										<td>Company</td>
										<td>Mobile Number</td>
										<td>Message</td>
										<td>Sent At</td>
									</tr>
								</thead>                                
								@if(!is_null($dealerSMSData))
								@foreach($dealerSMSData as $data)
								<td>{{$data->dealer_name}}</td>
								<td>{{$data->company_name}}</td>
								<td>{{$data->mobile_number}}</td>
								<td>{{$data->message}}</td>
								<td>{{$data->created_at}}</td>
								@endforeach
								@endif
								<tbody>
								</tbody>
							</table>
						</div>
					</div></br>
				</div>
			</div>
		</div><!-- /.page-content -->
	</div><!---/.main-content-inner-->
</div><!---/.main-content-->
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