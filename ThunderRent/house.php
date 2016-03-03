<?php
require_once('include/functions.php');
checkSession();
?>
<!DOCTYPE html>
<html>
<head>
	<title>House - ThunderRent</title>
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
			<a href="index.php">Home</a>|
			<a href="house.php">Housing</a>|
			<a href="mail.php">Mail Box</a>|
			<a href="<?php echo 'profile.php?userID='.$_SESSION['userID']; ?>">Profile</a>|
			<a href="logout.php">Log Out</a>
		</div>

		<h2>House</h2>
		<h4>Welcome back: <b><?php echo $_SESSION['userName']; ?></b></h4>

		<div id="list">
			<div class="link">Avgerage price: <b>$<?php echo avgPrice(); ?>/ft²</b></div>
			<div class="search">
				<form method="post">
					Search by:
					<input name="search" type="radio" value="title">Title
					<input name="search" type="radio" value="district">District
					<input name="search" type="radio" value="price">Price
					<input name="query" type="text" value="">
					<input name="submit" type="submit" value="Search">
				</form>
			</div>
			<?php
			ob_start();
			$connection = connectDB();
			$query = mysqli_query($connection, "SELECT houseID, title, district, price FROM house_profile");
			if (mysqli_num_rows($query) > 0) {
				echo '<table><tr><th>Title</th><th>District</th><th>Price</th><th>Link</th></tr>';
				while($row = mysqli_fetch_assoc($query)) {
					echo '<tr><td>'.$row['title'].'</td><td>'.$row['district'].'</td><td>'.$row['price'].'</td><td><a href="viewHouse.php?houseID='.$row['houseID'].'">View</a></td></tr>';
				}
				echo '</table>';
			} else {
				echo '<div class="link">0 results</div>';
			}
			echo '<div class="submit"><a href="host.php">Manage Ads</a></div>';

			if (isset($_POST['submit'])) {
				if ($_POST['query'] == NULL) {
					exit();
				}

				ob_end_clean();
				ob_start();
				$filter = $_POST['search'];
				$string = $_POST['query'];

				$query = mysqli_query($connection, "SELECT houseID, title, district, price FROM house_profile WHERE ".$filter." LIKE '%".$string."%'");
				if (mysqli_num_rows($query) > 0) {
					echo '<table><tr><th>Title</th><th>District</th><th>Price</th><th>Link</th></tr>';
					while($row = mysqli_fetch_assoc($query)) {
						echo '<tr><td>'.$row['title'].'</td><td>'.$row['district'].'</td><td>'.$row['price'].'</td><td><a href="viewHouse.php?houseID='.$row['houseID'].'">View</a></td></tr>';
					}
					echo '</table>';

					echo '<div class="submit"><a href="host.php">Manage Ads</a></div>';
				} else {
					echo '<div class="link">0 results</div>';
				}

				echo '<div class="link"><a href="">Back to list</a></div>';
			}
			?>
		</div>
	</div>
</body>
</html>