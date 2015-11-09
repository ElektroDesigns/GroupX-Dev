<?php

class User {

    public static function canAdd() {
		if(User::isLoggedIn()) {
			if(defined('ADMIN_HAS_FULL_CONTROL') && ADMIN_HAS_FULL_CONTROL && (User::isAdmin() || User::isSuperAdmin())) {
				return true;

			} else if(defined('ONLY_ADMIN_CAN_ADD_AND_EDIT') && ONLY_ADMIN_CAN_ADD_AND_EDIT && !User::isAdmin() && !User::isSuperAdmin()) {
				return false;

			}
		}
		if(ALLOW_ACCESS_BY == 'ip') {
			if((defined('CAL_IP') && $_SERVER['REMOTE_ADDR'] == CAL_IP) || $_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
	        	return true;
	        }

		} else if(ALLOW_ACCESS_BY == 'login') {
			if(User::isLoggedIn()) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
    }

    public static function canEdit($event_user = -1) {
		// admin
		if(User::isLoggedIn()) {
			if(defined('ADMIN_HAS_FULL_CONTROL') && ADMIN_HAS_FULL_CONTROL && (User::isAdmin() || User::isSuperAdmin())) {
				return true;

			} else if(defined('ONLY_ADMIN_CAN_ADD_AND_EDIT') && ONLY_ADMIN_CAN_ADD_AND_EDIT && !User::isAdmin() && !User::isSuperAdmin()) {
				return false;

			}
		}

		if(ALLOW_ACCESS_BY == 'ip') {
			if((defined('CAL_IP') && $_SERVER['REMOTE_ADDR'] == CAL_IP) || $_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
	        	return true;
	        }

		} else if(ALLOW_ACCESS_BY == 'login') {
			if(User::isLoggedIn()) {
				if(USERS_CAN_CHANGE_ITEMS_FROM_OTHERS || ($event_user > 0  && isset($_SESSION['calendar-uid']) && isset($_SESSION['calendar-uid']['uid']) && $event_user == $_SESSION['calendar-uid']['uid'])) {
					return true;
				} else {
					return false;
				}

			} else {
				return false;
			}
		} else if(ALLOW_ACCESS_BY == 'free' && ($event_user > 0  && $event_user == IP_AND_FREE_ACCESS_SAVED_USER_ID)) {
			return true;

		} else {
			if(USERS_CAN_CHANGE_ITEMS_FROM_OTHERS) {
				return true;
			}
			if(User::isLoggedIn()) {
				if(User::isAdmin() || $event_user == $_SESSION['calendar-uid']['uid']) {
					return true;
				}
			}

		}
		return false;
    }

	public static function canView() {
		if(User::isLoggedIn()) {
			if(defined('ADMIN_HAS_FULL_CONTROL') && ADMIN_HAS_FULL_CONTROL && (User::isAdmin() || User::isSuperAdmin())) {
				return true;

			} else if(defined('ONLY_ADMIN_CAN_ADD_AND_EDIT') && ONLY_ADMIN_CAN_ADD_AND_EDIT && !User::isAdmin() && !User::isSuperAdmin()) {
				return false;

			}
		}
		if(defined('USERS_CAN_SEE_ITEMS_FROM_OTHERS') && USERS_CAN_SEE_ITEMS_FROM_OTHERS) {
			return true;

		}
		return false;
    }

    public static function canDelete($event_user = -1) {
		if(User::isLoggedIn()) {
			if(defined('ADMIN_HAS_FULL_CONTROL') && ADMIN_HAS_FULL_CONTROL && (User::isAdmin() || User::isSuperAdmin())) {
				return true;

			} else if(defined('ONLY_ADMIN_CAN_ADD_AND_EDIT') && ONLY_ADMIN_CAN_ADD_AND_EDIT && !User::isAdmin() && !User::isSuperAdmin()) {
				return false;

			}
		}
		if(defined('USERS_CAN_DELETE_ITEMS_FROM_OTHERS')) {
			if(USERS_CAN_DELETE_ITEMS_FROM_OTHERS || ($event_user > 0 && isset($_SESSION['calendar-uid']['uid']) && $event_user == $_SESSION['calendar-uid']['uid'])) {
				return true;
			} else {
				return false;
			}

		}
		return false;
    }

    public static function getUser() {
		global $obj_db;

		if(!isset($_SESSION['calendar-uid']) && !isset($_SESSION['calendar-uid']['uid'])) {
			return null;
		}
    	$str_query = 'SELECT * FROM users WHERE user_id = ' . (int)$_SESSION['calendar-uid']['uid'];
		$obj_result = mysqli_query($obj_db, $str_query);
		$arr_user = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);
		if(is_array($arr_user)) {
			unset($arr_user['password']);
			return $arr_user;
		}
		return null;
    }

