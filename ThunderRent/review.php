<?php
require_once('include/functions.php');
checkSession();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Review - ThunderRent</title>
	<link href="include/css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="include/img/icon.png">
	<style>
		table tr td {
			width: 33.33%;
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

		<h2>Review</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>

		<div id="list">
			<?php
			$targetID = $_GET['targetID'];
			$reviewType = $_GET['type'];
			$array = array();

			$connection = connectDB();
			$query = mysqli_query($connection, "SELECT r.userID, userName, rating, comment FROM user_account ua INNER JOIN ".$reviewType."_review r ON ua.userID = r.userID WHERE targetID = '$targetID'");
			if (mysqli_num_rows($query) > 0) {
				echo '<table><tr><th>By User</th><th>Rating</th><th>Comment</th></tr>';
				while($row = mysqli_fetch_assoc($query)) {
					echo '<tr><td><a href="profile.php?userID='.$row['userID'].'">'.$row['userName'].'</a></td><td>'.$row['rating'].'</td><td>'.$row['comment'].'</td></tr>';
				}
				echo '</table>';
			} else {
				echo '0 results';
			}
			?>
			<div class="submit"><a href="viewHouse.php?houseID=<?php echo $targetID; ?>">Back to house</a></div>
		</div>
	</div>
</body>
</html>