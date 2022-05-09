@extends('dashboard.layouts.app')
@section('title','Godown Register')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <lui>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">{{__('messages.Home')}}</a>
                </lui>
                <li class="active">Godown Register New</li>
            </ul>
        </div>

        <div class="page-content">

            <div class="row">
                <div class="col-xs-12">
                    <h3 class="header smaller lighter blue">
                        Godown Register New
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

            <div class="row">
                <div class="col-xs-12">
                    <form action="" method="POST" role="form">
                        <div class="row">
                            {{csrf_field()}}

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="product_company_id"> Product Company</label>
                                    <select class="form-control select2" name="product_company_id" id="product_company_id">
                                        <option value=""> Select Company</option>
                                        @foreach($product_companies as $product_company)
                                        <option value="{{$product_company->id}}" {{isset($product_company_id) && $product_company_id==$product_company->id ? "selected":""}}>{{$product_company->name}}</option>
                                        @endforeach()
                                    </select>
                                    @if ($errors->has('product_company_id'))
                                    <span class="label label-danger">{{ $errors->first('product_company_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="warehouse_id"> {{__('messages.Warehouses')}}</label>
                                    <select class="form-control select2" name="warehouse_id" id="warehouse_id">
                                        <option value="">  {{__('messages.Warehouses')}} {{__('messages.Select')}}</option>
                                        @foreach($warehouses as $warehouse)
                                        <option value="{{$warehouse->id}}" {{isset($warehouse_id) && $warehouse_id==$warehouse->id ? "selected":""}}>{{$warehouse->name}}</option>
                                        @endforeach()
                                    </select>
                                    @if ($errors->has('warehouse_id'))
                                    <span class="label label-danger">{{ $errors->first('warehouse_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="product_id"> Product</label>
                                    <select class="form-control select2" name="product_id" id="product_id">
                                        <option value=""> Select Product</option>
                                        @foreach($products as $product)
                                        <option value="{{$product->id}}" {{isset($product_id) && $product_id==$product->id ? "selected":""}}>{{$product->name}}</option>
                                        @endforeach()
                                    </select>
                                    @if ($errors->has('product_id'))
                                    <span class="label label-danger">{{ $errors->first('product_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
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
            Godown Register New
            @if(isset($total))
            <span class="badge badge-success">{{$total}}</span>
            @endif
        </div>

        <div class="table-responsive">
            <div class="dataTables_borderWrap">
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Product Company</th>
                            <th>Warehouse</th>
                            <th>Product</th>
                            <th>Stock Qty</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($stocks)
                        @foreach($stocks as $stock)
                        <tr>
                            <td>{{$stock['party']}}</td>
                            <td>{{$stock['warehouse']}}</td>
                            <td>{{$stock['product']}}</td>
                            <td>{{$stock['balance']}}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
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





    jQuery(function($) {
        var myTable = 
        $('#dynamic-table').DataTable( {
            bAutoWidth: false,
            "aaSorting": [],
        } );

        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

        new $.fn.dataTable.Buttons( myTable, {
            buttons: [
            {
                "extend": "colvis",
                "text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
                "className": "btn btn-white btn-primary btn-bold",
                columns: ':not(:first):not(:last)'
            },
            {
                "extend": "copy",
                "text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
                "className": "btn btn-white btn-primary btn-bold"
            },
            {
                "extend": "csv",
                "text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
                "className": "btn btn-white btn-primary btn-bold"
            },
            {
                "extend": "excel",
                "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
                "className": "btn btn-white btn-primary btn-bold"
            },
            {
                "extend": "pdf",
                "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
                "className": "btn btn-white btn-primary btn-bold"
            },
            {
                "extend": "print",
                "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
                "className": "btn btn-white btn-primary btn-bold",
                autoPrint: true,
                message: 'IManager',
                exportOptions: {
                    columns: ':visible'
                }
            }         
            ]
        } );
        myTable.buttons().container().appendTo( $('.tableTools-container') );

        var defaultCopyAction = myTable.button(1).action();
        myTable.button(1).action(function (e, dt, button, config) {
            defaultCopyAction(e, dt, button, config);
            $('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
        });


        var defaultColvisAction = myTable.button(0).action();
        myTable.button(0).action(function (e, dt, button, config) {

            defaultColvisAction(e, dt, button, config);


            if($('.dt-button-collection > .dropdown-menu').length == 0) {
                $('.dt-button-collection')
                .wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
                .find('a').attr('href', '#').wrap("<li />")
            }
            $('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
        });

    })


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
