@extends('dashboard.layouts.app')
@section('title','Retailer')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Retailer</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Retailer</h3>
					<div id="google_translate_element"></div>


					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Results for "Latest Registered Retailer"
						<div class="widget-toolbar no-border">

							<a  href="{{URL('/user/export-retailer')}}" class="btn btn-xs bigger btn-primary">
								Export
							</a>

							<a class="btn btn-xs bigger btn-primary dropdown-toggle"  data-toggle="modal" href='#importRetailerModal'>
								Import
							</a>

							<a class="btn btn-xs bigger btn-danger dropdown-toggle"  data-toggle="modal" href='#addRetailerModal'>
								Add
								<i class="ace-icon fa fa-plus icon-on-right"></i>
							</a>
						</div>

					</div>

					<!-- div.table-responsive -->

					<!-- div.dataTables_borderWrap -->
					<div>
						<table id="dynamic-table" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>

									<th>Unique ID</th>
									<th>Retailer</th>
									<th>Dealer</th>
									<th>Phone</th>
									<th>Address</th>
									<th>Address2</th>
									<th>District</th>
									<th>GST</th>
									<th></th>
								</tr>
							</thead>

						
						</table>
					</div>
				</div>
			</div>




		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->


<!-- Import Retailer Modal -->
<div class="modal fade" id="importRetailerModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Import Retailer</h4>
			</div>
			<div class="modal-body">

				<form  action="{{URL('/user/import-retailer')}}" enctype="multipart/form-data" method="post">
					<div class="row">
						{{csrf_field()}}
						<div class="col-md-12">
							<div class="form-group">
								<label for="name">File</label>
								<input type="file" class="form-control" name="retailer_file" id="retailer_file">
							</div>
						</div>	
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" id="importDealerBtn" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Import Retailer Modal -->

<!-- Add Retailer Modal -->
<div class="modal fade" id="addRetailerModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Retailer</h4>
			</div>
			<div class="modal-body">

				<form action="" role="form" id="addRetailerForm">
					<div class="row">
					<div class="col-md-6">
							<div class="form-group">
								<label for="name"> Retailer Unique Code</label>
								<input type="text" class="form-control" name="unique_code" id="unique_code" placeholder="Retailer Unique Code">
								<span class="label label-danger" id="add_unique_code_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" name="name" id="name" placeholder="Name">
								<span class="label label-danger" id="add_name_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for="hindi_name">Hindi Name</label>
								<input type="text" class="form-control convertHindi" name="hindi_name" id="hindi_name" placeholder="Hindi name">
								
							</div>
						</div>	

						<div class="col-md-6">
							<div class="form-group">
								<label for="phone">Phone</label>
								<input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="Phone">
								<span class="label label-danger" id="add_mobile_number_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="phone">GSTIN</label>
								<input type="text" class="form-control" name="gst_number" id="gst_number" placeholder="GSTIN">
								<span class="label label-danger" id="add_gst_number_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="dealer_id">Location</label>
								<select class="form-control select2" name="location" id="location">
									<option value=""> Select Location</option>
									@foreach($locations as $location)
									<option value="{{$location->location_id}}">{{$location->name}} </option>
									@endforeach
								</select>
								<span class="label label-danger" id="add_location_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="district">District</label>
								<input type="text" class="form-control" name="district" id="district" placeholder="District">
								<span class="label label-danger" id="add_district_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address">Address</label>
								<input type="text" class="form-control" name="address" id="address" placeholder="Address">
								<span class="label label-danger" id="add_address_error" style="display: none;"></span>
							</div>
						</div>
                      <div class="col-md-6">
							<div class="form-group">
								<label for="hindi_address">Address 2</label>
								<input type="text" class="form-control" name="address2" id="hindi_address" placeholder="Address 2">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="hindi_address">Hindi address</label>
								<input type="text" class="form-control convertHindi" name="hindi_address" id="hindi_address" placeholder="Hindi address">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="dealer_id">Select Dealer</label>
								<select class="form-control select2" name="dealer_id" id="dealer_id">
									<option value=""> Select Deader</option>
									@foreach($dealers as $dealer)
									<option value="{{$dealer->unique_id}}">{{$dealer->name}} ( {{$dealer->unique_id}} )</option>
									@endforeach
								</select>
								<span class="label label-danger" id="add_dealer_id_error" style="display: none;"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="addRetailerBtn" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Add Retailer Modal -->


