<?php

class Utils {

  

	public static function getDaysBetween($sStartDate, $sEndDate){
		if(is_numeric($sStartDate) && is_numeric($sEndDate)) {
			$sStartDate 	= date("Y-m-d", $sStartDate);
	  		$sEndDate 		= date("Y-m-d", $sEndDate);
		} else {
			$sStartDate 	= date("Y-m-d", strtotime($sStartDate));
	  		$sEndDate 		= date("Y-m-d", strtotime($sEndDate));
		}

		$aDays		= array();
	  	$sCurrentDate 	= $sStartDate;
//print_r($sCurrentDate);
	  	while($sCurrentDate < $sEndDate){//print_r($sCurrentDate);
			$sCurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
	    	//print_r($sCurrentDate);echo(" , ");
			$aDays[] = $sCurrentDate;
		//	print_r($aDays);exit;
	  	}//print_r($aDays);exit;
	  	return $aDays;
	}

	public static function getDaysInPattern($frm_submitted) {
		$arr_return = array();

		$days_in_between = Utils::getDaysBetween($frm_submitted['date_start'], $frm_submitted['date_end']);

		$arr_weekdays = array();

		if($frm_submitted['interval'] == 'W') {
			$str_interval_days = substr($frm_submitted['weekdays'],1);	// trim first comma
 
			if(strstr($str_interval_days, ',')) {
				$arr_interval_days = explode(',', $str_interval_days);
			} else {
				$arr_interval_days = array($str_interval_days);
			}


			foreach($arr_interval_days as $day) {
				$arr_weekdays[$day] = $day;
			}

			foreach($days_in_between as $event_date) {
				if(array_key_exists(date('N', strtotime($event_date)), $arr_weekdays)) {
					$arr_return[] = $event_date;
				}
			}
		} else if($frm_submitted['interval'] == 'M') {

			if($frm_submitted['monthday'] == 'dom') {

				/*
				 * dom: day of month
				 */

				// what day is startdate?
				$int_monthday = date('d', $frm_submitted['date_start']);
				$arr_return[] = date('Y-m-d', $frm_submitted['date_start']);

				$plus_four_weeks = self::getPlusOneMonthDate($frm_submitted['date_start'], $int_monthday);

				foreach($days_in_between as $event_date) {

					if($event_date == $plus_four_weeks) {
						if(date('d', strtotime($plus_four_weeks)) == $int_monthday) {
							$arr_return[] = $event_date;
							$plus_four_weeks = self::getPlusOneMonthDate($event_date, $int_monthday);
						}
					}
				}

			} else if($frm_submitted['monthday'] == 'dow') {

				/*
				 * dow: day of week
				 */

				// what weekday is startdate?
				$int_weekday = self::getWeekdayFromDate($frm_submitted['date_start']);
				$arr_return[] = date('Y-m-d', $frm_submitted['date_start']);

				$plus_four_weeks = self::getPlusFourWeeksDate($frm_submitted['date_start'], $int_weekday);

				foreach($days_in_between as $event_date) {

					if($event_date == $plus_four_weeks) {
						if(date('N', strtotime($plus_four_weeks)) == $int_weekday) {
							$arr_return[] = $event_date;
							$plus_four_weeks = self::getPlusFourWeeksDate($event_date, $int_weekday);
						}
					}
				}
			}
		}

		return $arr_return;
	}

	public static function getWeekdayFromDate($str_date, $bln_textual=false) {
		if(is_int($str_date)) {
			$ts_date = $str_date;
		} else {
			$ts_date = strtotime($str_date);
		}


		if(!$bln_textual) {
			return (int)date('w', $ts_date);;
		} else {
			$date = strftime('%A', $ts_date);
		}
		return $date;
	}

	public static function getNextWeekDate($start_date, $weekday) {
		//$oneweekfromnow = date('Y-m-d', strtotime("+1 week", strtotime($start_date)));
		$oneweekfromnow = strtotime("+1 week", strtotime($start_date));	//timestamp

		// extra check
		if(date('N', $oneweekfromnow) == $weekday) {
			return $oneweekfromnow;
		}
		return false;
	}

	public static function getPlusFourWeeksDate($start_date, $weekday) {
		//$oneweekfromnow = date('Y-m-d', strtotime("+1 week", strtotime($start_date)));

		if(is_int($start_date)) {
			$ts_date = $start_date;
		} else {
			$ts_date = strtotime($start_date);
		}

		$oneweekfromnow = strtotime("+4 week", $ts_date);	//timestamp

		// extra check
		if(date('N', $oneweekfromnow) == $weekday) {
			return date('Y-m-d', $oneweekfromnow);
		}
		return false;
	}

