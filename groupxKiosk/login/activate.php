<?php /*versio:3.02*/ 
include 'dbc.php';
global $link;
foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

/******** EMAIL ACTIVATION LINK**********************/
if(isset($get['user']) && !empty($get['activ_code']) && !empty($get['user']) && is_numeric($get['activ_code']) ) {

$err = array();
$msg = array();

$user = mysqli_real_escape_string($link,$get['user']);
$activ = mysqli_real_escape_string($link,$get['activ_code']);

//check if activ code and user is valid
$rs_check = mysqli_query($link,"select id from users where md5_id='$user' ") or die (mysql_error()); 
$num = mysqli_num_rows($$link,rs_check);
  // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num <= 0 ) { 
	$err[] = "Sorry no such account exists or activation code invalid.";
	//header("Location: activate.php?msg=$msg");
	//exit();
	}

if(empty($err)) {
// set the approved field to 1 to activate the account
$rs_activ = mysqli_query($link,"update users set approved='1' WHERE 
						 md5_id='$user' ") or die(mysql_error());
$msg[] = "Thank you. Your account has been activated.";
//header("Location: activate.php?done=1&msg=$msg");						 
//exit();
 }
}

/******************* ACTIVATION BY FORM**************************/
if ($_POST['doActivate']=='Activate')
{
$err = array();
$msg = array();

$user_email = mysqli_real_escape_string($link,$_POST['user_email']);
$activ = mysqli_real_escape_string($link,$_POST['activ_code']);
//check if activ code and user is valid as precaution
$rs_check = mysqli_query($link,"select id from users where user_email='$user_email' and activation_code='$activ'") or die (mysql_error()); 
$num = mysqli_num_rows($link,$rs_check);
  // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num <= 0 ) { 
	$err[] = "Sorry no such account exists or activation code invalid.";
	//header("Location: activate.php?msg=$msg");
	//exit();
	}
//set approved field to 1 to activate the user
if(empty($err)) {
	$rs_activ = mysqli_query($link,"update users set approved='1' WHERE 
						 user_email='$user_email' AND activation_code = '$activ' ") or die(mysql_error());
	$msg[] = "Thank you. Your account has been activated.";
 }
//header("Location: activate.php?msg=$msg");						 
//exit();
}

	

?>
<html>
<head>
<title>User Account Activation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

 <script type="text/javascript">

(function () {
    var timeLeft = 5,
        cinterval;

    var timeDec = function (){
        timeLeft--;
        document.getElementById('countdown').innerHTML = timeLeft;
        if(timeLeft === 0){
            document.location.href="/";	
        }
    };

    cinterval = setInterval(timeDec, 1000);
})();

</script>


<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>


<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr> 
    <td width="160" valign="top"><p>&nbsp;</p>
      <p>&nbsp; </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="732" valign="top">
<h3 class="titlehdr">Account Activation</h3>

      <p> 
        <?php
	  /******************** ERROR MESSAGES*************************************************
	  This code is to show error messages 
	  **************************************************************************/
	if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* $e <br>";
	    }
	  echo "</div>";	
	   }
	   if(!empty($msg))  {
	    echo "<div class=\"msg\">" . $msg[0] . "</div>";

	   }	
	  /******************************* END ********************************/	  
	  ?>
      </p>
      <h1>Your account has been activated</h1>
<p style="font-size:15px;">Redirecting you to Login <span id="countdown">5</span></p>
	 
      
	  
      <p>&nbsp;</p>
	 
      </td>
    <td width="196" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

</body>
</html>
