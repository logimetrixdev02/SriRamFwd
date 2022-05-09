@extends('dashboard.layouts.app')
@section('title','Daily Expense Reports')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">Daily Expense Reports</li>
			</ul>
		</div>

		<div class="page-content">
			<h3 class="header smaller lighter blue">Daily Expense Reports  <div id="google_translate_element"></div></h3>

			<div class="row">
				<div class="col-xs-12">
					<form action="" method="POST" role="form">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="month">Month</label>
									<select class="form-control select2" name="month" id="month">
										<option value="">Select Month</option>
										<option value="01" {{isset($month) && $month=="01" ? "selected":""}}>January</option>
										<option value="02" {{isset($month) && $month=="02" ? "selected":""}}>February</option>
										<option value="03" {{isset($month) && $month=="03" ? "selected":""}}>March</option>
										<option value="04" {{isset($month) && $month=="04" ? "selected":""}}>April</option>
										<option value="05" {{isset($month) && $month=="05" ? "selected":""}}>May</option>
										<option value="06" {{isset($month) && $month=="06" ? "selected":""}}>June</option>
										<option value="07" {{isset($month) && $month=="07" ? "selected":""}}>July</option>
										<option value="08" {{isset($month) && $month=="08" ? "selected":""}}>August</option>
										<option value="09" {{isset($month) && $month=="09" ? "selected":""}}>September</option>
										<option value="10" {{isset($month) && $month=="10" ? "selected":""}}>October</option>
										<option value="11" {{isset($month) && $month=="11" ? "selected":""}}>November</option>
										<option value="12" {{isset($month) && $month=="12" ? "selected":""}}>December</option>
									</select>
									
								</div>
							</div>
							

							<div class="col-md-3">
								<div class="form-group">
									<label for="year">Year</label>
									<select class="form-control select2" name="year" id="year">
										<option value="">Select Year</option>
										<option value="2019" {{isset($year) && $year=="2019" ? "selected":""}}>2019</option>
										<option value="2020" {{isset($year) && $year=="2020" ? "selected":""}}>2020</option>
										<option value="2021" {{isset($year) && $year=="2021" ? "selected":""}}>2021</option>
										<option value="2022" {{isset($year) && $year=="2022" ? "selected":""}}>2022</option>
										
									</select>
									
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
						Results for Latest Generated Daily Expense Reports
						

					</div>

					<div class="table-responsive">

						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Generated At</th>
										<th>Generated By</th>
										<th>First Approval</th>
										<th>First Approval By</th>
										<th>Second Approval</th>
										<th>Second Approval By</th>
										<th>Third Approval</th>
										<th>Third Approval By</th>
										<th>Final Approval</th>
										<th>Generated Count</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@foreach($daily_expense_reports as $daily_expense_report)
									<tr>
										<td>{{date('d/m/Y H:i:s',strtotime($daily_expense_report->generated_at))}}</td>
										<td>{{getModelById('User',$daily_expense_report->generated_by)->name}}</td>
										<td>
											@if($daily_expense_report->first_approval == 1)
											<span class="label label-success">Approved</span>
											@else
											<span class="label label-warning">Pending</span>
											@endif
										</td>

										<td>
											@if(!is_null($daily_expense_report->first_approval_by))
											{{getModelById('User',$daily_expense_report->first_approval_by)->name}}
											@else
											--
											@endif
										</td>

										<td>
											@if($daily_expense_report->second_approval == 1)
											<span class="label label-success">Approved</span>
											@else
											<span class="label label-warning">Pending</span>
											@endif
										</td>

										<td>
											@if(!is_null($daily_expense_report->second_approval_by))
											{{getModelById('User',$daily_expense_report->second_approval_by)->name}}
											@else
											--
											@endif
										</td>

										<td>
											@if($daily_expense_report->third_approval == 1)
											<span class="label label-success">Approved</span>
											@else
											<span class="label label-warning">Pending</span>
											@endif
										</td>

										<td>
											@if(!is_null($daily_expense_report->third_approval_by))
											{{getModelById('User',$daily_expense_report->third_approval_by)->name}}
											@else
											--
											@endif
										</td>

										<td>{{$daily_expense_report->final_approval}}</td>
										<td>{{$daily_expense_report->generated_count}}</td>

										<td>
											<div class="hidden-sm hidden-xs btn-group">

												<a class="btn btn-xs btn-success" href="{{URL('/user/daily-warehouse-payments-detail',$daily_expense_report->id)}}" target="_blank" title="Details">
													<i class="ace-icon fa fa-eye bigger-120"></i>
												</a>

												@if($daily_expense_report->first_approval == 1 || $daily_expense_report->second_approval == 1 || $daily_expense_report->third_approval == 1)

												@if($daily_expense_report->first_approval_by != \Auth::user()->id || $daily_expense_report->second_approval_by != \Auth::user()->id || $daily_expense_report->third_approval_by != \Auth::user()->id)
												<a class="btn btn-xs btn-info" onclick="approve({{$daily_expense_report->id}})" title="Approve">
													<i class="ace-icon fa fa-thumbs-up bigger-120"></i>
												</a>
												@endif
												<a class="btn btn-xs btn-danger" onclick="decline({{$daily_expense_report->id}})" title="Reject">
													<i class="ace-icon fa fa-thumbs-down bigger-120"></i>
												</a>

												@else
												<a class="btn btn-xs btn-info" onclick="approve({{$daily_expense_report->id}})" title="Approve">
													<i class="ace-icon fa fa-thumbs-up bigger-120"></i>
												</a>
												@endif


												<a class="btn btn-xs btn-info" onclick="getRejections({{$daily_expense_report->id}})" title="Get Rejections">
													<i class="ace-icon fa fa-list bigger-120"></i>
												</a>

											</div>
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

