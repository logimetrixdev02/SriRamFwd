<!DOCTYPE html>

<html lang="en">

<head>

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<meta charset="UTF-8">

	<title>SAROJ | @yield('title') </title>



	<meta name="description" content="overview &amp; stats" />

	<meta name="_token" content="{{ csrf_token() }}" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<link rel="icon" type="image/png"  sizes="160x160" href="{{url('/assets/images/favicon.ico')}}"/>




	{{Html::style("assets/css/bootstrap.min.css")}}

	{{Html::style("assets/font-awesome/4.5.0/css/font-awesome.min.css")}}

	{{Html::style("assets/css/fonts.googleapis.com.css")}}

	{{Html::style("assets/css/ace.min.css")}}

	{{Html::style("assets/css/sweetalert.css")}}

	{{Html::style("assets/css/select2.min.css")}}

	{{ Html::style("assets/css/mdtimepicker.min.css")}}



    <!--[if lte IE 9]>

      {{Html::style("assets/css/ace-part2.min.css")}}

  <![endif]-->

  {{Html::style("assets/css/ace-skins.min.css")}}

  {{Html::style("assets/css/ace-rtl.min.css")}}



    <!--[if lte IE 9]>

      {{Html::style("assets/css/ace-ie.min.css")}}

  <![endif]-->

  {{ Html::script("assets/js/ace-extra.min.js")}}

    <!--[if lte IE 8]>

    {{ Html::script("assets/js/html5shiv.min.js")}}

    {{ Html::script("assets/js/respond.min.js")}}

<![endif]-->



@yield('style')



<style type="text/css">



	body {

		cursor: url("/assets/images/pointer.png"), auto;

	}

	.sidebar.menu-min, .sidebar.menu-min.compact, .sidebar.menu-min.navbar-collapse{

	    width: 43px !important;

	}

	.select2 {

		width:100%!important;

	}

	

	.company_name{

		color: #fff;

		position: absolute;

		width: 100%;

		left: 0;

		text-align: center;

		margin:0 auto;

		font-size: 25px;

	}

	.loading-bg {

		background: #4444447a;

		top: 0;

		bottom: 0;

		left: 0;

		right: 0;

		margin: 0px auto;

		z-index: 10053;

		position: fixed;

	}

	.loading {

		/*border: 16px solid #f3f3f3;*/

		/*border-radius: 50%;*/

		/*border-top: 16px solid #3498db;*/

		width: 40px;

		height: 150px;

		/*background-image: "";*/

     /* -webkit-animation: spin 2s linear infinite;

     animation: spin 2s linear infinite;*/

     position: absolute;

     left: 0;

     right: 280px;

     margin: 0px auto;

     top: 10%;

 }



 .selectCompanyBg {

 	background: #44444473;

 	top: 0;

 	bottom: 0;

 	left: 0;

 	right: 0;

 	margin: 0px auto;

 	z-index: 10053;

 	position: fixed;

 }



 @-webkit-keyframes spin {

 	0% { -webkit-transform: rotate(0deg); }

 	100% { -webkit-transform: rotate(360deg); }

 }



 @keyframes spin {

 	0% { transform: rotate(0deg); }

 	100% { transform: rotate(360deg); }

 }







 .float{

 	position:fixed;

 	width:60px;

 	height:60px;

 	bottom:40px;

 	right:40px;

 	background-color:#044fab;

 	color:#FFF;

 	border-radius:50px;

 	text-align:center;

 	box-shadow: 2px 2px 3px #020202;

 }



 .my-float{

 	margin-top:22px;

 } 

 .my-float:hover{

 	color:#FFF;

 } 

</style>

</head>



