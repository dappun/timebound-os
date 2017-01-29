import '../../../node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css';
import '../../../node_modules/timepicker/jquery.timepicker.css';

require("script-loader!../../../node_modules/timepicker/jquery.timepicker.min.js");
require("script-loader!../../../node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js");

var timesheetForm = {};

timesheetForm.init = function()
{
	timesheetForm.updateDurationField();

    $('.picker-start1, .picker-end1').datetimepicker({
	    format: 'YYYY-MM-DD',
	    maxDate: moment().endOf('day').format()
	});

	$('.picker-start2, .picker-end2').timepicker({
	    timeFormat: 'H:i',
	    interval: 15,
	    dynamic: false,
	    dropdown: true,
	    scrollbar: true,
	    show2400: true
	});

	$('.picker-start1').on("dp.change", function(e) {
	    var current = $(this).val();
	    $('.picker-end1').val(current);

	    timesheetForm.updateDurationField();
	});

	$('.picker-end1').on("dp.change", function(e) {
	    timesheetForm.updateDurationField();
	});

	$('.picker-start2, .picker-end2').on('change', function(e) {
	    timesheetForm.updateDurationField();
	});

	$('#timer-project').select2();
}

timesheetForm.updateDurationField = function() 
{

    var negative = '';
    
    var date_start = timesheetForm.getDateFromInput('.picker-start1', '.picker-start2');
    var date_end = timesheetForm.getDateFromInput('.picker-end1', '.picker-end2');
    
    var elapsedMinutes = (date_end - date_start) / 1000 / 60;

    if (elapsedMinutes < 0){
        negative = '-';
        elapsedMinutes = elapsedMinutes * -1;

        $('.timer-total').removeClass('label-info');
        $('.timer-total').addClass('label-important');
    } else {
        $('.timer-total').removeClass('label-important');
        $('.timer-total').addClass('label-info');
    }
        
    var hours = Math.floor(elapsedMinutes / 60);          
    var minutes = elapsedMinutes % 60;
    
    hours = (hours < 10 ? '0' : '') + hours;
    minutes = (minutes < 10 ? '0' : '') + minutes;
    
    $('.timer-total').html(negative + hours + ':' + minutes);
}

timesheetForm.refreshInput = function (dateSelector, timeSelector, reset) 
{

    var date = timesheetForm.getDateFromInput(dateSelector, timeSelector);
    
    if (reset) date = new Date();
    
    var val = {
            h: date.getHours(),
            i: date.getMinutes(),
            d: date.getDate(),
            m: date.getMonth() + 1,
            yy: date.getFullYear().toString()
        };
        val.hh = (val.h < 10 ? '0' : '') + val.h;
        val.ii = (val.i < 10 ? '0' : '') + val.i;
        val.dd = (val.d < 10 ? '0' : '') + val.d;
        val.mm = (val.m < 10 ? '0' : '') + val.m;
    
    console.log(date)
    $(dateSelector).val(val.yy + '-' + val.mm + '-' + val.dd);
    $(timeSelector).val(val.hh + ':' + val.ii);
    
    return;
}

timesheetForm.getDateFromInput = function (dateSelector, timeSelector) 
{

    var date_value = $(dateSelector).val();
    var time_value = $(timeSelector).val();
    
    if (date_value === undefined || time_value === undefined) return new Date();
    
    var arr = date_value.split("-");
    var year = arr[0];
    var month = arr[1];
    var day = arr[2];
    
    var arr = time_value.split(":");
    var hours = arr[0];
    var minutes = arr[1];
    
    var d = new Date();
    
    if(!(!isNaN(parseFloat(year)) && isFinite(year))) year = d.getFullYear(); else year = year - 0 + 2000;
    if(!(!isNaN(parseFloat(month)) && isFinite(month))) month = d.getMonth() + 1;
    if(!(!isNaN(parseFloat(day)) && isFinite(day))) day = d.getDate();
    if(!(!isNaN(parseFloat(hours)) && isFinite(hours))) hours = 0; //d.getHours();
    if(!(!isNaN(parseFloat(minutes)) && isFinite(minutes))) minutes = 0; //d.getMinutes();
    
    var date_return = new Date(year, month - 1, day, hours, minutes, 0);
    
    return date_return; 
}

window.timesheetForm = timesheetForm;