<!-- Edit Retailer Modal -->

<div class="modal fade" id="editRetailerModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Update Retailer</h4>
			</div>
			<div class="modal-body" id="EditBody">


			</div>
		</div>
	</div>
</div>

<!-- Edit Retailer Modal -->
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
{{ Html::script("http://www.google.com/jsapi")}}
<script type="text/javascript">
	google.load("elements", "1", {packages: "transliteration"});
</script> 


<script type="text/javascript">




		

			$(document).ready(function(){
				display();
				
				$('#addRetailerBtn').click(function(e){
					e.preventDefault();
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					});
					$('.loading-bg').show();
					$.ajax({
						url: $('#addRetailerForm').attr('action'),
						method: 'POST',
						data: $('#addRetailerForm').serialize(),
						success: function(data){
							$('.loading-bg').hide();
							if(!data.flag){
								$.each(data.errors, function(key,val) {
						            // alert(key+val);
						            showError('add_'+key+'_error',val);
						        });
								// showError('add_name_error',data.errors.name);
								//showError('add_company_error',data.errors.company_id);
								//showError('add_mobile_number_error',data.errors.mobile_number);
								//showError('add_gst_number_error',data.errors.gst_number);
								//showError('add_address_error',data.errors.address);
							}else{
								swal({
									title: "Success!",
									text: data.message,
									type: "success"
								}, function() {
									window.location.reload();
								});
							}
						}

					});
				});

			});


			function display(){
				
					var myTable=$('#dynamic-table').DataTable({
							"processing": true,
							"serverSide": true,
							bAutoWidth: false,
							"aaSorting": [],
							"ajax":{
								"url": "{{ route('user.retailer') }}",
								// "data":{from_date:from_date, to_date:to_date},
							},
							
							"columns":[
								
								{ "data": "unique_code" },
								{ "data": "name" },
								{ "data": "dealer_name" },
								
								{ "data": "mobile_number" },
								{ "data": "address" },
								{ "data": "address2" },
								{ "data": "district" },
								{ "data": "gst_number" },
							
							
							{"data": "action" }
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

			function getEdit(id){
			
				if(id == ""){
					swal('Error','Retailer id is missing','warning');
				}else{
					$.ajax({
						url: "{{url('/user/edit-retailer/')}}"+"/"+id,
						type: 'GET',
						success:function(data){
							$('#EditBody').html(data);
							$('#editRetailerModal').modal('toggle');
							OnLoad();
						}
					});
				}
			}

			function updateRetailer(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					}
				});
				$('.loading-bg').show();
				$.ajax({
					url: $('#editRetailerForm').attr('action'),
					method: 'POST',
					data: $('#editRetailerForm').serialize(),
					success: function(data){
						$('.loading-bg').hide();
						console.log(data);
						if(!data.flag){
							$.each(data.errors, function(key,val) {
						            // alert(key+val);
						            showError('edit_'+key+'_error',val);
						        });
						}else{
							swal({
								title: "Success!",
								text: data.message,
								type: "success"
							}, function() {
								window.location.reload();
							});
						}
					}

				});

			}

			function deleteRetailer(id){
				if(id == ""){
					swal('Error','Retailer id is missing','warning');
				}else{
					swal({
						title: "Are you sure?",
						text: "You will not be able to recover this Retailer!",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: '#DD6B55',
						confirmButtonText: 'Yes, I am sure!',
						cancelButtonText: "No, cancel it!",
						closeOnConfirm: false,
						closeOnCancel: false
					},
					function(isConfirm){
						if (isConfirm){
							$.ajax({
								url: "{{url('/user/delete-retailer/')}}"+"/"+id,
								type: 'GET',
								success:function(data){
									if(data.flag){
										$('#tr_'+id).remove();
										swal("Success", "Retailer Deleted Successfully", "success");
									}else{
										swal("Error", data.message, "error");
									}
								}
							});
						} else {
							swal("Cancelled", "Your Retailer is safe :)", "error");
						}
					});
				}
			}
			function showError(id,error){
				if(typeof(error) === "undefined"){
					$('#'+id).hide();
				}else{
					$('#'+id).show();
					$('#'+id).text(error);
				}
			}
		</script>

		<script type="text/javascript">
			function googleTranslateElementInit() {
				new google.translate.TranslateElement({pageLanguage: 'en',includedLanguages: 'en,hi'}, 'google_translate_element');
			}
		</script>

		<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

		@endsection
		@endsection
