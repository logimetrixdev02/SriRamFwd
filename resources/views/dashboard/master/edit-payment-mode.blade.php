<form action="{{URL('/user/edit-payment-mode')}}" role="form" id="editPaymentModeForm">
	<div class="row">
		<input type="hidden" value="{{$payment_mode->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Payment Mode</label>
				<input type="text" class="form-control"  value="{{$payment_mode->payment_mode}}" name="payment_mode" id="payment_mode" placeholder="Payment Mode">
				<span class="label label-danger" id="edit_payment_mode_error" style="display: none;"></span>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editPaymentModeBtn" class="btn btn-primary" onclick="updatePaymentMode()">Submit</button>
</div>
</form>

