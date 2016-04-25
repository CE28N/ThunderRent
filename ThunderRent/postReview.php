<?php
require_once('include/functions.php');
checkSession();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Post Review - ThunderRent</title>
	<link href="include/css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="include/img/icon.png">
</head>
<?php
$userID = $_SESSION['userID'];
$targetID = $_GET['targetID'];
$reviewType = $_GET['type'];

$connection = connectDB();

if ($reviewType == 'user') {
	$query = mysqli_query($connection, "SELECT userName FROM user_account WHERE userID = '$targetID' LIMIT 1");
	$row = mysqli_fetch_assoc($query);
	$targetName = $row['userName'];

	$query = mysqli_query($connection, "SELECT reviewID, comment FROM user_review WHERE targetID = '$targetID' AND userID = '$userID'");
	if (mysqli_num_rows($query) == 0) {
		$comment = '';
		if (isset($_POST['submit'])){
			postReview($userID, $targetID, $_POST['rating'], $_POST['comment'], 'user');
			echo '
				<script type="text/javascript">
					alert("SUCCESS: Review posted.");
					window.location.href = "profile.php?userID='.$userID.'";
				</script>
			';
		}
	} else {
		$row = mysqli_fetch_assoc($query);
		$reviewID = $row['reviewID'];
		$comment = $row['comment'];
		if (isset($_POST['submit'])){
			updateReview($reviewID, $targetID, $_POST['rating'], $_POST['comment'], 'user');
			echo '
				<script type="text/javascript">
					alert("SUCCESS: Review updated.");
					window.location.href = "profile.php?userID='.$userID.'";
				</script>
			';
		}
	}
} else {
	$query = mysqli_query($connection, "SELECT houseID, ownerID, userName AS ownerName, title FROM ((SELECT userID, userName FROM user_account) AS ua INNER JOIN (SELECT houseID, ownerID, title FROM house_profile) AS hp ON ua.userID = hp.ownerID) WHERE houseID = '$targetID' LIMIT 1");
	$row = mysqli_fetch_assoc($query);
	$ownerID = $row['ownerID'];
	$ownerName = $row['ownerName'];
	$targetName = $row['title'];

	$query = mysqli_query($connection, "SELECT reviewID, comment FROM house_review WHERE targetID = '$targetID' AND userID = '$userID'");
	if (mysqli_num_rows($query) == 0) {
		$comment = '';
		if (isset($_POST['submit'])){
			postReview($userID, $targetID, $_POST['rating'], $_POST['comment'], 'house');
			echo '
				<script type="text/javascript">
					alert("SUCCESS: Review posted.");
					window.location.href = "viewHouse.php?houseID='.$targetID.'";
				</script>
			';
		}
	} else {
		$row = mysqli_fetch_assoc($query);
		$reviewID = $row['reviewID'];
		$comment = $row['comment'];
		if (isset($_POST['submit'])){
			updateReview($reviewID, $targetID, $_POST['rating'], $_POST['comment'], 'house');
			echo '
				<script type="text/javascript">
					alert("SUCCESS: Review updated.");
					window.location.href = "viewHouse.php?houseID='.$targetID.'";
				</script>
			';
		}
	}
}
?>
<body>
	<div id="wrap">
		<div id="navigation">
			<a href="index.php">Home</a>|
			<a href="house.php">Housing</a>|
			<a href="mail.php">Mail Box</a>|
			<a href="<?php echo 'profile.php?userID='.$_SESSION['userID']; ?>">Profile</a>|
			<a href="logout.php">Log Out</a>
		</div>

		<h2>Post Review</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>

		<div id="postReview">
			<form method="post">
				<div class="submit">Please enter the detail of the review you want to post:</div>
				<?php
				if ($reviewType == 'user') {
					echo '<div class="info"><span>Target</span><a href="profile.php?userID='.$targetID.'">'.$targetName.'</a></div>';
				} else {
					echo '<div class="info"><span>Owner</span><a href="profile.php?userID='.$ownerID.'">'.$ownerName.'</a></div>';
					echo '<div class="info"><span>Target</span><a href="viewHouse.php?houseID='.$targetID.'">'.$targetName.'</a></div>';
				}
				?>
				<div class="info"><span>Rating</span><input name="rating" type="radio" value="1">Good <input name="rating" type="radio" value="0" checked>Average <input name="rating" type="radio" value="-1">Bad</div>
				<div class="info"><span>Comment</span><input name="comment" type="text" value="<?php echo $comment; ?>" maxlength="255"></div>
				<div class="submit"><input name="submit" type="submit" value="Update"></div>
				<?php
				if ($reviewType == 'user') {
					echo '<div class="submit"><a href="profile.php?userID='.$_SESSION['userID'].'">Back to profile</a></div>';
				} else {
					echo '<div class="submit"><a href="viewHouse.php?houseID='.$targetID.'">Back to house</a></div>';
				}
				?>
			</form>
		</div>
	</div>
</body>
</html>