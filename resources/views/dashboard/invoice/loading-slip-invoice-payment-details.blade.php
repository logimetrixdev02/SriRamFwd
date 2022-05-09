<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>Amount</th>
			<th>Payment Date</th>
			<th>Bank</th>
			<th>Bank Account Number</th>
			<th>Bank Reference Number</th>
			<th>Payment Mode</th>
		</tr>
	</thead>
	<tbody>
		@foreach($payments as $payment)
		<tr>
			<td>{{$payment->payment_amount}}</td>
			<td>{{date('d-m-Y',strtotime($payment->payment_date))}}</td>
			<td>{{$payment->bank_account->bank->name}}</td>
			<td>{{$payment->bank_account->account_number}}</td>
			<td>{{$payment->bank_reference_number}}</td>
			<td>{{$payment->payment_mode}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
