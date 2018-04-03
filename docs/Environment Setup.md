# SETTING UP THE ENVIRONMENT

### Download and Install a WAMP or LAMP Stack Server

The WAMP (or LAMP) stack provides the Http Apache and MSQL database Servers on PHP, needed to host the web page and back-end data store, respectively. For windows users click [WAMP](http://www.wampserver.com/en/), and [LAMP](https://bitnami.com/stack/lamp) for Linux users, to download and install.

Make sure you know the URL location of the WAMP (LAMP) server because you will need to configure the server's document root in the next step (after cloning/downloading) the project folder. 

### Clone "Messenger" 
To contribute to this code, like any githup project, you need to clone this project. The clone/download button is at the top right corner of the project directory, colored green. 

Next edit the following apache configuration files; `..\wamp64\bin\apache\apache2.4.27\conf\extra\httpd-vhosts.conf` and `..\wamp64\bin\apache\apache2.4.27\conf\httpd.conf`

* #### For `..\wamp64\bin\apache\apache2.4.27\conf\extra\httpd-vhosts.conf`
	* Change the value of `DocumentRoot` variable to the current path location of the "messenger" directory. 
	* Change the value of `Directory` variable to the current path location of the "messenger" directory. 
	* Change the value of `ServerName`  to the IP address of your computer (or its Local Domain Name).

* ### For `..\wamp64\bin\apache\apache2.4.27\conf\httpd.conf`
	* Change the value of `DocumentRoot` variable to the current path location of the "messenger" directory. 
	* Change the value of `Directory` variable to the current path location of the "messenger" directory.
	* Change the value of `ServerAlias` to the IP address of your computer (or its Local Domain Name).
	* Change the value of `ServerName`  to the IP address of your computer (or its Local Domain Name).
	
Note: that "wamp64", in the above URL, is the wamp server directory and apache2.4.27 means I am using apache version 2.4.27 (your version number might not be the same, no worries) 

### Build the Database

To build the database, import the database SQL script implemention into MYSQL Workbench (For Window users, if you have it installed) or PHP-MyAdmin and run it, Or just run the script on the command line to implement the database. 

### Open HTTP and ChatServer ports

This is the last step involved in setting up the environment for successfully running this application. At this point this application should be able to run on the server using the following url http://127.0.0.1. The only issue is no one else in the local network will be able to access this application; So no one, but yourself, can chat with you. To allow other people to access this application from with your next and chat with each other, you need to open two ports; port 80 and 5555, ChatServer port and the HTTP Port respectively.
