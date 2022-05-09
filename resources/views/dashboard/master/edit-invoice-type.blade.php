<form action="{{URL('/user/edit-invoice-type')}}" role="form" id="editInvoiceTypeForm">
	<div class="row">
		<input type="hidden" value="{{$invoice_type->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Invoice Type</label>
				<input type="text" class="form-control"  value="{{$invoice_type->invoice_type}}" name="invoice_type" id="invoice_type" placeholder="Invoice Type">
				<span class="label label-danger" id="edit_invoice_type_error" style="display: none;"></span>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editInvoiceTypeBtn" class="btn btn-primary" onclick="updateInvoiceType()">Submit</button>
</div>
</form>

