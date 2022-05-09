@extends('dashboard.layouts.app')
@section('title','Party Invoice Ledger')
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
				<li class="active"> Party Ledger</li>
			</ul>
		</div>

		<div class="page-content">


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
			
			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue"> Party Ledger</h3>

					<form action="" method="POST" role="form" id="FilterForm">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="retailer_id"> Retailer</label>
									<select class="form-control select2" name="retailer_id" id="retailer_id">
										<option value=""> Select Retailer</option>
										@foreach($retailers as $retailer)
										<option value="{{$retailer['id']}}" {{isset($retailer_id) && $retailer_id==$retailer->id ? "selected":""}}>{{$retailer->name}} ({{$retailer->address}})</option>
										@endforeach
									</select>
									@if ($errors->has('retailer_id'))
									<span class="label label-danger">{{ $errors->first('retailer_id') }}</span>
									@endif
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="retailer_id"> Session  </label>
									<select class="form-control select2" name="current_session" id="current_session" >
										<option value=""> Select Session </option>
										@foreach($allsessions as $mysession)
										<option value="{{$mysession->session}}" {{isset($current_session) && $current_session==$mysession->session ? "selected":""}}>{{$mysession->session}}</option>
										@endforeach
									</select>
									
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<input type="submit" name="submit" class="btn btn-success" />
								</div>
							</div>
						</form>


					</div>
				</div>
			@isset($ledgers)
			<div class="row">
				<div class="col-xs-6">
					<div class="widget-box ui-sortable-handle" id="widget-box-5">
						<div class="widget-header">
							<h5 class="widget-title smaller">Retailer Information</h5>
						</div>
						<div class="widget-body">
							<div style="padding: 5px;">
								<h4>{{$current_retailer->name}}</h4>
								<p>{{$current_retailer->address}}</p>
							</div>
							
						</div>
					</div>
				</div>
			</div>

				<div class="clearfix">
					<div class="pull-right tableTools-container">
						<a class="dt-button buttons-print btn btn-white btn-primary btn-bold" tabindex="0" aria-controls="dynamic-table"><span><i class="fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span></span></a>
					</div>
				</div>
				<div class="table-header">
					Party Ledger
				</div>
				
				<div class="table-responsive">
					<div class="dataTables_borderWrap">
						<table  class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Date</th>
									<th>Particular</th>
									<th>voucher Type</th>
									<th>voucher No</th>
									<th width="100">Debit</th>
									<th width="100">Credit</th>
									
									<th width="100">Balance</th>
									
								</tr>
							</thead>

							<tbody>
								<tr>
									<td>{{date('d/m/Y',strtotime($start))}}</td>
									<td colspan="5">Opening Balance</td>
									<td>@if($opening_balance < 0) {{abs($opening_balance)}} Cr @else {{$opening_balance}} Dr @endif</td>
								</tr>
								@foreach($ledgers as $ledger)
								<tr>
									<td>{{date('d/m/Y',strtotime($ledger->created_at))}}</td>
									<td>{{$ledger->particular}}</td>
									<td>{{$ledger->type}}</td>
									<td>{{$ledger->against}}</td>
									
									<td width="100">@if($ledger->debit != 0){{$ledger->debit}} @endif</td>
									<td width="100">@if($ledger->credit != 0){{$ledger->credit}} @endif</td>
									<td width="100">@if($ledger->balance < 0) {{abs($ledger->balance)}} Cr @else {{$ledger->balance}} Dr @endif</td>
									
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			@endif	
				<div class="row">
					<div class="col-xs-12">
						<br>
						<br>
						<br>
						<br>
						<br>
					</div>
				</div>
			</div>
			<!-- /.page-content -->
		</div>
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


		jQuery(function($) {

			$('.date-picker').datepicker({
				autoclose: true,
				todayHighlight: true
			})
			.next().on(ace.click_event, function(){
				$(this).prev().focus();
			});

		})


	</script>
	<script type="text/javascript">
		function filter(){
			$('#invoice_id').val('');
			$('#FilterForm').submit();
		}
	</script>
	@endsection
	@endsection
