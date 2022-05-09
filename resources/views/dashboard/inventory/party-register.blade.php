@extends('dashboard.layouts.app')
@section('title','Party Regsiter')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#"> {{__('messages.Home')}}</a>
				</li>
				<li class="#">Stock</li>
				<li class="active">Party Register</li>
			</ul>
		</div>

		<div class="page-content">
			<h3 class="header smaller lighter blue"> Party Register</h3>
			<div class="row">
				<div class="col-xs-12">
					<form action="" method="POST" role="form">
						<div class="row">
							{{csrf_field()}}


							<div class="col-md-3">
								<div class="form-group">
									<label for="dealer_id"> Dealer</label>
									<select class="form-control select2" name="dealer_id" id="dealer_id">
										<option value=""> Select Dealer</option>
										@foreach($dealers as $dealer)
										<option value="{{$dealer->id}}" {{isset($dealer_id) && $dealer_id==$dealer->id ? "selected":""}}>{{$dealer->name}}({{$dealer->address1}})</option>
										@endforeach()
									</select>
									@if ($errors->has('dealer_id'))
									<span class="label label-danger">{{ $errors->first('dealer_id') }}</span>
									@endif
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="product_brand_id"> Product Brand</label>
									<select class="form-control select2" name="product_brand_id" id="product_brand_id">
										<option value=""> Select Product Brand</option>
										@foreach($product_companies as $product_company)
										<option value="{{$product_company->id}}" {{isset($product_brand_id) && $product_brand_id==$product_company->id ? "selected":""}}>{{$product_company->name}}</option>
										@endforeach()
									</select>
									@if ($errors->has('product_brand_id'))
									<span class="label label-danger">{{ $errors->first('product_brand_id') }}</span>
									@endif
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
						Results for "Generated Party Register"
						<div class="widget-toolbar no-border">
							

						</div>

					</div>

					<div class="table-responsive">
						<div class="dataTables_borderWrap">
							<table  class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Product Loading</th>
										<th>Standard(Open)</th>
										<th>Company DI</th>
										<th>Warehouse DI(In)</th>
										<th>Warehouse DI(Out)</th>
										<th>Dealer Returned</th>
										<th>Retailer Returned</th>
										<th>Standard(Closed)</th>
									</tr>
								</thead>

								<tbody>
									<td>{{$total_loading}}</td>
									<td>{{$standard_open}}</td>
									<td>{{$total_company_di}}</td>
									<td>{{$total_warehouse_di}}</td>
									<td>{{$total_out_warehouse_di}}</td>
									<td>{{$total_dealer_returned}}</td>
									<td>{{$total_retailer_returned}}</td>
									<td>{{$standard_closed}}</td>
								</tbody>
							</table>
						</div>
					</div>


					<div class="table-responsive">
						<div class="dataTables_borderWrap">
							<table  id="dynamic-table"  class="table table-striped table-bordered table-hover">
								<thead>
									<tr>


										<th> Date</th>
										<th> Party <br>Name</th>
										<th> Type</th>
										<th> Godown</th>
										<th> {{__('messages.Product')}} </th>
										<th> {{__('messages.Truck')}}#</th>
										<th>IN</th>
										<th>OUT</th>
										<th>Balance</th>

									</tr>
								</thead>

								<tbody>
									@if(isset($registers))

									<tr>
										<td></td>
										<td></td>
										<td><b>Opening Stock</b></td>
										<td></td>
										<td></td>
										<td></td>
										<td>-</td>
										<td>-</td>
										<td>{{$opening_stock}}</td>
									</tr>

									@foreach($registers as $register)
									<tr>
										<td>{{date('d/m/Y',strtotime($register['date']))}}</td>
										<td>{{$register['party']}}</td>
										<td>{!! $register['source'] !!}</td>
										<td>{{$register['godown']}}</td>
										<td>{{$register['product']}}</td>
										<td>{{$register['truck_number']}}</td>
										<td>{{$register['in']}}</td>
										<td>{{$register['out']}}</td>
										<td>{{$register['balance']}}</td>
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
				autoPrint: true,
				message: 'IManager',
				exportOptions: {
					columns: ':visible'
				}
			}		  
			]
		} );
		myTable.buttons().container().appendTo( $('.tableTools-container') );

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
