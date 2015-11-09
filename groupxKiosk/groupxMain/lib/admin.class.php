<?php
session_start();
require '../../login/dbc.php';

switch($_GET['action']) {
	case 'addUser':
		addUser();
		break;
	case 'searchUser':
		searchUser();
		break;
	case 'searchClass':
		searchClass();
		break; 
	case 'updateClass':
		updateClass();
		break;
	case 'delClass':
		deleteClass();
		break;
	case 'addClass':
		addClass();
		break;
	case 'searchContact':
		searchContact();
		break; 
	case 'updateContact':
		updateContact();
		break;
	case 'delContact':
		deleteContact();
		break;
	case 'addContact':
		addContact();
		break;
	case 'send_adminMessage':
		send_adminMessage();
		break;

	case 'getUser':
		getUser();
		break;
	case 'userUpdate':
		userUpdate();
		break;
}
/*
function searchUser() {
global $link

    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );
if($_POST)
{
	$q = mysqli_real_escape_string($link,$_POST['search']);
	$str_query = "select id, full_name,user_email, user_name, user_level, tel, approved from users where full_name like '%$q%' or user_email like '%$q%' or user_name like '%$q%' order by id LIMIT 5";
	$obj_result = mysqli_query($link, $str_query);
    while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) { 
			
			$arr_content[] = $arr_line;
		}
	
	echo json_encode($arr_content);
	
}
}
*/
				 
function addUser() {
global $link,$live_ClassTable;

    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );

/******************* Filtering/Sanitizing Input *****************************
This code filters harmful script code and escapes data of all POST data
from the user submitted form.
*****************************************************************/
foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}
echo $value;
/********************* RECAPTCHA CHECK *******************************
This code checks and validates recaptcha
****************************************************************/
// require_once('recaptchalib.php');
     
//      $resp = recaptcha_check_answer ($privatekey,
//                                      $_SERVER["REMOTE_ADDR"],
//                                      $_POST["recaptcha_challenge_field"],
//                                      $_POST["recaptcha_response_field"]);

//      if (!$resp->is_valid) {
//        die ("<h3>Image Verification failed!. Go back and try again.</h3>" .
//            "(reCAPTCHA said: " . $resp->error . ")");			
//      }
/************************ SERVER SIDE VALIDATION **************************************/
/********** This validation is useful if javascript is disabled in the browswer ***/

if(empty($data['full_name']) || strlen($data['full_name']) < 4)
{
$err[] = "ERROR - Invalid name. Please enter at least 3 or more characters for your name";
//header("Location: register.php?msg=$err");
//exit();
}

// Validate User Name
if (!isUserID($data['user_name'])) {
$err[] = "ERROR - Invalid user name. It can contain alphabet, number and underscore.";
//header("Location: register.php?msg=$err");
//exit();
}

// Validate Email
if(!isEmail($data['usr_email'])) {
$err[] = "ERROR - Invalid email address.";
//header("Location: register.php?msg=$err");
//exit();
}



/************ USER EMAIL CHECK ************************************
This code does a second check on the server side if the email already exists. It 
queries the database and if it has any existing email it throws user email already exists
*******************************************************************/

//$rs_dup = mysql_query("select count(*) as total from users where user_name='$post[user_name]' OR user_email='$post[user_email]'") or die(mysql_error());
//list($dups) = mysql_fetch_row($rs_dup);

if($dups > 0) {
	die("The user name or email already exists in the system");
	}

if(!empty($_POST['pwd'])) {
  $pwd = $_POST['pwd'];	
  $hash = PwdHash($_POST['pwd']);
 
 }  
 else
 {
  $pwd = GenPwd();
  $hash = PwdHash($pwd);
  
 }
$user_email = $data['user_email'];
$user_name = $data['user_name'];
mysqli_query($link,"INSERT INTO users (`user_name`,`full_name`,`user_email`,`pwd`,`approved`,`date`,`user_level`)
			 VALUES ('$data[user_name]','$data[full_name]','$data[user_email]','$hash','1',now(),'$data[user_level]')
			 ") or die(mysql_error()); 

echo ("User created with password ".$pwd."....done."); 
}