<body class="skin-1">

	<div class="loading-bg" style="display: none;">  

		<div class="loading">

			<img src="{{url('/assets/images/loader5.gif')}}">

		</div>

	</div>

	@php

	$acting_company = Session::get('acting_company');

	$language = Session::get('language',Config::get('app.locale'));

	@endphp

	<div class="selectCompanyBg" style="display: {{is_null($acting_company) ? "block":"none"}};">  

		<div class="modal-dialog modal-sm">

			<div class="modal-body">

				<div class="panel panel-primary">

					<div class="panel-heading clearfix">

						<h4 class="panel-title">Select Company</h4> 

					</div>

					<div class="panel-body">

						@php

						$companies = \App\Company::where('is_active',1)->get();

						@endphp

						<form action="{{URL('set-company')}}" method="POST" role="form" id="setCompanyForm">

							<div class="form-group">

								<label for="act_as_company">Company</label>

								<select class="form-control" name="act_as_company" id="act_as_company">

									<option value="">--Select Company--</option>

									@foreach($companies as $company)

									<option value="{{$company->id}}" {{$acting_company == $company->id ? "selected":""}}>{{$company->name}}</option>

									@endforeach

								</select>

								<span class="label label-danger" id="set_company_id_error" style="display: none;"></span>

							</div>



						</div>

						<div class="modal-footer">

							<button type="submit" id="setCompany" class="btn btn-primary">Save</button>

						</div>

					</form>

				</div>

			</div>



		</div>



	</div>





	<div id="navbar" class="navbar navbar-default">

		<div class="navbar-container ace-save-state" id="navbar-container">

			<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">

				<span class="sr-only">Toggle sidebar</span>



				<span class="icon-bar"></span>



				<span class="icon-bar"></span>



				<span class="icon-bar"></span>

			</button>



			<div class="navbar-header pull-left">

				<a href="#" class="navbar-brand">

				<!-- 	<small>

						<i class="fa fa-train"></i>

						SAROJ

					</small> -->
					 <img src="{{url('/assets/images/slogo (2).png')}}"
					 style="height: 26px;"
                                  />

				</a>

			</div>



			<div class="company_name">

				{{ is_null($acting_company) ? "":getModelById('company',$acting_company)->name }}

			</div>



			<div class="navbar-buttons navbar-header pull-right" role="navigation">

				<ul class="nav ace-nav">

					<li class="grey dropdown-modal">

						<a data-toggle="dropdown" class="dropdown-toggle" href="#">

							<i class="ace-icon fa fa-tasks"></i>

							<span class="badge badge-grey">4</span>

						</a>



						<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">

							<li class="dropdown-header">

								<i class="ace-icon fa fa-check"></i>

								4 Tasks to complete

							</li>



							<li class="dropdown-content">

								<ul class="dropdown-menu dropdown-navbar">

									<li>

										<a href="#">

											<div class="clearfix">

												<span class="pull-left">Software Update</span>

												<span class="pull-right">65%</span>

											</div>



											<div class="progress progress-mini">

												<div style="width:65%" class="progress-bar"></div>

											</div>

										</a>

									</li>



									<li>

										<a href="#">

											<div class="clearfix">

												<span class="pull-left">Hardware Upgrade</span>

												<span class="pull-right">35%</span>

											</div>



											<div class="progress progress-mini">

												<div style="width:35%" class="progress-bar progress-bar-danger"></div>

											</div>

										</a>

									</li>



									<li>

										<a href="#">

											<div class="clearfix">

												<span class="pull-left">Unit Testing</span>

												<span class="pull-right">15%</span>

											</div>



											<div class="progress progress-mini">

												<div style="width:15%" class="progress-bar progress-bar-warning"></div>

											</div>

										</a>

									</li>



									<li>

										<a href="#">

											<div class="clearfix">

												<span class="pull-left">Bug Fixes</span>

												<span class="pull-right">90%</span>

											</div>



											<div class="progress progress-mini progress-striped active">

												<div style="width:90%" class="progress-bar progress-bar-success"></div>

											</div>

										</a>

									</li>

								</ul>

							</li>



							<li class="dropdown-footer">

								<a href="#">

									See tasks with details

									<i class="ace-icon fa fa-arrow-right"></i>

								</a>

							</li>

						</ul>

					</li>



					<li class="purple dropdown-modal">

						<a data-toggle="dropdown" class="dropdown-toggle" href="#">

							<i class="ace-icon fa fa-bell icon-animated-bell"></i>

							<span class="badge badge-important">8</span>

						</a>



						<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">

							<li class="dropdown-header">

								<i class="ace-icon fa fa-exclamation-triangle"></i>

								8 Notifications

							</li>



							<li class="dropdown-content">

								<ul class="dropdown-menu dropdown-navbar navbar-pink">

									<li>

										<a href="#">

											<div class="clearfix">

												<span class="pull-left">

													<i class="btn btn-xs no-hover btn-pink fa fa-comment"></i>

													New Comments

												</span>

												<span class="pull-right badge badge-info">+12</span>

											</div>

										</a>

									</li>



									<li>

										<a href="#">

											<i class="btn btn-xs btn-primary fa fa-user"></i>

											Bob just signed up as an editor ...

										</a>

									</li>



									<li>

										<a href="#">

											<div class="clearfix">

												<span class="pull-left">

													<i class="btn btn-xs no-hover btn-success fa fa-shopping-cart"></i>

													New Orders

												</span>

												<span class="pull-right badge badge-success">+8</span>

											</div>

										</a>

									</li>



									<li>

										<a href="#">

											<div class="clearfix">

												<span class="pull-left">

													<i class="btn btn-xs no-hover btn-info fa fa-twitter"></i>

													Followers

												</span>

												<span class="pull-right badge badge-info">+11</span>

											</div>

										</a>

									</li>

								</ul>

							</li>



							<li class="dropdown-footer">

								<a href="#">

									See all notifications

									<i class="ace-icon fa fa-arrow-right"></i>

								</a>

							</li>

						</ul>

					</li>



					<li class="green dropdown-modal">

						<a data-toggle="dropdown" class="dropdown-toggle" href="#">

							<i class="ace-icon fa fa-envelope icon-animated-vertical"></i>

							<span class="badge badge-success">5</span>

						</a>



						<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">

							<li class="dropdown-header">

								<i class="ace-icon fa fa-envelope-o"></i>

								5 Messages

							</li>



							<li class="dropdown-content">

								<ul class="dropdown-menu dropdown-navbar">

									<li>

										<a href="#" class="clearfix">

											<img src="{{URL('assets/images/avatars/avatar.png')}}" class="msg-photo" alt="Alex's Avatar" />

											<span class="msg-body">

												<span class="msg-title">

													<span class="blue">Alex:</span>

													Ciao sociis natoque penatibus et auctor ...

												</span>



												<span class="msg-time">

													<i class="ace-icon fa fa-clock-o"></i>

													<span>a moment ago</span>

												</span>

											</span>

										</a>

									</li>



									<li>

										<a href="#" class="clearfix">

											<img src="{{URL('assets/images/avatars/avatar3.png')}}" class="msg-photo" alt="Susan's Avatar" />

											<span class="msg-body">

												<span class="msg-title">

													<span class="blue">Susan:</span>

													Vestibulum id ligula porta felis euismod ...

												</span>



												<span class="msg-time">

													<i class="ace-icon fa fa-clock-o"></i>

													<span>20 minutes ago</span>

												</span>

											</span>

										</a>

									</li>



									<li>

										<a href="#" class="clearfix">

											<img src="{{URL('assets/images/avatars/avatar4.png')}}" class="msg-photo" alt="Bob's Avatar" />

											<span class="msg-body">

												<span class="msg-title">

													<span class="blue">Bob:</span>

													Nullam quis risus eget urna mollis ornare ...

												</span>



												<span class="msg-time">

													<i class="ace-icon fa fa-clock-o"></i>

													<span>3:15 pm</span>

												</span>

											</span>

										</a>

									</li>



									<li>

										<a href="#" class="clearfix">

											<img src="{{URL('assets/images/avatars/avatar2.png')}}" class="msg-photo" alt="Kate's Avatar" />

											<span class="msg-body">

												<span class="msg-title">

													<span class="blue">Kate:</span>

													Ciao sociis natoque eget urna mollis ornare ...

												</span>



												<span class="msg-time">

													<i class="ace-icon fa fa-clock-o"></i>

													<span>1:33 pm</span>

												</span>

											</span>

										</a>

									</li>



									<li>

										<a href="#" class="clearfix">

											<img src="{{URL('assets/images/avatars/avatar5.png')}}" class="msg-photo" alt="Fred's Avatar" />

											<span class="msg-body">

												<span class="msg-title">

													<span class="blue">Fred:</span>

													Vestibulum id penatibus et auctor  ...

												</span>



												<span class="msg-time">

													<i class="ace-icon fa fa-clock-o"></i>

													<span>10:09 am</span>

												</span>

											</span>

										</a>

									</li>

								</ul>

							</li>



							<li class="dropdown-footer">

								<a href="inbox.html">

									See all messages

									<i class="ace-icon fa fa-arrow-right"></i>

								</a>

							</li>

						</ul>

					</li>



					<li class="light-blue dropdown-modal">

						<a data-toggle="dropdown" href="#" class="dropdown-toggle">

							<img class="nav-user-photo" src="{{URL('assets/images/avatars/user.jpg')}}" alt="Jason's Photo" />

							<span class="user-info">

								<small>Welcome,</small>

								{{\Auth::user()->name}}

							</span>



							<i class="ace-icon fa fa-caret-down"></i>

						</a>



						<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

							<li>

								<a href="#">

									<i class="ace-icon fa fa-cog"></i>

									Settings

								</a>

							</li>







							<li>

								<a href="#" data-toggle="modal" data-target="#myModal-change">

									<i class="fa fa-pencil m-r-xs"></i>

									Change Password

								</a>

							</li>



							<li>

								<a href="javascript:;" onclick="changeCompany()">

									<i class="ace-icon fa fa-industry"></i>

									Change Company

								</a>

							</li>

							<li>

								<a href="#" data-toggle="modal" data-target="#myModal-language">

									<i class="fa fa-language"></i>

									Change Language

								</a>

							</li>



							<li class="divider"></li>



							<li>

								<a href="{{URL('/logout')}}">

									<i class="ace-icon fa fa-power-off"></i>

									Logout

								</a>

							</li>

						</ul>

					</li>

				</ul>

			</div>



		</div><!-- /.navbar-container -->

	</div>



	<div class="main-container ace-save-state" id="main-container">

		<script type="text/javascript">

			try{ace.settings.loadState('main-container')}catch(e){}

		</script>



		<div id="sidebar" class="sidebar                  responsive                    ace-save-state" style="width: 210px;">

			<script type="text/javascript">

				try{ace.settings.loadState('sidebar')}catch(e){}

			</script>



    <!--   <div class="sidebar-shortcuts" id="sidebar-shortcuts">

        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">

          <button class="btn btn-success">

            <i class="ace-icon fa fa-signal"></i>

          </button>



          <button class="btn btn-info">

            <i class="ace-icon fa fa-pencil"></i>

          </button>



          <button class="btn btn-warning">

            <i class="ace-icon fa fa-users"></i>

          </button>



          <button class="btn btn-danger">

            <i class="ace-icon fa fa-cogs"></i>

          </button>

        </div>



        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">

          <span class="btn btn-success"></span>



          <span class="btn btn-info"></span>



          <span class="btn btn-warning"></span>



          <span class="btn btn-danger"></span>

        </div>

    </div> -->

    <!-- /.sidebar-shortcuts -->



    @include('dashboard.layouts.sidebar')



    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">

    	<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>

    </div>

