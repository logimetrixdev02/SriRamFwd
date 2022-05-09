<form action="{{URL('/user/edit-location')}}" role="form" id="editLocationForm">
	<div class="row">
		<input type="hidden" value="{{$location->id}}" name="id">
		<div class="col-md-12">
            <div class="form-group">
                <label for="name">Location Id</label>
                <input type="text" class="form-control" value="{{ $location->location_id }}" name="location_id" id="location_id" placeholder="Location ID">
                <span class="label label-danger" id="edit_location_id_error" style="display: none;"></span>
            </div>
			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" class="form-control"  value="{{$location->name}}" name="name" id="name" placeholder="Name">
				<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="hindi_name">District</label>
				<input type="text" class="form-control" value="{{ $location->district }}" name="district" id="district" placeholder="District">
                <span class="label label-danger" id="edit_district_error" style="display: none;"></span>
			</div>
		</div>

	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="button" id="editCompanyBtn" class="btn btn-primary" onclick="updateLocation()">Submit</button>
	</div>
</form>

