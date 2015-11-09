<?php
/*
 * Created on 17-okt-2011
 * author Paul Wolbers
 */

function database_connect() {
   global $obj_db;

    // get calendar database
 
    	DEFINE('DBHOST', 'localhost');
		DEFINE('DBUSER', 'admin');
		DEFINE('DBPASS', 'austin67');
    	DEFINE('DBNAAM', 'test'); 
		DEFINE('GROUPXEVENTS', 'trilakes_groupxroom_events');
		DEFINE('ROOM_STATS', 'trilakes_groupxroom_usage_stats');


    $obj_db = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAAM);
    if($obj_db === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $obj_db , 'utf8' );
}

?>
