<form action="{{URL('/user/edit-bank')}}" role="form" id="editBankForm">
	<div class="row">
		<input type="hidden" value="{{$bank->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Bank</label>
				<input type="text" class="form-control"  value="{{$bank->name}}" name="name" id="name" placeholder="Bank">
				<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editBankBtn" class="btn btn-primary" onclick="updateBank()">Submit</button>
</div>
</form>

