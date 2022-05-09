<form action="{{url('/user/update-loading-slip')}}" method="post" role="form" id="generateLoadingSlip">

	<div class="container" style="width:100% !important;">
		<div class="row">
			<input type="hidden" name="loading_id" value="{{$loading_slip->id}}">

			<div class="col-md-12">
				<div class="form-group">
					<label >Order information</label>
					<table class="table table-striped table-bordered table-hover" id="orderDetails">
						
					</table>
				</div>
			</div>


			<div class="clearfix"></div>

			<div class="col-md-2">
				<div class="form-group">
					<label for="quantity">loading Quantity (in bags)</label>
					<input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity" value="{{$loading_slip->quantity}}">
					<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="order_from">Transporter</label>
					<select class="form-control select2" name="transporter_id" id="transporter_id" >
						<option value="">Select Order</option>
						@foreach($transporters as $transporter)
						<option value="{{$transporter->id}}" {{$transporter->id == $loading_slip->transporter_id ? 'selected' : ''}}>{{$transporter->name}} </option>
						@endforeach
					</select>
					<span class="label label-danger" id="add_transporter_id_error" style="display: none;"></span>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="order_from">Transport Mode</label>
					<select class="form-control select2" name="transport_mode">
						<option value="">Select Transport Mode</option>
						@foreach($transport_modes as $transport_mode)
						<option value="{{$transport_mode->id}}" {{$transport_mode->id == $loading_slip->transport_mode ? 'selected' : ''}}> {{$transport_mode->name}} </option>
						@endforeach
					</select>
					<span class="label label-danger" id="add_transport_mode_error" style="display: none;"></span>
				</div>
			</div>
			
			<div class="col-md-2">
				<div class="form-group">
					<label for="vehicle_no">Vehicle No</label>
					<input type="text" class="form-control" name="vehicle_no" id="vehicle_no" placeholder="Vehicle No" value="{{$loading_slip->vehicle_no}}" required="">
					<span class="label label-danger" id="add_vehicle_no_error" style="display: none;"></span>
				</div>
			</div>
              <div class="col-md-2">
				<div class="form-group">
					<label for="vehicle_no"> Driver Mobile No</label>
					<input type="text" class="form-control" name="driver_no" id="driver_no" placeholder="Driver Mobile No" value="{{$loading_slip->driver_no}}" required="">
					<span class="label label-danger" id="add_driver_no_error" style="display: none;"></span>
				</div>
			</div>
			
		</div>
	</div>

	<button type="button" id="generateTokenBtn" class="btn btn-primary">Submit</button>
	<span class="label label-danger" id="qty_error" style="display: none;"></span>
</form>

<script type="text/javascript">

	function orderDetails(){
		
		var data = {};
		data.order_id = '{{$loading_slip->order_id}}';
		data._token = '{{csrf_token()}}';
		var url = window.location.origin+'/user/get-order-details';
		$.ajax({
			url: url,
			type:'post',
			data: data,
			dataType:'json',
			success:function(responce){
				console.log(responce);
				if(responce.success == true){
					var html =`<tr>
							<td>Order Id : </td>
							<td>`+responce.order.id+`</td>
							<td>Order From : </td>`;

							if(responce.order.order_from == 1){ html +=`<td> Rake (`+responce.order.rake_point_name+`)</td>`; }else { html +=`<td> Warehouse (`+responce.order.from_warehouse_name+`)</td>`;  }
						html +=`</tr>
						<tr>
							<td>Dealer Name : </td>
							<td>`+responce.order.dealer_name+`</td>
							<td>Retailer Name : </td>
							<td>`+responce.order.retailer_name+`</td>
						</tr>
						<tr>
							<td>Retailer No: </td>
							<td>`+responce.order.phone_number+`</td>
							<td>Retailer Address : </td>
							<td>`+responce.order.retailer_address+`</td>
						</tr>
						<tr>
							<td>Product Company: </td>
							<td>`+responce.order.product_company_name+`</td>
							<td>Product Name : </td>
							<td>`+responce.order.product_name+`</td>
						</tr>
						<tr>
							<td>Order Quantity: </td>
							<td>`+responce.order.quantity+`</td>
							<td>Remaining Quantity : </td>
							<td>`+responce.order.remaining_qty+`</td>
						</tr>`;
					$('#orderDetails').html(html);
				}
			},
			error:function(error){
				console.log(error);
			}
		});
	}

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
						html +='<option value="'+val.id+'">'+val.name+'</option>';
					}); 

					$('#retailer_id').html(html);
					$("#retailer_id").select2();
					
				}
			},
			error:function(error){
				console.log(error);
			}
		});
	}

	$(document).ready(function() {

		orderDetails();

		$(".select2").select2();

		$('#generateTokenBtn').click(function(e){
			$('.loading-bg').show();
			e.preventDefault();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			});
			$.ajax({
				url: $('#generateLoadingSlip').attr('action'),
				method: 'POST',
				data: $('#generateLoadingSlip').serialize(),
				success: function(data){

					$('.loading-bg').hide();
					if(!data.flag){
						showError('add_order_id_error',data.errors.order_id);
						showError('add_quantity_error',data.errors.quantity);
						showError('add_transporter_id_error',data.errors.transporter_id);
						showError('add_transport_mode_error',data.errors.transport_mode);
						showError('add_vehicle_no_error',data.errors.vehicle_no);
						showError('qty_error',data.errors.qty);
					}else{
						swal({
							title: "Success!",
							text: data.message,
							type: "success"
						});
						// window.location.reload();
						$('#editModalPopup').modal('toggle');
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
	

	
	function showError(id,error){
		if(typeof(error) === "undefined"){
			$('#'+id).hide();
		}else{
			$('#'+id).show();
			$('#'+id).text(error);
		}
	}

</script>
