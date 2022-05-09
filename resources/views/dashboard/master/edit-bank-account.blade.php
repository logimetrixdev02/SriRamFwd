<form action="{{URL('/user/edit-bank-account')}}" role="form" id="editBankAccountForm">
	<div class="row">
		<input type="hidden" value="{{$bank_account->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="bank_id">Bank</label>
				<select name="bank_id" id="bank_id" class="form-control select2">
					<option value="">Select</option>
					@foreach($banks as $bank)
					<option value="{{$bank->id}}" {{$bank_account->bank_id == $bank->id ? "selected":""}}>{{$bank->name}}</option>
					@endforeach
				</select>
				<span class="label label-danger" id="edit_bank_id_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="bank_branch">Branch</label>
				<input type="text" class="form-control" value="{{$bank_account->bank_branch}}" name="bank_branch" id="bank_branch" placeholder="Branch">

			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="account_number">Account Number</label>
				<input type="text" class="form-control" value="{{$bank_account->account_number}}" name="account_number" id="account_number" placeholder="account_number">
				<span class="label label-danger" id="edit_account_number_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="ifsc_code">ifsc_code</label>
				<input type="text" class="form-control" value="{{$bank_account->ifsc_code}}" name="ifsc_code" id="ifsc_code" placeholder="Ifsc Code">
				<span class="label label-danger" id="edit_ifsc_code_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="account_holder_name">Account Holder Name</label>
				<input type="text" class="form-control" value="{{$bank_account->account_holder_name}}" name="account_holder_name" id="account_holder_name" placeholder="account_holder_name">
				<span class="label label-danger" id="edit_account_holder_name_error" style="display: none;"></span>
			</div>
		</div>



	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editBankAccountBtn" class="btn btn-primary" onclick="updateBankAccount()">Submit</button>
</div>
</form>

