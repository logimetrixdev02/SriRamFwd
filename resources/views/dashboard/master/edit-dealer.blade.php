<form action="{{URL('/user/edit-dealer')}}" role="form" id="editDealerForm">
	<div class="row">
		<input type="hidden" value="{{$dealer->id}}" name="id">
		<div class="col-md-4">
							<div class="form-group">
								<label for="name">Dealer Unique Id</label>
								<input type="text" class="form-control" value="{{$dealer->unique_id}}" name="unique_id" id="unique_id" placeholder="Dealer Unique Id">
								<span class="label label-danger" id="edit_unique_id_error" style="display: none;"></span>
							</div>
						</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" class="form-control"  value="{{$dealer->name}}" name="name" id="name" placeholder="Name">
				<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label for="hindi_name">Hindi Name</label>
				<input type="text" class="form-control convertHindi"  value="{{$dealer->hindi_name}}" name="hindi_name" id="hindi_name" placeholder="Hindi Name">
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label for="phone">Phone</label>
				<input type="text" class="form-control" name="phone" id="phone" value="{{$dealer->phone}}"  placeholder="Phone">
				<span class="label label-danger" id="edit_phone_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
					<label for="dealer_id">Location</label>
					<select class="form-control select2" name="location" id="location">
						<option value=""> Select Location</option>
							@foreach($locations as $location)
							<option value="{{$location->location_id}}" {{$dealer->destination_code == $location->location_id ? 'selected':''}}>{{$location->name}} </option>

							@endforeach
					</select>
					<span class="label label-danger" id="edit_location_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="address1">Address</label>
				<input type="text" class="form-control" name="address1" id="address1" value="{{$dealer->address1}}"  placeholder="Address">
				<span class="label label-danger" id="edit_address1_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="hindi_address">Hindi Address 1</label>
				<input type="text" class="form-control convertHindi" name="hindi_address1" id="hindi_address1" placeholder="Hindi Address1" value="{{$dealer->hindi_address1}}">
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label for="address2">Address2</label>
				<input type="text" class="form-control" name="address2" id="address2" value="{{$dealer->address2}}" placeholder="Address2">
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group">
				<label for="hindi_address2">Hindi Address2</label>
				<input type="text" class="form-control convertHindi" name="hindi_address2" id="hindi_address2" placeholder="Hindi Address2">
			</div>
		</div>
		<div class="clearfix"></div>

		

		

		

		<div class="col-md-4">
			<div class="form-group">
				<label for="district">District</label>
				<input type="text" class="form-control" name="district" id="district" value="{{$dealer->district}}" placeholder="District">
				<span class="label label-danger" id="edit_district_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="pin_code">Pin Code</label>
				<input type="text" class="form-control" name="pin_code" id="pin_code" value="{{$dealer->pin_code}}" placeholder="Pin Code">
				<span class="label label-danger" id="edit_pin_code_error" style="display: none;"></span>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group">
				<label for="owner_name">Owner</label>
				<input type="text" class="form-control" name="owner_name" id="owner_name" value="{{$dealer->owner_name}}" placeholder="Owner">
			</div>
		</div>
		<div class="clearfix"></div>
		

		
		<div class="clearfix"></div>
	

		<div class="col-md-4">
			<div class="form-group">
				<label for="mobile_number">Mobile Number</label>
				<input type="text" class="form-control" name="mobile_number" id="mobile_number" value="{{$dealer->mobile_number}}" placeholder="Mobile Number">
				<span class="label label-danger" id="edit_mobile_number_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label for="email">Email</label>
				<input type="text" class="form-control" name="email" id="email" value="{{$dealer->email}}" placeholder="Email">
				<span class="label label-danger" id="edit_email_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="gst_number">GST Number</label>
				<input type="text" class="form-control" name="gst_number" id="gst_number" value="{{$dealer->gst_number}}" placeholder="GST Number">
				<span class="label label-danger" id="edit_gst_number_error" style="display: none;"></span>
			</div>
		</div>
		

		

		

	

		<div class="clearfix"></div>

		<div class="col-md-4">
			<div class="form-group">
				<label for="show_separate_report">Show Separate Report</label>
				<input type="checkbox"  name="show_separate_report" id="show_separate_report" {{$dealer->show_separate_report ? "checked":""}}>
			</div>
		</div>


	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editDealerBtn" class="btn btn-primary" onclick="updateDealer()">Submit</button>
</div>
</form>

<script>
	$(document).ready(function() {

$('.select2').select2({
	allowClear: true
});
	});
</script>