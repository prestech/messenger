/*This script contains functions used to manipulate groups: creating groups, deleting groups,adding users to groups, checking
		group notifications, and routing group messages to the right view*/
		/**This function is called when the user hits the 
		* "New Group" button*/
		function createGroup(){

			//diplay a popup window and receive the group info from 
			// the user; Group name, selected members, and whether the group is public or private. 


			//retreive all information entered by the user, from the pop-up window.

			//for the group information to JSON 

			//send the information to the data base via AJAX

			//create a new group object and attach it to the Group section. 

		}//createGroup() ends 


		/***********************************************************
		* This function is called when the user opts to delete a user.
		*/
		function deleteGroup(id){

			//display a pop confirmation window

			//if user confirms to delete group, retreive the group name to be deleted and send a delete request to the server (handle the rest on the server side)

			//upon successfull deletion of the group, from the database (make sure the server returns a confirmation that the group has been deleted), delete the group name from the view. 

		}//deleteGroup() Ends 

		/*************************************************************
		*This function will be called when the user wants to add another user to a group*/
		function addUserToGroup(){

			//display a pop-window with a list of users for the user to check

			//Collect the list of user selected by the user.

			//send the list of users to the server to store in a database.

			//delete the user from the ui

		}//addUserToGroup() Ends 

		/****************************************************************
		*This function will be called when the user wants to remove a user from the group (Only the owner of a group can delete a user)*/
		function removeUserFromGroup(){

		}//removeUserFromGroup() Ends 
		