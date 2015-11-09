
<html>
<head>
<title>Thank you</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

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
<h3 class="titlehdr">Thank you</h3>
      <h4>Your registration is now complete!</h4>
      <p><font size="2" face="Arial, Helvetica, sans-serif">Check you EMAIL for an activation email 
        (dont forget to check your spam folder). 
        Please check your email and click on the activation link. You can <a href="../groupx.php">login 
        here</a> if you have already activated your account.</font></p>
      <p>&nbsp;</p>
	   
      <p align="right">&nbsp; </p></td>
    <td width="196" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
<script type="text/javascript">

(function () { 
    var timeLeft = 5,
        cinterval;

    var timeDec = function (){
        timeLeft--;
        document.getElementById('countdown').innerHTML = timeLeft;
        if(timeLeft === 0){
            document.location.href="../groupx.php";	 
        }
    };

    cinterval = setInterval(timeDec, 1000);
})();

</script>
<p style="font-size:20px;text-align:center;">Redirecting you to Login <span id="countdown">5</span></p>
</body>
</html>
