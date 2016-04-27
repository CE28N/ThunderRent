<?php
require_once('include/functions.php');

session_start();
logout($_SESSION['userID']);
session_destroy();

header('location: login.php');
?>