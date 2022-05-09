<form action="{{URL('/user/edit-rake-point')}}" role="form" id="editRakePointForm">
	<div class="row">
		<input type="hidden" value="{{$rake_point->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Rake Point</label>
				<input type="text" class="form-control"  value="{{$rake_point->rake_point}}" name="rake_point" id="rake_point" placeholder="Rake Point">
				<span class="label label-danger" id="edit_role_error" style="display: none;"></span>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editRakePointBtn" class="btn btn-primary" onclick="updateRakePoint()">Submit</button>
</div>
</form>

