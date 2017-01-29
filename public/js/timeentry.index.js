$('#timer select').select2();

$(document).ready(function(){
  //   var duration = $('#timer-start').val();
  //   if (duration != '') {
  //       startTimer()
  //       $.APP.resumeTimer('sw', moment(duration).format());    
  //   }

  //   $('#timer-form').submit(function(e){
  //   	e.preventDefault();
  //   	var timerID = $('#timer-id').val();
  //   	var timerDesc = $('#timer-desc').val();
  //   	var timeBtn = $('#timer-btn').val();

  //   	var form = jQuery(this).parents("#timer form");
		// var dataString = jQuery(this).serialize();
		// var method = $('#timer form').attr('method');

  //       // console.log(timeBtn);

		// var url = $('#timer form').attr('action');
		// $.ajax({
  //           url: url,
  //           data: dataString,
  //           type: method,
  //           datatype: 'JSON',
  //           success: function (data, textStatus, jqXHR) {
  //           	var d = JSON.parse(data);
  //           	var timerID = d.id;
  //           	if (timeBtn == 'Start') {
  //           		startTimer();
  //           		$('#timer-id').val(timerID);
  //           	} else {
  //           		stopTimer();
  //           	}
  //           },
  //           error: function(jqXHR, textStatus, errorThrown) {
  //               $("#timer-desc").notify(
		// 		  "Description is required.", "error",
		// 		  { position:"right" }
		// 		);
  //           	stopTimer();
		// 	},
		// 	complete: function(jqXHR, textStatus) {
		// 		console.log(textStatus + ' timer');
		// 	}
  //       });
  //   });

  //   function stopTimer()
  //   {
  //   	$('.btn').removeClass('btn-danger').val('Start');	
  //   	$.APP.stopTimer('sw');
  //       refreshTimerForm();
  //   }

  //   function startTimer()
  //   {
  //   	$('.btn').addClass('btn-danger').val('Stop');	
  //   	$.APP.startTimer('sw');
  //   }

  //   function refreshTimerForm()
  //   {
  //       // $('#timer-form')[0].reset();
  //       $('#timer-id').val('');
  //       $('#timer-desc').val('');
  //       $('#timer-ticket').val('');
        
  //       $('#timer-project').prop('selectedIndex',0);
  //       $('#timer-project').select2('');
  //       $('.counter span').each(function() {
  //           $(this).html('00')
  //       })

  //       $.ajax({
  //           url: urlDashboard,
  //           success: function (data, textStatus, jqXHR) {
  //               $('#timer-table').html(data);
  //           },
  //           error: function(jqXHR, textStatus, errorThrown) {
  //           },
  //           complete: function(jqXHR, textStatus) {
  //           }
  //       });
  //   }
});