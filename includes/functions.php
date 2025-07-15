<?php
// includes/functions.php

function requireRole($role) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in and has the correct role
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: ../auth/login.php");
        exit;
    }
}
?>
