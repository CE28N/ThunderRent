<?php
require_once('include/functions.php');
checkSession();

$error = '';

if (isset($_POST['submit'])) {
	if (empty($_POST['password'])) {
		$error = 'Error: Password is invalid';
	} else {
		if (deleteAccount($_SESSION['userID'], $_POST['password'])) {
			echo '
				<script type="text/javascript">
					alert("SUCCESS: Account deleted");
					window.location.href = "login.php";
				</script>
			';
		} else {
			$error = 'Error: Wrong Password';
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Delete Account - ThunderRent</title>
	<link href="include/css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="include/img/icon.png">
	<style>
		span {
			color: red;
		}
	</style>
</head>
<body>
	<div id="wrap">
		<div id="navigation">
			<a href="index.php">Home</a>|
			<a href="house.php">Housing</a>|
			<a href="mail.php">Mail Box</a>|
			<a href="<?php echo 'profile.php?userID='.$_SESSION['userID']; ?>">Profile</a>|
			<a href="logout.php">Log Out</a>
		</div>

		<h2>Delete Account</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>

		<div id="delete">
			<b>Confirm ThunderRent Account Deletion</b><br><br>
			If you do not think you will ever use ThunderRent again and would like your account deleted, we can take care of this for you.<br>
			Keep in mind that you will <b>NOT</b> be able to reactivate your account or retrieve any of the content or information you have added.<br>
			If you would still like to have your account deleted, enter your password then click "Submit."<br><br>

			<b>Password</b>
			<form method="post">
				<input name="password" type="password">
				<input name="submit" type="submit" value="Submit"><br>
				<span><?php echo $error; ?></span>
			</form><br>

			<a href="profile.php?userID=<?php echo $_SESSION['userID']; ?>">Back to profile</a>
		</div>
	</div>
</body>
</html>