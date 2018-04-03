# Messenger

This is a client-server messenger web browser application that allows users to register and account, send "contact" request to other users, accept "contact" request to other users and ultimately chat with their contacts. The server side is implement in php while the client side is implemented in javascript, html, and css. 

Before you can tryout this webapplication you need to setup the webserver, database server, and PHP programming environment needed to host the webpage and use it. See [Environment Setup](https://github.com/prestech/messenger/blob/master/docs/Environment%20Setup.md) before moving forward (You cannot skip this step). 

### Snapshot 1.0 Running the severside messenger application  
The server side application "chatServer.php" is implemented using a third party socket API know as Ratchet. For users to send and receive messages, the server side application must be up and running. 
To start the meesenger sever application cd (change directory) into /php and run the chatSever.php (/php/chatSever.php) script by entering "php \chatSever.php" on the commandline. 

![alt text](https://github.com/prestech/messenger/blob/master/docs/snapshot/server.gif "Description goes here")


### GIF 2.0 Signup and login 
![alt text](https://github.com/prestech/messenger/blob/master/docs/snapshot/login.gif "Description goes here")

### GIF 2.1 Send and accept contact request 
![alt text](https://github.com/prestech/messenger/blob/master/docs/snapshot/contact_request.gif "Description goes here")

### GIF 2.2 Send and receive maessages
![alt text](https://github.com/prestech/messenger/blob/master/docs/snapshot/messaging.gif "Description goes here")

### GIF 2.3 Logout from messenger
![alt text](https://github.com/prestech/messenger/blob/master/docs/snapshot/signout.gif "Description goes here")

 
