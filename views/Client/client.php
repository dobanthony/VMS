<?php
session_start();
require_once '../../models/Pet.php';
require_once '../../models/Appointment.php';
require_once '../../models/MedicalRecord.php';
include '../../includes/clientLayout.php';

// Check if user is logged in and is a client
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'client') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Fetch summary data
$pets = Pet::getByUser($user_id);
$appointments = Appointment::getByClient($user_id);
$records = MedicalRecord::getByClientId($_SESSION['user']['id']);

// Counts
$totalPets = count($pets);
$totalAppointments = count($appointments);
$totalRecords = count($records);

// Upcoming appointments
$upcoming = array_filter($appointments, function ($appt) {
    return strtotime($appt['appointment_date']) >= strtotime(date('Y-m-d'));
});
?>

<div class="container mt-4">
    <h3>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</h3>

    <div class="row my-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-left-primary p-3">
                <h5>Total Pets</h5>
                <p class="display-6"><?= $totalPets ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-left-info p-3">
                <h5>Total Appointments</h5>
                <p class="display-6"><?= $totalAppointments ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-left-success p-3">
                <h5>Upcoming Appointments</h5>
                <p class="display-6"><?= count($upcoming) ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-left-warning p-3">
                <h5>Medical Records</h5>
                <p class="display-6"><?= $totalRecords ?></p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <strong>Upcoming Appointments</strong>
        </div>
        <div class="card-body">
            <?php if (count($upcoming)): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Pet</th>
                            <th>Vet</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($upcoming as $appt): ?>
                            <tr>
                                <td><?= htmlspecialchars($appt['pet_name']) ?></td>
                                <td><?= htmlspecialchars($appt['vet_name']) ?></td>
                                <td><?= htmlspecialchars($appt['appointment_date']) ?></td>
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
                <p class="text-muted">You have no upcoming appointments.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
