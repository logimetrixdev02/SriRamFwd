

<div class="footer">

	<div class="footer-inner">

		<div class="footer-content">

			<span class="bigger-120">

				<span class="blue bolder">SAROJ</span>

			 &copy; {{date('Y')}}

			</span>

		</div>

	</div>

</div>



<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">

	<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>

</a>

</div><!-- /.main-container -->



<!-- basic scripts -->



<!--[if !IE]> -->

{{ Html::script("assets/js/jquery-2.1.4.min.js")}}



<!-- <![endif]-->



    <!--[if IE]>

{{ Html::script("assets/js/jquery-1.11.3.min.js")}}

<![endif]-->



<script type="text/javascript">

	if('ontouchstart' in document.documentElement) document.write("<script src={{url('assets/js/jquery.mobile.custom.min.js')}}>"+"<"+"/script>");

</script>

{{ Html::script("assets/js/bootstrap.min.js")}}

{{ Html::script("assets/js/sweetalert.min.js")}}

{{ Html::script("assets/js/select2.min.js")}}

{{ Html::script("assets/js/moment.min.js")}}

{{ Html::script("http://www.google.com/jsapi")}}



<script type="text/javascript">

	$(document).ready(function(){

	if($('.submenu li').has('active')){

	    $('.submenu li.active').parent().parent().addClass('open');

	}

});

</script>



<!-- page specific plugin scripts -->



    <!--[if lte IE 8]>

      {{ Html::script("assets/js/excanvas.min.js")}}

    <![endif]-->