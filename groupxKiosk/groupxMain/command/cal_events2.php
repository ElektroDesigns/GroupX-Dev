<?php
 
header("Content-Type: application/json");
session_start();
require '../../login/dbc.php';
if (!isset($_SESSION['eventTable'])) {    
        $_SESSION['eventTable'] = $_POST['eventTable'];
		$_SESSION['repeatTable'] = $_POST['classTable'];
		}    
if (isset($_POST['eventTable'])) {    
        $_SESSION['eventTable'] = $_POST['eventTable'];
		$_SESSION['classTable'] = $_POST['classTable'];
		}    
		$eventTable = $_SESSION['eventTable'];
		$classTable= $_SESSION['classTable'];	
/*
$eventtable ='trilakes_groupxroom_events'; 
$classtable ='trilakes_groupxroom_usage_stats';	
*/

// test with session user_id
 if (!isset($_SESSION['user_id'])) {    
        $_SESSION['user_id'] = null;
		$_SESSION['user_level'] = null;
		}    
	
switch($_GET['action']) {
	case 'add':
		addEvent();
		break;
	case 'start':
		getEvents();
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
	case 'mail':
		mailEvent();
		break;
	case 'reserveEvent':
		reserveEvent();
		break;
	case 'allRes':
		allRes();
		break;
	case 'remove':
		remove();
		break;
	case 'play':
		play();
		break;
}
function getEvents() {

	$arr_submit 		= array(
		array('start'),
		array('end'),
		
	);

	$frm_submitted      = $arr_submit;

	if(!$error)	 {


		$arr_content = Events::getEvents($frm_submitted);
	} else {
		$arr_content = array();
	}

	echo json_encode($arr_content);

}
function addEvent() {
$timezone = new DateTimeZone("Etc/GMT+6");
$offset   = $timezone->getOffset(new DateTime);

global $link,$eventTable,$classTable;


    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );
 
$_POST['date_start'] -= $offset;
$_POST['date_end'] -= $offset;


//SELECT *, usrname FROM events WHERE usrname = "groupxadmin" and concat_ws(" ",date_start,time_start) > NOW()

$str_query = 'INSERT INTO '.$eventTable.' (title, user_id, video_url, category, genre, instructor_link,spots, usrname, img_url, level, size, class_time, instructor, date_start, time_start, date_end, time_end, allday) ' .
			'VALUES ("'.$_POST['title'].'",
					'.$_SESSION['user_id'].',
					"'.$_POST['video_url'].'",
					"'.$_POST['category'].'", 
					"'.$_POST['genre'].'",
					"'.$_POST['instructor_link'].'",
					"'.$_POST['spots'].'",
					"'.$_SESSION['user_name'].'",
					"'.$_POST['img_url'].'",
					"'.$_POST['level'].'",
					"'.$_POST['size'].'",
					"'.$_POST['class_time'].'",
					"'.$_POST['inst'].'",
					"'.date('Y-m-d', $_POST['date_start']).'",
					"'.date('H:i:s', $_POST['date_start']).'",
					"'.date('Y-m-d', $_POST['date_end']).'",
					"'.date('H:i:s', $_POST['date_end']).'"'.
	(date('H:i:s', $_POST['date_start']) == '00:00:00' && date('H:i:s', $_POST['date_end']) == '00:00:00' ? ' ,1' : ' ,0').')';
	$obj_result = mysqli_query($link, $str_query);

	$str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end ' .
			'FROM '.$eventTable.' WHERE event_id = '.mysqli_insert_id($link);
	$eventid = mysqli_insert_id($link);
	$obj_result = mysqli_query($link, $str_query);
	
	$str_query2 = 'INSERT INTO '.$classTable.' (event_id, title, user_id, evparticpant_name, assigned_user, user_level, ip_address, email_sent, text_sent, instructor, invite) ' .
			'VALUES ("'.$eventid.'",
					"'.$_SESSION['title'].'",
					"'.$_SESSION['user_id'].'",
					"'.$_SESSION['user_name'].'",
					"'.$_SESSION['user_name'].'",
					"'.$_SESSION['user_level'].'",
					"'.$_SERVER['REMOTE_ADDR'].'",
					"'.$_SESSION['email_sent'].'",
					"'.$_SESSION['text_sent'].'",
					"'.$_SESSION['instructor'].'",
					"'.$_SESSION['invite'].'"
					)';
	$obj_result2 = mysqli_query($link, $str_query2);
	
	$arr_event = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
	$arr_event2 = mysqli_fetch_array($obj_result2, MYSQLI_ASSOC);
	$arr_event['editable'] 	= $arr_event['user_id'] == $_SESSION['user_id'] ? true : false;

	echo json_encode(array('success'=>true, 'event'=>$arr_event	));
	echo json_encode(array('success'=>true, 'event2'=>$arr_event2	));exit;

}
function updateEvent() {
$timezone = new DateTimeZone("Etc/GMT+6");
$offset   = $timezone->getOffset(new DateTime);

global $link,$eventTable,$classTable;

    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );
