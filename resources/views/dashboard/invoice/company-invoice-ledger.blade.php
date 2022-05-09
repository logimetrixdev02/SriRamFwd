@extends('dashboard.layouts.app')
@section('title','Company Invoice Ledger')
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
				<li class="active"> Company Ledger</li>
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
					<h3 class="header smaller lighter blue"> Company Ledger</h3>

					<form action="" method="POST" role="form" id="FilterForm">
						<div class="row">
							{{csrf_field()}}
							<div class="col-md-3">
								<div class="form-group">
									<label for="product_company_id"> Poduct Company</label>
									<select class="form-control select2" name="product_company_id" id="product_company_id" onchange="filter()">
										<option value=""> Select Poduct Company</option>
										@foreach($product_companies as $product_company)
										<option value="{{$product_company['id']}}" {{isset($product_company_id) && $product_company_id==$product_company['id'] ? "selected":""}}>{{$product_company['name']}}</option>
										@endforeach()
									</select>
									@if ($errors->has('product_company_id'))
									<span class="label label-danger">{{ $errors->first('product_company_id') }}</span>
									@endif
								</div>
							</div>


							<div class="col-md-4">
								<div class="form-group">
									<label for="dealer_id"> Dealer</label>
									<select class="form-control select2" name="dealer_id" id="dealer_id" onchange="filter()">
										<option value=""> Select Dealer</option>
										@foreach($dealers as $dealer)
										<option value="{{$dealer['id']}}" {{isset($dealer_id) && $dealer_id==$dealer['id'] ? "selected":""}}>{{$dealer['name']}}({{$dealer['address']}})</option>
										@endforeach()
									</select>
									@if ($errors->has('dealer_id'))
									<span class="label label-danger">{{ $errors->first('dealer_id') }}</span>
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


							<div class="col-md-2">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">Submit</button>
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
								<h5 class="widget-title smaller">Company Information</h5>
							</div>
							<div class="widget-body">
								<div style="padding: 5px;">
									<h4>{{$company_info->name}}</h4>
									<p>{{$company_info->address}}</p>
								</div>
								
							</div>
						</div>
					</div>
				</div>

				<div class="clearfix">
					<div class="pull-right tableTools-container">
					</div>
				</div>
				<div class="table-header">
					Company Ledger
				</div>
				
				
				<div class="table-responsive">
					<div class="dataTables_borderWrap">
						<table  class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Date</th>
									<th>Particular</th>
									<th>Vch Type</th>
									<th>Vch No</th>
									<th>Debit</th>
									<th>Credit</th>
									<th>Balance</th>
								</tr>
							</thead>

							<tbody>
								@foreach($ledgers as $ledger)
								<tr>
									<td>{{date('d/m/Y',strtotime($ledger->created_at))}}</td>
									<td>{{$ledger->particular}}</td>
									<td>{{$ledger->type}}</td>
									<td>{{$ledger->voucher_no}}</td>
									<td>{{$ledger->debit}}</td>
									<td>{{$ledger->credit}}</td>
									<td>{{$ledger->balance}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
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
