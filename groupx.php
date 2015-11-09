<?php 
include 'login/dbc.php';
session_start();
?>
<html>
<head>
<meta http-equiv="cache-control" content="max-age=31036000" />
<meta http-equiv="cache-control" content="public" />
<meta http-equiv="expires" content="Mon, 22 Jul 2002 11:12:01 GMT" />
<meta http-equiv="vary" content="user-agent" />
<meta name="robots" content="none" /> 
<!-- Bootstrap CSS -->
<script>

</script>
<link rel="stylesheet" type="text/css" href="groupxMain/bootstrap/css/bootstrap.min.css" media="screen" >
<link rel="stylesheet" type="text/css" href="groupxMain/bootstrap/css/bootstrap-theme.min.css" media="screen" >
<link rel="stylesheet" type="text/css" href="groupxMain/css/jquery-ui-1.10.3.custom.orig.css" media="screen" />
<link rel="stylesheet" type="text/css" href="groupxMain/css/jquery.ui.timepicker.css" media="screen" />
<link rel="stylesheet" type="text/css" href="groupxMain/css/bootstrap-datetimepicker.min.css" media="screen" >



	<link rel="stylesheet" href="groupxMain/css/font-icons.css" type="text/css" />
	<link rel="stylesheet" href="groupxMain/css/animate.css" type="text/css" />
	<link rel="stylesheet" href="groupxMain/css/magnific-popup.css" type="text/css" />

<link rel="stylesheet" type="text/css" href="login/css/modals.css" media="screen" />
<link rel="stylesheet" type="text/css" href="login/css/page.css" media="screen" />
<link rel="stylesheet" type="text/css" href="login/css/forms.css" media="screen" />
<link rel="stylesheet" href="groupxMain/css/style.css" type="text/css" />
<link rel="stylesheet" href="groupxMain/css/dark.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="groupxMain/css/test.css" media="screen" />
<link rel="stylesheet" type="text/css" href="groupxMain/css/main.css" media="screen" />
<!--<link rel="stylesheet" type="text/css" href="groupxMain/css/jquery.mCustomScrollbar.css" media="screen" />-->
<meta name="viewport" content="width=device-width, initial-scale=1.5, maximum-scale=1.0">
<title>Members Login</title> 
</head>
<body class="demo5 preload stretched">
<div class="modal-wrap md-effect-5" id="md-1" >
	<div class="md-content">
		<div class="form-wrap">
			<span class="logo"></span>
			<span class="md-close">✕</span>
			<h1>Login or register</h1>
            <form id="login-form">
                <input type="text" id="login_username" name="username" placeholder="Username" required />
                <input type="password" id="login_password" name="password" placeholder="Password" required />
                <button class="btn-primary"style="padding:20px 14px">Continue.</button>
            </form>
			<div style="padding-top:20px"></div>
			<button class="button md-click md-setperspective btn-primary no-member" data-modal="md-3">Forgot Password.</button>
            <button class="button md-click md-setperspective btn-primary no-member" data-modal="md-2" ><p>Not a member?</p><a style="margin-left:20px;"href=""> Sign up now →</a></button>
		</div><!-- form wrap end -->
	</div><!-- modal content end -->
</div><!-- modal wrap end -->

<!-- modal 2 (Lost Password) -->
<div class="modal-wrap md-effect-5" id="md-3">
	<div class="md-content">
		<div class="form-wrap">
			<span class="logo"></span>
			<span class="md-close">✕</span>
			<h1>Recover your password</h1>
            <form id="lost-form">
                <input type="text" id="recover_username" name="Name" placeholder="Your Name" required />
                <input type="email" id="recover_email"  name="Email" placeholder="Your Email Address" required />
                <button  class="btn-primary">Submit</button>
            </form>
		</div><!-- form-wrap end -->
	</div><!-- modal content end -->
</div><!-- modal wrap end -->
<div class="modal-wrap md-effect-5" id="md-4">
	<div class="md-content">
		<div class="form-wrap">
			<span class="logo"></span>
			<span class="md-close">✕</span>
			<h1>Reset Password</h1>
            <form id="reset-password">
				<input type="text" id="reset_username" name="Name"  />
				<input type="resetpass" id="reset_password" name="resetpass" placeholder="New Password" required />
                <button  class="btn-primary">Submit</button>
            </form>
		</div><!-- form-wrap end -->
	</div><!-- modal content end -->
