@extends('dashboard.layouts.app')
@section('title','Dashboard')
@section('style')
{{ Html::style("//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css")}}


@endsection

@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Dashboard</li>
			</ul><!-- /.breadcrumb -->

			
		</div>

		<div class="page-content">
		
			<div class="page-header">
				<h1>
					Dashboard
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						overview &amp; stats
					</small>
				</h1>
			</div><!-- /.page-header -->


		</div><!-- /.row -->

		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.page-content -->
</div>
</div><!-- /.main-content -->

@section('script')

{{ Html::script("https://code.jquery.com/ui/1.12.1/jquery-ui.js")}}
{{ Html::script("assets/js/jquery-ui.custom.min.js")}}
{{ Html::script("assets/js/jquery.ui.touch-punch.min.js")}}
{{ Html::script("assets/js/jquery.easypiechart.min.js")}}
{{ Html::script("assets/js/jquery.sparkline.index.min.js")}}
{{ Html::script("assets/js/jquery.flot.min.js")}}
{{ Html::script("assets/js/jquery.flot.pie.min.js")}}
{{ Html::script("assets/js/jquery.flot.resize.min.js")}}

<!-- ace scripts -->
{{ Html::script("assets/js/ace-elements.min.js")}}
{{ Html::script("assets/js/ace.min.js")}}


<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>


@endsection
@endsection
