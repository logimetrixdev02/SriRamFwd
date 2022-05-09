

	<div class="container" style="width:100% !important;">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{{-- <label for="order_from">Orders</label> --}}
					 {{-- <select class="form-control select2" name="order_id" id="order_id" onchange="orderDetails(this)" disabled> --}}
						
					{{-- </select> --}}
					<span class="label label-danger" id="add_order_id_error" style="display: none;"></span>
				</div>
			</div>

			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Create Invoice For Loading Slip Id ( {{$loading_slip->id}} )</h3>
				</div>
				<div class="panel-body">

					<form action="{{url('/user/create-invoice-now')}}" method="post" role="form" id="generateTokenForm">
						{{csrf_field()}}
						<input type="hidden" name="loading_slip_id" value="{{$loading_slip->id}}">
						<div class="container" style="width:100% !important;">
							<div class="row">
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="dealer_name">From Dealer Name</label>
                                        <input type="hidden" name="id" id="id_{{ $loading_slip->id }}" value="{{ $loading_slip->id }}">
										<input type="text" class="form-control" name="dealer_name" id="dealer_name" value="{{$loading_slip->dealer_name}}" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="retailer_name">To Retailer Name</label>
										<input type="text" class="form-control" name="retailer_name" id="retailer_name" value="{{$loading_slip->retailer_name}}" readonly>
									</div>
								</div>

								<div class="clearfix"></div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="product_company_name">Product Company Name</label>
										<input type="text" class="form-control" name="product_company_name" id="product_company_name" value="{{$loading_slip->product_company_name}}" readonly>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="product_name">Product Name</label>
										<input type="text" class="form-control" name="product_name" id="product_name" value="{{$loading_slip->product_name}}" readonly>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="unit_name">Unit</label>
										<input type="text" class="form-control" name="unit_name" id="unit_name" value="{{$loading_slip->unit_name}}" readonly>
									</div>
								</div>
								
								
								

								<div class="clearfix"></div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="quantity">Quantity</label>
											<input type="text" class="form-control" name="quantity" id="quantity" value="{{$loading_slip->quantity}}">
											<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
										</div>
									</div>

									<div class="col-md-4">
									<div class="form-group">
										<label for="referance_invoice_id">Referance Invoice Id</label>
										<input type="text" class="form-control" name="referance_invoice_id" id="referance_invoice_id" placeholder="Referance Invoice Id">
										<span class="label label-danger" id="add_referance_invoice_id_error" style="display: none;"></span>
									</div>
								</div>
								
								<div class="col-md-2">
									<div class="form-group">
										<label for="order_status">Order Status</label>
										<select class="form-control select2" name="order_status" id="order_status">
											<option value="">Select Order Status</option>
											<option value="dispatched" {{$loading_slip->slip_status == 'dispatched' ? 'selected':''}}>Dispatched</option>
											<option value="cancelled" {{$loading_slip->slip_status == 'cancelled' ? 'selected':''}}>Cancelled</option>
										</select>
										<span class="label label-danger" id="add_order_status_error" style="display: none;"></span>
									</div>
								</div>
								
								
							</div>
						</div>
						
						<button type="button" id="generateTokenBtn" class="btn btn-primary">Submit</button>
					</form>


				</div>
			</div>
		</div>
	</div>


<script type="text/javascript">

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
				showError('add_order_status_error',data.errors.order_status);
				showError('add_referance_invoice_id_error',data.errors.referance_invoice_id);
				showError('add_quantity_error',data.errors.quantity);

			}else{
				swal({
					title: "Success!",
					text: data.message,
					type: "success"
				});
				$('#createInvoiceModalPopupLoading').modal('hide');
				$('#modalPopupLoading').modal('toggle');
				$('#dynamic-table').DataTable().draw();
			}

		},
		error:function(error){
			console.log(error);
			$('.loading-bg').show();
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
}

</script>
