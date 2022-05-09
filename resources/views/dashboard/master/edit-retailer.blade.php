<form action="{{URL('/user/edit-retailer')}}" role="form" id="editRetailerForm">
	<div class="row">
		<input type="hidden" value="{{$retailer->id}}" name="id">
		<div class="col-md-6">
							<div class="form-group">
								<label for="name"> Retailer Unique Code</label>
								<input type="text" class="form-control" value="{{$retailer->unique_code}}" name="unique_code" id="unique_code" placeholder="Retailer Unique Code"> 
								<span class="label label-danger" id="edit_unique_code_error" style="display: none;"></span>
							</div>
						</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" class="form-control"  value="{{$retailer->name}}" name="name" id="name" placeholder="Name">
				<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="hindi_name">Hindi Name</label>
				<input type="text" class="form-control convertHindi" value="{{$retailer->hindi_name}}" name="hindi_name" id="hindi_name" placeholder="Hindi name">

			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="phone">Phone</label>
				<input type="text" class="form-control" name="mobile_number" id="mobile_number" value="{{$retailer->mobile_number}}"  placeholder="Phone">
				<span class="label label-danger" id="edit_mobile_number_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="phone">GSTIN</label>
				<input type="text" class="form-control" name="gst_number" id="gst_number" value="{{$retailer->gst_number}}" placeholder="GSTIN">
				<span class="label label-danger" id="edit_gst_number_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
					<label for="dealer_id">Location</label>
					<select class="form-control select2" name="location" id="location">
						<option value=""> Select Location</option>
							@foreach($locations as $location)
							<option value="{{$location->location_id}}" {{$retailer->destination_code == $location->location_id ? 'selected':''}}>{{$location->name}} </option>

							@endforeach
					</select>
					<span class="label label-danger" id="edit_location_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-6">
							<div class="form-group">
								<label for="district">District</label>
								<input type="text" class="form-control" name="district" id="district" value="{{$retailer->district}}" placeholder="District">
								<span class="label label-danger" id="edit_district_error" style="display: none;"></span>
							</div>
						</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="address">Address</label>
				<input type="text" class="form-control" name="address" id="address" value="{{$retailer->address}}"  placeholder="Address">
				<span class="label label-danger" id="edit_address_error" style="display: none;"></span>
			</div>
		</div>
<div class="col-md-6">
			<div class="form-group">
				<label for="address">Address2</label>
				<input type="text" class="form-control" name="address2" id="address2" value="{{$retailer->address2}}"  placeholder="Address2">
				<span class="label label-danger" id="edit_address_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="hindi_address">Hindi address</label>
				<input type="text" class="form-control convertHindi" name="hindi_address" id="hindi_address" placeholder="Hindi address" value="{{$retailer->hindi_address}}">
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="dealer_id">Select Dealer</label>
				<select class="form-control select2" name="dealer_id" id="dealer_id">
					<option value=""> Select Deader</option>
					@foreach($dealers as $dealer)
					<option value="{{$dealer->unique_id}}" {{$retailer->dealer_id == $dealer->unique_id ? 'selected':''}}>{{$dealer->name}} ( {{$dealer->unique_id}} )</option>
					@endforeach
				</select>
				<span class="label label-danger" id="edit_dealer_id_error" style="display: none;"></span>
			</div>
		</div>

	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editCompanyBtn" class="btn btn-primary" onclick="updateRetailer()">Submit</button>
</div>
</form>

<script>
	$(document).ready(function() {

$('.select2').select2({
	allowClear: true
});
	});
</script>