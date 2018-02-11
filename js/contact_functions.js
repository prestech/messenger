		
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
		function openMessageFrame(id){

			//get a reference to the view object
			var msgView = document.getElementById("message_view");

			if(activeContact.trim() !== ""){
				
				//Change the color of the current active contact to black
				var currentActiveContact = document.getElementById (activeContact);
				currentActiveContact.style.color = "black";
				console.log("Switch from "+  activeContact );
			}//if Ends 

			//remove current user's  message content and replace them with that of the new user.
			activeContact =  id.textContent; 

			msgView.textContent = "";

			id.style.color = "blue";

			console.log("Active contact: "+activeContact);

			//TODO:
			//clear the message notication status count to indicate that user has view the message. 
			//Let the sender know that receiver has view the message that was sent. 

		}//openMessageFrame() Ends 



		/**************************************************************
		*Function: updateOnlineStatus()
		*/
		function updateOnlineStatus(socketThread, contacts, message){

			console.log("contact_functions.js/updateOnlineStatus(): Sending STATUS-REQUEST");

			var messageObj = JSON.parse(message);

			for (var i = 0; i < contacts.length; i++) {
				//console.log(contacts[i].username);
				messageObj.receiver = contacts[i].username;

				//request for contacts' status 
				socketThread.postMessage( JSON.stringify(messageObj) );

				console.log("contact_function.js: "+JSON.stringify(messageObj));

			}//for Ends 

			return contacts;
		}//updateOnlineStatus() Ends 



		/**************************************************************
		* Function: addContactsToView();
		* Author: Presley Muwan
		* Desc: This function initializes the contact view with user's contacts 
		* Params: Conacts - A JSON-formated array of contacts information
		*/
		function addContactsToView(contacts){
						

			//the container element to hold the list of contacts 
			var contactUi = document.getElementById("contact_list");
						
			contacts = JSON.parse(contacts);

			var contactHtml = "";


			for (var i = 0;  i < contacts.length; i++) {
				
				var username = contacts[i].username;
				console.log(username);

				contactHtml +='<p class="contacts"> <bold id='+username+' class="contact_name">'+ username +'</bold>\
									<img id='+username+'"_status_icon" class="color_status" height="13" width="13"\
							 		src="http://192.168.1.158/images/offline.png">\
							 		<a class="badge" id="mpresley_msg_notice" style="background:red; margin-left:2%;"></a>\
							 	  </p>';

		    }//for ENDS
			//contactUi.empty();
			//contactUi.append(contactHtml);
			$("#contact_list").ready(function(){					
				$("#contact_list").append(contactHtml);
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



	    /********************************************************
		*Function: updateContactOnlinStatusChange()
		*Author: Presley M.
		*Desc: This function will update the online status of the user
		*		with the corresponding, online, UI.  
		*
		*/
		function updateContactOnlinStatusChange(username, status){

		    console.log(username+"_status_icon");

			var statusImageElement = document.getElementById(username+"_status_icon");

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
						url: url, //"http://192.168.1.158/php/service/account_service.php",
						data: data //{search_string: searchString}
					});

		 jqXhtp.done(function(response){
		 	console.log(response);
		 });
		 return jqXhtp;
		}//makeAjaxRequest()

		/*********************************************************
		*persistMsg()
		*/
		function persistMsg(messageObj){

			makeAjaxRequest("POST",
				"http://192.168.1.158/php/service/account_service.php",
				data).done( function(respond){

				});
		}//persistMsg() Ends


		function requestData(messageObj){

			return makeAjaxRequest("POST",
				"http://192.168.1.158/php/service/account_service.php",
				data);
		}//requestData() Ends 