@extends('dashboard.layouts.app')
@section('title','Product-Loading')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#"> {{__('messages.Home')}}</a>
				</li>
				<li class="active"> {{__('messages.ProductLoading')}}</li>
			</ul>
		</div>

		<div class="page-content">
			<h3 class="header smaller lighter blue"> Direct Unloading</h3>
			<div class="row">
				<div class="col-xs-12">
					<form action="" method="POST" role="form">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="warehouse_id"> Warehouse</label>
									<select class="form-control select2" name="warehouse_id" id="warehouse_id">
										<option value=""> Select Warehouse</option>
										@foreach($warehouses as $warehouse)
										<option value="{{$warehouse->id}}" {{isset($warehouse_id) && $warehouse_id==$warehouse->id ? "selected":""}}>{{$warehouse->name}}</option>
										@endforeach()
									</select>
									@if ($errors->has('warehouse_id'))
									<span class="label label-danger">{{ $errors->first('warehouse_id') }}</span>
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
						Results for "Latest Generated Product-Loading"
						<div class="widget-toolbar no-border">
						</div>

					</div>

					<div class="table-responsive">
						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th> Loading Slip#</th>
										<th> Unloading Slip#</th>
										<th> Unloading Warehouse</th>
										<th> {{__('messages.ProductCompany')}} </th>
										<th>Party</th>
										<th> {{__('messages.Product')}} </th>
										<th> {{__('messages.Quantity')}}</th>
										<th> Returned {{__('messages.Quantity')}}</th>
										<th> {{__('messages.Transporter')}}</th>
										<th> {{__('messages.Truck')}}#</th>
										<th>Freight Payment <br>Status</th>
										<th>Freight Paid </th>
										<th>Freight Paid <br>By</th>
										<th> Unloaded Date</th>
										<th></th>

									</tr>
								</thead>

								<tbody>
									@if(isset($product_unloadings))
									@foreach($product_unloadings as $product_unloading)
									<tr id="tr_{{$product_unloading->id}}">

										<td>{{$product_unloading->product_loading->id}}</td>
										<td>{{$product_unloading->id}}</td>
										<td>{{getModelById('Warehouse',$product_unloading->warehouse_id)->name}}</td>
										<td>{{$product_unloading->product_company_name}}</td>
										<td>
											
											@if(!is_null($product_unloading->product_loading->retailer_id))
											<b>
												Retailer
											</b>	
											<br>
											{{$product_unloading->product_loading->retailer_name}}
											<br>
											({{getModelById('Dealer',$product_unloading->product_loading->dealer_id)->name}})			
											@elseif($product_unloading->product_loading->loading_slip_type ==1 && is_null($product_unloading->product_loading->retailer_id))
											<b>
												Dealer
											</b>
											<br>
											{{getModelById('Dealer',$product_unloading->product_loading->dealer_id)->name}}										
											@else
											<b>
												Warehouse
											</b>
											<br>
											{{getModelById('Warehouse',$product_unloading->product_loading->warehouse_id)->name}}				
											@endif	

										</td>
										<td>{{ $product_unloading->product_name}}</td>
										<td>{{$product_unloading->product_loading->quantity}}</td>
										<td>{{$product_unloading->quantity}}</td>
										<td>{{$product_unloading->transporter_name}}</td>
										<td>{{$product_unloading->truck_number}}</td>
										<td>
											@if($product_unloading->is_freight_paid == 1)
											<span class="label label-success">Paid</span>
											@else
											<span class="label label-warning">Pending</span>
											@endif
										</td>

										<td>
											@if($product_unloading->is_freight_paid == 1)
											{{$product_unloading->freight_amount_paid}}
											@else
											--
											@endif
										</td>


										<td>
											@if($product_unloading->is_freight_paid == 1)
											{{getModelById('User',$product_unloading->freight_paid_by)->name}}
											@else
											--
											@endif
										</td>


										<td>{{date('d/m/Y H:i',strtotime($product_unloading->created_at))}}</td>

										<td>
											<a href="/user/print-unloading-slip/{{$product_unloading->id}}" class="btn btn-xs btn-info" >
												<i class="ace-icon fa fa-print bigger-120"></i>
											</a>
										</td>
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
		<!-- /.page-content -->
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


	function printTaxInvoice(token_id,company_id){
		$('#taxInvoiceModal').modal('toggle');
		var action = "{{URL('/')}}"+"/user/token/tax-invoice/"+token_id;
		$('#taxInvoiceForm').attr('action',action);
		$('#token_id').val(token_id);
		$('#company_id').val(company_id);
	}



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
