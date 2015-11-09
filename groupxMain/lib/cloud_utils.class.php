<?php

class CloudUtils {



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

	function hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   //return implode(",", $rgb); // returns the rgb values separated by commas
	   return $rgb; // returns an array with the rgb values
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

	function html2rgb($color)
	{
	    if ($color[0] == '#')
	        $color = substr($color, 1);

	    if (strlen($color) == 6)
	        list($r, $g, $b) = array($color[0].$color[1],
	                                 $color[2].$color[3],
	                                 $color[4].$color[5]);
	    elseif (strlen($color) == 3)
	        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
	    else
	        return false;

	    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

	    return array($r, $g, $b);
	}

	function rgb2hex($rgb) {
   		$hex = "#";
	   	$hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
	   	$hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
	   	$hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

   		return $hex; // returns the hex value including the number sign (#)
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


}
?>