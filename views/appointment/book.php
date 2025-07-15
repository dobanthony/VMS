<?php
session_start();
require_once '../../models/Appointment.php';
require_once '../../models/User.php'; // To fetch vets
require_once '../../models/Pet.php';  // To fetch client's pets
include '../../includes/clientLayout.php';

// Redirect if not logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'client') {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch list of veterinarians
$vets = User::getAllByRole('veterinarian');

// Fetch list of client pets
$pets = Pet::getByUser($_SESSION['user']['id']);

?>

<h3>Book Appointment</h3>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php elseif (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<form action="../../controllers/appointmentController.php" method="POST" class="row g-3">
    <div class="col-md-4">
        <label for="appointment_date">Date</label>
        <input type="date" name="appointment_date" class="form-control" required>
    </div>

    <div class="col-md-4">
        <label for="vet_id">Select Veterinarian</label>
        <select name="vet_id" class="form-control" required>
            <option value="">-- Select Vet --</option>
            <?php foreach ($vets as $vet): ?>
                <option value="<?= $vet['id'] ?>"><?= htmlspecialchars($vet['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4">
        <label for="pet_id">Select Pet</label>
        <select name="pet_id" class="form-control" required>
            <option value="">-- Select Pet --</option>
            <?php foreach ($pets as $pet): ?>
                <option value="<?= $pet['id'] ?>"><?= htmlspecialchars($pet['name']) ?> (<?= htmlspecialchars($pet['species']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12">
        <label for="notes">Reason / Notes</label>
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
            <th>Vet ID</th>
            <th>Pet ID</th>
            <th>Notes</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($appointments as $appt): ?>
        <tr>
            <td><?= $appt['appointment_date'] ?></td>
            <td><?= $appt['vet_id'] ?></td>
            <td><?= $appt['pet_id'] ?></td>
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
