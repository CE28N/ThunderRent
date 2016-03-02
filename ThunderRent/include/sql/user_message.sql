/*
ThunderRent Database - user_message
LAST CHANGE 3 Mar, 2016 02:00 HKT
*/

CREATE TABLE IF NOT EXISTS `user_message` (
  `messageID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `senderID` INT NOT NULL,
  `receiverID` INT NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  FOREIGN KEY (senderID) REFERENCES user_account(userID),
  FOREIGN KEY (receiverID) REFERENCES user_account(userID)
);