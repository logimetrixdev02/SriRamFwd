@extends('dashboard.layouts.app')
@section('title','Rakes')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Rakes</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Rakes</h3>


					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Results for "Latest Registered Rakes"
						<div class="widget-toolbar no-border">
							<a class="btn btn-xs bigger btn-danger dropdown-toggle"  data-toggle="modal" href='#addRakeModal'>
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

									<th>Name</th>
									<th>Rake No</th>
									<th>Product</th>
									<th>Quantity</th>
									<th>Master Rake</th>
									<th>Product Company</th>
									<th>Loading Time</th>
									<th>Unloading Time</th>
									<th>Date</th>
									<th></th>
								</tr>
							</thead>

							<tbody>
								@foreach($rakes as $rake)
								<tr id="tr_{{$rake->id}}">
									<td>{{$rake->name}}</td>
									<td>{{ $rake->rake_no}}</td>
									<td>{{ getModelById('Product',$rake->product_id)->name}}</td>
									<td>{{ $rake->quantity}}</td>
									<td>{{ getModelById('MasterRake',$rake->master_rake->id)->name}}</td>
									<td>{{ getModelById('ProductCompany',$rake->master_rake->product_company_id)->name }}</td>
									<td>{{$rake->master_rake->loading_time}}</td>
									<td>{{$rake->master_rake->unloading_time}}</td>
									<td>{{date('d-m-Y',strtotime($rake->master_rake->date))}}</td>
									<td>
										<div class="hidden-sm hidden-xs btn-group">
											
											<a class="btn btn-xs btn-info" onclick="getEdit({{$rake->id}})" >
												<i class="ace-icon fa fa-pencil bigger-120"></i>
											</a>

											<button class="btn btn-xs btn-danger" onclick="deleteRake({{$rake->id}})" >
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


<!-- Add Rake Modal -->
<div class="modal fade" id="addRakeModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Rake</h4>
			</div>
			<div class="modal-body">

				<form action="" role="form" id="addRakeForm">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="name">Master Rake</label>
								<select class="form-control" name="master_rake_id" id="master_rake_id" onchange="getMasterDetails(this.value,'')">
									<option value="">Select Master Rake</option>
									@foreach($master_rakes as $master_rake)
									<option value="{{$master_rake->id}}">{{$master_rake->name}}</option>
									@endforeach()
								</select>
								<span class="label label-danger" id="add_master_rake_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="name">Session</label>
								<select class="form-control" name="session" id="session">
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="name">Product Company</label>
								<select class="form-control" name="product_company_id" id="product_company_id">
								</select>
							</div>
						</div>


						<div class="clearfix"></div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="name">Loading Time</label>
								<input type="text" class="form-control" name="loading_time" id="loading_time" placeholder="Loading Time">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="name">Unloading Time</label>
								<input type="text" class="form-control" name="unloading_time" id="unloading_time" placeholder="Unloading Time">
							</div>
						</div>


						<div class="col-md-4">
							<div class="form-group">
								<label for="name">Date</label>
								<input type="text" class="form-control" name="date" id="date" placeholder="Date">
							</div>
						</div>

						<div class="clearfix"></div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="name">Quantity</label>
								<input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity">
								<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="name">Product</label>
								<select class="form-control" name="product_id" id="product_id">
									<option value="">Select Product</option>
									@foreach($products as $product)
									<option value="{{$product->id}}">{{$product->name}}</option>
									@endforeach()
								</select>
								<span class="label label-danger" id="add_product_error" style="display: none;"></span>
							</div>
						</div>

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="addRakeBtn" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Add Rake Modal -->


<!-- Edit Rake Modal -->

<div class="modal fade" id="editRakeModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Update Company</h4>
			</div>
			<div class="modal-body" id="EditBody">


			</div>
		</div>
	</div>
</div>

<!-- Edit Rake Modal -->

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
{{ Html::script("assets/js/mdtimepicker.min.js")}}
{{ Html::script("assets/js/bootstrap-datepicker.min.js")}}


<script type="text/javascript">



	jQuery(function($) {

		//datepicker plugin
		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		})
				//show datepicker when clicking on the icon
				.next().on(ace.click_event, function(){
					$(this).prev().focus();
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


		<script>

			$(document).ready(function(){

				// $('.select2').select2();
				$('.timepicker').mdtimepicker({
					timeFormat: 'hh:mm:ss.000',
					format: 'h:mm tt',     
					theme: 'blue',       
					readOnly: true,      
					hourPadding: false    
				});

				$('#addRakeBtn').click(function(e){
					e.preventDefault();
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					});
					$.ajax({
						url: $('#addRakeForm').attr('action'),
						method: 'POST',
						data: $('#addRakeForm').serialize(),
						success: function(data){
							if(!data.flag){
								showError('add_master_rake_error',data.errors.master_rake_id);
								showError('add_product_error',data.errors.product_id);
								showError('add_quantity_error',data.errors.quantity);
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


			function getMasterDetails(id,type){

				if(id == ""){
					swal('Error','Master Rake id is missing','warning');
				}else{
					$('.loading-bg').show();
					$.ajax({
						url: "{{url('/user/master-rake-details/')}}"+"/"+id,
						type: 'GET',
						success:function(data){
							if(data.flag){
								$('.loading-bg').hide();
								var session_option = "<option value='"+data.master_rake.session.id+"'>"+data.master_rake.session.session+"</option>"
								var company_option = "<option value='"+data.master_rake.product_company.id+"'>"+data.master_rake.product_company.name+"</option>"
								$('#'+type+'session').html(session_option);
								$('#'+type+'product_company_id').html(company_option);
								$('#'+type+'loading_time').val(data.master_rake.loading_time);
								$('#'+type+'unloading_time').val(data.master_rake.unloading_time);
								$('#'+type+'date').val(data.master_rake.date);
							}else{
								swal('Error',data.message,'warning');
							}
						}
					});
				}
			}

			function getEdit(id){
				if(id == ""){
					swal('Error','Rake id is missing','warning');
				}else{
					$('.loading-bg').show();
					$.ajax({
						url: "{{url('/user/edit-rake/')}}"+"/"+id,
						type: 'GET',
						success:function(data){
							$('.loading-bg').hide();
							$('#EditBody').html(data);
							$('#editRakeModal').modal('toggle');
						}
					});
				}
			}

			function updateRake(){

				$('.loading-bg').show();

				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					}
				});
				$.ajax({
					url: $('#editRakeForm').attr('action'),
					method: 'POST',
					data: $('#editRakeForm').serialize(),
					success: function(data){
						$('.loading-bg').hide();
						console.log(data);
						if(!data.flag){
							showError('edit_master_rake_error',data.errors.master_rake_id);
							showError('edit_product_id_error',data.errors.product_id);
							showError('edit_quantity_error',data.errors.quantity);
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

			function deleteRake(id){
				if(id == ""){
					swal('Error','Rake id is missing','warning');
				}else{
					swal({
						title: "Are you sure?",
						text: "You will not be able to recover this Rake!",
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
							$('.loading-bg').show();
							$.ajax({
								url: "{{url('/user/delete-rake/')}}"+"/"+id,
								type: 'GET',
								success:function(data){
									$('.loading-bg').hide();
									if(data.flag){
										$('#tr_'+id).remove();
										swal("Success", "Rake Deleted Successfully", "success");
									}else{
										swal("Error", data.message, "error");
									}
								}
							});
						} else {
							swal("Cancelled", "Your Rake is safe :)", "error");
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
