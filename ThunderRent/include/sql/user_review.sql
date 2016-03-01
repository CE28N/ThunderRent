/*
ThunderRent Database - user_review
LAST CHANGE 26 Feb, 2016 19:49 HKT
*/

CREATE TABLE IF NOT EXISTS `user_review` (
  `reviewID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userID` INT NOT NULL,
  `targetID` INT NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  FOREIGN KEY (userID) REFERENCES user_account(userID),
  FOREIGN KEY (targetID) REFERENCES user_account(userID)
);