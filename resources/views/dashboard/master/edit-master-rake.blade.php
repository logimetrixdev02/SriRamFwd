<form action="{{URL('/user/edit-master-rake')}}" role="form" id="editMasterRakeForm">
	<div class="row">
		<input type="hidden" value="{{$master_rake->id}}" name="id" id="id">
		<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
		<div class="col-md-6">
			<div class="form-group">
				<label for="name">Session</label>
				<select class="form-control" name="session_id" id="edit_session_id">
					<option value="">Select Session</option>
					@foreach($sessions as $session)
					<option value="{{$session->id}}" {{$session->id == $master_rake->session_id? "selected":""}}>{{$session->session}}</option>
					@endforeach()
				</select>
				<span class="label label-danger" id="edit_session_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="name">Product Company</label>
				<select class="form-control" name="product_company_id" id="edit_product_company_id">
					<option value="">Select Product Company</option>
					@foreach($product_companies as $product_company)
					<option value="{{$product_company->id}}" {{$product_company->id == $master_rake->product_company_id? "selected":""}}>{{$product_company->name}}</option>
					@endforeach()
				</select>
				<span class="label label-danger" id="edit_product_company_error" style="display: none;"></span>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="">Placement Time</label>
				<input type="text" class="form-control timepicker" name="loading_time" id="edit_loading_time" placeholder="Replacement Time" value="{{$master_rake->loading_time}}">
				<span class="label label-danger" id="edit_loading_time_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="name">Unloading Time</label>
				<input type="text" class="form-control timepicker" name="unloading_time" id="edit_unloading_time" placeholder="Unloading Time" value="{{$master_rake->unloading_time}}">
				<!-- <span class="label label-danger" id="edit_unloading_time_error" style="display: none;"></span> -->
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="name">Date</label>
				<input type="text" class="form-control date-picker" name="date" id="edit_date" value="{{date('d-m-Y',strtotime($master_rake->date))}}" placeholder="Date">
				<span class="label label-danger" id="edit_date_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="name">RR Document</label>
				<input type="file" class="form-control" name="rr_document" id="edit_rr_document" placeholder="Date">
				<span class="label label-danger" id="add_rr_document_error" style="display: none;"></span>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="demurrage">Demurrage</label>
				<input type="text" class="form-control" name="demurrage" id="edit_demurrage" placeholder="Demurrage" value="{{$master_rake->demurrage}}">
				<span class="label label-danger" id="add_demurrage_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="wharfage">Wharfage</label>
				<input type="text" class="form-control" name="wharfage" id="edit_wharfage" placeholder="wharfage" value="{{$master_rake->wharfage}}">
				<span class="label label-danger" id="add_wharfage_error" style="display: none;"></span>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="cheque_number">Cheque number</label>
				<input type="text" class="form-control" name="cheque_number" id="edit_cheque_number" placeholder="Cheque number" value="{{$master_rake->cheque_number}}">
				<span class="label label-danger" id="add_cheque_number_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="payment_date">Payment date</label>
				<input type="text" class="form-control date-picker" name="payment_date" id="edit_payment_date" placeholder="Payment date" value="{{ $master_rake->payment_date =='0000-00-00' ? '' : date('m-d-Y',strtotime($master_rake->payment_date)) }}">
				<span class="label label-danger" id="add_payment_date_error" style="display: none;"></span>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="rake_point">Rake Point</label>
				<select class="form-control select2 checkIfValid" name="rake_point" id="edit_rake_point">
					<option value="">Select Rake Point</option>
					@foreach($rake_points as $rake_point)
					<option value="{{$rake_point->id}}" {{$rake_point->id == $master_rake->rake_point_id ? "selected":""}}>{{$rake_point->rake_point}}</option>
					@endforeach()
				</select>
				<span class="label label-danger" id="edit_rake_point_error" style="display: none;"></span>
			</div>
		</div>
		<div class="clearfix"></div>

	</div>
	@foreach($master_rake->master_rake_products as $master_rake_product)
	<div class="row">

		<div class="col-md-4">
			<div class="form-group">
				<label for="proruct">Product</label>
				<select class="form-control select2 checkIfValids" name="edit_product_id[]" id="product_id">
					<option value="">Select Product</option>
					@foreach($products as $product)
					<option value="{{$product->id}}" {{$product->id == $master_rake_product->product_id?"selected":""}}>{{$product->name}}</option>
					@endforeach()
				</select>
				<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="rate">Quantity</label>
				<input type="text" class="form-control checkIfValids" name="edit_quantity[]"  placeholder="Quantity" value="{{$master_rake_product->quantity}}">
				<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-2">
			<div class="form-group">
				<label for="rate">Excess Quantity</label>
				<input type="text" class="form-control checkIfValids" name="excess_quantity[]"  placeholder="Quantity" value="{{$master_rake_product->excess_quantity}}">
				<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-2">
			<div class="form-group">
				<label for="rate">Shortage From Company</label>
				<input type="text" class="form-control checkIfValids" name="shortage_from_company[]"  placeholder="Shortage From Company" value="{{$master_rake_product->shortage_from_company}}">
				<span class="label label-danger" id="" style="display: none;"></span>
			</div>
		</div>

	</div>
	@endforeach	
	<div id="editMoreRowSection">

	</div>

	<div class="pull-left">
		<button type="button" id="editMoreRow" class="btn btn-danger"><i class="fa fa-plus"></i> Add More </button>

	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editMasterRakeBtn" class="btn btn-primary">Submit</button>