</div><!-- modal wrap end -->

<!-- modal 3 (Register) -->
<div class="modal-wrap md-effect-5" id="md-2">
	<div class="md-content">
		<div class="form-wrap">
			<span class="logo"></span>
			<span class="md-close">✕</span>
			<h1>Create Your Account</h1>
            <form id="register-form">
				<input name="user_name" placeholder="Choose username" class="form-control" type="text" id="register_username" required />
				<input style="font-size:12px;color:white;font-weight:bold;"class="btn btn-orange" name="btnAvailable" type="button" id="btnAvailable" 
					onclick='$("#checkid").html("Please wait..."); $.get("login/checkuser.php",{ cmd: "check", user: $("#register_username").val() } ,function(data){  $("#checkid").html(data); });'
					value="Check Availability"> 
				<span style="color:red; font: bold 12px verdana; " id="checkid"class="checkid" ></span>
                <input id="register_name" type="text" name="name" placeholder="Your Name" required />
                <input id="register_email" type="email" name="email" placeholder="Your Email Address" required />
				<input id="register_password" type="password" name="password" placeholder="Password" required />
                <button class="btn-primary">Sign Me Up</button>
            </form>
		</div><!-- form-wrap end -->
	</div><!-- modal content end -->
</div><!-- modal wrap end -->
	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">

		<!-- Header
		============================================= -->


		</header><!-- #header end -->

		<section id="slider" class="force-full-screen full-screen">

			<div class="force-full-screen full-screen dark section nopadding nomargin noborder ohidden">

				<div class="container clearfix">
					<div class="slider-caption slider-caption-center" style="margin-top:22%">

						<a data-animate="fadeInUp" data-delay="400" href="#" style="text-decoration:none"class="button md-click md-setperspective button-border button-light button-rounded button-large noleftmargin nobottommargin" data-modal="md-1" style="margin-top: 30px;">Start a Workout</a>
						<a data-animate="fadeInUp" data-delay="600" data-toggle="modal" href="#myModal" style="text-decoration:none"class="button button-3d button-teal button-large nobottommargin" style="margin: 30px 0 0 10px;">How to: Video</a>
					</div>
				</div>
				
<!-- <div class="section yt-bg-player nomargin dark full-screen" data-quality="hd1080" data-start="10" data-stop="70" data-video="http://youtu.be/bRfKzeQ3kYM?autoplay=1" data-fullscreen="true">
    <div class="container clearfix" />
</div> -->
				<div class="video-wrap">
					<video id ="kioskvid" preload="auto"  autoplay unmuted>
						<source src='http://groupxnow.com/images/videos/GroupXDemo.mp4' type='video/mp4' />
						<source src='http://groupxnow.com/images/videos/GroupXDemo.mp4' type='video/mp4' />
						<source src='http://groupxnow.com/images/videos/GroupXDemo.webm' type='video/webm' />
					</video>
					<div class="video-overlay" style="background-color: rgba(0,0,0,0.05);"></div>
				</div> 
 <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">How to Use GroupX onDemand</h4>
                </div>
                <div class="modal-body">
                  <!--  <iframe id="howtoVideo" width="1080" height="720" src="https://www.youtube.com/embed/-bIILrZ4aw8?autoplay=1&amp;rel=0" frameborder="0" allowfullscreen></iframe>
               --> </div>
            </div>
        </div>
    </div>

		</section>


	</div><!-- #wrapper end -->


<!-- ************************************************************************************************************

												SCHEDULER SECTION
