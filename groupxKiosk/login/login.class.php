<?php 
/***************                      *********************

						LOGIN

***********************************************************/
session_start();
require 'dbc.php';
require '../'.$site.'/mail/PHPMailer-master/PHPMailerAutoload.php';
switch($_GET['action']) {
	case 'login':
		login();
		break;
	case 'register':
		register();
		break;
	case 'reset':
		recover();
		break;
}

function login(){

global $link;

foreach($_POST as $key => $value) {
	$data[$key] = ($value); // post variables are filtered
	
}


$user_email = $data['user_email'];
$pass = $data['pass'];


if (strpos($user_email,'@') === false) {
    $user_cond = "user_name='$user_email'";
} else {
      $user_cond = "user_email='$user_email'";
    
}

$result = mysqli_query($link,"SELECT `id`,`pwd`,`full_name`,`approved`,`user_level`,`user_email` FROM users WHERE 
           $user_cond
			AND `banned` = '0'
			") ; 
$num = mysqli_num_rows($result);

  // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num > 0 ) {
		$str_query = "SELECT `id`,`full_name`,`user_level`,`user_email` FROM users WHERE 
           $user_cond
			AND `banned` = '0'
			";
		$obj_result = mysqli_query($link, $str_query);
		$user = array();	
		while ($arr_line = mysqli_fetch_array($obj_result , MYSQLI_ASSOC)) {
			//$arr_line['editable'] 	= true ;
			$user[] = $arr_line;
		}
	
			list($id,$pwd,$full_name,$approved,$user_level,$user_email) = mysqli_fetch_row($result);
			
			if(!$approved) {
			
			
				$msg['errorCode'] = 3;
					echo json_encode ($msg);
					exit;
			
			 }
			
				//check against salt
			if ($pwd === PwdHash($pass,substr($pwd,0,9))) {
					//	if ($pwd === $pwd){
								
							 // this sets session and logs user in  
							if(!isset($_SESSION)) {
									session_start();
							}
							   session_regenerate_id (true); //prevent against session fixation attacks.

							   // this sets variables in the session 
								$_SESSION['user_id']= $id;
								$_SESSION['user_email']= $user_email;
								$_SESSION['user_name'] = $full_name;
								$_SESSION['user_level'] = $user_level;
								$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
								
								//update the timestamp and key for cookie
								$stamp = time();
								$ckey = GenKey();
								mysqli_query($link,"update users set `ctime`='$stamp', `ckey` = '$ckey' where id='$id'") or die(mysql_error());
								
								//set a cookie 
						
					   if(isset($_POST['remember'])){
								  setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
								  setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
								  setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
								  setcookie("user_email",$_SESSION['user_email'], time()+60*60*24*COOKIE_TIME_OUT, "/");
								  setcookie("user_level",$_SESSION['user_level'], time()+60*60*24*COOKIE_TIME_OUT, "/");
								   }
							echo json_encode( $user) ;
						
				
			}else
				{ 
				$msg['errorCode'] = 2;
					echo json_encode ($msg);
					//header("Location: login.php?msg=$msg");
				}
	} 
	else { 
		$msg['errorCode'] = 1;
		echo json_encode ($msg);
	  }		

}   

