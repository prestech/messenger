<?php

require __Dir__."/vendor/autoload.php";

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


/**
* 
*/
class Iconnection
{
	private $conn; 
	private $connId = ""; //this will be the username

	function __construct($connId, ConnectionInterface $conn)
	{
		$this->connId = $connId;
		$this->conn = $conn;
		# code...
	}//constructor Ends 

	public function send($data){
		$this->conn->send($data);

	}

	public function close(){
		$conn->close();
	}

	public function getConn(){
		return $this->conn;
	}
	//getter
	public function getId(){
		return $this->connId;
	}//getId()

	//setter
	public function setId($id){
		$this->connId = $id;
	}
}//Iconnection Class Ends 
?>