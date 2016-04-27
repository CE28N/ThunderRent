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
	<title>User Privileges - ThunderRent</title>
	<link href="include/css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="include/img/icon.png">
	<style>
		.info {
			height: 15px;
			border-top: 0px;
		}

		select {
			width: 150px;
			height: 20px;
		}
	</style>
</head>
<?php
$connection = connectDB();

if (isset($_POST['submit'])) {
	$userID = mysqli_real_escape_string($connection, $_POST['userID']);
	$userType = mysqli_real_escape_string($connection, $_POST['userType']);

	if (mysqli_query($connection, "UPDATE user_account SET userType = '$userType' WHERE userID = '$userID'")) {
		echo '
			<script type="text/javascript">
				alert("SUCCESS: User privilege updated");
				window.location.href = "admin.php";
			</script>
		';
	}
}
?>
<body>
	<div id="wrap">
		<div id="navigation">
			<a href="admin.php">Panel</a>|
			<a href="userList.php">Logged On User</a>|
			<a href="privileges.php">User Privileges</a>|
			<a href="ban.php">Ban User</a>|
			<a href="logout.php">Log Out</a>
		</div>

		<h2>User Privileges</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>

		<div id="admin">
			<form method="post">
				<div class="info"><span>Select your target user:</span><select name="userID">
				<?php
					$connection = connectDB();

					$query = mysqli_query($connection, "SELECT userID, userName FROM user_account");
					while($row = mysqli_fetch_assoc($query)) {
						echo '<option value="'.$row['userID'].'">'.$row['userName'].'</option>';
					}

					echo '<div class="submit"><a href="admin.php">Back to panel</a></div>';
				?>
				</select></div>
				<div class="info"><span>User Type:</span><input name="userType" type="radio" value="user" checked>User <input name="userType" type="radio" value="admin">Admin</div>
				<div class="submit"><input name="submit" type="submit" value="Update"></div>
			</form>
		</div>
	</div>
</body>
</html>