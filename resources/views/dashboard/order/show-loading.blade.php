

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

			<div class="col-md-12">
				<div class="form-group">
					<label >Order information</label>
					<table id="dynamic-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>

                                <th>Loading Slip No</th>
                                <th>Order Id</th>
                                <th>Rake / Godown</th>
                                <th>Dealer</th>
                                <th>Retailer</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Transporter</th>
                                <th>Vehicle No</th>
                                <th>Genrated by</th>
                                <th>Slip Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($loading_slips as $loading_slip)
                           
                            <tr id="tr_{{$loading_slip->id}}">
                                <td>{{$loading_slip->id}}</td>
                                <td>{{$loading_slip->order_id}}</td>
                                <td>{{$loading_slip->order_from == 1 ? 'Rake' : 'Godown'}} <br/> (@if($loading_slip->order_from == 1) {{$loading_slip->rake_point_name}} @else {{$loading_slip->from_warehouse_name}} @endif)</td>
                                
                                <td>{{$loading_slip->dealer_name}} ({{$loading_slip->dealer_id}})</td>
                                <td>{{$loading_slip->retailer_name}}</td>
                                <td>{{$loading_slip->product_name}}</td>
                                <td>{{$loading_slip->quantity}}</td>
                                <td>{{$loading_slip->unit_name}}</td>
                                <td>{{$loading_slip->transporter_name}}</td>
                                <td>{{$loading_slip->transport_mode_name}} <br/>( {{$loading_slip->vehicle_no}} )</td>

                                <td>{{$loading_slip->user_name}} </td>
                                <td >
                                   @if($loading_slip->slip_status!='dispatched' ) 
                                <a href="javascript:void(0);" onclick="createInvoice({{$loading_slip->id}})" class="btn btn-xs btn-info">
                                        create Invoice
                                    </a>
                                    @endif
                                    @if( $loading_slip->slip_status=='dispatched')
                                    <span class="badge badge-success">Genrated Invoice</span>
                                    @endif
                                    <input type="hidden" name="id" id="id_{{ $loading_slip->id }}" value="{{ $loading_slip->id }}">
                                </td>
                                <td>
                                    <a href="#" onclick="get_edit_loading_form('{{$loading_slip->id}}')" ><i class="ace-icon fa fa-pencil bigger-120"></i></a>
                                    
                                    
                                    <a href="/user/print-loading-slip/{{$loading_slip->id}}"  target="_blank">
                                        <i class="ace-icon fa fa-print bigger-120"></i>
                                    </a>
                                </td>
                                
                            </tr>
                            
                            @empty
                            <tr>
                                <td colspan="12" style="text-align: center">No Data Found</td>
                            </tr>
                            </tbody>
                            
                    @endforelse
				</div>
			</div>
		</div>
	</div>

    {{-- <div class="modal fade" id="editModalPopup">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Edit Loading Slip</h4>
                </div>
                <div class="modal-body" id="editmodalPopupBody">
    
    
                </div>
            </div>
        </div>
    </div> --}}

<script type="text/javascript">

function createInvoice(id){
				// alert('hii');
				var id = $('#id_'+id).val();
				$.ajax({
					url: "{{url('/user/create-invoice/')}}",
					type: 'GET',
					data : {
					id : id
					},
					success:function(data){
						$('#createInvoiceModalPopupBodyLoading').html(data);
						$('#createInvoiceModalPopupLoading').modal('toggle');
						//OnLoad();
					}
				});
			}
            function get_edit_loading_form(id){
                // alert(id);
                $.ajax({
                    url: window.location.origin+"/user/edit-loading/"+id,
                    type: 'GET',
                    success:function(data){

                        $('#editmodalPopupBody').html(data);
                        $('#editModalPopup').modal('toggle');
                        //OnLoad();
                    }
                });
	    }

</script>