</div>
</form>


<script>
	function validateFields(){

		var errorCount = 0;
		$('.checkIfValids').each(function(){
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
	$(document).ready(function() {
		$('.select2').select2();
		$('.timepicker').mdtimepicker({
			timeFormat: 'hh:mm:ss.000',
			format: 'h:mm tt',     
			theme: 'blue',       
			readOnly: true,      
			hourPadding: false    
		});

		$('.date-picker').datepicker({
			autoclose: true,
			todayHighlight: true
		});

		$('#editMasterRakeBtn').click(function(e){
			e.preventDefault();

			if(validateFields()){
				return false;
			}else{
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					}
				});
				$('.loading-bg').show();
				var formData = new FormData();
				formData.append('id', $('#id').val());
				formData.append('session_id', $('#edit_session_id option:selected').val());
				formData.append('rake_point', $('#edit_rake_point option:selected').val());
				formData.append('product_company_id', $('#edit_product_company_id option:selected').val());
				formData.append('loading_time', $('#edit_loading_time').val());
				formData.append('date', $('#edit_date').val());
				formData.append('unloading_time', $('#edit_unloading_time').val()) ;

				formData.append('demurrage', $('#edit_demurrage').val()) ;
				formData.append('wharfage', $('#edit_wharfage').val()) ;
				formData.append('cheque_number', $('#edit_cheque_number').val()) ;
				formData.append('payment_date', $('#edit_payment_date').val()) ;

				var product_id = [];
				$("select[name='edit_product_id[]']").each(function() {
					product_id.push($(this).val());
				});

				var quantity = [];
				$("input[name='edit_quantity[]']").each(function() {
					quantity.push($(this).val());
				});

				var quantity = [];
				$("input[name='edit_quantity[]']").each(function() {
					quantity.push($(this).val());
				});

				var excess_quantity = [];
				$("input[name='excess_quantity[]']").each(function() {
					excess_quantity.push($(this).val());
				});

				var shortage_from_company = [];
				$("input[name='shortage_from_company[]']").each(function() {
					shortage_from_company.push($(this).val());
				});

				formData.append('shortage_from_company',  shortage_from_company) ;
				formData.append('product_id',  product_id) ;
				formData.append('quantity',  quantity) ;
				formData.append('excess_quantity',  excess_quantity) ;
				if (typeof $('#edit_rr_document')[0].files[0] !== 'undefined') {
					formData.append('rr_document', $('#edit_rr_document')[0].files[0]);
				}

				$.ajax({
					url: $('#editMasterRakeForm').attr('action'),
					method: 'POST',
					data: formData,
					contentType: false,
					cache: false,
					processData:false,
					success: function(data){
						$('.loading-bg').hide();
						console.log(data);
						if(!data.flag){
							showError('edit_session_error',data.errors.session_id);
							showError('edit_product_company_error',data.errors.product_company_id);
							showError('edit_loading_time_error',data.errors.loading_time);
							showError('edit_date_error',data.errors.date);
							showError('edit_rake_point_error',data.errors.rake_point);
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
			}
		});
	});

	$(document).ready(function(){
		$('#editMoreRow').click(function(){
			var count = $('.row').length + 1;
			console.log(count);
			var newRow = `<div class="row" id="newRow`+count+`">
			<div class="col-md-3">
			<div class="form-group">
			<label for="product">Product</label>
			<select class="form-control select2 checkIfValids" name="edit_product_id[]" id="product_id">
			<option value="">Select Product</option>
			@foreach($products as $product)
			<option value="{{$product->id}}">{{$product->name}}</option>
			@endforeach()
			</select>
			<span class="label label-danger" id="add_product_id_error" style="display: none;"></span>
			</div>
			</div>
			<div class="col-md-3">
			<div class="form-group">
			<label for="rate">Quantity</label>
			<input type="text" class="form-control checkIfValids" name="edit_quantity[]"  placeholder="Quantity">
			<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
			</div>
			</div>
			<div class="col-md-2">
			<div class="form-group">
			<label for="rate">Excess Quantity</label>
			<input type="text" class="form-control checkIfValids" name="excess_quantity[]"  placeholder="Quantity" value="0">
			<span class="label label-danger" id="add_quantity_error" style="display: none;"></span>
			</div>
			</div>
			<div class="col-md-2">
			<div class="form-group">
			<label for="rate">Shortage From Company</label>
			<input type="text" class="form-control checkIfValids" name="shortage_from_company[]"  placeholder="Shortage From Company" value="0">
			<span class="label label-danger" id="" style="display: none;"></span>
			</div>
			</div>

			<div class="col-md-1" style="margin-top: 33px;">
			<a href="javascript:;" onclick="removeRow(`+count+`)"><i class="fa fa-close fa-2x"></i></a>
			</div>
			</div>
			`;
			$('#editMoreRowSection').append(newRow);
			$('.select2').select2();

		});
	});
</script>