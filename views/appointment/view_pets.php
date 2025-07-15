<?php
session_start();
require_once '../../models/Pet.php';

if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'veterinarian'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid pet ID.";
    header("Location: ../appointment/manage.php");
    exit;
}

$pet = Pet::getById($_GET['id']);

if (!$pet) {
    $_SESSION['error'] = "Pet not found.";
    header("Location: ../appointment/manage.php");
    exit;
}

include ($_SESSION['user']['role'] === 'admin') ? '../../includes/adminLayout.php' : '../../includes/vetLayout.php';
?>

<div class="container mt-4">
  <h3>Pet Information</h3>

  <table class="table table-bordered">
    <tr>
      <th>Name</th>
      <td><?= htmlspecialchars($pet['name']) ?></td>
    </tr>
    <tr>
      <th>Species</th>
      <td><?= htmlspecialchars($pet['species']) ?></td>
    </tr>
    <tr>
      <th>Breed</th>
      <td><?= htmlspecialchars($pet['breed']) ?></td>
    </tr>
    <tr>
      <th>Age</th>
      <td><?= htmlspecialchars($pet['age']) ?> years</td>
    </tr>
    <tr>
      <th>Gender</th>
      <td><?= htmlspecialchars($pet['gender']) ?></td>
    </tr>
  </table>

  <a href="../appointment/manage.php" class="btn btn-secondary">⬅️ Back to Appointments</a>
</div>