/***************                      *********************

						REGISTRATION

***********************************************************/					 
function register(){ 
global $link;
function get_include_contents($filename, $variablesToMakeLocal) {
extract($variablesToMakeLocal);
if (is_file($filename)) {
ob_start();
include $filename;
return ob_get_clean();
}
return false;
}
	/******************* Filtering/Sanitizing Input *****************************
	This code filters harmful script code and escapes data of all POST data
	from the user submitted form.
	*****************************************************************/
	foreach($_POST as $key => $value) {
		$data[$key] = ($value);
	}

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

	if(empty($data['user_name']) || strlen($data['user_name']) < 4)
	{echo ("hello4");
	$err[] = "ERROR - Invalid name. Please enter atleast 3 or more characters for your name";
	//header("Location: register.php?msg=$err");
	//exit();
	}

	// Validate User Name
	if (!isUserID($data['user_username'])) {echo ("hello3");
	$err[] = "ERROR - Invalid user name. It can contain alphabet, number and underscore.";
	//header("Location: register.php?msg=$err");
	//exit();
	}

	// Validate Email
	if(!isEmail($data['user_email'])) {echo ("hello1");
	$err[] = "ERROR - Invalid email address.";
	//header("Location: register.php?msg=$err");
	//exit();
	}
	// Check User Passwords
//	if (!checkPwd($data['pass'],$data['pass2'])) {echo ("hello2");
//	$err[] = "ERROR - Invalid Password or mismatch. Enter 5 chars or more";
	//header("Location: register.php?msg=$err");
	//exit();
//	}
		  
	$user_ip = $_SERVER['REMOTE_ADDR'];

	// stores sha1 of password
	$sha1pass = PwdHash($data['pass']);

	// Automatically collects the hostname or domain  like example.com) 
	$host  = $_SERVER['HTTP_HOST'];
	$host_upper = strtoupper($host);
	$path   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

	// Generates activation code simple 4 digit number
	$activ_code = rand(1000,9999);

	$user_email = $data['user_email'];
	$user_name = $data['user_name'];

	/************ USER EMAIL CHECK ************************************
	This code does a second check on the server side if the email already exists. It 
	queries the database and if it has any existing email it throws user email already exists
	*******************************************************************/

	$rs_duplicate = mysqli_query($link, "select count(*) as total from users where user_email='$user_email' OR  user_name='$user_name'") or die(mysql_error());
	list($total) = mysqli_fetch_row($rs_duplicate);

	if ($total > 2)
	{echo ("hello5");
	$err[] = "ERROR - The username/email already exists. Please try again with different username and email.";
	//header("Location: register.php?msg=$err");
	//exit();
	}
	/***************************************************************************/

	if(empty($err)) {

	$sql_insert = "INSERT into `users`(`full_name`,`user_email`,`pwd`,`date`,`users_ip`,`activation_code`,`user_name`)
				VALUES('$data[user_name]','$user_email','$sha1pass',now(),'$user_ip','$activ_code','$user_name')";
			
	mysqli_query($link,$sql_insert) or die("Insertion Failed:" . mysql_error());
	$user_id = mysqli_insert_id($link);  
	$md5_id = md5($user_id);
	mysqli_query($link,"update users set md5_id='$md5_id' where id='$user_id'");
	//	echo "<h3>Thank You</h3> We received your submission.";

		$mail = new PHPMailer;
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';                       // Specify main and backup server
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'groupx67@gmail.com';                   // SMTP username
		$mail->Password = 'austin67';               // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
		$mail->Port = 587;                                    //Set the SMTP port number - 587 for authenticated TLS
		$mail->setFrom('LakeNona@groupondemand.com', 'Lake Nona');     //Set who the message is to be sent from
		//$mail->addReplyTo('labnol@gmail.com', 'First Last');  //Set an alternative reply-to address
		$mail->addAddress($user_email, $user_name);  // Add a recipient
		//$mail->addAddress('ellen@example.com');               // Name is optional
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');
		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		//$mail->addAttachment('/usr/labnol/file.doc');         // Add attachments
		//$mail->addAttachment('/images/image.jpg', 'new.jpg'); // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML
		// $mail->SMTPDebug = 2;
		$mail->Subject = 'GroupX onDemand Registration / Activation';
		$variable['user_username'] = $data['user_name'];
		$variable['user_name'] = $data['user_name'];
		$variable['user_email'] = $data['user_email'];
		$variable['host'] = $host;
		$variable['path'] = $path;
		$variable['activ_code'] = $activ_code;
		$variable['id'] = $md5_id;
		//$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		//$mail->AltBody = 'HEllo JIm Reydnolds this is a test';
		 
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->Body = get_include_contents('registration.php', $variable);
		$mail->Send();
				$msg['errorCode'] = 1;
					echo json_encode ($msg);
	  exit();
		 
		 } else{
				if(!$mail->send()) {
				   echo 'Message could not be sent.';
				   echo 'Mailer Error: ' . $mail->ErrorInfo;
				   exit;
				}
		 }
 }
				 
/***************                      *********************

						PASSWORD RECOVERY

***********************************************************/
function recover(){
	$err = array();
	$msg = array();

	foreach($_POST as $key => $value) {
		$data[$key] = filter($value);
	}
	if(!isEmail($data['user_email'])) {
	$err[] = "ERROR - Please enter a valid email"; 
	}

	$user_email = $data['user_email'];

	//check if activ code and user is valid as precaution
	$rs_check = mysql_query("select id from users where user_email='$user_email'") or die (mysql_error()); 
	$num = mysql_num_rows($rs_check);
	  // Match row found with more than 1 results  - the user is authenticated. 
		if ( $num <= 0 ) { 
		$err[] = "Error - Sorry no such account exists or registered.";
		//header("Location: forgot.php?msg=$msg");
		//exit();
		}


	if(empty($err)) {

	$new_pwd = GenPwd();
	$pwd_reset = PwdHash($new_pwd);
	//$sha1_new = sha1($new);	
	//set update sha1 of new password + salt
	$rs_activ = mysqli_query($link,"update users set pwd='$pwd_reset' WHERE 
							 user_email='$user_email'") or die(mysql_error());
							 
	$host  = $_SERVER['HTTP_HOST'];
	$host_upper = strtoupper($host);						 
							 
	//send email

	$message = 
	"Here are your new password details ...\n
	User Email: $user_email \n
	Passwd: $new_pwd \n

	Thank You

	Administrator
	$host_upper
	______________________________________________________
	THIS IS AN AUTOMATED RESPONSE. 
	***DO NOT RESPOND TO THIS EMAIL****
	";

		mail($user_email, "Reset Password", $message,
		"From: \"Member Registration\" <auto-reply@$host>\r\n" .
		 "X-Mailer: PHP/" . phpversion());						 
							 
	$msg[] = "Your account password has been reset and a new password has been sent to your email address.";						 

	 }
}
?>