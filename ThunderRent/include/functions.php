<?php
require_once("variables.php");

function checkSession() {
	session_start();
	if (!isset($_SESSION['userName'])) {
		header('location: login.php');
	} else {
		if ($_SESSION['userType'] == 'admin') {
			header('location: admin.php');
		}
	}
}

function connectDB() {
	return mysqli_connect(HOST, USER, PASSWORD, DATABASE);
}

function bcrypt($password) {
	$options = array('cost' => 12);
	return password_hash($password, PASSWORD_BCRYPT, $options);
}

function gen_uuid() {
	return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
	// 32 bits for "time_low"
	mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

	// 16 bits for "time_mid"
	mt_rand( 0, 0xffff ),

	// 16 bits for "time_hi_and_version",
	// four most significant bits holds version number 4
	mt_rand( 0, 0x0fff ) | 0x4000,

	// 16 bits, 8 bits for "clk_seq_hi_res",
	// 8 bits for "clk_seq_low",
	// two most significant bits holds zero and one for variant DCE1.1
	mt_rand( 0, 0x3fff ) | 0x8000,

	// 48 bits for "node"
	mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
	);
	//This function is the only part of this project which the code is not written by our team
}

function login($userName, $userPass) {
	$connection = connectDB();

	$userName = mysqli_real_escape_string($connection, $userName);
	$userPass = mysqli_real_escape_string($connection, $userPass);

	$query = mysqli_query($connection, "SELECT userID, userPassword, userType FROM user_account WHERE userName = '$userName' LIMIT 1");
	if (mysqli_num_rows($query) == 1) {
		$row = mysqli_fetch_assoc($query);
		$dbPass = $row['userPassword'];
		if (password_verify($userPass, $dbPass)) {
			mysqli_query($connection, "UPDATE user_account SET userState = 'logged on' WHERE userName = '$userName'");
			$_SESSION['userID'] = $row['userID'];
			$_SESSION['userName'] = $userName;
			$_SESSION['userType'] = $row['userType'];
			mysqli_close($connection);
			return true;
		} else {
			mysqli_close($connection);
			return false;
		}
	}

	mysqli_close($connection);
	return false;
}

function logout($userID) {
	$connection = connectDB();

	$userName = mysqli_real_escape_string($connection, $userName);

	mysqli_query($connection, "UPDATE user_account SET userState = 'logged off' WHERE userID = '$userID'");

	mysqli_close($connection);
	return;
}

function register($userName, $userPass) {
	$connection = connectDB();

	$userName = mysqli_real_escape_string($connection, $userName);
	$userPass = bcrypt(mysqli_real_escape_string($connection, $userPass));

	$query = mysqli_query($connection, "SELECT userID FROM user_account WHERE userName = '$userName' LIMIT 1");
	if (mysqli_num_rows($query) == 0) {
		mysqli_query($connection, "INSERT INTO user_account (userName, userPassword) VALUES ('$userName', '$userPass')");
		$query = mysqli_query($connection, "SELECT userID FROM user_account WHERE userName = '$userName' LIMIT 1");
		$row = mysqli_fetch_assoc($query);
		$userID = $row['userID'];
		mysqli_query($connection, "INSERT INTO user_profile (userID) VALUES ('$userID')");
		return true;
	} else {
		mysqli_close($connection);
		return false;
	}
}

function changePassword($userID, $oldPassword, $newPassword) {
	$connection = connectDB();

	$query = mysqli_query($connection, "SELECT userPassword FROM user_account WHERE userID = '$userID' LIMIT 1");
	if (mysqli_num_rows($query) == 1) {
		$row = mysqli_fetch_assoc($query);
		$dbPass = $row['userPassword'];
		if (password_verify($oldPassword, $dbPass)) {
			$newPassword = bcrypt(mysqli_real_escape_string($connection, $newPassword));
			mysqli_query($connection, "UPDATE user_account SET userPassword = '$newPassword' WHERE userID = '$userID'");
			return true;
		} else {
			mysqli_close($connection);
			return false;
		}
	}
}

function deleteAccount($userID, $userPass) {
	$connection = connectDB();

	$userPass = mysqli_real_escape_string($connection, $userPass);

	$query = mysqli_query($connection, "SELECT userPassword FROM user_account WHERE userID = '$userID' LIMIT 1");
	if (mysqli_num_rows($query) == 1) {
		$row = mysqli_fetch_assoc($query);
		$dbPass = $row['userPassword'];
		if (password_verify($userPass, $dbPass)) {
			mysqli_query($connection, "DELETE FROM user_profile WHERE userID = '$userID'");
			mysqli_query($connection, "DELETE FROM user_review WHERE userID = '$userID' OR targetID = '$userID'");
			mysqli_query($connection, "DELETE FROM user_message WHERE senderID = '$userID' OR receiverID = '$userID'");
			mysqli_query($connection, "DELETE FROM house_profile WHERE ownerID = '$userID'");
			mysqli_query($connection, "DELETE FROM house_review WHERE userID = '$userID' OR targetID = '$userID'");
			mysqli_query($connection, "DELETE FROM user_account WHERE userID = '$userID'");

			$query = mysqli_query($connection, "SELECT COUNT(*) AS count FROM user_profile");
			$row = mysqli_fetch_assoc($query);
			$countUser = $row['count'];
			$query = mysqli_query($connection, "SELECT COUNT(*) AS count FROM house_profile");
			$row = mysqli_fetch_assoc($query);
			$countHouse = $row['count'];

			for ($i=1; $i<=$countUser; $i++) {
				$query = mysqli_query($connection, "SELECT SUM(rating) AS score FROM user_review WHERE targetID = '$i'");
				$row = mysqli_fetch_assoc($query);
				$score = $row['score'];
				mysqli_query($connection, "UPDATE user_profile SET userScore = '$score' WHERE userID = '$i'");
			}

			for ($i=1; $i<=$countHouse; $i++) {
				$query = mysqli_query($connection, "SELECT SUM(rating) AS score FROM house_review WHERE targetID = '$i'");
				$row = mysqli_fetch_assoc($query);
				$score = $row['score'];
				mysqli_query($connection, "UPDATE house_profile SET houseScore = '$score' WHERE houseID = '$i'");
			}

			mysqli_close($connection);
			return true;
		} else {
			mysqli_close($connection);
			return false;
		}
	}

	mysqli_close($connection);
	return false;
}

