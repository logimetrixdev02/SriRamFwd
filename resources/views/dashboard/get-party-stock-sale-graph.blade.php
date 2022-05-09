<div class="widget-main padding-4">
	<div id="sales-charts" style="height: 280px;"></div>


</div>


<script>
	am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("sales-charts", am4charts.XYChart);

// Add data
chart.data = [{
	"Product": "Cement",
	"Sale": {{$cement_sale}}
}, {
	"Product": `NPK 
	20:20:0:13 & 
	12:32:16`,
	"Sale": {{$npk_sale}}
}, {
	"Product": "Urea",
	"Sale": {{$urea_sale}}
}, {
	"Product": "DAP",
	"Sale": {{$dap_sale}}
}, {
	"Product": "MOP",
	"Sale": {{$mop_sale}}
}];

// Create axes

var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "Product";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;

categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
	if (target.dataItem && target.dataItem.index & 2 == 2) {
		return dy + 25;
	}
	return dy;
});

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "Sale";
series.dataFields.categoryX = "Product";
series.name = "Sale";
series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
series.columns.template.fillOpacity = .8;

var columnTemplate = series.columns.template;
columnTemplate.strokeWidth = 2;
columnTemplate.strokeOpacity = 1;

}); 
</script>