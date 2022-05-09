@extends('dashboard.layouts.app')
@section('title','Modules')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Module</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Modules</h3>


					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Results for "Latest Registered Module"
						<div class="widget-toolbar no-border">
							<a class="btn btn-xs bigger btn-danger dropdown-toggle"  data-toggle="modal" href='#addModuleModal'>
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

									<th>Module</th>
									<th></th>
								</tr>
							</thead>

							<tbody>
								@foreach($modules as $module)
								<tr id="tr_{{$module->id}}">
									<td>{{$module->module}}</td>
									<td>
										<div class="hidden-sm hidden-xs btn-group">
											<a class="btn btn-xs btn-info" onclick="getEdit({{$module->id}})" >
												<i class="ace-icon fa fa-pencil bigger-120"></i>
											</a>

											<button class="btn btn-xs btn-danger" onclick="deleteModule({{$module->id}})" >
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


<!-- Add Module Modal -->
<div class="modal fade" id="addModuleModal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Module</h4>
			</div>
			<div class="modal-body">

				<form action="" role="form" id="addModuleForm">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="name">Module</label>
								<input type="text" class="form-control" name="module" id="module" placeholder="Module">
								<span class="label label-danger" id="add_module_error" style="display: none;"></span>
							</div>
						</div>


						<div class="col-md-12">
							<div class="form-group">
								<label for="link">Link</label>
								<input type="text" class="form-control" name="link" id="link" placeholder="Link">
								<span class="label label-danger" id="add_link_error" style="display: none;"></span>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label for="icon">Icon</label>
								<input type="text" class="form-control" name="icon" id="icon" placeholder="Sub Module">
								<span class="label label-danger" id="add_icon_error" style="display: none;"></span>
							</div>
						</div>

						

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="addModuleBtn" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Add Module Modal -->


<!-- Edit Module Modal -->

<div class="modal fade" id="editModuleModal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Update Module</h4>
			</div>
			<div class="modal-body" id="EditBody">


			</div>
		</div>
	</div>
</div>

<!-- Edit Module Modal -->
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
		$('#addModuleBtn').click(function(e){
			e.preventDefault();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			});
			$.ajax({
				url: $('#addModuleForm').attr('action'),
				method: 'POST',
				data: $('#addModuleForm').serialize(),
				success: function(data){
					if(!data.flag){
						showError('add_module_error',data.errors.module);
						showError('add_link_error',data.errors.link);
						showError('add_icon_error',data.errors.icon);
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
			swal('Error','Module id is missing','warning');
		}else{
			$.ajax({
				url: "{{url('/user/edit-module/')}}"+"/"+id,
				type: 'GET',
				success:function(data){
					$('#EditBody').html(data);
					$('#editModuleModal').modal('toggle');
				}
			});
		}
	}

	function updateModule(){
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
			}
		});
		$.ajax({
			url: $('#editModuleForm').attr('action'),
			method: 'POST',
			data: $('#editModuleForm').serialize(),
			success: function(data){
				console.log(data);
				if(!data.flag){
					showError('edit_module_error',data.errors.module);
					showError('edit_link_error',data.errors.link);
					showError('edit_icon_error',data.errors.icon);
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

	function deleteModule(id){
		if(id == ""){
			swal('Error','Module id is missing','warning');
		}else{
			swal({
				title: "Are you sure?",
				text: "You will not be able to recover this Module!",
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
						url: "{{url('/user/delete-module/')}}"+"/"+id,
						type: 'GET',
						success:function(data){
							if(data.flag){
								$('#tr_'+id).remove();
								swal("Success", "Module Deleted Successfully", "success");
							}else{
								swal("Error", data.message, "error");
							}
						}
					});
				} else {
					swal("Cancelled", "Your Module is safe :)", "error");
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
