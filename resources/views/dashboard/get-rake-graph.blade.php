<div class="widget-box">
	<div class="widget-header widget-header-flat widget-header-small">
		<h5 class="widget-title">
			<i class="ace-icon fa fa-signal"></i>
			Rakes
		</h5>

		<div class="widget-toolbar no-border">
			<div class="inline dropdown-hover">
				<button class="btn btn-minier btn-primary" >
					{{$master_rake->name}}
					<i class="ace-icon fa fa-angle-down icon-on-right bigger-110"></i>
				</button>

				<ul class="dropdown-menu dropdown-menu-right dropdown-125 dropdown-lighter dropdown-close dropdown-caret">
					@php
					$i = 0;
					@endphp
					@foreach($master_rakes as $rake)
					<li class="{{$i ==0 ? "active":""}}" onclick="getRakeGraph('{{$rake->id}}');">
						<a href="javascript:;" class="blue">
							<i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
							{{$rake->name}}
						</a>
					</li>
					@php
					$i++;
					@endphp
					@endforeach
				</ul>
			</div>
		</div>
	</div>

	<div class="widget-body">
		<div class="widget-main">
			<div id="piechart-placeholder"></div>

			<div class="hr hr8 hr-double"></div>

			<div class="infobox infobox-green infobox-small infobox-dark" style="width: 145px !important;height: 70px !important">
				<div class="infobox-icon">
					<i class="ace-icon fa fa-print"></i>
				</div>
				<div class="infobox-data">
					<div class="infobox-content">Tokens</div>
					<div class="infobox-content">@php
						echo $total_tokens = \App\Token::where('master_rake_id',$master_rake->id)->sum('quantity');
					@endphp</div>
				</div>
			</div>

			<div class="infobox infobox-blue infobox-small infobox-dark" style="width: 145px !important;height: 70px !important">
				<div class="infobox-icon">
					<i class="ace-icon fa fa-train"></i>
				</div>
				<div class="infobox-data">
					<div class="infobox-content">RR Quantity</div>
					<div class="infobox-content">{{$master_rake->quantity_alloted}}</div>
				</div>
			</div>

			<div class="infobox infobox-grey infobox-small infobox-dark" style="width: 145px !important;height: 70px !important">
				<div class="infobox-icon">
					<i class="ace-icon fa fa-shopping-cart"></i>
				</div>
				<div class="infobox-data">
					<div class="infobox-content">Wagon Unloadings</div>
					<div class="infobox-content">@php
						echo $wagon_unloadings = \App\WagonUnloading::where('master_rake_id',$master_rake->id)->sum('quantity');
					@endphp</div>
				</div>
			</div>

		</div>
	</div>
</div>

<script>
	var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
	var color = ['#68BC31','#2091CF','#AF4E96','#DA5430','#FEE074']
	var data = [
	@php
	$i = 0;
	@endphp
	@foreach($master_rake->master_rake_products as $product)
	{ label: "{{$product->product->name}}",  data: {{$product->quantity}}, color: color[{{$i}}]},

	@php
	$i++;
	@endphp

	@endforeach
	]
	function drawPieChart(placeholder, data, position) {
		$.plot(placeholder, data, {
			series: {
				pie: {
					show: true,
					tilt:0.8,
					highlight: {
						opacity: 0.25
					},
					stroke: {
						color: '#fff',
						width: 2
					},
					startAngle: 2
				}
			},
			legend: {
				show: true,
				position: position || "ne", 
				labelBoxBorderColor: null,
				margin:[-30,15]
			}
			,
			grid: {
				hoverable: true,
				clickable: true
			}
		})
	}
	drawPieChart(placeholder, data);

       /**
       we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
       so that's not needed actually.
       */
       placeholder.data('chart', data);
       placeholder.data('draw', drawPieChart);

        //pie chart tooltip example
        var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
        var previousPoint = null;

        placeholder.on('plothover', function (event, pos, item) {
        	if(item) {
        		if (previousPoint != item.seriesIndex) {
        			previousPoint = item.seriesIndex;
        			var tip = item.series['label'] + " : " + item.series['percent']+'%';
        			$tooltip.show().children(0).text(tip);
        		}
        		$tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
        	} else {
        		$tooltip.hide();
        		previousPoint = null;
        	}

        });


    </script>