************************************************************************************************************** -->
	<script>
		var idusr = "";
		var user_email = "";
		var usr = "";
		var rnk = "";
		var user_ip ="";   
		var usrname = "";
		var site = "<?php echo ($site); ?>";
    </script>

	<div class="container-fluid" style="visibility:hidden">
	<div class="navbar-brand navbar-fixed-top">
		<div id="siteLogo" class="container-fluid pull-right ">
			<div class="row ">
				<li>
					<img src='login/images/lakenonaLogo.jpg'>
				</li>
			</div>
		</div>
		<div class="container-fluid" >
         	<div class="row pull-left"style="padding-left:15px;">
				<div class="dropdown ">
					<a id="menu" style="font-size:2em;" href="#" class="dropdown-toggle " data-toggle="dropdown">Menu <span class="glyphicon glyphicon-menu-hamburger"></span></a>
					<ul class="dropdown-menu multi-level" role="menu">
					<!--		<li>
								<a href="admin/index.html">Administration Settings</a>
							</li>-->

							<li>
								<a href="../login/logout.php">Logout</a>
							</li>
							<li class="dropdown-submenu">
								<a tabindex="-1">Help</a>
								<ul class="dropdown-menu">
									<li>
										<a id="help" href="http://groupxondemand.com/ostic/"target="_blank">Submit a Ticket</a>
									</li>
								</ul>
							</li>
					<!--		<li class="dropdown-submenu">
								<a class="dropdown-toggle" data-toggle="dropdown">Personal Settings</a>
									<ul class="dropdown-menu">
										<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
											<tr> 
												<td colspan="3">&nbsp;</td>
											</tr>
											<tr> 
												<td width="20" valign="top"></td>
													<p>&nbsp;</p>
													<p>&nbsp;</p>
													<p>&nbsp;</p>
												<td width="732" valign="top"></td>
												<h3 class="titlehdr">My Account - Settings</h3>
												<p> 

												</p>
												<p>Here you can make changes to your profile. Please note that you will 
													not be able to change your email which has been already registered.</p>
												<table width="90%" border="0" align="center" cellpadding="3" cellspacing="3" class="forms">
												<tr> 
													<td colspan="2">Your Name<br> 
														<input tabindex="1" name="name" type="text" id="fullName"  class="required"size="38"> 
													</td>
													<td>User Name<br>
														<input tabindex="2" name="user_name" id="userName"class="required" type="text" size="30" disabled>
													</td> 
												</tr>
												<tr> 
													<td colspan="2"rowspan="3">Address <span class="example">(full address with ZIP)</span><br> 
														<textarea tabindex="3" name="address" cols="40" rows="4" class="required" id="address"></textarea> 
													</td>
												</tr>
												<tr> 
													<td width="27%">Phone<br>
														<input tabindex="4" name="tel" type="text" id="tel" class="required" value=""size="30"></td>
												</tr>
												<tr> 
													<td>Email<br>
														<input name="user_email" type="text" id="userEmail"  value="" disabled size="30"></td>
												</tr>
												<tr> 
													<td colspan="2">Website <br>
														<input tabindex="5" name="web" type="text" id="website" size="38"></input>
														<br></br><span class="example">GroupX is pleased to promote your Page</span>
													</td> 
													<td colspan="2" rowspan="8">Workout Buddies<br>
														<table id="contactInfo">
												<tr style="height:155px">
													<td colspan="2">
														<fieldset id="select_contact"><!--USER DATA INSERTED HERE--> <!-- </fieldset>
													</td>
												</tr>
												<tr><p id="id"></p>
													<td>Name<br>
														<input tabindex="9" type="text" id="contactName" size="30"></input>
													</td>
													<td colspan="2">
														<div id="submitInfo">
															<input tabindex="11" type="submit" id="add" value="Add" class="alt_btn">
															<input tabindex="12" type="submit" id="update" value="Update" class="alt_btn">
															<input tabindex="13"type="submit" id="delete" value="  Delete  ">
														</div>
													</td>
												</tr>
												<tr>
													<td>Email<br>
														<input  tabindex="10" type="text" id="contactEmail" size="30"></input>
													</td>
													<td>
													</td>
												</tr>
													<td></td>
											</table>
										</td>
									</tr>
									<tr> 
										<td colspan="4">Facebook Page <br>
											<input tabindex="6" name="web" type="text" id="facebook" class="optional defaultInvalid url" value=""size="38"> 
											<br><span class="example">Let's "LIKE" each other</span>
										</td> 
									</tr>
									<tr> 
										<td colspan="4">Twitter <br>
											<input tabindex="7" name="web" type="text" id="twitter" class="optional defaultInvalid url" value=""size="38"> 
											<br><span class="example">We're Happy to "FOLLOW" you</span></td> 
									</tr>
									<tr> 
										<td colspan="1">
										<div class="optout">
											<input tabindex="8" type="checkbox" id="terms-agree" name="terms-agree" checked="checked" />
											<label for="terms-agree"></label>
											<p class="newsletter">Newsletter Opt-In</p>
											<br><span class="example" style="font-size:10px;">Deselect to Opt-Out</span>
										</div>
										</td> 
									</tr>
									<tr>
										<td colspan="3"> <h3 class="titlehdr">Change Password</h3>
										</td>
									<tr>
										<td colspan="3"> <p>If you want to change your password, please input your old and new password 
																	to make changes.</p></td>
									</tr>
									<tr> 
										<td width="31%">Old Password</td>
										<td width="69%"><input tabindex="14"name="pwd_old" type="password" class="required password"  id="pwd_old"></td>
									</tr>
									<tr> 
										<td>New Password</td>
										<td><input tabindex="15"name="pwd_new" type="password" id="pwd_new" class="required password"  ></td>
									</tr>
								</tr>
									</table>
									<p align="center"> 
									<input tabindex="16"name="doUpdate" type="submit" id="userUpdate" value="Update">
									</p>
								</tr>
							</table>
						</ul>
					</li> -->
		         </ul>
			</div>
		

			</div>
			</div>
			
			<div id ="mainContent" class="row" style="visibility:hidden" >
			<div id="calendar" style="visibility:hidden"></div>
			<div id="container">
             <div id="main" role="main">
                <!-- Movie browser -->
                <div id="browser">
				<div class="panel-group" id="accordion">
                    <!-- Category -->
					<div id="groupxRoom" data-toggle="collapse" class="panel-collapse collapse" data-row="1">
						
						<div class="category" data-row="1"><p style="margin-top:-10px;padding-left:10px">All GroupX Workouts<p>
							<ul id ="groupx" value ="1"class="categoryRow clearfix"></ul>
						</div>
 					</div>
                    <!-- Category -->
					<div id="groupxRoom"  data-toggle="collapse" class="panel-collapse collapse" data-row="2" >
						
						<div class="category "data-row="2"><p style="margin-top:-10px;padding-left:10px">Spinning</p>
							<ul id ="groupxSpin" value ="2"class="categoryRow clearfix"></ul>
						</div>
					</div>
                   <!-- Category -->

                    <!-- Category -->

					<div id="groupxRoom"  data-toggle="collapse" class="panel-collapse collapse"data-row="3">
						
						<div class="category "data-row="3"><p style="margin-top:-10px;padding-left:10px">Silver Series</p>
							<ul id ="senior" value="3"class="categoryRow clearfix"></ul>
						</div>
                    </div>
                    <!-- Category -->
					<div id="groupxRoom"  data-toggle="collapse" class="panel-collapse collapse"data-row="4">
						
						<div class="category "data-row="4"><p style="margin-top:-10px;padding-left:10px">Youth Fitness</p>
							<ul id ="kids" value="4"class="categoryRow clearfix"></ul>
						</div>
                    </div>
                    <!-- Category -->
					<div id="groupxRoom" data-toggle="collapse" class="panel-collapse collapse"data-row="5">
						
						<div class="category "data-row="5" ><p style="margin-top:-10px;padding-left:10px">Just Beginning again</p>
							<ul id ="beginner" value="5"class="categoryRow clearfix"></ul>
						</div>
                    </div>
                   <!-- Category -->
				   <div id="groupxRoom" data-toggle="collapse" class="panel-collapse collapse"data-row="6">
						
						<div class="category " data-row="6"><p style="margin-top:-10px;padding-left:10px">Advanced Workouts</p>
							<ul id ="advanced" value="6"class="categoryRow clearfix"></ul>
						</div>
                    </div>
                    <!-- Category -->
					<div id="groupxRoom"  data-toggle="collapse" class="panel-collapse collapse"data-row="7">
						
						<div class="category "data-row="7"><p style="margin-top:-10px;padding-left:10px">Dance Outloud</p>
							<ul id ="dance" value="7" class="categoryRow clearfix"></ul>
						</div>
                    </div>
                    <!-- Category -->
					<div id="groupxRoom"  data-toggle="collapse" class="panel-collapse collapse"data-row="8">
					
						<div class="category "data-row="8"><p style="margin-top:-10px;padding-left:10px">Yoga / Pilates</p>
							<ul id ="yoga" value="8"class="categoryRow clearfix"></ul>
						</div>
                    </div>
                   <!-- Category -->
				   <div id="groupxRoom"  data-toggle="collapse" class="panel-collapse collapse"data-row="9">
						
						<div class="category "data-row ="9"><p style="margin-top:-10px;padding-left:10px">Strength</p>
							<ul id ="strength" value="9"class="categoryRow clearfix"></ul>
						</div>
					</div>
					<!-- Category -->
					<div id="groupxRoom"  data-toggle="collapse" class="panel-collapse collapse"data-row="10">
						
						<div class="category "data-row ="10"><p style="margin-top:-10px;padding-left:10px">Cardio</p>
							<ul id ="cardio" value="10"class="categoryRow clearfix"></ul>
						</div>
                    </div>
					<div id="groupxRoom"  data-toggle="collapse" class="panel-collapse collapse"data-row="2">
						<div class="category "data-row="2"><p style="margin-top:-10px;padding-left:10px">Kids Movies</p>
							<ul id ="movies"value ="2"class="categoryRow clearfix"></ul>
						</div>
 					</div>
                </div>
				</div>				
            </div>            
        </div> <!--! end of #container -->        

			</div>
	</div>
	</div>
	<div id="dialog-message" style="display: none;">
		<!-- <div id= "error_message" style="height:20px;font-size:10pt;color:#FF0004;" ></div> -->
	<div class="row">
			<label class=" control-label" for="title">Title </label>
					<input type="text" id="edited_title" style="color:#ccc"  />
			<label class=" control-label" for="title">Start Time </label>
					<input type="text" id="timepicker_starttime"  style="color:#ccc" />
	</div>
    <div class="row" >
            <div class="form-group">
		 <div id="datepicker_enddate" ></div>