function searchClass() {
global $link,$live_ClassTable;

    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysqli_set_charset( $link , 'utf8' );

	
	$str_query = "SELECT * from ".$live_ClassTable." ";
	$obj_result = mysqli_query($link, $str_query);
    while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) { 
			
			$arr_content[] = $arr_line;
		}
	
	echo json_encode($arr_content);
	


}
function addClass() {
 global $link,$live_ClassTable;

    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
/******************* Filtering/Sanitizing Input *****************************
This code filters harmful script code and escapes data of all POST data
from the user submitted form.
*****************************************************************/
mysqli_set_charset($link , 'utf8');

foreach($_POST as $key => $value) {
	//$data[$key] = filter($value);
	 $data[$key]  = mysqli_real_escape_string($link, $value);
}
echo $value;
 
mysqli_query($link,"INSERT INTO ".$live_ClassTable." (`title`,`instructor`,`category`,`class_duration`,`instructor_link`,`description`,`image_url`,`original_image`,`thumbnail_image`)
			 VALUES ('$data[title]','$data[instructor]','$data[category]','$data[class_duration]','$data[instructor_link]','$data[description]','$data[image_name]','$data[original_image]','$data[thumbnail_image]')
			 ") or die(mysql_error()); 

	
	$str_query = "SELECT * from ".$live_ClassTable" ";
	$obj_result = mysqli_query($link, $str_query);
    while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) { 
			
			$arr_content[] = $arr_line;
		}
	
	echo json_encode($arr_content);
echo "<div class=\"msg\">Class created ....done.</div>"; 
}

function updateClass() {
global $link,$live_ClassTable;
	
		if($link === FALSE) {
			$error= "Database connection failed";
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
foreach($_POST as $key => $value) {
	//$data[$key] = filter($value);
	 $data[$key]  = mysqli_real_escape_string($link, $value);
}
echo $value;
		mysqli_set_charset($link , 'utf8');
		$str_query = 'UPDATE '.$live_ClassTable.' SET title =  "'.$data['title'].'",instructor ="'.$data['instructor'].'",instructor_link ="'.$data['instructor_link'].'",image_url ="'.$data['image_name'].'",class_duration ="'.$data['class_duration'].'",category ="'.$data['category'].'",description ="'.$data['description'].'" WHERE event_id = '.$data['event_id'];
		$obj_result = mysqli_query($link, $str_query);

		$str_query = "SELECT * from ".$live_ClassTable." ";
		$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) { 
			$arr_content[] = $arr_line;
		}
	
	echo json_encode($arr_content);
echo "<div class=\"msg\">User created with password $pwd....done.</div>"; 
}
function deleteClass() {

 global $link;

 
    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	
	
			$str_query = 'DELETE FROM '.$live_ClassTable.' WHERE event_id = '.$_POST['id'];
			$obj_result = mysqli_query($link, $str_query);


echo "<div class=\"msg\">Class has been deleted.</div>"; 
}

/* *******************************************************************************************************
**********************************************************************************************************
*/
function searchContact() {
global $link,$live_ClassTable;

    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

foreach($_POST as $key => $value) {
	$data[$key] =($value);
}
 //echo $data['id'];exit;
	mysqli_set_charset($link ,'utf8' );
	$str_query = 'SELECT * from trilakes_user_data WHERE assigned_user = '.$data['id'];
	$obj_result = mysqli_query($link, $str_query);
	$row_cnt = mysqli_num_rows($obj_result);
	if ($row_cnt>0){
    while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) {
			
			$arr_content[] = $arr_line;
		}
		
	echo json_encode($arr_content);
	}else {
		$arr_content['contact_name'] = "<span  style='color:red;'>Add A Workout BUDDY!!!</span>"; 
		$arr_content['contact_email'] = "";
		$arr_content['id'] = " ";
		$arr_content['assigned_user'] = $_SESSION['user_id'];
		$contact = new StdClass;
	$contact->response = ($arr_content);
	
		echo json_encode($contact) ;
	}
}

