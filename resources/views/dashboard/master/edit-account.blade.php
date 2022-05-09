<form action="{{URL('/user/edit-account')}}" role="form" id="editAccountForm">
	<div class="row">
		<input type="hidden" value="{{$account->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" class="form-control"  value="{{$account->name}}" name="name" id="name" placeholder="Name">
				<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="email">Email</label>
				<input type="text" class="form-control"  value="{{$account->email}}" name="email" id="email" placeholder="Email">
				<span class="label label-danger" id="edit_email_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="phone">Phone</label>
				<input type="text" class="form-control"  value="{{$account->phone}}" name="phone" id="phone" placeholder="Phone">
				<span class="label label-danger" id="edit_phone_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="address">Address</label>
				<input type="text" class="form-control" name="address" id="address" value="{{$account->address}}"  placeholder="Address">
				<span class="label label-danger" id="add_address_error" style="display: none;"></span>
			</div>
		</div>


		<div class="col-md-12">
			<div class="form-group">
				<label for="gst_no">GST Number</label>
				<input type="text" class="form-control" name="gst_no" id="gst_no" value="{{$account->gst_no}}"  placeholder="GST Number">
				<span class="label label-danger" id="add_gst_no_error" style="display: none;"></span>
			</div>
		</div>


	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editAccountBtn" class="btn btn-primary" onclick="updateAccount()">Submit</button>
</div>
</form>

