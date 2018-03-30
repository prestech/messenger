<?php
	session_start();
	require $_SERVER['DOCUMENT_ROOT']."/global_functions.php";

	if( isset($_SESSION["username"]) == false){
		echo "<meta http-equiv='refresh' content='0; url=http://".IP_ADDR."/>";
	}//if Ends
	
?>

<!DOCTYPE html>

<html>

<!--Html Header starts -->
<head>
	<title>Messenger</title>

	<!--LOCAL CSS-->
	<link rel="stylesheet" type="text/css" href= "<?php echo('http://'.IP_ADDR.'/css/messenger.css') ?>" >

	<!--BOOTSTRAP-->
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">



	<!--JQUERY EMOJI API 
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<script src="inc/jquery.mb.emoticons.js"></script> -->

	<!--bootstrap jquery -->
	<script
	  src="http://code.jquery.com/jquery-3.3.1.min.js"
	  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	  crossorigin="anonymous"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



	<!--Import HTML modules from another folder -->
	<link rel="Import" type="text/html" href="<?php echo('http://'.IP_ADDR.'/html/add_user_group_popup.html') ?>">

	<script src="<?php echo('http://'.IP_ADDR.'/js/global_constants.js') ?>"></script>

	<script type="text/javascript">
		var contact_notice_count = 0; 
	</script>
</head> <!--Html body ends -->

