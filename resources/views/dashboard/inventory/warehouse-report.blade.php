@extends('dashboard.layouts.app')
@section('title','Warehouse Daliy Report')
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
				<li class="active"> {{__('messages.Warehouses')}} {{__('messages.Daliy')}} {{__('messages.Report')}}</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">
						{{__('messages.Warehouses')}} {{__('messages.Daliy')}} {{__('messages.Report')}}
					</h3>

					<form action="" method="POST" role="form">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="warehouse_id"> {{__('messages.Warehouses')}}</label>
									<select class="form-control select2" name="warehouse_id" id="warehouse_id">
										<option value="">  {{__('messages.Warehouses')}} {{__('messages.Select')}}</option>
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
									<label for="from_date"> {{__('messages.Date')}}</label>
									<input type="text" class="form-control date-picker" name="from_date" id="" placeholder="Date" readonly="readonly" value="{{$from_date}}">
									@if ($errors->has('from_date'))
									<span class="label label-danger">{{ $errors->first('from_date') }}</span>
									@endif
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">Submit</button>
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
					{{__('messages.Warehouses')}} {{__('messages.Daliy')}} {{__('messages.Report')}}
				</div>

				<div class="table-responsive">
					<div class="dataTables_borderWrap">
						<div class="table-responsive">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th></th>
										<th> {{__('messages.LoadingSlip')}} #</th>
										<th> {{__('messages.Token')}} #</th>
										<th> {{__('messages.Date')}} </th>
										<th> {{__('messages.Party')}}<br/> {{__('messages.Name')}} </th>
										<th>{{__('messages.ProductCompany')}}</th>
										<th> {{__('messages.Product')}} <br/> {{__('messages.Name')}} </th>
										<th> {{__('messages.TruckNumber')}}</th>
										<th> {{__('messages.Transporter')}} <br/> {{__('messages.Name')}}</th>
										<th> {{__('messages.Freight')}}</th>
										<th> {{__('messages.FreightPaymentMode')}}<br/> <br/></th>
										<th> {{__('messages.Quantity')}}</th>
										<th>Recieved Quantity</th>
										<th> {{__('messages.Rate')}}</th>
										<th>Bill(Invoice) #</th>
									</tr>
								</thead>

								<tbody>
									@if(isset($product_loadings))
									@foreach($product_loadings as $product_loading)
									<tr id="tr_{{$product_loading->id}}">
										<td>
											@if(is_null($product_loading->token))

											@if(is_null($product_loading->from_warehouse_id))
											<img src="{{URL('/assets/images/inward1.png')}}" height="30">
											@else
											<img src="{{URL('/assets/images/outward.png')}}" height="30">
											@endif

											@else
											@if($product_loading->token->token_type == 1)
											<img src="{{URL('/assets/images/inward1.png')}}" height="30">
											@else
											<img src="{{URL('/assets/images/outward.png')}}" height="30">
											@endif
											@endif
											
										</td>
										<td>{{$product_loading->id}}</td>
										<td>
											@if(!is_null($product_loading->token))
											{{$product_loading->token->unique_id}}
											@endif
										</td>

										<td>{{date('d-m-Y',strtotime($product_loading->created_at))}}
										</td>

										<td>
											@if($product_loading->loading_slip_type ==1)
											@if(!is_null($product_loading->retailer_id))
											<b>
												{{getModelById('Retailer',$product_loading->retailer_id)->name}}({{getModelById('Retailer',$product_loading->retailer_id)->address}})
											</b>
											<br>
											{{getModelById('Dealer',$product_loading->dealer_id)->name}}({{getModelById('Dealer',$product_loading->dealer_id)->address1}})
											@else
											<b>
												{{getModelById('Dealer',$product_loading->dealer_id)->name}} <br>({{getModelById('Dealer',$product_loading->dealer_id)->address1}})
											</b>

											@endif

											@else
											<b>
												{{getModelById('Warehouse',$product_loading->warehouse_id)->name}}
											</b>
											<br>
											{{getModelById('Warehouse',$product_loading->warehouse_id)->location}}				
											@endif			
										</td>
										<td><b>{{getModelById('ProductCompany',$product_loading->product_company_id)->name}}</b></td>
										<td>{{$product_loading->product_name}}</td>
										<td>{{$product_loading->truck_number}}</td>
										<td>{{$product_loading->transporter_name}}</td>
										<td>{{ $product_loading->freight}}</td>
										<td>{{is_null(getModelById('Token', $product_loading->token_id))?"":getModelById('Token', $product_loading->token_id)->delivery_payment_mode}}	
										</td>
										<td>{{$product_loading->quantity}} {{$product_loading->unit_name}}</td>
										<td>{{$product_loading->recieved_quantity}}</td>
										<td>{{is_null(getModelById('Token', $product_loading->token_id))?"":getModelById('Token', $product_loading->token_id)->rate}}</td>
										<td>
											@if(!is_null($product_loading->loading_slip_invoice))
											<a href="{{URL('user/loading-slip-invoices-details/'.$product_loading->loading_slip_invoice->id)}}" target="_blank">{{$product_loading->loading_slip_invoice->invoice_number}}
											</a>
											@endif
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
//initiate dataTables plugin
//
$('.date-picker').datepicker({
	autoclose: true,
	todayHighlight: true
})
.next().on(ace.click_event, function(){
	$(this).prev().focus();
});



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
		autoPrint: false,
		message: 'This print was produced using the Print button for DataTables'
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
<script type="text/javascript">
	function showProducts(id){
		if(id == ""){
			swal('Error','product_loading Is Missing');
		}else{
			$('.loading-bg').show();
			$.ajax({
				url: "{{url('/user/product-loading-details/')}}"+"/"+id,
				type: 'GET',
				success:function(data){
					$('.loading-bg').hide();
					$('#details').html(data);
					$('#productDetailsModal').modal('toggle');
				}
			});
		}
	}
</script>
@endsection
@endsection
