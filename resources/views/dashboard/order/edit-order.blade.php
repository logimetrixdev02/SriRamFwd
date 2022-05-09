

					<form action="{{url('/user/update-order')}}" method="post" role="form" id="generateTokenForm">
						<input type="hidden" name="order_id" value="{{$order->id}}">
						<div class="container" style="width:100% !important;">
							<div class="row">
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="order_from">Rake / Godown</label>
										<select class="form-control select2" name="order_from" id="order_from" onchange="handleTokenType(this.value)">
											<option value="1" {{$order->order_from == 1 ? 'selected' : ''}}>Rake</option>
											<option value="2" {{$order->order_from == 2 ? 'selected' : ''}}>Godown</option>
										</select>
									</div>
								</div>
								<div class="col-md-6" id="rake_div" style="display: {{$order->order_from == 1 ? 'block' : 'none'}}">
									<div class="form-group">
										<label for="rake_point">Rake Points</label>
										<select class="form-control select2" name="rake_point" id="rake_point" >
											<option value="">Select Rake Point</option>
											@foreach($rake_points as $rake_point)
											<option value="{{$rake_point->id}}" {{$rake_point->id == $order->rake_point ? 'selected' : ''}}>{{$rake_point->rake_point}}</option>
											@endforeach()
											
										</select>
										<span class="label label-danger" id="add_rake_point_error" style="display: none;"></span>
									</div>
								</div>
								
								<div class="col-md-6" id="warehouse_div" style="display: {{$order->order_from == 2 ? 'block' : 'none'}}">
									<div class="form-group">
										<label for="from_warehouse_id">{{__('messages.From')}} {{__('messages.Warehouses')}}</label>
										<select class="form-control select2" name="from_warehouse_id" id="from_warehouse_id" >
											<option value="">{{__('messages.Select')}} {{__('messages.Warehouses')}}</option>
											@foreach($warehouses as $warehouse)
											<option value="{{$warehouse->id}}" {{$warehouse->id == $order->from_warehouse_id ? 'selected' : ''}} >{{$warehouse->name}}</option>
											@endforeach()
											
										</select>
										<span class="label label-danger" id="add_from_warehouse_id_error" style="display: none;"></span>
									</div>
								</div>

							

								

								<div class="clearfix"></div>

								<div class="col-md-6" id="dealer_section">
									<div class="form-group">
										<label for="dealer_id"> Dealer Name</label>
										<select class="form-control select2" name="dealer_id" id="dealer_id" onchange="get_retailer(this)">
											<option value="">{{__('messages.Dealer')}} {{__('messages.Select')}}</option>
											@foreach($dealers as $dealer)
											<option value="{{$dealer->unique_id}}" {{$dealer->unique_id == $order->dealer_id ? 'selected' : ''}} >{{$dealer->name}} ({{$dealer->unique_id}}) </option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_dealer_id_error" style="display: none;"></span>
									</div>
								</div>

								

								<div class="col-md-6" id="retailer_section" >
									<div class="form-group">
										<label for="retailer_id">Retailer Name</label>
										<select class="form-control select2" name="retailer_id" id="retailer_id" onchange="get_address_of_retailer(this)">
											<option value="">{{__('messages.Retailers')}} {{__('messages.Select')}}</option>
											@foreach($retailers as $retailer)
											<option value="{{$retailer->unique_code}}" {{$retailer->unique_code == $order->retailer_id ? 'selected' : ''}} >{{$retailer->name}}({{$retailer->address}})</option>
											@endforeach()
											
										</select>
										<span class="label label-danger" id="add_retailer_id_error" style="display: none;"></span>
									</div>
								</div>


								


								<div class="clearfix"></div>


								<div class="col-md-6">
									<div class="form-group">
										<label for="product_id">{{__('messages.Product')}}</label>
										<select class="form-control select2" name="product_id" id="product_id" >
											<option value="">{{__('messages.Product')}} {{__('messages.Select')}}</option>
											
											@foreach($products as $product)
											<option value="{{$product->id}}" {{$product->id == $order->product_id ? 'selected' : ''}}>{{$product->name}}</option>
											@endforeach()
											
										</select>
										<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
									</div>
								</div>


								<div class="col-md-6">
									<div class="form-group">
										<label for="quantity">Quantity (in bags)</label>
										<input type="text" class="form-control" name="quantity" id="quantity" value="{{$order->quantity}}" placeholder="Quantity" onkeyup="checkQuantity(this.value)">
										<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
									</div>
								</div>


								
								<div class="clearfix"></div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="retailer_address">Retailer Address</label>
										<input type="text" class="form-control" name="retailer_address" id="retailer_address" value="{{$order->retailer_address}}">
										<span class="label label-danger" id="add_retailer_address_error" style="display: none;"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="phone_number">Phone Number</label>
										<input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="phone_number" value="{{$order->phone_number}}">
										<span class="label label-danger" id="add_phone_number_error" style="display: none;"></span>
									</div>
								</div>
								

								<div class="clearfix"></div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="remark">Remark</label>
										<textarea name="remark" id="remark" class="form-control" rows="3">{{$order->remark}}</textarea>
										<span class="label label-danger" id="add_remark_error" style="display: none;"></span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="product_id">Order Status</label>
										<select class="form-control select2" name="order_status" id="order_status" >
											<option value="">Order Status {{__('messages.Select')}}</option>
											
											
											<option value="approved" {{$order->order_status == 'approved' ? 'selected' : ''}}>Approved</option>
											
											<option value="cencel" {{$order->order_status == 'cencel' ? 'selected' : ''}}>Cencel</option>
										</select>
										<span class="label label-danger" id="add_remark_error" style="display: none;"></span>
									</div>
								</div>

							</div>
						</div>
						
						
						<button type="button" id="generateTokenBtn" class="btn btn-primary">Update</button>
					</form>

