@extends('dashboard.layouts.app')
@section('title','Dealers')
@section('content')


<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Dealers</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Dealers</h3>
					
					<div id="google_translate_elemen"></div>

					<script type="text/javascript">
						function googleTranslateElementInit() {
							new google.translate.TranslateElement({
								pageLanguage: 'en',
								includedLanguages: 'hi',

								layout: google.translate.TranslateElement.InlineLayout.SIMPLE
							}, 'google_translate_elemen');
						}
					</script>

					<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

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
						Results for "Latest Registered Dealers"
						<div class="widget-toolbar no-border">
							<a  href="{{URL('/user/export-dealer')}}" class="btn btn-xs bigger btn-primary">
								Export
							</a>

							<a class="btn btn-xs bigger btn-primary dropdown-toggle"  data-toggle="modal" href='#importDealerModal'>
								Import
							</a>

							<a class="btn btn-xs bigger btn-danger dropdown-toggle"  data-toggle="modal" href='#addDealerModal'>
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
									<th>Dealer</th>
									<th>Mobile No</th>
									<th>Address</th>
									<th>Address2</th>
									<th>District</th>
									<th></th>
								</tr>
							</thead>

							<tbody>
								@foreach($dealers as $dealer)
								<tr id="tr_{{$dealer->id}}">

									<td>{{$dealer->unique_id}}</td>
									<td>{{$dealer->name}}</td>
									<td>{{$dealer->phone}}</td>
									<td>{{$dealer->address1}}</td>
									<td>{{$dealer->address2}}</td>
									<td>{{$dealer->district}}</td>
									<td>
										<div class="hidden-sm hidden-xs btn-group">
											<a class="btn btn-xs btn-info" onclick="getEdit({{$dealer->id}})" >
												<i class="ace-icon fa fa-pencil bigger-120"></i>
											</a>

											<button class="btn btn-xs btn-danger" onclick="deleteDealer({{$dealer->id}})" >
												<i class="ace-icon fa fa-trash-o bigger-120"></i>
											</button>
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>




		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->


<!-- Import Dealer Modal -->
<div class="modal fade" id="importDealerModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Import Dealer</h4>
			</div>
			<div class="modal-body">

				<form  action="{{URL('/user/import-dealer')}}" enctype="multipart/form-data" method="post">
					<div class="row">
						{{csrf_field()}}
						<div class="col-md-12">
							<div class="form-group">
								<label for="name">File</label>
								<input type="file" class="form-control" name="dealer_file" id="dealer_file">
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
<!-- Import Dealer Modal -->