	public static function getPlusOneMonthDate($start_date, $monthday) {
		//$oneweekfromnow = date('Y-m-d', strtotime("+1 week", strtotime($start_date)));

		if(is_int($start_date)) {
			$ts_date = $start_date;
		} else {
			$ts_date = strtotime($start_date);
		}

		$onemonthfromnow = strtotime("+1 month", $ts_date);	//timestamp

		// extra check
		if(date('d', $onemonthfromnow) == $monthday) {
			return date('Y-m-d', $onemonthfromnow);
		}
		return false;
	}

/*
	public static function generatePassword($length = 10){
	  $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!#$%*_+|';

	  $str = '';
	  $max = strlen($chars) - 1;

	  for ($i=0; $i < $length; $i++)
	    $str .= $chars[mt_rand(0, $max)];

	  return $str;
	}
*/
	public static function setLocaleLanguage($lang = '') {

		if(!empty($lang)) {
			$language = $lang;
		} else {

			if(USE_CLIENTS_LANGUAGE) {
				$language = '';	// the clients language will be set
				$locale = setlocale(LC_ALL, '');


			} else {
				$language = strtolower(LANGUAGE);
			}

		}
//echo $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		if(!empty($language)) {
			switch($language) {
				case 'en':
					$locale = array('eng','en_EN.UTF-8');
					break;
				case 'de':
					$locale = array('deu','de_DE.UTF-8');
					break;
				case 'fr':
					$locale = array('fra','fr_FR.UTF-8','fr_FR');
					break;
				case 'nl':
					$locale = array('nld','nl_NL@euro, nl_NL.UTF-8, nl_NL, nld_nld, dut, nla, nl, nld, dutch');
					break;
				default:
					$locale = array('eng','en_EN.UTF-8');
			}
		}

		//setlocale(LC_ALL, NULL);

		setlocale(LC_ALL, $locale);

		header("Content-Type: text/html;charset=UTF-8");
		header("Content-Language: $language");

	}
/*
    public static function sortTwodimArrayByKey($two_dim_array, $key_to_sort_with, $dir='ASC', $case_sensitive=false) {

		if(!empty($two_dim_array)) {

			$arr_result 		= array();
			$arr_values 		= array();
			$bln_third_dim 		= false;

			if(strstr($key_to_sort_with, '/')) {
				$arr_dims 		= explode('/', $key_to_sort_with);
				$sec_dim_key 	= $arr_dims[0];
				$third_dim_key 	= $arr_dims[1];
				$bln_third_dim 	= true;
			}

			// array maken met de waardes waarop gesorteerd moet worden
			foreach($two_dim_array as $arr_second_dim) {
			    if(is_array($arr_second_dim[$key_to_sort_with])) {
			    	echo 'opgegeven key is een array. Gebruik key1/key2';break;
			    }
			   	$arr_values[] = $bln_third_dim ? $arr_second_dim[$sec_dim_key][$third_dim_key] : $arr_second_dim[$key_to_sort_with];
			}

			// sorteren ( de key's krijgen de nieuwe volgorde )
			if($case_sensitive) {
			    sort($arr_values);
			} else {
			    natcasesort($arr_values);
			}

			// nieuwe array maken met de juiste volgorde
			foreach($arr_values as $value) {
			    foreach($two_dim_array as $key=>$val) {
			       	if($value == ($bln_third_dim ? $val[$sec_dim_key][$third_dim_key] : $val[$key_to_sort_with])) {
				       	$arr_result[] = $two_dim_array[$key];
				        unset ($two_dim_array[$key]);
				    }
			    }
			}

			if($dir == 'DESC') {
			     $arr_result = array_reverse($arr_result);
			}

		    return $arr_result;
		} else {
		      return array();
		}
	}

	public static function getSubstring($string, $str_start,$start_plus_or_min=0, $str_end='', $end_plus_or_min=0) {

		$string_min_eerste_gedeelte = stristr($string, $str_start);
		//$str_start_pos = stripos($string, $str_start);

		$string_min_eerste_gedeelte_min_startstring = substr($string_min_eerste_gedeelte,strlen($str_start)+$start_plus_or_min);

		if(empty($str_end)) {
			return $string_min_eerste_gedeelte_min_startstring;
		}
		$eindpositie_karakter = stripos($string_min_eerste_gedeelte_min_startstring, $str_end);

		$str_result = substr($string_min_eerste_gedeelte_min_startstring,0 , $eindpositie_karakter+$end_plus_or_min);

		return $str_result;

	}
*/
	public static function getLanguage() {
		$language = Settings::getSetting('language');
		if(empty($language)) {
			if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
				if (class_exists('Locale')) {
					$language = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
				} else {
					$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
				}
			} else {
				$language = strtolower(LANGUAGE);
			}
		}
		return $language;
	}

