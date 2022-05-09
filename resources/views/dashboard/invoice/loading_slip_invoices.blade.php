@extends('dashboard.layouts.app')
@section('title','Loading Slip Invoices')
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
				<li class="active">{{__('messages.LoadingSlip')}} {{__('messages.Invoices')}} </li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">{{__('messages.LoadingSlip')}}{{__('messages.Invoices')}}</h3>

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


					<form action="" method="POST" role="form" id="FilterForm">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="dealer_id"> {{__('messages.Dealer')}}</label>
									<select class="form-control select2" name="dealer_id" id="dealer_id">
										<option value=""> Select Dealer </option>
										@foreach($dealers as $dealer)
										<option value="{{$dealer->id}}" {{ (isset($dealer_id) && $dealer_id==$dealer->id) ? "selected":""}}>{{$dealer->name}} ({{$dealer->address1}})</option>
										@endforeach()
									</select>
									@if ($errors->has('dealer_id'))
									<span class="label label-danger">{{ $errors->first('dealer_id') }}</span>
									@endif
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="retailers_id"> {{__('messages.Retailer')}}</label>
									<select class="form-control select2" name="retailer_id" >
										<option value=""> Select Retailer</option>
										@foreach($retailer as $retailers)
										<option value="{{$retailers->id}}" {{isset($retailers_id) && $retailers_id==$retailers->id ? "selected":""}}>{{$retailers->name}} ({{$retailers->address}})</option>
										@endforeach()
									</select>
									@if ($errors->has('retailer_id'))
									<span class="label label-danger">{{ $errors->first('retailer_id') }}</span>
									@endif
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</form>

						<div class="clearfix">
							<div class="pull-right tableTools-container">
							</div>
						</div>
						<div class="table-header">
							Results for "Latest Punched Loading Slip Invoices"
							<div class="widget-toolbar no-border">
								<a class="btn btn-xs bigger btn-danger" href="{{URL('/user/generate-loading-slip-invoice')}}">
									{{__('messages.GeneratedInvoices')}}
									<i class="ace-icon fa fa-plus icon-on-right"></i>
								</a>
							</div>

						</div>

						<div class="table-responsive">
							<div class="dataTables_borderWrap">
								<table id="dynamic-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>

											<th>{{__('messages.Invoices')}} {{__('messages.Number')}}</th>
											<th>{{__('messages.Invoices')}} {{__('messages.Date')}}</th>
											<th>{{__('messages.Invoices')}} {{__('messages.Amount')}}</th>
											<th>Remaining Amount</th>
											<th>Retailer</th>
											<th>{{__('messages.company')}} {{__('messages.Name')}}</th>
											<th></th>
										</tr>
									</thead>

									<tbody>
										@foreach($loading_slip_invoices as $loading_slip_invoice)
										<tr id="tr_{{$loading_slip_invoice->id}}">
											<td>{{$loading_slip_invoice->invoice_number}}</td>
											<td>{{date('d/m/Y',strtotime($loading_slip_invoice->invoice_date))}}</td>
											<td>{{$loading_slip_invoice->total}}</td>
											<td>{{$loading_slip_invoice->remaining_amount}}</td>
											<td>{{$loading_slip_invoice->retailer_name}}</td>
											<td>{{getModelById('Company',$loading_slip_invoice->company_id)->name}}</td>
											<td>
												{{--
													@if($loading_slip_invoice->remaining_amount != 0)
													<a href="javascript:;" onclick="payInvoice({{$loading_slip_invoice->id}},{{$loading_slip_invoice->remaining_amount}})"><i class="fa fa-rupee fa-2x"></i></a>
													@endif
													--}}

													@if(!is_null($loading_slip_invoice->retailer_invoice_payments))
													<a href="javascript:;" onclick="viewPayments({{$loading_slip_invoice->id}})">
														<i class="fa fa-money fa-2x"></i>
													</a>
													@endif

													<a href="/user/loading-slip-invoices-details/{{$loading_slip_invoice->id}}" >
														<i class="fa fa-print fa-2x"></i>
													</a>

													<a href="/user/expot-as-xml/{{$loading_slip_invoice->id}}" >
														<i class="fa fa-download fa-2x"></i>
													</a>

												</td>

											</tr>
											@endforeach
										</tbody>
									</table>
									{{$loading_slip_invoices->links()}}
								</div>
							</div>




						</div>
					</div>




				</div><!-- /.page-content -->
			</div>

			<div class="modal fade" id="paymentModal">
				<form action="{{URL('/user/save-loading-invoice-payment')}}" method="post" onsubmit="return validateForm()">
					{{csrf_field()}}
					<input type="hidden" name="invoice_id" id="invoice_id">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title">Payment Details</h4>
							</div>
							<div class="modal-body" id="paymentModalBody">
								<div class="form-group">
									<label for="">Paid Amount</label>
									<input type="text" class="form-control checkIfValid" name="paid_amount" id="paid_amount" placeholder="Paid Amount">
									<span class="label label-danger"  style="display: none;"></span>
								</div>

								<div class="form-group">
									<label for="">Payment Date</label>
									<input type="text" class="form-control checkIfValid date-picker" name="payment_date" id="payment_date" placeholder="Payment Date">
									<span class="label label-danger"  style="display: none;"></span>
								</div>
								<div class="form-group">
									<label for="">Bank</label>
									<input type="text" class="form-control checkIfValid" name="bank" id="bank" placeholder="Bank">
								</div>
								<div class="form-group">
									<span class="label label-danger"  style="display: none;"></span>
									<label for="">Bank Account Number</label>
									<input type="text" class="form-control checkIfValid" name="bank_account_number" id="bank_account_number" placeholder="Bank Account Number">
									<span class="label label-danger"  style="display: none;"></span>
								</div>
								<div class="form-group">
									<label for="">Payment Reference Number</label>
									<input type="text" class="form-control checkIfValid" name="bank_reference_number" id="bank_reference_number" placeholder="Payment Reference Number">
									<span class="label label-danger"  style="display: none;"></span>
								</div>

								<div class="form-group">
									<label for="">Payment Mode</label>
									<select class="form-control checkIfValid">
										<option value="Cash">Cash</option>
										<option value="Cheque">Cheque</option>
										<option value="DD">DD</option>
									</select>
									<span class="label label-danger"  style="display: none;"></span>
								</div>


							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary">Save changes</button>
							</div>
						</div>
					</form>
				</div>


			</div><!-- /.main-content -->



			<div class="modal fade" id="PaymentHistoryModal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Payment History</h4>
						</div>
						<div class="modal-body" id="PaymentHistoryBody">

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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


				jQuery(function($) {


					$('.date-picker').datepicker({
						autoclose: true,
						todayHighlight: true
					})
					.next().on(ace.click_event, function(){
						$(this).prev().focus();
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

				function payInvoice(invoice_id,remaining_balance){
					$('.loading-bg').show();
					$('#invoice_id').val(invoice_id);
					$('#paid_amount').val(remaining_balance);
					$('#paymentModal').modal('toggle');
					$('.loading-bg').hide();
				}

				function viewPayments(id){
					$('.loading-bg').show();
					$.ajax({
						url: "{{url('/user/loading-slip-invioce-payment-details/')}}"+'/'+id,
						type: 'GET',
						success:function(response){
							$('.loading-bg').hide();
							$('#PaymentHistoryBody').html(response);
							$('#PaymentHistoryModal').modal('toggle');
						}
					});
				}
			</script>

			@endsection
			@endsection
