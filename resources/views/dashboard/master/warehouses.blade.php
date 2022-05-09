@extends('dashboard.layouts.app')
@section('title','Warehouses')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Warehouses</li>
			</ul>
		</div>

		<div class="page-content">
			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Warehouses</h3>
					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Results for "Latest Registered Warehouses"
						<div class="widget-toolbar no-border">
							<a class="btn btn-xs bigger btn-danger dropdown-toggle"  data-toggle="modal" href='#addWarehouseModal'>
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
									<th>Warehouse</th>
									<th>Keeper</th>
									<th>Location</th>
									<th>Latitude</th>
									<th>Longitude</th>
									<th></th>
								</tr>
							</thead>

							<tbody>
								@foreach($warehouses as $warehouse)
								<tr id="tr_{{$warehouse->id}}">
									<td>{{$warehouse->unique_id}}</td>
									<td>{{$warehouse->name}}</td>
									<td>{{is_null($warehouse->user_id) ? "--":getModelById('User',$warehouse->user_id)->name}}</td>
									<td>{{$warehouse->location}}</td>
									<td>{{$warehouse->lat}}</td>
									<td>{{$warehouse->lng}}</td>
									<td>
										<div class="hidden-sm hidden-xs btn-group">
											<a class="btn btn-xs btn-info" onclick="getEdit({{$warehouse->id}})" >
												<i class="ace-icon fa fa-pencil bigger-120"></i>
											</a>
											{{--<button class="btn btn-xs btn-danger" onclick="deleteWarehouse({{$warehouse->id}})" >
												<i class="ace-icon fa fa-trash-o bigger-120"></i>
											</button>--}}
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


<!-- Add Warehouse Modal -->
<div class="modal fade" id="addWarehouseModal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Warehouse</h4>
			</div>
			<div class="modal-body">

				<form action="" role="form" id="addWarehouseForm">
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" name="name" id="name" placeholder="Warehouse Name">
								<span class="label label-danger" id="add_name_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="hindi_name">Hindi Name</label>
								<input type="text" class="form-control convertHindi" name="hindi_name" id="hindi_name" placeholder="Hindi Warehouse Name">
								<span class="label label-danger" id="add_name_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="user_id">Keeper</label>
								<select class="form-control" name="user_id" id="user_id" placeholder="Keeper">
									<option value="">Select keeper</option>
									@foreach($users as $user)
									<option value="{{$user->id}}">{{$user->name}}</option>
									@endforeach()
								</select> 
								<span class="label label-danger" id="add_user_id_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for="location">Location</label>
								<input type="text" class="form-control" name="location" id="location" placeholder="Warehouse Location">
								<span class="label label-danger" id="add_location_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for="lat">Latitude</label>
								<input type="text" class="form-control" name="lat" id="lat" placeholder="Latitude">
								<span class="label label-danger" id="add_lat_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="lng">Longitude</label>
								<input type="text" class="form-control" name="lng" id="lng" placeholder="Longitude">
								<span class="label label-danger" id="add_lng_error" style="display: none;"></span>
							</div>
						</div>


					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="addWarehouseBtn" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Add Warehouse Modal -->


<!-- Edit Warehouse Modal -->

<div class="modal fade" id="editWarehouseModal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Update Warehouse</h4>
			</div>
			<div class="modal-body" id="EditBody">


			</div>
		</div>
	</div>
</div>

<!-- Edit Warehouse Modal -->
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


		<script>

			$(document).ready(function(){
				$('#addWarehouseBtn').click(function(e){
					e.preventDefault();
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					});
					$.ajax({
						url: $('#addWarehouseForm').attr('action'),
						method: 'POST',
						data: $('#addWarehouseForm').serialize(),
						success: function(data){
							if(!data.flag){
								showError('add_name_error',data.errors.name);
								showError('add_user_id_error',data.errors.user_id);
								showError('add_location_error',data.errors.location);
								showError('add_lat_error',data.errors.lat);
								showError('add_lng_error',data.errors.lng);
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
					swal('Error','Warehouse id is missing','warning');
				}else{
					$.ajax({
						url: "{{url('/user/edit-warehouse/')}}"+"/"+id,
						type: 'GET',
						success:function(data){
							$('#EditBody').html(data);
							$('#editWarehouseModal').modal('toggle');
							OnLoad();
						}
					});
				}
			}

			function updateWarehouse(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					}
				});
				$.ajax({
					url: $('#editWarehouseForm').attr('action'),
					method: 'POST',
					data: $('#editWarehouseForm').serialize(),
					success: function(data){
						console.log(data);
						if(!data.flag){
							showError('edit_name_error',data.errors.name);
							showError('edit_user_id_error',data.errors.user_id);
							showError('edit_location_error',data.errors.location);
							showError('edit_lat_error',data.errors.lat);
							showError('edit_lng_error',data.errors.lng);
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

			function deleteWarehouse(id){
				if(id == ""){
					swal('Error','Warehouse id is missing','warning');
				}else{
					swal({
						title: "Are you sure?",
						text: "You will not be able to recover this Warehouse!",
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
								url: "{{url('/user/delete-warehouse/')}}"+"/"+id,
								type: 'GET',
								success:function(data){
									if(data.flag){
										$('#tr_'+id).remove();
										swal("Success", "Warehouse Deleted Successfully", "success");
									}else{
										swal("Error", data.message, "error");
									}
								}
							});
						} else {
							swal("Cancelled", "Your Warehouse is safe :)", "error");
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
		@endsection
		@endsection
