@extends('dashboard.layouts.app')
@section('title','Opening Stock Report')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">  Opening  {{__('messages.Stock')}} {{__('messages.Report')}}</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">  Opening  {{__('messages.Stock')}} {{__('messages.Report')}}</h3>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">

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

					@foreach($product_categories  as $product_cat)
					<div class="table-header">
						{{$product_cat->category}}
					</div>

					<div class="table-responsive">

						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th> {{ __('messages.Product')}}</th>
										@foreach($self_parties as $self_party)
										<th>{{$self_party['party_name']}} <br>({{$self_party['address']}})</th>
										@endforeach
										<th>Other Parties</th>
										@foreach($product_companies as $product_company)
										<th>{{$product_company['party_name']}} <br>({{$product_company['address']}})</th>
										@endforeach	
										<th> {{__('messages.Total')}}</th>
										@foreach($warehouses as $warehouse)
										<th>{{$warehouse->name}}</th>
										@endforeach	
										<th> {{__('messages.Total')}}</th>
									</tr>
								</thead>

								<tbody>
									@if(count($products) > 0)
									@foreach($products as $product)
									@if($product['product_category_id'] == $product_cat->id)
									<tr>
										<td>{{$product['name']}}/{{$product['product_company']}}</td>
										@php
										$party_total = 0;
										@endphp

										@foreach($self_parties as $self_party)
										<td>
											@if(is_null(getPartyOpeningInventoryByProductAndBrand($self_party['id'],$product['id'],$product['product_brand_id'],$self_party['type'])))
											0
											@else 
											{{getPartyOpeningInventoryByProductAndBrand($self_party['id'],$product['id'],$product['product_brand_id'],$self_party['type'])}}

											@php
											$party_total = $party_total + getPartyOpeningInventoryByProductAndBrand($self_party['id'],$product['id'],$product['product_brand_id'],$self_party['type']);
											@endphp

											@endif
										</td>
										@endforeach	

										<td>
											{{getOtherPartyOpeningInventoryByProductAndBrand($product['id'],$product['product_brand_id'])}}
											@php
											$party_total = $party_total + getOtherPartyOpeningInventoryByProductAndBrand($product['id'],$product['product_brand_id']);
											@endphp



										</td>

										@foreach($product_companies as $product_company)
										<td>
											@if(is_null(getPartyOpeningInventoryByProductAndBrand($product_company['id'],$product['id'],$product['product_brand_id'],$product_company['type'])))
											0
											@else 
											{{getPartyOpeningInventoryByProductAndBrand($product_company['id'],$product['id'],$product['product_brand_id'],$product_company['type'])}}

											@php
											$party_total = $party_total + getPartyOpeningInventoryByProductAndBrand($product_company['id'],$product['id'],$product['product_brand_id'],$product_company['type']);
											@endphp

											@endif
										</td>
										@endforeach	

										<td>{{$party_total}}</td>
										@php
										$warehouse_total = 0;
										@endphp

										@foreach($warehouses as $warehouse)
										<td> 
											@if(is_null(getOpeningInventory($product['product_brand_id'],$warehouse['id'],$product['id'])))
											0
											@else 
											{{getOpeningInventory($product['product_brand_id'],$warehouse['id'],$product['id'])}}

											@php
											$warehouse_total = $warehouse_total + getOpeningInventory($product['product_brand_id'],$warehouse['id'],$product['id']);
											@endphp

											@endif
										</td>
										@endforeach	
										<td>{{$warehouse_total}}</td>
									</tr>
									@endif
									@endforeach	
									@endif
								</tbody>
							</table>
						</div>
					</div></br>
					@endforeach
				</div>
			</div>




		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->



<!-- Edit Dealer Modal -->
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
