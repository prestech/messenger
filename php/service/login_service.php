<?php


 namespace chatapp\services;

 session_start();


 use chatapp\model;

 require $_SERVER['DOCUMENT_ROOT'].'/php/model/database.php';
 require $_SERVER['DOCUMENT_ROOT']."/global_functions.php";
 
 //get username and password from $_POST 
 $username = $_POST["username"];
 $password = $_POST["password"];

 #use chatapp\model\database; 
 	/**
	* Authenticate client information 
	*/
   function validateClientUser($username, $password){

		//connect to database 
		$conn = model\Database::connectToDb();   

		//TODO: Write a client side script with javascript and a
		// server side script to validate the format of the input 
		// This is to prevent SQL injection attacks 
		
		$querryStmt = "SELECT * FROM users WHERE username='$username' AND password = '$password'"; 

		//check if connection is oppen
		if($conn != NULL) {
			$result = $conn->query( $querryStmt );

			//$result = $conn->setFetchMode(\PDO::FETCH_ASSOC);

			//check size of the return, if it is 1 then 
			//the authentication is valide, else authentication 
			//is not valid. 
			if($result->rowCount() == 1){

				foreach ($result as $row) {
					
					$_SESSION["username"] =  $row['username']; 
					$_SESSION["userID"] =  $row['userID']; 
					$_SESSION["lastName"] =  $row['lastName']; 
					$_SESSION["firstName"] =  $row['firstName']; 

				}//foreach Ends 

				$conn = NULL;
			  //TODO retrieve the login information and check
			  //if the username and password do match
			 return true;

			}//if Ends 

			//var_dump($result);
		}//if Ends 


		return false; 
	}//validateClientUser() Ends 



 	//validate user 
	$isValid = validateClientUser($username, $password);

	//if user is valid store username in the session and redirect user 
	// to the account_setup.php script 
	if($isValid == true){
		//redirect to script responsible for setting up account environment
		echoRedirect( ACCOUNT_SERVICE );

	}//if Ends 

?>