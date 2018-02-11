<?php

	session_start();

    require $_SERVER['DOCUMENT_ROOT']."/global_functions.php";
	require $_SERVER['DOCUMENT_ROOT'].DATA_MODEL;
	use chatapp\model\Database; 

	/**
	* This function will authenticate the users when they establish a socket connection.
	*/
   	function authenticateSocket($username, $socketID){


		//prepare a query statement 
   		$queryStmt = "SELECT username, sockID FROM users WHERE username='$username' AND sockID = '$socketID'";
   			
   			//execute the query statement 
   			$queryResult = Database::queryDb($queryStmt);

			//check if the result matches the $username and socketID in the parameter
	   		if($queryResult->rowCount() == 1 ){

	   			$dbConnection = NULL;

	   			//return true if user is valid
	   			return true; 

	   		}//if Ends 

			//return false if user is not valid
	   		return false; 
	}//#authenticateSocket() Ends 

	/**
	* This function will return the message type: Authentication msg/ User msg.
	*
	*/
	function messageType($message){

		//transform the message from json to php object (with jsonToPHP() function )

		//check message type, by looking at the message_type value

		//return the message type 

		//(The caller, the chat_server, of this method will respond accordingly if message type send is valid or invalid )

	}//messageType() Ends 


?>
