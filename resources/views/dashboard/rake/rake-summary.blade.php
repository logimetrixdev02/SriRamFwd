@extends('dashboard.layouts.app')
@section('title','Rake Summary')
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
				<li class="active"> {{__('messages.RakeSummary')}}</li>
				<div id="google_translate_element"></div>

				<script type="text/javascript">
					function googleTranslateElementInit() {
						new google.translate.TranslateElement({
							pageLanguage: 'en',
							includedLanguages: 'hi',

							layout: google.translate.TranslateElement.InlineLayout.SIMPLE
						}, 'google_translate_element');
					}
				</script>

				<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
				<button id="btnExport"   onclick="fnExcelReport();"style="float: right" id="xls_download" title="Export"><i class="fa fa-2x fa-file-excel-o" aria-hidden="true"></i> </button>
				<script>
					response.setHeader("Set-Cookie", "HttpOnly;Secure;SameSite=Strict");
					function fnExcelReport()
					{
						var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
						var textRange; var j=0;
    tab = document.getElementById('dynamic-table'); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
    	tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
    	txtArea1.document.open("txt/html","replace");
    	txtArea1.document.write(tab_text);
    	txtArea1.document.close();
    	txtArea1.focus(); 
    	sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
    }  
    else                 //other browser not tested on IE 11
    	sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

    return (sa);
}
</script>
</ul>
</div>

