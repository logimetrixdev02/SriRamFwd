@extends('dashboard.layouts.app')
@section('title','Custom SMS Report')
@section('content')
<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">Custom SMS</li>
			</ul>
		</div>
		<div class="page-content">
			@if($messageDelivered)
			<div class="alert alert-success">
				<strong>Success!</strong>{{$messageDelivered}}
			</div>
			@endif
			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Custom SMS</h3>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">

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

					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Custom Messages Can be Send
					</div>
					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<form action="" method="POST" role="form">
						{{csrf_field()}}
						<div class="col-md-4">
							<div class="form-group">
								<label for="mobile_number">Mobile Number</label>
								<input type="text"  class="form-control" name="mobile_number">
								@if ($errors->has('mobile_number'))
								<span class="label label-danger">{{ $errors->first('mobile_number') }}</span>
								@endif
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="message">Messages</label>
								<textarea rows="3" class="form-control" name="message"></textarea>
								@if ($errors->has('message'))
								<span class="label label-danger">{{ $errors->first('message') }}</span>
								@endif
							</div>
						</div></br>

						<div class="col-md-3">
							<div class="form-group">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</div>

					</form>

				</div>
			</div>
		</div><!-- /.page-content -->
	</div><!---/.main-content-inner-->
</div><!---/.main-content-->

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

</script>

@endsection
@endsection