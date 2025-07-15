<?php
session_start();
require_once '../../models/Appointment.php';
require_once '../../models/User.php'; // To get vets
require_once '../../models/Pet.php';  // To get client's pets
include '../../includes/clientLayout.php';

// Fetch list of vets
$vets = User::getAllByRole('veterinarian');

// Fetch list of client pets
$pets = Pet::getByOwner($_SESSION['user']['id']);
?>

<h3>Book Appointment</h3>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<form action="../../controllers/appointmentController.php" method="POST" class="row g-3">
  <div class="col-md-4">
    <label>Date</label>
    <input type="date" name="appointment_date" class="form-control" required>
  </div>
  <div class="col-md-4">
    <label>Select Veterinarian</label>
    <select name="vet_id" class="form-control" required>
      <option value="">-- Select Vet --</option>
      <?php foreach ($vets as $vet): ?>
        <option value="<?= $vet['id'] ?>"><?= htmlspecialchars($vet['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-4">
    <label>Select Pet</label>
    <select name="pet_id" class="form-control" required>
      <option value="">-- Select Pet --</option>
      <?php foreach ($pets as $pet): ?>
        <option value="<?= $pet['id'] ?>"><?= htmlspecialchars($pet['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-12">
    <label>Reason / Notes</label>
    <textarea name="notes" class="form-control" rows="3" required></textarea>
  </div>
  <div class="col-12">
    <button type="submit" name="book" class="btn btn-success">Book Appointment</button>
  </div>
</form>

<hr>

<h4>Your Appointments</h4>
<?php
$appointments = Appointment::getByClient($_SESSION['user']['id']);
if ($appointments):
?>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>Date</th>
      <th>Vet</th>
      <th>Pet</th>
      <th>Notes</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($appointments as $appt): ?>
    <tr>
      <td><?= $appt['appointment_date'] ?></td>
      <td><?= htmlspecialchars($appt['vet_name']) ?></td>
      <td><?= htmlspecialchars($appt['pet_name']) ?></td>
      <td><?= htmlspecialchars($appt['notes']) ?></td>
      <td>
        <span class="badge bg-<?= $appt['status'] === 'approved' ? 'success' : ($appt['status'] === 'declined' ? 'danger' : 'warning') ?>">
          <?= ucfirst($appt['status']) ?>
        </span>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
  <p>No appointments found.</p>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>
