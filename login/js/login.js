$(document).ready(function(){
$('.modal-dialog').width('75%');
			eventTable = "trilakes_groupxroom_events";
			classTable = "trilakes_groupxroom_usage_stats";
			repeatTable = "trilakes_groupxroom_repeat_events";
			var dataString = '&eventTable=' + eventTable +'&repeatTable=' + repeatTable +'&classTable=' + classTable;
			$.post(''+site+'/model/events.class.php',dataString);
			$.post(''+site+'/command/cal_events2.php',dataString);
    $.validator.addMethod("user_name", function(value, element) {
        return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
    }, "Username must contain only letters, numbers, or underscore.");

    $("#register").validate();
			// Initialize Globe 3

/* #####################################################################
   #
   #   Project       : HOW TO VIDEO CONTROL
   #   Author        : 
   #   Version       : 
   #   Created       : 
   #   Last Change   : 
   #
   ##################################################################### */
      /* Get iframe src attribute value i.e. YouTube video url
    and store it in a variable */
    var url = $("#howtoVideo").attr('src');
    
    /* Assign empty url value to the iframe src attribute when
    modal hide, which stop the video playing */
    $("#myModal").on('hide.bs.modal', function(){
        $("#howtoVideo").attr('src', '');
		$("#kioskvid")[0].play();
	
    });
    
    /* Assign the initially stored url back to the iframe src
    attribute when modal is displayed again */
    $("#myModal").on('show.bs.modal', function(){
        $("#howtoVideo").attr('src', url);
		$("#kioskvid")[0].pause();
    });
   
 /* #####################################################################
   #
   #   Project       : Modal Login with jQuery Effects
   #   Author        : Rodrigo Amarante (rodrigockamarante)
   #   Version       : 1.0
   #   Created       : 07/29/2015
   #   Last Change   : 08/04/2015
   #
   ##################################################################### */
     

    
    var $formLogin = $('#login-form');
    var $formLost = $('#lost-form');
	var $reset = $('#reset-password');
    var $formRegister = $('#register-form');
    var $divForms = $('#div-forms');
    var $modalAnimateTime = 300;
    var $msgAnimateTime = 150;
    var $msgShowTime = 2000;
    $("form").submit(function () {
        switch(this.id) {
            case "login-form":
                var $lg_username=$('#login_username').val();
                var $lg_password=$('#login_password').val();
                if ($lg_username == "ERROR") {
                } else {
					var dataString = '&user_email='+ $lg_username +'&pass=' + $lg_password;
					$.ajax({
						type: "POST",
						url: "login/login.class.php?action=login",
						data: dataString,
						dataType:'json',
						cache: false,
						success: function( user ){
							// deal with this in the morning
							if (user.errorCode == 1){
								bootbox.alert("Please enter a Valid Username.", function() {
							
							}); window.setTimeout(function(){
									bootbox.hideAll();
								}, 3000);

								return false;
								}
								else if (user.errorCode == 2) {
									bootbox.alert("Please Check your Password.", function() {
							
							}); window.setTimeout(function(){
									bootbox.hideAll();
								}, 3000);
									return false;
									}
									else if (user.errorCode == 3) {
										bootbox.alert("Please Check your Email for your Verification Link.", function() {
							
							}); window.setTimeout(function(){
									bootbox.hideAll();
								}, 3000);
										return false;
									}else{
										for ( var obj = 0; obj < user.length; obj++ ){
										window['idusr' + idusr ] =  user[obj].id;	//"<?php echo ($_SESSION['user_id']); ?>";
										window['user_email' + user_email ] = user[obj].user_email;	//"<?php echo ($_SESSION['user_email']); ?>";
										window['usr' + usr ] = user[obj].id;	//"<?php echo ($arr_event['user_id']); ?>";
										window['rnk' + rnk ] = user[obj].user_level;	//"<?php echo ($_SESSION['user_level']); ?>";
										var user_ip = "";	//" <?php echo ( $_SERVER['REMOTE_ADDR']); ?>";   
										window['usrname' +  usrname ] = user[obj].full_name;	//"<?php echo ($_SESSION['userName']); ?>";
										}
									}
									/* initialize the external events
		-----------------------------------------------------------------*/

			$(".modal-wrap").css({display:"none"});
			$("#kioskvid")[0].pause();
                   $("#browser li").each(function() {
						var eventObject = {
							title: $(this).find("a").attr("className"),  // use the element's text as the event title
							class_duration: $(this).find("a").attr("class_duration"),
							class_time: $(this).find("a").attr("class_time"),
							video_url: $(this).find("a").attr("href"), 
							class_id: $(this).find("a").attr("class_id"),
							inst: $(this).find("a").attr("instructor"),
							level: $(this).find("a").attr("level"),
							category: $(this).find("a").attr("category"),
							instructor_link: $(this).find("a").attr("instructor_link"),
							size: $(this).find("a").attr("size"),
							img_url: $(this).find("a").attr("img_url"),
							genre: $(this).find("a").attr("genre"),
						};
					$(this).data('Object', eventObject);
							if (idusr != ""){
								if (rnk < 5){//console.log("user script1-1 rnk",rnk," ","idusr",idusr);
								// make the event draggable using jQuery UI
								
									$('.tile__play').draggable({
										zIndex: 999,
										revert: true,      // will cause the event to go back to its
										revertDuration: 0,  //  original position after the drag
										// scroll: true,
										helper:  function(event) {
										return $('<img src="'+site+'/css/images/playbuttonicon.png"/>');
											},
											cursor: "move", 
											cursorAt: {left: 10, bottom :10},
											appendTo: '#calendar'
										});
									}else{console.log("admin script1-2 rnk",rnk," ","idusr",idusr);
								
										$('.tile__play').draggable({
											zIndex: 999,
											revert: true,      // will cause the event to go back to its
											revertDuration: 0,  //  original position after the drag
											// scroll: true,
											helper:  function(event) {
											return $('<img src="'+site+'/css/images/playbuttonicon.png"/>');
											},
											cursor: "move", 
											cursorAt: {left: 10, bottom :10},
											appendTo: '#calendar'
										});
									}
								}
					});

							$.ajax({
								  url: ''+site+'/js/loginControl.js',
								  dataType: "script",
								});$('#calendar').fullCalendar('refetchEvents');
								
						$('.tile__play').draggable( 'disable' );
						 //var hover = $('.tile__play').attr('class');
							$('.tile__play').hover(function(){
								 var row = $(this).attr('value');
								$('.tile__play').draggable( 'enable' ); 
								$('.category[data-row='+row+'] li' ).addClass('row-hover-overlay');
							}, function() {
								 var row = $(this).attr('value');
								$('.category[data-row='+row+'] li').removeClass('row-hover-overlay');
							});
	 	
					},
						error:function(jqXHR, textStatus, errorThrown){
							alert("Error type    " + textStatus + " occured, with value " + errorThrown);
						}
					});
				}
                return false;
                break;
            case "lost-form":
                var $ls_email=$('#recover_email').val();
				var $ls_username=$('#recover_username').val();
                if ($ls_email == "ERROR") {
                } else {
					var dataString = '&user_email='+ $ls_email +'&user_name=' + $ls_username;
					$.ajax({
						type: "POST",
						url: "login/login.class.php?action=recover",
						data: dataString,
						dataType:'json',
						cache: false,
						success: function( user ){
							// deal with this in the morning
							if (user.errorCode == 1){
								bootbox.alert("Please enter a Valid Username.", function() {
							
							}); window.setTimeout(function(){
									bootbox.hideAll();
								}, 3000);

								return false;
								}
								else if (user.errorCode == 2) {
									bootbox.alert("Please Check your Password.", function() {
							
							}); window.setTimeout(function(){
									bootbox.hideAll();
								}, 3000);
									return false;
									}
									else if (user.errorCode == 3) {
										bootbox.alert("Please Check your Email for your Verification Link.", function() {
							
							}); window.setTimeout(function(){
									bootbox.hideAll();
								}, 3000);
										return false;
									}else{
										for ( var obj = 0; obj < user.length; obj++ ){
										window['idusr' + idusr ] =  user[obj].id;	//"<?php echo ($_SESSION['user_id']); ?>";
										window['user_email' + user_email ] = user[obj].user_email;	//"<?php echo ($_SESSION['user_email']); ?>";
										window['usr' + usr ] = user[obj].id;	//"<?php echo ($arr_event['user_id']); ?>";
										window['rnk' + rnk ] = user[obj].user_level;	//"<?php echo ($_SESSION['user_level']); ?>";
										var user_ip = "";	//" <?php echo ( $_SERVER['REMOTE_ADDR']); ?>";   
										window['usrname' +  usrname ] = user[obj].full_name;	//"<?php echo ($_SESSION['userName']); ?>";
										}
									}

	 	
					},
						error:function(jqXHR, textStatus, errorThrown){
							alert("Error type    " + textStatus + " occured, with value " + errorThrown);
						}
					});
				}
                return false;
                break;
            case "reset-password":
				var $ls_email=$('#reset_email').val();
                var $ls_password=$('#reset_password').val();
                if ($ls_password == "ERROR") {
                } else {
					var dataString = '&pass='+ $ls_password +'&user_email=' + $ls_email;
					$.ajax({
						type: "POST",
						url: "login/login.class.php?action=reset",
						data: dataString,
						dataType:'json',
						cache: false,
						success: function( user ){
							// deal with this in the morning
							$("#reset_username").val(user_name);
							if (user.errorCode == 1){
								bootbox.alert("Password has been changed.", function() {
							
							}); window.setTimeout(function(){
									bootbox.hideAll();
								}, 3000);

								return false;
								}


	 	
					},
						error:function(jqXHR, textStatus, errorThrown){
							alert("Error type    " + textStatus + " occured, with value " + errorThrown);
						}
					});
				}
                return false;
                break;
            case "register-form":
				var $rg_fullname=$('#register_name').val();
                var $rg_username=$('#register_username').val();
                var $rg_email=$('#register_email').val();
                var $rg_password=$('#register_password').val();
                if ($rg_username == "ERROR") {
                } else {
					var dataString = '&user_name='+ $rg_fullname +'&user_email='+ $rg_email + '&user_username='+ $rg_username +'&pass=' + $rg_password;
					$.ajax({
						type: "POST",
						url: "login/login.class.php?action=register",
						data: dataString,
						dataType:'json',
						cache: false,
						success: function( user ){
							// deal with this in the morning
							if (user.errorCode == 1){
								bootbox.alert("Check you EMAIL for an activation email, <br>(dont forget to check your spam folder). ", function() {
													}); window.setTimeout(function(){
															bootbox.hideAll();
														}, 4000);
								return false;
								}
								else if (user.errorCode == 2) {
									bootbox.alert("Please Check your Password.", function() {
							
							}); window.setTimeout(function(){
									bootbox.hideAll();
								}, 3000);
									return false;
									}
									else if (user.errorCode == 3) {
										bootbox.alert("Please Check your Email for your Verification Link.", function() {
							
							}); window.setTimeout(function(){
									bootbox.hideAll();
								}, 3000);
										return false;
									}else{
										for ( var obj = 0; obj < user.length; obj++ ){
										window['idusr' + idusr ] =  user[obj].id;	//"<?php echo ($_SESSION['user_id']); ?>";
										window['user_email' + user_email ] = user[obj].user_email;	//"<?php echo ($_SESSION['user_email']); ?>";
										window['usr' + usr ] = user[obj].id;	//"<?php echo ($arr_event['user_id']); ?>";
										window['rnk' + rnk ] = user[obj].user_level;	//"<?php echo ($_SESSION['user_level']); ?>";
										var user_ip = "";	//" <?php echo ( $_SERVER['REMOTE_ADDR']); ?>";   
										window['usrname' +  usrname ] = user[obj].full_name;	//"<?php echo ($_SESSION['userName']); ?>";
										}
									}

	 	
						},
						error:function(jqXHR, textStatus, errorThrown){
							alert("Error type    " + textStatus + " occured, with value " + errorThrown);
						}
					});
				}
                return false;
                break;
            default:
                return false;
        }
        return false;
    });
    $('#login_register_btn').click( function () { modalAnimate($formLogin, $formRegister) });
    $('#register_login_btn').click( function () { modalAnimate($formRegister, $formLogin); });
    $('#login_lost_btn').click( function () { modalAnimate($formLogin, $formLost); });
    $('#lost_login_btn').click( function () { modalAnimate($formLost, $formLogin); });
    $('#lost_register_btn').click( function () { modalAnimate($formLost, $formRegister); });
    $('#register_lost_btn').click( function () { modalAnimate($formRegister, $formLost); });
     function modalAnimate ($oldForm, $newForm) {
        var $oldH = $oldForm.height();
        var $newH = $newForm.height();
        $divForms.css("height",$oldH);
        $oldForm.fadeToggle($modalAnimateTime, function(){
            $divForms.animate({height: $newH}, $modalAnimateTime, function(){
                $newForm.fadeToggle($modalAnimateTime);
            });
        });
    }
     function msgFade ($msgId, $msgText) {
        $msgId.fadeOut($msgAnimateTime, function() {
            $(this).text($msgText).fadeIn($msgAnimateTime);
        });
    }
     function msgChange($divTag, $iconTag, $textTag, $divClass, $iconClass, $msgText) {
        var $msgOld = $divTag.text();
        msgFade($textTag, $msgText);
        $divTag.addClass($divClass);
        $iconTag.removeClass("glyphicon-chevron-right");
        $iconTag.addClass($iconClass + " " + $divClass);
        setTimeout(function() {
            msgFade($textTag, $msgOld);
            $divTag.removeClass($divClass);
            $iconTag.addClass("glyphicon-chevron-right");
            $iconTag.removeClass($iconClass + " " + $divClass);
  		}, $msgShowTime);
    }

	});