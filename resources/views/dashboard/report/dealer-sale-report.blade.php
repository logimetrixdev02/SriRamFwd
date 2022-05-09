@extends('dashboard.layouts.app')
@section('title','Stock Report')
@section('content')
<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">Dealer Sale Report</li>
			</ul>
		</div>
		<div class="page-content">
			<h3 class="header smaller lighter blue">Dealer Sale Report</h3>


			<div class="row">
				<div class="col-xs-12">

					<!-- <form action="" method="POST" role="form"> -->
						<div class="row">
							{{--{{csrf_field()}}--}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="master_rake_id">Dealers</label>
									<select class="form-control select2" name="dealer_id" id="dealer_id">
										<option value="">Select Dealers</option>
										@foreach($dealers as $dealer)
										<option value="{{$dealer->unique_id}}" {{isset($dealer_id) && $dealer_id==$dealer->id ? "selected":""}}>{{$dealer->name}} [{{$dealer->unique_id}}]</option>
										@endforeach()
										
									</select>
								
									<span class="label label-danger" style="display: none;"></span>
									
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group input-daterange">
									<label for="master_rake_id">From Date</label>
									 <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date"  readonly />
								
									<span class="label label-danger" style="display: none;"></span>
									
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group input-daterange">
									<label for="master_rake_id">To Date</label>
									 <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
								
									<span class="label label-danger" style="display: none;"></span>
									
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									 <input type="button" name="filter" id="filter"  class="btn btn-sm btn-primary" value="Filter">
										<input type="button" name="refresh" id="refresh" class="btn btn-sm btn-warning" value="Clear">
								</div>
							</div>
						</div>
					<!-- </form> -->

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
						Dearler Wise Sale Total (<b><span style="color: orange;" id="grand_total"></span></b>)
					

					</div>

					<div class="table-responsive">

						<div class="dataTables_borderWrap">
						<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>

										<th>#</th>
										<th>Retailer</th>
										
										<th>{{__('messages.Product')}}</th>
										<th>Qty</th>
										<th>Truck No</th>
										<th>Date</th>
										
									</tr>
								</thead>
							
					</table>
					
					
				</div>
			</div>

</div>
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
	$(document).ready(function(){
		display();


		$('#filter').click(function(){
				var from_date = $('#from_date').val();
				var to_date = $('#to_date').val();
				var dealer_id = $('#dealer_id').val();


				if(dealer_id !='' && from_date != '' &&  to_date != '')
				{
					
				$('#dynamic-table').DataTable().destroy();
				display(dealer_id,from_date, to_date);
				}else if(dealer_id!=''){
						$('#dynamic-table').DataTable().destroy();
					display(dealer_id,from_date="", to_date="");
				}else{
					alert('Dealer is required');
				}

			
	 
		});

		$('#refresh').click(function(){
				$('#from_date').val('');
				$('#to_date').val('');
				$('#dynamic-table').DataTable().destroy();
				display();;
			});

			$('.input-daterange').datepicker({
		
						format:'yyyy-mm-dd',
						autoclose:true
				});
	})
	function display(dealer_id='',from_date = '', to_date = ''){
				 let total_bag =0;
					var myTable=$('#dynamic-table').DataTable({
							"processing": true,
							"serverSide": true,
							stateSave: true,
							 colReorder: true,
							bAutoWidth: false,
							"aaSorting": [],
							"ajax":{
								"url": "{{ route('dealer.sale-reports') }}",
								"data":{dealer_id:dealer_id,from_date:from_date, to_date:to_date},
							},
							
							 "footerCallback": function ( row, data, start, end, display ) {
					            var api = this.api(), data;
					            let total_bag=0;
					              $('#grand_total').html('');
					            var json = api.ajax.json();
					             $.each(json.data, function(key,val) {
                       console.log(key+val.qty);
                       total_bag=total_bag+parseFloat(val.qty);
                      });
					             $('#grand_total').html(total_bag);
					            
       						 },

							"columns":[
								{ data:"DT_RowIndex" },
								
								{ "data": "retailer_name" },
								{ "data": "product_name" },
								{ "data": "qty" },
								{"data": "truck_no"},
								{"data": "date"},
								
								
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
</script>

@endsection
@endsection