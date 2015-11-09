<?php
session_start();
require '../../login/dbc.php';
		
if (!isset($_SESSION['eventTable'])) {    
        $_SESSION['eventTable'] = $_POST['eventTable'];
		$_SESSION['repeatTable'] = $_POST['repeatTable'];
		}    
if (isset($_POST['eventTable'])) {    
        $_SESSION['eventTable'] = $_POST['eventTable'];
		$_SESSION['repeatTable'] = $_POST['repeatTable'];
		}    
		$eventTable = $_SESSION['eventTable'];
		$repeatTable= $_SESSION['repeatTable'];	
/*		
		$eventTable = 'trilakes_groupxroom_events';
		$repeatTable = 'trilakes_groupxroom_repeat_events';
*/
class Events {
		public static function getEvents($frm_submitted) {
		global $link, $eventTable, $repeatTable;

	//	$link = mysqli_connect($hostname, $username, $password, $database);
			if($link === FALSE) {
				$error= "Database connection failed";
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			mysqli_set_charset( $link , 'utf8' );

	  	$arr_content = array();

		// get startdate and enddate from repeating_events table
		$arr_rep_events = array();

		$str_query_r = 'SELECT * FROM '.$repeatTable;

		$res1 = mysqli_query($link, $str_query_r);

		if($res1 !== false) {
			while ($arr_line = mysqli_fetch_array($res1, MYSQLI_ASSOC)) {
				$arr_rep_events[$arr_line['rep_event_id']] = $arr_line;
			}
		}

		$str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end FROM '.$eventTable.
		' WHERE ((date_start BETWEEN "'.date("Y-m-d", $frm_submitted['start']) .'" AND "'.date("Y-m-d", $frm_submitted['end']).'") OR ('.
							' date_end BETWEEN "'.date("Y-m-d", $frm_submitted['start']) .'" AND "'.date("Y-m-d", $frm_submitted['end']).'")) ';



		$str_query .= ' order by start';

	  	$obj_result = mysqli_query($link, $str_query);

	  	while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
		



			//if(defined('MULTI_CALENDAR') && defined('SORT_ALL_CALENDARS_BY_CALENDARID') && MULTI_CALENDAR && SORT_ALL_CALENDARS_BY_CALENDARID) {
			//	$arr_line['sorter'] = $arr_line['calendar_id'];
			//}

			if(isset($arr_line['repeating_event_id']) && $arr_line['repeating_event_id'] > 0) {
	  			// repeating events must have the same id
	  			$arr_line['id'] = $arr_line['repeating_event_id'];
	  			
	  			

	  			if(isset($arr_rep_events[$arr_line['repeating_event_id']])) {
	  				$arr_line['rep_start'] 	= $arr_rep_events[$arr_line['repeating_event_id']]['startdate'];
	  				$arr_line['rep_end'] 	= $arr_rep_events[$arr_line['repeating_event_id']]['enddate'];
	  				$arr_line['rep_event'] = $arr_rep_events[$arr_line['repeating_event_id']];
	  			}

	  			$arr_line['rep_start_day'] = (int) date('d', strtotime($arr_line['rep_start']));
	  		}



			$arr_content[] = $arr_line;

		}



		return $arr_content;
	}

