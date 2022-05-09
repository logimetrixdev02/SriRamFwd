@extends('dashboard.layouts.app')
@section('title','Company DI')
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
				<li class="active">Company DIs </li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Company DIs</h3>


					<div class="row">
						<div class="col-xs-12">
							<form action="" method="POST" role="form">
								<div class="row">
									{{csrf_field()}}


									<div class="col-md-3">
										<div class="form-group">
											<label for="product_company_id"> Product Company</label>
											<select class="form-control select2" name="product_company_id" id="product_company_id">
												<option value=""> Select Product Company</option>
												@foreach($product_companies as $product_company)
												<option value="{{$product_company->id}}" {{isset($product_company_id) && $product_company_id==$product_company->id ? "selected":""}}>{{$product_company->name}}</option>
												@endforeach()
											</select>

										</div>
									</div>



									<div class="col-md-3">
										<div class="form-group">
											<label for="product_id"> Product</label>
											<select class="form-control select2" name="product_id" id="product_id">
												<option value="">  {{__('messages.Select')}} Product </option>
												@foreach($products as $product)
												<option value="{{$product->id}}" {{isset($product_id) && $product_id==$product->id ? "selected":""}}>{{$product->name}}</option>
												@endforeach()
											</select>
											@if ($errors->has('product_id'))
											<span class="label label-danger">{{ $errors->first('product_id') }}</span>
											@endif
										</div>
									</div>

									



									<div class="col-md-3">
										<div class="form-group">
											<label for="dealer_id"> Dealer</label>
											<select class="form-control select2" name="dealer_id" id="dealer_id">
												<option value=""> Select Dealer</option>
												@foreach($dealers as $dealer)
												<option value="{{$dealer->id}}" {{isset($dealer_id) && $dealer_id==$dealer->id ? "selected":""}}>{{$dealer->name}}({{$dealer->address1}})</option>
												@endforeach()
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

					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Results for "Company DI" <span class="badge badge-success">{{$total}}</span>
						<div class="widget-toolbar no-border">

							<a class="btn btn-xs bigger btn-warning" href="{{URL('/user/pending-company-di')}}">
								Pending Company DIs
								<i class="ace-icon fa fa-eye icon-on-right"></i>
							</a>


							<a class="btn btn-xs bigger btn-danger" href="{{URL('/user/generate-company-di')}}">
								Generate Company DI
								<i class="ace-icon fa fa-plus icon-on-right"></i>
							</a>

							
						</div>

					</div>

					<div class="table-responsive">

						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>

										<th>Company</th>
										<th>Party</th>
										<th>Invoice Number</th>
										<th>Invoice Date</th>
										<th>Product</th>
										<th>Quantity</th>
										<th>Total Payment</th>
										<th>Payment Status</th>
										<th>Paid Amount</th>
										<th></th>
									</tr>
								</thead>

								<tbody>
									@foreach($invoices as $invoice)
									<tr>
										<td>{{getModelById('ProductCompany',$invoice->product_company_id)->name}}</td>
										<td>{{getModelById('Dealer',$invoice->dealer_id)->name}}
											<br>
											({{getModelById('Dealer',$invoice->dealer_id)->address1}})
										</td>
										<td>{{$invoice->invoice_number}}</td>
										<td>{{$invoice->invoice_date}}</td>
										<td>{{$invoice->product}}</td>
										<td>{{$invoice->quantity}}</td>
										<td>{{$invoice->total}}</td>
										<td>
											@if($invoice->is_paid)
											<span class="label label-success">Paid</span>
											@else
											<span class="label label-danger">Not Paid</span>
											@endif
										</td>
										<td>
											@if($invoice->is_paid)

											<a href="javascript:;" onclick="showPaymentDetails('{{$invoice->payment_amount}}','{{$invoice->payment_date}}','{{$invoice->bank}}','{{$invoice->bank_account_number}}','{{$invoice->bank_reference_number}}','{{$invoice->payment_mode}}')">{{$invoice->payment_amount}}</a>
											@else
											--
											@endif
										</td>
										<td>
											@if(!$invoice->is_paid)
											<a href="javascript:;" onclick="payInvoice({{$invoice->id}},'{{$invoice->total}}')"><i class="fa fa-inr fa-2x"></i></a>
											@endif
										</td>
									</tr>
									@endforeach

								</tr>
							</tbody>
						</table>
						
					</div>
				</div>
			</div>
		</div>


		<div class="modal fade" id="paymentDetailsModal">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Payment Details</h4>
					</div>
					<div class="modal-body">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>Payment Amount</th>
										<td id="payment_amount"></td>
									</tr>
									<tr>
										<th>Payment Date</th>
										<td id="payment_date"></td>
									</tr>
									<tr>
										<th>Bank</th>
										<td id="bank"></td>
									</tr>
									<tr>
										<th>Bank Account Number</th>
										<td id="bank_account_number"></td>
									</tr>
									<tr>
										<th>Bank Reference Number</th>
										<td id="bank_reference_number"></td>
									</tr>
									<tr>
										<th>Payment Mode</th>
										<td id="payment_mode"></td>
									</tr>
								</thead>
							</table>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="paymentModal">
			<form action="{{URL('/user/save-company-di-payment')}}" method="post" onsubmit="return validateForm()">
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
							<!--div class="form-group">
								<label for="">Bank</label>
								<input type="text" class="form-control checkIfValid" name="bank" id="bank" placeholder="Bank">
							</div -->
							
							<div class="form-group">
								<span class="label label-danger"  style="display: none;"></span>
								<label for="">Bank Account Number</label>
								<select class="form-control checkIfValid select2" name="bank_account_id" id="bank_account_id">
									
									@foreach($bank_accounts as $bank_account)
									<option value="{{$bank_account->id}}">{{$bank_account->bank->name}} - {{$bank_account->account_number}}</option>
									@endforeach
								</select>
								<span class="label label-danger"  style="display: none;"></span>
							</div>
								
							<div class="form-group">
								<label for="">Payment Reference Number</label>
								<input type="text" class="form-control checkIfValid" name="bank_reference_number" id="bank_reference_number" placeholder="Payment Reference Number">
								<span class="label label-danger"  style="display: none;"></span>
							</div>

							<div class="form-group">
								<label for="">Payment Mode</label>
								<select class="form-control checkIfValid" name="payment_mode">
									<option value="Cash">Cash</option>
									<option value="Cheque">Cheque</option>
									<option value="DD">DD</option>
									<option value="NEFT">NEFT</option>
									<option value="RTGS">RTGS</option>
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
			function payInvoice(invoice_id,payment){
				$('.loading-bg').show();
				$('#invoice_id').val(invoice_id);
				$('#paid_amount').val(payment);
				$('#paymentModal').modal('toggle');
				$('.loading-bg').hide();
			}
			function showPaymentDetails(payment_amount,payment_date,bank,bank_account_number,bank_reference_number,payment_mode){
				$('.loading-bg').show();
				$('#payment_amount').text(payment_amount);
				$('#payment_date').text(payment_date);
				$('#bank').text(bank);
				$('#bank_reference_number').text(bank_reference_number);
				$('#bank_account_number').text(bank_account_number);
				$('#payment_mode').text(payment_mode);
				$('#paymentDetailsModal').modal('toggle');
				$('.loading-bg').hide();
			}
			function validateForm(){
				$('.loading-bg').show();
				var errorCount = 0;
				$('.checkIfValid').each(function(){
					if($(this).val() == ""){
						errorCount = errorCount + 1;
						$(this).closest('.form-group').find('.label-danger').text('Required');
						$(this).closest('.form-group').find('.label-danger').show();
					}else{
						$(this).closest('.form-group').find('.label-danger').hide();
					}
				});

				$('.loading-bg').hide();
				if(errorCount > 0){
					return false;
				}else{
					return true;
				}
			}
		</script>
		@endsection
		@endsection
