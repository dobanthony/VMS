<?php
session_start();
require_once '../../models/MedicalRecord.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'client') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$records = MedicalRecord::getByClientId($user_id);

include '../../includes/clientLayout.php';
?>

<div class="container mt-4">
    <h3>My Pet's Medical Records</h3>

    <?php if ($records): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Pet Name</th>
                    <th>Veterinarian</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['pet_name']) ?></td>
                        <td><?= htmlspecialchars($record['vet_name']) ?></td>
                        <td><?= htmlspecialchars($record['record_date']) ?></td>
                        <td>
                            <a href="view_medical_record.php?id=<?= $record['id'] ?>" class="btn btn-info btn-sm">🔍 View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No medical records found for your pets.</p>
    <?php endif; ?>
</div>
