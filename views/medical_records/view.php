<?php
session_start();
require_once '../../models/MedicalRecord.php';
require_once '../../models/Pet.php';
require_once '../../models/User.php';

include '../../includes/vetLayout.php';

// Restrict access to veterinarians only
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'veterinarian') {
    header("Location: ../auth/login.php");
    exit;
}

$records = MedicalRecord::getAll(); // Fetch all records
?>

<div class="container mt-4">
    <h3>Medical Records</h3>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if ($records): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Pet Name</th>
                    <th>Owner</th>
                    <th>Vet</th>
                    <th>Diagnosis</th>
                    <th>Treatment</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['pet_name']) ?></td>
                        <td><?= htmlspecialchars($record['owner_name']) ?></td>
                        <td><?= htmlspecialchars($record['vet_name']) ?></td>
                        <td><?= nl2br(htmlspecialchars($record['diagnosis'])) ?></td>
                        <td><?= nl2br(htmlspecialchars($record['treatment'])) ?></td>
                        <td><?= htmlspecialchars($record['record_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No medical records found.</p>
    <?php endif; ?>
</div>