<div class="page-content">

	<div class="row">
		<div class="col-xs-12">
			<h3 class="header smaller lighter blue"> {{__('messages.RakeSummary')}}</h3>

			<form action="" method="POST" role="form" id="FilterForm">
				<div class="row">
					{{csrf_field()}}
					<div class="col-md-3">
						<div class="form-group">
							<label for="master_rake_id"> {{__('messages.masterrake')}}</label>
							<select class="form-control select2" name="master_rake_id" id="master_rake_id" onchange="filter()">
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
					@if(isset($master_rake_products))
					
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
					@endif
					

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
			{{__('messages.RakeSummary')}}
			@if(isset($master_rake_id))
			<div class="widget-toolbar no-border">
				<a href="{{URL('user/export-rake-summary/'.$master_rake_id)}}" class="btn btn-xs bigger btn-danger">
					{{__('messages.Export')}}
				</a>
			</div>
			@endif
		</div>

		<div class="table-responsive">
			<div class="dataTables_borderWrap">
				<div class="table-responsive">
					<table id="" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>

								<th>  {{__('messages.Party')}} {{__('messages.Name')}}</th>
								<th> {{__('messages.Product')}}</th>
								<th>  {{__('messages.Allotment')}} {{__('messages.From')}}<br/> {{__('messages.ProductCompany')}}</th>
								@if(isset($allotments))
								@foreach($date_range as $key=>$date)
								@php
								$total_datewise_quantity[$date] = array();
								@endphp
								<th>{{date('d-m-y',strtotime($date))}}</th>
								@endforeach
								@endif
								<th> {{__('messages.Total')}}</th>
								<th>  {{__('messages.Pending')}} {{__('messages.Quantity')}}</th>
							</tr>
						</thead>

						<tbody>

							@if(isset($allotments))
							@php
							$total_company_allotment = array();
							$total_loading_allotment = array();
							$pending_allotment 		 = array();
							@endphp
							@foreach($allotments as $allotment)
							
							<tr id="tr_{{$allotment->id}}">

								<td style="word-wrap: break-word;  width:350px;
								padding: 10px;">{{getModelById('Dealer',$allotment->dealer_id)->name}}({{getModelById('Dealer',$allotment->dealer_id)->address1}})</td>

								<td>
									{{getModelById('Product',$allotment->product_id)->name}}
								</td>
								<td>{{$allotment->alloted_quantity}}</td>

								@if(isset($allotments))
								@php
								@array_push($total_company_allotment, $allotment->alloted_quantity);
								$total = 0;
								@endphp
								@foreach($date_range as $key=>$date)
								<td>
									@php 
									$dates = $date;
									$date = date('Y-m-d',strtotime($date));
									$product_loadings = \App\ProductLoading::where('dealer_id',$allotment->dealer_id)
									->where('product_id',$allotment->product_id)
									->where('master_rake_id',$allotment->master_rake_id)
									->where('from_warehouse_id',null)
									->whereRaw('DATE(created_at) = ?', [$date])
									->sum('quantity');
									$total_amt = $product_loadings;
									$total = $total + $product_loadings;

									array_push($total_datewise_quantity[$dates], $total_amt);
									@endphp
									{{$product_loadings}}
								</td>
								@endforeach

								@endif
								<td>{{$total}}</td>
								<td>{{$allotment->alloted_quantity-$total}}</td>
							</tr>
							@php
							@array_push($total_loading_allotment, $total);
							@array_push($pending_allotment, $allotment->alloted_quantity-$total);
							@endphp
							@endforeach
							<tr>
								<th> {{__('messages.Total')}} ({{__('messages.with')}} {{__('messages.Allotment')}})</th>
								<th></th>
								<th>{{array_sum($total_company_allotment)}}</th>
								@if(isset($allotments))
								@foreach($date_range as $key=>$date)
								<th>{{array_sum($total_datewise_quantity[$date])}}</th>
								@endforeach
								@endif
								<th>{{array_sum($total_loading_allotment)}}</th>
								<th>{{array_sum($pending_allotment)}}</th>
							</tr>
							@foreach($warehouse_allotments as $warehouse_allotment)

							<tr>

								<td style="word-wrap: break-word;  width:350px;
								padding: 10px;">{{getModelById('Warehouse',$warehouse_allotment->warehouse_id)->name}}({{getModelById('Warehouse',$warehouse_allotment->warehouse_id)->location}})</td>

								<td></td>
								<td></td>
								@php
								$warehouse_total = 0;
								@endphp
								@foreach($date_range as $key=>$date)
								<td>
									@php 
									$dates = $date;
									$date = date('Y-m-d',strtotime($date));
									if(isset($product_id) && $product_id != ""){
									$product_loadings = \App\ProductLoading::where('warehouse_id',$warehouse_allotment->warehouse_id)
									->where('product_id',$product_id)
									->where('master_rake_id',$master_rake_id)
									->whereRaw('DATE(created_at) = ?', [$date])
									->where('loading_slip_type',2)
									->sum('quantity');
								}else{
								$product_loadings = \App\ProductLoading::where('warehouse_id',$warehouse_allotment->warehouse_id)
								->whereRaw('DATE(created_at) = ?', [$date])
								->where('master_rake_id',$master_rake_id)
								->where('loading_slip_type',2)
								->sum('quantity');
							}


							$total_amt = $product_loadings;
							$warehouse_total = $warehouse_total + $product_loadings;
							array_push($total_datewise_quantity[$dates], $total_amt);
							@endphp
							{{$product_loadings}}
						</td>
						@endforeach


						<td>{{$warehouse_total}}</td>
						<td></td>
					</tr>
					@php
					@array_push($total_loading_allotment, $warehouse_total);
					@endphp
					@endforeach
					<tr>

						<th>{{__('messages.Total')}} ({{__('messages.Allotment')}} {{__('messages.with')}}/{{__('messages.without')}})</th>
						<th></th>
						<th>{{array_sum($total_company_allotment)}}</th>
						@if(isset($allotments))
						@foreach($date_range as $key=>$date)
						<th>{{array_sum($total_datewise_quantity[$date])}}</th>
						@endforeach
						@endif
						<th>{{array_sum($total_loading_allotment)}}</th>
						<th>{{array_sum($pending_allotment)}}</th>
					</tr>
					<tr>
						<td>RR {{__('messages.Quantity')}}</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>
							@if(isset($product_id) && $product_id != "")
							@php
							$master_rake_product = \App\MasterRakeProduct::where('master_rake_id',$master_rake_id)->where('product_id',$product_id)->first();
							echo $master_rake_product->quantity;
							@endphp
							@else
							@php
							$total_rr = \App\MasterRakeProduct::where('master_rake_id',$master_rake_id)->sum('quantity');
							echo $total_rr;
							@endphp
							@endif
						</td>
					</tr>

					<tr>
						<td>Rake Shortage</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>
							@if(isset($product_id) && $product_id != "")
							@php
							$master_rake_product = \App\MasterRakeProduct::where('master_rake_id',$master_rake_id)->where('product_id',$product_id)->first();
							echo $master_rake_product->shortage_from_company;
							@endphp
							
							@endif
						</td>
					</tr>

					<tr>
						<td>Net Received RR</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>
							@if(isset($product_id) && $product_id != "")
							@php
							$master_rake_product = \App\MasterRakeProduct::where('master_rake_id',$master_rake_id)->where('product_id',$product_id)->first();
							echo ($master_rake_product->quantity - $master_rake_product->shortage_from_company);
							@endphp
							@else
							@php
							$total_rr = \App\MasterRakeProduct::where('master_rake_id',$master_rake_id)->sum('quantity');
							echo $total_rr;
							@endphp
							@endif
						</td>
					</tr>
					@php
					$returned = 0;
					@endphp
					<tr>
						<td>Returned</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>
							@if(isset($product_id) && $product_id != "")
							@php
							$returned = \App\ProductLoading::where('master_rake_id',$master_rake_id)->where('product_id',$product_id)->where('loading_slip_type',1)->where('recieved_quantity','>',0)->sum('recieved_quantity');
							echo $returned;
							@endphp
							@else
							@php
							$returned = \App\ProductLoading::where('master_rake_id',$master_rake_id)->where('loading_slip_type',1)->where('recieved_quantity','>',0)->sum('recieved_quantity');
							echo $returned;
							@endphp
							@endif
						</td>
					</tr>

					<tr>
						@if(isset($product_id) && $product_id != "")
						@php
						$master_rake_product = \App\MasterRakeProduct::where('master_rake_id',$master_rake_id)->where('product_id',$product_id)->first();
						$excess_shortage =  array_sum($total_loading_allotment) - ($master_rake_product->quantity - $master_rake_product->shortage_from_company);
						@endphp
						@else
						@php
						$total_rr = \App\MasterRakeProduct::where('master_rake_id',$master_rake_id)->sum('quantity');
						$excess_shortage =  array_sum($total_loading_allotment) - $total_rr 
						@endphp
						@endif

						<td>
							@if($excess_shortage > 0)
							{{__('messages.Excess')}}
							@else
							Shortage
							@endif
						</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>

						<td>
							{{$excess_shortage}}
							
							
						</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>



<!-- Product Details Modal -->
<div class="modal fade" id="productDetailsModal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Alloted Products</h4>
			</div>
			<div class="modal-body" id="details">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- Product Details Modal -->


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
//initiate dataTables plugin
//
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
	function showProducts(id){
		if(id == ""){
			swal('Error','Allotment Is Missing');
		}else{
			$('.loading-bg').show();
			$.ajax({
				url: "{{url('/user/allotment-details/')}}"+"/"+id,
				type: 'GET',
				success:function(data){
					$('.loading-bg').hide();
					$('#details').html(data);
					$('#productDetailsModal').modal('toggle');
				}
			});
		}
	}
	function filter(){
		$('#product_id').val('');
		$('#FilterForm').submit();
	}
</script>
@endsection
@endsection