$_POST['date_start'] -= $offset;
$_POST['date_end'] -= $offset;
  if ($_SESSION['user_level'] = 5) {
	$str_query = 'UPDATE '.$eventTable.' SET date_start = "'.date('Y-m-d', $_POST['date_start']).'" ' .
			(isset($_POST['title']) ? ', title = "'.$_POST['title'].'"' : '').
			', date_end = "'.date('Y-m-d', $_POST['date_end']).'" ' .
			', time_start = "'.date('H:i:s', $_POST['date_start']).'" ' .
			', time_end = "'.date('H:i:s', $_POST['date_end']).'" ' .
			
		' WHERE event_id = '.$_POST['event_id'];
	$obj_result = mysqli_query($link, $str_query);
  }else{
	$str_query = 'UPDATE '.$eventTable.' SET date_start = "'.date('Y-m-d', $_POST['date_start']).'" ' .
			(isset($_POST['title']) ? ', title = "'.$_POST['title'].'"' : '').
			', date_end = "'.date('Y-m-d', $_POST['date_end']).'" ' .
			', time_start = "'.date('H:i:s', $_POST['date_start']).'" ' .
			', time_end = "'.date('H:i:s', $_POST['date_end']).'" ' .
			
		' WHERE event_id = '.$_POST['event_id'].' AND user_id = '. $_SESSION['user_id'];
	$obj_result = mysqli_query($link, $str_query);
	}
		
	$str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end ' . 'FROM '.$eventtable.' WHERE event_id = '.$_POST['event_id'];
	$obj_result = mysqli_query($link, $str_query);
	$arr_event = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

	
	$arr_event['editable'] 	= $arr_event['user_id'] == $_SESSION['user_id'] ? true : false;

	echo json_encode(array('success'=>true, 'event'=>$arr_event	));exit;
}
function deleteEvent() {
global $link,$eventTable,$classTable;


    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );

	if ($_SESSION['user_level'] = 5) { 
			$str_query = 'DELETE FROM '.$eventTable.' WHERE event_id = '.$_POST['event_id'] ;
			$obj_result = mysqli_query($link, $str_query);
			$str_query2 = 'DELETE FROM '.$classTable.'  WHERE event_id = '.$_POST['event_id'] ;
			$obj_result2 = mysqli_query($link, $str_query2);
			}
	else {
			$str_query = 'DELETE FROM '.$eventTable.' WHERE event_id = '.$_POST['event_id'].' AND user_id = '. $_SESSION['user_id'];
			$obj_result = mysqli_query($link, $str_query);
			$str_query2 = 'DELETE FROM '.$classTable.'  WHERE event_id = '.$_POST['event_id'].' AND COUNT(evparticipant_id) > 1';
			$obj_result2 = mysqli_query($link, $str_query2);
	}
	echo json_encode(array('success'=>true));exit;
}

function reserveEvent() {
$timezone = new DateTimeZone("Etc/GMT+6");
$offset   = $timezone->getOffset(new DateTime);

global $link,$eventTable,$classTable;
$spots = $_POST['spots'];

    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );
	$str_query = "UPDATE ".$eventTable." SET spots = '$spots' WHERE event_id =".$_POST['event_id'];
					
	$obj_result = mysqli_query($link, $str_query);
	
	$str_query = 'INSERT INTO '.$classTable.'  (event_id, user_id, evparticpant_name, user_name, user_level, ip_address) ' .
			'VALUES ("'.$_POST['event_id'].'",
					"'.$_SESSION['user_id'].'",
					"'.$_SESSION['user_name'].'",
					"'.$_SESSION['user_name'].'", 
					"'.$_SESSION['user_level'].'",
					"'.$_SERVER['REMOTE_ADDR'].'"
					)';
						
	$obj_result = mysqli_query($link, $str_query);
	$str_query = 'SELECT * FROM '.$classTable.' WHERE event_id = '.$_POST['event_id'];
	$obj_result = mysqli_query($link, $str_query);
	$str_query = 'SELECT spots FROM '.$eventTable.' WHERE event_id = '.$_POST['event_id'];
	$obj_result = mysqli_query($link, $str_query);
	$arr_event = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
	$arr_event['editable'] == $_SESSION['user_id'] ? true : false;
	echo json_encode(array('success'=>true, 'event'=>$arr_event	));exit;
	}
