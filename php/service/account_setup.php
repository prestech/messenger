<?php

 	namespace chatapp\services;

	session_start();

    // post.php ???
    // This all was here before  ;)
    require $_SERVER['DOCUMENT_ROOT'].'/php/vendor/autoload.php';
 	require $_SERVER['DOCUMENT_ROOT']."/global_functions.php";
    require $_SERVER['DOCUMENT_ROOT']."/".DATA_MODEL;

    use chatapp\model\Database;



	

	/* -> Online status of the contacts. 
	* -> Potential contacts: other users who are not contacts with the users (first 10 rows).
	*/	
	function getPotentialFriendFor($username){

		//prepare query
		$queryStmt = "SELECT * FROM users WHERE username != '$username'";

		$resultSet = Database::queryDb($queryStmt);


		//change query into JSON format
		if($resultSet != NULL){
			$resultToJson = json_encode($resultSet);

			//write to a file and send it via AJAX upon request 
			$potential_contacts = fopen("potential_contacts.txt", "w");

			fwrite($potential_contacts, $resultToJson);

			//close file 
			fclose($potential_contacts);

			return true; //operation succeeded 

		}//if Ends 

		return false; //failed 

	}//getPotentialFriendFor() Ends 


	//redirect to the home page
	echoRedirect( HOME_PAGE );

?>