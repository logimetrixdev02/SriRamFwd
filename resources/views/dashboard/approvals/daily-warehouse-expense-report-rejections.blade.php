<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Rejected By</th>
			<th>Reason</th>
			<th>Reject at</th>
		</tr>
	</thead>
	<tbody>
		@foreach($report_rejections as $report_rejection)
		<tr>
			<td>{{getModelById('User',$report_rejection->rejected_by)->name}}</td>
			<td>{{$report_rejection->reason}}</td>
			<td>{{date('d-m-Y H:i',strtotime($report_rejection->created_at))}}</td>
		</tr>
		@endforeach
	</tbody>
</table>