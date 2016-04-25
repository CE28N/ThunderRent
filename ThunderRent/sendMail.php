<?php
require_once('include/functions.php');
checkSession();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Send Mail - ThunderRent</title>
	<link href="include/css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="include/img/icon.png">
	<style>
		input[type=text] {
			width: 300px;
		}
	</style>
</head>
<?php
$receiverID = $_GET['receiverID'];

$connection = connectDB();

$query = mysqli_query($connection, "SELECT userName FROM user_account WHERE userID = '$receiverID'");
$row = mysqli_fetch_assoc($query);
$receiverName = $row['userName'];

if (isset($_POST['submit'])) {
	if (send($_SESSION['userID'], $receiverID, $_POST['title'], $_POST['message'])) {
		echo '
			<script type="text/javascript">
				alert("SUCCESS: Message sent");
				window.location.href = "profile.php?userID='.$receiverID.'";
			</script>
		';
	} else {
		echo '<script>alert("Error: Please contact server admin")';
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

		<h2>Send Mail</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>

		<div id="mail">
			<form method="post">
				<div class="info"><span>To:</span><a href="profile.php?userID=<?php echo $receiverID; ?>"><?php echo $receiverName; ?></a></div>
				<div class="info"><span>Title</span><input name="title" type="text" value=""></div>
				<div class="message"><span>Message</span><textarea name="message"></textarea></div>
				<div class="submit"><input name="submit" type="submit" value="Send"></div>
				<div class="submit"><a href="profile.php?userID=<?php echo $_SESSION['userID']; ?>">Back to profile</a></div>
			</form>
		</div>
	</div>
</body>
</html>