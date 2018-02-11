<?php

namespace chatapp\model;

/**
* 
*/
class Database 
{
	/*private $serverName ="localhost";
	  private $dbUsername = "root";
	  private $dbPassword = "";
	  private $dbName = "chatAppDB";*/

	function __construct()
	{
		# code...
	}#__construct() Ends 

	 /***************************************************
   Connect to MYSQL database 
   */
	public static function connectToDb(){

		//try to connect to database, and return NULL if connection fails.
		try{
		  $conn = new \PDO( 'mysql:host=localhost;dbname=chatAppDB',"root", "" );
		  #$conn = new \PDO( 'mysql:host=localhost;dbname=chatAppDB',"root","");
		  $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		}catch(\PDOException $ex){
			echo "Database connection failed".$ex->getMessage(); 
			return NULL; 
		}//try-catch ends 

		//return database connection
		return $conn; 
	}//connectToDb() Ends 

	/************************************************
	*/
	public static function queryDb(string $query){

		//connect to database 
		$conn = self::connectToDb(); 

		if($conn != NULL ){
			//execute query statment 
			$resultSet = $conn->query($query);

			//close db
			$dbConnection = NULL;

			return $resultSet; 
		}//if ends 

		return NULL; 

	}//queryDb() Ends 

	/********************************************
	*/
	public static function updateDb( string $updateStmt ){

		//get connection the database 
		$conn = self::connectToDb();

		if($conn != NULL ){
			//execute query statment 
			$resultSet = $conn->exec($updateStmt);
	   		
	   		//close database 	
	   		$conn = NULL;

			return $resultSet; 
		}
		return NULL; 

	}//updateDb() Ends 
 

	
  }# Database Class Ends 


?>