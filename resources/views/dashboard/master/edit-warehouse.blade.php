<form action="{{URL('/user/edit-warehouse')}}" role="form" id="editWarehouseForm">
	<div class="row">
		<input type="hidden" value="{{$warehouse->id}}" name="id">

		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" class="form-control"  value="{{$warehouse->name}}" name="name" id="name" placeholder="Name">
				<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="hindi_name">Hindi Name</label>
				<input type="text" class="form-control convertHindi" name="hindi_name" id="hindi_name" value="{{$warehouse->hindi_name}}"  placeholder="Hindi Warehouse Name">
				<span class="label label-danger" id="add_name_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="user_id">Keeper</label>
				<select class="form-control" name="user_id" id="user_id" placeholder="Keeper">
					<option value="">Select keeper</option>
					@foreach($users as $user)
					<option value="{{$user->id}}" {{$user->id == $warehouse->id ? "selected":""}}>{{$user->name}}</option>
					@endforeach()
				</select> 
				<span class="label label-danger" id="edit_user_id_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="location">Location</label>
				<input type="text" class="form-control" value="{{$warehouse->location}}" name="location" id="location" placeholder="Warehouse Location">
				<span class="label label-danger" id="edit_location_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="lng">lngitude</label>
				<input type="text" class="form-control" value="{{$warehouse->lat}}" name="lat" id="lat" placeholder="latitude">
				<span class="label label-danger" id="edit_lat_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<label for="lng">Longitude</label>
				<input type="text" class="form-control" value="{{$warehouse->lng}}" name="lng" id="lng" placeholder="longitude">
				<span class="label label-danger" id="edit_lng_error" style="display: none;"></span>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editSessionBtn" class="btn btn-primary" onclick="updateWarehouse()">Submit</button>
</div>
</form>

