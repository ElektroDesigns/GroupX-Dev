<?php
/*
 * Created on 17-okt-2011
 * author Paul Wolbers
 *
 */
require_once '../include/default.inc.php';
 if (!isset($_SESSION['user_id'])) {    
        $_SESSION['user_id'] = null;
		$_SESSION['user_level'] = null;
		}  

if(isset( $_GET['action'])) {
	switch($_GET['action']) {
		case 'start':
			getEvents();
			break;
		case 'add':
			addEvent();
			break;
		case 'update':
			updateEvent();
			break;
		case 'resize':
			resizeEvent();
			break;
		case 'del':
			deleteEvent();
			break;
	}
}


function addEvent() {

	global $error;

	$arr_submit 		= array(
		
		array('date_end',		'int',   		false, 	''),
		array('date_start',		'int',   		false, 	''),
		array('title',			'string',   	false, 	''),
		array('video_url',		'string',   	false, 	''),
		array('category',		'string',   	false, 	''),
		array('genre',			'string',   	false, 	''),
		array('instructor_link','string',   	false, 	''),
		array('spots',			'string',   	false, 	''),
		array('size',		'string',   	false, 	''),		
		array('img_url',		'string',   	false, 	''),
		array('class_time',		'string',   	false, 	''),
		array('rank',			'string',   	false, 	''),
		array('instructor',		'string',   	false, 	''),			
		array('description',	'string',   	false, 	''),
		
		array('interval',		'string',   	false, 	''),
		array('weekdays',		'string',   	false, 	''),
		
	);

$frm_submitted      = validate_var($arr_submit);
if(defined('TIMEZONE') && TIMEZONE != '' ) {
	$timezone_name = TIMEZONE;
} else {
	$timezone_name = date_default_timezone_get();
}
date_default_timezone_set($timezone_name);
$gettimezone = new DateTimeZone($timezone_name);
$offset = ($gettimezone->getOffset(new DateTime) );
define('TIME_OFFSET', $offset);
	

	if(!isset($frm_submitted['date_start']) ) {
    	$frm_submitted['date_start'] = time();
  	}

	// time offset
  	if(!empty($frm_submitted['time_start'])) {
        $frm_submitted['date_start'] = strtotime(date('Y-m-d', $frm_submitted['date_start']).' '.$frm_submitted['time_start']);
    } else {
        $frm_submitted['date_start'] -= TIME_OFFSET;
    }

	if(!isset($frm_submitted['date_end']) || empty($frm_submitted['date_end'])) {
		$frm_submitted['date_end'] = $frm_submitted['date_start'];
	
	} else {
       	if(!empty($frm_submitted['time_end'])) {
            $frm_submitted['date_end'] = strtotime(date('Y-m-d', $frm_submitted['date_end']).' '.$frm_submitted['time_end']);
        } else {
            $frm_submitted['date_end'] -= TIME_OFFSET;
        }
	}


	

	if(empty($error)) {

		// for now make sql dump of database, set interval in config.php
//		Schedule::run();

		// check if repeating event
		if(isset($frm_submitted['interval']) && ($frm_submitted['interval'] == 'W' ||
					$frm_submitted['interval'] == '2W' ||
					$frm_submitted['interval'] == 'M' ||
					$frm_submitted['interval'] == 'Y')) {
			// weekday

			$arr_days = Utils::getDaysInPattern($frm_submitted);
			$arr_event = Events::insertRepeatingEvent($arr_days, $frm_submitted);

			echo json_encode(array('success'=>true));exit;
		} else {
			// check if this calendar allows overlapping
//			if(!Settings::getSetting('allow_overlapping')) {
//				if(Events::isTimeAvailable($frm_submitted) || $frm_submitted['date_end'] != $frm_submitted['date_start']) {
//					$arr_event = Events::insertEvent($frm_submitted);
//					echo json_encode(array('success'=>true, 'event'=>$arr_event	));exit;
//				} else {
//					echo json_encode(array('success'=>false, 'error'=>'Overlapping'));exit;
//				}
//			} else {
				$arr_event = Events::insertEvent($frm_submitted);
				echo json_encode(array('success'=>true, 'event'=>$arr_event	));exit;
//			}
		}
	} else {
		echo json_encode(array('success'=>false, 'error'=>$error));
      	exit;
	}
}