function postReview($userID, $targetID, $rating, $comment, $type) {
	$connection = connectDB();

	$comment = mysqli_real_escape_string($connection, $comment);
	if (mysqli_query($connection, "INSERT INTO ".$type."_review (userID, targetID, rating, comment) VALUES ('$userID', '$targetID', '$rating', '$comment')")) {
		$query = mysqli_query($connection, "SELECT SUM(rating) AS score FROM ".$type."_review WHERE targetID = '$targetID'");
		$row = mysqli_fetch_assoc($query);
		$score = $row['score'];
		if (mysqli_query($connection, "UPDATE ".$type."_profile SET ".$type."Score = '$score' WHERE ".$type."ID = '$targetID'")) {
			return true;
		}
	}

	mysqli_close($connection);
	return false;
}

function updateReview($reviewID, $targetID, $rating, $comment, $type) {
	$connection = connectDB();

	$comment = mysqli_real_escape_string($connection, $comment);
	if (mysqli_query($connection, "UPDATE ".$type."_review SET rating = '$rating', comment = '$comment' WHERE reviewID = '$reviewID'")) {
		$query = mysqli_query($connection, "SELECT SUM(rating) AS score FROM ".$type."_review WHERE targetID = '$targetID'");
		$row = mysqli_fetch_assoc($query);
		$score = $row['score'];
		if (mysqli_query($connection, "UPDATE ".$type."_profile SET ".$type."Score = '$score' WHERE ".$type."ID = '$targetID'")) {
			return true;
		}
	}

	mysqli_close($connection);
	return false;
}

function save($userID, $houseID) {
	$connection = connectDB();

	if (mysqli_query($connection, "UPDATE user_profile SET savedItems = '$houseID' WHERE userID = '$userID'")) {
		return true;
	} else {
		mysqli_close($connection);
		return false;
	}
}

function showSaved($userID) {
	$connection = connectDB();

	$query = mysqli_query($connection, "SELECT houseID AS savedID, title AS savedName FROM ((SELECT savedItems FROM user_profile WHERE userID = '$userID' LIMIT 1) AS up INNER JOIN (SELECT houseID, title FROM house_profile) AS hp ON up.savedItems = hp.houseID)");
	$row = mysqli_fetch_assoc($query);
	$savedID = $row['savedID'];
	$savedName = $row['savedName'];

	return '<a href="viewHouse.php?houseID='.$savedID.'">'.$savedName.'</a>';
}

function deleteSaved($userID) {
	$connection = connectDB();

	if (mysqli_query($connection, "UPDATE user_profile SET savedItems = NULL WHERE userID = '$userID'")) {
		return true;
	} else {
		mysqli_close($connection);
		return false;
	}
}

function send($senderID, $receiverID, $title, $message) {
	$connection = connectDB();

	if (mysqli_query($connection, "INSERT INTO user_message (senderID, receiverID, title, message) VALUES ('$senderID', '$receiverID', '$title', '$message')")) {
		return true;
	} else {
		mysqli_close($connection);
		return false;
	}
}

function showDistrict() {
	return '<select name="district">
		<option value="Central and Western">Central and Western</option>
		<option value="Wan Chai">Wan Chai</option>
		<option value="Eastern">Eastern</option>
		<option value="Southern">Southern</option>
		<option value="Yau Tsim Mong">Yau Tsim Mong</option>
		<option value="Sham Shui Po">Sham Shui Po</option>
		<option value="Kowloon City">Kowloon City</option>
		<option value="Wong Tai Sin">Wong Tai Sin</option>
		<option value="Kwun Tong">Kwun Tong</option>
		<option value="Kwai Tsing">Kwai Tsing</option>
		<option value="Tsuen Wan">Tsuen Wan</option>
		<option value="Tuen Mun">Tuen Mun</option>
		<option value="Yuen Long">Yuen Long</option>
		<option value="North">North</option>
		<option value="Tai Po">Tai Po</option>
		<option value="Sha Tin">Sha Tin</option>
		<option value="Sai Kung">Sai Kung</option>
		<option value="Islands">Islands</option>
	</select>';
}

function avgPrice() {
	$connection = connectDB();

	$query = mysqli_query($connection, "SELECT AVG(price)/AVG(size) AS avg FROM house_profile");
	$row = mysqli_fetch_assoc($query);

	return round($row['avg'], 2);
}
?>