/*
ThunderRent Database - user_account
LAST CHANGE 27 Apr, 2016 14:48 HKT
*/

CREATE TABLE IF NOT EXISTS `user_account` (
  `userID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userName` varchar(30) NOT NULL,
  `userPassword` BINARY(60) NOT NULL,
  `userType` varchar(8) NOT NULL DEFAULT 'user',
  `userState` varchar(11) NOT NULL DEFAULT 'logged off'
);