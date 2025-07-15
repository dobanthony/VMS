<?php
session_start();
require_once '../../models/MedicalRecord.php';
require_once '../../models/Appointment.php';

// Restrict access to veterinarians only
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'veterinarian') {
    header("Location: ../auth/login.php");
    exit;
}

$vet_id = $_SESSION['user']['id'];
$pets = Appointment::getApprovedPetsByVet($vet_id); // Only show pets with approved appointments

// Handle form submission before output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id      = $_POST['pet_id'];
    $diagnosis   = trim($_POST['diagnosis']);
    $treatment   = trim($_POST['treatment']);
    $record_date = $_POST['record_date'];

    if ($pet_id && $diagnosis && $treatment && $record_date) {
        $success = MedicalRecord::create($pet_id, $vet_id, $diagnosis, $treatment, $record_date);
        $_SESSION[$success ? 'success' : 'error'] = $success
            ? "Medical record added successfully!"
            : "Failed to add medical record.";
        header("Location: view.php");
        exit;
    } else {
        $_SESSION['error'] = "All fields are required.";
    }
}

// Only include layout after all logic
include '../../includes/vetLayout.php';
?>

<div class="container mt-4">
    <h3>Add Medical Record</h3>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (empty($pets)): ?>
        <div class="alert alert-warning">
            No pets available. Make sure the client has an <strong>approved appointment</strong> with you before adding a medical record.
        </div>
    <?php else: ?>
        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label for="pet_id" class="form-label">Select Pet</label>
                <select name="pet_id" id="pet_id" class="form-control" required>
                    <option value="">-- Choose Pet --</option>
                    <?php foreach ($pets as $pet): ?>
                        <option value="<?= $pet['id'] ?>"><?= htmlspecialchars($pet['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="record_date" class="form-label">Record Date</label>
                <input type="date" name="record_date" class="form-control" required>
            </div>

            <div class="col-12">
                <label for="diagnosis" class="form-label">Diagnosis</label>
                <textarea name="diagnosis" class="form-control" rows="3" required></textarea>
            </div>

            <div class="col-12">
                <label for="treatment" class="form-label">Treatment</label>
                <textarea name="treatment" class="form-control" rows="3" required></textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success">Save Record</button>
                <a href="view.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    <?php endif; ?>
</div>
