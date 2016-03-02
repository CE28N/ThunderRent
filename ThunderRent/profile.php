<?php
require_once('include/functions.php');
checkSession();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile - ThunderRent</title>
	<link href="include/css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="include/img/icon.png">
</head>
<?php
$userID = $_GET['userID'];

$connection = connectDB();

$query = mysqli_query($connection, "SELECT * FROM ((SELECT userID, userName FROM user_account) AS ua INNER JOIN (SELECT * FROM user_profile) AS up ON ua.userID = up.userID) WHERE ua.userID = '$userID'");
$row = mysqli_fetch_assoc($query);
$userName = $row['userName'];
$firstName = $row['firstName'];
$lastName = $row['lastName'];
$userEmail = $row['userEmail'];
$userScore = $row['userScore'];
$userGender = $row['userGender'];
$userPhone = $row['userPhone'];
$photoPath = $row['photoPath'];
$savedItems = $row['savedItems'];
$interested = $row['interested'];

$query = mysqli_query($connection, "SELECT houseID AS savedID, title AS savedName FROM ((SELECT savedItems FROM user_profile WHERE userID = '$userID' LIMIT 1) AS up INNER JOIN (SELECT houseID, title FROM house_profile) AS hp ON up.savedItems = hp.houseID)");
$row = mysqli_fetch_assoc($query);
$savedID = $row['savedID'];
$savedName = $row['savedName'];
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

		<h2>Profile</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>
		<?php
		if ($userID == $_SESSION['userID']) {
			echo '<h4><a href="edit.php?userID='.$_SESSION['userID'].'">Edit profile</a></h4>';
		}
		?>

		<div id="profile">
			<div class="photo"><img src="<?php echo $photoPath; ?>" width="150" height="150" /></div>
			<div class="info"><span>Username</span><?php echo $userName; ?></div>
			<div class="info"><span>Password</span><a href="changePassword.php">Change</a></div>
			<div class="info"><span>First Name</span><?php echo $firstName; ?></div>
			<div class="info"><span>Last Name</span><?php echo $lastName; ?></div>
			<div class="info"><span>Email</span><?php echo $userEmail; ?></div>
			<div class="info"><span>Score</span><?php echo $userScore; ?></div>
			<div class="info"><span>Gender</span><?php echo $userGender; ?></div>
			<div class="info"><span>Phone</span><?php echo $userPhone; ?></div>
			<div class="info"><span>Saved Items</span><a href="viewHouse.php?houseID=<?php echo $savedID; ?>"><?php echo $savedName;?></a></div>
			<div class="info"><span>Interested House</span><?php echo $interested;?></div>
			<?php
			if ($userID == $_SESSION['userID']) {
				echo '
					<div class="info">
						<span>Reviews</span><a href="review.php?type=user&targetID='.$userID.'">View</a>
					</div>
				';
			} else {
				echo '
					<div class="info">
						<span>Reviews</span><a href="review.php?type=user&targetID='.$userID.'">View</a> | <a href="postReview.php?type=user&targetID='.$userID.'">Post</a>
					</div>
				';
			}
			?>
		</div>
	</div>
</body>
</html>