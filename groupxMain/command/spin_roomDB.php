<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");



$hostname = 'localhost';
$username = 'admin';
$password = 'austin67';  
$database = 'ppymca';
$eventtable ='trilakes_spinroom_events';


$db_connection = mysql_connect($hostname,$username,$password) or die (mysql_error());
$db_select = mysql_select_db($database) or die (mysql_error());  

// Query the database.
$str_query = 'SELECT * FROM `trilakes_spinroom_events` WHERE time_start <= DATE_ADD(NOW() + interval -1 hour, INTERVAL 1 HOUR) and time_end >= DATE_ADD(NOW() + interval -1 hour, INTERVAL 1 HOUR) and date_start = CURDATE()';  
if (!$str_query) {
    die('Invalid query:Database Error ' . mysql_error());
} 

$query_result = mysql_query($str_query) or die (mysql_error());

$num_results = (mysql_num_rows($query_result));
if ($num_results > 0) {
	$row = mysql_fetch_assoc($query_result);
	
	$video_result['video_url'] = preg_replace('/\s\s+/', ' ', $row['video_url']);
     $video_result['category'] = $row['category'];
 	 
	$video = new StdClass;
	$video->message = ($video_result);
        echo $_GET['callback']. '('. json_encode ($video) . ')';

	}
else {	

	
//echo $video_result;
}
?>