<?php
/*
 * Created on 17-okt-2011
 * author Paul Wolbers
 */


	// YOUR PATH DEFINES HERE
	define('FULLCAL_DIR','/var/www/clients/client1/web5/web/test/groupxMain/');
	define('FULLCAL_URL','http://www.LNA.groupxnow.com/groupxMain/');

 


	// don't change these
	define('CONFIG_DIR',	FULLCAL_DIR.'/configs');
	define('INCLUDE_DIR', 	FULLCAL_DIR.'/include');
	define('CLASSES_DIR', 	FULLCAL_DIR.'/model');
	define('LIB_DIR', 		FULLCAL_DIR.'/lib');
	define('EXTERNAL_DIR',	FULLCAL_DIR.'/external');
	define('EXTERNAL_URL',	FULLCAL_URL.'/external');
	define('IMAGES_URL',	FULLCAL_URL.'/images');



	/*
	 * options you can change as you wish
	 */

	// ACCESS OPTIONS
	define('LANGUAGE', 'EN');							// supported NL, EN, FR, DE, ES, PL
    define('ADMIN_USER_ID', 2);							// only used when you didn't do the insert queries from the sql file. You can put the ID of one or more users here. When you did do the query, the admin_user_id is taken from the row where user_type is ‘admin’. More than 1 ID is also possible. Example: 1,2
    define('ALLOW_ACCESS_BY', 'login');  				// def. login. ip or free or login. If ip, users and events get user_id 1000000

	define('ONLY_ADMIN_CAN_ADD_AND_EDIT', false); // only the admin can add and edit events. People who visit the calendar can only view
	define('ADMIN_HAS_FULL_CONTROL', false);	// when true the calendar options are ignored


	// ACCESS TYPE 'LOGIN' OPTIONS
	define('USERS_CAN_SEE_ITEMS_FROM_OTHERS', true);
	define('USERS_CAN_CHANGE_ITEMS_FROM_OTHERS', false);		// shared calendar: users can also change others items, if ALLOW_ACCESS_BY is free, this constant is ignored
	define('USERS_CAN_DELETE_ITEMS_FROM_OTHERS', false);
	define('USERS_CAN_REGISTER', true);
	define('SEND_ACTIVATION_MAIL', true);				// true, user is activated after click on activationlink in mail, false, user is immediately activated, password is in registerform (or email when admin registers a user with add_user form)
	define('ACTIVATION_MAIL_SUBJECT', 'Welcome %USERNAME%');
	define('RESET_PASSWORD_MAIL_SUBJECT', 'Reset your password');

	// ACCESS TYPE 'LOGIN' AND 'FREE' OPTIONS
	define('ADMIN_CAN_LOGIN_FROM_ADMIN_URL', true);	// when accesstype = free AND SHOW_SMALL_LOGIN_LINK = false the admin can login with ../admin url
	define('SHOW_SMALL_LOGIN_LINK', true);				// true means that a login link is visible above the calendar.
														// false: depends on ALLOW_ACCESS_BY what happens: When ALLOW_ACCESS_BY is ‘free’ then visitors will see the calendar, when ALLOW_ACCESS_BY is ‘login’ visitors will be redirected to the login page.


	// ACCESS TYPE 'IP' OPTIONS
	define('CAL_IP', '');								// ip of the calendaruser

	// ACCESS TYPE 'IP' AND 'FREE' OPTIONS
	define('IP_AND_FREE_ACCESS_SAVED_USER_ID', 1000000);	// def. 1000000. 1000000 or another user_id


	// MAIL
	define('FROM_EMAILADDRESS', 'your_emailaddress');	// used as from address when mails are send to users
	define('ADMIN_EMAILADDRESS', '');	// used to send copy of add_user mail

	// optional, in case you want other than the clienttimezone
    define('TIMEZONE', 'America/Denver');     // example: America/New_York


	/**
	 * CALENDAR OPTIONS
	 */

	define('SHOW_WEEKNUMBERS', true);
	define('SHOW_MONTH_VIEW_BUTTON', true);
	define('SHOW_WEEK_VIEW_BUTTON', true);
	define('SHOW_DAY_VIEW_BUTTON', true);
	define('SHOW_AGENDA_VIEW_BUTTON', true);
	define('WEEK_VIEW_TYPE', 'agendaWeek');	//basicWeek or agendaWeek
	define('DAY_VIEW_TYPE', 'agendaDay');	//basicDay or agendaDay
	define('DEFAULT_VIEW', 'month');		//month, basicWeek, agendaWeek, basicDay, agendaDay, agendaList
	define('CALENDAR_WIDTH_TYPE', 'stretch');		// fixed or stretch
	define('CALENDAR_WIDTH', 900);

	define('SHOW_TITLE_FIRST', false);	 	// true: the title will be in front of the time. 	event1 10:00-11:00
											// false: the time will be in front of the title. 	10:00-11:00 event1

	define('SHOW_NOTALLOWED_MESSAGES', false);	// what should happen when someone has no rights to add or edit an event after a click on a day or an event?
												// true, there will be a message.
												// false, no message



	/**
	 * WORDCLOUD
	 */
	global $EXCLUDED_WORDS_WORDCLOUD;		// words that won't show up in the wordcloud
	$EXCLUDED_WORDS_WORDCLOUD = array(' the ',' is ', '!','.',',',':',"'","\n","\r","\t",'_','>>','<<','&laquo;','&raquo;','&nbsp;','(',')','"','[',']','&#;');

	define('ONLY_SHOW_CALENDAR', true);		// if true, calendar is visible and logout link when ALLOW_ACCESS_BY is login

	/*
	 * if ONLY_SHOW_CALENDAR is false you can define which left blocks you want to be visible
	 * if you only want to show the calendar and the logout button above the calendar, set these 3 to false
	 */
	define('SHOW_SMALL_CLOUD', true);		// to show the cloud set ONLY_SHOW_CALENDAR to false
	define('SHOW_SMALL_CLOUD_COUNTS', false);
	define('SHOW_LINK_TO_CLOUD', true);
	define('SHOW_SEARCH_BOX', false);


	define('DRAG_DROP_EVENTS', 'event1,event2');
	define('SHOW_DRAG_AND_DROP_EVENTS', true);		// to show the drag and drop items set ONLY_SHOW_CALENDAR to false
	define('ONLY_ADMIN_CAN_SEE_DRAG_DROP_ITEMS', false);

	/**
	 * EDIT DIALOG
	 */

	define('SHOW_COLOR_SELECTION', true);
	define('SHOW_DESCRIPTION_FIELD', true);
	define('SHOW_LOCATION_FIELD', true);
	define('SHOW_PHONE_FIELD', true);

	define('DATEPICKER_DATEFORMAT', 'dd/mm/yy');	// dd/mm/yy or 'mm/dd/yy'
	define('SHORT_DATEFORMAT', 'dd/mm/yyyy');	// dd-mm-yyyy or 'mm/dd/yyyy'
	define('TWELVE_TWENTYFOUR_HOURS', 12);	// 12 or 24
	define('DIALOGS_RESIZABLE', true);
	define('DEFAULT_TIME_FOR_EVENT', '09:00');	// leave empty ('') if you want the current time to be set when adding an event


	// TIMEPICKER
	define('MINHOUR', 5);						// the earliest hour that will be showed in the timepicker
	define('MAXHOUR', 23);						// the latest hour that will be showed in the timepicker
	define('MINUTE_INTERVAL', 15);
	define('SIMPLE_TIME_SELECT', false);		// if true -> smarty time_select combos else jQuery timepicker (http://fgelinas.com/code/timepicker/)


	/**
	 * ADD USER FORM and profile form
	 */
	 define('SHOW_USERNAME_IN_FORM', true);
	 define('SHOW_INFIX_IN_FORM', false);
	 define('SHOW_CHECKBOX_COPY_TO_ADMIN', true);

	/**
	 * ADD USER THROUGH BROWSER URL
	 */
	 define('SHOW_CREATED_PASSWORD_WHEN_ADMIN_REGISTER_VIA_BROWSER_URL', false);	// password will be shown so the admin knows it


	/**
	 * ADD USER THROUGH ADD USER DIALOG (ONLY ADMIN CAN DO THIS)
	 */
	 define('SHOW_CREATED_PASSWORD_WHEN_ADMIN_ADDS_USER', false);	// password will be shown so the admin knows it

	/**
	 * CALENDAR ITEMS
	 */

	define('DEFAULT_COLOR', '#3366cc');
	define('SHOW_MOUSEOVER_DELETE_BUTTON', false);
	define('TRUNCATE_TITLE', true);
	define('TRUNCATE_LENGTH', 20);

	define('SCHEDULE_SQLDUMP', 86400);	// for example: 3600 = 1 hour, 86400 = 1 day, 604800 = 1 week

?>