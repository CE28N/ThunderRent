<?php
require_once('include/functions.php');
checkSession();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Mail - ThunderRent</title>
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

		<h2>Mail</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>

		<div id="mail">
			<?php
			$connection = connectDB();

			$receiverID = $_SESSION['userID'];
			$query = mysqli_query($connection, "SELECT senderID, userName AS senderName, receiverID, message FROM ((SELECT userID, userName FROM user_account) AS ua INNER JOIN (SELECT senderID, receiverID, message FROM user_message) AS um ON ua.userID = um.senderID) WHERE receiverID = '$receiverID'");
			if (mysqli_num_rows($query) > 0) {
				echo '<table><tr><th width="25%">By User</th><th width="75%">Message</th></tr>';
				while($row = mysqli_fetch_assoc($query)) {
					echo '<tr><td><a href="profile.php?userID='.$row['senderID'].'">'.$row['senderName'].'</a></td><td>'.$row['message'].'</td></tr>';
				}
				echo '</table>';
			} else {
				echo '0 results';
			}
			?>
		</div>
	</div>
</body>
</html>