function updateEvent1() {
	global $error;

	$color = '#3366CC';
	if(defined('DEFAULT_COLOR')) {
		$color = DEFAULT_COLOR;
		if(empty($color)) {
			$color = '#3366CC';
		}
	}

/*	$arr_submit 		= array(
		array('color',			'string',   	false, 	$color),
		array('date_end',		'int',   		true, 	''),
		array('date_start',		'int',   		true, 	''),
		array('title',			'string',   	false, 	''),
		array('allDay',			'bool',   		false, 	''),
		array('event_id',		'int',   		true, 	''),
	);*/

	$frm_submitted      = validate_var($arr_submit);

	if(empty($error)) {

		if(!empty($frm_submitted['title'])) {
			$frm_submitted['title'] = stripslashes($frm_submitted['title']);

			$arr_event = Events::updateEvent($frm_submitted);

			if(!$arr_event) {
				echo json_encode(array('success'=>false));exit;
			} else {
				echo json_encode(array('success'=>true, 'event'=>$arr_event	));exit;
			}
		}
	}
	echo json_encode(array('success'=>false));exit;
}

function updateEvent() {

	global $error;

	$arr_submit 		= array(
	//	array('event_id',		'int',   		false, 	''),
		array('date_end',		'int',   		false, 	''),
		array('date_start',		'int',   		false, 	''),
		array('title',			'string',   	false, 	''),
		array('video_url',		'string',   	false, 	''),
		array('category',		'string',   	false, 	''),
		array('genre',			'string',   	false, 	''),
		array('instructor_link','string',   	false, 	''),
		array('spots',			'string',   	false, 	''),
		array('size',			'string',   	false, 	''),		
		array('img_url',		'string',   	false, 	''),
		array('class_time',		'string',   	false, 	''),
		array('rank',			'string',   	false, 	''),
		array('instructor',		'string',   	false, 	''),			
		array('description',	'string',   	false, 	''),
		
		array('interval',		'string',   	false, 	''),
		array('weekdays',		'string',   	false, 	''),
	);

	$frm_submitted      = validate_var($arr_submit); 
//print_r($frm_submitted);
	if(empty($error)) {
		
		if(!empty($frm_submitted['time_end'])) {
            $frm_submitted['date_end'] = strtotime(date('Y-m-d', $frm_submitted['date_end']).' '.$frm_submitted['time_end']);
        } else {
            $frm_submitted['date_end'] -= TIME_OFFSET;
        }
        if(!empty($frm_submitted['time_start'])) {
            $frm_submitted['date_start'] = strtotime(date('Y-m-d', $frm_submitted['date_start']).' '.$frm_submitted['time_start']);
        } else {
            $frm_submitted['date_start'] -= TIME_OFFSET;
        }

		// check if repeating event

			// existing repeating event
			if($frm_submitted['date_start'] !== $frm_submitted['date_end'] && isset($frm_submitted['interval']) && ($frm_submitted['interval'] == 'W' ||
						$frm_submitted['interval'] == '2W' ||
						$frm_submitted['interval'] == 'M' ||
						$frm_submitted['interval'] == 'Y')) {

				if($frm_submitted['rep_event_id'] > 0) {
					$arr_days = Utils::getDaysInPattern($frm_submitted); return $arr_days; exit;
					$arr_event = Events::updateRepeatingEvent($arr_days, $frm_submitted);

					echo json_encode(array('success'=>true));exit;

				} else {
					// normal event changed to repeating pattern
					$arr_days = Utils::getDaysInPattern($frm_submitted);
					$arr_event = Events::insertRepeatingEvent($arr_days, $frm_submitted);

					if($frm_submitted['event_id'] > 0) {
						// delete old normal event
						$bln_deleted = Events::deleteEvent(array('event_id'=>$frm_submitted['event_id'],
																	'rep_event_id'=>0));

					}
					echo json_encode(array('success'=>true));exit;

				}


			} else {

				if($frm_submitted['rep_event_id'] > 0) {
					// this event changed from repeating event to an normal day event

					$bln_deleted = Events::deleteEvent(array('event_id'		=> $frm_submitted['event_id'],
															'rep_event_id' 	=> $frm_submitted['rep_event_id'],
															'delete_all'	=> true));

//					// delete repeating_event
//					Events::deleteRepeatingEvent($frm_submitted['rep_event_id']);
//
//					// delete events with this rep_event_id
//
//
					// insert new daily event
					$frm_submitted['repeating_event_id'] = 0;
					$frm_submitted['rep_event_id'] = 0;
					$arr_event = Events::insertEvent($frm_submitted);

					$arr_event['remove_old_event'] = true;
					echo json_encode(array('success'=>true, 'event'=>$arr_event	));exit;
//
//					//Events::setEventToNotRepeating($frm_submitted['rep_event_id']);


				} else {
					$arr_event = Events::updateEvent($frm_submitted);
					echo json_encode(array('success'=>true, 'event'=>$arr_event	));exit;
				}

			}

//
//		if($frm_submitted['interval'] == 'W') {
//			// weekday
//
//			$arr_days = Utils::getDaysInPattern($frm_submitted);
//			$arr_event = Events::updateRepeatingEvent($arr_days, $frm_submitted);
//
//			echo json_encode(array('success'=>true));exit;
//		}
//		else {
//			$arr_days = Utils::getDaysInPattern($frm_submitted);
//			$arr_event = Events::insertRepeatingEvent($arr_days, $frm_submitted);
//			//$arr_event = Events::updateEvent($frm_submitted);
//			echo json_encode(array('success'=>true, 'event'=>$arr_event	));exit;
//		}

	} else {
		echo json_encode(array('success'=>false, 'error'=>$error));exit;
	}
	echo json_encode(array('success'=>false));exit;
}


