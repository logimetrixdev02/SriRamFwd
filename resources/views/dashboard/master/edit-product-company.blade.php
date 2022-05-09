<form action="{{URL('/user/edit-product-company')}}" role="form" id="editCompanyForm">
	<div class="row">
		<input type="hidden" value="{{$company->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" class="form-control"  value="{{$company->name}}" name="name" id="name" placeholder="Name">
				<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="hindi_name">Hindi Name</label>
				<input type="text" class="form-control convertHindi" name="hindi_name" id="hindi_name" placeholder="Hindi Name">
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="abbreviation">Abbreviation</label>
				<input type="text" class="form-control" name="abbreviation" id="abbreviation" value="{{$company->abbreviation}}" placeholder="Abbreviation">
				<span class="label label-danger" id="edit_abbreviation_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="brand_name">Brand Name</label>
				<input type="text" class="form-control" name="brand_name" id="brand_name" value="{{$company->brand_name}}" placeholder="Brand Name">
				<span class="label label-danger" id="edit_brand_name_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="hindi_brand_name">Hindi Brand Name</label>
				<input type="text" class="form-control convertHindi" name="hindi_brand_name" id="hindi_brand_name" placeholder="Hindi Brand Name">
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="address">Address</label>
				<input type="text" class="form-control" name="address" id="address" value="{{$company->address}}" placeholder="Address">
				<span class="label label-danger" id="edit_address_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="hindi_address">Hindi Address</label>
				<input type="text" class="form-control convertHindi" name="hindi_address" id="hindi_address" placeholder="Hindi Address">
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="state">State</label>
				<input type="text" class="form-control" name="state" id="state" value="{{$company->state}}" placeholder="State">
				<span class="label label-danger" id="edit_state_error" style="display: none;"></span>
			</div>
		</div>


		<div class="col-md-12">
			<div class="form-group">
				<label for="gst_no">GST Number</label>
				<input type="text" class="form-control" name="gst_no" id="gst_no" value="{{$company->gst_no}}" placeholder="GST Number">
				<span class="label label-danger" id="edit_gst_no_error" style="display: none;"></span>
			</div>
		</div>


	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="button" id="editCompanyBtn" class="btn btn-primary" onclick="updateCompany()">Submit</button>
	</div>
</form>

