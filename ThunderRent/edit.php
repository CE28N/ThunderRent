<?php
require_once('include/functions.php');
checkSession();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Profile - ThunderRent</title>
	<link href="include/css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="include/img/icon.png">
</head>
<?php
$userID = $_SESSION['userID'];

$connection = connectDB();

$query = mysqli_query($connection, "SELECT userName FROM user_account WHERE userID = '$userID'");
$row = mysqli_fetch_assoc($query);
$userName = $row['userName'];

$query = mysqli_query($connection, "SELECT * FROM user_profile WHERE userID = '$userID'");
$row = mysqli_fetch_assoc($query);
$firstName = $row['firstName'];
$lastName = $row['lastName'];
$userEmail = $row['userEmail'];
$userScore = $row['userScore'];
$userGender = $row['userGender'];
$userPhone = $row['userPhone'];
$photoPath = $row['photoPath'];
$savedItems = unserialize($row['savedItems']);
$interested = unserialize($row['interested']);

if (isset($_POST['submit'])){
	$firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
	$lastName = mysqli_real_escape_string($connection, $_POST['lastName']);
	$userEmail = mysqli_real_escape_string($connection, $_POST['userEmail']);
	$userGender = mysqli_real_escape_string($connection, $_POST['userGender']);
	$userPhone = mysqli_real_escape_string($connection, $_POST['userPhone']);

	if (isset($_FILES['photoPath']) && $_FILES['photoPath']['error'] != UPLOAD_ERR_NO_FILE) {
		$image_name = $_FILES['photoPath']['name'];
		$image_size = $_FILES['photoPath']['size'];
		$image_type = pathinfo($image_name,PATHINFO_EXTENSION);

		$image_dir = 'include/img/user/';
		$status = 1;

		//Check if empty
		if ($image_name == '' || $image_size == 0) {
			echo '<script>alert("Error: File is empty")</script>';
			$status = 0;
		}
		//Allow certain file formats
		if ($image_type != 'bmp' && $image_type != 'jpg' && $image_type != 'jpeg' && $image_type != 'png') {
			echo '<script>alert("Error: Only .bmp, .jpg, .jpeg and .png are accepted")</script>';
			$status = 0;
		}

		//Upload if no error
		if($status == 1){
			//Rename image with UUID
			$uuid = gen_uuid();
			$image_tmp = $_FILES['photoPath']['tmp_name'];

			if (move_uploaded_file($image_tmp,'include/img/user/$uuid.$image_type')) {
				echo '<script>alert("SUCCESS: Image updated")</script>';

				$photoPath = 'include/img/user/$uuid.$image_type';
			} else {
				echo '<script>alert("Error: Please contact server admin")';
			}
		} else {
			$photoPath = NULL;
		}
	} else {
		$photoPath = NULL;
	}

	if ($photoPath == NULL) {
		if (mysqli_query($connection, "UPDATE user_profile SET firstName = '$firstName', lastName = '$lastName', userEmail = '$userEmail', userPhone = '$userPhone' WHERE userID = '$userID'")) {
			echo '
				<script type="text/javascript">
					alert("SUCCESS: Profile updated");
					window.location.href = "profile.php?userID='.$userID.'";
				</script>
			';
		} else {
			echo '<script>alert("Error: Please contact server admin")';
		}
	} else {
		if (mysqli_query($connection, "UPDATE user_profile SET firstName = '$firstName', lastName = '$lastName', userEmail = '$userEmail', userPhone = '$userPhone', photoPath = '$photoPath' WHERE userID = '$userID'")) {
			echo '
				<script type="text/javascript">
					alert("SUCCESS: Profile updated");
					window.location.href = "profile.php?userID='.$userID.'";
				</script>
			';
		} else {
			echo '<script>alert("Error: Please contact server admin")';
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

		<h2>Edit Profile</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>

		<div id="edit">
			<form method="post" enctype="multipart/form-data">
				<div class="photo"><img src="<?php echo $photoPath; ?>" width="150" height="150" /></div>
				<div class="info"><span>Username</span><?php echo $_SESSION['userName']; ?></div>
				<div class="info"><span>Password</span><a href="changePassword.php">Change</a></div>
				<div class="info"><span>First Name</span><input name="firstName" type="text" value="<?php echo $firstName; ?>"></div>
				<div class="info"><span>Last Name</span><input name="lastName" type="text" value="<?php echo $lastName; ?>"></div>
				<div class="info"><span>Email</span><input name="userEmail" type="text" value="<?php echo $userEmail; ?>"></div>
				<div class="info"><span>Score</span><?php echo $userScore; ?></div>
				<div class="info"><span>Gender</span><input name="userGender" type="radio" value="M" checked>M <input name="userGender" type="radio" value="F">F</div>
				<div class="info"><span>Phone</span><input name="userPhone" type="text" value="<?php echo $userPhone; ?>"></div>
				<div class="info"><span>Saved Items</span></div>
				<div class="info"><span>Interested House</span></div>
				<div class="info"><span>Photo</span><input name="photoPath" type="file" value="" maxlength="255"></div>
				<div class="submit"><input name="submit" type="submit" value="Update"></p></div>
				<div class="submit"><a href="profile.php?userID=<?php echo $_SESSION['userID']; ?>">Back to profile</a></div>
			</form>
		</div>
	</div>
</body>
</html>