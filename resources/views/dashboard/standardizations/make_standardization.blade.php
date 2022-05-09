@extends('dashboard.layouts.app')
@section('title','Standardization')

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
				<li class="active">Standardization</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Standardization</h3>

					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Results for "Latest Registered Master Rakes"
						<div class="widget-toolbar no-border">
							<a class="btn btn-xs bigger btn-danger dropdown-toggle"  data-toggle="modal" href='#standardizationModal'>
								Add
								<i class="ace-icon fa fa-plus icon-on-right"></i>
							</a>
						</div>
					</div>

					<div class="table-responsive">
						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<!-- <thead>
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
								</thead> -->

								<tbody>
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->
<!-- Add Master Rake Modal -->
<div class="modal fade" id="standardizationModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Standardization</h4>
			</div>
			<div class="modal-body">

				<form action="" role="form" id="standardizationForm">
					<div class="row">

						<div class="col-md-6">
							<div class="form-group">
								<label for="rake_point">Warehouse List </label>
								<select class="form-control select2 checkIfValid" name="warehouse_id" id="warehouse_id" onchange="get_product_qty(this,'warehouse')">
									<option value="">Select Warehouse List </option>
                                    @foreach($warehouse_list as $whl)
									<option value="{{$whl->wh_id}}">{{$whl->wh_name}}</option>
									@endforeach()
								</select>
								<span class="label label-danger" id="warehouse_id_error" style="display: none;"></span>
							</div>
						</div>

                        <div class="col-md-6">
							<div class="form-group">
								<label for="proruct">From Warehouse List</label>
								<select class="form-control select2 checkIfValid" name="from_warehouse_id" id="from_warehouse_id">
									<option value="">Select From Warehouse List</option>
									
                                    @foreach($from_warehouse_list as $fwhl)
									<option value="{{$fwhl->id}}">{{$fwhl->name}}</option>
									@endforeach()
									
								</select>
								<span class="label label-danger" id="from_warehouse_id_error" style="display: none;"></span>
							</div>
						</div>
						<div class="clearfix"></div>

					</div>
					<div class="row">

						<div class="col-md-12">
							<!-- <div class="form-group">
								<label for="proruct">proruct List</label>
								<select class="form-control select2 checkIfValid product_id" name="product_id[]" id="product_id"  multiple>
									<option value="">Select proruct List</option>
									
								</select>
								<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
							</div> -->
							<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th> Product Name</th> 
									<th> Damage Quantity</th>
									<th> Damage To Fresh Convert Quantity</th>
								</tr>
							</thead> 
							<tbody id="tbody">

							</tbody>
						</table> 
						</div>
						<!-- <div class="col-md-6">
							<div class="form-group">
								<label for="rate">Quantity</label>
								<input type="text" class="form-control checkIfValid" name="quantity[]"  placeholder="Quantity">
								<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
							</div>
						</div> -->

					</div>	
					

					<!-- <div class="pull-left">
						<button type="button" id="addMoreRow" class="btn btn-danger"><i class="fa fa-plus"></i> Add More </button>

					</div> -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" id="standardizationFormBtn" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Add Master Rake Modal -->







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

