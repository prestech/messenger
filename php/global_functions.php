<?php
 namespace chatapp;
	
	define("LOCAL_ROOT" , "php/service/");
	define("HOME_PAGE" , "php/html/messenger.php");
	
	define("STATUS_REQUEST", "update_request");
	define("STATUS_UPDATE", "status_update");
	define("CONTACT_MESSAGE", "contact_message");
	
	define("CHECK_IN_USER", "check_in_user");
	define("CHECK_IN_COMPLETE", "check_is_complete");
	define("NETWORK_UNREACHABLE","network_unreachable");
	define("ADD_CONTACT_REQUEST","add_contact_request");
	define("JOIN_GROUP_REQUEST","join_group_request");
	define("REQUEST_NOTIFICATION","notification_request");

	
	function echoRedirect($file_path){
		echo "<meta http-equiv='refresh' content='0; url=http://localhost/$file_path' />";
	}//echRedirect() Ends 


?>