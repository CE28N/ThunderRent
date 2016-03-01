<?php
require_once("include/functions.php");

$error = '';

session_start();
if (isset($_SESSION['username'])) {
	header('location: index.php');
}

if (isset($_POST['submit'])) {
	if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['confirmPassword'])) {
		$error = "Error: Please fill in every field";
	} else {
		if (strcmp($_POST['password'], $_POST['confirmPassword'])) {
			$error = "Error: Password not match";
		} else {
			if (register($_POST['username'], $_POST['password'])) {
				echo "
					<script type='text/javascript'>
						alert('SUCCESS: New account registered');
						window.location.href = 'login.php';
					</script>
				";
			} else {
				$error = "Error: Username in use";
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sign Up - ThunderRent</title>
	<link rel="icon" type="image/png" href="include/img/icon.png">
	<style>
		html, body {
			margin: 0 auto;
			font-family: sans-serif;
			background: url('include/img/background.jpg') no-repeat fixed center center;
			background-size: cover;
		}

		#register {
			width: 320px;
			padding: 20px;
			background: #fff;
			border-radius: 5px;
			margin: 0 auto;
			margin-top: 30px;
		}

		a {
			font-size: 12px;
			margin-bottom: 20px;
			color: #888;
			text-decoration: none;
		}

		a:hover {
			color: #000;
			text-decoration: underline;
		}

		span {
			color: red;
		}

		h2 {
			text-align: center;
			color: #000;
			text-transform: uppercase;
			margin-top: 0;
			margin-bottom: 20px;
		}

		input[type=text],input[type=email],input[type=password] {
			width: 100%;
			height: 40px;
			box-sizing: border-box;
			border-radius: 5px;
			border: 1px solid #ccc;
			margin-bottom: 10px;
			font-size: 14px;
			padding: 0 10px 0 20px;
		}

		input[type=submit] {
			width: 100%;
			background-color: #FFBC00;
			color: #fff;
			border: 2px solid #FFCB00;
			padding: 10px;
			font-size: 20px;
			text-transform: uppercase;
			border-radius: 5px;
			margin-bottom: 15px
		}

		input:hover[type=submit] {
			background-color: #FFCB00;
		}
	</style>
</head>

<body>
	<div id="register">
		<form method="post">
			<h2>Sign Up</h2>
			<input name="username" type="text" placeholder="Username">
			<input name="password" type="password" placeholder="Password">
			<input name="confirmPassword" type="password" placeholder="Confirm Password">
			<input name="submit" type="submit" value="Submit">
			<span><?php echo $error; ?></span>
			<h2><a href="login.php">Sign in</a></h2>
		</form>
	</div>
</body>
</html>