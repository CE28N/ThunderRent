<?php
require_once("include/functions.php");
checkSession();
?>
<!DOCTYPE html>
<html>
<head>
	<title>House - ThunderRent</title>
	<link href="include/css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="include/img/icon.png">
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

		<h2>House</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>
		<h4><a href="host.php">Manage Ads</a></h4>

		<div id="index">
			<!---->
		</div>
	</div>
</body>
</html>