<!--BODY OPENING TAG-->
<body class="container_fluid" style="background: #6F256F"  >

	<div class="bdy_div">

		<!--left sided menu -->
		<div id= "left_side_view" class="side_view">

		   <!-- User specific salutation and drop down menu-->
		   <div class="dropdown">

				<!--User specific salutation --> 
				<button type="button" class="btn btn-lg btn-block btn-primary  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" id="dropdownMenuButton" > Hi <?php echo $_SESSION["lastName"]?> </button>

				<!--Dropdown menu for the button above--> 
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="#"> Account settings </a>
					<a class="dropdown-item" href="#"> Online Status </a>
					<a class="dropdown-item" href="#"> Preference </a>
					<a class="dropdown-item" href="#"> Help </a>
					<div role="seperator" class="dropdown-divider"></div>
					<a class="dropdown-item"  href="#http://192.168.1.158" id="logout_menu"> Logout </a>
				</div> <!--dropdown menu ends --> 

			</div> <!--Dropdown div Ends -->

			<!--This will hold a list of user (possible contact)-->
			<div class="side_nav_sector" id="group_list">
				<h4>Groups  <span class="glyphicon glyphicon-plus" id="group_add_sign" data-toggle="modal" data-target="#add_group_popup_modal"></span>
					<span id="group_notice_bell" class="glyphicon glyphicon-bell" data-toggle="modal" data-targ="#group_notification_popup_modal"></span> </h4>

				<!--Modal will be loaded into the div below-->
				 <div id="add_group_popup_modal" class="modal fade" role="dialog">
				 </div>
				 <div id="group_notification_popup_modal" class="modal fade" role="dialog"> </div>
			</div>

			<!--This will hold a list of user's contacts-->
			<div class="side_nav_sector" id="contact_list">

				<h4>Contacts <span class="glyphicon glyphicon-plus" id="contact_add_sign" data-toggle="modal" data-target="#add_contact_popup_modal"></span> <span id="contact_notice_bell" class="glyphicon glyphicon-bell" data-toggle="modal" data-target="#contact_notification_popup_modal" ></span> </h4>

				<div id="my_contacts"> </div>

				<!--Modal will be loaded into the div below-->
				<div id="add_contact_popup_modal" class="modal fade" role="dialog"> </div>
				<div id="contact_notification_popup_modal" class="modal fade" role="dialog"> </div>
			</div>
		</div><!--left sided menu Ends -->
		

		<!--This contains the messsage view: The view is on th righside of the side menu (div above)-->
		<div id="messager_frame">
			
			<div id="message_view" class="well well-lg">
				<h3> message view </h3>
			</div>

			<!--Input text area div-->
			<!--This div holds the message text area and its command buttons: the send, file attachment, video call ,audio call, and emoji btn-->
			<div id="input_div" class="input-group input-group-lg">

					<span class="btn btn-info input-group-addon glyphicon glyphicon-paperclip"></span>
					<input type="text" id="msg_input" class="form-control" onclick="">

					<span class="btn btn-info input-group-addon glyphicon glyphicon-send" onclick="submitData('<?php echo $_SESSION['username'] ?>')" ></span>
					<span class="btn btn-info input-group-addon glyphicon glyphicon-earphone"></span>
					<span class="btn btn-info input-group-addon glyphicon glyphicon-facetime-video"></span>

			</div><!--Input text area div Ends --> 

		  </div> <!--div id="messager_frame" Ends-->
		
		 <!--right side menu-->
	      <div id="right_side_view" class="side_view">
		 </div> <!--<div id="right_side_view" ENDS -->

	</div><!--div class="bdy_div" Ends -->

	
	<script src="<?php echo('http://'.IP_ADDR.'/js/message_functions.js') ?>"> </script>
	<script src="<?php echo('http://'.IP_ADDR.'/js/contact_functions.js') ?>"></script>
	<script src="<?php echo('http://'.IP_ADDR.'/js/ws_worker.js') ?>"></script>


	<!--call and run script function within this script tag below-->
	<script type="text/javascript">
		var username = '<?php echo $_SESSION['username']?>';

		//initiate socket-web-worker to establish socket connection
	    var socketWebWorker  = startWebSocketWorker(username);

		/************************************************************
		* Function: generateTxtMessage()
		* This function generate JSON formatted text message of all types 
		*/		
		function generateTxtMessage(messageType, content, username, receiver, status=1){
			var date = new Date();
			var msg_date = date.getMonth()+"-"+date.getDay()+"-"+date.getFullYear();

			//date will be added to the message automatically
			var message = {request_type:messageType, sender:username, receiver: receiver , content:content, status:status, date:msg_date}; //add date and time 
			message = JSON.stringify(message);
			return message;
		}//generateMessage()


		/*******************************************************
		*/
		function updateMessageView(username, msgTxt, received = true){
			var d = new Date();

			var timeNode = d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
			username = username+" "+timeNode;

			//update message dialogue view 
			var msgView = document.getElementById("message_view");

			var Hnode = document.createElement("h4")
			var divNode = document.createElement("div");
			//var hrNode = document.createElement("hr");

			msgTxtNode = document.createTextNode(msgTxt);
			userTxtNode = document.createTextNode(username);
			//userTxtNode.appendChild(timeNode);

			Hnode.appendChild(userTxtNode);

			divNode.appendChild(Hnode);
			divNode.appendChild(msgTxtNode);
			divNode.style.color= "#ffffff";
			divNode.style.background= "#6F256F";
			divNode.style.marginTop= "2%";
			//divNode.style.marginRadius= "2%";
			//divNode.appendChild(hrNode);

			if(received == false){
				divNode.style.color= "#000000";
				divNode.style.background= "#f0f0f5";
			}//if Ends 

			msgView.appendChild(divNode);

		}//updateMessageView() Ends 

	   /******************************************************************
		* Function: submitData(username)
		* Author: Presley M. 
		* Desc: This function is submits data, messages, when the user 
		*       hits send, to send message to a contact.
		* Params: 
		*		- username: the username of the user submitting the data.
							(the current logged in user)
		*/
		function submitData(username){
			//make sure that user have selected a user for the contact list
			if(activeContact == ""){
				alert("Select a Contact");
				return;
			}//if ends 
			//retreive text message and send it. 
			var msgField = document.getElementById("msg_input");
			var msgTxt = (msgField.value).trim();
			if(msgTxt == ""){return 0};

			//only bother to sumbit data when there is text available 

			//what until connection has been established before sending the message
			msgTxt = msgTxt.trim() //trim trailing an ending white spaces

			//generate JSON formatted text message 
			var message = generateTxtMessage(CONTACT_MESSAGE, msgTxt, username, activeContact);
			
			console.log("message.php/submitData(): Sending msg to another user: "+ message);

			if(msgTxt != null || msgTxt != ""){

				//post message to socket worker thread
				socketWebWorker.postMessage(message);

				//update message dialog box
			    updateMessageView( username ,msgTxt);
			}//if Ends 
			//reset message field 
			msgField.value = "";

		}//submitData() Ends 	
	</script>


	<!--JQUERY SCRIPT --> 
	<script type="text/javascript">
		$(document).ready(function(){
			console.log("document is ready");

			/*****************************************************************************************************
			* This code implements the functionality to add or join a new group. 
			*/
			$("#add_group_popup_modal").load( "add_user_group_popup.html",function(response, status, jqXhr){
				//console.log(response);
				jqXhr.done( function(){

				}); 
				
			}); //$("#add_group_popup_modal") Ends 

			/***********************************************************************************
			* This code implements the notification listener for the group notification UI (the Bell)
			*/


			/**************************************************************************************************
			* This code implements the functionality to add a new contact to the contact list 
			*/
			$("#add_contact_popup_modal").load( "add_user_group_popup.html",function(response, status, jqXhr){

				jqXhr.done( function(){
					//console.log("Done loading");
					 var searchString ="";
					$("#contact_list input.user_search_box").keyup(function(event){
						//console.log(" "+event.keyCode);
						if( (event.keyCode > 64 && event.keyCode < 91) 
							||(event.keyCode > 96 && event.keyCode < 123) || (event.keyCode > 48 && event.keyCode < 58) || (event.keyCode == 42) || (event.keyCode == 8) ){
							//fire a search request 
						   searchString = $(this)[0].value;
						   
						   //remove trailing white spaces 
						   searchString = searchString.trim() 

						   //check if the string entered is not empty 
						   //and fire a search request view Ajax
						   if(searchString.trim() != ""){

						   		//submit search request
						   		requestMsg ={request_type:SEARCH_USERS, search_string: searchString };

						   		makeAjaxRequest("POST",ACCOUNT_SERVICE_URL,
						   			requestMsg ).done( function(response){
										//remove php exit value (1) from the response string
										response = response.substr(1,response.length);

										//remove the previous search result
										$("#contact_list div.search_result").empty();

										//append the search result to the modal content
										$("#contact_list div.search_result").append(response);

										//add the a click event to contact_request_btn
										$("#contact_list button.contact_request_btn").each(function(index){
											
											var reqBtn = $( "#"+$("#contact_list button.contact_request_btn")[index].id);

											if(reqBtn.hasClass("glyphicon-ok") == false){
												reqBtn.click( function(){

													console.log("siblings");
													console.log(this);
													//retreive the username of the potential contact 
													var potential_contact = $(this).siblings("h3").children()[0].innerText;

													//send an "add_contact" request to the contact
													var msgContent = username+" wants to add as a contact";

													var contactReqNoticeMsg = generateTxtMessage(ADD_CONTACT_NOTIFICATION,msgContent,username, potential_contact);

													console.log("Contact Request: ");
													console.log(contactReqNoticeMsg);

													//Update the notication table in the database
													socketWebWorker.postMessage(contactReqNoticeMsg);

													//write the notification to the database 
													makeAjaxRequest("POST", ACCOUNT_SERVICE_URL, JSON.parse(contactReqNoticeMsg)).done(function(response){
														  console.log(response);
													   });

													//change the glyphicon from 'plus' to 'ok'
													reqBtn.removeClass("glyphicon-plus");
													reqBtn.addClass("glyphicon-ok");
												});
											}
											
										});//$("#contact_list span.request_btn").onclick() Ends
										//console.log($("#contact_list button.contact_request_btn"));

									}).fail(function(){
										console.log(".ajax failed");
								  });//$.ajax({}) Ends 
									
						   }else { //if searchString is Empty 
						   	//remove all search results
						   	$("#contact_list div.search_result").empty();

						   }//if else ends 

						}//if Ends 
					});

				});//jqXhr.done( function() Ends 
				
			});//$("#add_contact_popup_modal").load() Ends 




			/***********************************************************************************
			* This code implements the notification listener for the contact notification UI (the Bell)
			*/
			$("#contact_notification_popup_modal").load( "notification_popup.html",function(response, status, jqXhr){
				jqXhr.done( function(){

					//check if they are any new notifications (query the database) through an AJAX request

					$("#contact_notice_bell").click(function(){
						
					   var requestNoticeMsg ={request_type:REQUEST_NOTIFICATION};

						//TODO:Do not make unneccessary request. Make requests only when socket recovers from a disconnection (and a startup)  
						makeAjaxRequest("POST",ACCOUNT_SERVICE_URL, requestNoticeMsg )
						.done( function(response){

						    var jsResponseObj = JSON.parse(response);

						    //TODO: Only perform this part when data is available 
						    var oneRequestObj = "";
						    var htmlResponse = "";
						    
						    $("div.notification_div").empty();//clear space before displaying notification

						    for(var i=0; i < jsResponseObj.length; i++){

						    	oneRequestObj = jsResponseObj[i];
						    	
								htmlResponse += '<div class="request_div" id="'+oneRequestObj["sender"]+'_request_div" style="margin-botton:2%"> <p>'+ oneRequestObj["description"] +'<button class="accept_contact_btn" id="accept_'+oneRequestObj["sender"]+'" name="'+i+' "> accept </button> <button class="reject_contact_btn id="reject_'+oneRequestObj["sender"]+'" name="'+i+'"> reject </button> </p> </div>';

								console.log(oneRequestObj);
								console.log($("#accept_"+oneRequestObj["sender"]));
								
							}//for Ends 

						    $("div.notification_div").append(htmlResponse);


						    $("button.accept_contact_btn").ready(function(){

						    	//itereate through each button and add a click listener 
						        $("button.accept_contact_btn").each(function(index){
						        						        
							    	$( "#"+$("button.accept_contact_btn")[index].id).click( function(){

							    		var msg = jsResponseObj[parseInt(this.name)];

							    		//accept the user request (acceptContactRequest() from 'contact_function.js') 
							    		acceptContactRequest( jsResponseObj[parseInt(this.name)].receiver,
							    							  jsResponseObj[parseInt(this.name)].sender,
							    							  msg,
							    							  socketWebWorker);

							    		$("#"+oneRequestObj["sender"]+"_request_div").remove();//remove notice from UI.

							    	});

						    	});

						    });// $("button.accept_contact_btn").ready(function()
						    
					  }); 

					});//$("#contact_notice_bell").click(function(){}) Ends 

				}); //jqXhr.done( function(){}) Ends 
					
			}); //$("#add_group_popup_modal") Ends
			

			//********************************************************************
			//implement the logout functionality 
			$("#logout_menu").click( function(){
				console.log("logout");
				var myContacts = []; //declare and array

				//update online status to 'offline'
				$("bold.contact_name").each(function(index){
					myContacts[index] = $("bold.contact_name")[index].id;

					var statusReqMsg = generateTxtMessage(STATUS_UPDATE, "going offline", username, myContacts[index] , status=STATUS_OFFLINE);
					console.log(statusReqMsg);

					var myContactsToJson = JSON.stringify(myContacts[index]);

					//send status update message
					socketWebWorker.postMessage(statusReqMsg);

					//go to the index (login) page
					window.location.href = "http://"+'<?php echo(IP_ADDR); ?>'; 

				});//$("bold.contact_name") Ends 

				
	   

			});//$("#logout_menu").click(function() Ends 

		});//$(document).ready(function(){} )Ends 
 


		


	</script>


<!--BODY CLOSING TAG-->
</body>

<!--HTML CLOSING TAG-->
</html>