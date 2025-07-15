<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>VRMS - Veterinary Record Management System</title>

  <!-- ✅ FIXED PATHS FOR ROOT-LEVEL PAGES -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    .navbar-vet {
      background-color: #4CAF50;
    }
    .navbar-brand img {
      height: 40px;
      margin-right: 10px;
    }
    .navbar-brand span {
      font-weight: bold;
      font-size: 1.2rem;
    }
    body {
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-vet navbar-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <!-- Optional logo -->
      <!-- <img src="assets/images/logo.png" alt="VRMS Logo"> -->
      <span>VRMS - Vet Clinic</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="views/dashboard/<?= $_SESSION['user']['role'] ?>.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="views/auth/login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="views/auth/register.php">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
