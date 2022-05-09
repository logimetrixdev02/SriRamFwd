<form action="{{URL('/user/edit-product')}}" role="form" id="editProductForm">
	<input type="hidden" value="{{$product->id}}" name="id">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="name">Product Name</label>
				<input type="text" class="form-control"  value="{{$product->name}}" name="name" id="edit_name" placeholder="Name">
				<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="hsn_code">HSN Code</label>
				<input type="text" class="form-control"  value="{{$product->hsn_code}}" name="hsn_code" id="edit_hsn_code" placeholder="HSN Code">
				<span class="label label-danger" id="edit_hsn_code_error" style="display: none;"></span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="gst_slab">GST Slab (%)</label>
				<input type="text" class="form-control"  value="{{$product->gst_slab}}" name="gst_slab" id="edit_gst_slab" placeholder="GST Slab" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
				<span class="label label-danger" id="edit_gst_slab_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="igst">IGST (%)</label>
				<input type="text" class="form-control"  value="{{$product->igst}}" name="igst" id="edit_igst" placeholder="IGST" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
				<span class="label label-danger" id="edit_igst_error" style="display: none;"></span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="cgst">CGST (%)</label>
				<input type="text" class="form-control"  value="{{$product->cgst}}" name="cgst" id="edit_cgst" placeholder="CGST" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
				<span class="label label-danger" id="edit_cgst_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="sgst">SGST (%)</label>
				<input type="text" class="form-control"  value="{{$product->sgst}}" name="sgst" id="edit_sgst" placeholder="SGST" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
				<span class="label label-danger" id="edit_sgst_error" style="display: none;"></span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="invoice_type">Invoice Type</label>
				<select name="invoice_type" id="invoice_type" class="form-control">
					<option value="">Select Invoice Type</option>
					@foreach($invoice_types as $invoice_type)
					<option value="{{$invoice_type->id}}" {{$product->invoice_type_id == $invoice_type->id ? "selected":""}}>{{$invoice_type->invoice_type}}</option>
					@endforeach
				</select>
				<span class="label label-danger" id="edit_invoice_type_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="product_category">Product Categories</label>
				<select name="product_category" id="product_category" class="form-control">
					@foreach($product_categories  as  $product_cat)
					<option value="{{$product_cat->id}}" {{$product_cat->id == $product->product_category_id ? "selected":""}}>{{$product_cat->category}}</option>
					@endforeach
				</select>
				<span class="label label-danger" id="edit_product_category_error" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="weight_in_kg">Weight (in kg)</label>
				<input type="text" class="form-control"  value="{{$product->weight_in_kg}}" name="weight_in_kg" id="edit_weight_in_kg" placeholder="Weight" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
				<span class="label label-danger" id="edit_weight_in_kg_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="hindi_name">Hindi Name</label>
				<input type="text" class="form-control convertHindi" value="{{$product->hindi_name}}" name="hindi_name" id="hindi_name" placeholder="Product Name">
			</div>
		</div>

	</div>

	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="button" id="editCompanyBtn" class="btn btn-primary" onclick="updateProduct()">Submit</button>
	</div>
</form>

