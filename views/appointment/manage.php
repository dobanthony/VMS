<?php
session_start();
require_once '../../models/Appointment.php';
include '../../includes/adminLayout.php'; // Or header.php
?>

<h3>Manage Appointments</h3>

<?php
$appointments = Appointment::getAll();
?>

<?php if ($appointments): ?>
<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Client</th>
      <th>Vet ID</th>
      <th>Pet ID</th>
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
        <td><?= $appt['vet_id'] ?></td>
        <td><?= $appt['pet_id'] ?></td>
        <td><?= $appt['appointment_date'] ?></td>
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
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
  <p>No appointments found.</p>
<?php endif; ?>

