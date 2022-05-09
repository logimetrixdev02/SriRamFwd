@extends('dashboard.layouts.app')
@section('title','Dashboard')
@section('style')
<style type="text/css">

	.wrimagecard{	
		margin-top: 0;
		margin-bottom: 1.5rem;
		text-align: left;
		position: relative;
		background: #fff;
		box-shadow: 12px 15px 20px 0px rgba(46,61,73,0.15);
		border-radius: 4px;
		transition: all 0.3s ease;
	}
	.wrimagecard .fa{
		position: relative;
		font-size: 70px;
	}
	.wrimagecard-topimage_header{
		padding: 20px;
	}
	a.wrimagecard:hover, .wrimagecard-topimage:hover {
		box-shadow: 2px 4px 8px 0px rgba(46,61,73,0.2);
	}
	.wrimagecard-topimage a {
		width: 100%;
		height: 100%;
		display: block;
	}
	.wrimagecard-topimage a {
		border-bottom: none;
		text-decoration: none;
		color: #525c65;
		transition: color 0.3s ease;
	}


</style>
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
			</ul>
		</div>

		<div class="page-content">

			<div class="row">

				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/backend/update-opening-inventory')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-truck" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">Update Opening Inventory</h2></center>
							</div>
						</a>
					</div>
				</div>


				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/backend/update-inventory')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-truck" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">Update Inventory</h2></center>
							</div>
						</a>
					</div>
				</div>

				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/backend/update-rake-token')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-print" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">Update Rake Token</h2></center>
							</div>
						</a>
					</div>
				</div>

				<div class="clearfix"></div>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/backend/update-warehouse-token')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-print" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">Update warehouse Token</h2></center>
							</div>
						</a>
					</div>
				</div>

				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/backend/update-loadings')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-print" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">Update Loadings</h2></center>
							</div>
						</a>
					</div>
				</div>

				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/backend/update-labour-payments')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-print" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">Loading Labour Payments</h2></center>
							</div>
						</a>
					</div>
				</div>


				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/backend/update-wagon-unloadings')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-train" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">Wagon Unloading</h2></center>
							</div>
						</a>
					</div>
				</div>


				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/backend/update-direct-labour-payments')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-print" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">Direct Labour Payments</h2></center>
							</div>
						</a>
					</div>
				</div>


				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/backend/update-unloading-labour-payments')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-print" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">Unloading Labour Payments</h2></center>
							</div>
						</a>
					</div>
				</div>


				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/backend/update-wtl-freight-payments')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-print" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">WT Loading Freight</h2></center>
							</div>
						</a>
					</div>
				</div>


				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/backend/update-wtl-labour-payments')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-print" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">WT Loading Labour Payment</h2></center>
							</div>
						</a>
					</div>
				</div>


				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/backend/update-wtul-labour-payments')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-print" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">WT Unloading Labour Payments</h2></center>
							</div>
						</a>
					</div>
				</div>


			</div>




		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->
@section('script')
{{ Html::script("assets/js/ace-elements.min.js")}}
{{ Html::script("assets/js/ace.min.js")}}

@endsection
@endsection
