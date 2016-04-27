<?php
require_once('include/functions.php');

session_start();
if (!isset($_SESSION['userName'])) {
	header('location: login.php');
} else {
	if ($_SESSION['userType'] != 'admin') {
		header('location: index.php');
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin Panel - ThunderRent</title>
	<link href="include/css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="include/img/icon.png">
</head>
<body>
	<div id="wrap">
		<div id="navigation">
			<a href="admin.php">Panel</a>|
			<a href="userList.php">Logged On User</a>|
			<a href="privileges.php">User Privileges</a>|
			<a href="ban.php">Ban User</a>|
			<a href="logout.php">Log Out</a>
		</div>

		<h2>Admin Panel</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>

		<div id="admin">
			Welcome back to Admin Panel, <b><?php echo $_SESSION['userName']; ?></b>! You can do these in this panel:<br>
			<ul>
				<li>View logged on user</li>
				<li>Change user privileges</li>
				<li>Ban/unban user</li>
			</ul>
			Please be aware you are the superuser of this system, you can either break the system as well as rule the system at the same time. Use with caution!
			<br><br>
			<b>Note:</b> Admin account are not compatible with normal user account. If you want to use this system to host/rent a house, please register another user account.
		</div>
	</div>
</body>
</html>