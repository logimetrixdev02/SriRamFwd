<!DOCTYPE html>

<html lang="en">

<head>

  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <meta charset="utf-8" />

  <meta name="_token" content="{{ csrf_token() }}" />

  <title>SAROJ | Login Page</title>



  <meta name="description" content="User login page" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <link rel="icon" type="image/png"  sizes="160x160" href="{{url('/assets/images/favicon.ico')}}"/>



  <!-- bootstrap & fontawesome -->

  {{Html::style("assets/css/bootstrap.min.css")}}

  {{Html::style("assets/font-awesome/4.5.0/css/font-awesome.min.css")}}



  <!-- text fonts -->

  {{Html::style("assets/css/fonts.googleapis.com.css")}}



  <!-- ace styles -->

  {{Html::style("assets/css/ace.min.css")}}

  {{Html::style("assets/css/sweetalert.css")}}



        <!--[if lte IE 9]>

            {{Html::style("assets/css/ace-part2.min.css")}}

          <![endif]-->

          {{Html::style("assets/css/ace-rtl.min.css")}}



        <!--[if lte IE 9]>

          {{Html::style("assets/css/ace-ie.min.css")}}

        <![endif]-->



        <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->



        <!--[if lte IE 8]>

        {{ Html::script("assets/js/html5shiv.min.js")}}

        {{ Html::script("assets/js/respond.min.js")}}

      <![endif]-->



      <style type="text/css">

        .loading-bg {

          background: #44444473;

          top: 0;

          bottom: 0;

          left: 0;

          right: 0;

          margin: 0px auto;

          z-index: 10053;

          position: fixed;

        }

        .loading {

          border: 16px solid #f3f3f3;

          border-radius: 50%;

          border-top: 16px solid #3498db;

          width: 150px;

          height: 150px;

          -webkit-animation: spin 2s linear infinite;

          animation: spin 2s linear infinite;

          position: absolute;

          left: 0;

          right: 0;

          margin: 0px auto;

          top: 50%;

        }

        @-webkit-keyframes spin {

          0% { -webkit-transform: rotate(0deg); }

          100% { -webkit-transform: rotate(360deg); }

        }



        @keyframes spin {

          0% { transform: rotate(0deg); }

          100% { transform: rotate(360deg); }

        }





      </style>

    </head>



    <body class="login-layout blur-login">



     <div class="loading-bg" style="display: none;">  

      <div class="loading"></div>

    </div>

    

    <div class="main-container">

      <div class="main-content">

        <div class="row">

          <div class="col-sm-10 col-sm-offset-1">

            <div class="login-container">

              <div class="center">

                <h1>

                  <!-- <i class="ace-icon fa fa-train green"></i> -->

                 <!--  <span class="red">I</span>

                  <span class="white" id="id-text2">Manager</span> -->

                  <img src="{{url('/assets/images/logo3.png')}}"
                              style="

                                  width: 94%;
                                  height: 79%;
                                  
                                  margin: -86px;
                                 margin-left: -95px;

                                  "

                                  />

                </h1>

                <h4 class="blue" id="id-company-text">&copy; Logimetrix techsolutions Pvt Ltd</h4>

              </div>



              <div class="space-6"></div>



              <div class="position-relative">

                <div id="login-box" class="login-box visible widget-box no-border">

                  <div class="widget-body">

                    <div class="widget-main">

                      <h4 class="header blue lighter bigger">

                        <i class="ace-icon fa fa-coffee green"></i>

                        Please Enter Your Information



                      </h4>



                      <div class="space-6"></div>



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



                      <form action="{{URL('/')}}" method="post">

                        {{ csrf_field() }}

                        <fieldset>

                          <label class="block clearfix">

                            <span class="block input-icon input-icon-right">

                              <input type="text" name="email" class="form-control" placeholder="Username" value="{{\Request::old('email')}}" />

                              <i class="ace-icon fa fa-user"></i>

                              @if ($errors->has('email'))

                              <span class="label label-danger">{{ $errors->first('email') }}</span>

                              @endif

                            </span>

                          </label>



                          <label class="block clearfix">

                            <span class="block input-icon input-icon-right">

                              <input type="password" name="password" class="form-control" placeholder="Password" />

                              <i class="ace-icon fa fa-lock"></i>

                              @if ($errors->has('password'))

                              <span class="label label-danger">{{ $errors->first('password') }}</span>

                              @endif

                            </span>

                          </label>



                          <div class="space"></div>



                          <div class="clearfix">





                            <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">

                              <i class="ace-icon fa fa-key"></i>

                              <span class="bigger-110">Login</span>

                            </button>

                          </div>



                          <div class="space-4"></div>

                        </fieldset>

                      </form>



                    </div><!-- /.widget-main -->



                    <div class="toolbar clearfix">

                      <div>

                        <a href="#" data-target="#forgot-box" class="forgot-password-link">

                          <i class="ace-icon fa fa-arrow-left"></i>

                          I forgot my password

                        </a>

                      </div>



                    </div>

                  </div><!-- /.widget-body -->

                </div><!-- /.login-box -->



                <div id="forgot-box" class="forgot-box widget-box no-border">

                  <div class="widget-body">

                    <div class="widget-main">

                      <h4 class="header red lighter bigger">

                        <i class="ace-icon fa fa-key"></i>

                        Retrieve Password

                      </h4>



                      <div class="space-6"></div>

                      <p>

                        Enter your email and to receive instructions

                      </p>



                      <form action="{{URL('/forget-password')}}" id="forgetPasswordForm">

                        {{ csrf_field() }}

                        <fieldset>

                          <label class="block clearfix">

                            <span class="block input-icon input-icon-right">

                              <input type="email" name="email" id="forget_email" class="form-control" placeholder="Email" />

                              <i class="ace-icon fa fa-envelope"></i>

                            </span>

                          </label>



                          <div class="clearfix">

                            <button type="button" class="width-35 pull-right btn btn-sm btn-danger" id="forgetBtn">

                              <i class="ace-icon fa fa-lightbulb-o"></i>

                              <span class="bigger-110">Send Me!</span>

                            </button>

                          </div>

                        </fieldset>

                      </form>

                    </div><!-- /.widget-main -->



                    <div class="toolbar center">

                      <a href="#" data-target="#login-box" class="back-to-login-link">

                        Back to login

                        <i class="ace-icon fa fa-arrow-right"></i>

                      </a>

                    </div>

                  </div><!-- /.widget-body -->

                </div><!-- /.forgot-box -->





              </div><!-- /.position-relative -->



              <div class="navbar-fixed-top align-right">

                <br />

                &nbsp;

                <a id="btn-login-dark" href="#">Dark</a>

                &nbsp;

                <span class="blue">/</span>

                &nbsp;

                <a id="btn-login-blur" href="#">Blur</a>

                &nbsp;

                <span class="blue">/</span>

                &nbsp;

                <a id="btn-login-light" href="#">Light</a>

                &nbsp; &nbsp; &nbsp;

              </div>

            </div>

          </div><!-- /.col -->

        </div><!-- /.row -->

      </div><!-- /.main-content -->

    </div><!-- /.main-container -->



    <!-- basic scripts -->



    <!--[if !IE]> -->

    {{ Html::script("assets/js/jquery-2.1.4.min.js")}}

    {{ Html::script("assets/js/sweetalert.min.js")}}



    <!-- <![endif]-->



        <!--[if IE]>

