@extends('dashboard.layouts.app')
@section('title','Master Rakes')

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
					<a href="#">Home</a>
				</li>
				<li class="active">Stock Transfer Warehouse</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Stock Transfer Warehouse</h3>

					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Results for "Latest Registered Master Rakes"
						<div class="widget-toolbar no-border">
							<a class="btn btn-xs bigger btn-danger dropdown-toggle"  data-toggle="modal" href='#stockWarehouseTransferModal'>
								Add
								<i class="ace-icon fa fa-plus icon-on-right"></i>
							</a>
						</div>
					</div>

					<!-- <div class="table-responsive">
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
										<th></th>
									</tr>
								</thead>

								<tbody>
								
								</tbody>
							</table>
						</div>
					</div> -->
				</div>
			</div>
		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->
<!-- Add Master Rake Modal -->
<div class="modal fade" id="stockWarehouseTransferModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Stock Transfer Warehouse</h4>
			</div>
			<div class="modal-body">

				<form action=""  role="form" id="stocktransferwarehouse" method="POST">
					<div class="row">
						<div class="clearfix"></div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="rake_point">Rake Point</label>
								<select class="form-control select2" name="rake_point" id="rake_point" onchange="get_product_qty(this, 'rake_point')">
								<option value="">Select Rake Point</option>
								@foreach($rake_points as $rake_point)
									<option value="{{$rake_point->id}}">{{$rake_point->rake_point}}</option>
								@endforeach()
						
					</select>
								<span class="label label-danger" id="add_rake_point_error" style="display: none;"></span>
							</div>
						</div>

                        <div class="col-md-5">
							<div class="form-group">
								<label for="name">Warehouse</label>
								<select class="form-control select2" name="warehouse_id" id="warehouse_id">
									<option value="">Select Warehouse</option>
                                    @foreach($warehouse as $warehouses)
									<option value="{{$warehouses->id}}">{{$warehouses->name}}</option>
									@endforeach()
								</select>
								<span class="label label-danger" id="add_product_company_error" style="display: none;"></span>
							</div>
						</div>
						<div class="clearfix"></div>

					</div>
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
							<label for="product_id">{{__('messages.Product')}}</label>
							<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th> Product Name</th> 
									<th> Quantity</th>
									<th> Fresh Quantity</th>
									<th> Damage Quantity</th>
								</tr>
							</thead> 
							<tbody id="tbody">

							</tbody>
						</table> 
								<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
							</div>
						</div>
						<!-- <div class="col-md-3">
							<div class="form-group">
								<label for="rate">Fresh Quantity</label>
								<input type="text" class="form-control checkIfValid" name="fresh_qty"  placeholder="Quantity">
								<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
							</div>
						</div>
                        <div class="col-md-2">
                        <div class="form-group">
								<label for="wharfage">Damage Qty</label>
								<input type="text" class="form-control" name="damage_qty" id="Damage" placeholder="Damage">
								<span class="label label-danger" id="add_wharfage_error" style="display: none;"></span>
							</div>
						</div> -->
					</div>	
					<!-- <div id="addMoreRowSection">

					</div>

					<div class="pull-left">
						<button type="button" id="addMoreRow" class="btn btn-danger"><i class="fa fa-plus"></i> Add More </button>

					</div> -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="stocktransferwarehouseBtn" class="btn btn-primary">Submit</button>
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
// $('.select2').select2();

	function get_product_qty(t, name) {
		//alert();
		var warehouse_id = $(t).val();
		var data = {};
		data.warehouse_id = warehouse_id;
		data.name = name;
		data._token = '{{csrf_token()}}';
		var url = window.location.origin+'/stock/get-product-qty';
		$.ajax({
			url: url,
			type:'get',
			data: data,
			dataType:'json',
			success:function(response){		
				//console.log(response);	
				if(response.success == true) {
					html = '';
					// $('#product_id').html();
					$("#tbody").html('');
					$.each(response.product_qtys, function(key, product){
						console.log(product);
						//html += `<option value="`+product['product_id']+`">`+product['product']['name']+` - `+product['damage_qty']+` `+product['unit']['unit']+`</option>`;		
						html += `<tr id="product_`+product.product_id+`">
          					<td><input type="text" name="product_id[]" value="${product.product_id}"><input type="text" id="input_`+product.product_id+`" onkeypress="return onlyCurrency(event)"  onblur="check_zero(this)" data-id="id_`+product.product_id+`_`+product.product_id+`" name="product_name[`+product.product_id+`]" onkeyup="getvalue(this,`+product.product_id+`)" value="`+product.product.name+`" style="width:100px;" /> </td>
							<td>`+product.quantity+`</td>
          					<td><input type="text" id="input_`+product.product_id+`" onkeypress="return onlyCurrency(event)"  onblur="check_zero(this)" data-id="id_`+product.product_id+`_`+product.product_id+`" name="fresh_qty[`+product.product_id+`]"/></td>
							  <td><input type="text" id="input_`+product.product_id+`" onkeypress="return onlyCurrency(event)"  onblur="check_zero(this)" data-id="id_`+product.product_id+`_`+product.product_id+`" name="demage_qty[`+product.product_id+`]"/></td>
          				</tr>`;
					});	
					$('#tbody').append(html);							
					//$('.product_id').html(html);
					// html = '';
					// // $('#product_id').html();
					// $.each(response.product_qtys, function(key, product){
					// 	//console.log(product.product_id);
					// 	//html += `<option value="`+product['product_id']+`">`+product['product']['name']+` - `+product['quantity']+` `+product['unit']['unit']+`</option>`;		
					// });								
					// //$('#product_id').html(html);
				}	
				if(response.error == true) {
					swal("No product found !",  {icon: "warning"}, );
				}
							
			},
			error:function(error){
				console.log(error);
			}
		}); 
	}



	$(document).ready(function() {

		console.log($('#stocktransferwarehouse').attr('action'));
		$('#stocktransferwarehouseBtn').click(function(e){
			$('.loading-bg').show();
			// alert($('#stocktransferwarehouse').attr('action'));
			e.preventDefault();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			});     
			$.ajax({
				url: $('#stocktransferwarehouse').attr('action'),
				method: 'POST',
				data: $('#stocktransferwarehouse').serialize(),
				success: function(data){

					$('.loading-bg').hide();
					if(!data.flag){
						// showError('add_rake_point_error',data.errors.rake_point);
						// showError('add_from_warehouse_id_error',data.errors.from_warehouse_id);
						// showError('add_despatch_location_error',data.errors.despatch_location);
						// showError('add_dealer_id_error',data.errors.dealer_id);
						// showError('add_product_company_id_error',data.errors.product_company_id);
						// showError('add_product_id_error',data.errors.product_id);
						// showError('add_retailer_id_error',data.errors.retailer_id);
						// showError('add_quantity_error',data.errors.quantity);
						// showError('add_unit_id_error',data.errors.unit_id);
					}else{
						swal({
							title: "Success!",
							text: data.message,
							type: "success"
						},function() {
									window.location.reload();
								});
						$('#modalPopup').modal('toggle')
						$('#dynamic-table').DataTable().draw();	
						// window.location.reload();
					}

				}

			});
		});

	});


	
	
	

	
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
				<label for="product_id">{{__('messages.Product')}}</label>
					<select class="form-control select2" name="product_id" id="product_id" >
						<option value="">{{__('messages.Product')}} {{__('messages.Select')}}</option>
					
				</select>
				<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
				</div>
				</div>
				<div class="col-md-3">
				<div class="form-group">
				<label for="rate">Fresh Quantity</label>
				<input type="text" class="form-control checkIfValid" name="quantity"  placeholder="Quantity">
				<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
				</div>
				</div>
                <div class="col-md-2">
                <div class="form-group">
				<label for="wharfage">Damage Qty</label>
				<input type="text" class="form-control" name="Damage" id="Damage" placeholder="Damage">
				<span class="label label-danger" id="add_wharfage_error" style="display: none;"></span>
				</div>
				</div>
				<div class="col-md-1" style="margin-top: 30px;">
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
