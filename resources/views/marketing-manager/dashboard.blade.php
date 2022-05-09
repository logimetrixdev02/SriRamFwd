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
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/marketing-manager/rake')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-train" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">Rake</h2></center>
							</div>
						</a>
					</div>
				</div>

				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="wrimagecard wrimagecard-topimage">
						<a href="{{URL('/marketing-manager/warehouse')}}">
							<div class="wrimagecard-topimage_header" style="background-color:#1abffd;">
								<center><i class="fa fa-home" style="color:#090909"></i></center>
								<center><h2 style="color: #090909">Warehouse</h2></center>
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
