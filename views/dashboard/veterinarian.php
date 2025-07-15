<?php
include '../../includes/vetLayout.php';
require_once '../../includes/functions.php';
requireRole('veterinarian');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Veterinarian Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <h1>Welcome, Dr. <?= htmlspecialchars($_SESSION['user']['name']) ?></h1>
    <p>This is the veterinarian dashboard.</p>
    <a href="../../logout.php">Logout</a>
</body>
</html>
