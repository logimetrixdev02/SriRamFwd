
<div class="infobox infobox-green">
	<div class="infobox-icon">
		<i class="ace-icon fa fa-rupee"></i>
	</div>

	<div class="infobox-data">
		<span class="infobox-data-number">{{round(monthlyAverageLabourRate($month,$year,'loading'),2)}}</span>
		<div class="infobox-content">Loading labour Rate</div>
	</div>
	<div class="stat stat-important">Average</div>

</div>

<div class="infobox infobox-blue">
	<div class="infobox-icon">
		<i class="ace-icon fa fa-rupee"></i>
	</div>

	<div class="infobox-data">
		<span class="infobox-data-number">{{round(monthlyAverageLabourRate($month,$year,'unloading'),2)}}</span>
		<div class="infobox-content">Unloading labour Rate</div>
	</div>
	<div class="stat stat-important">Average</div>
</div>

<div class="infobox infobox-pink">
	<div class="infobox-icon">
		<i class="ace-icon fa fa-rupee"></i>
	</div>

	<div class="infobox-data">
		<span class="infobox-data-number">{{round(monthlyAverageLabourRate($month,$year,'wagon_unloading'),2)}}</span>
		<div class="infobox-content">Wagon Unloading Rate</div>
	</div>
</div>

<div class="infobox infobox-red">
	<div class="infobox-icon">
		<i class="ace-icon fa fa-rupee"></i>
	</div>

	<div class="infobox-data">
		<span class="infobox-data-number">{{round(monthlyAverageFreight($month,$year),2)}}</span>
		<div class="infobox-content">Freight</div>
	</div>
	<div class="stat stat-important">Average</div>
</div>

<div class="infobox infobox-orange2">
	<div class="infobox-icon">
		<i class="ace-icon fa fa-rupee"></i>
	</div>

	<div class="infobox-data">
		<span class="infobox-data-number">{{round(monthlyAverageDemurrage($month,$year),2)}}</span>
		<div class="infobox-content">Average Demurrage</div>
	</div>
</div>

<div class="infobox infobox-blue2">
	<div class="infobox-icon">
		<i class="ace-icon fa fa-rupee"></i>
	</div>

	<div class="infobox-data">
		<span class="infobox-data-number">{{round(monthlyAverageWharfage($month,$year),2)}}</span>
		<div class="infobox-content">Average Wharfage</div>
	</div>
</div>