<?php
session_start(); // Start the session

// Destroy the session and redirect to login page
session_unset();
session_destroy();
header('Location: login.php');
exit();
?>
