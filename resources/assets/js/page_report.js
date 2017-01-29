import '../../../node_modules/bootstrap-daterangepicker/daterangepicker.css';

require("script-loader!../../../node_modules/bootstrap-daterangepicker/daterangepicker.js");

var report = {};

report.initSearchForm = function (start, end, container)
{
	report.initSelect2();

	report.data = {
		start: start,
		end: end,
		container: container
	}

	report.initDaterangepicker();
}

report.cb = function (start, end) {
	$('#reportrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
    $('input[name="start"]').val(start.format('MMM D, YYYY'));
    $('input[name="end"]').val(end.format('MMM D, YYYY'));
}

report.initSelect2 = function ()
{
	$('#reports-filter select').select2();
}

report.initDaterangepicker = function()
{
	$(report.data.container).daterangepicker({
        startDate: start,
        endDate: end,
        applyClass: 'btn-primary',
        cancelClass: 'btn',
        weekStart: 1,
        locale: {
        	"firstDay": 1
        },
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'This week': [moment().startOf('isoweek'), moment().endOf('isoweek')],
           'Last week': [moment().subtract(1, 'week').startOf('isoweek'), moment().subtract(1, 'week').endOf('isoweek')],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, report.cb);

	report.cb(report.data.start, report.data.end);
    
    $('input[name="start"]').val(report.data.start);
	$('input[name="end"]').val(report.data.end);
}

report.initChart = function(data, container)
{
	report.chart = {
		container: container,
		data: data
	}

	google.charts.load('current', {packages: ['corechart']});
	google.charts.setOnLoadCallback(report.drawReportChart);
}

report.drawReportChart = function ()
{
	var data = google.visualization.arrayToDataTable(report.chart.data);

	var g1 = google.visualization.data.group(data, [0], [{
	    type: 'number',
	    label: 'Total Hours',
	    column: 1,
	    aggregation: google.visualization.data.sum
	}]);

	var view = new google.visualization.DataView(g1);
  	view.setColumns([0, 1, { calc: function(data, row){
  			var num = data.getValue(row, 1);
  			return core.formatGMH(num);
  		},
        sourceColumn: 1,
        type: "string",
        role: "annotation"}
    ]);

  	var options = {
		hAxis: {
			title: 'Date'
		},
		vAxis: {
			title: 'Total Hours',
		},
		bar: {groupWidth: "95%"}
	};

	var chart = new google.visualization.ColumnChart(document.getElementById(report.chart.container));
	chart.draw(view, options);
}


window.report = report;
