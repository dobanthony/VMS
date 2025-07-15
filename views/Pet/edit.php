<?php
session_start();
require_once '../../models/Pet.php';

if (!isset($_GET['id'])) {
    header('Location: view.php');
    exit;
}

$pet = Pet::getById($_GET['id']);

if (!$pet || $pet['user_id'] != $_SESSION['user']['id']) {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: view.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated = Pet::update(
        $pet['id'],
        $_POST['name'],
        $_POST['species'],
        $_POST['breed'],
        $_POST['age'],
        $_POST['gender']
    );

    $_SESSION[$updated ? 'success' : 'error'] = $updated ? "Pet updated successfully!" : "Failed to update pet.";
    header('Location: view.php');
    exit;
}

include '../../includes/clientLayout.php'; // ✅ Moved below header calls
?>

<div class="container mt-4">
  <h3>Edit Pet</h3>

  <form method="POST" class="row g-3">
    <div class="col-md-6">
      <label>Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($pet['name']) ?>" required>
    </div>
    <div class="col-md-6">
      <label>Species</label>
      <input type="text" name="species" class="form-control" value="<?= htmlspecialchars($pet['species']) ?>" required>
    </div>
    <div class="col-md-6">
      <label>Breed</label>
      <input type="text" name="breed" class="form-control" value="<?= htmlspecialchars($pet['breed']) ?>">
    </div>
    <div class="col-md-6">
      <label>Age</label>
      <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($pet['age']) ?>" required>
    </div>
    <div class="col-md-6">
      <label>Gender</label>
      <select name="gender" class="form-control" required>
        <option value="Male" <?= $pet['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= $pet['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
      </select>
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-success">Update Pet</button>
      <a href="view.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
