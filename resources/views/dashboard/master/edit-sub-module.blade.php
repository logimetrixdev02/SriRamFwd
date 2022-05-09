<form action="{{URL('/user/edit-sub-module')}}" role="form" id="editSubModuleForm">
	<div class="row">
		<input type="hidden" value="{{$sub_module->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="module_id">Module</label>
				<select class="form-control" name="module_id">
					<option value="">Select Module</option>
					@foreach($modules as $module)
					<option value="{{$module->id}}" {{$module->id == $sub_module->module_id  ? "selected":""}}>{{$module->module}}</option>
					@endforeach
				</select>
				<span class="label label-danger" id="add_module_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="sub_module">Sub-Module</label>
				<input type="text" class="form-control" value="{{$sub_module->sub_module}}" name="sub_module" id="sub_module" placeholder="Sub Module">
				<span class="label label-danger" id="add_sub_module_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="link">Link</label>
				<input type="text" class="form-control" name="link" value="{{$sub_module->link}}" id="link" placeholder="Link">
				<span class="label label-danger" id="add_link_error" style="display: none;"></span>
			</div>
		</div>

	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editSubModuleBtn" class="btn btn-primary" onclick="updateSubModule()">Submit</button>
</div>
</form>

