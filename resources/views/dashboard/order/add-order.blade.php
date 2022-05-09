<form action="{{url('/user/post-order')}}" method="post" role="form" id="generateTokenForm">



	<div class="container" style="width:100% !important;">

		<div class="row">

			

			<div class="col-md-6">

				<div class="form-group">

					<label for="order_from">Rake / Godown</label>

					<select class="form-control select2" name="order_from" id="order_from" onchange="handleTokenType(this.value)">

						<option value="1" selected>Rake</option>

						<option value="2">Godown</option>

					</select>

				</div>

			</div>

			<div class="col-md-6" id="rake_div">

				<div class="form-group">

					<label for="rake_point">Rake Points</label>

					<select class="form-control select2" name="rake_point" id="rake_point" onchange="get_product_qty(this, 'rake_point')">

						<option value="">Select Rake Point</option>

						@foreach($rake_points as $rake_point)

						<option value="{{$rake_point->id}}">{{$rake_point->rake_point}}</option>

						@endforeach()

						

					</select>

					<span class="label label-danger" id="add_rake_point_error" style="display: none;"></span>

				</div>

			</div>

			<div class="col-md-6" id="warehouse_div" style="display: none;">

				<div class="form-group">

					<label for="from_warehouse_id">{{__('messages.From')}} {{__('messages.Warehouses')}}</label>

					<select class="form-control select2" name="from_warehouse_id" id="from_warehouse_id" onchange="get_product_qty(this, 'warehouse')">

						<option value="">{{__('messages.Select')}} {{__('messages.Warehouses')}}</option>

						@foreach($warehouses as $warehouse)

						<option value="{{$warehouse->id}}">{{$warehouse->name}}</option>

						@endforeach()

						

					</select>

					<span class="label label-danger" id="add_from_warehouse_id_error" style="display: none;"></span>

				</div>

			</div>



			<!--div class="col-md-4" id="despatch_location_div" >

				<div class="form-group">

					<label for="despatch_location">Despatch Location</label>

					<select class="form-control select2" name="despatch_location" id="despatch_location" >

						<option value="">Select Location</option>

						@foreach($destinations as $destination)

						<option value="{{$destination->code}}">{{$destination->name}}</option>

						@endforeach()

						

					</select>

					<span class="label label-danger" id="add_despatch_location_error" style="display: none;"></span>

				</div>

			</div-->



			



			<div class="clearfix"></div>







			<div class="col-md-6" id="dealer_section">

				<div class="form-group">

					<label for="dealer_id"> Dealer Name</label>

					<select class="form-control select2" name="dealer_id" id="dealer_id"  onchange="get_retailer(this)">

						<option value="">{{__('messages.Dealer')}} {{__('messages.Select')}}</option>

						@foreach($dealers as $dealer)

						<option value="{{$dealer->unique_id}}">{{$dealer->name}} ({{$dealer->unique_id}})</option>

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

						

						

					</select>

					<span class="label label-danger" id="add_retailer_id_error" style="display: none;"></span>

				</div>

			</div>









			<div class="clearfix"></div>



			

			<div class="col-md-6" id='product_div'>

				<div class="form-group">

					<label for="product_id">{{__('messages.Product')}}</label>

					<select class="form-control select2" name="product_id" id="product_id" >

						

						

						<!-- @foreach($products as $product)

						<option value="{{$product->id}}">{{$product->name}}</option>

						@endforeach() -->

						

					</select>

					<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>

				</div>

			</div>







			<div class="col-md-6">

				<div class="form-group">

					<label for="quantity">Quantity (in bags)</label>

					<input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity">

					<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>

				</div>

			</div>





			

			<div class="clearfix"></div>



			<div class="col-md-6">

				<div class="form-group">

					<label for="retailer_address">Retailer Address</label>

					<input type="text" class="form-control" name="retailer_address" id="retailer_address" placeholder="Retailer Address">

					<span class="label label-danger" id="add_retailer_address_error" style="display: none;"></span>

				</div>

			</div>

			<div class="col-md-6">

				<div class="form-group">

					<label for="phone_number">Phone Number</label>

					<input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="phone_number">

					<span class="label label-danger" id="add_phone_number_error" style="display: none;"></span>

				</div>

			</div>

			



			<div class="clearfix"></div>



			<div class="col-md-12">

				<div class="form-group">

					<label for="remark">Remark</label>

					<textarea name="remark" id="remark" class="form-control" rows="3" required="required"></textarea>

					<span class="label label-danger" id="add_remark_error" style="display: none;"></span>

				</div>

			</div>



		</div>

	</div>

	

	<a href="{{URL('/user/generate-token')}}" class="btn btn-default" >Reset</a>

	<button type="button" id="generateTokenBtn" class="btn btn-primary">Submit</button>

</form>



<script type="text/javascript">

$('.select2').select2();



	function get_product_qty(t, name) {

		$('#product_id').html('');

		var warehouse_id = $(t).val();

		var data = {};

		data.warehouse_id = warehouse_id;

		data.name = name;

		data._token = '{{csrf_token()}}';

		var url = window.location.origin+'/user/get-product-qty';

		$.ajax({

			url: url,

			type:'get',

			data: data,

			dataType:'json',

			success:function(response){	



				console.log(response);		

				if(response.success == true) {

					html = '';

					$('#product_id').html(html);





					// $('#product_id').html();

					html+=`<option value="">Product Select</option>`;

					$.each(response.product_qtys, function(key, product){

						let damage_qty='';

						if(product.demage_qty!=''){

							damage_qty=`Damage(${product.demage_qty})   -`;



						}

						// fresh_qty=`Fresh(${product.qty})`;

						html += `<option value="`+product['id']+`">`+product['product_name']+` - Fresh(`+product['qty']+`)  ${damage_qty} `+product['unit']+`</option>`;		

					});								

					$('#product_id').html(html);

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

						html +='<option value="'+val.unique_code+'">'+val.name+' ( '+val.address+' )</option>';

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

					}else{

						swal({

							title: "Success!",

							text: data.message,

							type: "success"

						});

						$('#modalPopup').modal('toggle')

						$('#dynamic-table').DataTable().draw();	

						// window.location.reload();

					}



				}



			});

		});



	});



	function handleTokenType(id){

		if(id==1){

			$("#product_id").val('').change();

			$("#rake_point").val('').change();

			$('#rake_div').show();

			$('#warehouse_div').hide();

			$('#product_id').html();

			

			

		}else if(id==2){

			$("#product_id").val('').change();

			$("#from_warehouse_id").val('').change();

			$('#rake_div').hide();

			$('#warehouse_div').show();

			$('#product_id').html();

			

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

