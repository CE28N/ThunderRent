<?php
require_once("include/functions.php");
checkSession();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Host - ThunderRent</title>
	<link href="include/css/style.css" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" href="include/img/icon.png">
</head>
<?php
$ownerID = $_SESSION['userID'];

$connection = connectDB();

$query = mysqli_query($connection, "SELECT * FROM house_profile WHERE ownerID = '$ownerID'");
$row = mysqli_fetch_assoc($query);
$title = $row['title'];
$houseScore = $row['houseScore'];
$district = $row['district'];
$price = $row['price'];
$size = $row['size'];
$detail = $row['detail'];
$photoPath = $row['photoPath'];

if (mysqli_num_rows($query) == 0) {
	$ownerID = "";
	if (isset($_POST['submit'])){
		$ownerID = $_SESSION['userID'];
		$title = mysqli_real_escape_string($connection, $_POST['title']);
		$district = mysqli_real_escape_string($connection, $_POST['district']);
		$price = mysqli_real_escape_string($connection, $_POST['price']);
		$size = mysqli_real_escape_string($connection, $_POST['size']);
		$detail = mysqli_real_escape_string($connection, $_POST['detail']);

		if (isset($_FILES['photoPath']) && $_FILES['photoPath']['error'] != UPLOAD_ERR_NO_FILE) {
			$image_name = $_FILES['photoPath']['name'];
			$image_size = $_FILES['photoPath']['size'];
			$image_type = pathinfo($image_name,PATHINFO_EXTENSION);

			$image_dir = "include/img/house/";
			$status = 1;

			//Check if empty
			if ($image_name == '' || $image_size == 0) {
				echo "<script>alert('Error: File is empty')</script>";
				$status = 0;
			}
			//Allow certain file formats
			if ($image_type != "bmp" && $image_type != "jpg" && $image_type != "jpeg" && $image_type != "png") {
				echo "<script>alert('Error: Only .bmp, .jpg, .jpeg and .png are accepted')</script>";
				$status = 0;
			}

			//Upload if no error
			if($status == 1){
				//Rename image with UUID
				$uuid = gen_uuid();
				$image_tmp = $_FILES['photoPath']['tmp_name'];

				if (move_uploaded_file($image_tmp,"include/img/house/$uuid.$image_type")) {
					echo "<script>alert('SUCCESS: Image updated')</script>";

					$photoPath = "include/img/house/$uuid.$image_type";
				} else {
					echo "<script>alert('Error: Please contact server admin')";
				}
			} else {
				$photoPath = NULL;
			}
		} else {
			$photoPath = NULL;
		}

		if ($photoPath == NULL) {
			if (mysqli_query($connection, "INSERT INTO house_profile (ownerID, title, district, price, size, detail) VALUES ('$ownerID', '$title', '$district', '$price', '$size', '$detail')")) {
				echo "
					<script type='text/javascript'>
						alert('SUCCESS: Ads posted');
						window.location.href = '';
					</script>
				";
			} else {
				echo "<script>alert('Error: Please contact server admin')";
			}
		} else {
			if (mysqli_query($connection, "INSERT INTO house_profile (ownerID, title, district, price, size, detail, photoPath) VALUES ('$ownerID', '$title', '$district', '$price', '$size', '$detail', '$photoPath')")) {
				echo "
					<script type='text/javascript'>
						alert('SUCCESS: Ads posted');
						window.location.href = '';
					</script>
				";
			} else {
				echo "<script>alert('Error: Please contact server admin')";
			}
		}
	}
} else {
	$ownerID = $_SESSION['userName'];
	if (isset($_POST['submit'])){
		$ownerID = $_SESSION['userID'];
		$title = mysqli_real_escape_string($connection, $_POST['title']);
		$district = mysqli_real_escape_string($connection, $_POST['district']);
		$price = mysqli_real_escape_string($connection, $_POST['price']);
		$size = mysqli_real_escape_string($connection, $_POST['size']);
		$detail = mysqli_real_escape_string($connection, $_POST['detail']);

		if (isset($_FILES['photoPath']) && $_FILES['photoPath']['error'] != UPLOAD_ERR_NO_FILE) {
			$image_name = $_FILES['photoPath']['name'];
			$image_size = $_FILES['photoPath']['size'];
			$image_type = pathinfo($image_name,PATHINFO_EXTENSION);

			$image_dir = "include/img/house/";
			$status = 1;

			//Check if empty
			if ($image_name == '' || $image_size == 0) {
				echo "<script>alert('Error: File is empty')</script>";
				$status = 0;
			}
			//Allow certain file formats
			if ($image_type != "bmp" && $image_type != "jpg" && $image_type != "jpeg" && $image_type != "png") {
				echo "<script>alert('Error: Only .bmp, .jpg, .jpeg and .png are accepted')</script>";
				$status = 0;
			}

			//Upload if no error
			if($status == 1){
				//Rename image with UUID
				$uuid = gen_uuid();
				$image_tmp = $_FILES['photoPath']['tmp_name'];

				if (move_uploaded_file($image_tmp,"include/img/house/$uuid.$image_type")) {
					echo "<script>alert('SUCCESS: Image updated')</script>";

					$photoPath = "include/img/house/$uuid.$image_type";
				} else {
					echo "<script>alert('Error: Please contact server admin')";
				}
			} else {
				$photoPath = NULL;
			}
		} else {
			$photoPath = NULL;
		}

		if ($photoPath == NULL) {
			if (mysqli_query($connection, "UPDATE house_profile SET title = '$title', district = '$district', price = '$price', size = '$size', detail = '$detail' WHERE ownerID = '$ownerID'")) {
				echo "
					<script type='text/javascript'>
						alert('SUCCESS: Ads updated');
						window.location.href = '';
					</script>
				";
			} else {
				echo "<script>alert('Error: Please contact server admin')";
			}
		} else {
			if (mysqli_query($connection, "UPDATE house_profile SET title = '$title', district = '$district', price = '$price', size = '$size', detail = '$detail', photoPath = '$photoPath' WHERE ownerID = '$ownerID'")) {
				echo "
					<script type='text/javascript'>
						alert('SUCCESS: Ads updated');
						window.location.href = '';
					</script>
				";
			} else {
				echo "<script>alert('Error: Please contact server admin')";
			}
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

		<h2>Host</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>

		<div id="house">
			<form method="post" enctype="multipart/form-data">
				<div class="submit">
				<?php
				if (mysqli_num_rows($query) == 0) {
					echo "Please enter the detail of the house you want to host:";
				} else {
					echo "Detail of the house you host for lease:";
				} ?>
				</div>
				<div class="info"><span>Owner</span><a href="profile.php?userID=<?php echo $_SESSION['userID']; ?>"><?php echo $ownerID; ?></a></div>
				<div class="info"><span>Title</span><input name="title" type="text" value="<?php echo $title; ?>"></div>
				<div class="info"><span>Score</span><?php echo $houseScore; ?></div>
				<div class="info"><span>District</span><?php echo showDistrict(); ?></div>
				<div class="info"><span>Price</span><input name="price" type="number" value="<?php echo $price; ?>"></div>
				<div class="info"><span>Size (ft²)</span><input name="size" type="number" value="<?php echo $size; ?>"></div>
				<div class="info"><span>Detail</span><input name="detail" type="text" value="<?php echo $detail; ?>"></div>
				<div class="info"><span>Photo</span><input name="photoPath" type="file" value="" maxlength="255"></div>
				<?php
				if ($photoPath != null) {
					echo "<img src='".$photoPath."' alt='House Image' width='400' height='300' />";
				}
				?>
				<div class="submit"><input name="submit" type="submit" value="Update"></p></div>
				<div class="submit"><a href="house.php">Back to house list</a></div>
			</form>
		</div>
	</div>
</body>
</html>