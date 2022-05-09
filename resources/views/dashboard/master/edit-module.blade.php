<form action="{{URL('/user/edit-module')}}" role="form" id="editModuleForm">
	<div class="row">
		<input type="hidden" value="{{$module->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Module</label>
				<input type="text" class="form-control"  value="{{$module->module}}" name="module" id="module" placeholder="Module">
				<span class="label label-danger" id="edit_module_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="link">Link</label>
				<input type="text" class="form-control"  value="{{$module->link}}" name="link" id="link" placeholder="Link">
				<span class="label label-danger" id="add_link_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="icon">Icon</label>
				<input type="text" class="form-control"  value="{{$module->icon}}" name="icon" id="icon" placeholder="Sub Module">
				<span class="label label-danger" id="add_icon_error" style="display: none;"></span>
			</div>
		</div>

	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editModuleBtn" class="btn btn-primary" onclick="updateModule()">Submit</button>
</div>
</form>

