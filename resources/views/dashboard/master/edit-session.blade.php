<form action="{{URL('/user/edit-session')}}" role="form" id="editSessionForm">
	<div class="row">
		<input type="hidden" value="{{$session->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Session</label>
				<input type="text" class="form-control"  value="{{$session->session}}" name="session" id="session" placeholder="Name">
				<span class="label label-danger" id="edit_session_error" style="display: none;"></span>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editSessionBtn" class="btn btn-primary" onclick="updateSession()">Submit</button>
</div>
</form>