<div class="modal fade" id="declineModal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Reject Report</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" value="" id="report_id">
				<textarea name="" id="reason" cols="30" rows="10"></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" onclick="declineWithReason()" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="rejectionsModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Report Rejections </h4>
			</div>
			<div class="modal-body" id="rejectionsBody">
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade in" id="viewModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Details </h4>
			</div>
			<div class="modal-body"><table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>Perticular</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody id="details_tr">
				</tbody>
			</table></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

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
		autoPrint: true,
		message: 'IManager',
		exportOptions: {
			columns: ':visible'
		}
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
	function approve(id){
		if(id == ""){
			swal('Error','id is missing','warning');
		}else{
			swal({
				title: "Are you sure?",
				text: "",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'Yes, I am sure!',
				cancelButtonText: "No, approve it!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm){
				if (isConfirm){
					$('.loading-bg').show();
					$.ajax({
						url: "{{url('/user/approve-daily-payments-report/')}}"+"/"+id,
						type: 'GET',
						success:function(data){
							$('.loading-bg').hide();
							if(data.flag){
								swal("Success", "Approved Successfully", "success");
								window.location.reload();
							}else{
								swal("Error", data.message, "error");
							}
						}
					});
				} else {
					swal("Cancelled", "Your Decision is safe :)", "error");
				}
			});
		}
	}

	function decline(id){
		$('#report_id').val(id);
		$('#declineModal').modal('toggle');
	}


	function declineWithReason(){
		if($('#reason').val() == ""){
			swal("Error", "Enter reason", "error");
		}else{
			swal({
				title: "Are you sure?",
				text: "",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'Yes, I am sure!',
				cancelButtonText: "No, Reject it!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm){
				if (isConfirm){
					$.ajaxSetup({
						headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
					});

					$.ajax({
						url: "{{url('/user/reject-daily-payments-report/')}}",
						type: 'POST',
						data: {'reason':$('#reason').val(),'id':$('#report_id').val()},
						success:function(data){
							if(data.flag){
								swal("Success", "Rejected Successfully", "success");
								window.location.reload();
							}else{
								swal("Error", data.message, "error");
							}
						}
					});
				} else {
					swal("Cancelled", "Your Decision is safe :)", "error");
				}
			});
		}
		
		
	}

	function getRejections(id){
		$('.loading-bg').show();
		$.ajax({
			url: "{{url('/user/daily-warehouse-payments-report-rejections/')}}"+"/"+id,
			type: 'GET',
			success:function(data){
				$('.loading-bg').hide();
				$('#rejectionsModal').modal('toggle');
				$('#rejectionsBody').html(data);
			}
		});
	}


	function details(json){
		json = JSON.parse(json);
		var html = '';
		$.each(json, function(index, val) {
			html += `<tr><td>`+index+`</td><td>`+val+`</td></tr>`
		});

		$('#details_tr').html(html);
		$('#viewModal').modal('toggle');
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