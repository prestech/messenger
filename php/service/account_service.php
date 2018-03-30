<?php
 	namespace chatapp\services;

	session_start();

    require $_SERVER['DOCUMENT_ROOT'].'php/vendor/autoload.php';
    require $_SERVER['DOCUMENT_ROOT']."/global_functions.php";
	require $_SERVER['DOCUMENT_ROOT'].DATA_MODEL;

	use chatapp\model\Database; 

	//check if there is an incomming query string 
	$requestType = $_POST["request_type"];

	//Service request
	define("SEARCH_USERS","search_users");
	define("REQUEST_CONTACTS_LIST","request_contacts_list");
	define("LOAD_MESSAGES","load_messages");
	define("REQUEST_MESSAGE", "request_messages");
	define("PERSIST_MESSAGE","store_message");
	define("ADD_CONTACT_REQUEST","add_contact_request");
	define("JOIN_GROUP_REQUEST","join_group_request");

	//data types that will be persist
	define("NEW_CONTACT_DATA","new_contact");
	define('REQUEST_NOTIFICATION', "request_notification");
	define('ADD_CONTACT_NOTIFICATION', 'add_contact_notification');
	

	//define("JOIN_GROUP_REQUEST","join_group_request");



	//echo "string";
	//echo $searchString;
	
  function searchContacts($searchString, $acctUsername){

	
	   $contactQueryStmt = "SELECT * FROM users WHERE  (users.username IN (SELECT contacts.username FROM contacts WHERE contacts.username = '$acctUsername' OR contacts.contact = '$acctUsername') OR users.username IN (SELECT contacts.contact FROM contacts WHERE contacts.username = '$acctUsername' OR contacts.contact = '$acctUsername')) AND (users.username LIKE'$searchString%' OR users.lastName LIKE '$searchString%' OR users.firstName LIKE '$searchString%')";

	   $nonContactQueryStmt = "SELECT * FROM users WHERE  (users.username NOT IN (SELECT contacts.username FROM contacts WHERE contacts.username = '$acctUsername' OR contacts.contact = '$acctUsername') AND users.username NOT IN (SELECT contacts.contact FROM contacts WHERE contacts.username = '$acctUsername' OR contacts.contact = '$acctUsername')) AND (users.username LIKE'$searchString%' OR users.lastName LIKE '$searchString%' OR users.firstName LIKE '$searchString%')";

		$contactResultSet = Database::queryDb($contactQueryStmt);
		$nonContactResultSet = Database::queryDb($nonContactQueryStmt);

		//change query into JSON format
		if($contactResultSet != NULL){
			$contacts = array();
			$index = 0;
			$queryResult=" ";
			foreach ($contactResultSet as $rows) {

				$contacts[$index] = $rows;

				$index = $index + 1;
			 //echo $rows['firstName'];

			 $queryResult.='<!--Div to hold contact information -->  
              <div class="" style="display: flex; align-content: center; margin-top:2%">
                <img  class="user_img rounded float-left" src="http://santetotal.com/wp-content/uploads/2014/05/default-user-36x36.png" style="height: 4em; align-self: center;">
                
                <h3 style="align-self: center;" class="first_last_name"><span class="username">'.$rows['username'].'</span> <br>'.$rows['firstName'].' '.$rows['lastName'].'</h3>

                <button id="'.$rows['username'].'_remove" class="contact_request_btn btn btn-sm btn-info glyphicon glyphicon-ok" style="position:relative; margin-left: 20em; align-self: center; background: green; height:3%"></button>
             </div>';

			}//foreach Ends 
		}  


		//change query into JSON format
		if($nonContactResultSet != NULL){
			$contacts = array();
			$index = 0;
			foreach ($nonContactResultSet as $rows) {
				$contacts[$index] = $rows;

				$index = $index + 1;

				$queryResult.='<!--Div to hold contact information -->  
		           <div class="" style="display: flex; align-content: center; margin-top:2%">
		           <img  class="user_img rounded float-left" src="http://santetotal.com/wp-content/uploads/2014/05/default-user-36x36.png" style="height: 4em; align-self: center;">
		                
		           <h3 style="align-self: center;" class="first_last_name"><span class="username">'.$rows['username'].'</span> <br>'.$rows['firstName'].' '.$rows['lastName'].'</h3>

		           <button id="'.$rows['username'].'_req" class="contact_request_btn btn btn-sm btn-info glyphicon glyphicon-plus" style="position:relative; margin-left: 20em; align-self: center; background: green; height:3%"></button>
		           </div>';

				}//foreach Ends 

		}  

		exit($queryResult);

	}//searchContacts() Ends 

	
	/**
	* This script organizes user account information, after login, and 
	* send them to the client's UI for display. The information collected 
	* during this setup includes:-
	* -> General user info: name, username, and email; this will be displayed in account settings 

	* -> Users contacts: will be display in the "contact list" UI of the massenger.*/
	function getUserContacts($username){

		    
		$queryStmt = "SELECT DISTINCT  * FROM #DISTINCT makes sure the rows return are unique; no duplicates 
			( SELECT users.username, firstName, lastName, email  AS left_contacts FROM contacts LEFT JOIN users ON (contacts.contact = users.username) WHERE contacts.username = '$username' 
		    UNION ALL 
		    SELECT users.username, firstName, lastName, email  AS right_contacts  FROM contacts LEFT JOIN users ON (contacts.username = users.username) WHERE contacts.contact = '$username') AS all_contacts";
		

		$resultSet = Database::queryDb($queryStmt);

		//echo $resultSet;
		//change query into JSON format
		if($resultSet != NULL){
			$contacts = array();
			$index = 0;

			foreach ($resultSet as $rows) {

				$contacts[$index] = $rows;

				$index = $index + 1;

			}//foreach Ends 

			$jsonContacts = json_encode($contacts);

	        /*$userFolder = USER_FOLDER.$_SESSION['username']."/";

	        $_SESSION['user_folder'] = $userFolder;

			//Check if user's folder exist and create it if it does not.
			if( file_exists($userFolder) != 1  ){

				echo "Created a user folder ".$userFolder;
				//create directory
				mkdir($userFolder);

			}//if Ends */

			exit($jsonContacts);

		}//if Ends 
		
		return false; //failed 
	}//getUserContacts() Ends 


 /*************************************************************************
 *
 */
 function addContact($contactName, $myUsername){
 	//SLQ statement to add contact 
 	$insertStmt = "INSERT INTO contacts (username, contact) VALUES
 	 ('$contactName', '$myUsername')";
 	 
 	 $insertStatus = Database::queryDb($insertStmt); 

 	 if ($insertStatus == true) {
 	 	# code...
 	 	$deleteStmt = "DELETE FROM notification WHERE sender = '$contactName' AND receiver = '$myUsername'"; 

 	 	$deleteStatus = Database::updateDb($deleteStmt);
 	 	exit($deleteStatus);

 	 	if($deleteStatus == true){
 	 		//good
 	 	}else{
 	 		//bad: TODO-log this and try again later
 	 	}//else if end 

 	 }else{
 	 	//this might fail because the two parties are already connected. 
 		//so make sure to remove request notification from database at first write. 
 	 }//if else Ends 

 }//addContact() Ends 


 function saveUserNotification($dataObject){
  		//this notification might target a group of users or just a single user. So it is necessary to have a boolean that indicates so. 
		$insertStatement = "INSERT INTO notification (noticeType, description, sender, receiver) VALUES ( '$dataObject[request_type]', '$dataObject[content]', '$dataObject[sender]', '$dataObject[receiver]')";

	    if(Database::updateDb($insertStatement) == true){
	    	//success 
	    }//if Ends 
}//saveUserNotification() Ends


