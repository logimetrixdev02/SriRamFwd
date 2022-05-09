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
			</ul>
		</div>

		<div class="page-content">




		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->
@section('script')
{{ Html::script("assets/js/ace-elements.min.js")}}
{{ Html::script("assets/js/ace.min.js")}}

@endsection
@endsection
