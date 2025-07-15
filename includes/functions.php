<?php
function requireRole($role) {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
        header("Location: ../auth/login.php");
        exit;
    }
}
?>
