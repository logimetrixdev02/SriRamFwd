@extends('dashboard.layouts.app')
@section('title','User')

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">{{__('messages.Users')}}</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="page-header">
				<h1>
					  {{__('messages.Users')}} {{__('messages.AddNew')}}
				</h1>
			</div><!-- /.page-header -->

			

			<form action="" role="form" id="AddUserForm">
				<div class="container">
					<div class="row">

						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">{{__('messages.UserForm')}}</h3>
							</div>
							<div class="panel-body">
								<div class="col-md-4">
									<div class="form-group">
										<label for="role">{{__('messages.Roles')}}</label>
										<select class="form-control" name="role" id="role">
											<option value="">{{__('messages.Roles')}} {{__('messages.Select')}}</option>
											@foreach($roles as $role)
											<option value="{{$role->id}}">{{$role->role}}</option>
											@endforeach()
										</select>
										<span class="label label-danger" id="add_role_error" style="display: none;"></span>
									</div>
								</div>

								<div class="clearfix"></div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="name">{{__('messages.Name')}}</label>
										<input type="text" name="name" id="name" class="form-control">
										<span class="label label-danger" id="add_name_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="email">{{__('messages.Email')}}</label>
										<input type="text" name="email" id="email" class="form-control">
										<span class="label label-danger" id="add_email_error" style="display: none;"></span>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="password">{{__('messages.Password')}} </label>
										<input type="password" name="password" id="password" class="form-control">
										<span class="label label-danger" id="add_password_error" style="display: none;"></span>
									</div>
								</div>

								<div class="pull-right">
									<a href="{{URL('/user/add-new-user')}}" class="btn btn-default" >Reset</a>
									<button type="button" id="addUserBtn" class="btn btn-primary">Submit</button>

								</div>
							</div>
						</div>


					</div>
				</div>
				
			</form>
		</div>
	</div><!-- /.page-content -->
</div><!-- /.main-content -->
@section('script')

{{ Html::script("assets/js/ace-elements.min.js")}}
{{ Html::script("assets/js/ace.min.js")}}
{{ Html::script("assets/js/bootstrap-datepicker.min.js")}}


<script type="text/javascript">
	$(document).ready(function() {

		$('#addUserBtn').click(function(e){
			e.preventDefault();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			});
			$.ajax({
				url: $('#AddUserForm').attr('action'),
				method: 'POST',
				data: $('#AddUserForm').serialize(),
				success: function(data){
					if(!data.flag){
						showError('add_name_error',data.errors.name);
						showError('add_email_error',data.errors.email);
						showError('add_role_error',data.errors.role);
						showError('add_password_error',data.errors.password);
					}else{
						swal({
							title: "Success!",
							text: data.message,
							type: "success"
						}, function() {
							window.location.reload();
						});
					}
				}

			});
		});

	});

	function showError(id,error){
		if(typeof(error) === "undefined"){
			$('#'+id).hide();
		}else{
			$('#'+id).show();
			$('#'+id).text(error);
		}
	}

</script>

@endsection
@endsection