<script type="text/javascript">
$('.select2').select2();
	function get_address_of_retailer(t){
		var retailer_id = $(t).val();
		var data = {};
		data.retailer_id = retailer_id;
		data._token = '{{csrf_token()}}';
		var url = window.location.origin+'/user/get-address-of-retailer';
		$.ajax({
			url: url,
			type:'post',
			data: data,
			dataType:'json',
			success:function(responce){
				console.log(responce);
				if(responce.success == true){
					$('#retailer_address').val(responce.retailer.address);
					$('#phone_number').val(responce.retailer.mobile_number);
				}
			},
			error:function(error){
				console.log(error);
			}
		});
	}

	function get_retailer(t){

		var dealer_id = $(t).val();
		
		var data = {};
		data.dealer_id = dealer_id;
		data._token = '{{csrf_token()}}';
		var url = window.location.origin+'/user/get-retailer';
		$.ajax({
			url: url,
			type:'post',
			data: data,
			dataType:'json',
			success:function(responce){
				console.log(responce);
				if(responce.success == true){
					var html = '<option value="">Select Retailer</option>';
					$.each(responce.retailers, function(k,val){
						html +='<option value="'+val.unique_code+'">'+val.name+'</option>';
					}); 

					$('#retailer_id').html(html);
					
				}
			},
			error:function(error){
				console.log(error);
			}
		});
	}

	$(document).ready(function() {

		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		})
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});
		


		$('#generateTokenBtn').click(function(e){
			$('.loading-bg').show();
			e.preventDefault();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			});
			$.ajax({
				url: $('#generateTokenForm').attr('action'),
				method: 'POST',
				data: $('#generateTokenForm').serialize(),
				success: function(data){

					$('.loading-bg').hide();
					if(!data.flag){
						showError('add_rake_point_error',data.errors.rake_point);
						showError('add_from_warehouse_id_error',data.errors.from_warehouse_id);
						showError('add_despatch_location_error',data.errors.despatch_location);
						showError('add_dealer_id_error',data.errors.dealer_id);
						showError('add_product_company_id_error',data.errors.product_company_id);
						showError('add_product_id_error',data.errors.product_id);
						showError('add_retailer_id_error',data.errors.retailer_id);
						showError('add_quantity_error',data.errors.quantity);
						showError('add_unit_id_error',data.errors.unit_id);
						showError('add_remark_error',data.errors.unit_id);
					}else{
						swal({
							title: "Success!",
							text: data.message,
							type: "success"
						});
						$('#editModalPopup').modal('toggle');
						$('#dynamic-table').DataTable().draw();
					}

				}

			});
		});




		

	});

	function handleTokenType(id){

		
		if(id==1){
			$('#rake_div').show();
			$('#warehouse_div').hide();
			
		}else if(id==2){
			$('#rake_div').hide();
			$('#warehouse_div').show();
		}
	}
	
	function handleToType(type){
		$('.loading-bg').show();
		if(type == 1){
			$('#warehouses_section').show();
			$('#retailer_section').hide();
		}else if(type == 2){
			$('#warehouses_section').hide();
			$('#retailer_section').show();
		}else if(type == 3){
			$('#warehouses_section').hide();
			$('#retailer_section').hide();
		}
		$('.loading-bg').hide();
	}
	

	function checkQuantity(value){
		var order_from = $('#order_from option:selected').val();
		if(order_from == 2){
			var remaining_quantity = parseInt($('#remaining_quantity').val());
			if(remaining_quantity < parseInt(value)){
				('#quantity').val('');
				swal('Error','Entered Quantity ('+parseInt(value)+') should not be greater tha remaining quantity('+remaining_quantity+')','error');
			}

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