<!-- 
<script type="text/javascript">


	jQuery(function($) {

		//datepicker plugin
		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		})
				//show datepicker when clicking on the icon
				.next().on(ace.click_event, function(){
					$(this).prev().focus();
				});

				//initiate dataTables plugin
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
						autoPrint: false,
						message: 'This print was produced using the Print button for DataTables'
					}		  
					]
				} );
				myTable.buttons().container().appendTo( $('.tableTools-container') );
				
				//style the message box
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
		</script>


		<script>
			function validateField(){
				var errorCount = 0;
				$('.checkIfValid').each(function(){
					console.log($(this).val());
					if($(this).val() == ""){
						errorCount = errorCount + 1;
						$(this).closest('.form-group').find('.label-danger').text('Required');
						$(this).closest('.form-group').find('.label-danger').show();
					}else{
						$(this).closest('.form-group').find('.label-danger').hide();
					}
				});
				if(errorCount > 0){
					return true;
				}else{
					return false;
				}
			}

			$(document).ready(function(){

				// $('.select2').select2();
				$('.timepicker').mdtimepicker({
					timeFormat: 'hh:mm:ss.000',
					format: 'h:mm tt',     
					theme: 'blue',       
					readOnly: true,      
					hourPadding: false    
				});

			


				$('#lockMasterRakeBtnEnabled').click(function(e){
					e.preventDefault();

					var errorCount = 0;
					$('.checkIfValids').each(function(){
						if(this.checked ==false){
							errorCount = errorCount + 1;
						}	
					});

					// if(errorCount > 0){
						if(0){
							$("#edit_name_error").show();
							$('.loading-bg').hide();
							return false;
						}

						else{
							$("#edit_name_error").hide();
							
							$.ajax({
								url:  "{{url('/')}}"+'/user/lock-master-rake/'+$('#master_rake_id').val(),
								method: 'GET',
								success: function(data){
									console.log(data);
								// var data = JSON.parse(data);
								if(data.flag){
									swal({
										title: "Success!",
										text: data.message,
										type: "success"
									}, function() {
										window.location.reload();
									});
								}else{
									swal('Error',data.message,'error')
								}
								
							}
						});
						}	
					});


			});


			function getEdit(id){
				if(id == ""){
					swal('Error','Master Rake id is missing','warning');
				}else{
					$('.loading-bg').show();
					$.ajax({
						url: "{{url('/user/edit-master-rake/')}}"+"/"+id,
						type: 'GET',
						success:function(data){
							$('.loading-bg').hide();
							$('#EditBody').html(data);
							$('#editMasterRakeModal').modal('toggle');
						}
					});

				}
			}


			function lockMasterRake(id){
				if(id == ""){
					swal('Error','Master Rake id is missing','warning');
				}else{
					$('#master_rake_id').val(id);
					$('#lockMasterRakeModal').modal('toggle');
				}
			}

			function deleteMasterRake(id){
				if(id == ""){
					swal('Error','Master Rake id is missing','warning');
				}else{
					swal({
						title: "Are you sure?",
						text: "You will not be able to recover this Master Rake!",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: '#DD6B55',
						confirmButtonText: 'Yes, I am sure!',
						cancelButtonText: "No, cancel it!",
						closeOnConfirm: false,
						closeOnCancel: false
					},
					function(isConfirm){
						if (isConfirm){
							$.ajax({
								url: "{{url('/user/delete-master-rake/')}}"+"/"+id,
								type: 'GET',
								success:function(data){
									if(data.flag){
										$('#tr_'+id).remove();
										swal("Success", "Master Rake Deleted Successfully", "success");
									}else{
										swal("Error", data.message, "error");
									}
								}
							});
						} else {
							swal("Cancelled", "Your Master Rake is safe :)", "error");
						}
					});
				}
			}
			function showError(id,error){
				if(typeof(error) === "undefined"){
					$('#'+id).hide();
				}else{
					$('#'+id).show();
					$('#'+id).text(error);
				}
			};
            </script> -->
            <script>
		$('#standardizationFormBtn').click(function(e){
			$('.loading-bg').show();
			e.preventDefault();
			// $('#standardizationModal').modal('toggle');
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			});     
			$.ajax({
				url: $('#standardizationForm').attr('action'),
				method: 'POST',
				data: $('#standardizationForm').serialize(),
				success: function(data){

					$('.loading-bg').hide();
					
						swal({
							title: "Success!",
							text: data.msg,
							type: "success"
						},function() {
									window.location.reload();
								});
						
						// window.location.reload();
				}

			});
		});



			
			

		function get_product_qty(t, name) {
			//alert();
		var warehouse_id = $(t).val();
		var data = {};
		data.warehouse_id = warehouse_id;
		data.name = name;
		data._token = '{{csrf_token()}}';
		var url = window.location.origin+'/user/get-damage-product-qty';
		$.ajax({
			url: url,
			type:'get',
			data: data,
			dataType:'json',
			success:function(response){			
				if(response.success == true) {
					html = '';
					// $('#product_id').html();
					$("#tbody").html('');
					$.each(response.product_qtys, function(key, product){
						//console.log(product.product.name);
						//html += `<option value="`+product['product_id']+`">`+product['product']['name']+` - `+product['damage_qty']+` `+product['unit']['unit']+`</option>`;		
						html += `<tr id="product_`+product.product_id+`">
							

          					<td><input type="hidden" id="input_`+product.product_id+`" onkeypress="return onlyCurrency(event)"  onblur="check_zero(this)" data-id="id_`+product.product_id+`_`+product.product_id+`" name="product_id[`+product.product_id+`]" onkeyup="getvalue(this,`+product.product_id+`)" value="`+product.product.id+`"><input type="text" id="input_`+product.product_id+`" onkeypress="return onlyCurrency(event)"  onblur="check_zero(this)" data-id="id_`+product.product_id+`_`+product.product_id+`" name="product_name[`+product.product_id+`]" onkeyup="getvalue(this,`+product.product_id+`)" value="`+product.product.name+`" style="width:100px;" readonly/> </td>
							<td>`+product.damage_qty+`</td>
          					<td><input type="text" id="input_`+product.product_id+`" onkeypress="return onlyCurrency(event)"  onblur="check_zero(this)" data-id="id_`+product.product_id+`_`+product.product_id+`" name="damage_qty[`+product.product_id+`]" onkeyup="getRate(this,`+product.product_id+`)" value="`+product.damage_qty+`"/></td>
          				</tr>`;
					});	
					$('#tbody').append(html);							
					//$('.product_id').html(html);
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
		</script>
		@endsection
		@endsection
