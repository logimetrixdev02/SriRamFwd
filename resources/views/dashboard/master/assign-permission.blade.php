@extends('dashboard.layouts.app')
@section('title','Role-Permissions')
@section('content')
@section('style')
<style>
	.panel-heading h3 {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		line-height: normal;
	}
</style>
@endsection
<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Role-Permissions</li>
			</ul>
		</div>

		<div class="page-content">
			
			{{csrf_field()}}
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title pull-left">Role-Permissions</h3>

					<button type="submit" class="btn btn-info pull-right">
						Save 
						<i class="ace-icon fa fa-floppy icon-on-right bigger-110"></i>
					</button>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">

					<div class="row">
						@php
						$i = 1;
						@endphp
						@foreach($modules as $module)
						<div class="col-sm-4">
							<div class="widget-box collapsed">
								<div class="widget-header">
									<h4 class="widget-title">{{$module->module}}</h4>

									<div class="widget-toolbar">
										<a href="#" data-action="collapse">
											<i class="ace-icon fa fa-chevron-down"></i>
										</a>

										<a href="#" data-action="close">
											<i class="ace-icon fa fa-times"></i>
										</a>
									</div>
								</div>

								<div class="widget-body" style="display: none;">
									<div class="widget-main">
										@php
										$j = 0;
										@endphp
										@foreach($module->sub_modules as $sub_module)
										@php
										$permission = \App\RoleModuleAssociation::where('role_id',$role->id)->where('module_id',$module->id)->where('sub_module_id',$sub_module->id)->first();

										if(is_null($permission)){
										$checked = '';
									}else{
									$checked = 'checked';
								}
								
								@endphp


								<div class="row">
									<div class="col-xs-9">
										<label>
											{{$sub_module->sub_module}}
										</label>
									</div>

									<div class="col-xs-3">
										<label>
											<input name="sub_module[]" class="ace ace-switch ace-switch-4 btn-flat" type="checkbox" value="{{$sub_module->id}}" onchange="handleChange({{$role->id}},{{$module->id}},this.value)" {{$checked}}>
											<span class="lbl"></span>
										</label>
									</div>
								</div>
								@php
								$j++;
								@endphp
								@endforeach
							</div>
						</div>
					</div>
				</div>
				@if($i%3 == 0)
				<div class="clearfix"></div>
				@endif

				@php
				$i++;
				@endphp
				@endforeach


			</div>
		</div>
	</div>
</div>
<!-- /.page-content -->
</div>
</div><!-- /.main-content -->
@section('script')
{{ Html::script("assets/js/ace-elements.min.js")}}
{{ Html::script("assets/js/ace.min.js")}}


<script>
	function handleChange(role_id,module_id,value){
		$('.loading-bg').show();
		$.ajax({
			url: "{{url('/user/update-permissions')}}"+"/"+role_id+"/"+module_id+"/"+value,
			type: 'GET',
			success:function(data){
				console.log(data);
				$('.loading-bg').hide();
				if(!data.flag){
					swal('Error',data.message,'warning');
				}
			}
		});
	}
</script>
@endsection
@endsection
