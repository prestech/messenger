/*const STATUS_REQUEST = "update_request";
const STATUS_UPDATE = "status_update";
const CONTACT_MESSAGE = "contact_message";
const NETWORK_UNREACHABLE = "network_unreachable";
const CHECK_IN_USER = "check_in_user";



const  STATUS_ONLINE = 1;
const  STATUS_OFFLINE = 0;
const  STATUS_IDLE = 2;
const  STATUS_BUSY = 3;*/
//import "http://192.168.1.158/js/message_functions.js";
var webWorkerUrl = "http://"+IP+"/js/main_websocket.js"; 
var jsonContactResource = "http://"+IP+"/php/service/user/"; 
var allContacts = null;

function startWebSocketWorker(username){

	//This Script implements a dedicated web worker responsible for managing the web socket. 
	var socketWebWorker = null;

	//Check if browser supports web Worker  
	if(typeof(Worker) !=="undefine"){

		console.log("Creating a dedicated socket worker");

		//This web worker will start as soon as the contacts 
		//are loaded into the main UI.  Since the contacts'
		//online status need to be updated. 
		socketWebWorker = new Worker(webWorkerUrl);

		//attach a message event listener to the worker
		socketWebWorker.onmessage = function(event){

			//all messages will be in JSON format and will
			//have a message type 
			console.log("ws_worker/socketWebWorker.onmessage: "+event.data);

			//handle the incoming messages 
			handleWsWorkerMessage(event.data, username);

		}//function(event) ENDS 
	    
    	return socketWebWorker;
	}else{
		return null;
	}//if-else Ends 
			
}//startWebSocketWorker() Ends

function checkIn(worker){

	//send check-in message 
	var msgTxt = "okokibo";
			
	console.log("ws_worker.js/checkIn: Checking in with server");

	var checkInMsg = generateTxtMessage(CHECK_IN_USER, msgTxt, username,"", status=STATUS_ONLINE);
	console.log("ws_worker.js/checkIn():"+checkInMsg);
	worker.postMessage(checkInMsg);

}//checkIn() Ends 

/*************************************************************
* Function: handleWsWorkerMessage()
* 
*/
function handleWsWorkerMessage(eventData, username){

   var wMessage = JSON.parse(eventData);
   console.log("ws_worker/handleWsWorkerMessage(): "+wMessage.request_type);
				   
	//check the message type and react accordingly
	switch(wMessage.request_type){

		//case this is an incomming message from a contact
		case CONTACT_MESSAGE:

			console.log("ws_worker.js/handleWsWorkerMessage()-CONTACT_MESSAGE: you have a new message");

			//retreive the message content and call the "receive()" fucntion on the message  
			receiveMessage(wMessage);
			break;

		//case this  is a (\an online) status update message  
		case STATUS_UPDATE:
			//update the onine status of the target user(s)
			console.log("ws_worker.js/handleWsWorkerMessage()-STATUS_UPDATE: Contact's status  have been update back");
			updateContactOnlinStatusChange(wMessage.sender, wMessage.status);
			break;

		//STATUS_REQUEST will only be sent and not received
		case CHECK_IN_USER:

			console.log("ws_worker.js/handleWsWorkerMessage()-CHECK_IN_USER: Request to check-in");

			//send check-in message 
			/*call the loadContact function, which returns a promise, from 'contact_function.js'
			 to load  user's contacts. Then call 
			 addContactsToView to add the contacts to the the view. Update the users' online status
			 afterwards. Next update the online status of the contact */
			requestMsg ={request_type:REQUEST_CONTACTS_LIST};

			makeAjaxRequest("POST", ACCOUNT_SERVICE_URL, requestMsg).
			done(  function(response){
				addContactsToView(response);
			}).
			
			then( function(contacts){
				    allContacts = contacts;
					checkIn(socketWebWorker);
				});//function(){} Ends

			break; 

		case CHECK_IN_COMPLETE:
			
			console.log("ws_worker.js/handleWsWorkerMessage()-CHECK_IN_COMPLETE: "+allContacts);
			var statusReqMsg = generateTxtMessage(STATUS_REQUEST, "are you online?", username,"", status=STATUS_ONLINE);
			updateOnlineStatus(socketWebWorker, allContacts, statusReqMsg);
			break;
			//Case the websocket cannot reach the server
		case NETWORK_UNREACHABLE:

			break;

	    case ADD_CONTACT_REQUEST:

	    	//show as notification in the "contact" list heading 
	    	console.log("contact request received");
	    	//console.log($("#contact_notice_bell"));
	    	
	    	contact_notice_count = contact_notice_count+1;

	    	$("#contact_notice_bell")[0].textContent = ""+contact_notice_count;
	     	break; 

	    case ADD_NEW_CONTACT_TO_VIEW:
	    
	    	 console.log("ADD_NEW_CONTACT_TO_VIEW");
	         console.log(wMessage);
	         //add contact to view 
	         addSingleContactToView(wMessage.sender);

	         //check newly added contact online status
			 var statusReqMsg = generateTxtMessage(STATUS_REQUEST, "are you online?", username,"", status=STATUS_ONLINE);
	         updateOnlineStatusForSingleContact(socketWebWorker, wMessage.sender, statusReqMsg);

	         break; 
	}//swich() Ends 

	//console.log(event.data);

}//function handleWsWorkerMessage(eventData)() Ends 
