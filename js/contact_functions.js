		
		$.getScript("https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js",
					 function(){});	

		//this variable holds the name of the contact user is currently messaging.
		var activeContact = ""; 
		var allContacts = "";

		/*************************************************************
		* Function: openMessageFrame()
		* Authors: Presley Muwan
		* Users: html/messenger.php(HTML UI) 
		* This functions opens and sets up the user-contact message view 
		*/
		function openMessageFrame(contactElement){

			//get a reference to the view object
			var msgView = document.getElementById("message_view");

			if(activeContact.trim() !== ""){
				
				//Change the color of the current active contact to black
				var currentActiveContact = document.getElementById (activeContact);
				currentActiveContact.style.color = "black";
				console.log("Switch from "+  activeContact );
			}//if Ends 

			//remove current user's  message content and replace them with that of the new user.
			activeContact =  contactElement.textContent; 

			msgView.textContent = "";

			contactElement.style.color = "blue";

			console.log("Active contact: "+activeContact);

			//TODO:Let the sender know that receiver has view the message that was sent.
			//clear the message notication status count to indicate that user has view the message.  
		    document.getElementById(activeContact+"_msg_notice").textContent = "";
		}//openMessageFrame() Ends 



		/**************************************************************
		*Function: updateOnlineStatus()
		*/
		function updateOnlineStatus(socketThread, contacts, message){

			console.log("contact_functions.js/updateOnlineStatus(): Sending STATUS-REQUEST");

			var messageObj = JSON.parse(message);
			var contacts = JSON.parse(contacts);

			for (var i = 0; i < contacts.length; i++) {
				console.log(contacts[i].username);
				console.log(i);
				messageObj.receiver = contacts[i].username;
				console.log(contacts[i].username);
				//request for contacts' status 
				socketThread.postMessage( JSON.stringify(messageObj) );

				//console.log("contact_function.js: "+JSON.stringify(messageObj));

			}//for Ends

			return contacts;
		}//updateOnlineStatus() Ends 


		/***************************************************************
		/*/
		function updateOnlineStatusForSingleContact(socketThread, contact_username, message){

			console.log("contact_functions.js/updateOnlineStatus(): Sending STATUS-REQUEST");

			var messageObj = JSON.parse(message);
			messageObj.receiver = contact_username;
			//request for contacts' status 
			socketThread.postMessage( JSON.stringify(messageObj) );

			//console.log("contact_function.js: "+JSON.stringify(messageObj));

		}//updateSingleUserOnlineStatus() Ends


		/**************************************************************
		/* This function sents a notification message to another user (requester)
		*  to inform the user that his/her request to connect as a contact
		* has been accepted 
		*/
		function acceptContactRequest(sender, reciever, msg, socketWebWorker){

			//console.log(tagetContactNotice);
			//change the message type to ADD_CONTACT_REQUEST
			msg.request_type = ADD_CONTACT_REQUEST; 

			makeAjaxRequest("POST", ACCOUNT_SERVICE_URL, msg).done(function(response){

			    //add new contact to the view; send a 'new contact added message' 

				if(addSingleContactToView(reciever) == true){
					//inform the other party (sender) that contact request has been accepted, so that this new 
					//contact can be added to the view  
					var addNewUserToViewReq = generateTxtMessage( ADD_NEW_CONTACT_TO_VIEW, "contact_request accepted", sender, reciever);
					console.log(addNewUserToViewReq);
					console.log("Message Sent: -----")
					socketWebWorker.postMessage(addNewUserToViewReq);
																									
				}//if Ends 
										
		    });//done(function(response) Ends 

		}//function ends 

		/**************************************************************
		/* This function sents a notification message to another user (requester)
		*  to inform the user that his/her request to connect as a contact
		* has been accepted 
		*/		
		function rejectContactRequest(acceptBtn, receiver, msg){

		}//function ends 


		/**************************************************************
		* Function: addContactsToView();
		* Author: Presley Muwan
		* Desc: This function initializes the contact view with user's contacts 
		* Params: Conacts - A JSON-formated array of contacts information
		*/
		function addContactsToView(contacts){
						
			console.log(contacts);
			//the container element to hold the list of contacts 						
			contacts = JSON.parse(contacts);

			var contactHtml = "";


			for (var i = 0;  i < contacts.length; i++) {
				
				var username = contacts[i].username;
				console.log(username);

				contactHtml +='<p class="contacts"> <bold id='+username+' class="contact_name">'+ username +'</bold>\
									<img id="'+username+'_status_icon" class="color_status" height="13" width="13"\
							 		src="http://'+IP+'/images/offline.png">\
							 		<a class="badge" id='+username+'_msg_notice style="background:red margin-left:2%"></a>\
							 	  </p>';
		    }//for ENDS

			//contactUi.empty();
			//contactUi.append(contactHtml);
			$("#my_contacts").ready(function(){	
				$("#my_contacts").empty();//cleaer contact space to refresh 			
				$("#my_contacts").append(contactHtml);
			});	

			//add and event listener to the contact element
		   $("p.contacts bold.contact_name").ready(function(){
				    var contactHtmlTag = $("p.contacts bold.contact_name");
				    for (var i = 0; i < contactHtmlTag.length; i++) {

				    	 //add click event listener to each contact
				    	 $("#"+contactHtmlTag[i].textContent).click(function(){
				    	 	openMessageFrame(this); 
				    	 	//console.log(this.textContent);   	
				    	});

				    }//for ENDS 
			});//$(p.contacts).ready(function(){}) Ends

			//return the list of contacts as a promise
			return new Promise(function(resolve, reject){
				resolve(contacts);
			});//promise ends */

		}//addContactsToView() Ends 


		/***********************************************************
		/*Function to add a single contact to view 
		*/
		function addSingleContactToView(username){
						
			//check if the username is already attached to view before carrying out this operation
			var mContact = document.getElementById(username);
			
			if(mContact != null){
				return false;
			}//if Ends 

			//the container element to hold the list of contacts 						
			var contactHtml = "";

			contactHtml +='<p class="contacts"> <bold id='+username+' class="contact_name">'+ username +'</bold>\
									<img id="'+username+'_status_icon" class="color_status" height="13" width="13"\
							 		src="http://'+IP+'/images/offline.png">\
							 		<a class="badge" id='+username+'_msg_notice style="background:red margin-left:2%"></a>\
							 	  </p>';

			//contactUi.empty();
			//contactUi.append(contactHtml);
			$("#my_contacts").ready(function(){					
				$("#my_contacts").append(contactHtml);
			});	

			//add and event listener to the contact element
		   $("p.contacts bold.contact_name").ready(function(){
				    var contactHtmlTag = $("p.contacts bold.contact_name");
				    for (var i = 0; i < contactHtmlTag.length; i++) {

				    	 //add click event listener to each contact
				    	 $("#"+contactHtmlTag[i].textContent).click(function(){
				    	 	openMessageFrame(this); 
				    	 	//console.log(this.textContent);   	
				    	});

				    }//for ENDS 
			});//$(p.contacts).ready(function(){}) Ends

			//return the list of contacts as a promise
			return true;

		}//addContactsToView() Ends 




	    /********************************************************
		*Function: updateContactOnlinStatusChange()
		*Author: Presley M.
		*Desc: This function will update the online status of the user
		*		with the username passed in the parameter.  
		*
		*/
		function updateContactOnlinStatusChange(username, status){

		    

			var statusImageElement = document.getElementById(username+"_status_icon");
			console.log(username+"_status_icon");

			console.log(status+"=="+STATUS_ONLINE);
			//check the status type and update the status with the corresponding status color 
			if(status == STATUS_ONLINE){

				statusImageElement.setAttribute("src", ONLINE_IMG_URL);

			}else if(status == STATUS_IDLE){
				statusImageElement.setAttribute("src", IDLE_IMG_URL);

			}else if(status == STATUS_BUSY){
				statusImageElement.setAttribute("src", BUSY_IMG_URL);

			}else{
				//status is offline 
				statusImageElement.setAttribute("src", OFFLINE_IMG_URL);

			}//else if Ends 

		}//updateContactOnlinStatusChange


		/*********************************************************
		*makeAjaxRequst()
		*/
		function makeAjaxRequest(type="POST", url, data){
		 var jqXhtp = $.ajax({
						type: "POST",
						url: url,
						data: data 
					});//ajax() Ends 

		 jqXhtp.done(function(response){
		 	console.log(response);
		 });
		 return jqXhtp;
		}//makeAjaxRequest()




		/*****************************************************************
		*upConversation() Function 
		*Author: Presley M.  
		*This function will request past conversation between a user (username)
		*and his/her contact (contact) from the database. The conversation will
		*be received in JSON format and displayed in the the message view. 
		*/
		function loadConversation(request_type, username, contact){

			//generate request message to send to the backend
			 var messageReq = generateTxtMessage( request_type, "load users' conversation", sender, reciever);

			//make an AJAX request to the backend
			 makeAjaxRequest("POST",
				"http://"+IP+"/php/service/account_service.php",
				messageReq).done( function(response){

					//receive and format the response into html format
					//display the result 
				});//makeAjaxRequest() Ends 

		}//upConversation() Ends 