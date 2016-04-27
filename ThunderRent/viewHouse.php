<?php
require_once('include/functions.php');
checkSession();
?>
<!DOCTYPE html>
<html>
<head>
	<title>View House - ThunderRent</title>
	<link href="include/css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="include/img/icon.png">
	<style>
		table tr td {
			width: 25%;
		}
	</style>
</head>
<?php
if (isset($_GET['saveHouse'])) {
	if (save($_SESSION['userID'], $_GET['saveHouse'])) {
		echo '
			<script type="text/javascript">
				alert("SUCCESS: House saved");
				window.location.href = "viewHouse.php?houseID='.$_GET['saveHouse'].'";
			</script>
		';
	} else {
		echo '<script>alert("Error: Please contact server admin")';
	}
}

$houseID = $_GET['houseID'];

$connection = connectDB();
$query = mysqli_query($connection, "SELECT * FROM house_profile hp INNER JOIN (SELECT userID, userName FROM user_account) AS ua ON hp.ownerID = ua.userID WHERE houseID = '$houseID' LIMIT 1");
$row = mysqli_fetch_assoc($query);
$ownerID = $row['userID'];
$ownerName = $row['userName'];
$title = $row['title'];
$houseScore = $row['houseScore'];
$district = $row['district'];
$price = $row['price'];
$size = $row['size'];
$detail = $row['detail'];
$photoPath = $row['photoPath'];
?>
<body>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.6";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<div id="wrap">
		<div id="navigation">
			<a href="index.php">Home</a>|
			<a href="house.php">Housing</a>|
			<a href="mail.php">Mail Box</a>|
			<a href="<?php echo 'profile.php?userID='.$_SESSION['userID']; ?>">Profile</a>|
			<a href="logout.php">Log Out</a>
		</div>

		<h2>View House</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>

		<div id="house">
			<div class="info"><span>Owner</span><a href="profile.php?userID=<?php echo $ownerID; ?>"><?php echo $ownerName; ?></a></div>
			<div class="info"><span>Title</span><?php echo $title; ?></div>
			<div class="info"><span>Score</span><?php echo $houseScore; ?></div>
			<div class="info"><span>District</span><?php echo $district; ?></div>
			<div class="info"><span>Price</span><?php echo $price; ?></div>
			<div class="info"><span>Size (ft²)</span><?php echo $size; ?></div>
			<div class="info"><span>Detail</span><?php echo $detail; ?></div>
			<div class="info"><span>Photo</span>
			<?php
			if ($photoPath != null) {
				echo '</div><img src="'.$photoPath.'" alt="House Image" width="400" height="300" />';
			} else {
				echo 'This ads has no photo.</div>';
			}
			?>
			<div class="link">
				<a href="review.php?type=house&targetID=<?php echo $houseID; ?>">View review</a> | 
				<?php
					if ($ownerName != $_SESSION['userName']) {
						echo '<a href="postReview.php?type=house&targetID='.$houseID.'">Submit review</a> | ';
					}
				?>
				<a href="viewHouse.php?&saveHouse=<?php echo $houseID; ?>">Save as interested</a> | 
				<a href="mailto:?subject=Check this house out: <?php echo $title.' by '.$ownerName; ?>&body=This house looks good: <?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">Mail to friends</a> | 
				<div class="fb-share-button" data-href="<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" data-layout="button_count" data-mobile-iframe="true"></div>
			</div>
			<div class="submit"><a href="house.php">Back to house list</a></div>
		</div>
	</div>
</body>
</html>