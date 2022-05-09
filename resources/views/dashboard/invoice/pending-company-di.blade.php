@extends('dashboard.layouts.app')
@section('title','Pending Company DI')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">Pending Company DIs </li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Pending Company DI</h3>

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


					<div class="row">
						<div class="col-xs-12">

							<form action="" method="POST" role="form" id="FilterForm">
								<div class="row">
									{{csrf_field()}}
									<div class="col-md-3">
										<div class="form-group">
											<label for="product_company_id"> {{__('messages.ProductCompany')}}</label>
											<select class="form-control select2" name="product_company_id" id="product_company_id" onchange="filter()">
												<option value=""> Select Product Company</option>
												@foreach($product_companies as $product_company)
												<option value="{{$product_company->id}}" {{isset($product_company_id) && $product_company_id==$product_company->id ? "selected":""}}>{{$product_company->name}}</option>
												@endforeach()
											</select>
											@if ($errors->has('product_company_id'))
											<span class="label label-danger">{{ $errors->first('product_company_id') }}</span>
											@endif
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="product_id"> {{__('messages.Product')}}</label>
											<select class="form-control select2" name="product_id" id="product_id" onchange="filter()">
												<option value=""> Select Product </option>
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
							Results for "Pending Company DI"


						</div>

						<div class="table-responsive">

							<div class="dataTables_borderWrap">
								<table id="dynamic-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>

											<th>Rake</th>
											<th>Total RR</th>
											<th>Total Company DI</th>
											<th>Pending Company DI</th>
										</tr>
									</thead>

									<tbody>
										@foreach($invoices as $invoice)
										<tr>
											
											<td>{{$invoice['rake']}}</td>
											<td>{{$invoice['total_rr']}}</td>
											<td>{{$invoice['total_di']}}</td>
											<td>{{$invoice['total_rr'] - $invoice['total_di']}}</td>
										</tr>
										@endforeach

									</tr>
								</tbody>
							</table>
						</div>
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


<script type="text/javascript">


	jQuery(function($) {

		
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
