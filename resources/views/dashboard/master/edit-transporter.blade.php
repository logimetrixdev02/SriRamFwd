<form action="{{URL('/user/edit-transporter')}}" role="form" id="editTransporterForm">
	<div class="row">
		<input type="hidden" value="{{$transporter->id}}" name="id">
		<div class="col-md-12">
			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" class="form-control"  value="{{$transporter->name}}" name="name" id="name" placeholder="Name">
				<span class="label label-danger" id="edit_name_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="hindi_name">Hindi Name</label>
				<input type="text" class="form-control convertHindi"  value="{{$transporter->hindi_name}}" name="hindi_name" id="hindi_name" placeholder="Hindi Name">
				<span class="label label-danger" id="edit_hindi_name_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="email">Email</label>
				<input type="text" class="form-control"  value="{{$transporter->email}}" name="email" id="email" placeholder="Email">
				<span class="label label-danger" id="edit_email_error" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label for="phone">Phone</label>
				<input type="text" class="form-control"  value="{{$transporter->phone}}" name="phone" id="phone" placeholder="Phone">
				<span class="label label-danger" id="edit_phone_error" style="display: none;"></span>
			</div>
		</div>

	</div>

	@if($transporter->destination_rates != "")
	@php
	$destination_rates = json_decode($transporter->destination_rates);
	$i = 0;
	@endphp
	@foreach($destination_rates as $destination_rate)
	<div class="row" id="newRow{{$i}}">

		<div class="col-md-6">
			<div class="form-group">
				<label for="destination">Destination</label>
				<input type="text" class="form-control checkIfValid" name="destination[]"  placeholder="Destination" value="{{$destination_rate->destination}}">
				<span class="label label-danger" style="display: none;"></span>
			</div>
		</div>
		<div class="col-md-5">
			<div class="form-group">
				<label for="rate">Rate</label>
				<input type="text" class="form-control checkIfValid" name="rate[]"  placeholder="Rate"  value="{{$destination_rate->rate}}">
				<span class="label label-danger" style="display: none;"></span>
			</div>
		</div>

		<div class="col-md-1">
			<a href="javascript:;" onclick="removeRow({{$i}})"><i class="fa fa-close fa-2x"></i></a>
		</div> 
	</div>
	@php
	$i++;
	@endphp
	@endforeach
	@endif
	<div id="editMoreRowSection">

	</div>

	<div class="pull-left">
		<button type="button" id="editMoreRow" class="btn btn-danger"><i class="fa fa-plus"></i> Add More </button>

	</div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	<button type="button" id="editTransporterBtn" class="btn btn-primary" onclick="updateTransporter()">Submit</button>
</div>
</form>
<script type="text/javascript">
	$(document).ready(function(){

		$('#editMoreRow').click(function(){
			var count = $('.row').length + 1;
			console.log(count);
			var newRow = `<div class="row" id="newRow`+count+`">
			<div class="col-md-6">
			<div class="form-group">
			<label for="destination">Destination</label>
			<input type="text" class="form-control checkIfValid" name="destination[]"  placeholder="Destination">
			<span class="label label-danger" id="add_phone_error" style="display: none;"></span>
			</div>
			</div>
			<div class="col-md-5">
			<div class="form-group">
			<label for="rate">Rate</label>
			<input type="text" class="form-control checkIfValid" name="rate[]"  placeholder="Rate">
			<span class="label label-danger" id="add_phone_error" style="display: none;"></span>
			</div>
			</div>
			<div class="col-md-1">
			<a href="javascript:;" onclick="removeRow(`+count+`)"><i class="fa fa-close fa-2x"></i></a>
			</div>
			</div>
			`;
			$('#editMoreRowSection').append(newRow);
			$('.select2').select2();

		});
	});
</script>
