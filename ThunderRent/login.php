<?php
require_once('include/functions.php');

$error = '';

session_start();
if (isset($_SESSION['userName'])) {
	if ($_SESSION['userType'] == 'user') {
		header('location: index.php');
	} else {
		header('location: admin.php');
	}
}

if (isset($_POST['submit'])) {
	if (empty($_POST['username']) || empty($_POST['password'])) {
		$error = 'Error: Username or Password is invalid';
	} else {
		if (login($_POST['username'], $_POST['password'])) {
			if ($_SESSION['userType'] != 'banned') {
				if ($_SESSION['userType'] == 'user') {
					header('location: index.php');
				} else {
					header('location: admin.php');
				}
			} else {
				$error = 'Error: Account Banned';
			}
		} else {
			$error = 'Error: Wrong Username or Passwords';
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login - ThunderRent</title>
	<link rel="icon" type="image/png" href="include/img/icon.png">
	<style>
		html, body {
			margin: 0 auto;
			font-family: sans-serif;
			background: url('include/img/background.jpg') no-repeat fixed center center;
			background-size: cover;
		}

		#login {
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

		h2 {
			text-align: center;
			color: #000;
			text-transform: uppercase;
			margin-top: 0;
			margin-bottom: 20px;
		}

		span {
			color: red;
		}

		input[type=text],input[type=password] {
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
	<div id="login">
		<form method="post">
			<h2>Login</h2>
			<input name="username" type="text" placeholder="Username">
			<input name="password" type="password" placeholder="Password">
			<input name="submit" type="submit" value="Submit">
			<span><?php echo $error; ?></span>
			<h2><a href="register.php">Sign Up</a></h2>
		</form>
	</div>
</body>
</html>