function allRes() {


$timezone = new DateTimeZone("Etc/GMT+6");
$offset   = $timezone->getOffset(new DateTime);

global $link,$eventTable,$classTable;


    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );

	$str_query = 'SELECT * FROM '.$classTable.' WHERE event_id ='.$_POST['var1'];
	
	$obj_result = mysqli_query($link, $str_query);

	while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			
			$arr_content[] = $arr_line;
		}
	
$data = json_encode($arr_content);
$str_query2 = 'SELECT spots FROM '.$eventTable.' WHERE event_id ='.$_POST['var1'];
	
	$obj_result2 = mysqli_query($link, $str_query2);
	$spotsData = json_encode($obj_results2);
	echo $data; exit;

	}
function remove() {
global $link,$eventTable,$classTable;
$spots = $_POST['spots'];

    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );
			$str_query = 'DELETE FROM '.$classTable.' WHERE event_id = '.$_POST['event_id'].' AND user_id = '. $_SESSION['user_id'];
			$obj_result = mysqli_query($link, $str_query);
			
			$str_query = "UPDATE ".$eventTable." SET spots = (spots-1) WHERE event_id =".$_POST['event_id'];
			$obj_result = mysqli_query($link, $str_query);
			
			$str_query = 'DELETE FROM '.$eventTable.' WHERE event_id = '.$_POST['event_id'].' AND spots = 0';
			$obj_result = mysqli_query($link, $str_query);
	
	echo json_encode(array('success'=>true));exit;
}	
function play() {
$timezone = new DateTimeZone("Etc/GMT+6");
$offset   = $timezone->getOffset(new DateTime);

global $link,$eventTable,$classTable;


    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );
 
$_POST['date_start'] -= $offset;
$_POST['date_end'] -= $offset;

$str_query = 'INSERT INTO '.$eventTable.'  (title, user_id, video_url, category, repeating_event_id, genre, instructor_link,spots, assigned_user, img_url, level, size, class_time, instructor, date_start, time_start, date_end, time_end, create_date) ' .
				'VALUES ("'.$_POST['title'].'",'.
					'"'.$_SESSION['user_id'].'",'.
					'"'.$_POST['video_url'].'",'.
					'"'.$_POST['category'].'",'.
					(!empty($_POST['rep_event_id']) ? $_POST['rep_event_id'] : 0).','.
					'"'.$_POST['genre'].'",'.
					'"'.$_POST['instructor_link'].'",'.
					'"'.$_POST['spots'].'",'.
					'"'.$_SESSION['user_name'].'",'.
					'"'.$_POST['img_url'].'",'.
					'"'.$_POST['level'].'",'.
					'"'.$_POST['size'].'",'.
					'"'.$_POST['class_time'].'",'.
					'"'.$_POST['inst'].'",'.
					'"'.date('Y-m-d', $_POST['date_start']).'",'.
					'"'.date('H:i:s', $_POST['date_start']).'",'.
					'"'.date('Y-m-d', $_POST['date_end']).'",'.
					'"'.date('H:i:s', $_POST['date_end']).'",'. 
					'"'.date('Y-m-d H:i:s').'"'.
					')';
	$obj_result = mysqli_query($link, $str_query);

	$str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end ' .
			'FROM '.$eventTable.' WHERE event_id = '.mysqli_insert_id($link);
	$eventid = mysqli_insert_id($link);
	$obj_result = mysqli_query($link, $str_query);
	
	$str_query_stats = 'INSERT INTO '.$classTable.' (event_id,title, user_id, evparticpant_name, assigned_user, user_level, ip_address, email_sent,text_sent,  instructor, invite) ' .
							'VALUES ("'.$eventid.'",
									"'.$_POST['title'].'",
									"'.$_SESSION['user_id'].'",
									"'.$_SESSION['user_name'].'",
									"'.$_SESSION['user_name'].'",
									"'.$_SESSION['user_level'].'",
									"'.$_SERVER['REMOTE_ADDR'].'",
									"'.$_POST['email_sent'].'",
									"'.$_POST['text_sent'].'",
									"'.$_POST['instructor'].'",
									"'.$_POST['invite'].'"
									)';
					$obj_result_stats = mysqli_query($link, $str_query_stats);
	
	$arr_event = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
	$arr_event2 = mysqli_fetch_array($obj_result2, MYSQLI_ASSOC);
	$arr_event['editable'] 	= $arr_event['user_id'] == $_SESSION['user_id'] ? true : false;

	echo json_encode(array('success'=>true, 'event'=>$arr_event	));exit;
 
}
?>