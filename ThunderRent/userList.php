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
	<title>Logged On User - ThunderRent</title>
	<link href="include/css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="include/img/icon.png">
	<style>
		table {
			margin-top: 10px;
		}

		table tr td {
			width: 25%;
		}
	</style>
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

		<h2>Logged On User</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>

		<div id="admin">
			<?php
				$connection = connectDB();

				$query = mysqli_query($connection, "SELECT COUNT(*) AS count FROM user_account WHERE userState = 'logged on'");
				$row = mysqli_fetch_assoc($query);
				echo '<div class="link">Number of active user: <b>'.$row['count'].'</b></div>';

				$query = mysqli_query($connection, "SELECT userID, userName, userType, userState FROM user_account WHERE userState = 'logged on'");
				echo '<table><tr><th>User</th><th>User Type</th><th>User State</th><th>Link</th></tr>';
				while($row = mysqli_fetch_assoc($query)) {
					echo '<tr><td>'.$row['userName'].'</td><td>'.$row['userType'].'</td><td>'.$row['userState'].'</td><td><a href="profile.php?userID='.$row['userID'].'">View</a></td></tr>';
				}
				echo '</table>';

				echo '<div class="submit"><a href="admin.php">Back to panel</a></div>';
			?>
		</div>
	</div>
</body>
</html>