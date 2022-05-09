@extends('dashboard.layouts.app')
@section('title','Transporter')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Transporter</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Transporter</h3>


					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Results for "Latest Registered Transporter"
						<div class="widget-toolbar no-border">
							<a class="btn btn-xs bigger btn-danger dropdown-toggle"  data-toggle="modal" href='#addTransporterModal'>
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

									<th>Transporter Name</th>
									<th>Hindi Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th></th>
								</tr>
							</thead>

							<tbody>
								@foreach($transporters as $transporter)
								<tr id="tr_{{$transporter->id}}">

									<td>{{$transporter->name}}</td>
									<td>{{$transporter->hindi_name}}</td>
									<td>{{$transporter->email}}</td>
									<td>{{$transporter->phone}}</td>
									<td>
										<div class="hidden-sm hidden-xs btn-group">
											<a class="btn btn-xs btn-info" onclick="getEdit({{$transporter->id}})" >
												<i class="ace-icon fa fa-pencil bigger-120"></i>
											</a>

											<button class="btn btn-xs btn-danger" onclick="deleteTransporter({{$transporter->id}})" >
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


<!-- Add Transporter Modal -->
<div class="modal fade" id="addTransporterModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Transporter</h4>
			</div>
			<div class="modal-body">

				<form action="" role="form" id="addTransporterForm">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" name="name" id="name" placeholder="Name">
								<span class="label label-danger" id="add_name_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for="name">Hindi Name</label>
								<input type="text" class="form-control convertHindi" name="hindi_name" id="hindi_name" placeholder="Name">
								<span class="label label-danger" id="add_hindi_name_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for="email">Email</label>
								<input type="text" class="form-control" name="email" id="email" placeholder="Email">
								<span class="label label-danger" id="add_email_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for="phone">Phone</label>
								<input type="text" class="form-control" name="phone" id="phone" placeholder="Phone">
								<span class="label label-danger" id="add_phone_error" style="display: none;"></span>
							</div>
						</div>
					</div>

					<div class="row">

						<div class="col-md-6">
							<div class="form-group">
								<label for="destination">Destination</label>
								<input type="text" class="form-control checkIfValid" name="destination[]"  placeholder="Destination">
								<span class="label label-danger" id="add_phone_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label for="rate">Rate</label>
								<input type="text" class="form-control checkIfValid" name="rate[]"  placeholder="Rate">
								<span class="label label-danger" id="add_phone_error" style="display: none;"></span>
							</div>
						</div>

					</div>

					<div id="addMoreRowSection">

					</div>

					<div class="pull-left">
						<button type="button" id="addMoreRow" class="btn btn-danger"><i class="fa fa-plus"></i> Add More </button>

					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="addTransporterBtn" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Add Transporter Modal -->


<!-- Edit Transporter Modal -->

<div class="modal fade" id="editTransporterModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Update Transporter</h4>
			</div>
			<div class="modal-body" id="EditBody">


			</div>
		</div>
	</div>
</div>

<!-- Edit Transporter Modal -->
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


				$('#addMoreRow').click(function(){
					var count = $('.row').length + 1;
					console.log(count);
					var newRow = `<div class="row" id="newRow`+count+`">
					<div class="col-md-6">
					<div class="form-group">
					<label for="destination">Destination</label>
					<input type="text" class="form-control checkIfValid" name="destination[]"  placeholder="Destination">
					<span class="label label-danger" id="add_phone_error" style="display: none;"></span>
					</div>
					</div>
					<div class="col-md-5">
					<div class="form-group">
					<label for="rate">Rate</label>
					<input type="text" class="form-control checkIfValid" name="rate[]"  placeholder="Rate">
					<span class="label label-danger" id="add_phone_error" style="display: none;"></span>
					</div>
					</div>
					<div class="col-md-1">
					<a href="javascript:;" onclick="removeRow(`+count+`)"><i class="fa fa-close fa-2x"></i></a>
					</div>
					</div>
					`;
					$('#addMoreRowSection').append(newRow);
					$('.select2').select2();

				});

				$('#editMoreRow').click(function(){
					var count = $('.row').length + 1;
					console.log(count);
					var newRow = `<div class="row" id="newRow`+count+`">
					<div class="col-md-6">
					<div class="form-group">
					<label for="destination">Destination</label>
					<input type="text" class="form-control checkIfValid" name="destination[]"  placeholder="Destination">
					<span class="label label-danger" id="add_phone_error" style="display: none;"></span>
					</div>
					</div>
					<div class="col-md-5">
					<div class="form-group">
					<label for="rate">Rate</label>
					<input type="text" class="form-control checkIfValid" name="rate[]"  placeholder="Rate">
					<span class="label label-danger" id="add_phone_error" style="display: none;"></span>
					</div>
					</div>
					<div class="col-md-1">
					<a href="javascript:;" onclick="removeRow(`+count+`)"><i class="fa fa-close fa-2x"></i></a>
					</div>
					</div>
					`;
					$('#editMoreRowSection').append(newRow);
					$('.select2').select2();

				});

				$('#addTransporterBtn').click(function(e){
					e.preventDefault();

					if(validateField()){
						return false;
					}else{
						$.ajaxSetup({
							headers: {
								'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
							}
						});
						$('.loading-bg').show();
						$.ajax({
							url: $('#addTransporterForm').attr('action'),
							method: 'POST',
							data: $('#addTransporterForm').serialize(),
							success: function(data){
								$('.loading-bg').hide();
								if(!data.flag){
									showError('add_name_error',data.errors.name);
									showError('add_email_error',data.errors.email);
									showError('add_phone_error',data.errors.phone);
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
				});

			});


			function validateField(){
				var errorCount = 0;
				$('.checkIfValid').each(function(){
					console.log($(this).val());
					if($(this).val() == ""){
						errorCount = errorCount + 1;
						$(this).closest('.form-group').find('.label-danger').text('Required');
						$(this).closest('.form-group').find('.label-danger').show();
					}else{
						$(this).closest('.form-group').find('.label-danger').hide();
					}
				});

				if(errorCount > 0){
					return true;
				}else{
					return false;
				}
			}
			function removeRow(id){
				$('#newRow'+id).remove();
			}

			function getEdit(id){
				if(id == ""){
					swal('Error','Transporter id is missing','warning');
				}else{
					$.ajax({
						url: "{{url('/user/edit-transporter/')}}"+"/"+id,
						type: 'GET',
						success:function(data){
							$('#EditBody').html(data);
							$('#editTransporterModal').modal('toggle');
							OnLoad();
						}
					});
				}
			}

			function updateTransporter(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					}
				});
				$.ajax({
					url: $('#editTransporterForm').attr('action'),
					method: 'POST',
					data: $('#editTransporterForm').serialize(),
					success: function(data){
						console.log(data);
						if(!data.flag){
							showError('edit_name_error',data.errors.name);
							showError('edit_email_error',data.errors.email);
							showError('edit_phone_error',data.errors.phone);
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

			function deleteTransporter(id){
				if(id == ""){
					swal('Error','Transporter id is missing','warning');
				}else{
					swal({
						title: "Are you sure?",
						text: "You will not be able to recover this Transporter!",
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
								url: "{{url('/user/delete-transporter/')}}"+"/"+id,
								type: 'GET',
								success:function(data){
									if(data.flag){
										$('#tr_'+id).remove();
										swal("Success", "Transporter Deleted Successfully", "success");
									}else{
										swal("Error", data.message, "error");
									}
								}
							});
						} else {
							swal("Cancelled", "Your Transporter is safe :)", "error");
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
