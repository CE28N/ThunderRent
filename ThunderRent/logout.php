<?php
require_once('include/functions.php');
session_start();
session_destroy();
mysqli_close($connection);
header('location: login.php');
?>