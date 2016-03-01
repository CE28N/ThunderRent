/*
ThunderRent Database - user_account
LAST CHANGE 26 Feb, 2016 19:23 HKT
*/

CREATE TABLE IF NOT EXISTS `user_account` (
  `userID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userName` varchar(30) NOT NULL,
  `userPassword` BINARY(60) NOT NULL
);