<?php
session_start();
require_once '../../models/MedicalRecord.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'client') {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: my_medical_records.php");
    exit;
}

$record = MedicalRecord::getByIdAndClient($_GET['id'], $_SESSION['user']['id']);

if (!$record) {
    $_SESSION['error'] = "Unauthorized access.";
    header("Location: my_medical_records.php");
    exit;
}

include '../../includes/clientLayout.php';
?>

<div class="container mt-4">
    <h3>Medical Record Details</h3>
    <table class="table table-bordered">
        <tr>
            <th>Pet Name</th>
            <td><?= htmlspecialchars($record['pet_name']) ?></td>
        </tr>
        <tr>
            <th>Veterinarian</th>
            <td><?= htmlspecialchars($record['vet_name']) ?></td>
        </tr>
        <tr>
            <th>Diagnosis</th>
            <td><?= nl2br(htmlspecialchars($record['diagnosis'])) ?></td>
        </tr>
        <tr>
            <th>Treatment</th>
            <td><?= nl2br(htmlspecialchars($record['treatment'])) ?></td>
        </tr>
        <tr>
            <th>Date</th>
            <td><?= htmlspecialchars($record['record_date']) ?></td>
        </tr>
    </table>
    <a href="my_medical_records.php" class="btn btn-secondary">← Back to Records</a>
</div>
