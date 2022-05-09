@extends('dashboard.layouts.app')
@section('title','Companies')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Companies</li>
			</ul>
		</div>

		<div class="page-content">
			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Companies</h3>
					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Results for "Latest Registered Companies"
						<div class="widget-toolbar no-border">
							<a class="btn btn-xs bigger btn-danger dropdown-toggle"  data-toggle="modal" href='#addCompanyModal'>
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
									<th>Company</th>
									<th>Token Abbreviation</th>
									<th>Address Line1</th>
									<th>Address Line2</th>
									<th>City</th>
									<th>Phone</th>
									<th>Email</th>
									<th>GST #</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($companies as $company)
								<tr id="tr_{{$company->id}}">
									<td>{{$company->name}}</td>
									<td>{{$company->token_abbreviation}}</td>
									<td>{{$company->address1}}</td>
									<td>{{$company->address2}}</td>
									<td>{{$company->city}}</td>
									<td>{{$company->phone}}</td>
									<td>{{$company->email}}</td>
									<td>{{$company->gst_no}}</td>
									<td>
										<div class="hidden-sm hidden-xs btn-group">
											<a class="btn btn-xs btn-info" onclick="getEdit({{$company->id}})" >
												<i class="ace-icon fa fa-pencil bigger-120"></i>
											</a>

											<button class="btn btn-xs btn-danger" onclick="deleteCompany({{$company->id}})" >
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
<!-- Add Company Modal -->
<div class="modal fade" id="addCompanyModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Company</h4>
			</div>
			<div class="modal-body">
				<form action="" role="form" id="addCompanyForm">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Name *</label>
								<input type="text" class="form-control" name="name" id="name" placeholder="Name">
								<span class="label label-danger" id="add_name_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="hindi_name">Hindi Name</label>
								<input type="text" class="form-control convertHindi" name="hindi_name" id="hindi_name" placeholder="Hindi Name">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address1">Address Line 1 *</label>
								<input type="text" class="form-control" name="address1" id="address1" placeholder="Address Line 1">
								<span class="label label-danger" id="add_address1_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="hindi_address1">Hindi Address Line 1 </label>
								<input type="text" class="form-control convertHindi" name="hindi_address1" id="hindi_address1" placeholder="Hindi Address Line 1">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address2">Address Line 2 </label>
								<input type="text" class="form-control" name="address2" id="address2" placeholder="Address Line 2">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="hindi_address2">Hindi Address Line 2 </label>
								<input type="text" class="form-control convertHindi" name="hindi_address2" id="hindi_address2" placeholder="Hindi Address Line 2">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="city">City *</label>
								<input type="text" class="form-control" name="city" id="city" placeholder="City">
								<span class="label label-danger" id="add_city_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="hindi_city">Hindi City </label>
								<input type="text" class="form-control convertHindi" name="hindi_city" id="hindi_city" placeholder="Hindi City">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="phone">Phone *</label>
								<input type="text" class="form-control" name="phone" id="phone" placeholder="Phone">
								<span class="label label-danger" id="add_phone_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="email">Email </label>
								<input type="text" class="form-control" name="email" id="email" placeholder="Email">
								<span class="label label-danger" id="add_email_error" style="display: none;"></span>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="gst_no">GST Number *</label>
								<input type="text" class="form-control" name="gst_no" id="gst_no" placeholder="GST Number">
								<span class="label label-danger" id="add_gst_no_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="pan_number">PAN Number *</label>
								<input type="text" class="form-control" name="pan_number" id="pan_number" placeholder="PAN Number">
								<span class="label label-danger" id="add_pan_no_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="bank_name">Bank Name *</label>
								<input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Bank Name">
								<span class="label label-danger" id="add_bank_name_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="bank_branch_name">Bank Branch *</label>
								<input type="text" class="form-control" name="bank_branch_name" id="bank_branch_name" placeholder="Bank Branch">
								<span class="label label-danger" id="add_bank_branch_name_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="acc_number">Account Number *</label>
								<input type="text" class="form-control" name="acc_number" id="acc_number" placeholder="Account Number">
								<span class="label label-danger" id="add_acc_number_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="bank_ifs_code">Bank IFS Code *</label>
								<input type="text" class="form-control" name="bank_ifs_code" id="bank_ifs_code" placeholder="Bank IFS Code">
								<span class="label label-danger" id="add_bank_ifs_code_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="gst_no">is Rate Mandatory? </label>
								<input type="checkbox"  name="is_rate_mandatory" id="is_rate_mandatory" >
								<span class="label label-danger" id="add_is_rate_mandatory_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="for_invoice">Using For Invoice? </label>
								<input type="checkbox"  name="for_invoice" id="for_invoice" >
								<span class="label label-danger" id="add_for_invoice_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="token_abbreviation">Token Abbreviation* </label>
								<input type="text" class="form-control" name="token_abbreviation" id="token_abbreviation" >
								<span class="label label-danger" id="add_token_abbreviation_error" style="display: none;"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="addCompanyBtn" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Add Company Modal -->