<input type="hidden" id="my_hidden_input">
                   <input id="datepicker_enddate"type="hidden" />
             </div>
     </div>
		<!--		<div id="info_txt" style="text-align:center;color:blue;"></div>
                <div style="margin: 3px 0;">
					<input type="hidden" name="event_id" id="event_id" value="" />
				</div>
				<div id= "error_message" >
                </div> -->

	</div>
	<div id="user-dialog-message" style="display: none;">
		<div id= "error_message" style="display:none;height:20px;font-size:10pt;color:#FF0004;" ></div>
			<form class="form-horizontal" >
				<div style="color:white" class ="row">
					<label id="user_label_id" class="col-lg-4 control-label">User:</label>
				</div>
					<p>Scheduled this Class</p>
					<div class="row">
						<div class="container" style="border:2px solid #fff; width:95%; height: 150px; overflow-y: scroll;" id="contacts"> <!--USER DATA INSERTED HERE--></div>
					</div>
			</form>
	</div>
			</div>
	</div>
	</div>
	<script type='text/javascript' src="groupxMain/js/jquery-2.1.4.min.js"></script>
	<script type='text/javascript' src="groupxMain/bootstrap/js/bootstrap.min.js"></script>
	<script type='text/javascript' src="groupxMain/js/jquery-ui.min.js"></script>
	<script type='text/javascript' src="groupxMain/js/controller.js"></script>
	<script type='text/javascript' src="login/jquery.validate.min.js"></script>
	<script type='text/javascript' src="login/js/login.js"></script> 
	<script type="text/javascript" src="groupxMain/js/plugins.js"></script>
	<script type="text/javascript" src="groupxMain/js/functions.js"></script>
	<script type='text/javascript' src="groupxMain/js/groupxroom.js"></script>
	<script type='text/javascript' src="groupxMain/js/bootbox.min.js"></script>
		<script type='text/javascript' src='groupxMain/js/moment.min.js'></script>
	<script type='text/javascript' src="groupxMain/js/bootstrap-datetimepicker.min.js"></script>
	<script type='text/javascript' src='groupxMain/js/jquery.ui.timepicker.js'></script>
	<script type='text/javascript' src='login/js/classie.js'></script>
	<script type='text/javascript' src='login/js/modal.js'></script>

<!--	<script type='text/javascript' src='groupxMain/js/jquery.mCustomScrollbar.js'></script>	-->
</body>
</html>