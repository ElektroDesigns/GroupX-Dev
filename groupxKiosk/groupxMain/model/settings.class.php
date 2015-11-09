<?php

class Settings {

	public static function getSetting($name, $user_id='') {
		global $obj_db;

		$arr_user = User::getUser();

		if(!is_null($arr_user['user_id'])) {
			$str_query = 'SELECT `value` FROM `settings` WHERE `name` = "'.$name.'"' ;
			$str_query .= ' AND `user_id` = "'.$arr_user['user_id'].'"';

	     	$obj_result = mysqli_query($obj_db, $str_query);
	 		$arr_content = array();

			if($obj_result !== false) {
				$arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

				if($arr_line !== false && !empty($arr_line) && !empty($arr_line['value'])) {
					return $arr_line['value'];
				}
			}
		}

		return '';
	}

	public static function getSettings($user_id='') {
		global $obj_db;
		$arr_result = array();
		$arr_return = array();

      	$str_query = 'SELECT * FROM `settings` WHERE 1' ;

      	if(!empty($user_id)) {
      		$str_query .= ' AND `user_id` = "'.$user_id.'"';
      	}
     	$obj_result = mysqli_query($obj_db, $str_query);
 		$arr_content = array();

		while($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_result[$arr_line['name']] = $arr_line['value'];
		}

		return $arr_result;
	}

	public static function saveSetting($name, $value, $section='', $user_id='') {
		global $obj_db;

	    $str_query = 'REPLACE INTO settings (`value`, `name`, `section`, `user_id`, `update_date`) ' .
	    		'VALUES ("'.$value.'", "'.$name.'", "'.$section.'", "'.$user_id.'", "'.date('Y-m-d H:i:s').'") ';

	    $obj_result = mysqli_query($obj_db, $str_query);
	    return $obj_result;
	}

	public static function saveSettings($arr_settings, $section='', $user_id='') {
		global $obj_db;

		foreach($arr_settings as $key => $value) {
			if(!empty($value)) {
				$str_query = 'REPLACE INTO settings (`value`, `name`, `section`, `user_id`, `update_date`) ' .
		    		'VALUES ("'.$value.'", "'.$key.'", "'.$section.'", "'.$user_id.'", "'.date('Y-m-d H:i:s').'") ';

		    	$obj_result = mysqli_query($obj_db, $str_query);
			}

		}
		return true;
	}

	public static function getLanguage($user_id=-1) {
		$language = '';
		if($user_id > 0) {
			$language = Settings::getSetting('language', $user_id);
		}
		if(empty($language)) {
			if(ALLOW_ACCESS_BY == 'free') {
				// check if admin has settings
				$language = self::getLanguageFromAdmin(true);
			}
			if(empty($language)) {
				if(defined('LANGUAGE')) {
					$language = LANGUAGE;
				} else {
					$language = 'EN';
				}
			}
		}
		return $language;
	}

	public static function getLanguageFromAdmin($bln_only_admin=false) {
		global $obj_db;

		$language = '';
		$str_query = ' SELECT * '.
					' FROM `settings` s'.
					' LEFT JOIN users u ON ( s.user_id = u.user_id )'.
					' WHERE s.name = "language"'.
					' AND u.usertype = "admin"'.
					' LIMIT 1 ';

     	$obj_result = mysqli_query($obj_db, $str_query);
 		$arr_content = array();

		$arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

		if($arr_line !== false && !empty($arr_line)) {
			if(!empty($arr_line['value'])) {
				$language = $arr_line['value'];
			}
		}

		if($bln_only_admin) {
			return $language;
		}

		if(empty($language)) {
			if(defined('LANGUAGE')) {
				$language = LANGUAGE;
			} else {
				$language = 'EN';
			}
		}
		return $language;
	}


	public static function getDefaultView($user_id=-1) {
		$default_view = '';
		if($user_id > 0) {
			$default_view = Settings::getSetting('default_view', $user_id);
		}
		if(empty($default_view)) {
			if(ALLOW_ACCESS_BY == 'free') {
				// check if admin has settings
				$default_view = self::getDefaultViewFromAdmin(true);
			}
			if(empty($default_view)) {
				if(defined('DEFAULT_VIEW')) {
					$default_view = DEFAULT_VIEW;
				} else {
					$default_view = 'month';
				}
			}
		}
		return $default_view;
	}

	public static function getDefaultViewFromAdmin($bln_only_admin=false) {
		global $obj_db;

		$default_view = '';
		$str_query = ' SELECT * '.
					' FROM `settings` s'.
					' LEFT JOIN users u ON ( s.user_id = u.user_id )'.
					' WHERE s.name = "default_view"'.
					' AND u.usertype = "admin"'.
					' LIMIT 1 ';

     	$obj_result = mysqli_query($obj_db, $str_query);
 		$arr_content = array();

		$arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

		if($arr_line !== false && !empty($arr_line)) {
			if(!empty($arr_line['value'])) {
				$default_view = $arr_line['value'];
			}
		}
		if($bln_only_admin) {
			return $default_view;
		}

		if(empty($default_view)) {
			if(defined('DEFAULT_VIEW')) {
				$default_view = DEFAULT_VIEW;
			} else {
				$default_view = 'month';
			}
		}
		return $default_view;
	}

	public static function getTimezone($user_id) {
		$default_view = Settings::getSetting('timezone', $user_id);
		if(empty($default_view)) {
			if(defined('TIMEZONE')) {
				$default_view = TIMEZONE;
			} else {
				$default_view = '';
			}

		}
		return $default_view;
	}
}
?>
