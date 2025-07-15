<?php
session_start();
require_once '../../models/Appointment.php';

// Allow only admin and veterinarian roles
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'veterinarian'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Load layout
if ($_SESSION['user']['role'] === 'admin') {
    include '../../includes/adminLayout.php';
} else {
    include '../../includes/vetLayout.php';
}

$appointments = Appointment::getAll();
?>

<div class="container mt-4">
  <h3>Manage Appointments</h3>

  <?php if ($appointments): ?>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Client</th>
          <th>Veterinarian</th>
          <th>Pet</th>
          <th>Date</th>
          <th>Notes</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($appointments as $appt): ?>
          <tr>
            <td><?= htmlspecialchars($appt['client_name']) ?></td>
            <td><?= htmlspecialchars($appt['vet_name']) ?></td>
            <td><?= htmlspecialchars($appt['pet_id']) ?></td>
            <td><?= htmlspecialchars($appt['appointment_date']) ?></td>
            <td><?= htmlspecialchars($appt['notes']) ?></td>
            <td>
              <span class="badge bg-<?= $appt['status'] === 'approved' ? 'success' : ($appt['status'] === 'declined' ? 'danger' : 'warning') ?>">
                <?= ucfirst($appt['status']) ?>
              </span>
            </td>
            <td>
              <?php if ($appt['status'] === 'pending'): ?>
                <form action="../../controllers/appointmentController.php" method="POST" class="d-inline">
                  <input type="hidden" name="id" value="<?= $appt['id'] ?>">
                  <button type="submit" name="approve" class="btn btn-sm btn-success">Approve</button>
                </form>
                <form action="../../controllers/appointmentController.php" method="POST" class="d-inline">
                  <input type="hidden" name="id" value="<?= $appt['id'] ?>">
                  <button type="submit" name="decline" class="btn btn-sm btn-danger">Decline</button>
                </form>
              <?php endif; ?>
              <a href="../appointment/view_pets.php?id=<?= $appt['pet_id'] ?>" class="btn btn-sm btn-info">View Pet</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No appointments found.</p>
  <?php endif; ?>
</div>
