@extends('dashboard.layouts.app')
@section('title','Dashboard')
@section('style')

@endsection
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Dashboard</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12">
					<div class="widget-box">
						<div class="widget-header">
							<h4 class="widget-title">{{__('messages.Token')}} - {{__('messages.ProductLoading')}}</h4>

							<div class="widget-toolbar">
								<a href="#" data-action="collapse">
									<i class="ace-icon fa fa-chevron-up"></i>
								</a>

								<a href="#" data-action="close">
									<i class="ace-icon fa fa-times"></i>
								</a>
							</div>
						</div>

						<div class="widget-body">
							<div class="widget-main">
								<div>
									<label for="master_rake_id">{{__('messages.Rake')}}</label>

									<select class="form-control select2" name="master_rake_id" id="master_rake_id" onchange="getTotalTokens(this.value)">
										<option value="">{{__('messages.Rake')}} {{__('messages.Select')}}</option>
										@foreach($master_rakes as $master_rake)
										<option value="{{$master_rake->id}}" {{isset($master_rake_id) && $master_rake_id==$master_rake->id ? "selected":""}}>{{$master_rake->name}}</option>
										@endforeach()
									</select>

								</div>
								<div style="margin-top: 15px;">
									<div class="row totals">
										<div class="col-md-6 col-sm-12 col-xs-12">
											<div class="table-responsive">
												<table class="table table-hover" id="token_table">
													
												</tbody>
											</table>
										</div>
									</div>


									<div class="col-md-6 col-sm-12 col-xs-12">
										<div class="table-responsive">
											<table class="table table-hover" id="loading_table">

											</table>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="tokenModal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Tokans</h4>
						</div>
						<div class="modal-body" style="height: 500px !important; overflow-y: scroll" >
							<div class="table-responsive">
								<table class="table table-hover datatable" style="height: 200px !important;overflow-y: scroll;">
									<thead>
										<tr>
											<th>#</th>
											<th>Retailer</th>
											<th>Dealer</th>
											<th>Rate</th>
											<th>Qty</th>
											<th>Transporter</th>
										</tr>
									</thead>
									<tbody id="tokenModalBody">

									</tbody>
								</table>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>


			<div class="modal fade" id="loadingModal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Product Loadings</h4>
						</div>
						<div class="modal-body" style="height: 500px !important; overflow-y: scroll" >
							<div class="table-responsive">
								<table class="table table-hover datatable" style="height: 200px !important;overflow-y: scroll;">
									<thead>
										<tr>
											<th>#</th>
											<th>Retailer</th>
											<th>Qty</th>
											<th>Transporter</th>
											<th>Driver <br> Number</th>
										</tr>
									</thead>
									<tbody id="loadingModalBody">

									</tbody>
								</table>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div><!-- /.page-content -->
</div>
</div><!-- /.main-content -->
@section('script')

{{ Html::script("assets/js/jquery.dataTables.min.js")}}
{{ Html::script("assets/js/jquery.dataTables.bootstrap.min.js")}}
{{ Html::script("assets/js/dataTables.buttons.min.js")}}
{{ Html::script("assets/js/ace-elements.min.js")}}
{{ Html::script("assets/js/ace.min.js")}}

<script type="text/javascript">

	$(document).ready(function() {
	});
	function getTotalTokens(rake_id){
		if(rake_id != ""){
			$('.loading-bg').show();
			$.ajaxSetup({
				headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
			});
			var url  = "{{URL('/api/rake-total-tokens-loadings')}}";
			var api_token = "{{Auth::user()->api_token}}";
			$.ajax({
				url: url,
				type: 'POST',
				data: {'master_rake_id':rake_id,'api_token':api_token},
				success: function (data) {
					$('.loading-bg').hide();

					var products = "";

					$.each(data.products, function(index, val) {
						console.log(val);
						products+= `<th>`+val+`</th>`
					});

					var token_html = `<tr>
					<th>Total Tokens Quantity</th>
					`+products+`
					</tr>`;
					

					var loading_html = `<tr>
					<th>Total Loadings Quantity</th>
					`+products+`
					</tr>`;
					$.each(data.companies, function(index, val) {
						var token_product_counts = "";
						var loading_product_counts = "";
						$.each(val.count_data, function(index1,val1 ) {
							token_product_counts += `<td><a href="javascript:;" onclick="getTokens(`+rake_id+`)"><span class="badge">`+val1.total_token_quantity+`</span></a></td>
							`;
							loading_product_counts += `<td><a href="javascript:;" onclick="getLoadings(`+rake_id+`)"><span class="badge">`+val1.total_loading_quantity+`</span></a></td>
							`;
						});

						token_html += `<tr>
						<td>`+val.name+`</td>
						`+token_product_counts+`
						</tr>`;

						loading_html += `
						<tr>
						<td>`+val.name+`</td>
						`+loading_product_counts+`
						</tr>`;
					});

					$('#token_table').html(token_html);
					$('#loading_table').html(loading_html);

				}
			});
		}
	}
	function getTokens(rake_id){
		if(rake_id != ""){
			$('.loading-bg').show();
			$.ajaxSetup({
				headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
			});
			var url  = "{{URL('/api/rake-tokens')}}";
			var api_token = "{{Auth::user()->api_token}}";
			$.ajax({
				url: url,
				type: 'POST',
				data: {'master_rake_id':rake_id,'api_token':api_token},
				success: function (data) {
					$('.loading-bg').hide();
					console.log(data);
					var tokens = "";
					$.each(data.tokens, function(index, val) {
						dealer = "";
						retailer = "";
						rate = "";
						if(val.rate != null ){
							rate = val.rate;
						}
						if(val.retailer != null ){
							retailer = val.retailer.name;
						}
						if(val.dealer != null ){
							dealer = val.dealer.name;
						}
						tokens += `<tr>
						<td>`+val.id+`</td>
						<td>`+retailer+`</td>
						<td>`+dealer+`</td>
						<td>`+rate+`</td>
						<td>`+val.quantity+`</td>
						<td>`+val.transporter.name+`</td>
						</tr>`;
					});
					$('#tokenModalBody').html(tokens);
					$('.datatable').DataTable( {
						bAutoWidth: false,
						"aaSorting": [],
					} );
					$('#tokenModal').modal('toggle');
				}
			});
		}
	}

	function getLoadings(rake_id){
		if(rake_id != ""){
			$('.loading-bg').show();
			$.ajaxSetup({
				headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
			});
			var url  = "{{URL('/api/rake-loadings')}}";
			var api_token = "{{Auth::user()->api_token}}";
			$.ajax({
				url: url,
				type: 'POST',
				data: {'master_rake_id':rake_id,'api_token':api_token},
				success: function (data) {
					$('.loading-bg').hide();
					console.log(data);
					var loadings = "";
					$.each(data.loadings, function(index, val) {
						retailer = "";
						rate = "";
						driver_number = "";

						if(val.retailer_name != null ){
							retailer = val.retailer_name
						}if(val.driver_number != null ){
							driver_number = val.driver_number
						}

						loadings += `<tr>
						<td>`+val.id+`</td>
						<td>`+retailer+`</td>
						<td>`+val.quantity+`</td>
						<td>`+val.transporter_name+`</td>
						<td>`+driver_number+`</td>
						</tr>`;
					});
					$('#loadingModalBody').html(loadings);

					$('.datatable').DataTable( {
						bAutoWidth: false,
						"aaSorting": [],
					} );


					$('#loadingModal').modal('toggle');
				}
			});
		}
	}
</script>

@endsection
@endsection
