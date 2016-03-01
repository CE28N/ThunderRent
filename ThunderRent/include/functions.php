<?php
require_once("variables.php");

function checkSession() {
	session_start();
	if (!isset($_SESSION['userName'])) {
		header('location: login.php');
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
}

function login($userName, $userPass) {
	$connection = connectDB();

	$userName = mysqli_real_escape_string($connection, $userName);
	$userPass = mysqli_real_escape_string($connection, $userPass);

	$query = mysqli_query($connection, "SELECT userID, userPassword FROM user_account WHERE userName = '$userName' LIMIT 1");
	if (mysqli_num_rows($query) == 1) {
		$row = mysqli_fetch_assoc($query);
		$dbPass = $row['userPassword'];
		if (password_verify($userPass, $dbPass)) {
			$_SESSION['userID'] = $row['userID'];
			$_SESSION['userName'] = $userName;
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

function postReview($userID, $targetID, $rating, $comment) {
	$connection = connectDB();

	$comment = mysqli_real_escape_string($connection, $comment);
	if (mysqli_query($connection, "INSERT INTO user_review (userID, targetID, rating, comment) VALUES ('$userID', '$targetID', '$rating', '$comment')")) {
		$query = mysqli_query($connection, "SELECT SUM(rating) AS score FROM user_review WHERE targetID = '$targetID'");
		$row = mysqli_fetch_assoc($query);
		$score = $row['score'];
		if (mysqli_query($connection, "UPDATE user_profile SET userScore = '$score' WHERE userID = '$targetID'")) {
			return true;
		}
	}

	mysqli_close($connection);
	return false;
}

function updateReview($reviewID, $targetID, $rating, $comment) {
	$connection = connectDB();

	$comment = mysqli_real_escape_string($connection, $comment);
	if (mysqli_query($connection, "UPDATE user_review SET rating = '$rating', comment = '$comment' WHERE reviewID = '$reviewID'")) {
		$query = mysqli_query($connection, "SELECT SUM(rating) AS score FROM user_review WHERE targetID = '$targetID'");
		$row = mysqli_fetch_assoc($query);
		$score = $row['score'];
		if (mysqli_query($connection, "UPDATE user_profile SET userScore = '$score' WHERE userID = '$targetID'")) {
			return true;
		}
	}

	mysqli_close($connection);
	return false;
}

function showDistrict() {
	return "<select name='district'>
		<option value='Central and Western'>Central and Western</option>
		<option value='Wan Chai'>Wan Chai</option>
		<option value='Eastern'>Eastern</option>
		<option value='Southern'>Southern</option>
		<option value='Yau Tsim Mong'>Yau Tsim Mong</option>
		<option value='Sham Shui Po'>Sham Shui Po</option>
		<option value='Kowloon City'>Kowloon City</option>
		<option value='Wong Tai Sin'>Wong Tai Sin</option>
		<option value='Kwun Tong'>Kwun Tong</option>
		<option value='Kwai Tsing'>Kwai Tsing</option>
		<option value='Tsuen Wan'>Tsuen Wan</option>
		<option value='Tuen Mun'>Tuen Mun</option>
		<option value='Yuen Long'>Yuen Long</option>
		<option value='North'>North</option>
		<option value='Tai Po'>Tai Po</option>
		<option value='Sha Tin'>Sha Tin</option>
		<option value='Sai Kung'>Sai Kung</option>
		<option value='Islands'>Islands</option>
	</select>";
}
?>