</div>



@yield('content')







<!-- Notification Modal -->

<div class="modal fade empList-modal-lg" id="viewNotificationModal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-body">

			<div class="panel panel-success">

				<div class="panel-heading clearfix">

					<h4 class="panel-title" id="notificationTitle">Notification Title</h4> 

				</div>

				<div class="panel-body" id="notificationMessage">



				</div>

				<div class="modal-footer">

					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

				</div>

			</div>

		</div>



	</div>

</div>

<!-- Notification Modal -->

<!-- change password Modal -->

<div class="modal fade empList-modal-lg changePassModal" id="myModal-change" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-body">

			<div class="panel panel-white">

				<div class="panel-heading clearfix">

					<h4 class="panel-title" id="msg">Change Password</h4> 



				</div>

				<div class="panel-body">

					<form class="form-horizontal" >

						{{csrf_field()}}

						<div class="form-group">

							<label for="old_password" class="col-sm-4 control-label">Old Password</label>

							<div class="col-sm-8">

								<input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password">  

							</div>

						</div>

						<div class="form-group">

							<label for="new_password" class="col-sm-4 control-label">New Password</label>

							<div class="col-sm-8">

								<input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password">  

							</div>

						</div>

						<div class="form-group">

							<label for="confirm_password" class="col-sm-4 control-label">Confirm Password</label>

							<div class="col-sm-8">

								<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">  

							</div>

						</div>

					</form>

				</div>

				<div class="modal-footer">

					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

					<a  id="changePassword"  class="btn btn-success">Change</a>

				</div>

			</div>

		</div>



	</div>

