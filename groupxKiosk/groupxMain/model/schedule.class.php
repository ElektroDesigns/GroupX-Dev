<?php
//require_once CLASSES_DIR.'/config.class.php';

class Schedule {

	const MINUTE = 60;
	const HOUR = 3600;
	const DAY = 86400;
	const WEEK = 604800;

	public static function fetchValues($date) {
		global $obj_db;
		$arr_values = array();
		$arr_schedule = array();

		$str_query_r = 'SELECT * FROM schedule WHERE DATE(`last_exec_date`) = "'. $date.'" ORDER BY last_exec_date DESC LIMIT 1';

		$res1 = mysqli_query($obj_db, $str_query_r);

		if($res1 !== false) {
			while (	$arr_line = mysqli_fetch_array($res1, MYSQLI_ASSOC)) {
				$arr_schedule[] = $arr_line;
			}
		}

		if(!is_null($arr_schedule) && is_array($arr_schedule)) {
			foreach($arr_schedule as $c) {
				$arr_values[$c['jobname']] = $c['last_exec_date'];

			}
			return $arr_values;
		} else {
			return false;
		}

	}

	public static function run($bln_execute=false) {

		if(date("d") == '10') {
			// do something every month on day 10
		}

	//	if($bln_execute || date('w') == '2' || date('w') == '4' || date('w') == '6') {

			// get done jobs of today
			$arr_schedule = self::fetchValues(date('Y-m-d'));
			$bln_execute = false;

			/**
			 * SQLDUMP
			 */
			if($arr_schedule === false) {
				$bln_execute = true;
			}
			if(isset($arr_schedule['sqldump'])) {
				if(!defined('SCHEDULE_SQLDUMP')) {
					define('SCHEDULE_SQLDUMP', 86400);	// set this config in configs/config.php
				}
				$bln_execute = strtotime($arr_schedule['sqldump']) + SCHEDULE_SQLDUMP < time();
			}
			if(!isset($arr_schedule['sqldump']) || $bln_execute) {
				error_log('saveSqlDumpToFile');
				$bln_success = self::makeMysqldump('file');
				error_log($bln_success ? 'saveSqlDumpToFile success' : 'saveSqlDumpToFile failure');
			}

	//	}
	}

	static public function makeMysqldump($type = 'text') {

		//connect with database
		$link = mysql_connect(DBHOST,DBUSER,DBPASS) or die('No database connection could be established');
		mysql_select_db(DBNAAM,$link) or die('No database selected');

		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}

		$return = '';

		//cycle through
		foreach($tables as $table)
		{
			$result = mysql_query('SELECT * FROM '.$table);
			$num_fields = mysql_num_fields($result);

			$arr_create_table = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));

			$str_create_table = "\n\n".$arr_create_table[1].";\n\n";

			$return .= str_replace('CREATE TABLE `'.$table.'` (', 'CREATE TABLE IF NOT EXISTS `'.$table.'` (', $str_create_table);

			for ($i = 0; $i < $num_fields; $i++)
			{
				while($row = mysql_fetch_row($result))
				{
					$return.= 'INSERT INTO `'.$table.'` VALUES(';
					for($j=0; $j<$num_fields; $j++)
					{
						$row[$j] = addslashes($row[$j]);
						$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						if ($j<($num_fields-1)) { $return.= ','; }
					}
					$return.= ");\n";
				}
			}
			$return.="\n\n\n";
		}

		$int_result = file_put_contents(FULLCAL_DIR.'/system/dbdump/caldump_'.date("Y-m-d_H-i").'.txt', $return);

	  	if($int_result > 0) {
			// met bijgewerkte datum in schedule tabel zetten
			$str_query = 'REPLACE INTO `schedule` SET `jobname` = "sqldump" , `last_exec_date` = NOW() ';

			global $obj_db;

			$obj_result = mysqli_query($obj_db, $str_query);
			if($obj_result !== false) {
				return true;
			}
		}
		return false;
	}

}
?>