    public static function insertEvent($frm_submitted, $current_user_id='') {

		global $link;
		global $hostname, $username , $password, $database, $eventTable, $repeatTable;

	//	$link = mysqli_connect($hostname, $username, $password, $database);
			if($link === FALSE) {
				$error= "Database connection failed";
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			mysqli_set_charset( $link , 'utf8' );


	//		if(empty($current_user_id)) {
	//			$current_user_id = $_SESSION['calendar-uid']['uid'];
	//		}

			$str_query = 'INSERT INTO '.$eventTable.'  (title, user_id, video_url, category, repeating_event_id, genre, instructor_link,spots, assigned_user, img_url, level, size, class_time, instructor, date_start, time_start, date_end, time_end, create_date) ' .
				'VALUES ("'.$frm_submitted['title'].'",'.
					'"'.$_SESSION['user_id'].'",'.
					'"'.$frm_submitted['video_url'].'",'.
					'"'.$frm_submitted['category'].'",'.
					(!empty($frm_submitted['rep_event_id']) ? $frm_submitted['rep_event_id'] : 0).','.
					'"'.$frm_submitted['genre'].'",'.
					'"'.$frm_submitted['instructor_link'].'",'.
					'"'.$frm_submitted['spots'].'",'.
					'"'.$_SESSION['user_name'].'",'.
					'"'.$frm_submitted['img_url'].'",'.
					'"'.$frm_submitted['level'].'",'.
					'"'.$frm_submitted['size'].'",'.
					'"'.$frm_submitted['class_time'].'",'.
					'"'.$frm_submitted['inst'].'",'.
					'"'.date('Y-m-d', $frm_submitted['date_start']).'",'.
					'"'.date('H:i:s', $frm_submitted['date_start']).'",'.
					'"'.date('Y-m-d', $frm_submitted['date_end']).'",'.
					'"'.date('H:i:s', $frm_submitted['date_end']).'",'. 
					'"'.date('Y-m-d H:i:s').'"'.
					')';
			$obj_result = mysqli_query($link, $str_query);

			if($obj_result !== false) { 
					$str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end ' .
							'FROM '.$eventTable.' WHERE event_id = '.mysqli_insert_id($link);
					$eventid = mysqli_insert_id($link);
					$obj_result = mysqli_query($link, $str_query);
					$arr_event = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
					
					$str_query_stats = 'INSERT INTO trilakes_groupxroom_usage_stats (event_id,title, user_id, evparticpant_name, assigned_user, user_level, ip_address, email_sent,text_sent,  instructor, invite) ' .
							'VALUES ("'.$eventid.'",
									"'.$frm_submitted['title'].'",
									"'.$_SESSION['user_id'].'",
									"'.$_SESSION['user_name'].'",
									"'.$_SESSION['user_name'].'",
									"'.$_SESSION['user_level'].'",
									"'.$_SERVER['REMOTE_ADDR'].'",
									"'.$frm_submitted['email_sent'].'",
									"'.$frm_submitted['text_sent'].'",
									"'.$frm_submitted['instructor'].'",
									"'.$frm_submitted['invite'].'"
									)';
					$obj_result_stats = mysqli_query($link, $str_query_stats);
					
					
					$arr_event2 = mysqli_fetch_array($obj_result_stats, MYSQLI_ASSOC);
					//$arr_event['editable'] 	= $arr_event['user_id'] == $_SESSION['user_id'] ? true : false;
					
			} else {
				return false;
			}

			return $arr_event;
			return $arr_event2;

    }