<!-- Edit Company Modal -->
<div class="modal fade" id="editCompanyModal">
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
<!-- Edit Company Modal -->
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
				$('#addCompanyBtn').click(function(e){
					e.preventDefault();
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					});
					$.ajax({
						url: $('#addCompanyForm').attr('action'),
						method: 'POST',
						data: $('#addCompanyForm').serialize(),
						success: function(data){
							if(!data.flag){
								showError('add_name_error',data.errors.name);
								showError('add_address1_error',data.errors.address1);
								showError('add_city_error',data.errors.city);
								showError('add_phone_error',data.errors.phone);
								showError('add_gst_no_error',data.errors.gst_no);
								showError('add_pan_no_error',data.errors.pan_number);
								showError('add_bank_name_error',data.errors.bank_name);
								showError('add_bank_branch_name_error',data.errors.bank_branch_name);
								showError('add_acc_number_error',data.errors.acc_number);
								showError('add_bank_ifs_code_error',data.errors.bank_ifs_code);
								showError('add_is_rate_mandatory_error',data.errors.is_rate_mandatory);
								showError('add_for_invoice_error',data.errors.for_invoice);
								showError('add_token_abbreviation_error',data.errors.token_abbreviation);
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
					swal('Error','Company id is missing','warning');
				}else{
					$.ajax({
						url: "{{url('/user/edit-company/')}}"+"/"+id,
						type: 'GET',
						success:function(data){
							$('#EditBody').html(data);
							$('#editCompanyModal').modal('toggle');
							OnLoad();
						}
					});
				}
			}

			function updateCompany(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					}
				});
				$.ajax({
					url: $('#editCompanyForm').attr('action'),
					method: 'POST',
					data: $('#editCompanyForm').serialize(),
					success: function(data){
						console.log(data);
						if(!data.flag){
							showError('edit_name_error',data.errors.name);
							showError('edit_address1_error',data.errors.address1);
							showError('edit_city_error',data.errors.city);
							showError('edit_phone_error',data.errors.phone);
							showError('edit_pan_no_error',data.errors.pan_number);
							showError('edit_bank_name_error',data.errors.bank_name);
							showError('edit_bank_branch_name_error',data.errors.bank_branch_name);
							showError('edit_acc_number_error',data.errors.acc_number);
							showError('edit_bank_ifs_code_error',data.errors.bank_ifs_code);
							showError('edit_gst_no_error',data.errors.gst_no);
							showError('edit_is_rate_mandatory_error',data.errors.is_rate_mandatory);
							showError('edit_for_invoice_error_error',data.errors.for_invoice_error);
							showError('edit_token_abbreviation_error',data.errors.token_abbreviation);

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

			function deleteCompany(id){
				if(id == ""){
					swal('Error','Company id is missing','warning');
				}else{
					swal({
						title: "Are you sure?",
						text: "You will not be able to recover this company!",
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
								url: "{{url('/user/delete-company/')}}"+"/"+id,
								type: 'GET',
								success:function(data){
									if(data.flag){
										$('#tr_'+id).remove();
										swal("Success", "Company Deleted Successfully", "success");
									}else{
										swal("Error", data.message, "error");
									}
								}
							});
						} else {
							swal("Cancelled", "Your company is safe :)", "error");
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
