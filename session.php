<?php
// session_check.php

session_start();

function checkSession($required_user_type = null) {
    if (!isset($_SESSION['loggedin'])) {
        // Not logged in, redirect to login page
        header('Location: login.php');
        exit();
    }

    if ($required_user_type !== null && $_SESSION['user_type'] !== $required_user_type) {
        // User type doesn't match, redirect to login page
        header('Location: login.php');
        exit();
    }
}
?>
