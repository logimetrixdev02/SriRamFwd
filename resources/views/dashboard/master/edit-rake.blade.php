<form action="{{URL('/user/edit-rake')}}" role="form" id="editRakeForm">
	<div class="row">
		<input type="hidden" value="{{$rake->id}}" name="id">
		<div class="col-md-4">
			<div class="form-group">
				<label for="name">Master Rake</label>
				<select class="form-control" name="master_rake_id" id="master_rake_id" onchange="getMasterDetails(this.value,'edit_')">
					<option value="">Select Master Rake</option>
					@foreach($master_rakes as $master_rake)
					<option value="{{$master_rake->id}}" {{$master_rake->id == $rake->master_rake_id ? "selected":""}}>{{$master_rake->name}}</option>
					@endforeach()
				</select>
				<span class="label label-danger" id="edit_master_rake_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label for="name">Session</label>
				<select class="form-control" name="session" id="edit_session">
					<option value="{{ getModelById('Session',$rake->master_rake->session_id)->id }}">{{ getModelById('Session',$rake->master_rake->session_id)->session }}</option>
				</select>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label for="name">Product Company</label>
				<select class="form-control" name="product_company" id="edit_product_company_id">
					<option value="{{ getModelById('ProductCompany',$rake->master_rake->product_company_id)->id }}">{{ getModelById('ProductCompany',$rake->master_rake->product_company_id)->name }}</option>
				</select>
			</div>
		</div>


		<div class="clearfix"></div>

		<div class="col-md-4">
			<div class="form-group">
				<label for="name">Loading Time</label>
				<input type="text" class="form-control" name="loading_time" id="edit_loading_time" placeholder="Loading Time" value="{{$rake->master_rake->loading_time}}">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="name">Unloading Time</label>
				<input type="text" class="form-control" name="unloading_time" id="edit_unloading_time" placeholder="Unloading Time" value="{{$rake->master_rake->unloading_time}}">
			</div>
		</div>


		<div class="col-md-4">
			<div class="form-group">
				<label for="name">Date</label>
				<input type="text" class="form-control" name="date" id="edit_date" placeholder="Date" value="{{$rake->master_rake->date}}">
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="col-md-4">
			<div class="form-group">
				<label for="name">Quantity</label>
				<input type="text" class="form-control" name="quantity" id="quantity" value="{{$rake->quantity}}" placeholder="Quantity">
				<span class="label label-danger" id="edit_quantity_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="name">Product</label>
				<select class="form-control" name="product_id" id="product_id">
					<option value="">Select Product</option>
					@foreach($products as $product)
					<option value="{{$product->id}}" {{$product->id == $rake->product_id ? "selected":""}}>{{$product->name}}</option>
					@endforeach()
				</select>
				<span class="label label-danger" id="edit_product_error" style="display: none;"></span>
			</div>
		</div>

	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editRakeBtn" class="btn btn-primary" onclick="updateRake()">Submit</button>
</div>
</form>