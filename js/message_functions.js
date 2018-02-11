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

		   if (messageObj.type == CONTACT_MESSAGE){
				//check if the receiver is the active contact and update the 
				//message UI. Else leave a notification on the senders name. 
				if(activeContact ===  messageObj.sender){
						updateMessageView( activeContact , messageObj.content);
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