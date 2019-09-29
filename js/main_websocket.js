		
		//import "/js/global_constants.js";

		const STATUS_REQUEST = "update_request";
		const STATUS_UPDATE = "status_update";
		const CONTACT_MESSAGE = "contact_message";
		const NETWORK_UNREACHABLE = "network_unreachable";
		const CHECK_IN_USER = "check_in_user";
		const SERVER_ADDR = 'ws://127.0.0.1:5555';

		/*
		*TODO: Authenticates users who connect to sockets. Whe the connection is first established send an automated message to the 
		Server containing the username and the sessionID of the user. Communication cannot begin untill this process has been performed. 
		* A boolean value will be set to true to signal that socket authentication has been carried; this will the allow the user to be able (we might not need to do all these since we have a protocol parameter, we will use it)
		to send and recieve message. 
		* If the first message received from by the server does not contain this information the server will send back a warning message to the sender.
		* When the the authentication is done, the userID will be set to the user's username. For this point a connection object must be associated with 
		a username. 
		* Connection object not associated with a username will be expected to send an authentication message, else they are considered hackers (passive or active hacker are all THREATS to the system)
		*/
		//get the user's username from the session 

		//TODO:get the users online status from the session 
		var onlineStatus = 1; //default to 1 

		//establish connection with server side application 
		var mWebSocket =  connectToMessenger();


		function connectToMessenger(){
			
			//$protocol = "username&socketID";
			var conn = new WebSocket(SERVER_ADDR);
			console.log("Initiating socket connection ");

			conn.onopen = function(e){
				//console.log(conn); 

				if(conn.readyState == 1){
					//IMPORTANT: If a user attempts to connect and does not send a status update message, reject it.
					 var message = { request_type:CHECK_IN_USER};
					 console.log("ws_worker.js/conn.onopen: Socket connected "+conn.readyState);

					 //let the the main thread know socket is ready
					 postMessage( JSON.stringify(message) );

				}//if Ends 

			};//function() ends 

			conn.onmessage = function(event){
				//send message to the main thread 
				console.log("main_websocket.js/onmessage: Message received from Server");
				
				postMessage(event.data); 

				//socket will be signalled to terminate

			};//onmessage ends 

			conn.onclose = function(){
				//let the main UI know about this closing 
			};//onclose() Ends 

			conn.error = function(){
				//handle error 
				console.log("Error connecting to socket");

				//Auto reconnect if error occurs 
				checkConnAndReConn();
			};//error() Ends 

			return conn; 
		}//connectToMessenger() Ends 

		


		/******************************************************************
		* Function: submitData(username)
		* Author: Presley M. 
		* Desc: This function is submits data, messages, when the user 
		*       hits send, to send message to a contact.
		* Params: 
		*		- username: the username of the user submitting the data.
							(the current logged in user)
		*/
		function submitDataToServer(message){
			
			//console.log("main_websocket.js/submitDataToServer:"+message);

			//what until connection has been established before sending the message

			console.log("main_websocket.js/submitDataToServer:"+mWebSocket.readyState);

			//TODO: In reality we will wait for just few seconds and display a message to the user. 
			//have a buffering UI on the screen untill connection is established
			//while(mWebSocket.readyState == 0 ){}//while ends 

			console.log("main_websocket.js/submitDataToServer:"+message);

			if(message != null || message.trim() != ""){
				mWebSocket.send(message);

			}//if Ends 

		}//submitData() Ends 


		/*******************************************************
		* Function: checkConnAndReConn();
		* Author: Presley M. 
		* Desc: This function checks if the websocket  connection is up, and 
		*		connects it if it is not.
		*/
		function checkConnAndReConn(){
			
			//try to reconnect
			if(mWebSocket.readyState == 3 || mWebSocket.readyState == 2 ){
				console.log("Trying to reconnect");
				//reconnect
				 mWebSocket = connectToMessenger();
				//TODO:add code to see if re-connection succeeded 
			}//if ends 

		  //upon recovery request for message status from the main thread
		  //this requested status will be sent to the server 
		  //postMessage("MESSAGE");
		}//checkConnAndReConn() Ends 
 
		/*Messages sent to the worker from the main thread 
		 *will be received here */
		onmessage = function(event){
			//all data receive will be in JSON formatted string, with aggreed components
		   	//forward all messages to server 
		     console.log("main_websocket.js/onmessage" );

		     submitDataToServer(event.data);
	
		}//function(event)
