@extends('dashboard.layouts.app')
@section('title','Warehouse Manager')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <lui>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">{{__('messages.Home')}}</a>
                </lui>
                <li class="active">Arrived Stock</li>
            </ul>
        </div>

        <div class="page-content">

            <div class="row">
                <div class="col-xs-12">
                    <h3 class="header smaller lighter blue">
                        Arrived Stock
                    </h3>
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

                </div>
            </div>
            <div class="clearfix">
               <div class="pull-right tableTools-container">
               </div>
           </div>
           <div class="table-header">
               Stock Arrived in Warehouse
           </div>
           <form action="{{URL('/user/warehouse-manager')}}" method="POST" role="form" id="recievedQuanityForm">
               {{ csrf_field() }}
               <div class="table-responsive">
                <div class="dataTables_borderWrap">
                    <div class="table-responsive">
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>  {{__('messages.LoadingSlip')}} #</th>
                                    <th>  {{__('messages.Token')}}#</th>
                                    <th> {{__('messages.Date')}} </th>
                                    <th> {{__('messages.Party')}} <br/> {{__('messages.Name')}}</th>
                                    <th> {{__('messages.Product')}}<br/>{{__('messages.Name')}} </th>
                                    <th> {{__('messages.TruckNumber')}} </th>
                                    <th> {{__('messages.Transporter')}}<br/> {{__('messages.Name')}}</th>
                                    <th>Freight</th>
                                    <th>Payment Mode</th>
                                    <th>Quantity</br> Approved</th>
                                    <th> {{__('messages.Quantity')}}</th>
                                    <th>Recieved Quantity</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($product_loadings))
                                @foreach($product_loadings as $product_loading)
                                <tr id="tr_{{$product_loading->id}}">
                                    <td>{{$product_loading->id}}</td>
                                    <td>{{$product_loading->token_id}}</td>
                                    <td>{{date('d-m-Y',strtotime($product_loading->created_at))}}</td>
                                    <td>

                                        @if(!is_null($product_loading->warehouse_id))
                                        <b>
                                            {{getModelById('Warehouse',$product_loading->warehouse_id)->name}}
                                        </b>
                                        <br>
                                        {{getModelById('Warehouse',$product_loading->warehouse_id)->location}}   
                                        @else
                                        <b>
                                            {{getModelById('Dealer',$product_loading->dealer_id)->name}}
                                        </b>
                                        <br>
                                        {{getModelById('Dealer',$product_loading->dealer_id)->address1}}           
                                        @endif          
                                    </td>
                                    <td>{{$product_loading->product_name}} </td>

                                    <td>{{$product_loading->truck_number}} </td>

                                    <td>{{$product_loading->transporter_name}} </td>
                                    <td>{{$product_loading->freight}}</td>

                                    <td>{{is_null(getModelById('Token', $product_loading->token_id))?"":getModelById('Token', $product_loading->token_id)->delivery_payment_mode}}
                                    </td>
                                    <td>
                                        @if($product_loading->is_approved==0)
                                        <a class="fa fa-times fa-3x" id="approval" onclick="quantityDetails('{{$product_loading->product_id}}','{{$product_loading->product_company_id}}','{{$product_loading->dealer_id}}','{{$product_loading->unit_id}}','{{$product_loading->warehouse_id}}','{{$product_loading->quantity}}','{{$product_loading->id}}')"></a>
                                        @else
                                        <a class="fa fa-check fa-3x" id="approval" onclick="quantityDetails('{{$product_loading->product_id}}','{{$product_loading->product_company_id}}','{{$product_loading->dealer_id}}','{{$product_loading->unit_id}}','{{$product_loading->warehouse_id}}' ,'{{$product_loading->quantity}}','{{$product_loading->id}}')"></a>
                                        @endif
                                    </td>
                                    <td>{{$product_loading->quantity}}</td>  
                                    @if($product_loading->recieved_quantity==0)
                                    <td></td>
                                    @else
                                    <td>{{$product_loading->recieved_quantity}}</td>
                                    @endif
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
        <div class="modal fade" id="QuantityDetailsModal">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Recieved Quantity</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Quantity</th>
                                        <td id="payment_amount"></td>
                                    </tr>

                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="QuantityModal">
            <form action="{{URL('/user/warehouse-manager')}}" method="post" id="quantityForm">
                {{csrf_field()}}
                <input type="hidden" name="id" id="id" >
                <input type="hidden" name="dealer_id" id="dealer_id" >
                <input type="hidden" name="unit_id" id="unit_id" >
                <input type="hidden" name="product_company_id" id="product_company_id" >
                <input type="hidden" name="warehouse_id" id="warehouse_id" >
                <input type="hidden" name="product_id" id="product_id">

                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Quantity</h4>
                        </div>
                        <div class="modal-body" id="quantityModalBody">
                            <div class="form-group">
                                <label for="">Quantity</label>
                                <input type="text" class="form-control" name="quantity" id="quantity">
                                <span class="label label-danger" id="qty_error"  style="display: none;"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary " id="saveQuantity">Save changes</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>


    </div>
</div>
</div>
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

<script type="text/javascript">

    function quantityDetails(product_id,product_company_id,dealer_id,unit_id,warehouse_id,quantity,id){
        $('#id').prop('value',id);
        $('#product_id').prop('value',product_id);
        $('#product_company_id').prop('value',product_company_id);
        $('#dealer_id').prop('value',dealer_id);
        $('#unit_id').prop('value',unit_id);
        $('#quantity').prop('value',quantity);
        $('#warehouse_id').prop('value',warehouse_id);
        $('#QuantityModal').modal('toggle');
    }
    
    $('#saveQuantity').click(function(e){
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
                // $('.loading-bg').show();
                $.ajax({
                    url: $('#quantityForm').attr('action'),
                    method: 'POST',
                    data: $('#quantityForm').serialize(),
                    success: function(data){
                        $('.loading-bg').hide();
                        if(data.flag){
                            swal({
                                title: "Success!",
                                text: data.message,
                                type: "success"
                            }, function() {
                                window.location.reload();
                            });
                        }else{
                            showError('qty_error',data.errors.quantity);
                        }
                    }

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
@endsection
@endsection
