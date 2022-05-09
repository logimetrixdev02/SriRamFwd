

@extends('dashboard.layouts.app')
@section('title','Stock Report')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Master Rakes</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Master Rakes</h3>

					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Results for "Latest Registered Master Rakes"
						<div class="widget-toolbar no-border">
							<a class="btn btn-xs bigger btn-danger dropdown-toggle"  data-toggle="modal" href='#addMasterRakeModal'>
								Add
								<i class="ace-icon fa fa-plus icon-on-right"></i>
							</a>
						</div>
					</div>

					<div class="table-responsive">
						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Name</th>
										<th>Session</th>
										<th>Rake Point</th>
										<th>Product Company</th>
										<th>Placement Time</th>
										<th>Unloading Time</th>

										<th>Date</th>
										<th>RR Document</th>
										<th>Warfage<br>Document</th>
										<th></th>
									</tr>
								</thead>

								<tbody>
									@foreach($master_rakes as $master_rake)
									<tr id="tr_{{$master_rake->id}}">

										<td>{{$master_rake->name}}</td>
										<td>{{ getModelById('Session',$master_rake->session_id)->session}}</td>
										<td>{{ getModelById('RakePoint',$master_rake->rake_point_id)->rake_point}}</td>
										<td>{{ getModelById('ProductCompany',$master_rake->product_company_id)->name }}</td>
										<td>{{$master_rake->loading_time}}</td>
										<td>{{$master_rake->unloading_time}}</td>
										
										<td>{{date('d-m-Y',strtotime($master_rake->date))}}</td>
										<td>@if(!is_null($master_rake->rr_document) || $master_rake->rr_document != "")
											<a href="{{URL('/').$master_rake->rr_document}}" download="download"><i class="fa fa-download"></i></a>
										@endif</td>
										<td>@if(!is_null($master_rake->warfage_document) || $master_rake->warfage_document != "")
											<a href="{{URL('/').$master_rake->warfage_document}}" download="download"><i class="fa fa-download"></i></a>
										@endif</td>
										<td>
											@if($master_rake->is_closed==0)
											<div class="hidden-sm hidden-xs btn-group">
												<a class="btn btn-xs btn-info" onclick="getEdit({{$master_rake->id}})" title="Edit">
													<i class="ace-icon fa fa-pencil bigger-120"></i>
												</a>
												<button class="btn btn-xs btn-danger" onclick="deleteMasterRake({{$master_rake->id}})" title="Delete">
													<i class="ace-icon fa fa-trash-o bigger-120"></i>
												</button>

												<button class="btn btn-xs btn-alert" onclick="lockMasterRake({{$master_rake->id}})" title="Lock">
													<i class="ace-icon fa fa-ban bigger-120"></i>
												</button>
											</div>
											@endif
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
<!-- Add Master Rake Modal -->
<div class="modal fade" id="addMasterRakeModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Master Rake</h4>
			</div>
			<div class="modal-body">

				<form action="" role="form" id="addMasterRakeForm">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Session</label>
								<select class="form-control select2" name="session_id" id="session_id">
									<option value="">Select Session</option>
									@foreach($sessions as $session)
									<option value="{{$session->id}}">{{$session->session}}</option>
									@endforeach()
								</select>
								<span class="label label-danger" id="add_session_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Product Company</label>
								<select class="form-control select2" name="product_company_id" id="product_company_id">
									<option value="">Select Product Company</option>
									@foreach($product_companies as $product_company)
									<option value="{{$product_company->id}}">{{$product_company->name}} ({{$product_company->brand_name}})</option>
									@endforeach()
								</select>
								<span class="label label-danger" id="add_product_company_error" style="display: none;"></span>
							</div>
						</div>

						<div class="clearfix"></div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Placement Time</label>
								<input type="text" class="form-control timepicker" name="loading_time" id="loading_time" placeholder="Placement Time">
								<span class="label label-danger" id="add_loading_time_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Unloading Time</label>
								<input type="text" class="form-control timepicker" name="unloading_time" id="unloading_time" placeholder="Unloading Time">
								<!-- <span class="label label-danger" id="add_unloading_time_error" style="display: none;"></span> -->
							</div>
						</div>

						<div class="clearfix"></div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Date</label>
								<input type="text" class="form-control date-picker" name="date" id="date" placeholder="Date">
								<span class="label label-danger" id="add_date_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="name">RR Document</label>
								<input type="file" class="form-control" name="rr_document" id="rr_document" placeholder="Date">
								<span class="label label-danger" id="add_rr_document_error" style="display: none;"></span>
							</div>
						</div>

						<div class="clearfix"></div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="demurrage">Demurrage</label>
								<input type="text" class="form-control" name="demurrage" id="demurrage" placeholder="Demurrage">
								<span class="label label-danger" id="add_demurrage_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="wharfage">Wharfage</label>
								<input type="text" class="form-control" name="wharfage" id="wharfage" placeholder="wharfage">
								<span class="label label-danger" id="add_wharfage_error" style="display: none;"></span>
							</div>
						</div>

						<div class="clearfix"></div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="cheque_number">Cheque number</label>
								<input type="text" class="form-control" name="cheque_number" id="cheque_number" placeholder="Cheque number">
								<span class="label label-danger" id="add_cheque_number_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="payment_date">Payment date</label>
								<input type="text" class="form-control date-picker" name="payment_date" id="payment_date" placeholder="Payment date">
								<span class="label label-danger" id="add_payment_date_error" style="display: none;"></span>
							</div>
						</div>

						<div class="clearfix"></div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="rake_point">Rake Point</label>
								<select class="form-control select2 checkIfValid" name="rake_point" id="rake_point">
									<option value="">Select Product</option>
									@foreach($rake_points as $rake_point)
									<option value="{{$rake_point->id}}">{{$rake_point->rake_point}}</option>
									@endforeach()
								</select>
								<span class="label label-danger" id="add_rake_point_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Warfage Document</label>
								<input type="file" class="form-control" name="warfage_document" id="warfage_document" placeholder="Date">
								<span class="label label-danger" id="add_warfage_document_error" style="display: none;"></span>
							</div>
						</div>
						<div class="clearfix"></div>

					</div>
					<div class="row">

						<div class="col-md-6">
							<div class="form-group">
								<label for="proruct">Product</label>
								<select class="form-control select2 checkIfValid" name="product_id[]" id="product_id">
									<option value="">Select Product</option>
									@foreach($products as $product)
									<option value="{{$product->id}}">{{$product->name}}</option>
									@endforeach()
								</select>
								<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label for="rate">Quantity</label>
								<input type="text" class="form-control checkIfValid" name="quantity[]"  placeholder="Quantity">
								<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
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
					<button type="button" id="addMasterRakeBtn" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Add Master Rake Modal -->


