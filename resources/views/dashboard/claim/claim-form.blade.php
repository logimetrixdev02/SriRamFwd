@extends('dashboard.layouts.app')
@section('title','Claims')
@section('style')
{{Html::style("assets/css/bootstrap-datepicker3.min.css")}}
@endsection

@section('content')
<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">Claims</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="page-header">
				<h1>
					Claims
				</h1>
			</div><!-- /.page-header -->

			<form action="" role="form" id="AddClaimForm">
				<div class="container">
					<div class="row">

						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">Claim Form</h3>
							</div>
							<div class="panel-body">
								<div class="col-md-4">
									<div class="form-group">
										<label for="company"> Product Company</label>
										<select class="form-control checkIfValid" name="company" id="company">
											<option value="">Select Company</option>
											@foreach($companies as $company)
											<option value="{{$company->id}}">{{$company->name}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_company_error" style="display: none;"></span>
									</div>
								</div>

								<div class="clearfix"></div>
								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label for="head">Head</label>
											<input type="text"  class="form-control checkIfValid" name="head[]" id="head" >
											<span class="label label-danger" id="add_head_error" style="display: none;"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="rate">Rate (in M.T)</label>
											<input type="text" class="form-control checkIfValid" name="rate[]"  placeholder="rate">
											<span class="label label-danger" id="add_rate_error" style="display: none;"></span>
										</div>
									</div>

								</div>	
								<div id="addMoreRowSection">

								</div>
								<div class="pull-left">
									<button type="button" id="addMoreRow" class="btn btn-danger"><i class="fa fa-plus"></i> Add More </button>
								</div>
								
								<div class="pull-right">
									<a href="{{URL('/user/claims')}}" class="btn btn-default" >Reset</a>
									<button type="button" id="addClaimBtn" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</div>


					</div>
				</div>
			</form>
		</div>
	</div><!-- /.page-content -->
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
{{ Html::script("assets/js/bootstrap-datepicker.min.js")}}
<script type="text/javascript">
	$('#addMoreRow').click(function(){
		var count = $('.row').length + 1;
		var newRow = `<div class="row" id="newRow`+count+`">
		<div class="col-md-4">
		<div class="form-group">
		<label for="head">Head</label>
		<input type="text"  class="form-control select2 checkIfValid" name="head[]" id="head">
		<span class="label label-danger" id="add_head_error" style="display: none;"></span>
		</div>
		</div>
		<div class="col-md-4">
		<div class="form-group">
		<label for="rate">Rate</label>
		<input type="text" class="form-control checkIfValid" name="rate[]"  placeholder="Rate">
		<span class="label label-danger" id="add_rate_error" style="display: none;"></span>
		</div>
		</div>
		<div class="col-md-1" style="margin-top: 33px;">
		<a href="javascript:;" onclick="removeRow(`+count+`)"><i class="fa fa-close fa-2x"></i></a>
		</div>
		</div>
		`;
		$('#addMoreRowSection').append(newRow);
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

	function showError(id,error){
		if(typeof(error) === "undefined"){
			$('#'+id).hide();
		}else{
			$('#'+id).show();
			$('#'+id).text(error);
		}
	};

	$(document).ready(function(){

				// $('.select2').select2();

				$('#addClaimBtn').click(function(e){
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
						formData.append('company_id', $('#company option:selected').val());
						var head = [];
						$("input[name='head[]']").each(function() {
							head.push($(this).val());
						});

						var rate = [];
						$("input[name='rate[]']").each(function() {
							rate.push($(this).val());
						});

						formData.append('head',  head) ;
						formData.append('rate',  rate) ;
						$.ajax({
							url: $('#addClaimBtn').attr('action'),
							method: 'POST',
							data: formData,
							contentType: false,
							cache: false,
							processData:false,
							success: function(data){
								$('.loading-bg').hide();
								if(!data.flag){
									showError('add_company_error',data.errors.company_id);
									showError('add_head_error',data.errors.head);
									showError('add_rate_error',data.errors.rate);
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
		</script>
		@endsection
		@endsection