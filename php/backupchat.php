<?php
namespace MyApp;

require dirname(__DIR__) . '/php/vendor/autoload.php';
require "global_functions.php";

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/*
TODO::
retrieve username and check, via a background worker thread, 
if the user is already logged in.The user's name will be attached to the message sent. 

Message sent will contain the username and a unique sockedID  provided to the users. 
This socketID will be changed 

Update other users when status of a contact changes*/

/*
*TODO:  Authenticate users who connect to sockets. Whe the connection is first established send an automated message to the 
        Server containing the username and the sessionID of the user. Communication cannot begin untill this process has been performed. 
        * A boolean value will be set to true to signal that socket authentication has been carried; this will the allow the user to be able 
        to send and recieve message. 
        * If the first message received from by the server does not contain this information the server will send back a warning message to the sender.
        * When the the authentication is done, the userID will be set to the user's username. For this point a connection object must be associated with 
        a username. 
        * Connection object not associated with a username will be expected to send an authentication message, else they are considered hackers (passive or active hacker are all THREATS to the system)
        */

class Chat implements MessageComponentInterface {
    protected $clients;


    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {

        // Store the new connection to send messages to later
        echo "New connection ".$conn->resourceId." \n";
        //$this->clients->attach( $conn );

    }//onOpen() Ends 

    public function onMessage(ConnectionInterface $from, $msg) {

        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n",$from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        //change message data from JSON to PHP object 
        $msgObject = json_decode($msg);

        $content = trim($msgObject->content);
        $type = trim($msgObject->type);

        /*echo $type;
        echo "\n";
        echo $bool;
        echo "\n";
        echo $content;
        echo "\n*****************************";*/
        if(strcmp( $content, "okokibo") == 0){

            $from->connectionID = $msgObject->sender; //add connectionID 
            $from->onlineStatus = $msgObject->status; //add online status 
            print_r($from->onlineStatus);

            //register client by saving the socket connection
            $this->clients->attach($from);

            //update user's status 
            $this->requestAndUpdateStatus($from, $msgObject);

        }//if Ends 

        elseif( strcmp( $type, STATUS_UPDATE)== 0) {

              //update user's online status and inform the user's contact
              //about this status change 

        	  $from->onlineStatus = $msgObject->status; //add online status 

        	  //update the user's contacts about the change in the user's status. 
        	  
        }//if Ends 

        elseif ( strcmp( $type, STATUS_REQUEST) == 0 ) {            
            
            //update contact with user's (sender's) status and alse retreive contacts' online status.
            requestAndUpdateStatus($from, $msgObject);
           
        }elseif(strcmp($type, CONTACT_MESSAGE)) {
           
           //write incoming message to the database 
            forwardMsgToReceiver($msgObject);

        }//if-else Ends 

    }//onMessage() 


    /*******************************************************************
    * Function: onClose()
    * 
    */
    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }//onClose() Ends 


    /********************************************************************
    * Function: onError()
    * 
    */
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }//onError() Ends 

    /*******************************************************************
    *Function: updateUserStatus()
    */
    public function updateUserStatus(){

    }//updateUserStatus() Ends 

    /*******************************************************************
    * Function: forwardMsgToReceiver()
    */
    public function forwardMsgToReceiver($msgObject){

        //check if user is online
        $msgeReceiver = trim($msgObject->receiver);
        $sockId = trim($client->connectionID);

        //send message to reviever's thread 
        foreach ($this->clients as $client) {

            if (strcmp($sockId, $msgeReceiver ) == 0) {
                // The sender is not the receiver, send to each client connected
                    echo "\n";
                    echo "Sending msg to : ".$client->connectionID;

                $client->send($msg);

                return;
            }//if Ends 

       }//foreach Ends 

    }//forwardMsgToReceiver() Ends 

    /*********************************************************************
    *Function: requestAndUpdateStatus() 
    * 
    */
    public function requestAndUpdateStatus(ConnectionInterface $from, $msgObject){

        echo "\nhonoring online status request and update...";


        //send automated messages to sender 
        //$autoMsge = array();
        $msgObject->type = STATUS_UPDATE;

        $msgeReceiver = trim($msgObject->receiver);

        $autoMsge->type = STATUS_UPDATE;
        $autoMsge->sender = $msgeReceiver;

        $clientStatusFound = false;


        echo "string: ".$msgeReceiver;
        //check if targeted user is online
        foreach ($this->clients as $client) {

            $sockId = trim($client->connectionID);
                
            if (strcmp($sockId, $msgeReceiver ) == 0) {
                // The sender is not the receiver, send to each client connected
                echo "\n";
                echo "Sending ".$msgeReceiver." status to : ".$from->connectionID;
                $clientStatusFound = true;

                $autoMsge->status = $client->onlineStatus;

                echo "\n \n".$msgeReceiver." Onlie Status ".$client->onlineStatus;
                $autoMsge->content = "status found";

                //send message to both parties about their online status 
                $jsonMsgBack = json_encode($autoMsge);
                $from->send($jsonMsgBack);

                $msgObject->status = $from->onlineStatus;
                $msgObject->content = "status found";
                $jsonMsgFwd = json_encode($msgObject);

                $client->send($jsonMsgFwd);

                //return;
            }//if Ends 

        }//foreach Ends 

        if($clientStatusFound == false){

            $autoMsge->status = 0;
            $autoMsge->content = $msgeReceiver." is offline";
            $jsonMsgBack = json_encode($autoMsge);

            $from->send($jsonMsgBack);
        }// if($clientStatusFound == true) Ends  

    }//requestAndUpdateStatus() Ends 

}//chat class ends 


use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Session\SessionProvider;
use chatapp;
use Symfony\Component\HttpFoundation\Session\Storage\Handler; 

    $memcached = new Handler\PdoSessionHandler ;

    $server = IoServer::factory(


        new HttpServer(
                new WsServer(
                    new Chat()
                )
        ),

        5555
    );

    $server->run();
?>