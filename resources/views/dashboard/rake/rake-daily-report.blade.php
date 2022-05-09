@extends('dashboard.layouts.app')
@section('title','Rake Daily Report')
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
					<a href="#">Home</a>
				</li>
				<li class="active">Rake Daily Report

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

				</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Rake Daily Report</h3>

					<form action="" method="POST" role="form">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="master_rake_id">Master Rake</label>
									<select class="form-control select2" name="master_rake_id" id="master_rake_id">
										<option value="">Select Master Rake</option>
										@foreach($master_rakes as $master_rake)
										<option value="{{$master_rake->id}}" {{isset($master_rake_id) && $master_rake_id==$master_rake->id ? "selected":""}}>{{$master_rake->name}}</option>
										@endforeach()
									</select>
									@if ($errors->has('master_rake_id'))
									<span class="label label-danger">{{ $errors->first('master_rake_id') }}</span>
									@endif
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="from_date">From Date</label>
									<input type="text" class="form-control date-picker" name="from_date" id="" placeholder="From Date" readonly="readonly" value="{{$from_date}}">
									@if ($errors->has('from_date'))
									<span class="label label-danger">{{ $errors->first('from_date') }}</span>
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
					Rake Daily Report
					<div class="widget-toolbar no-border">

						@if(isset($master_rake_id))
						<a class="btn btn-xs bigger btn-danger" href="{{URL('/user/export-rake-daily-report/'.$master_rake_id.'/'.date('Y-m-d',strtotime($from_date)))}}">
							Export 
							<i class="fa fa-file-excel-o"></i>
						</a>
						@endif
					</div>
				</div>

				<div class="table-responsive">
					<div class="dataTables_borderWrap">
						<div class="table-responsive">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>

										<th>Loading <br/>Slip #</th>
										<th>Token #</th>
										<th>Date </th>
										<th>Party <br/>Name </th>
										<th>Party <br/>Address </th>
										<th>Product <br/>Name </th>
										<th>Truck No. </th>
										<th>Transporter <br/>Name</th>
										<th>Freight <br/>Payment <br/>Mode</th>
										<th>Quantity</th>
										<th>Rate</th>
										<th>Bill(Invoice) #</th>
									</tr>
								</thead>

								<tbody>
									@if(isset($product_loadings))
									@foreach($product_loadings as $product_loading)
									<tr id="tr_{{$product_loading->id}}">
										<td>{{$product_loading->id}}
										</td>
										<td>{{$product_loading->token_id}}
										</td>

										<td>{{date('d-m-Y',strtotime($product_loading->created_at))}}
										</td>

										<td>

											@if(!is_null($product_loading->retailer_id))
											<b>
												Retailer
											</b>	
											<br>
											{{$product_loading->retailer_name}}
											<br>
											({{getModelById('Dealer',$product_loading->dealer_id)->name}})			
											@elseif($product_loading->loading_slip_type ==1 && is_null($product_loading->retailer_id))
											<b>
												Dealer
											</b>
											<br>
											{{getModelById('Dealer',$product_loading->dealer_id)->name}}										
											@else
											<b>
												Warehouse
											</b>
											<br>
											{{getModelById('Warehouse',$product_loading->warehouse_id)->name}}				
											@endif	

										</td>

										<td>
											<b>
												@if($product_loading->loading_slip_type ==1)
												@if(!is_null($product_loading->retailer_id))
												{{getModelById('Retailer',$product_loading->retailer_id)->address}}
												@else
												{{getModelById('Dealer',$product_loading->dealer_id)->address1}}
												@endif

												@else
												{{getModelById('Warehouse',$product_loading->warehouse_id)->location}}				
												@endif
											</b>
										</td>

										<td>{{$product_loading->product_name}}				
										</td>

										<td>{{$product_loading->truck_number}}				
										</td>

										<td>{{$product_loading->transporter_name}}	
										</td>

										<td>{{is_null(getModelById('Token', $product_loading->token_id))?"":getModelById('Token', $product_loading->token_id)->delivery_payment_mode}}	
										</td>
										<td>{{$product_loading->quantity}}</td>
										<td>{{is_null(getModelById('Token', $product_loading->token_id))?"":getModelById('Token', $product_loading->token_id)->rate}} </td>
										<td>
											@if(!is_null($product_loading->loading_slip_invoice))
											<a href="{{URL('user/loading-slip-invoices-details/'.$product_loading->loading_slip_invoice->id)}}" target="_blank">{{$product_loading->loading_slip_invoice->invoice_number}}
											</a>
											@endif
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
			swal('Error','product_loading Is Missing');
		}else{
			$('.loading-bg').show();
			$.ajax({
				url: "{{url('/user/product-loading-details/')}}"+"/"+id,
				type: 'GET',
				success:function(data){
					$('.loading-bg').hide();
					$('#details').html(data);
					$('#productDetailsModal').modal('toggle');
				}
			});
		}
	}
</script>
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
@endsection
@endsection
