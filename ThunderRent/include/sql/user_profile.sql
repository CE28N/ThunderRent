/*
ThunderRent Database - user_profile
LAST CHANGE 26 Feb, 2016 19:49 HKT
*/

CREATE TABLE IF NOT EXISTS `user_profile` (
  `userID` INT NOT NULL PRIMARY KEY,
  `firstName` varchar(30) DEFAULT NULL,
  `lastName` varchar(30) DEFAULT NULL,
  `userEmail` varchar(50) DEFAULT NULL,
  `userScore` int(11) NOT NULL DEFAULT 0,
  `userGender` char(1) NOT NULL DEFAULT 'M',
  `userPhone` varchar(8) DEFAULT NULL,
  `photoPath` varchar(255) DEFAULT 'include/img/avatar.png',
  `savedItems` varchar(255) DEFAULT NULL,
  `interested` varchar(255) DEFAULT NULL,
  FOREIGN KEY (userID) REFERENCES user_account(userID)
);