	public static function getUserById($user_id) {
		global $obj_db;

    	$str_query = 'SELECT * FROM users WHERE user_id = ' . $user_id;
		$obj_result = mysqli_query($obj_db, $str_query);
		$arr_user = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

		return $arr_user;
    }

	public static function isAdminUser($user_id=-1) {
		global $obj_db;

		if(isset($_SESSION['calendar-uid']) && $user_id > 0) {
			if($_SESSION['calendar-uid']['uid'] == $user_id) {
				// change own userprofile
				return true;
			} else {
				$str_query = 'SELECT * FROM users';

				$str_query .= ' WHERE (`admin_group` = '.$_SESSION['calendar-uid']['uid'].' AND `usertype` = "user" AND `user_id` = '.$user_id.') ';

				$obj_result = mysqli_query($obj_db, $str_query);
				$arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

		      	if($arr_line !== false && !empty($arr_line) ) {
		        	return true;
		      	} else {
		        	return false;
		      	}
			}
		}
	}

	public static function saveProfile($frm_submitted) {
		global $obj_db;

		$str_query = 'UPDATE users SET `firstname` = "'.$frm_submitted['firstname'].'" '.
						' ,`infix` = "'.$frm_submitted['infix'].'"'.
						' ,`lastname` = "'.$frm_submitted['lastname'].'"'.
						' ,`username` = "'.$frm_submitted['username'].'"'.
						' ,`country` = "'.$frm_submitted['country'].'"'.
						' ,`birth_date` = "'.$frm_submitted['birthdate_year'].'-'.$frm_submitted['birthdate_month'].'-'.$frm_submitted['birthdate_day'].'"'.
						' ,`email` = "'.$frm_submitted['email'].'"'.
						' WHERE `user_id` = '.(int)$_SESSION['calendar-uid']['uid'];

		$res = mysqli_query($obj_db, $str_query);


		return $res;
	}

	public static function adminSaveProfile($frm_submitted) {
		global $obj_db;

		$str_query = 'UPDATE users SET `firstname` = "'.$frm_submitted['firstname'].'" '.
						' ,`infix` = "'.$frm_submitted['infix'].'"'.
						' ,`lastname` = "'.$frm_submitted['lastname'].'"'.
						' ,`username` = "'.$frm_submitted['username'].'"'.
						' ,`country` = "'.$frm_submitted['country'].'"'.
						' ,`birth_date` = "'.$frm_submitted['birthdate_year'].'-'.$frm_submitted['birthdate_month'].'-'.$frm_submitted['birthdate_day'].'"'.
						' ,`email` = "'.$frm_submitted['email'].'"'.
						' WHERE `user_id` = '.(int)$frm_submitted['user_id'];

		$res = mysqli_query($obj_db, $str_query);


		return $res;
	}

