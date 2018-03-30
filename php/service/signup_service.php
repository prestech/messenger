<?php


 namespace chatapp\services;

 session_start();


 use chatapp\model;

 require $_SERVER['DOCUMENT_ROOT'].'/php/model/database.php';
 require $_SERVER['DOCUMENT_ROOT']."/global_functions.php";

 //makes sure that this script is opened only when a registration request has been submitted
 if($_POST["submit"] == false ) return; 

 	/*******************************************************
 	*validateUsername() 
	* This function checks whether the username already exist  
	*/
   function validateUsername($username){

		//connect to database 
		$conn = model\Database::connectToDb();   

		//TODO: Write a client side script with javascript and a
		// server side script to validate the format of the input 
		// This is to prevent SQL injection attacks 
		
		$querryStmt = "SELECT * FROM users WHERE username = '$username'"; 

		//check if connection is oppen
		if($conn != NULL) {
			$result = $conn->query( $querryStmt );

			//$result = $conn->setFetchMode(\PDO::FETCH_ASSOC);
			
			//check size of the return, if it is greater than 0 (eq 1) then
			// the username is not valid
			if($result->rowCount() > 0){
				//exit("This username '".$username."' has been taken");
			}//if Ends 


			//var_dump($result);
		}//if Ends 

	}//validateUsername() Ends 


   /*********************************************************
   *validatePassword() 
   *This function checks if the password meets the constraints 
   */
   function validatePassword($password, $re_password){

	   	//check if passwords match 
	   	if(strcmp($password, $re_password) != 0){
	   		$_SESSION["feedback_msg"] = "Password entered in password fields do not Match";
	   		echoRedirect( SIGNUP_PAGE );
	   	   //exit("Password entered in password fields do not Match");
	   	}//if Ends 

		//make sure password is atleast six characters long 
	   	if(strlen($password) < 6 ){
	   	    //exit("Password MUST be atleast SIX characters long");
	   	}//if Ends 

   }//validatePassword() Ends 
 	
 
   /**************************************************************************
   *registerUser()
   *This function performs user validation on user and register the information 
   * to the database
   */
   function registerUser($POST){
   		 //get username and password from $_POST 
		 $username = $POST["username"];
		 $password = $POST["password"];
		 $re_password = $POST["re_password"];
		 $email = $POST["email"];
		 $lastName = $POST["last_name"];
		 $firstName = $POST["first_name"];

		//validate password
		validateUsername($username);

		//validate username
		validatePassword($password, $re_password); 

		//connect to database 
		$conn = model\Database::connectToDb(); 

		//TODO:Catch exceptional case were the QUERY fails 
		//$querryStmt = "SELECT username, password FROM users;";  

		//TODO: Write a client side script with javascript and a
		// server side script to validate the format of the input 
		// This is to prevent SQL injection attacks 
		
		$querryStmt = "INSERT INTO users (username, password, email, firstName, lastName) VALUES ('$username', '$password', '$email' ,'$firstName','$lastName')" ; 

		//check if connection is oppen
		if($conn != NULL) {
			$result = $conn->exec( $querryStmt );
			return true;
		}//if Ends 

		return false; 
	}//registerUser() Ends 


 	//validate user 
	$isValid = registerUser($_POST);

	//if user is valid store username in the session and redirect user 
	// to the account_setup.php script 
	if($isValid == true){
		//redirect to script responsible for setting up account environment
		$_SESSION["feedback_msg"] = "";
		echoRedirect( LOGIN_PAGE );
	}//if Ends 

//echoRedirect( SIGNUP_PAGE );
?>