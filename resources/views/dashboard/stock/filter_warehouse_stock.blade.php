<?php 
use App\Http\Controllers\StockController;
?>


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
				<li class="active">Warehouse Stock</li>
			</ul>
		</div>
		<div class="page-content">
			<h3 class="header smaller lighter blue">Warehouse Stock</h3>


			<div class="row">
				<div class="col-xs-12">

					<form action="{{url('warehousefilter')}}" method="post" role="form">
						<div class="row">
							@csrf
							<div class="col-md-3">
								<div class="form-group">
									<label for="master_rake_id">Warehouse Stock</label>
									<select class="form-control select2" name="session_id" id="session_id">
										<option value="">Warehouse Stock</option>
									  @foreach($inventories as $invntry=>$inventorie)
										<option value="" id="warehouse_name">
											{{$inventorie->warehouse_name}}
										</option>
										@endforeach
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
						Warehouse Report
					</div>

					<div class="table-responsive">
						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										
 	                                    <th>Id</th>
										<th>Product Company</th>
										<th>Warehouse</th>
									     <th>Product Brand</th>
										<th>Product</th>
										 <th>Unit</th>
										 <th>quantity</th>

									</tr>
									
								</thead>
								<tbody>
                                   @foreach($inventories as $invntry=>$inventorie)
                                   <tr>
                                   	<td>{{$invntry+1}}</td>
                                   	<td>{{$inventorie->product_companies_name}}</td>
                                   	<td>{{$inventorie->warehouse_name}}</td>
                                   	<td>{{$inventorie->brand_name}}</td>
                                   	<td>{{$inventorie->product_name}}</td>
                                   	<td>{{$inventorie->units}}</td>
                                   	<td>{{$inventorie->quantity}}</td>
                                   </tr>
                                   	@endforeach
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

	$('#filter').click(function(){
			
				var warehouse_name = $('#warehouse_name').val();


				if(dealer_id !='')
				{
					
				$('#dynamic-table').DataTable().destroy();
				display(warehouse_name);
				}else if(warehouse_name!=''){
						$('#dynamic-table').DataTable().destroy();
					display(warehouse_name);
				}else{
					alert('Warehouse is required');
				}

			
	 
		});



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
})
</script>

@endsection
@endsection