</div>

<!-- change password Modal -->





<!-- change Language Modal -->

<div class="modal fade empList-modal-lg changelanguageModal" id="myModal-language" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-body">

			<div class="panel panel-white">

				<div class="panel-heading clearfix">

					<h4 class="panel-title" id="msg">Change Language</h4> 



				</div>

				<div class="panel-body">

					<form action="{{URL('change-language')}}" method="POST" role="form" id="changeCompanyForm">

						<div class="form-group">

							<label for="language">Company</label>

							<select class="form-control" name="language" id="language">

								<option value="">--Select Language--</option>

								<option value="en" {{$language == "en" ? "selected":""}}>English</option>

								<option value="hi" {{$language == "hi" ? "selected":""}}>हिंदी</option>

							</select>

							<span class="label label-danger" id="change_language_error" style="display: none;"></span>

						</div>



					</div>

					<div class="modal-footer">

						<button type="button" id="changeLanguage" class="btn btn-primary">Save</button>

					</div>

				</form>

			</div>

		</div>

	</div>

</div>

<!-- change password Modal -->



@include('dashboard.layouts.footer')





<script>



	function changeCompany(){

		$('.selectCompanyBg').show();

	}

	function changeLanguage(){

		$('.selectCompanyBg').show();

	}

	$(document).ready(function() {



		$('.select2').select2({

			allowClear: true

		});



		$('#changePassword').on('click',function(){

			var old_password = $('#old_password').val();

			var new_password = $('#new_password').val();

			var confirm_password = $('#confirm_password').val();

			if(old_password == ""){

				swal('Oops',"Old Password Required",'warning');  

			}else if(new_password == ""){

				swal('Oops',"New Password Required",'warning');  

			}else if(confirm_password == ""){

				swal('Oops',"Confirm Password Required",'warning');  

			}else if(confirm_password !== new_password){

				swal('Oops',"Confirm Password & New Password Not Matched ",'warning');  

			}else{

				$.ajaxSetup({

					headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}

				});

				var url  = "{{URL('/postChangePassword')}}";

				$.ajax({

					url: url,

					type: 'POST',

					data: {'old_password':old_password,'new_password':new_password},

					success: function (data) {

						console.log(data);

						if(data.flag){

							$('.changePassModal').modal('toggle');

							swal('Success','Password Changed Successfully','success'); 

						}else{

							$('.changePassModal').modal('toggle');

							swal('Oops',data.error,'warning');  

						}

					}

				});

			}



		});





		$('#setCompany').click(function(e){

			e.preventDefault();

			$.ajaxSetup({

				headers: {

					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')

				}

			});

			$('.loading-bg').show();

			$.ajax({

				url: $('#setCompanyForm').attr('action'),

				method: 'POST',

				data: $('#setCompanyForm').serialize(),

				success: function(data){

					$('.loading-bg').hide();

					if(!data.flag){

						if(typeof(data.errors.act_as_company) === "undefined"){

							$('#set_company_id_error').hide();

						}else{

							$('#set_company_id_error').show();

							$('#set_company_id_error').text(data.errors.act_as_company);

						}

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





		$('#changeLanguage').click(function(e){

			e.preventDefault();

			$.ajaxSetup({

				headers: {

					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')

				}

			});

			$('.loading-bg').show();

			$.ajax({

				url: $('#changeCompanyForm').attr('action'),

				method: 'POST',

				data: $('#changeCompanyForm').serialize(),

				success: function(data){

					$('.loading-bg').hide();

					if(!data.flag){

						if(typeof(data.errors.language) === "undefined"){

							$('#change_language_error').hide();

						}else{

							$('#change_language_error').show();

							$('#change_language_error').text(data.errors.language);

						}

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





	function acceptNumber(value){

		if (Number.isInteger(value)) {

			return false;

		}else{

			return true;

		}

	}



</script>





@yield('script')

</body>

</html>

