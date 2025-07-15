<?php
session_start();
require_once '../../models/Pet.php';
include '../../includes/clientLayout.php';

$pets = Pet::getByUser($_SESSION['user']['id']);

?>

<div class="container mt-4">
  <h3>My Pets</h3>

  <a href="add.php" class="btn btn-primary mb-3">➕ Add New Pet</a>

  <?php if ($pets): ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Species</th>
          <th>Breed</th>
          <th>Age</th>
          <th>Gender</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pets as $pet): ?>
          <tr>
            <td><?= htmlspecialchars($pet['name']) ?></td>
            <td><?= htmlspecialchars($pet['species']) ?></td>
            <td><?= htmlspecialchars($pet['breed']) ?></td>
            <td><?= htmlspecialchars($pet['age']) ?></td>
            <td><?= htmlspecialchars($pet['gender']) ?></td>
            <td>
              <a href="edit.php?id=<?= $pet['id'] ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
              <a href="delete.php?id=<?= $pet['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">🗑️ Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No pets found.</p>
  <?php endif; ?>
</div>
