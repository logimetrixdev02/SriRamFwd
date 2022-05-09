@section('style')
{{ Html::style("//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css")}}
@endsection

<div class="tabbable"> 
	<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4"> 
		<li class="active">
			<a data-toggle="tab" href="#today_sales">Sales</a> 
		</li> 
		<li class="">
			<a data-toggle="tab" href="#today_purchase">Purchase</a>
		</li> 
		<li><input type="text" class="form-control datepicker" value="{{$date}}" placeholder="date" onchange="getSaleAndPurchase(this.value)"></li>
	</ul> 
	<div class="tab-content" id=""> 
		<div id="today_sales" class="tab-pane active"> 
			<table class="table table-bordered table-striped">
				<thead class="thin-border-bottom">
					<tr>
						<th>From Account</th>
						<th>Product</th>
						<th>Quantity</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody>
					@if(count($today_sales)>0)
					@foreach($today_sales as $sale)
					<tr>
						<td>{{$sale['party']}}</td>
						<td>{{$sale['product']}}</td>
						<td>{{$sale['total_sale_quantity']}}</td>
						<td>Rs {{$sale['total_sale_amount']}}</td>
					</tr>
					@endforeach
					@else
					<tr><td colspan="4">No record found</td></tr>
					@endif
				</tbody>
			</table>

		</div> 
		<div id="today_purchase" class="tab-pane"> 
			<table class="table table-bordered table-striped">
				<thead class="thin-border-bottom">
					<tr>
						<th>From Account</th>
						<th>Product</th>
						<th>Quantity</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody>
					@if(count($today_purchase)>0 || count($today_purchase)>0)
					@foreach($today_purchase as $purchase)

					<tr>
						<td>{{$purchase['party']}}</td>
						<td>{{$purchase['product']}}</td>
						<td>{{$purchase['total_purchase_quantity']}}</td>
						<td>Rs {{$purchase['total_purchase_amount']}}</td>
					</tr>
					@endforeach
					@else
					<tr><td colspan="4">No record found</td></tr>
					@endif
				</tbody>
			</table>


		</div> 

	</div> 
</div>

@section('script')
{{ Html::script("/assets/js/jquery-2.1.4.min.js")}}
{{ Html::script("https://code.jquery.com/ui/1.12.1/jquery-ui.js")}}
<script>

	jQuery(function($) {
		$( ".datepicker" ).datepicker({
			onSelect: function(dateText) {
				getSaleAndPurchase(this.value);
			}
		});
	});
</script>

@endsection