    public static function updateEvent($frm_submitted) {
		global $link;
	global $hostname, $username , $password, $database, $eventTable, $repeatTable;


	//	$link = mysqli_connect($hostname, $username, $password, $database);
			if($link === FALSE) {
				$error= "Database connection failed";
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			mysqli_set_charset( $link , 'utf8' );

	  	$arr_event = array();
		
	

		$str_query = 'UPDATE '.$eventTable.' SET date_start = "'.date('Y-m-d', $frm_submitted['date_start']).'" ' .
				', date_end = "'.date('Y-m-d', $frm_submitted['date_end']).'" ' .
				', time_start = "'.(isset($frm_submitted['allDay']) && $frm_submitted['allDay'] ? '00:00:00' : date('H:i:s', $frm_submitted['date_start'])).'" ' .
				', time_end = "'.(isset($frm_submitted['allDay']) && $frm_submitted['allDay'] ? '00:00:00' : date('H:i:s', $frm_submitted['date_end'])).'" ' .
				', create_date = "'.date('Y-m-d H:i:s').'" ' .' WHERE event_id = '.$frm_submitted['event_id']; 

		$obj_result = mysqli_query($link, $str_query);

		if($obj_result !== false) {
			$str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end ' .
					'FROM '.$eventTable.' WHERE event_id = '.$frm_submitted['event_id'];
			$obj_result = mysqli_query($link, $str_query);
			$arr_event = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

		}
		return $arr_event;
	}

	public static function resizeEvent($frm_submitted) {
		global $link;
global $hostname, $username , $password, $database, $eventTable, $repeatTable;

//$link = mysqli_connect($hostname, $username, $password, $database);
    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );

		$frm_submitted['date_start'] -= TIME_OFFSET;
	  	$frm_submitted['date_end'] -= TIME_OFFSET;

		$str_query = 'UPDATE '.$eventTable.' SET date_start = "'.date('Y-m-d', $frm_submitted['date_start']).'" ' .
				', date_end = "'.date('Y-m-d', $frm_submitted['date_end']).'" ' .
				', time_start = "'.date('H:i:s', $frm_submitted['date_start']).'" ' .
				', time_end = "'.date('H:i:s', $frm_submitted['date_end']).'" ' .
					' WHERE event_id = '.$frm_submitted['event_id'];

		if(defined('USERS_CAN_CHANGE_ITEMS_FROM_OTHERS') && USERS_CAN_CHANGE_ITEMS_FROM_OTHERS) {
			// don't check on user_id
		} else {
			$str_query .= ' AND user_id = '. $_SESSION['calendar-uid']['uid'];
		}

		$obj_result = mysqli_query($link, $str_query);

		if($obj_result !== false) {
			$str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end ' .
					'FROM '.$eventTable.' WHERE event_id = '.$frm_submitted['event_id'];
			$obj_result = mysqli_query($link, $str_query);
			$arr_event = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

			$arr_event['allDay'] 	= $arr_event['allDay'] == 0 ? false : true;
			$arr_event['allowEdit'] 	= User::canEdit($arr_event['user_id']);
			$arr_event['deletable'] 	= User::canDelete($arr_event['user_id']);

			return $arr_event;
		}
		return false;
	}

//	public static function deleteEvent($int_event_id) {
//		global $link;
//
//		$str_query = 'DELETE FROM events WHERE event_id = '.$int_event_id;
//
//		if(defined('USERS_CAN_DELETE_ITEMS_FROM_OTHERS') && USERS_CAN_DELETE_ITEMS_FROM_OTHERS) {
//			// don't check on user_id
//		} else {
//			$str_query .= ' AND user_id = '. $_SESSION['calendar-uid']['uid'];
//		}
//
//		$obj_result = mysqli_query($link, $str_query);
//
//		if($obj_result !== false) {
//			return true;
//		}
//		return false;
//	}

