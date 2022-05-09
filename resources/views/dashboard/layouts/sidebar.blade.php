
<ul class="nav nav-list">
	<li class="active">
			<a href="{{URL('/')}}">
				<i class="menu-icon fa fa-tachometer"></i>
				<span class="menu-text"> Dashboard </span>
			</a>

			<b class="arrow"></b>
		</li>
		@if(Session::has('menus'))
		@php
		$menus  = Session::get('menus'); 

		@endphp
		@foreach($menus as $menu)

		<li class="">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon {{$menu->module->icon}}"></i>
				<span class="menu-text"> {{$menu->module->module}} </span>

				<b class="arrow fa fa-angle-down"></b>
			</a>

			<b class="arrow"></b>
			@php
			$submenus = getSubmoduleByModule($menu->role_id,$menu->module_id)

			@endphp
			@if(count($submenus) > 0)
			<ul class="submenu">
				@foreach($submenus as $submenu)

				<li class="{{setActive($submenu->sub_module->link)}}">
					<a href="{{URL($submenu->sub_module->link)}}">
						<i class="menu-icon fa fa-caret-right"></i>
						{{$submenu->sub_module->sub_module}}
					</a>
					<b class="arrow"></b>
				</li>
				@endforeach
			</ul>
			@endif
		</li>

		@endforeach
		@endif

</ul>