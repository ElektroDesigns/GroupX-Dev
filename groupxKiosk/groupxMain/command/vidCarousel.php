<?php
 
header("Content-Type: application/json");
header("access-control-allow-origin: *");
session_start();
require '../../login/dbc.php';




switch($_GET['action']) {
	case 'groupx':
		groupx();
		break;
			

}

function groupx() {
global $link,$groupx_ClassTable,$live_ClassTable;

    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );
$groupx_Content = array();
	$str_query = 'SELECT * FROM '.$groupx_ClassTable.' WHERE active = "yes" and category = "groupx" ORDER BY event_id ASC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$groupx_Content[] = $arr_line;
		}
$groupxSpin_Content = array();
	$str_query = 'SELECT * FROM '.$groupx_ClassTable.' WHERE active = "yes"  and category = "GroupXspin" ORDER BY event_id DESC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$groupxSpin_Content[] = $arr_line;
		}
$live_spin_Content = array();
	$str_query = 'SELECT * FROM '.$live_ClassTable.' WHERE category = "spinning" ORDER BY event_id DESC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$live_spin_Content[] = $arr_line;
		}
$live_mp_Content = array();
	$str_query = 'SELECT * FROM '.$live_ClassTable.' WHERE category = "Multi-Purpose" ORDER BY event_id DESC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$live_mp_Content[] = $arr_line;
			
		}
$movie_Content = array();
	$str_query = 'SELECT * FROM '.$groupx_ClassTable.' WHERE category = "movies" ORDER BY event_id DESC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$movie_Content[] = $arr_line;
			
		}
$senior_Content = array();
	$str_query = 'SELECT * FROM '.$groupx_ClassTable.' WHERE active = "yes" and category = "groupx" and genre = "srFitness" ORDER BY event_id DESC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$senior_Content[] = $arr_line;
		}
$kids_Content = array();
	$str_query = 'SELECT * FROM '.$groupx_ClassTable.' WHERE active = "yes" and category = "groupx" and genre ="kidsFitness" ORDER BY event_id DESC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$kids_Content[] = $arr_line;
		}
$beginner_Content = array();
	$str_query = 'SELECT * FROM '.$groupx_ClassTable.' WHERE active = "yes" and category = "groupx" and level = "beginner" ORDER BY event_id DESC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$beginner_Content[] = $arr_line;
		}
$advanced_Content = array();
	$str_query = 'SELECT * FROM '.$groupx_ClassTable.' WHERE active = "yes" and category = "groupx" and level = "advanced" ORDER BY event_id DESC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$advanced_Content[] = $arr_line;
		}
$cardio_Content = array();
	$str_query = 'SELECT * FROM '.$groupx_ClassTable.' WHERE active = "yes" and category = "groupx" and genre = "cardio" ORDER BY event_id DESC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$cardio_Content[] = $arr_line;
		}
$dance_Content = array();
	$str_query = 'SELECT * FROM '.$groupx_ClassTable.' WHERE active = "yes" and category = "groupx" and genre = "dance" ORDER BY event_id DESC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$dance_Content[] = $arr_line;
		}
$yoga_Content = array();
	$str_query = 'SELECT * FROM '.$groupx_ClassTable.' WHERE active = "yes" and category = "groupx" and genre = "yoga" ORDER BY event_id DESC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$yoga_Content[] = $arr_line;
		}
$strength_Content = array();
	$str_query = 'SELECT * FROM '.$groupx_ClassTable.' WHERE active = "yes" and category = "groupx" and genre = "strength" ORDER BY event_id DESC';
	$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_line['editable'] 	= true ;
			$strength_Content[] = $arr_line;
		}

echo json_encode(Array("groupx" => $groupx_Content,
					"groupxSpin" => $groupxSpin_Content,
					"liveSpin" => $live_spin_Content,
					"liveMP" => $live_mp_Content,
					"movies" => $movie_Content,
					"senior" => $senior_Content,
					"kids" => $kids_Content,
					"beginner" => $beginner_Content,
					"advanced" => $advanced_Content,
					"cardio" => $cardio_Content,
					"dance" => $dance_Content,
					"yoga" => $yoga_Content,
					"strength" => $strength_Content
					));

}
?>