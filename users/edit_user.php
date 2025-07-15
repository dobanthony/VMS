<?php
session_start();
require_once '../includes/db.php';

// Ensure user is logged in and has a role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: ../views/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch user from DB
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit;
}

// Include layout based on role
switch ($role) {
    case 'admin':
        include '../includes/adminLayout.php';
        break;
    case 'veterinary':
        include '../includes/veterinaryLayout.php';
        break;
    default:
        include '../includes/clientLayout.php';
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit My Info</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Edit My Information</h2>

  <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>
  <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form action="update_user.php" method="POST">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">

    <div class="row g-3">
      <div class="col-md-4">
        <label>First Name</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
      </div>
      <div class="col-md-4">
        <label>Middle Name</label>
        <input type="text" name="middle_name" class="form-control" value="<?= htmlspecialchars($user['middle_name']) ?>">
      </div>
      <div class="col-md-4">
        <label>Last Name</label>
        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>">
      </div>
      <div class="col-md-4">
        <label>Date of Birth</label>
        <input type="date" name="dob" class="form-control" value="<?= $user['dob'] ?>">
      </div>
      <div class="col-md-2">
        <label>Age</label>
        <input type="number" name="age" class="form-control" value="<?= $user['age'] ?>">
      </div>
      <div class="col-md-2">
        <label>Sex</label>
        <select name="sex" class="form-control">
          <option <?= $user['sex'] == '' ? 'selected' : '' ?> value="">Select</option>
          <option <?= $user['sex'] == 'Male' ? 'selected' : '' ?>>Male</option>
          <option <?= $user['sex'] == 'Female' ? 'selected' : '' ?>>Female</option>
        </select>
      </div>
      <div class="col-md-4">
        <label>Blood Type</label>
        <select name="blood_type" class="form-control">
          <?php
          $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
          foreach ($bloodTypes as $type) {
              echo "<option value='$type'" . ($user['blood_type'] == $type ? ' selected' : '') . ">$type</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-6">
        <label>Address</label>
        <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user['address']) ?>">
      </div>
      <div class="col-md-6">
        <label>Contact</label>
        <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($user['contact']) ?>">
      </div>

      <div class="col-md-6">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>

      <hr class="mt-4 mb-2">

      <h5 class="mt-3">Change Password (optional)</h5>
      <div class="col-md-6">
        <label>Current Password</label>
        <input type="password" name="current_password" class="form-control">
      </div>
      <div class="col-md-6">
        <label>New Password</label>
        <input type="password" name="new_password" class="form-control">
      </div>
      <div class="col-md-6">
        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control">
      </div>

      <div class="col-12 text-end">
        <button type="submit" class="btn btn-success mt-3">Save Changes</button>
      </div>
    </div>
  </form>
</div>
</body>
</html>