	public static function addUser($frm_submitted) {
		global $obj_db;

		$str_query = 'SELECT * FROM users WHERE username = "' . (!empty($frm_submitted['username']) ? $frm_submitted['username'] : $frm_submitted['email']).'"';
		$obj_result = mysqli_query($obj_db, $str_query);
		$arr_user = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

		if(!empty($arr_user) && $arr_user !== false) {
			if(SHOW_USERNAME_IN_FORM && !empty($frm_submitted['username'])) {
				return 'Username already exists';
			} else {
				return 'Username (emailaddress) already exists';
			}
		}

		// generate random password
		$password = Utils::generatePassword();

		$bln_insert = false;

		if(User::isSuperAdmin()) {
			$usertype = 'admin';
			$admin_group = $_SESSION['calendar-uid']['uid'];
			$bln_insert = true;
		} else if(User::isAdmin()) {
			$usertype = 'user';
			$admin_group = $_SESSION['calendar-uid']['uid'];
			$bln_insert = true;
		}

        if($bln_insert) {
        	$str_query = 'INSERT INTO users ( `firstname` ,`infix` ,`lastname` ,`username`,`password` ,`email` ,`registration_date` ,'.
											'`birth_date`, `active`, `ip`, `country`, `country_code`, `usertype`, `admin_group`) VALUES ('.
            					'"'.$frm_submitted['firstname'].'",'.
            					'"'.$frm_submitted['infix'].'",'.
            					'"'.$frm_submitted['lastname'].'",'.
            					'"'.(isset($frm_submitted['username']) && !empty($frm_submitted['username']) ? $frm_submitted['username'] : $frm_submitted['email']).'",'.
            					'"'.self::getPasswordHashcode($password).'",'.
            					'"'.$frm_submitted['email'].'",'.
            					'NOW(),'.
            					'"",' .
            					'1,'.
            					'"",' .
            					'"",' .
            					'"",' .
            					'"'.$usertype.'",'.
            					$admin_group.')';

		    $res = mysqli_query($obj_db, $str_query);

			$int_user_id = mysqli_insert_id($obj_db);

			$hash_code = self::getUserHashcode($int_user_id);

			if($res !== false) {
				$admin_mail = '';
				if(defined('ADMIN_EMAILADDRESS')) {
					$admin_mail = ADMIN_EMAILADDRESS;
				}

				if(User::isSuperAdmin()) {
					$bln_send = Utils::sendMail('add_admin', $frm_submitted['email'], $password);

					if($frm_submitted['copy_to_admin'] && !empty($admin_mail)) {
						Utils::sendMail('copy_to_admin_admin_created', $admin_mail, $password, $frm_submitted);
					}
				} else if(User::isAdmin()) {
					$bln_send = Utils::sendMail('add_user', $frm_submitted['email'], $password, $frm_submitted, $int_user_id, $hash_code);

					if($frm_submitted['copy_to_admin'] && !empty($admin_mail)) {
						Utils::sendMail('copy_to_admin_user_created', $admin_mail, $password, $frm_submitted);
					}
				}

				if($bln_send){
					if(defined('SHOW_CREATED_PASSWORD_WHEN_ADMIN_ADDS_USER') && SHOW_CREATED_PASSWORD_WHEN_ADMIN_ADDS_USER) {
						return array('insert'=>true, 'mail'=>'send', 'password'=>$password);
					} else {
						return array('insert'=>true, 'mail'=>'send');
					}
				} else {
					if(defined('SHOW_CREATED_PASSWORD_WHEN_ADMIN_ADDS_USER') && SHOW_CREATED_PASSWORD_WHEN_ADMIN_ADDS_USER) {
						return array('insert'=>true, 'mail'=>'notsend', 'password'=>$password);
					} else {
						return array('insert'=>true, 'mail'=>'notsend');
					}
				}
			} else {
				return array('insert'=>false, 'mail'=>'notsend');
			}
        } else {
        	return array('insert'=>false, 'mail'=>'notsend', 'error'=>'You have no admin rights!');
        }
	}

	public static function deleteUser($int_user_id) {
		global $obj_db;

		$str_query = 'UPDATE users SET `deleted` = 1 WHERE `user_id` = '.$int_user_id.' AND `usertype` = "user" AND `admin_group` = '.$_SESSION['calendar-uid']['uid'];

		$obj_result = mysqli_query($obj_db, $str_query);

		if($obj_result !== false) {
			return true;
		}
		return false;
	}

	public static function undeleteUser($int_user_id) {
		global $obj_db;

		$str_query = 'UPDATE users SET `deleted` = 0 WHERE `user_id` = '.$int_user_id.' AND `usertype` = "user" AND `admin_group` = '.$_SESSION['calendar-uid']['uid'];

		$obj_result = mysqli_query($obj_db, $str_query);

		if($obj_result !== false) {
			return true;
		}
		return false;
	}

