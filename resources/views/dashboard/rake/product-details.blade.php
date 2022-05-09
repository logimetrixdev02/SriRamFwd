<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th> {{__('messages.Product')}}</th>
			<th> {{__('messages.Unit')}}</th>
			<th>  {{__('messages.Alloted')}} {{__('messages.Quantity')}}</th>
			<th> {{__('messages.RemainingQuantity')}}</th>
		</tr>
	</thead>
	<tbody>
		@foreach($allotment->rake_product_allotment_details as $product)
		<tr>
			<td>{{$product->product->name}}</td>
			<td>{{$product->unit->unit}}</td>
			<td>{{$product->alloted_quantity}}</td>
			<td>{{$product->remaining_quantity}}</td>
		</tr>
		@endforeach
	</tbody>
</table>