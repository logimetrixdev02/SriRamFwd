@extends('dashboard.layouts.app')
@section('title','Stock Report')
@section('content')
<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">Retailer Potaintial Sale Report</li>
			</ul>
		</div>
		<div class="page-content">
			<h3 class="header smaller lighter blue">Retailer Potaintial Sale Report</h3>


			<div class="row">
				<div class="col-xs-12">

					<form action="" method="POST" role="form">
						<div class="row">
							@csrf
							<div class="col-md-3">
								<div class="form-group">
									<label for="master_rake_id">Session</label>
									<select class="form-control select2" name="session_id" id="session_id">
										<option value="">Select Session</option>
										@foreach($sessions as $session)
										<option value="{{$session->session}}" {{isset($session_id) && $session_id==$session->session ? "selected":""}}>{{$session->session}}</option>
										@endforeach()
									</select>
									
									<span class="label label-danger" style="display: none;"></span>
									
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									 <input type="submit" name="filter" id="filter"  class="btn btn-sm btn-primary" value="Filter" style="margin-top: 20px;">
										
								</div>
							</div>
						</div>
					</form>

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
						Retailer Potaintial Sale Report
					</div>

					<div class="table-responsive">
						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Retailer</th>
										<th>Product</th>
										<th>Aprail</th>
										<th>May</th>
										<th>June</th>
										<th>July</th>
										<th>August</th>
										<th>September</th>
										<th>October</th>
										<th>November</th>
										<th>December</th>
										<th>January</th>
										<th>February</th>
										<th>March</th>

									</tr>
									
								</thead>
								<tbody>
								    @if(isset($orders))

								    @foreach($orders as $o)
									<tr>
										<td >{{getretailer($o->retailer_id)->name}}</td>
										<td >{{getModelById('Product',$o->product_id)->name}}</td>
										  @foreach($month_codes as $moc)
										@if($moc==$o->m)
                                          <td class="bg bg-success">{{$o->qty}}</td>
										@else
										<td>0</td>
										@endif
										@endforeach
						
									</tr>
                                    @endforeach
									@endif
									
									
								</tbody>

								
</table>
</div>
</div>

</div>
</div>

</div>
</div>
</div>


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

<script type="text/javascript">
	jQuery(function($) {
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