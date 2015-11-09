<?php

class Calendar {

	private $cal_id;

	function __construct ($cal_id) {
		$this->cal_id = $cal_id;
	}

    public static function getCalendar($cal_id) {
		global $obj_db;

		$str_query = 'SELECT * FROM calendars WHERE calendar_id = '. $cal_id;
		$obj_result = mysqli_query($obj_db, $str_query);

	  	$arr_calendar = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

	  	return $arr_calendar;
    }

    public static function getColor($cal_id) {
		global $obj_db;

		$str_query = 'SELECT calendar_color FROM calendars WHERE calendar_id = '. $cal_id;
		$obj_result = mysqli_query($obj_db, $str_query);

	  	$arr_calendar = mysqli_fetch_row($obj_result);

	  	return $arr_calendar[0];
    }

    public static function updateCalendar($frm_submitted) {
		global $obj_db;

		$str_query = 'UPDATE calendars SET '.($frm_submitted['bln_color_future_events'] ? 'events_color = "'.$frm_submitted['color'].'",' : '').' calendar_color = "'.$frm_submitted['color'].'", name = "'.$frm_submitted['title'].'" WHERE calendar_id = '. $frm_submitted['cal_id'];
		$obj_result = mysqli_query($obj_db, $str_query);

		if($obj_result !== false) {

			return true;
		}
		return false;


    }
}
?>