function retreiveNotifications($notificationType = ""){
	  	//this notification might target a group of users or just a single user. So it is necessary to have a boolean that indicates so. 
		$queryStmt = "SELECT * FROM notification WHERE receiver = '$_SESSION[username]'";
		$resultSet = Database::queryDb($queryStmt);


		if($resultSet != NULL){
			$resultInJson = [];
			$index = 0;
			foreach ($resultSet as $row) {
				# code...
				$resultInJson[$index] = $row;
				$index = $index+1;
			}//foreach ends 
			exit(json_encode($resultInJson));
		}//If Ends 

}//retreiveNotifications

function persistMessage($dataObject){
		
  		//this notification might target a group of users or just a single user. So it is necessary to have a boolean that indicates so. 
  	

}//persistMessage() Ends 


function loadUserMessage($dataObject){
		
  		//this notification might target a group of users or just a single user. So it is necessary to have a boolean that indicates so. 
  	    if($dataObject["isGroup"] == true){

  	    }else{
			$insertStatement = "INSERT INTO notification ('noticeType', 'description', 'notifiedDate', 'receiver') VALUES () ";
	
  	    }//if Else ends 


}//loadUserMessage() Ends 
 

switch ($requestType) {
	case SEARCH_USERS:
		//retreive the search string 
		$searchString = $_POST['search_string'];
		searchContacts($searchString, $_SESSION["username"]);
		break;

	case REQUEST_CONTACTS_LIST:
		 getUserContacts($_SESSION['username']);
		break; 

	case LOAD_MESSAGES:
		break;

	case REQUEST_NOTIFICATION:
		retreiveNotifications("s");
		//exit($_POST);
		break;

	case ADD_CONTACT_REQUEST:
		addContact($_POST['sender'], $_SESSION['username']);
		break;
	case ADD_CONTACT_NOTIFICATION:
		saveUserNotification($_POST);
	    //print_r("ADD_CONTACT_NOTIFICATION:".ADD_CONTACT_NOTIFICATION);
		# code...
		break;
	case JOIN_GROUP_REQUEST:
		//loadGroupNotification($_POST);
		echo("Winner");
		break;

	case PERSIST_MESSAGE:

		echo("Winner");
		break;

	case REQUEST_MESSAGE:
		echo("Winner");
		break;
}//switch Case Ends 
	

?>