function addContact() {
 global $link,$live_ClassTable;

 
    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
/******************* Filtering/Sanitizing Input *****************************
This code filters harmful script code and escapes data of all POST data
from the user submitted form.
*****************************************************************/
mysqli_set_charset($link , 'utf8');

foreach($_POST as $key => $value) {
	//$data[$key] = filter($value);
	 $data[$key]  = mysqli_real_escape_string($link, $value);
}
echo $value;
 
$str_query = 'INSERT INTO trilakes_user_data (assigned_user, contact_name, contact_email)'.
			 'VALUES ( "'.$data['assigned_user'].'",'.
					'"'.$data['contact_name'].'",'.
					'"'.$data['contact_email'].'"'.
					')';
			
$obj_result = mysqli_query($link, $str_query);
	$str_query = "SELECT * from trilakes_user_data WHERE assigned_user = ".$data['assigned_user'];
	$obj_result = mysqli_query($link, $str_query);
    while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) { 
			$arr_content[] = $arr_line;
		}
	echo json_encode($arr_content);
echo "<div class=\"msg\">Your New Workout Buddy has been created.</div>"; 
}

function updateContact() {
global $link,$live_ClassTable;
	
		if($link === FALSE) {
			$error= "Database connection failed";
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
foreach($_POST as $key => $value) {
	//$data[$key] = filter($value);
	 $data[$key]  = mysqli_real_escape_string($link, $value);
}
echo $value;
		mysqli_set_charset($link , 'utf8');
		$str_query = 'UPDATE trilakes_user_data SET contact_name =  "'.$data['contact_name'].'",contact_email ="'.$data['contact_email'].'" WHERE id = '.$data['id'];
		$obj_result = mysqli_query($link, $str_query);

		$str_query = "SELECT * from trilakes_user_data ";
		$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) { 
			$arr_content[] = $arr_line;
		}
	
	echo json_encode($arr_content);
echo "<div class=\"msg\">Your Buddy's information has been updated.</div>"; 
}
function deleteContact() {

 global $link;

 foreach($_POST as $key => $value) {
	//$data[$key] = filter($value);
	 $data[$key]  = mysqli_real_escape_string($link, $value);
}
echo $value;

 
    if($link === FALSE) {
        $error= "Database connection failed";
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	
	
			$str_query = 'DELETE FROM trilakes_user_data WHERE id = '.$_POST['id'];
			$obj_result = mysqli_query($link, $str_query);


echo "<div class=\"msg\">This Workout Buddy has been removed from your list.</div>"; 
}


function sendConfirmation() {


}

function userUpdate(){
global $link,$live_ClassTable;
	
		if($link === FALSE) {
			$error= "Database connection failed";
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
foreach($_POST as $key => $value) {
	 $data[$key]  = mysqli_real_escape_string($link, $value);
	 
}
		mysqli_set_charset($link , 'utf8');
		
		$str_query = 'UPDATE users SET full_name = "'.$data['fullName'].'",address = "'.$data['address'].'",tel = "'.$data['tel'].'",facebook = "'.$data['facebook'].'",website = "'.$data['website'].'",twitter = "'.$data['twitter'].'",user_email = "'.$data['userEmail'].'" WHERE id='.$data['id'];
		
		$obj_result = mysqli_query($link, $str_query);

		$str_query = "SELECT * from users WHERE id= ".$data['id'];
		$obj_result = mysqli_query($link, $str_query);
		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) { 
			$arr_content[] = $arr_line;
		}
	
	echo json_encode($arr_content);




$rs_pwd = mysqli_query($link,"select pwd from users where id='$_POST[id]'");
list($old) = mysql_fetch_row($rs_pwd);
$old_salt = substr($old,0,9);

//check for old password in md5 format
	if($old === PwdHash($data['oldPwd'],$old_salt))
	{
	$newsha1 = PwdHash($data['newPwd']);
	mysqli_query($link,"update users set pwd='$newsha1' where id='$_POST[id]'");
	$msg[] = "Your new password is updated";
	//header("Location: mysettings.php?msg=Your new password is updated");
	} else
	{
	 $err[] = "Your old password is invalid";
	 //header("Location: mysettings.php?msg=Your old password is invalid");
	}
	

	
}
function getUser(){
	global $link,$live_ClassTable;

		if($link === FALSE) {
			$error= "Database connection failed";
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}


		mysqli_set_charset($link , 'utf8');
		$str_query = 'SELECT * from users where id= '.$data['id'];
		
		$obj_result = mysqli_query($link, $str_query);

		while ($arr_line = mysqli_fetch_array($obj_result, MYSQLI_ASSOC)) { 
			$arr_content[] = $arr_line;
		}
	
	echo json_encode($arr_content);
}
?>
