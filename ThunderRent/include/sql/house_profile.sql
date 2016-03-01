/*
ThunderRent Database - house_profile
LAST CHANGE 2 Mar 2016, 00:03 HKT
*/

CREATE TABLE IF NOT EXISTS `house_profile` (
  `houseID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ownerID` INT NOT NULL,
  `title` varchar(32) NOT NULL,
  `houseScore` int(11) NOT NULL DEFAULT 0,
  `district` varchar(32) NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `size` int(11) NOT NULL DEFAULT 0,
  `detail` varchar(255) DEFAULT NULL,
  `photoPath` varchar(255) DEFAULT NULL,
  FOREIGN KEY (ownerID) REFERENCES user_account(userID)
);