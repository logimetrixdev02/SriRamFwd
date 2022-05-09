@extends('dashboard.layouts.app')
@section('title','Wagon Unloadings')
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
					<a href="#"> {{__('messages.Home')}}</a>
				</li>
				<li class="active"> Wagon {{__('messages.Productloading')}}</li>
			</ul>
		</div>

		<div class="page-content">
			<h3 class="header smaller lighter blue"> Wagon {{__('messages.Productloading')}}</h3>

			<div class="row">
				<div class="col-xs-12">
					<form action="" method="POST" role="form">
						<div class="row">
							{{csrf_field()}}
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
									<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">


					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>

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

					<div class="table-header">
						Results for "Latest Generated Wagon Unloadings"
						

					</div>

					<div class="table-responsive">

						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>

										<th> Wagon <br>{{__('messages.LoadingSlip')}} #</th>
										<th> {{__('messages.Date')}}</th>
										<th> {{__('messages.Rake')}}</th>
										<th>Wagon #</th>
										<th> {{__('messages.Labour')}} </th>
										<th>Quantity</th>
										<th>Wagon Rate</th>
										<th>Amount To Pay</th>
										<th>{{__('messages.PaymentStatus')}}</th>
										<th>{{__('messages.PaidBy')}}</th>
										<th>{{__('messages.PaidAmount')}}</th>
										<th>{{__('messages.Payment')}}<br>{{__('messages.Date')}}</th>
										<th></th>
									</tr>
								</thead>

								<tbody>

									@php
									$total_to_pay = 0;
									$total_paid = 0;
									@endphp

									@foreach($wagon_unloadings as $wagon_unloading)
									<tr>
										<td>{{$wagon_unloading->id}}</td>
										<td>{{date('d/m/Y',strtotime($wagon_unloading->created_at))}}</td>
										<td>
											@if(!is_null(getModelById('MasterRake',$wagon_unloading->master_rake_id)))
											{{ getModelById('MasterRake',$wagon_unloading->master_rake_id)->name}}
											@endif
										</td>
										
										<td>{{$wagon_unloading->wagon_number}}</td>
										<td>{{$wagon_unloading->labour_name}}</td>
										<td>{{$wagon_unloading->quantity}}</td>
										<td>{{$wagon_unloading->wagon_rate}}</td>
										<td>{{$wagon_unloading->wagon_rate}}</td>
										<td>
											@if($wagon_unloading->is_paid == 0)
											<span class="label label-warning">Not Paid</span>
											@else
											<span class="label label-success">Paid</span>
											@endif
										</td>
										<td>
											@if($wagon_unloading->is_paid == 1)
											{{ getModelById('User',$wagon_unloading->paid_by)->name}}
											@else
											--
											@endif
										</td>
										<td>
											@if($wagon_unloading->is_paid == 1)
											{{$wagon_unloading->paid_amount}}
											@else
											--
											@endif
										</td>

										<td>
											@if($wagon_unloading->is_paid == 1)
											{{date('d/m/Y',strtotime($wagon_unloading->payment_date))}}
											@else
											--
											@endif
										</td>

										<td><a href="{{URL('/user/print-wagon-unloading-slip/'.$wagon_unloading->id)}}"><i class="fa fa-print fa-2x"></i></a></td>

									</tr>

									@php
									if($wagon_unloading->is_paid == 1){
									$total_paid = $total_paid + ($wagon_unloading->paid_amount);
								}
								$total_to_pay = $total_to_pay + ($wagon_unloading->wagon_rate);
								@endphp

								@endforeach
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td>{{$total_paid}}/{{$total_to_pay}}</td>
									<td></td>

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
{{ Html::script("assets/js/bootstrap-datepicker.min.js")}}



<script type="text/javascript">


	jQuery(function($) {

		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		})

//initiate dataTables plugin
var myTable = 
$('#dynamic-table').DataTable( {
	bAutoWidth: false,
	"aaSorting": [],
} );

$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

new $.fn.dataTable.Buttons( myTable, {
	buttons: [
	{
		"extend": "colvis",
		"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
		"className": "btn btn-white btn-primary btn-bold",
		columns: ':not(:first):not(:last)'
	},
	{
		"extend": "copy",
		"text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
		"className": "btn btn-white btn-primary btn-bold"
	},
	{
		"extend": "csv",
		"text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
		"className": "btn btn-white btn-primary btn-bold"
	},
	{
		"extend": "excel",
		"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
		"className": "btn btn-white btn-primary btn-bold"
	},
	{
		"extend": "pdf",
		"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
		"className": "btn btn-white btn-primary btn-bold"
	},
	{
		"extend": "print",
		"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
		"className": "btn btn-white btn-primary btn-bold",
		autoPrint: true,
		message: 'IManager',
		exportOptions: {
			columns: ':visible'
		}
	}		  
	]
} );
myTable.buttons().container().appendTo( $('.tableTools-container') );

//style the message box
var defaultCopyAction = myTable.button(1).action();
myTable.button(1).action(function (e, dt, button, config) {
	defaultCopyAction(e, dt, button, config);
	$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
});


var defaultColvisAction = myTable.button(0).action();
myTable.button(0).action(function (e, dt, button, config) {

	defaultColvisAction(e, dt, button, config);


	if($('.dt-button-collection > .dropdown-menu').length == 0) {
		$('.dt-button-collection')
		.wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
		.find('a').attr('href', '#').wrap("<li />")
	}
	$('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
});

})
</script>

@endsection
@endsection
