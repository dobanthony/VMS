<?php
session_start();
require_once '../models/Vaccination.php';

if (isset($_POST['add_vaccination'])) {
    if ($_SESSION['user']['role'] !== 'vet') {
        header("Location: ../views/auth/login.php");
        exit;
    }

    $data = [
        'pet_id'        => $_POST['pet_id'],
        'vet_id'        => $_SESSION['user']['id'],
        'vaccine_name'  => $_POST['vaccine_name'],
        'date_given'    => $_POST['date_given'],
        'next_due_date' => $_POST['next_due_date'],
        'notes'         => $_POST['notes']
    ];

    Vaccination::create($data);
    header("Location: ../views/vaccinations/list.php?pet_id=" . $data['pet_id']);
    exit;
}

if (isset($_POST['delete_vaccination'])) {
    if ($_SESSION['user']['role'] !== 'vet') {
        header("Location: ../views/auth/login.php");
        exit;
    }

    Vaccination::delete($_POST['id']);
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
