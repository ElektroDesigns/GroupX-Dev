	var active = true;
	function roomControl() {
			var room = $(this).data('val');
				eventTable = "trilakes_groupxroom_events";
				classTable = "trilakes_groupxroom_usage_stats";
				repeatTable = "trilakes_groupxroom_repeat_events";
				var dataString = '&eventTable=' + eventTable +'&repeatTable=' + repeatTable +'&classTable=' + classTable;
				$.post(''+site+'/model/events.class.php',dataString);
				$.post(''+site+'/command/cal_events2.php',dataString);
		if (active) {
            active = false;
            $('.panel-collapse').collapse('show');
           $('.panel-collapse1').collapse('hide');
           
        } else {
            active = true;
            $('.panel-collapse').collapse('hide');
			 $('.panel-collapse1').collapse('hide');
           
        }
		}; roomControl();
	$('.dropdown').on( 'click', '.dropdown-menu li a', function() { 
			var room = $(this).data('val');
			if (room == "groupxroom"){
				var active = true;
				eventTable = "trilakes_groupxroom_events";
				classTable = "trilakes_groupxroom_usage_stats";
				repeatTable = "trilakes_groupxroom_repeat_events";
				var dataString = '&eventTable=' + eventTable +'&repeatTable=' + repeatTable +'&classTable=' + classTable;
				$.post(''+site+'/model/events.class.php',dataString);
				$.post(''+site+'/command/cal_events2.php',dataString);
				if (active) {
            active = false;
            $('.panel-collapse').collapse('show');
            $('.panel-collapse1').collapse('hide');
           
        } else {
            active = true;
            $('.panel-collapse').collapse('hide');
            $('.panel-collapse1').collapse('show');
           
        }
				$('#calendar').fullCalendar('refetchEvents');
			}else if (room == "spinroom"){
				eventTable = "trilakes_spinroom_events";
				classTable = "trilakes_spinroom_usage_stats";
				repeatTable = "trilakes_spinroom_repeat_events";
				var dataString = '&eventTable=' + eventTable +'&repeatTable=' + repeatTable +'&classTable=' + classTable;
				$.post(''+site+'/model/events.class.php',dataString);
				$.post(''+site+'/command/cal_events2.php',dataString);
				if (active) {
            active = false;
            $('.panel-collapse').collapse('hide');
            $('.panel-collapse1').collapse('show');
           
        } else {
            active = true;
            $('.panel-collapse').collapse('hide');
            $('.panel-collapse1').collapse('show');
           
        }
				$('#calendar').fullCalendar('refetchEvents');
			}		
	});