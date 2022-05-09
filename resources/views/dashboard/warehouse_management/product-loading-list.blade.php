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
			<h3 class="header smaller lighter blue"> {{__('messages.ProductLoading')}}</h3>
			<div class="row">
				<div class="col-xs-12">
					<form action="" method="POST" role="form">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="from_Warehouse_id"> {{__('messages.Warehouses')}}</label>
									<select class="form-control select2" name="from_Warehouse_id" id="from_Warehouse_id">
										<option value="">  {{__('messages.Warehouses')}} {{__('messages.Select')}}</option>
										@foreach($warehouses as $warehouse)
										<option value="{{$warehouse->id}}" {{isset($from_Warehouse_id) && $from_Warehouse_id==$warehouse->id ? "selected":""}}>{{$warehouse->name}}</option>
										@endforeach()
									</select>
									@if ($errors->has('from_Warehouse_id'))
									<span class="label label-danger">{{ $errors->first('from_Warehouse_id') }}</span>
									@endif
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="include_non_freight"> Include Non-Freight ?</label>
									<select class="form-control select2" name="include_non_freight" id="include_non_freight">
										<option value="0" {{isset($include_non_freight) && $include_non_freight==0 ? "selected":""}}>Yes</option>
										<option value="1" {{isset($include_non_freight) && $include_non_freight==1 ? "selected":""}}>No</option>
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

					</div>

					<div class="table-responsive">
						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>


										<th> {{__('messages.Date')}}</th>
										<th> {{__('messages.LoadingSlip')}}#</th>
										<th> {{__('messages.Token')}}#</th>
										<th> {{__('messages.ProductCompany')}} </th>
										<th> {{__('messages.Product')}} </th>
										<th> {{__('messages.Transporter')}}</th>
										<th> {{__('messages.Retailers')}}</th>
										<th> {{__('messages.Party')}}<br/> {{__('messages.Name')}} </th>
										<th> {{__('messages.Truck')}}#</th>
										<th> {{__('messages.Quantity')}}</th>
										<th> {{__('messages.Rate')}}#</th>
										<th> Amount</th>
										<th>Freight</th>
										<th>{{__('messages.PaymentStatus')}}</th>
										<th>{{__('messages.PaidBy')}}</th>
										<th>{{__('messages.PaidAmount')}}</th>
										<th>{{__('messages.Payment')}}<br>{{__('messages.Date')}}</th>
										<th></th>

									</tr>
								</thead>

								<tbody>
									@if(isset($product_loadings))
									@php
									$total_to_pay = 0;
									$total_paid = 0;
									@endphp
									@foreach($product_loadings as $product_loading)
									<tr id="tr_{{$product_loading->id}}">
										<td>{{date('d/m/Y',strtotime($product_loading->created_at))}}</td>
										<td>{{$product_loading->id}}</td>
										<td>{{getModelById('Token',$product_loading->token_id)->unique_id}}</td>
										<td>{{$product_loading->product_company_name}}</td>
										<td>{{ $product_loading->product_name}}</td>
										
										<td>{{$product_loading->transporter_name}}</td>
										<td>
											@if(!is_null($product_loading->retailer_id))
											{{$product_loading->retailer_name}}			
											@endif	
										</td>
										<td>
											@if($product_loading->loading_slip_type ==1)
											{{getModelById('Dealer',$product_loading->dealer_id)->name}}
											({{getModelById('Dealer',$product_loading->dealer_id)->address1}})				
											@else
											{{getModelById('Warehouse',$product_loading->warehouse_id)->name}}				
											@endif	
										</td>
										<td>{{$product_loading->truck_number}}</td>
										<td>{{$product_loading->quantity}}</td>
										<td>{{getModelById('Token',$product_loading->token_id)->rate}}</td>
										<td>
											@if(!is_null($product_loading->token_id))
											{{$product_loading->quantity * $product_loading->token->rate}}
											@endif
										</td>
										<td>{{$product_loading->freight}}</td>
										<td>
											@if($product_loading->is_freight_paid == 0)
											<span class="label label-warning">Not Paid</span>
											@else
											<span class="label label-success">Paid</span>
											@endif
										</td>
										<td>
											@if($product_loading->is_freight_paid == 1)
											{{ getModelById('User',$product_loading->freight_paid_by)->name}}
											@else
											--
											@endif
										</td>
										<td>
											@if($product_loading->is_freight_paid == 1)
											{{$product_loading->freight_paid_amount}}
											@else
											--
											@endif
										</td>

										<td>
											@if($product_loading->is_freight_paid == 1)
											{{date('d/m/Y',strtotime($product_loading->freight_pay_date))}}
											@else
											--
											@endif
										</td>

										<td>
											<a href="/user/print-loading-slip/{{$product_loading->id}}" class="btn btn-xs btn-info" >
												<i class="ace-icon fa fa-print bigger-120"></i>
											</a>
										</td>
									</tr>

									@php
									if($product_loading->is_freight_paid == 1){
									$total_paid = $total_paid + ($product_loading->freight_paid_amount);
								}
								$total_to_pay = $total_to_pay + ($product_loading->quantity * $product_loading->freight);
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
									<td></td>
									<td></td>
									<td></td>
									<td>{{$total_paid}}/{{$total_to_pay}}</td>
									<td></td>
									<td></td>
									<td></td>

								</tr>
								
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
