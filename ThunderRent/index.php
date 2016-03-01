<?php
require_once("include/functions.php");
checkSession();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Index - ThunderRent</title>
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

		<h2>Thunder Rent</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['username']; ?></b></h4>

		<div id="index">
			Welcome to ThunderRent, <b><?php echo $_SESSION['username']; ?></b>! You can view, search or post any house for rent in this website. You can also use our built in functions to find your renting partner.
			<br><br>
			Try it now!
		</div>
	</div>
</body>
</html>