/*
ThunderRent Database - user_account
LAST CHANGE 25 Apr, 2016 15:41 HKT
*/

CREATE TABLE IF NOT EXISTS `user_account` (
  `userID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userName` varchar(30) NOT NULL,
  `userPassword` BINARY(60) NOT NULL,
  `userType` varchar(8) NOT NULL DEFAULT 'user'
);