function resizeEvent() {
	global $error;

	$arr_submit 		= array(
		array('event_id',		'int',   		true, 	''),
		array('date_end',		'int',   		true, 	''),
		array('date_start',		'int',   		true, 	''),
	);

	$frm_submitted      = $arr_submit  ;

	if(!$error)	 {
		$arr_event = Events::resizeEvent($frm_submitted);

		echo json_encode(array('success'=>true, 'event'=>$arr_event));exit;
	} else {
		echo json_encode(array('success'=>false));exit;
	}
}

function deleteEvent() {
	global $error;

	$arr_submit 		= array(
		array('event_id',		'int',   		true, 	''),
		array('rep_event_id',	'int',   		false, 	''),
		array('delete_all',		'bool',   		false, 	''),
	);

	$frm_submitted      = validate_var($arr_submit);
	if(empty($error)) {
		$bln_deleted = Events::deleteEvent($frm_submitted);
	} else {
		$bln_deleted = false;
	}
	echo json_encode(array('success'=>$bln_deleted));exit;

}

function getEvents() {
	global $error;

	
	$arr_submit 		= array(
		array('start',		'int',   		true, 	''),
		array('end',		'int',   		true, 	''),
		//array('sd',		'string',   		false, 	''),
		//array('ft',		'string',   		false, 	''),
	);

	$frm_submitted      = validate_var($arr_submit); 

	if(!$error)	 {


		$arr_content = Events::getEvents($frm_submitted);
		
	} else {
		$arr_content = array();
	}

	echo json_encode($arr_content); 
}

function getDaysBetween($sStartDate, $sEndDate){
	$sStartDate 	= date("Y-m-d", strtotime($sStartDate));
  	$sEndDate 		= date("Y-m-d", strtotime($sEndDate));
	$aDays[] 		= $sStartDate;
  	$sCurrentDate 	= $sStartDate;

  	while($sCurrentDate < $sEndDate){
		$sCurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
    	$aDays[] = $sCurrentDate;
  	}
  	return $aDays;
}


?>