{{ Html::script("assets/js/jquery-1.11.3.min.js")}}

<![endif]-->

<script type="text/javascript">

  if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");

</script>



<!-- inline scripts related to this page -->

<script type="text/javascript">

  jQuery(function($) {

   $(document).on('click', '.toolbar a[data-target]', function(e) {

    e.preventDefault();

    var target = $(this).data('target');

                $('.widget-box.visible').removeClass('visible');//hide others

                $(target).addClass('visible');//show target

              });



   $(document).on('click', '#forgetBtn', function(e) {

    e.preventDefault();

    var forget_email = $('#forget_email').val();

    if(forget_email == ""){

     swal('Oops',"Email Required",'warning');  

   }else{

    $.ajaxSetup({

      headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}

    });

    var url  = $('#forgetPasswordForm').attr('action');

    $('.loading-bg').show();

    $.ajax({

      url: url,

      type: 'POST',

      data: {'email':forget_email},

      success: function (data) {

        $('.loading-bg').hide();

        if(data.flag){

          swal('Success','Email Sent Successfully. Please Check Your Email','success'); 

        }else{

          swal('Oops',data.errors.email,'warning');  

        }

      }

    });

  }





});





 });







  jQuery(function($) {

   $('#btn-login-dark').on('click', function(e) {

    $('body').attr('class', 'login-layout');

    $('#id-text2').attr('class', 'white');

    $('#id-company-text').attr('class', 'blue');



    e.preventDefault();

  });

   $('#btn-login-light').on('click', function(e) {

    $('body').attr('class', 'login-layout light-login');

    $('#id-text2').attr('class', 'grey');

    $('#id-company-text').attr('class', 'blue');



    e.preventDefault();

  });

   $('#btn-login-blur').on('click', function(e) {

    $('body').attr('class', 'login-layout blur-login');

    $('#id-text2').attr('class', 'white');

    $('#id-company-text').attr('class', 'light-blue');



    e.preventDefault();

  });



 });

</script>

</body>

</html>

