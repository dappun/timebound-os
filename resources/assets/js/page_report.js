import '../../../node_modules/bootstrap-daterangepicker/daterangepicker.css';
import Chart from '../../../node_modules/chart.js/src/chart.js';

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

	var ctx = $("#" + container);

	var myChart = new Chart(ctx, {
	    type: 'bar',
	    data: {
	        labels: report.chart.data.label,
	        datasets: [{
	            label: 'Worked hours',
	            data: report.chart.data.data,
	            backgroundColor: 'rgba(54, 162, 235, 0.2)',
	            borderColor: 'rgba(54, 162, 235, 1)',
	            borderWidth: 1
	        }]
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }
	});
}

window.report = report;
