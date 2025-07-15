<?php include '../../includes/clientLayout.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></h1>
        <p>This is the client dashboard.</p>
    </div>
</body>
</html>
