<form action="{{URL('/user/edit-company')}}" role="form" id="editCompanyForm">
	<div class="row">
		<input type="hidden" value="{{$company->id}}" name="id">
		<div class="col-md-6">
			<div class="form-group">
				<label for="name">Name *</label>
				<input type="text" class="form-control"  value="{{$company->name}}" name="name" id="name" placeholder="Name">
				<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="hindi_name">Hindi Name</label>
				<input type="text" class="form-control convertHindi" name="hindi_name" value="{{$company->hindi_name}}" id="hindi_name" placeholder="Hindi Name">
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="address1">Address Line 1 *</label>
				<input type="text" class="form-control" value="{{$company->address1}}" name="address1" id="address1" placeholder="Address Line 1">
				<span class="label label-danger" id="edit_address1_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="hindi_address1">Hindi Address Line 1 </label>
				<input type="text" class="form-control convertHindi" name="hindi_address1" value="{{$company->hindi_address1}}" id="hindi_address1" placeholder="Hindi Address Line 1">
			</div>
		</div>
		<div class="clearfix"></div>


		<div class="col-md-6">
			<div class="form-group">
				<label for="address2">Address Line 2</label>
				<input type="text" class="form-control" value="{{$company->address2}}" name="address2" id="address2" placeholder="Address Line 2">
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="hindi_address2">Hindi Address Line 2 </label>
				<input type="text" class="form-control convertHindi" name="hindi_address2" id="hindi_address2" value="{{$company->hindi_address2}}" placeholder="Hindi Address Line 2">
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="city">City *</label>
				<input type="text" class="form-control" value="{{$company->city}}" name="city" id="city" placeholder="City">
				<span class="label label-danger" id="edit_city_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="hindi_city">Hindi City </label>
				<input type="text" class="form-control convertHindi" name="hindi_city" id="hindi_city" value="{{$company->hindi_city}}" placeholder="Hindi City">
			</div>
		</div>
		<div class="clearfix"></div>


		<div class="col-md-6">
			<div class="form-group">
				<label for="phone">Phone *</label>
				<input type="text" class="form-control" value="{{$company->phone}}" name="phone" id="phone" placeholder="Phone">
				<span class="label label-danger" id="edit_phone_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="email">Email</label>
				<input type="text" class="form-control" value="{{$company->email}}" name="email" id="email" placeholder="Email">
				<span class="label label-danger" id="edit_email_error" style="display: none;"></span>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="gst_no">GST Number *</label>
				<input type="text" class="form-control" value="{{$company->gst_no}}" name="gst_no" id="gst_no" placeholder="GST Number">
				<span class="label label-danger" id="gst_no_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="pan_number">PAN Number *</label>
				<input type="text" class="form-control" name="pan_number" id="pan_number" value="{{$company->pan_number}}" placeholder="PAN Number">
				<span class="label label-danger" id="edit_pan_no_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="bank_name">Bank Name *</label>
				<input type="text" class="form-control" name="bank_name" id="bank_name" value="{{$company->bank_name}}" placeholder="Bank Name">
				<span class="label label-danger" id="edit_bank_name_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="bank_branch_name">Bank Branch *</label>
				<input type="text" class="form-control" name="bank_branch_name" id="bank_branch_name" value="{{$company->bank_branch_name}}" placeholder="Bank Branch">
				<span class="label label-danger" id="edit_bank_branch_name_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="acc_number">Account Number *</label>
				<input type="text" class="form-control" name="acc_number" id="acc_number" value="{{$company->bank_account_number}}" placeholder="Account Number">
				<span class="label label-danger" id="edit_acc_number_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="bank_ifs_code">Bank IFS Code *</label>
				<input type="text" class="form-control" name="bank_ifs_code" id="bank_ifs_code" value="{{$company->bank_ifsc_code}}" placeholder="Bank IFS Code">
				<span class="label label-danger" id="edit_bank_ifs_code_error" style="display: none;"></span>
			</div>
		</div>


		<div class="col-md-6">
			<div class="form-group">
				<label for="is_rate_mandatory">is Rate Mandatory?</label>
				<input type="checkbox"  name="is_rate_mandatory" id="is_rate_mandatory" {{$company->is_rate_mandatory ? "checked":""}}>
				<span class="label label-danger" id="edit_is_rate_mandatory_error" style="display: none;"></span>
			</div>
		</div>


		<div class="col-md-6">
			<div class="form-group">
				<label for="for_invoice">Using For Invoice?</label>
				<input type="checkbox"  name="for_invoice" id="for_invoice" {{$company->for_invoice ? "checked":""}}>
				<span class="label label-danger" id="edit_for_invoice_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="token_abbreviation">Token Abbreviation* </label>
				<input type="text" class="form-control" name="token_abbreviation" id="token_abbreviation" value="{{$company->token_abbreviation}}">
				<span class="label label-danger" id="edit_token_abbreviation_error" style="display: none;"></span>
			</div>
		</div>

	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editCompanyBtn" class="btn btn-primary" onclick="updateCompany()">Submit</button>
</div>
</form>
