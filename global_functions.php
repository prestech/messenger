<?php
 namespace chatapp\services;
	
	define("LOGIN_PAGE" , "index.php");
	define("SIGNUP_PAGE" , "/html/signup.php");
	define("ACCOUNT_SERVICE" , "php/service/account_setup.php");
	define("HOME_PAGE" , "html/messenger.php");
	define("DATA_MODEL" , "php/model/database.php");
	define("USER_FOLDER" , "user/");
	define("ONLINE_IMG" , "images/online.jpg");
	define("OFFLINE_IMG" , "images/offline.png");
	define("BUSY_IMG" , "images/busy.jpg");
	define("IDLE_IMG" , "images/idle.jpg");
	
	function echoRedirect($file_path){
		echo "<meta http-equiv='refresh' content='0; url=http://192.168.1.158//$file_path' />";
	}//echRedirect() Ends 


?>