<!-- Edit Master Rake Modal -->

<div class="modal fade" id="editMasterRakeModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Update Master Rake</h4>
			</div>
			<div class="modal-body" id="EditBody">


			</div>
		</div>
	</div>
</div>

<!-- Edit Master Rake Modal -->

<!-------Start lock page modal---------->
<div class="modal fade" id="lockMasterRakeModal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Lock Master Rake</h4>
			</div>
			<div class="modal-body" id="lockBody">


				<div class="row">
					<input type="hidden" value="" name="master_rake_id" id="master_rake_id">
					<span class="label label-danger" id="edit_name_error" style="display: none;">All fields must be active*</span>
					<div class="widget-main">
						<div class="row">
							<div class="col-xs-6">
								<label>
									Is Demurrage Field?
								</label>
							</div>
							<div class="col-xs-3">
								<label>
									<input name="is_demurrage_field" class="ace ace-switch ace-switch-4 btn-flat checkIfValids" type="checkbox" value="10"   >
									<span class="lbl"></span>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<label>
									Is Wharfage Field?
								</label>
							</div>
							<div class="col-xs-3">
								<label>
									<input name="is_wharfage_field" id="validate_check" class="ace ace-switch ace-switch-4 btn-flat checkIfValids" type="checkbox" value="11"   >
									<span class="lbl"></span>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<label>
									All labour payment done?
								</label>
							</div>
							<div class="col-xs-3">
								<label>
									<input name="all_labour_payment_done" id="validate_check" class="ace ace-switch ace-switch-4 btn-flat checkIfValids" type="checkbox" value="11"   >
									<span class="lbl"></span>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<label>
									Frieght payment done?
								</label>
							</div>
							<div class="col-xs-3">
								<label>
									<input name="frieght_payment_done" id="validate_check" class="ace ace-switch ace-switch-4 btn-flat checkIfValids" type="checkbox" value="11"   >
									<span class="lbl"></span>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<label>
									Wagon Unloading payment done?
								</label>
							</div>
							<div class="col-xs-3">
								<label>
									<input name="wagon_unloading_payment_done" id="validate_check" class="ace ace-switch ace-switch-4 btn-flat checkIfValids" type="checkbox" value="11"   >
									<span class="lbl"></span>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<label>
									Unloading labour payment done?
								</label>
							</div>
							<div class="col-xs-3">
								<label>
									<input name="unloading_labour_payment_done" id="validate_check" class="ace ace-switch ace-switch-4 btn-flat checkIfValids" type="checkbox" value="11"   >
									<span class="lbl"></span>
								</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" id="lockMasterRakeBtnEnabled"  class="btn btn-primary" >Close Now</button>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>


