<?php
session_start();
require_once '../models/Appointment.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../views/auth/login.php");
    exit;
}

// Handle Booking (Client)
if (isset($_POST['book'])) {
    $client_id = $_SESSION['user']['id'];
    $appointment_date = trim($_POST['appointment_date']);
    $vet_id = trim($_POST['vet_id']);
    $pet_id = trim($_POST['pet_id']);
    $notes = trim($_POST['notes']);

    if ($appointment_date && $vet_id && $pet_id && $notes) {
        $success = Appointment::create($client_id, $vet_id, $pet_id, $appointment_date, $notes);

        $_SESSION[$success ? 'success' : 'error'] = $success
            ? "Appointment booked successfully!"
            : "Failed to book appointment.";
    } else {
        $_SESSION['error'] = "All fields are required.";
    }

    header("Location: ../views/appointment/book.php");
    exit;
}

// Handle Status Update (Admin/Vet only)
if ((isset($_POST['approve']) || isset($_POST['decline'])) && $_SESSION['user']['role'] !== 'client') {
    $id = $_POST['id'];
    $status = isset($_POST['approve']) ? 'approved' : 'declined';

    if (Appointment::updateStatus($id, $status)) {
        $_SESSION['success'] = "Appointment $status successfully.";
    } else {
        $_SESSION['error'] = "Failed to update appointment.";
    }

    header("Location: ../views/appointment/manage.php");
    exit;
}
