

		/**********************************************************
		* Function: sendStatusMsg()
		*msgType: can either be 'status-update' or 'status-request'
		*/ 
		function sendStatusMsg(webSocket, msgType){

		    var contacts = document.getElementsByClassName("contact_name");

			for (var i = 0; i < contacts.length ;i++) {
				
 				//console.log("sending status request to "+contacts[i].innerText );

				//TODO: do not wait for too long 
				//while(webSocket.readyState == 0){};
				//build message object
				var statusMsg = { type:msgType, sender:'<?php echo $_SESSION["username"]?>', receiver: contacts[i].innerText , content:"are you there?"}; 

				//Transform it to JSON format
				var statusMsg = JSON.stringify(statusMsg);

				//TODO:Make sure to handly incidents in which connection fails
				//send it to the server side application 
				webSocket.send(statusMsg);
			}
		}//sendStatusMsg() Ends 

