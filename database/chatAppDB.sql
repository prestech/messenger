
CREATE DATABASE IF NOT EXISTS chatAppDB;
USE chatAppDB;


DROP TABLE IF exists users; 

# Create a user table to hold all ther users 
CREATE TABLE IF NOT EXISTS users (
	userID int NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (userID), 
	lastName varchar(255),
	firstName varchar(255),
	username varchar(255) NOT NULL UNIQUE,
	email varchar(255) NOT NULL UNIQUE,
	password varchar(255) NOT NULL
    );

DROP TABLE IF exists contacts; 

#create a contact table to husersold all the user's contacts. 
CREATE TABLE IF NOT EXISTS contacts (
  #startDate datetime NOT NULL,
  username VARCHAR(255) NOT NULL, 
  contact VARCHAR(255) NOT NULL, 
  FOREIGN KEY (username) REFERENCES users(username),
  FOREIGN KEY (contact) REFERENCES users(username)
   ON DELETE CASCADE
   ON UPDATE CASCADE,
   
   PRIMARY KEY (username, contact)

);

#DROP TABLE message; 

#create a message table to hold message sent and receives 
CREATE TABLE IF NOT EXISTS message (
	messageID int NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (messageID), 
	content varchar(1000), 
	sent_time datetime,
    sender int, 
	FOREIGN KEY (sender) REFERENCES users(userID)
	ON DELETE CASCADE
	ON UPDATE CASCADE #user_fk

);

#DROP TABLE msg_receiver; 

# This table will store a list of recievers of a message 
CREATE TABLE IF NOT EXISTS msg_receiver (
 messageID int NOT NULL,
 receiver int NOT NULL,
 FOREIGN KEY (receiver) REFERENCES users(userID) 
 ON DELETE CASCADE
 ON UPDATE CASCADE #user_fk
 
); 

DROP TABLE notification; 

#This is a notification table to hold user's notifications 
CREATE TABLE IF NOT EXISTS notification (
  notificationID int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY(notificationID),
  noticeType varchar(50) NOT NULL,
  description varchar(255) NOT NULL,  
  #notifiedDate DATE NOT NULL,
  sender VARCHAR(255) NOT NULL, 
  receiver VARCHAR(255) NOT NULL, 
  FOREIGN KEY (sender) REFERENCES users(username),
  FOREIGN KEY (receiver) REFERENCES users(username) 
   ON DELETE CASCADE
   ON UPDATE CASCADE
);

#DROP TABLE notice_receiver; 

# This table will store a list of recievers of a message 
CREATE TABLE IF NOT EXISTS notice_receiver (
 notificationID int NOT NULL,
 receiver int NOT NULL,
 FOREIGN KEY (notificationID) REFERENCES notification(notificationID), 
 FOREIGN KEY (receiver) REFERENCES users(userID) 
 ON DELETE CASCADE
 ON UPDATE CASCADE #user_fk
); 

#DROP TABLE user_group; 

CREATE TABLE IF NOT EXISTS user_group (
	groupName varchar(255) NOT NULL,
    groupID INT NOT NULL,
    PRIMARY KEY(groupID)
);

#DROP TABLE group_member; 

CREATE TABLE IF NOT EXISTS group_member(
	groupID VARCHAR(255),
    member INT NOT NULL,
    FOREIGN KEY (groupID) REFERENCES user_group(groupID),
    FOREIGN KEY (member) REFERENCES users(userID)
);

#add sample data to users table 
#INSERT INTO users (lastName, firstName, username, email, password, sockID, onlineStatus) 
#			VALUES ("muwan","presley", "mpresley", "muwanpresley@gmail.com" , "123456789", "sockid1", FALSE );
            
#INSERT INTO users (lastName, firstName, username, email, password, sockID, onlineStatus ) 
#			VALUES ("Fangmo","Gilles", "fgilles", "presleymuwan@yahoo.com" , "123456789", "sockid2", FALSE);

#INSERT INTO users (lastName, firstName, username, email, password, sockID, onlineStatus ) 
#			VALUES ("Edy","Murf", "emurf", "presmuwan@yahoo.com" , "123456789", "sockid3", FALSE);

#INSERT INTO users (lastName, firstName, username, email, password, sockID, onlineStatus ) 
#			VALUES ("Sandra","Peters", "psandra", "pres@yahoo.com" , "123456789", "sockid4", FALSE);

#INSERT INTO users (lastName, firstName, username, email, password, sockID, onlineStatus ) 
#			VALUES ("Anderson","Coops", "cadams", "adam@yahoo.com" , "123456789", "sockid5", FALSE);

# add sample data to contact table 

#SHOW columns from contacts;


select * FROM users;
#select * FROM contacts;
#select * FROM notification;

#select * FROM contacts;