	public static function deleteEvent($frm_submitted) {
		global $link;
		global $hostname, $username , $password, $database, $eventTable, $repeatTable;


	//	$link = mysqli_connect($hostname, $username, $password, $database);
			if($link === FALSE) {
				$error= "Database connection failed";
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			mysqli_set_charset( $link , 'utf8' );

		if(isset($frm_submitted['delete_all']) && $frm_submitted['delete_all'] === true && isset($frm_submitted['rep_event_id']) && $frm_submitted['rep_event_id'] > 0) {

			// part of repeat , delete all items
			$str_query = 'DELETE FROM '.$eventTable.' WHERE repeating_event_id = '.$frm_submitted['rep_event_id'];
			$obj_result = mysqli_query($link, $str_query);

			if($obj_result !== false) {

				// delete row from repeating_events
				$str_query = 'DELETE FROM '.$repeatTable.' WHERE rep_event_id = '.$frm_submitted['rep_event_id'];
				$obj_result = mysqli_query($link, $str_query);
				if($obj_result !== false) {
					return true;
				}


			}

		} else if($frm_submitted['rep_event_id'] > 0) {

			// part of repeat , delete only this one
			$str_query = 'DELETE FROM '.$eventTable.' WHERE event_id = '.$frm_submitted['event_id'];
			$obj_result = mysqli_query($link, $str_query);

			// the pattern is broken, put bln_broken in db,
			// so that we know it that we have to show the repair pattern button
			$str_update_query = 'UPDATE '.$repeatTable.' SET bln_broken = 1 WHERE rep_event_id = ' . $frm_submitted['rep_event_id'];
			$res = mysqli_query($link, $str_update_query);


			if($obj_result !== false) {

				// check if there is only one item left in this repeat,
				// if yes then delete row in repeating_events table and set repeating_event_id to 0 in events table
				if(self::OneHasLeftOfThisRepeat($frm_submitted['rep_event_id'])) {
					$str_query = 'DELETE FROM '.$repeatTable.' WHERE rep_event_id = '.$frm_submitted['rep_event_id'];
					$obj_result = mysqli_query($link, $str_query);
					if($obj_result !== false) {

						// update row
						//$str_update_query = 'UPDATE events SET repeating_event_id = 0 WHERE event_id = '.$frm_submitted['event_id'];
						$str_update_query = 'UPDATE '.$eventTable.' SET repeating_event_id = 0 WHERE repeating_event_id = '.$frm_submitted['rep_event_id'];
						$obj_result = mysqli_query($link, $str_query);
						if($obj_result !== false) {
							return true;
						}
					} else {
						echo 'Error while trying to delete the row in repeating_events table';
					}
				}
				return true;

			} else {
				echo 'Error while trying to delete the event';
			}
		} else {

			/*
			 * normal event
			 */
			$str_query = 'DELETE FROM '.$eventTable.' WHERE event_id = '.$frm_submitted['event_id'];
			$obj_result = mysqli_query($link, $str_query);

			if($obj_result !== false) {


				return true;
			}
		}

		return false;
	}

	public static function OneHasLeftOfThisRepeat($rep_event_id) {
		global $link;
		global $hostname, $username , $password, $database, $eventTable, $repeatTable;

	//	$link = mysqli_connect($hostname, $username, $password, $database);
			if($link === FALSE) {
				$error= "Database connection failed";
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			mysqli_set_charset( $link , 'utf8' );

		$arr_content = array();

		$str_query = 'SELECT * FROM '.$eventTable.' WHERE repeating_event_id = '.$rep_event_id;
		$obj_result = mysqli_query($link, $str_query);

		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_content[] = $arr_line;
		}
		if(count($arr_content) == 1) {
			return true;
		} else {
			return false;
		}
	}

	public static function insertRepeatingEvent($arr_dates, $frm_submitted) {
		global $link;
		global $hostname, $username , $password, $database, $eventTable, $repeatTable;

	//	$link = mysqli_connect($hostname, $username, $password, $database);
			if($link === FALSE) {
				$error= "Database connection failed";
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			mysqli_set_charset( $link , 'utf8' );


		// set the first date as source

		$str_query = 'INSERT INTO '.$repeatTable.' ( rep_interval, weekdays, monthday, startdate, enddate) VALUES '.
					'("'.$frm_submitted['interval'].'",'.
					'"'.$frm_submitted['weekdays'].'",'.
					'"'.$frm_submitted['monthday'].'",'.
					'"'.date('Y-m-d', $frm_submitted['date_start']).'",'.
					'"'.date('Y-m-d', $frm_submitted['date_end']).'")';

		$res = mysqli_query($link, $str_query);

		$int_rep_event_id = mysqli_insert_id($link);
		
			
		

		
		$str_query_r = 'INSERT INTO '.$eventTable.' (title, user_id, video_url, category, repeating_event_id, genre, instructor_link,spots, assigned_user, img_url, level, size, class_time, instructor, date_start, time_start, date_end, time_end, create_date) VALUES ';

		foreach($arr_dates as $key=>$date) {
			if($key != 0) {
				$str_query_r .= ',';//
			}
			$str_query_r .= '("'.$frm_submitted['title'].'",'.
					'"'.$_SESSION['user_id'].'",'.
					'"'.$frm_submitted['video_url'].'",'.
					'"'.$frm_submitted['category'].'",'.
					$int_rep_event_id.','.
					'"'.$frm_submitted['genre'].'",'.
					'"'.$frm_submitted['instructor_link'].'",'.
					'"'.$frm_submitted['spots'].'",'.
					'"'.$frm_submitted['user_name'].'",'.
					'"'.$frm_submitted['img_url'].'",'.
					'"'.$frm_submitted['level'].'",'.
					'"'.$frm_submitted['size'].'",'.
					'"'.$frm_submitted['class_time'].'",'.
					'"'.$frm_submitted['inst'].'",'.
					'"'.$date.'",'.
					'"'.date('H:i:s', $frm_submitted['date_start']).'",'.
					'"'.$date.'",'.
					'"'.date('H:i:s', $frm_submitted['date_end']).'",'.
					'"'.date('Y-m-d H:i:s').'"'.
 					')';

		}

		$res = mysqli_query($link, $str_query_r);
		
				 $str_query_rep = 'UPDATE '.$eventTable.' SET repeating_event_id = '.$int_rep_event_id.' WHERE event_id='.$_POST['event_id'].'';
		
		$rep = mysqli_query($link, $str_query_rep);
		
	}

	public static function updateRepeatingEvent($arr_dates, $frm_submitted) {
		global $link;
		global $hostname, $username , $password, $database, $eventTable, $repeatTable;


	//	$link = mysqli_connect($hostname, $username, $password, $database);
			if($link === FALSE) {
				$error= "Database connection failed";
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			mysqli_set_charset( $link , 'utf8' );


		/*
		 * check if interval or weekdays have changed
		 */

		//TODO other intervals like month, 2weeks and year


		// get the pattern
		$str_select_repeating_query = 'SELECT * FROM '.$repeatTable.' WHERE rep_event_id = ' . $frm_submitted['rep_event_id'];
		$obj_result1 = mysqli_query($link, $str_select_repeating_query);
		$arr_repeat_pattern = mysqli_fetch_array($obj_result1, MYSQLI_ASSOC);

		// update repeating_events table
		$str_update_query = 'UPDATE '.$repeatTable.' SET rep_interval = "'.$frm_submitted['interval'].'", ' .
								'weekdays = "'.$frm_submitted['weekdays'].'",' .
								'monthday = "'.$frm_submitted['monthday'].'",' .
								'startdate = "'.date('Y-m-d', $frm_submitted['date_start']).'",' .
								'enddate = "'.date('Y-m-d', $frm_submitted['date_end']).'" ' .
							'WHERE rep_event_id = ' . $frm_submitted['rep_event_id'];


		$res = mysqli_query($link, $str_update_query);

		// update events
		$str_update_events_query = 'UPDATE '.$eventTable.' SET title = "'.$frm_submitted['title'].'", ' .

										'`time_start` = "'.date('H:i:s', $frm_submitted['date_start']).'", '. 
										'`time_end` = "'.date('H:i:s', $frm_submitted['date_end']).'", '.
										
									'WHERE `repeating_event_id` = ' . $frm_submitted['rep_event_id'];
		$res = mysqli_query($link, $str_update_events_query);

		/*
		 * get all existing items in this pattern
		 */
		$arr_events_from_this_pattern = array();

		$str_events_query = 'SELECT * FROM '.$eventTable.' WHERE repeating_event_id = '. $frm_submitted['rep_event_id'];
		$obj_result1 = mysqli_query($link, $str_events_query);
		while ($arr_line = mysqli_fetch_array($obj_result1, MYSQLI_ASSOC)) {
			$arr_events_from_this_pattern[] = $arr_line;

		}

		/*
		 * find deleted weekdays
		 */
		$current_user_id = '';
		foreach($arr_events_from_this_pattern as $event) {
			if(!in_array($event['date_start'], $arr_dates)) {
				// delete
				$obj_result_del = mysqli_query($link, 'DELETE FROM '.$eventTable.' WHERE event_id = '.$event['event_id']);

			} else {
				$search = array_search($event['date_start'], $arr_dates);
				unset($arr_dates[$search]);
			}
			$time_start = $event['time_start'];
			$time_end = $event['time_end'];
			$current_user_id = $event['user_id'];
		}

		/*
		 * added/changed weekdays
		 */
		if($frm_submitted['repair_pattern']
			|| $arr_repeat_pattern['weekdays'] != $frm_submitted['weekdays']
			|| $arr_repeat_pattern['startdate'] != date('Y-m-d', $frm_submitted['date_start'])
			|| $arr_repeat_pattern['enddate'] != date('Y-m-d', $frm_submitted['date_end'] )
			// || $arr_event['rep_interval'] != $frm_submitted['interval']
			) {
			// add new items to pattern
			foreach($arr_dates as $day) {
				$frm_submitted['date_start'] = strtotime($day .' '. $time_start);
				$frm_submitted['date_end'] = strtotime($day .' '. $time_end);
				self::insertEvent($frm_submitted, $current_user_id);
			}
		}

		if($frm_submitted['repair_pattern']) {
			// set bln_broken to 0
			$str_update_query = 'UPDATE '.$repeatTable.' SET bln_broken = 0 WHERE rep_event_id = ' . $frm_submitted['rep_event_id'];
			$res = mysqli_query($link, $str_update_query);

		}

	}

	public static function deleteRepeatingEvent($rep_event_id) {
		global $link;
		global $hostname, $username , $password, $database, $eventTable, $repeatTable;

	//	$link = mysqli_connect($hostname, $username, $password, $database);
			if($link === FALSE) {
				$error= "Database connection failed";
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			mysqli_set_charset( $link , 'utf8' );

		// delete row from repeating_events
		$str_query = 'DELETE FROM '.$repeatTable.' WHERE rep_event_id = '.$rep_event_id;
		$obj_result = mysqli_query($link, $str_query);
		if($obj_result !== false) {
			return true;
		}
	}

	public static function setEventToNotRepeating($rep_event_id) {
		global $link;
		global $hostname, $username , $password, $database, $eventTable, $repeatTable;

	//	$link = mysqli_connect($hostname, $username, $password, $database);
			if($link === FALSE) {
				$error= "Database connection failed";
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			mysqli_set_charset( $link , 'utf8' );

		$str_update_query = 'UPDATE '.$eventTable.' SET repeating_event_id = 0 WHERE repeating_event_id = '.$rep_event_id;
		$obj_result = mysqli_query($link, $str_update_query);
		if($obj_result !== false) {
			return true;
		}
	}

	public static function isTimeAvailable($frm_submitted) {
		global $link;
		global $hostname, $username , $password, $database, $eventTable, $repeatTable;
		
	//	$link = mysqli_connect($hostname, $username, $password, $database);
			if($link === FALSE) {
				$error= "Database connection failed";
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			mysqli_set_charset( $link , 'utf8' );

//		$str_query = 'SELECT * FROM events WHERE user_id = '. $_SESSION['mylogbook-uid']['uid'].
//				'AND '.$frm_submitted['date_start'].' BETWEEN date_start AND date_end';
		$str_query = 'SELECT * FROM '.$eventTable.' WHERE user_id = '. $_SESSION['calendar-uid']['uid'].
						' AND "'.date('Y-m-d H:i:s', $frm_submitted['date_start']).'" BETWEEN concat_ws(" ",date_start,time_start) AND concat_ws(" ",date_end,time_end)';
		$obj_result = mysqli_query($link, $str_query);

		$arr_event = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
		if(!empty($arr_event)) {
			return false;
		}

		return true;
	}

	public static function getSmallCalEvents($cal_id, $year=null, $month=null, $day=null) {
		global $link;
		$arr_content = array();

		$str_query = 'SELECT * , concat_ws( " ", date_start, time_start ) AS START , concat_ws( " ", date_end, time_end ) AS END FROM '.$eventTable.' as e
						WHERE 1 '.

						($year !== null && $month !== null ? ' and ((MONTH(date_start) = "'.$month.'" AND YEAR(date_start) = "'.$year.'"  ) OR (MONTH(date_end) = "' .$month.'" AND YEAR(date_end) = "'.$year.'" ))' : '').
						($day !== null ? ' and ("'.$year.'-'.$month.'-'.$day.'" BETWEEN date_start and date_end)' : '').
						' 	group by e.event_id ORDER BY date_start ';
		$obj_result = mysqli_query($link, $str_query);

	  	while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_content[] = $arr_line;

		}
		return $arr_content;
	}

	public static function getSmallCalItems($arr_content) {
		$arr_result = array();

		foreach($arr_content as $event) {
			if(!isset($arr_result[substr($event['date_start'],8,2)])) {
				$arr_result[ltrim(substr($event['date_start'],8,2), '0')] = array();
			}
			// meerdaags event
			if($event['date_end'] != $event['date_start']) {
				$days_in_between = Utils::getDaysBetween($event['date_start'], $event['date_end']); 
				foreach($days_in_between as $day) {
					$arr_result[ltrim(substr($day,8,2), '0')][] = $event;
				}

			} else {
				$arr_result[ltrim(substr($event['date_start'],8,2), '0')][] = $event;
			}

		}
		return $arr_result;
	}


	public static function getListviewEvents($frm_submitted, $bln_widget=true) {
		global $link;
	global $hostname, $username , $password, $database, $eventTable, $repeatTable;

// $link = mysqli_connect($hostname, $username, $password, $database);
    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );

		$arr_content = array();

		if(defined('AGENDA_VIEW_AMOUNT_DAYS_TO_SHOW') && AGENDA_VIEW_AMOUNT_DAYS_TO_SHOW > 0) {
			$amount_days_to_show = AGENDA_VIEW_AMOUNT_DAYS_TO_SHOW;
		} else {
			$amount_days_to_show = 5;
		}

		$str_query = 'SELECT * ,event_id AS id, concat_ws( " ", date_start, time_start ) AS START , concat_ws( " ", date_end, time_end ) AS END ' .
				'FROM events as e WHERE 1 ';

		if(!empty($frm_submitted['from'])) {
			$date_to = date('Y-m-d', strtotime('+6 MONTH', strtotime($frm_submitted['from'])));
			$date_from = $frm_submitted['from'];

			$str_query .= ' AND ((date_start > "'.$date_from.'" AND date_start <= "'.$date_to.'")
							OR (
							date_start < "'.$date_from.'"
							AND (date_end BETWEEN "'.$date_from.'" AND "'.$date_to.'")
							))';
			$str_query .= '	ORDER BY date_start ASC';
		} else if(!empty($frm_submitted['to'])) {
			$date_from = date('Y-m-d', strtotime('-6 MONTH', strtotime($frm_submitted['to'])));
			$date_to = $frm_submitted['to'];

			$str_query .= ' AND (date_end < "'.$date_to.'" AND date_start >= "'.$date_from.'"	)';
			$str_query .= '	ORDER BY date_start DESC';
		} else {
			$date_from = date('Y-m-d');
			$date_to = date('Y-m-d', strtotime('+6 MONTH', strtotime($date_from)));

			$str_query .= ' AND (date_start >= DATE( NOW( ) )
							OR (
							date_start < DATE( NOW( ) )
							AND date_end >= DATE( NOW( ) )
							))';
			$str_query .= '	ORDER BY date_start ASC';
		}

		// if you want to show a specific amount of items
		//$str_query .= '	LIMIT '.$amount_days_to_show;


		$obj_result = mysqli_query($link, $str_query);

	  	while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_content[] = $arr_line;
		}

		$arr_result = self::getAgendaItems($arr_content, $frm_submitted);

		// if you want to show a specific amount of days
		$arr_result = array_slice($arr_result, 0, $amount_days_to_show);

		// when ->to the order is desc
		// after array_slice we want to sort normal again (asc)
		ksort($arr_result);

		$arr_return = array();
		$arr_return['results'] = $arr_result;
		$arr_return['hide_from'] = false;
		$arr_return['hide_to'] = false;

		//$arr_result = Utils::sortTwodimArrayByKey($arr_result, 'date_start');

		if(!empty($frm_submitted['from'])) {
			if(count($arr_result) < $amount_days_to_show) {
				$arr_return['hide_from'] = true;
			}
		}
		if(!empty($frm_submitted['to'])) {
			if(count($arr_result) < $amount_days_to_show) { 
				$arr_return['hide_to'] = true;
			}
		}

		return $arr_return;
	}


	public static function getAgendaItems($arr_content, $frm_submitted=array()) {
		$arr_result = array();
		global $hostname, $username , $password, $database, $eventTable, $repeatTable;

// $link = mysqli_connect($hostname, $username, $password, $database);
    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );


		foreach($arr_content as $event) {

			// moreday event
			if($event['date_end'] != $event['date_start']) {

				if((defined('COMBINE_MOREDAYS_EVENTS') && COMBINE_MOREDAYS_EVENTS) && $frm_submitted['combine_moreday_events'] !== false) {
					if(defined('ENDDATE_OF_COMBINED_MOREDAYS_EVENTS_TEXT')) {
						$str_enddate_and_title = str_replace('%ENDDATE%', strftime("%A, %d %B", strtotime($event['date_end'])), ENDDATE_OF_COMBINED_MOREDAYS_EVENTS_TEXT);	// example: 'to %ENDDATE% ,inclusive'
						$event['title'] = $str_enddate_and_title.': '.$event['title'];
					} else {
						$event['title'] = '-> '.date('D, d M', strtotime($event['date_end'])).': '.$event['title'];
					}

					$arr_result[$event['date_start']][] = $event;
				} else {
					$days_in_between = Utils::getDaysBetween($event['date_start'], $event['date_end']);
					foreach($days_in_between as $day) {
						$arr_result[$day][] = $event;
				    }
				}

			} else {
				$arr_result[$event['date_start']][] = $event;
			}
		}
		return $arr_result;
	}
}
?>