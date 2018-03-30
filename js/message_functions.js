 /*****************************************************************
		This script contains functions for processing incoming messages and outgoing messages*/

		//TODO: This should be a data structure that holds unread message notification count for every contact. 
		var msgNoteCout = 0;
		
		/****************************************************************
		* receiveMessage()
		* This function receives and processes incoming messages 
		*/
		function receiveMessage(messageObj){

			//change the message to js object
			//var messageObj = JSON.parse(message);

			var sender = messageObj.sender;

		   if (messageObj.request_type == CONTACT_MESSAGE){
				//check if the receiver is the active contact and update the 
				//message UI. Else leave a notification on the senders name. 
				if(activeContact ===  messageObj.sender){
					updateMessageView( activeContact , messageObj.content,false);

					//sent message to the database 
					persistMsg(messageObj);

				}//if Ends 
				else{
					notifyUserOnIncomingMsges(messageObj);
				}//if else Ends 
			}//else if() ends s

		}//receiveMessage



		/*************************************************************
		*Function: notifyUserOnIncomingMsges()
		*This function handles incoming message nofications 
		*/
		function notifyUserOnIncomingMsges(messageObj){

				//TODO: msgNoteCout should be a JSON (associative array) 
				//representing each contact. (only users with message notifications will be stored)

				//increment the message notification count 
				msgNoteCout++; 

				console.log(msgNoteCout+" incoming msg from "+ messageObj.sender);
				
				//TODO: Store the messages in a client side data structure (and write it to the server, along with a message notification, if client signs out without reading the message)

				//add the notification count on the account field
				document.getElementById(messageObj.sender+"_msg_notice").textContent = msgNoteCout+"";

		}//notifyUserOnIncomingMsges() Ends 



		/*********************************************************
		*Function: persistMsg()
		*Author : Presley M.
		*This function sends data to the database for persitent storage;
		*/
		function persistMsg(request_type , messageObj){

			messageObj.request_type = request_type;
			
			makeAjaxRequest("POST",
				"http://"+IP+"/php/service/account_service.php",
				messageObj).done( function(respond){
					console.log(request_type+" "+response);
				});
		}//persistMsg() Ends