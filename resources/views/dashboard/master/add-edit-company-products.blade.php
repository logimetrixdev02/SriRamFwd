<form action="" role="form" >
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for="selectComapny">Select Comapany</label>
				<select class="form-control select2" id="selectComapny">
					<option value="0">-- Select Company --</option>
					@foreach($product_companies as $company)
						<option value="{{$company->id}}">{{$company->name}} ({{$company->abbreviation}})</option>
					@endforeach
				</select>
				<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
			</div>
		</div>
	</div>
	@foreach($products as $product)
	<div class="row">
		<div class="col-md-2"><input type="checkbox" name=""/></div>
		<div class="col-md-10">{{$product->name}}</div>
	</div>
	@endforeach

	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8"><input type="submit" name="submit" class="btn btn-sm btn-warning" value="Save"/></div>
		<div class="col-md-2"></div>
	</div>
</form>

