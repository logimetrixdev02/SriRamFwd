@extends('dashboard.layouts.app')
@section('title','Dashboard')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Generate Invoice</li>
			</ul><!-- /.breadcrumb -->			
		</div>

		<div class="page-content">
			
			<div class="page-header">
				<h1>
                    Generate Invoice 
				</h1>               
                <a href="print-invoice" target="_blank">View Invoice</a>
                <!-- <button onclick="window.print()" id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button> -->
			</div><!-- /.page-header -->

            <div class="container">
                <div class="row" >
                    <form action="" method="post" id="generateInovoiceForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="sender_name">Sender Name</label>
                                    <input type="text" name="sender_name" class="form-control" id="sender_name" placeholder="Sender Name">
                                    <span class="label label-danger" style="display: none;" id="add_sender_name_error"></span>
                                </div>	                            
                            </div>   
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="receiver_name">Receiver Name</label>
                                    <input type="text" class="form-control" id="receiver_name" palceholder="Receiver Nmme">
                                    <span class="label label-danger" id="add_receiver_name_error" style="display: none;"></span>
                                </div>
                            </div>                    
                            <div class="col-md-2">
                                <div class="from-group">
                                    <label for="">Builty No.</label>      
                                    <input type="text" name="builty_no" class="form-control" id="builty_no" readonly="true">
                                </div>                                
                            </div>                        
                        </div>      
                        <div class="row">                            
                            <div class="col-md-10">
                                <div class="form-group">
                                    <lable for="delivery_address">Delivery Address</lable>
                                    <input type="text" class="form-control" id="delivery_address" placeholder="Delivery Address">
                                    <span class="label label-danger" id="add_delivery_address_error" style="display:none;"></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <lable for="date">Date</lable>
                                    <input type="date" name="date" class="form-control" id="date">
                                    <span class="label lable-danger" id="add_date_error" style="display: none;"></span>
                                </div>
                            </div>
                        </div>   
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="truck_owner_name">Truck Owner Name</label>
                                    <input type="text" class="form-control" id="truck_owner_name" name="truck_owner_name" placeholer="Truck Owner Name">
                                    <span class="lable lable-danger" id="add_truck_owner_name_error" style="display:none;"></span>
                                </div>                                
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="truck_driver_name">Truck Driver Name</label>
                                    <input type="text" class="form-control" name="truck_driver_name" id="truck_driver_name" placeholder="Truck Driver Name">
                                    <span class="label label-danger" id="add_truck_driver_name_error" style="display:none;"></span>                           
                                </div>
                            </div> 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="truck_no">Truck No.</label>
                                    <input type="text" class="form-control" id="truck_no" name="truck_no" placeholder="Truck No.">
                                    <span class="label label-danger" id="add_truck_no_error" style="display: none;"></span>
                                </div>  
                            </div> 
                        </div>                         
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="itemDetail">
                                    <thead>
                                        <th>Description</th>
                                        <th>Count</th>
                                        <th>Weight</th>
                                        <th>Rate</th>
                                        <th>Advance</th>
                                        <th>Remaining Freight</th>
                                        <th>Remark</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        <tr id="row_2">
                                            <td>
                                                <input type="text" class="form-control" id="product_2" name="product[]">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" id="count_2" name="product_count[]">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="weight_2" name="weight[]">        
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="rate_2" name="rate[]">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="advance_2" name="advance[]">
                                            </td>
                                            <td>
                                                <table>
                                                    <tr>
                                                        <th>Freight</th>
                                                        <th><input type="text"></th>
                                                    </tr>
                                                    <tr>
                                                        <th>TDS</th>
                                                        <th><input type="text"></th>
                                                    </tr>
                                                    <tr>
                                                        <th>TOTAL</th>
                                                        <th><input type="text"></th>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="remark_2" name="remark[]">
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>  
                            </div>              
                        </div> 
                        <div class="row">
                            <div class="col-md-12">    
                                <div class="form-group">
                                    <button type="button" name="addRow" class="btn btn-responsive   btn-success" onclick="addMoreRow();">Add More Item</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sale_no">Sale Order No.</label>
                                    <input type="text" name="sale_order_no" id="sale_order_no" class="form-control" placeholder="Sale Order Number">
                                    <span class="label label-danger" id="add_sale_order_no_error" style="display: none;"></span>
                                </div>                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="delivery_no">Delivery No.</label>
                                    <input type="text" name="delivery_no" id="delivery_no" placeholder="Delivery Number" class="form-control">
                                    <span class="label label-danger" id="add_delivery_no_error" style="display: none;"></span>
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gate_pass">Gate Pass No.</label>
                                    <input type="text" name="gate_pass_no" class="form-control" id="gate_pass_no" placeholder="Gate Pass Number">
                                    <span class="label label-danger" id="add_gate_pass_no_error" style="display: none;"></span>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" name="submit" class="btn btn-responsive pull-right btn-primary">Submit</button>                             
                                </div>
                            </div>
                        </div>
                        
                    </form>								
                </div>
            </div>
					

		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->







@section('script')


<!-- ace scripts -->
{{ Html::script("assets/js/ace-elements.min.js")}}
{{ Html::script("assets/js/ace.min.js")}}

<script>
    function addMoreRow() {
        var length = $("#itemDetail tr").length;
        var row_count = parseInt(length) + 1;
        var row_html = `<tr id="row_`+row_count+`">
            <td><input type="text" class="form-control" id="product_`+row_count+`" name="product[]"></td>
            <td><input type="number" class="form-control" id="count_`+row_count+`" name="product_count[]"></td>
            <td><input type="text" class="form-control" id="weight_`+row_count+`" name="weight[]"></td>
            <td><input type="text" class="form-control" id="rate_`+row_count+`" name="rate[]"></td>
            <td><input type="text" class="form-control" id="advance_`+row_count+`" name="advance[]"></td>
            <td><table><tr><th>Freight</th><th><input type="text" name="freight[]" id="freight_`+row_count+`"></th></tr><tr><th>TDS</th><th><input type="text" name="tds" id="tds_`+row_count+`"></th></tr><tr><th>TOTAL</th><th><input type="text" name="total" id="total_`+row_count+`"></th></tr></table></td>
            <td><input type="text" class="form-control" id="remark`+row_count+`" name="remark[]"></td>        
            <td><a href="javascript:void(0)" title="Delete Row" onclick="deleteRow(`+row_count+`)"><i class="fa fa-trash fa-2x" style="color: red;"></i></a></td>
            </tr>`;
        $("#itemDetail").append(row_html);
    }

    function deleteRow(count) {
		$("#row_"+count).remove();
	}

</script>




@endsection
@endsection

