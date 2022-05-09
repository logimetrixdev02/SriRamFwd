<form action="{{URL('/user/edit-client')}}" role="form" id="editClientForm">
	<div class="row">
		<input type="hidden" value="{{$client->id}}" name="id">

		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" class="form-control"  value="{{$client->name}}" name="name" id="name" placeholder="Name">
				<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editCompanyBtn" class="btn btn-primary" onclick="updateClient()">Submit</button>
</div>
</form>

