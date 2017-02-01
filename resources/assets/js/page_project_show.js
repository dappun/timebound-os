import Chart from '../../../node_modules/chart.js/src/chart.js';

var project = {};

project.initChart = function(data, container)
{
	var ctx = $("#" + container);

	var myChart = new Chart(ctx, {
	    type: 'polarArea',
	    data: {
	        labels: data.label,
	        datasets: [{
	            label: 'Worked hours',
	            data: data.data,
	            backgroundColor: data.bgcolor,
	            borderWidth: 1
	        }]
	    }
	});
}

window.project = project;
