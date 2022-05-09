@extends('dashboard.layouts.app')
@section('title','Dashboard')
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

			<div class="nav-search" id="nav-search">
				<form class="form-search">
					<span class="input-icon">
						<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
						<i class="ace-icon fa fa-search nav-search-icon"></i>
					</span>
				</form>
			</div><!-- /.nav-search -->
		</div>

		<div class="page-content">
			<div class="ace-settings-container" id="ace-settings-container">
				<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
					<i class="ace-icon fa fa-cog bigger-130"></i>
				</div>

			
			</div><!-- /.ace-settings-container -->

			<div class="page-header">
				<h1>
					Dashboard
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						overview &amp; stats
					</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row" style="width:100%; height: 500px; overflow-y: scroll">
				<div class="col-sm-12">
					

				</div><!-- /.col -->
			</div>


		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->

@section('script')


<!-- ace scripts -->
{{ Html::script("assets/js/ace-elements.min.js")}}
{{ Html::script("assets/js/ace.min.js")}}
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		am4core.ready(function() {

			am4core.useTheme(am4themes_animated);

			@php
			$i = 1;
			@endphp
			@foreach($master_rakes as $rake)

			var chart = am4core.create("rake{{$i}}div", am4charts.PieChart3D);
			chart.hiddenState.properties.opacity = 0; 

			chart.legend = new am4charts.Legend();

			chart.data = [

			@foreach($rake->master_rake_products as $product)
			{
				product: "{{$product->product->name}}",
				quantities: {{$product->quantity}}
			},
			@endforeach
			
			];

			var series = chart.series.push(new am4charts.PieSeries3D());
			series.dataFields.value = "quantities";
			series.dataFields.category = "product";


			@php
			$i++;
			@endphp
			@endforeach

		});



	});

</script>
@endsection
@endsection
