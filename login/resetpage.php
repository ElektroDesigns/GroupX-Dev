<?php /*versio:3.02*/ 
include 'dbc.php';

?>
<html> 
<head>
<title>User Account Reset Password</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 <script type="text/javascript">
</script>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<h3 class="title">Reset Password</h3>
<p>If you want to change your password, please input your old and new password to make changes.</p>
            <td width="31%">Old Password</td>
            <td width="69%"><input tabindex="14"name="pwd_old" type="password" class="required password"  id="pwd_old"></td>
            <td>New Password</td>
            <td><input tabindex="15"name="pwd_new" type="password" id="pwd_new" class="required password"  ></td>
	<p align="center"> 
          <input tabindex="16"name="doUpdate" type="submit" id="userUpdate" value="Update">
        </p>
<p style="font-size:15px;">Redirecting you to Login <span id="countdown">5</span></p>
	 
</body>
<script>
document.getElementById("userUpdate").addEventListener('click',function(){
					
					var userEmail = 		$('#userEmail').val();
					var	full_name = 		$('#fullName').val().trim();

					var oldPwd =			$('#pwd_old').val().trim();
					var newPwd =			$('#pwd_new').val().trim();
						
								var dataString = '&userEmail=' + userEmail 
								+'&newPwd=' + newPwd ;
									
											   $.ajax({
													type: "POST",
													url: "login.class.php?action=reset",
													dataType: "text",//set to JSON 
													data: dataString,
													cache: false,
													success: function()
													{
														$("#contact_status").html("<h1 style=\"color:green;float:left;margin-left:20%\">Buddy List Updated</h1>");
														$('.add_contact').each(function(){
																this.reset();
															});
															
													},
													error:function(jqXHR, textStatus, errorThrown){
														alert("Error type    " + textStatus + " occured, with value " + errorThrown);
														}
												});
												
											
			
						
});	
</script>
</html>
