<form action="{{URL('/user/edit-freight')}}" role="form" id="editFreightForm">
	<div class="row">
		<input type="hidden" value="{{$freight->id}}" name="id">
		

		<div class="col-md-12">
			<div class="form-group">
				<label for="district">District</label>
				<input type="text" class="form-control" name="district" value="{{$freight->district}}" id="district" placeholder="District">
				<span class="label label-danger" id="edit_district_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="distance">Distance</label>
				<input type="text" class="form-control" name="distance" value="{{$freight->distance}}" id="distance" placeholder="Distance">
				<span class="label label-danger" id="edit_distance_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="destination">Destination</label>
				<input type="text" class="form-control checkIfValid" name="destination" value="{{$freight->destination}}" id="destination" placeholder="Destination">
				<span class="label label-danger" id="edit_destination_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="freight">Freight</label>
				<input type="text" class="form-control checkIfValid" name="freight" value="{{$freight->freight}}"  placeholder="Freight">
				<span class="label label-danger" id="edit_freight_error" style="display: none;"></span>
			</div>
		</div>

	</div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editFreightBtn" class="btn btn-primary" onclick="updateFreight()">Submit</button>
</div>
</form>