	public static function getUsers() {
		global $obj_db;

		$arr_users = array();

		$str_query = 'SELECT user_id, concat_ws(" ",firstname,infix,lastname) as fullname FROM users';

		$obj_result = mysqli_query($obj_db, $str_query);

		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			$arr_users[] = $arr_line;
		}
		return $arr_users;
    }

	public static function getAdminUsers($bln_include_myself=false, $bln_complete=false) {
		global $obj_db;

		$arr_users = array();

		if($bln_complete) {
			$str_query = 'SELECT users.*, concat_ws(" ",firstname,infix,lastname) as fullname FROM users';
		} else {
			$str_query = 'SELECT user_id, concat_ws(" ",firstname,infix,lastname) as fullname FROM users';
		}

		$str_query .= ' WHERE (`admin_group` = '.$_SESSION['calendar-uid']['uid'].' AND `usertype` = "user" AND `deleted` = 0) ';

		if($bln_include_myself) {
			$str_query .= ' OR user_id = '.$_SESSION['calendar-uid']['uid'];
		}

		$obj_result = mysqli_query($obj_db, $str_query);

		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			if(User::isAdmin($arr_line['user_id'])) {
				$arr_line['admin'] = true;
			}

			$arr_users[] = $arr_line;
		}
		return $arr_users;
    }

	public static function getAdmins($bln_include_myself=false, $bln_complete=false) {
		global $obj_db;

		$arr_users = array();

		if($bln_complete) {
			$str_query = 'SELECT users.*, concat_ws(" ",firstname,infix,lastname) as fullname FROM users';
		} else {
			$str_query = 'SELECT user_id, concat_ws(" ",firstname,infix,lastname) as fullname FROM users';
		}

		$str_query .= ' WHERE (`admin_group` = '.$_SESSION['calendar-uid']['uid'].' AND `usertype` = "admin" AND `deleted` = 0) ';

		if($bln_include_myself) {
			$str_query .= ' OR user_id = '.$_SESSION['calendar-uid']['uid'];
		}

		$obj_result = mysqli_query($obj_db, $str_query);

		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			if(User::isAdmin($arr_line['user_id'])) {
				$arr_line['admin'] = true;
			}
			if(User::isSuperAdmin($arr_line['user_id'])) {
				$arr_line['superadmin'] = true;
			}

			$arr_users[] = $arr_line;
		}
		return $arr_users;
    }


    public static function getUserHashcode($uid) {
		$salt1 = sha1('356432');
		$salt2 = sha1('83455');

		return md5($salt1.$uid.$salt2);
    }


    public static function UserAuthenticated($username, $password, &$int_user_id) {
		$passwordhash = self::getPasswordHashcode($password);

      	global $obj_db;

      	$str_query = 'SELECT `user_id`, `firstname`, `lastname` FROM `users` WHERE `username` = "'.mysqli_real_escape_string($obj_db, $username).'" AND `password` = "'.mysqli_real_escape_string($obj_db, $passwordhash).'"';

      	$obj_result = mysqli_query($obj_db, $str_query);
		$arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

      	if($arr_line !== false && !empty($arr_line) ) {
        	$int_user_id = $arr_line['user_id'];
        	return true;
      	} else {
        	return false;
      	}
    }

    public static function getSessionHashcode($uid, $username) {
		$salt1 = sha1('89275');

		return md5($uid.$salt1.date('w').$username);
    }

    public static function createHashcode($pw) {
        $salt1 = sha1('356432');
    		$salt2 = sha1('83455');

    		return md5($salt1.$pw.$salt2);
    }

    public static function getPasswordHashcode($pw) {
    		$salt1 = sha1('376455');
    		$salt2 = sha1('34675');

    		return md5($salt1.$pw.$salt2);
    }

    public static function adminRegister($frm_submitted, $bln_activate=false) {
        global $obj_db;

		$bln_insert = false;

		if(User::isSuperAdmin()) {
			$usertype = 'admin';
			$admin_group = $_SESSION['calendar-uid']['uid'];
			$bln_insert = true;
		} else if(User::isAdmin()) {
			$usertype = 'user';
			$admin_group = $_SESSION['calendar-uid']['uid'];
			$bln_insert = true;
		}

		if(defined('SHOW_CREATED_PASSWORD_WHEN_ADMIN_REGISTER_VIA_BROWSER_URL') && SHOW_CREATED_PASSWORD_WHEN_ADMIN_REGISTER_VIA_BROWSER_URL) {
			echo (self::getPasswordHashcode($frm_submitted['password']));
		}

		if($bln_insert) {

	   		$str_query = 'INSERT INTO users ( `firstname` ,`infix` ,`lastname` ,`username`,`password` ,`email` ,`registration_date` ,'.
											'`birth_date`, `active`, `ip`, `country`, `country_code`, `usertype`, `admin_group`) VALUES ('.
            					'"",'.
	            				'"",'.
            					'"'.$frm_submitted['lastname'].'",'.
            					'"'.$frm_submitted['username'].'",'.
	            				'"'.self::getPasswordHashcode($frm_submitted['password']).'",'.
            					'"",'.
            					'NOW(),'.
            					'"",' .
            					'1,'.
            					'"'.$_SERVER['REMOTE_ADDR'].'",' .
            					'"",' .
            					'"",' .
            					'"'.$usertype.'",'.
            					$admin_group.')';

		    $res = mysqli_query($obj_db, $str_query);
			return $res;
		}
		return false;
    }

    public static function register($frm_submitted) {

		global $obj_db;

		$str_query = 'INSERT INTO users ( `firstname` ,`infix` ,`lastname` ,`username` ,`email` ,`registration_date` ,'.
											'`birth_date`, `ip`, `country`, `country_code`) VALUES ('.
					'"'.$frm_submitted['firstname'].'",'.
					'"'.$frm_submitted['infix'].'",'.
					'"'.$frm_submitted['lastname'].'",'.
					'"'.$frm_submitted['username'].'",'.
					'"'.$frm_submitted['email'].'",'.
					'NOW(),'.
					'"'.date('Y-m-d', strtotime($frm_submitted['year'].'/'.$frm_submitted['month'].'/'.$frm_submitted['day'])).'",' .
					'"'.$_SERVER['REMOTE_ADDR'].'",' .
					'"",' .
					'"")';

		$res = mysqli_query($obj_db, $str_query);

		$int_user_id = mysqli_insert_id($obj_db);

		if($int_user_id > 0) {

			/*
			 * generate hash
			 */
			$hash_code = self::getUserHashcode($int_user_id);

			$str_query = 'UPDATE users SET `user_hash` = "'.$hash_code.'" WHERE user_id = '.$int_user_id;

			$res = mysqli_query($obj_db, $str_query);

			if(SEND_ACTIVATION_MAIL) {

				/*
				 * send mail to new user
				 */

				if(defined('ACTIVATION_MAIL_SUBJECT') && ACTIVATION_MAIL_SUBJECT !== '') {
					$subject = ACTIVATION_MAIL_SUBJECT;

					$subject = str_replace('%USERNAME%', $frm_submitted['username'], $subject);
				} else {
					$subject = 'Registration for fast & easy calendar';
				}

				$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">' .
						'<html>' .
						'<head></head>' .
						'<body>' .

				$message = 'Thank you for registering. <br /><br />' .
									'To confirm the registration click on this link: <br />' .
									'<a href="'.FULLCAL_URL.'/?action=activate&uid='.$int_user_id.'&hash='.$hash_code.'">'.FULLCAL_URL.'/?action=activate&uid='.$int_user_id.'&hash='.$hash_code.'</a><br /><br />'.
									'<br />If your browser doesn\'t automatically open, paste the link in your browser '.

							'</body>' .
						'</html>';

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.FROM_EMAILADDRESS . "\r\n";
				//$headers .= 'Bcc: your@email.com' . "\r\n";


				if(mail($frm_submitted['email'], $subject, $message, $headers)){

					unset($_SESSION['c_s_id']);
					unset($_SESSION['cptch']);
				//	error_log('registratiemail succesvol verstuurd naar: '. $frm_submitted['email']);
					return true;
				} else {

					echo 'Error while sending the email. You can activate by clicking this link:  <a href="'.FULLCAL_URL.'/?action=activate&uid='.$int_user_id.'&hash='.$hash_code.'">'.FULLCAL_URL.'/?action=activate&uid='.$int_user_id.'&hash='.$hash_code.'</a>';
				}
			} else {
				$str_query = 'UPDATE users SET `password` = "'.self::getPasswordHashcode($frm_submitted['password']).'", `active` = 1 WHERE user_id = '.$int_user_id;

				$res = mysqli_query($obj_db, $str_query);

				return true;
			}


		} else {
			echo 'something went wrong, the new user could not be inserted.';
		}
  	}

    public static function login($frm_submitted) {


     	global $obj_db;
		$msg = '';

		if(empty($frm_submitted['usern']) && empty($frm_submitted['passw'])) {
			$msg = 'both fields are empty';
		} else if(empty($frm_submitted['usern'])){
			$msg = 'username is empty';

		} else if(empty($frm_submitted['passw'])) {
			$msg = 'password is empty';
		} else {

			$pasword_hash = self::getPasswordHashcode($frm_submitted['passw']);

			$str_query = 'SELECT `user_id`, `username` FROM `users` WHERE `username` = "'.mysqli_real_escape_string($obj_db, $frm_submitted['usern']).'" AND `password` = "'.mysqli_real_escape_string($obj_db, $pasword_hash).'"';
    		$obj_result = mysqli_query($obj_db, $str_query);
			$arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

			if($arr_line !== false && !is_null($arr_line) && !empty($arr_line) && !empty($arr_line['user_id'])) {
				$_SESSION['calendar-uid'] = array();
			    $_SESSION['calendar-uid']['uid'] 		= $arr_line['user_id'];
			    $_SESSION['calendar-uid']['username'] 	= $arr_line['username'];
				$_SESSION['calendar-uid']['hash'] 		= md5($arr_line['user_id'].sha1('89275').date('w').$arr_line['username']);

			} else {
				$msg = 'User not found !';
			}
		}

		return $msg;
  	}

	public static function activate($frm_submitted, $bln_only_forgotten_password=false) {
		global $obj_db;
		global $obj_smarty;

		$str_query = 'SELECT * FROM `users` ' .
				' WHERE `user_id` = ' .$frm_submitted['uid'].
				' AND `user_hash` = "'.$frm_submitted['hash'].'"';

		$res = mysqli_query($obj_db, $str_query);

		if($res !== false) {

			if($bln_only_forgotten_password) {

				$obj_smarty->assign('forgotten_password',   true);

				// set active and remove user_hash
				$str_query = 'UPDATE `users` SET user_hash = "" ' .
					' WHERE `user_id` = ' .$frm_submitted['uid'].
					' AND `user_hash` = "'.$frm_submitted['hash'].'"';

				$res5 = mysqli_query($obj_db, $str_query);

			} else {
				// set active and remove user_hash
				$str_query = 'UPDATE `users` SET `active` = 1 , `registration_date` = NOW(), user_hash = "" ' .
					' WHERE `user_id` = ' .$frm_submitted['uid'].
					' AND `user_hash` = "'.$frm_submitted['hash'].'"';

				$res2 = mysqli_query($obj_db, $str_query);

				if($res2 !== false) {
					$arr_user = mysqli_fetch_array($res, MYSQLI_ASSOC);
				}
			}

			$obj_smarty->assign('uid',   $frm_submitted['uid']);
			$obj_smarty->display(FULLCAL_DIR.'/activation/index.tpl');
			exit;
		}
 	}

	public static function forgottenPassword($frm_submitted) {

		$int_user_id = 0;
		global $obj_db;

      	$str_query = 'SELECT `user_id`, `firstname`, `lastname` FROM `users` WHERE `email` = "'.mysqli_real_escape_string($obj_db, $frm_submitted['email']).'"';

      	$obj_result = mysqli_query($obj_db, $str_query);
		$arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);

      	if($arr_line !== false && !empty($arr_line) ) {
        	$int_user_id = $arr_line['user_id'];
    	}

		if($int_user_id > 0) {

			/*
			 * generate hash
			 */
			$hash_code = self::getUserHashcode($int_user_id);

			$str_query = 'UPDATE users SET `user_hash` = "'.$hash_code.'" WHERE user_id = '.$int_user_id;

			$res = mysqli_query($obj_db, $str_query);

			if(SEND_ACTIVATION_MAIL) {
				/*
				 * send mail to new user
				 */

				if(defined('RESET_PASSWORD_MAIL_SUBJECT') && RESET_PASSWORD_MAIL_SUBJECT !== '') {
					$subject = RESET_PASSWORD_MAIL_SUBJECT;
				} else {
					$subject = 'Reset your password for fast-easy-calendar';
				}

				$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">' .
						'<html>' .
						'<head></head>' .
						'<body>' .
						'You requested to reset your password<br /><br /><br />' .
								'You can do this by clicking on this link:<br />' .
									'<a href="'.FULLCAL_URL.'/?action=reset_password&uid='.$int_user_id.'&hash='.$hash_code.'">'.FULLCAL_URL.'/?action=reset_password&uid='.$int_user_id.'&hash='.$hash_code.'</a>' .
									'<br /><br />If your browser doesn\'t automatically open, paste the link in your browser ' .

						'</body>' .
						'</html>';

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: ' . FROM_EMAILADDRESS. "\r\n";

				if(mail($frm_submitted['email'], $subject, $message, $headers)){

					return true;
				} else {

					echo 'Something went wrong, check your emailaddress or try again later';
				}
			} else {
				// for testing
				echo '<a href="'.FULLCAL_URL.'/?action=reset_password&uid='.$int_user_id.'&hash='.$hash_code.'">'.FULLCAL_URL.'/?action=reset_password&uid='.$int_user_id.'&hash='.$hash_code.'</a>';
			}
		}
  	}

	public static function changePassword($frm_submitted) {
		global $obj_db;

		$str_query = 'UPDATE `users` SET `password` = "'.self::getPasswordHashcode($frm_submitted['passw1']).'" ' .
			' WHERE `user_id` = ' .$frm_submitted['uid'];

		$res = mysqli_query($obj_db, $str_query);

		return $res !== false;
 	}

	public static function isLoggedIn() {
    	$bln_loggedin = (isset($_SESSION['calendar-uid'])
    		&& isset($_SESSION['calendar-uid']['uid'])
    		&& isset($_SESSION['calendar-uid']['username'])
    		&& isset($_SESSION['calendar-uid']['hash'])
    		&& self::getSessionHashcode($_SESSION['calendar-uid']['uid'], $_SESSION['calendar-uid']['username']) == $_SESSION['calendar-uid']['hash']) ;

    	if($bln_loggedin && !is_null(self::getUser())) {
    		return true;
    	}
    	return false;
  	}

	public static function checkLoggedIn() {
    	if( !self::isLoggedIn()) {
    		header("location: ".FULLCAL_URL. "/login.html");exit;
	   	}
  	}

  	public static function isAdmin($int_user_id=-1) {
  		global $obj_db;

		if($int_user_id == -1) {
			$int_user_id = $_SESSION['calendar-uid']['uid'];
		}

      	$str_query = 'SELECT `user_id` FROM `users` WHERE `user_id` = '.$int_user_id. ' AND (`usertype` = "admin" OR `usertype` = "superadmin")';

      	$obj_result = mysqli_query($obj_db, $str_query);
		$arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);


		if($arr_line !== false && !empty($arr_line)) {
			return true;
		} else {
	  		if(defined('ADMIN_USER_ID')) {
				$admin_user_id = ADMIN_USER_ID;
				if(!empty($admin_user_id)) {
					if(strstr($admin_user_id, ',')) {
						$arr_admin_ids = explode(',', $admin_user_id);
						if(in_array($int_user_id, $arr_admin_ids)) {
							return true;
						}
					} else {
						if( (int) $int_user_id === (int) $admin_user_id) {
							return true;
						}
					}
				}
	  		}
		}

  		return false;
  	}

  	public static function isSuperAdmin($int_user_id=-1) {
  		global $obj_db;

		if($int_user_id == -1) {
			$int_user_id = $_SESSION['calendar-uid']['uid'];
		}

      	$str_query = 'SELECT `user_id` FROM `users` WHERE `user_id` = '.$int_user_id. ' AND `usertype` = "superadmin"';

      	$obj_result = mysqli_query($obj_db, $str_query);
		$arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);


		if($arr_line !== false && !empty($arr_line)) {
			return true;
		}
		return false;
  	}

  	public static function isUser($user_id=-1) {
  		global $obj_db;

		if($user_id == -1) {
			$user_id = $_SESSION['calendar-uid']['uid'];
		}

      	$str_query = 'SELECT `user_id` FROM `users` WHERE `user_id` = '.$user_id. ' AND `deleted` = 0 AND `usertype` = "user"';

      	$obj_result = mysqli_query($obj_db, $str_query);
		$arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC);


		if($arr_line !== false && !empty($arr_line)) {
			return true;
		}
		return false;
  	}
}
?>