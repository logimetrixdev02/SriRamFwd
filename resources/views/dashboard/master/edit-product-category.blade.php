<form action="{{URL('/user/edit-product-category')}}" role="form" id="editCategoryForm">
	<div class="row">
		<input type="hidden" value="{{$category->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="category">Category</label>
				<input type="text" class="form-control"  value="{{$category->category}}" name="category" id="category" placeholder="Category">
				<span class="label label-danger" id="edit_category_error" style="display: none;"></span>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editCategoryBtn" class="btn btn-primary" onclick="updateCategory()">Submit</button>
</div>
</form>

