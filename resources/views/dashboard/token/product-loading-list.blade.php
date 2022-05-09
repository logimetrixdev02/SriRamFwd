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
							

							@if(isset($master_rake_id))
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="product_id"> {{__('messages.Product')}}</label>
									<select class="form-control select2" name="product_id" id="product_id">
										<option value=""> Select {{__('messages.Product')}}</option>
										@foreach($master_rake_products as $master_rake_product)
										<option value="{{$master_rake_product->product_id}}" {{isset($product_id) && $product_id==$master_rake_product->product_id ? "selected":""}}>{{getModelById('Product',$master_rake_product->product_id)->name}}</option>
										@endforeach()
									</select>
								</div>
							</div>



							<div class="col-md-3">
								<div class="form-group">
									<label for="user_id"> User</label>
									<select class="form-control select2" name="user_id" id="user_id">
										<option value=""> Select User</option>
										@foreach($users as $user)
										<option value="{{$user['id']}}" {{isset($user_id) && $user_id==$user['id'] ? "selected":""}}>{{$user['name']}}</option>
										@endforeach()
									</select>
								</div>
							</div>

							@endif


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
						Results for "Latest Generated Product-Loading" <span class="badge">{{$quantity}}</span>
						<div class="widget-toolbar no-border">
							<!-- <a class="btn btn-xs bigger btn-danger" href="{{URL('/user/product-loading')}}">
								{{__('messages.generateNew')}}
								<i class="ace-icon fa fa-plus icon-on-right"></i>
							</a> -->
							@if(isset($master_rake_id))
							<a class="btn btn-xs bigger btn-danger" href="{{URL('/user/export-product-loadings/'.$master_rake_id)}}">
								Export 
								<i class="fa fa-file-excel-o"></i>
							</a>
							@endif
						</div>

					</div>

					<div class="table-responsive">
						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>


										<th> {{__('messages.LoadingSlip')}}#</th>
										<th> {{__('messages.Token')}}#</th>
										<th> {{__('messages.ProductCompany')}} </th>
										<th> {{__('messages.Wagon')}}#</th>
										<th> {{__('messages.Product')}} </th>
										<th> {{__('messages.Quantity')}}</th>
										<th> Rate</th>
										<th> Amount</th>
										<th> {{__('messages.Transporter')}}</th>
										<th> {{__('messages.Retailers')}}</th>
										<th> {{__('messages.Party')}}<br/> {{__('messages.Name')}} </th>
										<th> {{__('messages.Truck')}}#</th>
										<th> Warehouse <br>Keeper</th>
										<th></th>

									</tr>
								</thead>

								<tbody>
									@if(isset($product_loadings))
									@foreach($product_loadings as $product_loading)
									<tr id="tr_{{$product_loading->id}}">

										<td>{{$product_loading->id}}</td>
										<td>{{$product_loading->token_id}}</td>
										<td>{{$product_loading->product_company_name}}</td>
										<td>{{$product_loading->wagon_number}}</td>
										<td>{{ $product_loading->product_name}}</td>
										<td>{{$product_loading->quantity}}/{{$product_loading->unit_name}}</td>
										<td>{{ !is_null($product_loading->token_id) ? $product_loading->token->rate :""}}</td>
										<td>
											@if(!is_null($product_loading->token_id))
											{{$product_loading->quantity * $product_loading->token->rate}}
											@endif
										</td>
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
										<td>{{getModelById('User',$product_loading->user_id)->name}}</td>

										<td>
											<a href="/user/print-loading-slip/{{$product_loading->id}}" class="btn btn-xs btn-info" >
												<i class="ace-icon fa fa-print bigger-120"></i>
											</a>
											{{-- @if(!is_null($product_loading->token_id))
												@if(is_null($product_loading->token->master_rake_id) || $product_loading->token->to_type == 2)
												<a href="javascript:;" onclick="printTaxInvoice({{$product_loading->token->id}},{{$product_loading->token->company_id}})" class="btn btn-xs btn-info" >
													<i class="ace-icon fa fa-calculator  bigger-120"></i>
												</a>
												@endif
												@endif --}}
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


	<form action="" id="taxInvoiceForm" method="post">
		<div class="modal fade" id="taxInvoiceModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">   {{__('messages.Invoices')}}  {{__('messages.Details')}}</h4>
					</div>
					<div class="modal-body">
						{{csrf_field()}}
						<input type="hidden" name="token_id" id="token_id">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="company_id">Company {{__('messages.company')}}</label>
									<select class="form-control select2" name="company_id" id="company_id" required="required">
										@foreach($companies as $company)
										<option value="{{$company->id}}">{{$company->name}}</option>
										@endforeach()
									</select>
								</div>
							</div>


							<div class="col-md-4">
								<div class="form-group">
									<label for="invoice_type_id">{{__('messages.Invoices')}} {{__('messages.Type')}}</label>
									<select class="form-control select2" name="invoice_type_id" id="invoice_type_id" required="required">
										<option value="">Select Invoice Type {{__('messages.')}} {{__('messages.')}} {{__('messages.')}}</option>
										@foreach($invoice_types as $invoice_type)
										<option value="{{$invoice_type->id}}">{{$invoice_type->invoice_type}}</option>
										@endforeach()
									</select>
								</div>
							</div>


							<div class="col-md-4">
								<div class="form-group">
									<label for="invoice_date">{{__('messages.Invoices')}} {{__('messages.Date')}}</label>
									<input type="text" name="invoice_date" id="invoice_date" class="form-control date-picker" readonly="" value="{{date('d/m/Y')}}" required="required">
								</div>
							</div>
							<div class="clearfix"></div>

							<div class="col-md-4">
								<div class="form-group">
									<label for="eway_bill_no">{{__('messages.eWayBillNumber')}}</label>
									<input type="text" name="eway_bill_no" id="eway_bill_no" class="form-control" required="required">
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label for="invoice_remark">{{__('messages.Invoices')}} {{__('messages.Remark')}}</label>
									<input type="text" name="invoice_remark" id="invoice_remark" class="form-control">
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label for="dispatched_through"> {{__('messages.DispatchedThrough')}}</label>
									<input type="text" name="dispatched_through" id="dispatched_through" class="form-control" placeholder="Dispatched Through">
								</div>
							</div>
							<div class="clearfix"></div>


							<div class="col-md-4">
								<div class="form-group">
									<label for="destination"> {{__('messages.Destination')}}</label>
									<input type="text" name="destination" id="destination" class="form-control" placeholder="Enter Destination">
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label for="terms_of_delivery"> {{__('messages.TermsOfDelivery')}} </label>
									<input type="text" name="terms_of_delivery" id="terms_of_delivery" class="form-control">
								</div>
							</div>	
							<div class="clearfix"></div>


						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Print {{__('messages.')}}</button>
					</div>
				</div>
			</div>
		</div>
	</form>

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