<!-- Add Dealer Modal -->
<div class="modal fade" id="addDealerModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Dealer</h4>
			</div>
			<div class="modal-body">

				<form action="" role="form" id="addDealerForm">
					<div class="row">


					<div class="col-md-4">
							<div class="form-group">
								<label for="name">Dealer Unique Id</label>
								<input type="text" class="form-control" name="unique_id" id="unique_id" placeholder="Dealer Unique Id">
								<span class="label label-danger" id="add_unique_id_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" name="name" id="name" placeholder="Name">
								<span class="label label-danger" id="add_name_error" style="display: none;"></span>
							</div>
						</div>	

						<div class="col-md-4">
							<div class="form-group">
								<label for="hindi_name">Hindi Name</label>
								<input type="text" class="form-control convertHindi"  name="hindi_name" id="hindi_name" placeholder="Hindi Name">
							</div>
						</div>

					
						<div class="clearfix"></div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="phone">Phone</label>
								<input type="text" class="form-control" name="phone" id="phone" placeholder="Phone">
								<span class="label label-danger" id="add_phone_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-4">
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
						<div class="col-md-4">
							<div class="form-group">
								<label for="address">Address 1</label>
								<input type="text" class="form-control" name="address1" id="address1" placeholder="Address1">
								<span class="label label-danger" id="add_address1_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="hindi_address">Hindi Address 1</label>
								<input type="text" class="form-control convertHindi" name="hindi_address1" id="hindi_address1" placeholder="Hindi Address1">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="address2">Address2</label>
								<input type="text" class="form-control" name="address2" id="address2" placeholder="Address2">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="hindi_address2">Hindi Address2</label>
								<input type="text" class="form-control convertHindi" name="hindi_address2" id="hindi_address2" placeholder="Hindi Address2">
							</div>
						</div>	
						<div class="clearfix"></div>

						

						<div class="col-md-4">
							<div class="form-group">
								<label for="district">District</label>
								<input type="text" class="form-control" name="district" id="district" placeholder="District">
								<span class="label label-danger" id="add_district_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="pin_code">Pin Code</label>
								<input type="text" class="form-control" name="pin_code" id="pin_code" placeholder="Pin Code">
								<span class="label label-danger" id="add_pin_code_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="owner_name">Owner</label>
								<input type="text" class="form-control" name="owner_name" id="owner_name" placeholder="Owner">
							</div>
						</div>	
						<div class="clearfix"></div>

						

						<div class="col-md-4">
							<div class="form-group">
								<label for="mobile_number">Mobile Number</label>
								<input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="Mobile Number">
								<span class="label label-danger" id="add_mobile_number_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="email">Email</label>
								<input type="text" class="form-control" name="email" id="email" placeholder="Email">
								<span class="label label-danger" id="add_email_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="ifms_code">IFMS Code</label>
								<input type="text" class="form-control" name="ifms_code" id="ifms_code" placeholder="IFMS Code">
							</div>
						</div>
						<div class="clearfix"></div>

							

						<div class="col-md-4">
							<div class="form-group">
								<label for="gst_number">GST Number</label>
								<input type="text" class="form-control" name="gst_number" id="gst_number" placeholder="GST Number">
								<span class="label label-danger" id="add_gst_number_error" style="display: none;"></span>
							</div>
						</div>
						<div class="clearfix"></div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="show_separate_report">Show Separate Report</label>
								<input type="checkbox"  name="show_separate_report" id="show_separate_report">
							</div>
						</div>

						


					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="addDealerBtn" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Add Dealer Modal -->


<!-- Edit Dealer Modal -->

<div class="modal fade" id="editDealerModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Update Dealer</h4>
			</div>
			<div class="modal-body" id="EditBody">


			</div>
		</div>
	</div>
</div>

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
		$('.select2').select2();
				//initiate dataTables plugin
				var myTable = 
				$('#dynamic-table').DataTable( {
					bAutoWidth: false,
					"aaSorting": [],
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
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


		<script>

			$(document).ready(function(){


				$('#addDealerBtn').click(function(e){
					$('.loading-bg').show();
					e.preventDefault();
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					});
					$.ajax({
						url: $('#addDealerForm').attr('action'),
						method: 'POST',
						data: $('#addDealerForm').serialize(),
						success: function(data){
							$('.loading-bg').hide();
							if(!data.flag){
								$.each(data.errors, function(key,val) {
						            // alert(key+val);

						            showError('add_'+key+'_error',val);
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
				});

			});


			function getEdit(id){

				
				if(id == ""){
					swal('Error','Dealer id is missing','warning');
				}else{
					$('.loading-bg').show();
					$.ajax({
						url: "{{url('/user/edit-dealer/')}}"+"/"+id,
						type: 'GET',
						success:function(data){
							$('.loading-bg').hide();
							$('#EditBody').html(data);
							$('#editDealerModal').modal('toggle');
							OnLoad();
						}
					});
				}
			}

			function updateDealer(){
				$('.loading-bg').show();
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					}
				});
				$.ajax({
					url: $('#editDealerForm').attr('action'),
					method: 'POST',
					data: $('#editDealerForm').serialize(),
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

			function deleteDealer(id){
				if(id == ""){
					swal('Error','Dealer id is missing','warning');
				}else{
					swal({
						title: "Are you sure?",
						text: "You will not be able to recover this Dealer!",
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
								url: "{{url('/user/delete-dealer/')}}"+"/"+id,
								type: 'GET',
								success:function(data){
									if(data.flag){
										$('#tr_'+id).remove();
										swal("Success", "Dealer Deleted Successfully", "success");
									}else{
										swal("Error", data.message, "error");
									}
								}
							});
						} else {
							swal("Cancelled", "Your Dealer is safe :)", "error");
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
