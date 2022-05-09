@extends('dashboard.layouts.app')
@section('title','All Invoices')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">All Invoices</li>
			</ul>
		</div>

		<div class="page-content">
			<h3 class="header smaller lighter blue">All Invoices</h3>


			
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
						List Of All Invoices
						<div class="widget-toolbar no-border">
							
						</div>

					</div>

					<div class="table-responsive">

						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>

										<th>Loading Slip No</th>
										<th>Order Id</th>
										<th>Invoice No</th>
										<th>Invoice Date</th>
										<th>Rake / Godown</th>
										<th>Dealer</th>
										<th>Retailer</th>
										<th>Product</th>
										<th>Quantity</th>
										<th>Unit</th>
										<th>Transporter</th>
										<th>Vehicle No</th>
										<th>Action</th>
									</tr>
								</thead>

								<tbody>
									@foreach($invoices as $invoice)
									<tr id="tr_{{$invoice->id}}">
										<td>{{$invoice->id}}</td>
										<td>{{$invoice->order_id}}</td>
										<td>{{$invoice->invoice_no}}</td>
										<td>{{$invoice->invoice_date}}</td>
										<td>{{$invoice->order_from == 1 ? 'Rake' : 'Godown'}} <br/> (@if($invoice->order_from == 1) {{$invoice->rake_point_name}} @else {{$invoice->from_warehouse_name}} @endif)</td>
										
										<td>{{$invoice->dealer_name}}</td>
										<td>{{$invoice->retailer_name}}</td>
										<td>{{$invoice->product_name}}</td>
										<td>{{$invoice->quantity}}</td>
										<td>{{$invoice->unit_name}}</td>
										<td>{{$invoice->transporter_name}}</td>
										<td>{{$invoice->transport_mode_name}} <br/>( {{$invoice->vehicle_no}} )</td>
										
										<td>
											@if($invoice->slip_status == 'dispatched')
											<a href="/user/print-loading-slip-invoice/{{$invoice->id}}" class="btn btn-xs btn-info" >
												<i class="ace-icon fa fa-print bigger-120"></i>
											</a>
											@endif
											
										</td>
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

	function approved_order_now(id){
		var url = window.location.origin+'/order/change-status';
		var data = {};
		data.order_id = id;
		data._token = $('meta[name="_token"]').attr('content');
		$('.loading-bg').show();
		$.ajax({
			url:url,
			data:data,
			type:'post',
			dataType:'json',
			success:function(responce){
				$('.loading-bg').hide();
				if(responce.flag == true){
				$('#status_'+id).text('Approved');
				swal({ title: "Success!", text: responce.message, type: "success" });
				}else{
					swal({ title: "Error!", text: responce.message, type: "error" });
				}
				
			},
			error:function(error){
				$('.loading-bg').hide();
				console.log(error);
			}
		});
	}

	jQuery(function($) {

		$('.date-picker').datepicker({
			format: 'dd/mm/yyyy',
			endDate: '+0d',
			autoclose: true
		});

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

		@endsection
		@endsection
