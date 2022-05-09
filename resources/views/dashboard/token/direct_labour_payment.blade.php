@extends('dashboard.layouts.app')
@section('title','Direct Labour Payment')
@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="#">Home</a>
                </li>
                <li class="active">Direct Labour Payment</li>
            </ul>
        </div>

        <div class="page-content">


            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Direct Labour Payment</h3>
                </div>
                <div class="panel-body">
                    <form action="" role="form" id="directlabourpaymentForm">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="rake">Rake</label></br>
                                        <select  name="master_rake_id" class="form-control select1" onchange="getMasterRakeDetails(this.value)"  id="master_rake_id">
                                            <option></option>
                                            @foreach($master_rakes  as $rake)
                                            <option value="{{$rake->id}}" >{{$rake->name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="label label-danger" id="add_rake_id_error" style="display: none;"></span>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="rake">WareHouses</label></br>
                                        <select  name="warehouse_id"  class="form-control select2" onchange="getMasterRakeDetails(this.value)" id="warehouse_id">
                                            <option></option>
                                            @foreach($warehouses as $warehouse)
                                            <option value="{{$warehouse->id}}" >{{$warehouse->name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="label label-danger" id="add_warehouse_id_error" style="display: none;"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="rake">Labour Name</label></br>
                                        <input type="text" class="form-group" id="labour_name" name="labour_name">
                                        <span class="label label-danger" id="add_labour_name_error" style="display: none;"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="rake">Amount</label></br>
                                        <input type="text" class="form-group" id="amount" name="amount">
                                        <span class="label label-danger" id="add_amount_error" style="display: none;"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="rake">Description</label></br>
                                        <select class="form-control select1" name="Description" onchange="gettextarea(this.value)" >
                                            <option>Select</option>
                                            <option value="1">भोजन</option>
                                            <option value="2">किराया</option>
                                            <option value="3">इनाम</option>
                                        </select>
                                        <span class="label label-danger" id="add_description_error" style="display: none;"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" id="Description" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{URL('/user/labour-payment')}}" class="btn btn-default" >Back</a>
                        <button type="button" id="directlabourpayement" class="btn btn-primary">Submit</button>
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
{{ Html::script("assets/js/mdtimepicker.min.js")}}
{{ Html::script("assets/js/bootstrap-datepicker.min.js")}}

<script>
    $(document).ready(function() {

       $('#directlabourpayement').click(function(e){
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $('.loading-bg').show();
        $.ajax({
            url: $('#directlabourpaymentForm').attr('action'),
            method: 'POST',
            data: $('#directlabourpaymentForm').serialize(),
            success: function(data){
                console.log(data);
                $('.loading-bg').hide();
                if(!data.flag){
                    showError('add_rake_id_error',data.errors.master_rake_id);
                    showError('add_warehouse_id_error',data.errors.warehouse_id);
                    showError('add_labour_name_error',data.errors.labour_name);
                    showError('add_amount_error',data.errors.amount);
                    showError('add_description_error',data.errors.description);
                }else{
                  swal({
                      title: "Success!",
                      text: data.message,
                      type: "success"
                  }, function() {
                    window.location.reload();
                });
              }
          }

      });
    });

   });

    function getMasterRakeDetails(id)
    {
        if(id == ""){
            swal('Error','Master Rake id is missing','warning');
        }else{
            var rake =$('#master_rake_id').val();
            var warehouse =$('#warehouse_id').val();
            console.log(rake);
            if(rake)
            {
              $('#warehouse_id').prop('disabled','true');
          }
          if(warehouse)
          {
              $('#master_rake_id').prop('disabled','true');
          }
      }
  }
  function gettextarea(id)
  {
    console.log(id);
    if(id ==""){
        swal('Error','Description id is missing','warning');
    } else{
        var html ="<label>Description</label>\
        <textarea row='30' cols='40' name='description'></textarea>";
        $('#Description').html(html);
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

@endsection
@endsection
