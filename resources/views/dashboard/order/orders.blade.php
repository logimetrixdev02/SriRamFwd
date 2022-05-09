@extends('dashboard.layouts.app')

@section('title','Token')



@section('content')



<div class="main-content">

	<div class="main-content-inner">

		<div class="breadcrumbs ace-save-state" id="breadcrumbs">

			<ul class="breadcrumb">

				<li>

					<i class="ace-icon fa fa-home home-icon"></i>

					<a href="#">{{__('messages.Home')}}</a>

				</li>

				<li class="active">{{__('messages.Token')}}</li>

			</ul>

		</div>



		<div class="page-content">

			<h3 class="header smaller lighter blue">All Orders</h3>

		<div class="row">

			

				

 

				<div class="col-xs-12">

					<div class="clearfix">

						<div class="filter-form pull-left">

							{{-- <form action="{{url('user/orders')}}" method="post"> --}}

								{{-- @csrf --}}

								<div class="row ">

					                <div class="col-md-4 input-daterange">

					                    <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date"  readonly />

					                </div>

					                <div class="col-md-4 input-daterange">

					                    <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />

					                </div>

					                <div class="col-md-4">

					                    <input type="button" name="filter" id="filter"  class="btn btn-sm btn-primary" value="Filter">

										<input type="button" name="refresh" id="refresh" class="btn btn-sm btn-warning" value="Clear">



					                </div>

					            </div>

							{{-- </form> --}}

						</div>

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

						List Of letest Generated Orders

						<div class="widget-toolbar no-border">



							@if(\Auth::user()->role_id !=11)

							<a class="btn btn-xs bigger btn-danger" onclick="get_add_order_form()">

								New Order

								<i class="ace-icon fa fa-plus icon-on-right"></i>

							</a>

							@endif

						</div>



					</div>



					<div class="table-responsive">



						<div class="dataTables_borderWrap">

						<table id="dynamic-table" class="table table-striped table-bordered table-hover">

								<thead>

									<tr>



										<th>Order No</th>

										<th>Order Date</th>

										<th>Rake / Godown</th>

										<th>SAP Dealer Id</th>

										<th>Dealer</th>

										<th>Retailer</th>

										<th>Product</th>

										<th>Destination</th>

										<th>Qty</th>

										<th>R Qty</th>

										<th>Unit</th>

										<th>Truck No</th>

										<th>Invoice Date</th>

										<th>Order Status</th>

										<th>Loding Status</th>

										<th>Invoice Status</th>

										 {{-- <th>Action</th> --}}

										<th></th>

									</tr>

								</thead>



							

					</table>

				</div>

			</div>

		</div>

	</div>

</div><!-- /.page-content -->

</div>

</div><!-- /.main-content -->

<!-- Edit Warehouse Modal -->



<div class="modal fade" id="modalPopup">

	<div class="modal-dialog modal-md">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title">Add Order</h4>

			</div>

			<div class="modal-body" id="modalPopupBody">





			</div>

		</div>

	</div>

</div>







<div class="modal fade" id="modalPopupLoading">

	<div class="modal-dialog modal-lg" style="width: 90%">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title">Create Loading Slip</h4>

			</div>

			<div class="modal-body" id="modalPopupBodyLoading">





			</div>

		</div>

	</div>

</div>

<div class="modal fade" id="createInvoiceModalPopupLoading">

	<div class="modal-dialog modal-lg">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title">Create Loading Slip</h4>

			</div>

			<div class="modal-body" id="createInvoiceModalPopupBodyLoading">





			</div>

		</div>

	</div>

</div>

<div class="modal fade" id="editModalPopup">

	<div class="modal-dialog modal-lg">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

				<h4 class="modal-title">Edit Order</h4>

			</div>

			<div class="modal-body" id="editmodalPopupBody">





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

{{ Html::script("assets/js/bootstrap-datepicker.min.js")}}





