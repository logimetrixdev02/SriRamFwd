@extends('dashboard.layouts.app')
@section('title','Stock Report')
@section('content')
<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">	Monthly Sale Report</li>
			</ul>
		</div>
		<div class="page-content">
			<h3 class="header smaller lighter blue">Monthly Sale Report</h3>


			<div class="row">
				<div class="col-xs-12">

					<form action="" method="POST" role="form">
						<div class="row">
							@csrf
							<div class="col-md-2">
								<div class="form-group">
									<label for="monthly-sale-report">Month</label>
									<select class="form-control select2" name="month_id" id="month_id">
										<option value="">Select Months</option>
										<option value="01">January</option>
										<option value="02">February</option>
										<option value="03">March</option>
										<option value="04">April</option>
										<option value="05">May</option>
										<option value="06">June</option>
										<option value="07">July</option>
										<option value="08">August</option>
										<option value="09">September </option>
										<option value="10">October</option>
										<option value="11">November </option>
										<option value="12">December</option>
									</select>
									
									<span class="label label-danger" style="display: none;"></span>
									
								</div>
								
							</div>
                            
							<div class="col-md-2">
								<div class="form-group">
								<label for="year">Year</label>
								<select class="form-control select2" name="year" id="year_id">
                                <option value="">Select Year</option>
							<option value="2021">2021</option>
							<option value="2022">2022</option>
							<option value="2023">2023</option>
							<option value="2024">2024</option>
							<option value="2025">2025</option>
							<option value="2026">2026</option>
							<option value="2027">2027</option>
							<option value="2028">2028</option>
							<option value="2029">2029</option>
                                 </select>
								</div>
							</div>
							<!-- <div class="col-md-2">
								<div class="form-group input-daterange">
									<label for="master_rake_id">From Date</label>
									 <input type="date" name="from_date"  class="form-control" placeholder="From Date"  readonly />
								
									<span class="label label-danger" style="display: none;"></span>
									
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group input-daterange">
									<label for="master_rake_id">To Date</label>
									 <input type="date" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
								
									<span class="label label-danger" style="display: none;"></span>
									
								</div>
							</div> -->
							
							<div class="col-md-2">
								<div class="form-group">
									 <input type="submit" name="filter" id="filter"  class="btn btn-sm btn-primary" value="Filter" style="margin-top: 20px;">
										
								</div>
							</div>
						</div>
					</form>

					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>

					@if (session('success'))
					<div class="alert alert-success">
						{{ session('success') }}
					</div>
					@endif
					@if (session('error'))
					<div class="alert alert-danger">
						{{ session('error') }}
					</div>
					@endif

					
					<div class="table-header">
						Monthly Sale Report
					</div>

					<div class="table-responsive">
						<div class="dataTables_borderWrap">
							<table id="dynamic-table" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
                                        <th>Dealer/Rake</th>
										<th>Retailer</th>
										<th>Product</th>
										<th>Qty</th>
										<th>Date</th>
									
									

									</tr>
									
								</thead>
								<tbody>
								   
									
									
								</tbody>

								
</table>
</div>
</div>

</div>
</div>

</div>
</div>
</div>


@section('script')
{{ Html::script("assets/js/jquery.dataTables.min.js")}}
{{ Html::script("assets/js/jquery.dataTables.bootstrap.min.js")}}
{{ Html::script("assets/js/dataTables.buttons.min.js")}}
{{ Html::script("assets/js/buttons.flash.min.js")}}
{{ Html::script("assets/js/buttons.html5.min.js")}}
{{ Html::script("assets/js/buttons.print.min.js")}}
{{ Html::script("assets/js/buttons.colVis.min.js")}}
{{ Html::script("assets/js/dataTables.select.min.js")}}
{{ Html::script("assets/js/ace-elements.min.js")}}
{{ Html::script("assets/js/ace.min.js")}}

<script type="text/javascript">
	$(document).ready(function(){
		display();


		$('#filter').click(function(){
				var month_id = $('#month_id').val();
				var year_id = $('#year_id').val();


				if(dealer_id !='' && from_date != '' &&  to_date != '')
				{
					
				$('#dynamic-table').DataTable().destroy();
				display(dealer_id,from_date, to_date);
				}else if(dealer_id!=''){
						$('#dynamic-table').DataTable().destroy();
					display(dealer_id,from_date="", to_date="");
				}else{
					alert('Dealer is required');
				}
		});
		$('#refresh').click(function(){
				$('#from_date').val('');
				$('#to_date').val('');
				$('#dynamic-table').DataTable().destroy();
				display();;
			});

			$('.input-daterange').datepicker({
		
						format:'yyyy-mm-dd',
						autoclose:true
				});
	})

</script>

@endsection
@endsection