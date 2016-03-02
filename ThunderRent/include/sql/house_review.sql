/*
ThunderRent Database - house_review
LAST CHANGE 2 Mar, 2016 13:57 HKT
*/

CREATE TABLE IF NOT EXISTS `house_review` (
  `reviewID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userID` INT NOT NULL,
  `targetID` INT NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  FOREIGN KEY (userID) REFERENCES user_account(userID),
  FOREIGN KEY (targetID) REFERENCES house_profile(houseID)
);