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
				<li class="active"> Party Invoice Ledger</li>
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
					<h3 class="header smaller lighter blue"> Party Invoice Ledger</h3>

					<form action="" method="POST" role="form" id="FilterForm">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="retailer_id"> Retailer</label>
									<select class="form-control select2" name="retailer_id" id="retailer_id" onchange="filter()">
										<option value=""> Select Retailer</option>
										@foreach($retailers as $retailer)
										<option value="{{$retailer['id']}}" {{isset($retailer_id) && $retailer_id==$retailer['id'] ? "selected":""}}>{{$retailer['name']}} ({{$retailer['address']}})</option>
										@endforeach()
									</select>
									@if ($errors->has('retailer_id'))
									<span class="label label-danger">{{ $errors->first('retailer_id') }}</span>
									@endif
								</div>
							</div>
							

						</form>


					</div>
				</div>


				<div class="clearfix">
					<div class="pull-right tableTools-container">
					</div>
				</div>
				<div class="table-header">
					Party Invoice Ledger
				</div>
				@isset($ledgers)
				<div class="table-responsive">
					<div class="dataTables_borderWrap">
						<table  class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Retailer</th>
									<th>Particular</th>
									<th>Against</th>
									<th>credit</th>
									<th>Debit</th>
									<th>Balance</th>
									<th>Date</th>
								</tr>
							</thead>

							<tbody>
								@foreach($ledgers as $ledger)
								<tr>
									<th>{{getModelById('Retailer',$ledger->retailer_id)->name}} ({{getModelById('Retailer',$ledger->retailer_id)->address}})</th>
									<th>{{$ledger->particular}}</th>
									<th>{{$ledger->against}}</th>
									<th>{{$ledger->credit}}</th>
									<th>{{$ledger->debit}}</th>
									<th>{{$ledger->balance}}</th>
									<th>{{date('d/m/Y',strtotime($ledger->created_at))}}</th>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				@endif

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