<script type="text/javascript">



	function get_add_order_form(){



		$.ajax({

			url: "{{url('user/new-order')}}",

			type: 'GET',

			success:function(data){



				$('#modalPopupBody').html(data);

				$('#modalPopup').modal('toggle');

				$('#order').select2();

			}

		});

	}



	function get_edit_order_form(id){

		$.ajax({

			url: window.location.origin+"/user/edit-order/"+id,

			type: 'GET',

			success:function(data){



				if(data.success ==false){

					

				}else{

					$('#editmodalPopupBody').html(data);

				    $('#editModalPopup').modal('toggle');

				}



				

				//OnLoad();

			}

		});

	}



	function approved_order_now(id){





                       swal({ 

							title: "Do you want to Aprove?",

							// text: "SUMIT",

							type: "success",

							confirmButtonText: 'Ok',

							showCancelButton: true,

						},(function(isConfirm) {

						if (isConfirm) {

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

				$('#status_'+id).html('<span class="badge badge-success">Approved</span>');

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

		$('#dynamic-table').DataTable().draw();				

						} else {

							location.reload();

						}

					})

						)

		

	}



	jQuery(function($) {



		display();



			$('#filter').click(function(){

				var from_date = $('#from_date').val();

				var to_date = $('#to_date').val();



				if(from_date != '' &&  to_date != '')

				{

					

				$('#dynamic-table').DataTable().destroy();

				display(from_date, to_date);

				}

				else

				{

				alert('Both Date is required');

				}

	 

		});



			$('#refresh').click(function(){

				$('#from_date').val('');

				$('#to_date').val('');

				$('#dynamic-table').DataTable().destroy();

				display();;

			});



		$('.date-picker').datepicker({

			format: 'dd/mm/yyyy',

			endDate: '+0d',

			autoclose: true

		});



				$('.input-daterange').datepicker({

		

						format:'yyyy-mm-dd',

						autoclose:true

				});





			})

	

			function display(from_date = '', to_date = ''){

				

					var myTable=$('#dynamic-table').DataTable({

							"processing": true,

							"oLanguage": {

									"sProcessing": 

									"<b style='color:green;margin-top:5px;'>Orders Fetching...<b><i class='fa fa-spinner fa-spin' style='font-size:24px;color:rgb(75, 183, 245);'></i>",

									

								},

							"serverSide": true,

							"autoWidth": false,

							"aaSorting": [],

							"ajax":{

								// "type": 'POST',

								"url": "{{ route('user.order') }}",

								"data":{from_date:from_date, to_date:to_date},

							},

							

							"columns":[

								{ "data":"id" },

								{ "data": "order_date" },

								{ "data": "rake_godown" },

								{ "data": "dealer_id" },

								{ "data": "dealer_name" },

								{ "data": "retailer_name" },

								{ "data": "product_name" },

								{ "data": "destination" },

								{ "data": "quantity" },

								{ "data": "remaining_qty" },

								{ "data": "unit" },

								{ "data": "truck_no"},

								{ "data": "invoice_date" },

								{ "data": "order_status" },

								{ "data": "loading_status" },

								{ "data": "invoice_status" },
							

							{"data": "action" }

							]

				});



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

				

}



		

			function get_add_loading_slip_form(id){

				var id = $('#id_'+id).val();

				$.ajax({

					url: "{{url('user/add-loading')}}",

					type: 'GET',

					data : {

					id : id

					},

					success:function(data){

						$('#modalPopupBodyLoading').html(data);

						$('#modalPopupLoading').modal('toggle');

						//OnLoad();

					}

				});

			}

			function get_loading_slips(id){

				var id = $('#id_'+id).val();

				$.ajax({

					url: "{{url('user/show-loading')}}",

					type: 'GET',

					data : {

					id : id

					},

					success:function(data){

						$('#modalPopupBodyLoading').html(data);

						$('#modalPopupLoading').modal('toggle');

						//OnLoad();

					}

				});

			}

			

		</script>



		@endsection

		@endsection

