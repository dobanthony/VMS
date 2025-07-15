<?php
session_start();
require_once '../models/Appointment.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../views/auth/login.php");
    exit;
}

// Handle Booking (Client)
if (isset($_POST['book'])) {
    $user_id = $_SESSION['user']['id'];
    $date    = trim($_POST['date']);
    $time    = trim($_POST['time']);
    $reason  = trim($_POST['reason']);

    if ($date && $time && $reason) {
        $success = Appointment::create($user_id, $date, $time, $reason);

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
