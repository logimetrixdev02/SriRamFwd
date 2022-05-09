@extends('dashboard.layouts.app')
@section('title','Warehouse Token')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">{{__('messages.WarehouseToken')}}</li>
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
	<h3 class="header smaller lighter blue">{{__('messages.WarehouseToken')}}</h3>


	<div class="row">
		<div class="col-xs-12">
			<form action="" method="POST" role="form">
				<div class="row">
					{{csrf_field()}}
					<div class="col-md-3">
						<div class="form-group">
							<label for="warehouse_id"> {{__('messages.Warehouses')}}</label>
							<select class="form-control select2" name="warehouse_id" id="warehouse_id">
								<option value="">  {{__('messages.Warehouses')}} {{__('messages.Select')}}</option>
								@foreach($warehouses as $warehouse)
								<option value="{{$warehouse->id}}" {{isset($from_warehouse_id) && $from_warehouse_id==$warehouse->id ? "selected":""}}>{{$warehouse->name}}</option>
								@endforeach()
							</select>
							@if ($errors->has('warehouse_id'))
							<span class="label label-danger">{{ $errors->first('warehouse_id') }}</span>
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
				{{__('messages.ResultsforLatestGeneratedToken')}}
				<div class="widget-toolbar no-border">
					<a class="btn btn-xs bigger btn-danger" href="{{URL('/user/generate-token')}}">
						{{__('messages.generateNew')}}
						<i class="ace-icon fa fa-plus icon-on-right"></i>
					</a>
				</div>

			</div>

			<div class="table-responsive">

				<div class="dataTables_borderWrap">
					<table id="dynamic-table" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>

								<th>{{__('messages.Date')}}</th>
								<th>{{__('messages.TokenNumber')}}</th>
								<th>{{__('messages.Rake')}} / {{__('messages.Warehouses')}}</th>
								<th>{{__('messages.company')}}</th>
								<th>{{__('messages.ProductCompany')}}</th>
								<th>{{__('messages.To')}}</th>
								<th>{{__('messages.Product')}}</th>
								<th>{{__('messages.Quantity')}}</th>
								<th>Remaining <br>Loading <br>	{{__('messages.Quantity')}}</th>
								<th>{{__('messages.Unit')}}</th>
								<th>{{__('messages.Rate')}}</th>
								<th>Freight <br>Payment <br>Mode</th>
								<th>{{__('messages.Transporter')}}</th>
								<th>{{__('messages.WarehouseKeeper')}}</th>
								<th></th>
							</tr>
						</thead>

						<tbody>
							@foreach($tokens as $token)
							<tr id="tr_{{$token->id}}">

								<td>{{date('d/m/Y',strtotime($token->created_at))}}</td>
								<td>{{$token->unique_id}}</td>
								<td>
									<b>
										@if(is_null($token->master_rake_id))
										{{getModelById('Warehouse',$token->from_warehouse_id)->name}}
										@else 
										{{getModelById('MasterRake',$token->master_rake_id)->name}}
										@endif
									</b>
								</td>
								<td>{{getModelById('Company',$token->company_id)->name}}</td>
								<td>{{getModelById('ProductCompany',$token->product_company_id)->name}}</td>
								<td>
									@php
									if($token->to_type == 1){
									echo "<b>Warehouse </b><br>".getModelById('Warehouse',$token->warehouse_id)->name."(".getModelById('Warehouse',$token->warehouse_id)->location.")";
								}else if($token->to_type == 2){
								echo "<b>Retailer </b><br>".getModelById('Retailer',$token->retailer_id)->name."(".getModelById('Retailer',$token->retailer_id)->address.")";
								echo "<br><b>";
									echo getModelById('Dealer',$token->account_from_id)->name."(".getModelById('Dealer',$token->account_from_id)->address1.")"."</b>";
								}else if($token->to_type == 3){
								echo "<b>Dealer </b><br>".getModelById('Dealer',$token->dealer_id)->name."(".getModelById('Dealer',$token->dealer_id)->address1.")";
							}
							@endphp
						</td>
						<td>{{getModelById('Product',$token->product_id)->name}}</td>
						<td>{{$token->quantity}}</td>
						<td>{{$token->quantity - totalTokenLoading($token->id)}}</td>
						<td>{{getModelById('Unit',$token->unit_id)->unit}}</td>
						<td>{{$token->rate}}</td>
						<td>{{$token->delivery_payment_mode}}</td>
						<td>{{is_null(getModelById('Transporter',$token->transporter_id)) ? "":getModelById('Transporter',$token->transporter_id)->name}}</td>
						<td>{{is_null(getModelById('User',$token->warehouse_keeper_id)) ? "":getModelById('User',$token->warehouse_keeper_id)->name}}</td>
						<td>
							<a href="/user/edit-token/{{$token->id}}" class="btn btn-xs btn-info">
								<i class="ace-icon fa fa-pencil bigger-120"></i>
							</a>
							<a href="/user/print-token/{{$token->id}}" class="btn btn-xs btn-info" >
								<i class="ace-icon fa fa-print bigger-120"></i>
							</a>
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
