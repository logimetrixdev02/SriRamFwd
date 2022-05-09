<form action="{{URL('/user/edit-role')}}" role="form" id="editRoleForm">
	<div class="row">
		<input type="hidden" value="{{$role->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Role</label>
				<input type="text" class="form-control"  value="{{$role->role}}" name="role" id="role" placeholder="Role">
				<span class="label label-danger" id="edit_role_error" style="display: none;"></span>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editRoleBtn" class="btn btn-primary" onclick="updateRole()">Submit</button>
</div>
</form>