/*
	public static function getSmallCloud($user_id=0) {
		global $obj_db;

		if(!defined('AMOUNT_WORDS')) {
			define('AMOUNT_WORDS', 20);
		}
		if(!defined('CASE_SENSITIVE')) {
			define('CASE_SENSITIVE', false);   // true, false
		}
		if(!defined('SORTING_ORDER')) {
			define('SORTING_ORDER', 'natcasesort');   // natcasesort, sort, grootnaarklein, random
		}
		if(!defined('SHOW_SMALL_CLOUD_COUNTS')) {
			define('SHOW_SMALL_CLOUD_COUNTS', false);
		}
		if(!defined('USE_COLORS')) {
			define('USE_COLORS',  false);
		}
		if(!defined('HIDE_ONE_COUNT_WORDS')) {
			define('HIDE_ONE_COUNT_WORDS', false);
		}


		$url = '';
		$msg = '';
		$str_url_content = '';

		//if($user_id == 0) {
		//	$user_id = $_SESSION['calendar-uid']['uid'];
		//}
		$str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end FROM events ' .
				' WHERE 1 ';

		if(isset($_SESSION['calendar-uid']) && defined('USERS_CAN_SEE_ITEMS_FROM_OTHERS') && !USERS_CAN_SEE_ITEMS_FROM_OTHERS && ALLOW_ACCESS_BY !== 'free') {
			$str_query .= ' AND `user_id` = '.$_SESSION['calendar-uid']['uid'];
		}

		$str_query .= ' ORDER BY `start`';

		$obj_result = mysqli_query($obj_db, $str_query);

	  	while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
		//	$arr_line['title'] = str_replace('<br />', ' ', $arr_line['title']);
	    //$arr_line['title'] = str_replace( "\n", ' ', $arr_line['title']);

			if(CASE_SENSITIVE) {
				$str_url_content .= ' '.$arr_line['title'];
			} else {
				$str_url_content .= ' '.strtolower($arr_line['title']);
			}
		}

		if(empty($str_url_content)) {
			return array();
		} else {

			global $EXCLUDED_WORDS_WORDCLOUD;
        	if(!empty($EXCLUDED_WORDS_WORDCLOUD)) {
        		$str_url_content = str_replace($EXCLUDED_WORDS_WORDCLOUD, ' ', $str_url_content);
        	}

        	$str_url_content = trim($str_url_content);
			$str_url_content = rtrim($str_url_content);

			$str_url_content = self::strip_script($str_url_content);

			if(strstr($str_url_content, ' ')) {
				$arr_url_content = explode(" ", $str_url_content);
			} else {
				$arr_url_content = array($str_url_content);
			}

			$arr_url_content = self::cleanArray($arr_url_content);
			$arr_words = array();
			//$arr_url_content = array_unique($arr_url_content);

			// woorden tellen
			foreach($arr_url_content as $r) {
				if(strlen($r) > 2 && !is_numeric($r)) {
                    if(CASE_SENSITIVE) {
    					if(in_array($r, $arr_words) ) {
    						if(isset($arr_cnt_words[$r])) {
    							$arr_cnt_words[$r] ++ ;
    						}

    					} else {
    						$arr_cnt_words[$r] = 1;
    						$arr_words[]  = $r;
    					}
    				} else {
    					$rlc = strtolower($r);
    			    	if(in_array($rlc, $arr_words) ) {
    						if(isset($arr_cnt_words[$rlc])) {
    							$arr_cnt_words[$rlc] ++ ;
    						}

    					} else {
    						$arr_cnt_words[$r] = 1;
    						$arr_words[]  = $rlc;
    					}
    				}
                }

			}

			// sort: highest count first
			arsort($arr_cnt_words);

			// slice to amount words you want to show
			$arr_res_words = array_slice($arr_cnt_words, 0, AMOUNT_WORDS);

			// highest count
			$highest_value = reset($arr_cnt_words); // or max()
	     	$lowest_value  = min($arr_res_words);

        	$corr = ($highest_value < 4) ? 2 : 1;
			$corr2 = 0;
		   if($lowest_value > 6) {
		   		$corr2 = 10;
		   }
		   if($lowest_value > 10) {
		   		$corr2 = 25;
		   }

			if(SORTING_ORDER == 'natcasesort') {
				uksort($arr_res_words, 'strcasecmp');
			} elseif(SORTING_ORDER == 'sort') {
				ksort($arr_res_words);
			} elseif(SORTING_ORDER == 'random') {
				$arr_res_words = shuffle_assoc($arr_res_words);
			}

			//$highest_value  = max($arr_res_words);
		   	//$lowest_value   = min($arr_res_words);

			$arr_colors = array();
			$arr_tags = array();
			$n=1;

			if(USE_COLORS) {
				for($i=1;$i<=180;$i+=6){
					$arr_colors[$n] = '#' . strtoupper(dechex(mt_rand(256,16777215)));

					$n++;
			    }
			} else {

			    for($i=1;$i<=180;$i+=6){
					//$arr_colors[$i] = '#' . strtoupper(dechex(mt_rand(256,16777215)));

					$arr_rgb = self::fGetRGB(237, 100, $i);		// blue teints

					$dR = str_pad(dechex($arr_rgb[0]), 2, "0", STR_PAD_LEFT);
					$dG = str_pad(dechex($arr_rgb[1]), 2, "0", STR_PAD_LEFT);
					$dB = str_pad(dechex($arr_rgb[2]), 2, "0", STR_PAD_LEFT);
					$hex = '#'.strtoupper($dR.$dG.$dB);;

		  			//$hex = HSLtoHex(237, 100, $i);

		  			$arr_colors[$n] =  $hex;

		  			$n++;
			    }

			    $arr_keys = array_keys($arr_colors);
			    $arr_colors = array_reverse($arr_colors);
			    $arr_colors = array_combine($arr_keys, $arr_colors);
			}

			if($msg != '') {
				echo $msg; exit;
			} else {
				if(!empty($arr_res_words)) {
					foreach($arr_res_words as $k=>$size) {
						if(HIDE_ONE_COUNT_WORDS && $size == 1) {
			                  continue;
			              }
						$fontsize = ($size * 3) + 5 ;
						$fontsize = ($fontsize > 26) ? 25 : $fontsize;

						$str_color = isset($arr_colors[$size]) ? $arr_colors[$size] : '#01004F';

						$arr_tags[$k] = array('size'=>$fontsize, 'color'=>$str_color, 'count'=>$size);
					}
				}
			}
			return $arr_tags;
		}
	}


 
public static function showCloudWidget($bln_widget=false) {
global $obj_db;

if(isset($_GET['q'])) {

    $str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end FROM events WHERE title LIKE  "%'.$_GET['q'].'%" ORDER BY date_start';
	$obj_result = mysqli_query($obj_db, $str_query);

  $arr_events = array();

  	while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
		//	$arr_line['title'] = str_replace('<br />', ' ', $arr_line['title']);
	    //$arr_line['title'] = str_replace( "\n", ' ', $arr_line['title']);
	    $arr_line['title'] = str_replace($_GET['q'], '<strong>'.$_GET['q'].'</strong>', $arr_line['title']);

		$arr_events[] = $arr_line;

	}
	header('Content-Type: text/html; charset=utf-8');
		$obj_smarty = new Smarty();
	$obj_smarty->compile_dir = 'templates_c/';

	$obj_smarty->assign('events', $arr_events);

	$obj_smarty->display(FULLCAL_DIR.'/view/cloud.html');

} else {
//Utils::getSmallCloud();exit;
    define('CLOUDWIDTH', 400);
    define('AMOUNT_WORDS', 70);
    define('LOWERCASE', false);   // true, false
    define('SORTING', 'natcasesort');   // natcasesort, sort, grootnaarklein, random
    define('SHOW_COUNTS', false);
    define('USE_COLORS',  true);
    define('HIDE_ONE_COUNT_WORDS', false);
    $url = '';
    $msg = '';
	$str_url_content = '';

	$str_query = 'SELECT *, event_id as id, concat_ws(" ",date_start,time_start) as start,concat_ws(" ",date_end,time_end) as end FROM events ' .
				' WHERE 1 ';

	if($bln_widget) {
		if(isset($_GET['uid'])) {
			$str_query .= ' AND `user_id` = '.$_GET['uid'];
		}
	} else {
		if(isset($_SESSION['calendar-uid']) && defined('USERS_CAN_SEE_ITEMS_FROM_OTHERS') && !USERS_CAN_SEE_ITEMS_FROM_OTHERS && ALLOW_ACCESS_BY !== 'free') {
			$str_query .= ' AND `user_id` = '.$_SESSION['calendar-uid']['uid'];
		}
	}

	$str_query .= ' ORDER BY `start`';

	$obj_result = mysqli_query($obj_db, $str_query);

  	while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
		$str_url_content .= ' '.$arr_line['title'];
	}

    header("Content-Type: text/html;charset=UTF-8");

	global $EXCLUDED_WORDS_WORDCLOUD;
	if(!empty($EXCLUDED_WORDS_WORDCLOUD)) {
		$str_url_content = str_replace($EXCLUDED_WORDS_WORDCLOUD, ' ', $str_url_content);
	}

	$str_url_content = trim($str_url_content);
	$str_url_content = rtrim($str_url_content);

	$str_url_content = Utils::strip_script($str_url_content);

	$arr_url_content = explode(" ", $str_url_content);
	$arr_url_content = cleanArray($arr_url_content);
	$arr_words = array();
	$arr_cnt_words = array();

	//$arr_url_content = array_unique($arr_url_content);

	// woorden tellen
	foreach($arr_url_content as $r) {
		$rlc = strtolower($r);
	    	if(in_array($rlc, $arr_words) ) {

				if($r == $rlc) {	// geen hoofdletter
	    			if(array_key_exists(ucfirst($r), $arr_cnt_words)) {
						$arr_cnt_words[$r] = $arr_cnt_words[ucfirst($r)];
						$arr_cnt_words[$r] ++ ;
						unset($arr_cnt_words[ucfirst($r)]);
	    			} elseif(array_key_exists($r, $arr_cnt_words)) {
						$arr_cnt_words[$r] ++;

		    		} else {
						$arr_cnt_words[$r] = 1;

						$arr_words[]  = $rlc;
		    		}
	    		} else {
					if(array_key_exists($rlc, $arr_cnt_words)) {
						$arr_cnt_words[$rlc] ++ ;

	    			} elseif(array_key_exists($r, $arr_cnt_words)) {
						$arr_cnt_words[$r] ++;

		    		} else {
						$arr_cnt_words[$r] = 1;

						$arr_words[]  = $rlc;
		    		}
	    		}


			} else {
				$arr_cnt_words[$r] = 1;
				//$arr_tags[]  = $r;
				$arr_words[]  = $rlc;
			}
	}
	// sort: highest count first
	arsort($arr_cnt_words);

	// slice to amount words you want to show
	$arr_res_words = array_slice($arr_cnt_words, 0, AMOUNT_WORDS);

	// highest count
	$highest_value = reset($arr_cnt_words); // or max()
    $lowest_value  = min($arr_res_words);

    $corr = ($highest_value < 4) ? 2 : 1;
	$corr2 = 0;
    if($lowest_value > 6) {
   		$corr2 = 10;
    }
    if($lowest_value > 10) {
   		$corr2 = 25;
    }

	if(SORTING == 'natcasesort') {
		uksort($arr_res_words, 'strcasecmp');
	} elseif(SORTING == 'sort') {
		ksort($arr_res_words);
	} elseif(SORTING == 'random') {
		$arr_res_words = shuffle_assoc($arr_res_words);
	}

    $arr_colors = array();

    echo '<div style="font-family: georgia, tahoma, arial;margin:0 auto;width:'.CLOUDWIDTH.'px;padding-left:10px;padding-top:50px;text-align: justify;overflow:auto;">';


    $n=1;
    for($i=12;$i<=120;$i+=3){ //+=2

        // 237,100,81 licht
        // 237,100,24 licht

        $arr_rgb = self::fGetRGB(237, 100, $i);

        $dR = str_pad(dechex($arr_rgb[0]), 2, "0", STR_PAD_LEFT);
        $dG = str_pad(dechex($arr_rgb[1]), 2, "0", STR_PAD_LEFT);
        $dB = str_pad(dechex($arr_rgb[2]), 2, "0", STR_PAD_LEFT);
        $hex = '#'.strtoupper($dR.$dG.$dB);;

        //  $hex = HSLtoHex(237, 100, $i);

        $arr_colors[$n] =  $hex;
        // $arr_colors[$n] =  $hex;
        //echo dechex(mt_rand($i,255)).'-';
        //$arr_colors[$i] = '#' . strtoupper(dechex(mt_rand(256,16777215)));
        //  $arr_colors[$n] = '#' . strtoupper(dechex($i)).strtoupper(dechex($i)).strtoupper(dechex($i+150));
        $n++;
    }
    $arr_keys = array_keys($arr_colors);
    $arr_colors = array_reverse($arr_colors);
    $arr_colors = array_combine($arr_keys, $arr_colors);

  	echo '<p></p>';

  	if($msg != '') {
  		echo $msg; exit;
  	} else {
  		if(!empty($arr_res_words)) {
			foreach($arr_res_words as $k=>$size) {
			    if(HIDE_ONE_COUNT_WORDS && $size == 1) {
                    continue;
                }
				$fontsize = ($size * 3) + 6 ;
				$fontsize = ($fontsize > 26) ? 25 : $fontsize;

				$str_color = isset($arr_colors[$size]) ? $arr_colors[$size] : '#01004F';

				//	$arr_tags[$k] = array('size'=>$fontsize, 'color'=>$arr_colors[$size], 'count'=>$size);
				echo '<span style="'.((USE_COLORS) ? 'color: '.$str_color.';' : '').'font-size:'.$fontsize.'px;"><a style=  "text-decoration:none;'.((USE_COLORS) ? 'color: '.$str_color.';' : '').'" href= "'.FULLCAL_URL.'/?action=cloud&q='.$k.'" >'.$k.((SHOW_COUNTS) ? '<span style="font-size:12px;">('.$size.')</span>' : '').' </a></span>';
			}

  		}
  	}

  	echo '<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
  	echo '</div>';
  	//}
  	exit;
}

exit;

//$arr_content['events'] = $arr_events;
	//echo json_encode(array($arr_content));

	$obj_smarty = new Smarty();
	$obj_smarty->compile_dir = 'templates_c/';

	$obj_smarty->assign('events', $arr_events);

	$obj_smarty->display(FULLCAL_DIR.'/view/cloud.html');


}

	private static function fGetRGB($iH, $iS, $iV) {

		if($iH < 0) $iH = 0; // Hue:
		if($iH > 360) $iH = 360; // 0-360
		if($iS < 0) $iS = 0; // Saturation:
		if($iS > 100) $iS = 100; // 0-100
		if($iV < 0) $iV = 0; // Lightness:
		if($iV > 100) $iV = 100; // 0-100

		$dS = $iS/100.0; // Saturation: 0.0-1.0
		$dV = $iV/100.0; // Lightness: 0.0-1.0
		$dC = $dV*$dS; // Chroma: 0.0-1.0
		$dH = $iH/60.0; // H-Prime: 0.0-6.0
		$dT = $dH; // Temp variable

		while($dT >= 2.0) $dT -= 2.0; // php modulus does not work with float
		$dX = $dC*(1-abs($dT-1)); // as used in the Wikipedia link

		switch($dH) {
			case($dH >= 0.0 && $dH < 1.0):
			$dR = $dC; $dG = $dX; $dB = 0.0; break;
			case($dH >= 1.0 && $dH < 2.0):
			$dR = $dX; $dG = $dC; $dB = 0.0; break;
			case($dH >= 2.0 && $dH < 3.0):
			$dR = 0.0; $dG = $dC; $dB = $dX; break;
			case($dH >= 3.0 && $dH < 4.0):
			$dR = 0.0; $dG = $dX; $dB = $dC; break;
			case($dH >= 4.0 && $dH < 5.0):
			$dR = $dX; $dG = 0.0; $dB = $dC; break;
			case($dH >= 5.0 && $dH < 6.0):
			$dR = $dC; $dG = 0.0; $dB = $dX; break;
			default:
			$dR = 0.0; $dG = 0.0; $dB = 0.0; break;
		}

		$dM = $dV - $dC;
		$dR += $dM; $dG += $dM; $dB += $dM;
		$dR *= 255; $dG *= 255; $dB *= 255;

		return array(round($dR),round($dG),round($dB));
	}

	public static function cleanArray ($array, $own_arg='#*%@72-$') {
		foreach ($array as $key => $value) {

			if(is_array($value)) {
				$array[$key] = self::cleanArray($value, $own_arg);
			} else {
				//$value = str_replace('/', '', $value);
	//			if(LOWERCASE) {
	//				$value = preg_replace("[^a-z]", "", $value );
	//			} else {
	//				$value = preg_replace("[^a-zA-Z]", "", $value );
	//			}

				$value = trim($value);
		      	$value = rtrim($value);

				$value = ' '.$value.' ';

		      	global $EXCLUDED_WORDS_WORDCLOUD;
            	if(!empty($EXCLUDED_WORDS_WORDCLOUD)) {
            		$value = str_replace($EXCLUDED_WORDS_WORDCLOUD, ' ', $value);
            	}

		      	if ($value == '' OR strlen($value) < 3 OR strlen($value) > 20 OR strstr($value,$own_arg) OR strstr($value,'_') ) {
		        	unset($array[$key]);
		     	}
		    }
	    }
	    $array = array_values($array);
		return $array;
	}

	public static function strip_script($string) {
	    if(strstr($string, '<script')) {
			$str_part_after_scripttag = trim(strstr($string, '<script'));
			$str_result = substr($str_part_after_scripttag, 0, strpos($str_part_after_scripttag,'</script>')+9);
			$string = str_replace($str_result, '', $string);
			$string = self::strip_script($string);
		}
		return $string;
	}

	function showExampleAgendaWidget($bln_google_like=false) {
	    global $error;

		$arr_submit 		= array(
			array('from',			'string',   	false, 	''),
			array('to',				'string',   	false, 	''),
			array('uid',			'int',   		false, 	''),
			array('c',				'string',   	false, 	''),
			array('w',				'int',   		false, 	200),
			array('hrs',			'int',   		false, 	24),
			array('ebc',			'string',   	false, 	'FFFFCC'),	// event background color
			array('bc',				'string',   	false, 	'FFFFCC'),	// background color
			array('showec',			'string',   	false, 	'no'),		// show event color
			array('lang',			'string',   	false, 	''),
			array('ics',			'string',   	false, 	'no'),
			array('period',			'int',   		false, 	''),
			array('google_calid',	'string',   	false, 	''),
			array('google_privatekey',	'string',   	false, 	''),
		);

		$frm_submitted      = validate_var($arr_submit);

		$obj_smarty = new Smarty();
		$obj_smarty->compile_dir = 'templates_c/';

		if(!empty($frm_submitted['lang'])) {
			$frm_submitted['lang'] = strtolower($frm_submitted['lang']);

			if($frm_submitted['lang'] == 'nl' || $frm_submitted['lang'] == 'en' || $frm_submitted['lang'] == 'fr' || $frm_submitted['lang'] == 'de') {
				Utils::setLocaleLanguage($frm_submitted['lang']);
			}
		}

		header("Content-Type: text/html;charset=UTF-8");

		$obj_smarty->assign('iframewidth', $frm_submitted['w']);
		$obj_smarty->assign('showeventcolor', $frm_submitted['showec']);
		$obj_smarty->assign('hrs', $frm_submitted['hrs']);

		$arr_res = array();

			$arr_res['results'] = array (

					 date('Y-m-d', strtotime('+2DAY')) => array(array (
				    'event_id' => 102,
				    'title' => 'Walking in the Belgian hills near Spa',
				    'date_start' => date('Y-m-d', strtotime('+2DAY')),
				    'time_start' => '12:16:58',
				    'date_end' => date('Y-m-d', strtotime('+2DAY')),
				    'time_end' => '17:27:45',
				    'allDay' => '1',
				    'calendartype' => '',
				    'user_id' => '2',
				    'color' => '#FFBB00',
				  ))
				  ,
				   date('Y-m-d', strtotime('+3DAY')) => array(array (
				    'event_id' => 102,
				    'title' => 'Luxembourg',
				    'date_start' => date('Y-m-d', strtotime('+3DAY')),
				    'time_start' => '12:16:58',
				    'date_end' => date('Y-m-d', strtotime('+3DAY')),
				    'time_end' => '17:27:45',
				    'allDay' => '1',
				    'calendartype' => '',
				    'user_id' => '2',
				    'color' => '#FFBB00',
				  ))
				  ,
				  date('Y-m-d', strtotime('+4DAY')) => array(array (
				    'event_id' => 102,
				    'title' => 'Stayed at the campingsite',
				    'date_start' => date('Y-m-d', strtotime('+4DAY')),
				    'time_start' => '12:16:58',
				    'date_end' => date('Y-m-d', strtotime('+4DAY')),
				    'time_end' => '17:27:45',
				    'allDay' => '1',
				    'calendartype' => '',
				    'user_id' => '2',
				    'color' => '#3366cc',
				  ))
				  ,

				  date('Y-m-d', strtotime('+5DAY')) => array(array (
				    'event_id' => 104,
				    'title' => 'another event',
				    'date_start' => date('Y-m-d', strtotime('+5DAY')),
				    'time_start' => '6:59:52',
				    'date_end' => date('Y-m-d', strtotime('+5DAY')),
				    'time_end' => '14:50:36',
					'allDay' => '1',
				    'calendartype' => '',
				    'user_id' => '2',
				    'color' => '#3366cc',
				  ))
				  ,

				  date('Y-m-d', strtotime('+6DAY')) => array(array (
				    'event_id' => 105,
				    'title' => 'yet another event',
				    'date_start' => date('Y-m-d', strtotime('+6DAY')),
				    'time_start' => '10:58:21',
				    'date_end' => date('Y-m-d', strtotime('+6DAY')),
				    'time_end' => '14:21:26',
				    'allDay' => '1',
				    'calendartype' => '',
				    'user_id' => '2',
				    'color' => '#3366cc',
				  ),array (
				    'event_id' => 106,
				    'title' => 'Back home',
				    'date_start' => date('Y-m-d', strtotime('+6DAY')),
				    'time_start' => '11:35:28',
				    'date_end' => date('Y-m-d', strtotime('+6DAY')),
				    'time_end' => '18:15:41',
				    'allDay' => '0',
				    'calendartype' => '',
				    'user_id' => '2',
				    'color' => '#3366cc',
				  ))
				  ,

				);

		$arr_return['hide_from'] = false;
		$arr_return['hide_to'] = false;

		if(defined('AGENDA_VIEW_AMOUNT_DAYS_TO_SHOW') && AGENDA_VIEW_AMOUNT_DAYS_TO_SHOW > 0) {
			$amount_days_to_show = AGENDA_VIEW_AMOUNT_DAYS_TO_SHOW;
		} else {
			$amount_days_to_show = 5;
		}

		if(!empty($frm_submitted['from'])) {
			$arr_res['hide_from'] = true;

		}
		if(!empty($frm_submitted['to'])) {
			$arr_res['results'] = array (
				date('Y-m-d', strtotime('-4DAY')) => array(array (
				    'event_id' => 99,
				    'title' => 'felisc',
				    'date_start' => date('Y-m-d', strtotime('-4DAY')),
				    'time_start' => '9:21:48',
				    'date_end' => date('Y-m-d', strtotime('-4DAY')),
				    'time_end' => '13:54:41',
				    'allDay' => '0',
				    'calendartype' => '',
				    'user_id' => '2',
				    'color' => '#3366cc',
				  ))
				  ,

				date('Y-m-d', strtotime('-1DAY')) => array(array (
				    'event_id' => 100,
				    'title' => 'felisc',
				    'date_start' => date('Y-m-d', strtotime('-1DAY')),
				    'time_start' => '9:21:48',
				    'date_end' => date('Y-m-d', strtotime('-1DAY')),
				    'time_end' => '13:54:41',
				    'allDay' => '0',
				    'calendartype' => '',
				    'user_id' => '2',
				    'color' => '#3366cc',
				  ))
				  ,
			);
			if(count($arr_res['results']) < $amount_days_to_show) {
				$arr_res['hide_to'] = true;
			}
		}

		if(empty($frm_submitted['from']) && empty($frm_submitted['to'])) {
			$arr_res['hide_from'] = true;
		}

		$obj_smarty->assign('items', $arr_res['results']);
		$obj_smarty->assign('from', current(array_keys($arr_res['results'])));
		$obj_smarty->assign('to', end(array_keys($arr_res['results'])));
		$obj_smarty->assign('hide_from', $arr_res['hide_from']);
		$obj_smarty->assign('hide_to', $arr_res['hide_to']);


		if($bln_google_like) {
			$obj_smarty->display(FULLCAL_DIR.'/view/examples/agenda_widget_google_like.html');

		} else {
	//		$frm_submitted['from'] = date('Y-m-d');
	//		unset($frm_submitted['to']);
	//		$frm_submitted['combine_moreday_events'] = false;
	//
	//		$arr_res = Events::getListviewEvents($frm_submitted);
	//
	//		if(isset($arr_res)) {
	//		    $obj_smarty->assign('items', $arr_res['results']);
	//			$obj_smarty->assign('from', $arr_res['results']);
	//			$obj_smarty->assign('to', $arr_res['results']);
	//		}

			$obj_smarty->display(FULLCAL_DIR.'/view/examples/agenda_widget_justtext.html');
		}
	}


	function rgb2html($r, $g=-1, $b=-1)
	{
	    if (is_array($r) && sizeof($r) == 3)
	        list($r, $g, $b) = $r;

	    $r = intval($r); $g = intval($g);
	    $b = intval($b);

	    $r = dechex($r<0?0:($r>255?255:$r));
	    $g = dechex($g<0?0:($g>255?255:$g));
	    $b = dechex($b<0?0:($b>255?255:$b));

	    $color = (strlen($r) < 2?'0':'').$r;
	    $color .= (strlen($g) < 2?'0':'').$g;
	    $color .= (strlen($b) < 2?'0':'').$b;
	    return '#'.$color;
	}


	function HSLtoHex( $Hue = 0, $Saturation = 0, $Luminance = 0 ) {
	    $HSLColor    = array( 'Hue' => $Hue, 'Saturation' => $Saturation, 'Luminance' => $Luminance );
	    $RGBColor    = array( 'Red' => 0, 'Green' => 0, 'Blue' => 0 );


	    foreach( $HSLColor as $Name => $Value ) {
	        if( is_string( $Value ) && strpos( $Value, '%' ) !== false )
	                $Value = round( round( (int)str_replace( '%', '', $Value ) / 100, 2 ) * 255, 0 );

	        else if( is_float( $Value ) )
	                $Value = round( $Value * 255, 0 );

	        $Value    = (int)$Value * 1;
	        $Value    = $Value > 255 ? 255 : ( $Value < 0 ? 0 : $Value );
	        $ValuePct = round( $Value / 255, 6 );


	        if($Name == 'Hue') {
	            $str_hue = $ValuePct;
	        } else if($Name == 'Saturation') {
	             $str_saturation = $ValuePct;
	        } else if($Name == 'Luminance') {
	             $str_luminance = $ValuePct;
	        }


	    }


	    $RGBColor['Red']   = $str_luminance;
	    $RGBColor['Green'] = $str_luminance;
	    $RGBColor['Blue']  = $str_luminance;



	    $Radial  = $str_luminance <= 0.5 ? $str_luminance * ( 1.0 + $str_saturation ) : $str_luminance + $str_saturation - ( $str_luminance * $str_saturation );



	    if( $Radial > 0 )
	    {

	            $Ma   = $str_luminance + ( $str_luminance - $Radial );
	            $Sv   = round( ( $Radial - $Ma ) / $Radial, 6 );
	            $Th   = $str_hue * 6;
	            $Wg   = floor( $Th );
	            $Fr   = $Th - $Wg;
	            $Vs   = $Radial * $Sv * $Fr;
	            $Mb   = $Ma + $Vs;
	            $Mc   = $Radial - $Vs;


	            // Color is between yellow and green
	            if ($Wg == 1)
	            {
	                    $RGBColor['Red']   = $Mc;
	                    $RGBColor['Green'] = $Radial;
	                    $RGBColor['Blue']  = $Ma;
	            }
	            // Color is between green and cyan
	            else if( $Wg == 2 )
	            {
	                    $RGBColor['Red']   = $Ma;
	                    $RGBColor['Green'] = $Radial;
	                    $RGBColor['Blue']  = $Mb;
	            }

	            // Color is between cyan and blue
	            else if( $Wg == 3 )
	            {
	                    $RGBColor['Red']   = $Ma;
	                    $RGBColor['Green'] = $Mc;
	                    $RGBColor['Blue']  = $Radial;
	            }

	            // Color is between blue and magenta
	            else if( $Wg == 4 )
	            {
	                    $RGBColor['Red']   = $Mb;
	                    $RGBColor['Green'] = $Ma;
	                    $RGBColor['Blue']  = $Radial;
	            }

	            // Color is between magenta and red
	            else if( $Wg == 5 )
	            {
	                    $RGBColor['Red']   = $Radial;
	                    $RGBColor['Green'] = $Ma;
	                    $RGBColor['Blue']  = $Mc;
	            }

	            // Color is between red and yellow or is black
	            else
	            {
	                    $RGBColor['Red']   = $Radial;
	                    $RGBColor['Green'] = $Mb;
	                    $RGBColor['Blue']  = $Ma;
	            }

	     }



	     $RGBColor['Red']   = ($C = round( $RGBColor['Red'] * 255, 0 )) < 15 ? '0'.dechex( $C ) : dechex( $C );
	     $RGBColor['Green'] = ($C = round( $RGBColor['Green'] * 255, 0 )) < 15 ? '0'.dechex( $C ) : dechex( $C );
	     $RGBColor['Blue']  = ($C = round( $RGBColor['Blue'] * 255, 0 )) < 15 ? '0'.dechex( $C ) : dechex( $C );



	     return '#' . $RGBColor['Red'].$RGBColor['Green'].$RGBColor['Blue'];


	}
	
  public static function sendMail($type='', $email='', $password='', $frm_submitted=array(), $int_user_id=0, $hash_code='') {
		if(!empty($type) && !empty($email)) {
			switch($type) {
				case 'assign_notify':
					$subject = 'Task assigned to you';
					$mailtext = '';
					break;
				case 'add_user':
					$subject = 'Your new account';
					$mailtext = 'The admin created an account for you. <br /><br />' ;

					if(SEND_ACTIVATION_MAIL) {

						if(defined('ACTIVATION_MAIL_SUBJECT') && ACTIVATION_MAIL_SUBJECT !== '') {
							$subject = ACTIVATION_MAIL_SUBJECT;

							if(stristr($subject, '%USERNAME%')) {
								if(isset($frm_submitted['username']) && !empty($frm_submitted['username'])) {
									$subject = str_replace('%USERNAME%', $frm_submitted['username'], $subject);
								} else {
									$subject = str_replace('%USERNAME%', '', $subject);
								}
							}
						}

						$mailtext .= 'To confirm the registration click on this link: <br />' .
											'<a href="'.FULLCAL_URL.'/?action=activate&uid='.$int_user_id.'&hash='.$hash_code.'">'.FULLCAL_URL.'/?action=activate&uid='.$int_user_id.'&hash='.$hash_code.'</a><br /><br />'.
											'<br />If your browser doesn\'t automatically open, paste the link in your browser ';


					} else {
						if(!isset($frm_submitted['username']) || empty($frm_submitted['username'])) {
							$mailtext .= 'You can login with your emailaddress as username. ';
						}

						$mailtext .= '<br />Your password is: '.$password;
					}

					break;
				case 'add_admin':
					$subject = 'Your new admin account';
					$mailtext = 'The admin created an admin account for you. <br /><br />' ;

					if(!isset($frm_submitted['username']) || empty($frm_submitted['username'])) {
						$mailtext .= 'You can login with your emailaddress as username ';
					}

					$mailtext .= '<br />Your password is: '.$password;
					break;
				case 'copy_to_admin_admin_created':
					$subject = 'New account';
					$mailtext = 'You created a new admin account for: '.$frm_submitted['firstname'].' '.$frm_submitted['infix'].' '.$frm_submitted['lastname'].'. <br /><br />' .
								'Username: '.$frm_submitted['username'].'<br />Password: '.$password;
					break;
				case 'copy_to_admin_user_created':
					$subject = 'New account';
					$mailtext = 'You created a new user account for: '.$frm_submitted['firstname'].' '.$frm_submitted['infix'].' '.$frm_submitted['lastname'].'. <br /><br />' .
								'Username: '.$frm_submitted['username'].'<br />Password: '.$password;
					break;
			}

			$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">' .
					'<html>' .
					'<head></head>' .
					'<body>' ;

			$message .= $mailtext;

			$message .= '</body></html>';

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.FROM_EMAILADDRESS . "\r\n";

			if(mail($email, $subject, $message, $headers)){
				return true;
			} else {
				return false;
			}
		}
		return false;

	} */

}
?>