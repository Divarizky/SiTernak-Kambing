<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Mengakhiri sesi.
session_destroy();

// Redirect to login page inside auth folder
header("location: login.php");
exit;
?>