<!----------End of lock page modal--------->

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

			$(document).ready(function(){

				// $('.select2').select2();
				$('.timepicker').mdtimepicker({
					timeFormat: 'hh:mm:ss.000',
					format: 'h:mm tt',     
					theme: 'blue',       
					readOnly: true,      
					hourPadding: false    
				});

				$('#addMasterRakeBtn').click(function(e){
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

						var formData = new FormData();
						formData.append('session_id', $('#session_id option:selected').val());
						formData.append('rake_point', $('#rake_point option:selected').val());
						formData.append('product_company_id', $('#product_company_id option:selected').val());
						formData.append('loading_time', $('#loading_time').val());
						formData.append('date', $('#date').val());
						formData.append('unloading_time', $('#unloading_time').val()) ;
						formData.append('demurrage', $('#demurrage').val()) ;
						formData.append('wharfage', $('#wharfage').val()) ;
						formData.append('cheque_number', $('#cheque_number').val()) ;
						formData.append('payment_date', $('#payment_date').val()) ;
						var product_id = [];
						$("select[name='product_id[]']").each(function() {
							product_id.push($(this).val());
						});

						var quantity = [];
						$("input[name='quantity[]']").each(function() {
							quantity.push($(this).val());
						});

						formData.append('product_id',  product_id) ;
						formData.append('quantity',  quantity) ;
						if (typeof $('#rr_document')[0].files[0] !== 'undefined') {
							formData.append('rr_document', $('#rr_document')[0].files[0]);
						}
						if (typeof $('#warfage_document')[0].files[0] !== 'undefined') {
							formData.append('warfage_document', $('#warfage_document')[0].files[0]);
						}

						$.ajax({
							url: $('#addMasterRakeForm').attr('action'),
							method: 'POST',
							data: formData,
							contentType: false,
							cache: false,
							processData:false,
							success: function(data){
								$('.loading-bg').hide();
								if(!data.flag){
									showError('add_session_error',data.errors.session_id);
									showError('add_product_company_error',data.errors.product_company_id);
									showError('add_loading_time_error',data.errors.loading_time);
									showError('add_date_error',data.errors.date);
									showError('add_rake_point_error',data.errors.rake_point);
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


				$('#lockMasterRakeBtnEnabled').click(function(e){
					e.preventDefault();

					var errorCount = 0;
					$('.checkIfValids').each(function(){
						if(this.checked ==false){
							errorCount = errorCount + 1;
						}	
					});

					// if(errorCount > 0){
						if(0){
							$("#edit_name_error").show();
							$('.loading-bg').hide();
							return false;
						}

						else{
							$("#edit_name_error").hide();
							
							$.ajax({
								url:  "{{url('/')}}"+'/master/lock-master-rake/'+$('#master_rake_id').val(),
								method: 'GET',
								success: function(data){
									console.log(data);
								// var data = JSON.parse(data);
								if(data.flag){
									swal({
										title: "Success!",
										text: data.message,
										type: "success"
									}, function() {
										window.location.reload();
									});
								}else{
									swal('Error',data.message,'error')
								}
								
							}
						});
						}	
					});


			});


			function getEdit(id){
				if(id == ""){
					swal('Error','Master Rake id is missing','warning');
				}else{
					$('.loading-bg').show();
					$.ajax({
						url: "{{url('/master/edit-master-rake/')}}"+"/"+id,
						type: 'GET',
						success:function(data){
							$('.loading-bg').hide();
							$('#EditBody').html(data);
							$('#editMasterRakeModal').modal('toggle');
						}
					});

				}
			}


			function lockMasterRake(id){
				if(id == ""){
					swal('Error','Master Rake id is missing','warning');
				}else{
					$('#master_rake_id').val(id);
					$('#lockMasterRakeModal').modal('toggle');
				}
			}

			function deleteMasterRake(id){
				if(id == ""){
					swal('Error','Master Rake id is missing','warning');
				}else{
					swal({
						title: "Are you sure?",
						text: "You will not be able to recover this Master Rake!",
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
								url: "{{url('/master/delete-master-rake/')}}"+"/"+id,
								type: 'GET',
								success:function(data){
									if(data.flag){
										$('#tr_'+id).remove();
										swal("Success", "Master Rake Deleted Successfully", "success");
									}else{
										swal("Error", data.message, "error");
									}
								}
							});
						} else {
							swal("Cancelled", "Your Master Rake is safe :)", "error");
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
			};

			function removeRow(id){
				$('#newRow'+id).remove();
			}

			$('#addMoreRow').click(function(){
				var count = $('.row').length + 1;
				console.log(count);
				var newRow = `<div class="row" id="newRow`+count+`">
				<div class="col-md-6">
				<div class="form-group">
				<label for="product">Product</label>
				<select class="form-control select2 checkIfValid" name="product_id[]" id="product_id">
				<option value="">Select Product</option>
				@foreach($products as $product)
				<option value="{{$product->id}}">{{$product->name}}</option>
				@endforeach()
				</select>
				<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
				</div>
				</div>
				<div class="col-md-5">
				<div class="form-group">
				<label for="rate">Quantity</label>
				<input type="text" class="form-control checkIfValid" name="quantity[]"  placeholder="Quantity">
				<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
				</div>
				</div>
				<div class="col-md-1" style="margin-top: 33px;">
				<a href="javascript:;" onclick="removeRow(`+count+`)"><i class="fa fa-close fa-2x"></i></a>
				</div>
				</div>
				`;
				$('#addMoreRowSection').append(newRow);
				$('.select2').select2();

			});
		</script>
		@endsection
		@endsection
