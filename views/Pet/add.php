<?php
session_start();
require_once '../../models/Pet.php';

// ✅ Handle form submission BEFORE any HTML output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_SESSION['user']['id'];
    $name      = trim($_POST['name']);
    $species   = trim($_POST['species']);
    $breed     = trim($_POST['breed']);
    $age       = intval($_POST['age']);
    $gender    = $_POST['gender'];

    if ($name && $species && $breed && $age && $gender) {
        if (Pet::create($client_id, $name, $species, $breed, $age, $gender)) {
            $_SESSION['success'] = "Pet added successfully.";
        } else {
            $_SESSION['error'] = "Failed to add pet.";
        }
    } else {
        $_SESSION['error'] = "Please fill in all fields.";
    }

    header("Location: add.php");
    exit;
}

// 🧩 Now include layout
include '../../includes/clientLayout.php';
?>

<div class="container mt-4">
  <h3>Add New Pet</h3>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form method="POST" class="row g-3">
    <div class="col-md-6">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label>Species</label>
      <input type="text" name="species" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label>Breed</label>
      <input type="text" name="breed" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label>Age</label>
      <input type="number" name="age" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label>Gender</label>
      <select name="gender" class="form-control" required>
        <option value="">-- Select Gender --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" type="submit">Add Pet</button>
    </div>
  </form>
</div>

<?php include '../../includes/footer.php'; ?>
