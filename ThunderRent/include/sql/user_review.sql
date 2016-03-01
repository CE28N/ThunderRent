/*
ThunderRent Database - user_review
LAST CHANGE 1 Mar, 2016 16:30 HKT
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