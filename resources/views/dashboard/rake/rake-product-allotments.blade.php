@extends('dashboard.layouts.app')
@section('title','Rake Product Allotments')
@section('content')
<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home{{__('messages.Home')}}</a>
				</li>
				<li class="active">   {{__('messages.Rake')}} {{__('messages.Product')}} {{__('messages.Allotment')}}</li>
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
			<h3 class="header smaller lighter blue">  {{__('messages.Rake')}} {{__('messages.Product')}} {{__('messages.Allotment')}}</h3>

			<form action="" method="POST" role="form">
				<div class="row">
					{{csrf_field()}}
					<div class="col-md-3">
						<div class="form-group">
							<label for="master_rake_id"> {{__('messages.masterrake')}}</label>
							<select class="form-control select2" name="master_rake_id" id="master_rake_id">
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
					
					<div class="col-md-3">
						<div class="form-group">
							<button type="submit" class="btn btn-primary">Submit</button>
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
				<div class="table-header">
					Results for "Latest Registered Rake Product Allotments"
					<div class="widget-toolbar no-border">
						<a class="btn btn-xs bigger btn-danger dropdown-toggle"  data-toggle="modal" href="{{URL('/user/allot-product')}}">
							{{__('messages.AddNew')}}
							<i class="ace-icon fa fa-plus icon-on-right"></i>
						</a>
					</div>

				</div>

				<div class="table-responsive">
					<div class="dataTables_borderWrap">
						<table id="dynamic-table" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>

									<th> {{__('messages.Allotment')}}#</th>
									<th> {{__('messages.masterrake')}}</th>
									<th> {{__('messages.Dealer')}}</th>
									<th> {{__('messages.Product')}}</th>
									<th> {{__('messages.Unit')}}</th>
									<th> {{__('messages.Quantity')}}</th>
									<th> {{__('messages.Remaining')}} {{__('messages.Token')}}  <br>{{__('messages.Quantity')}} </th>
									<th> {{__('messages.Remaining')}} {{__('messages.LoadingSlip')}}<br> {{__('messages.Quantity')}}</th>
									<th> {{__('messages.Allotment')}}<br> {{__('messages.Date')}}</th>
									<th></th>
								</tr>
							</thead>

							<tbody>
								@foreach($allotments as $allotment)
								<tr id="tr_{{$allotment->id}}">

									<td>{{$allotment->id}}</td>
									<td>{{getModelById('MasterRake',$allotment->master_rake_id)->name}}</td>
									<td>{{getModelById('Dealer',$allotment->dealer_id)->name}}({{getModelById('Dealer',$allotment->dealer_id)->address1}})</td>
									<td>
										{{getModelById('Product',$allotment->product_id)->name}}
									</td>
									<td>
										{{getModelById('Unit',$allotment->unit_id)->unit}}
									</td>
									<td>{{$allotment->alloted_quantity}}</td>
									<td>{{$allotment->remaining_quantity}}</td>
									<td>
										{{$allotment->alloted_quantity - getDealerTotalLoadingQuantity($allotment->dealer_id,$allotment->master_rake_id,$allotment->product_id)}}
									</td>
									<td>{{date('d/m/Y',strtotime($allotment->created_at))}}</td>
									<td>
										{{-- <a class="btn btn-xs btn-info" href="{{URL('user/edit-allotment/'.$allotment->id)}}" >
											<i class="ace-icon fa fa-pencil bigger-120"></i>
										</a>
										--}}
										<button class="btn btn-xs btn-danger" onclick="deleteallotment({{$allotment->id}})" >
											<i class="ace-icon fa fa-trash-o bigger-120"></i>
										</button>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
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
					<h4 class="modal-title">  {{__('messages.Alloted')}} {{__('messages.Products')}}</h4>
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
		</script>
		@endsection
		@endsection
