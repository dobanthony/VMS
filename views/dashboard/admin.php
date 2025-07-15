<?php
require_once '../../includes/functions.php';
requireRole('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <h1>Welcome, Admin <?= htmlspecialchars($_SESSION['user']['name']) ?></h1>
    <p>This is the admin dashboard.</p>
    <a href="../../logout.php">Logout</a>
</body>
</html>
