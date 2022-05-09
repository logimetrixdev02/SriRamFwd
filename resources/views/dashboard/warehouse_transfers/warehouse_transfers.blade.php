@extends('dashboard.layouts.app')
@section('title','Warehouse Transfers')
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
				<li class="active"> Warehouse Transfers</li>
			</ul>
		</div>

		<div class="page-content">
			<h3 class="header smaller lighter blue"> Warehouse Transfers</h3>

			<div class="row">
				<div class="col-xs-12">
					<form action="" method="POST" role="form">
						<div class="row">
							{{csrf_field()}}
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="from_warehouse_id"> From {{__('messages.Warehouses')}}</label>
									<select class="form-control select2" name="from_warehouse_id" id="from_warehouse_id">
										<option value="">  {{__('messages.Warehouses')}} {{__('messages.Select')}}</option>
										@foreach($warehouses as $warehouse)
										<option value="{{$warehouse->id}}" {{isset($from_warehouse_id) && $from_warehouse_id == $warehouse->id ? "selected":""}}>{{$warehouse->name}}</option>
										@endforeach()
									</select>
									@if ($errors->has('from_warehouse_id'))
									<span class="label label-danger">{{ $errors->first('from_warehouse_id') }}</span>
									@endif
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="to_warehouse_id"> To {{__('messages.Warehouses')}}</label>
									<select class="form-control select2" name="to_warehouse_id" id="to_warehouse_id">
										<option value="">  {{__('messages.Warehouses')}} {{__('messages.Select')}}</option>
										@foreach($warehouses as $warehouse)
										<option value="{{$warehouse->id}}" {{isset($to_warehouse_id) && $to_warehouse_id == $warehouse->id ? "selected":""}}>{{$warehouse->name}}</option>
										@endforeach()
									</select>
									@if ($errors->has('to_warehouse_id'))
									<span class="label label-danger">{{ $errors->first('to_warehouse_id') }}</span>
									@endif
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="product_brand_id"> Product Brand</label>
									<select class="form-control select2" name="product_brand_id" id="product_brand_id">
										<option value="">  Select Brand</option>
										@foreach($product_companies as $product_company)
										<option value="{{$product_company->id}}" {{isset($product_company_id) && $product_company_id == $product_company->id ? "selected":""}}>{{$product_company->name}}</option>
										@endforeach()
									</select>
									@if ($errors->has('product_company_id'))
									<span class="label label-danger">{{ $errors->first('product_company_id') }}</span>
									@endif
								</div>
							</div>


							<div class="col-md-3">
								<div class="form-group">
									<label for="product_id"> Product</label>
									<select class="form-control select2" name="product_id" id="product_id">
										<option value="">  Select Product</option>
										@foreach($products as $product)
										<option value="{{$product->id}}" {{isset($product_id) && $product_id == $product->id ? "selected":""}}>{{$product->name}}</option>
										@endforeach()
									</select>
									@if ($errors->has('product_id'))
									<span class="label label-danger">{{ $errors->first('product_id') }}</span>
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
						Results for "Latest Generated Standardization"
						

					</div>

					<div class="table-responsive">

						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>

										<th>#</th>
										<th>From Warehouse</th>
										<th>To Warehouse</th>
										<th>Brand</th>
										<th>Product</th>
										<th>Quantity</th>
										<th>Transporter</th>
										<th>Truck #</th>
										<th></th>
										
									</tr>
								</thead>

								<tbody>
									@foreach($warehouse_transfers as $warehouse_transfer)
									<tr>
										
										<td>{{$warehouse_transfer->id}}</td>
										<td>{{$warehouse_transfer->from_warehouse->name}}</td>
										<td>{{$warehouse_transfer->to_warehouse->name}}</td>
										<td>{{$warehouse_transfer->product_brand->name}}</td>
										<td>{{$warehouse_transfer->product->name}}</td>
										<td>{{$warehouse_transfer->quantity}} {{$warehouse_transfer->unit->unit_name}}</td>
										<td>{{$warehouse_transfer->transporter->name}}</td>
										<td>{{$warehouse_transfer->truck_number}}</td>

										<td><a href="{{URL('/user/print-warehouse_transfer-slip/'.$warehouse_transfer->id)}}"><i class="fa fa-print fa-2x"></i></a></td>

									</tr>

									@endforeach

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
