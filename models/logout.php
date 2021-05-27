<?php

// Initialize the session
session_start();

$username = $_SESSION["username"];

// Log Sign out History
chdir('../');
require_once('config.php');
chdir('../');
logoutHistory($username);
chdir($backup_dir . '/models');

// Unset all of the session variables
$_SESSION["loggedin"] = false;
$_SESSION["id"] = '';
$_SESSION["username"] = '';
$_SESSION["is_admin"] = false;


// Redirect to login page
header("location: ../index.php");
exit;

?>