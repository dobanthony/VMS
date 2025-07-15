<?php
session_start();
require_once '../../models/Pet.php';

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid pet ID.";
    header('Location: view.php');
    exit;
}

$pet = Pet::getById($_GET['id']);

// Verify pet exists and belongs to current user
if (!$pet || $pet['user_id'] != $_SESSION['user']['id']) {
    $_SESSION['error'] = "Unauthorized action.";
    header('Location: view.php');
    exit;
}

// Attempt to delete pet
$deleted = Pet::delete($pet['id']);

$_SESSION[$deleted ? 'success' : 'error'] = $deleted 
    ? "Pet deleted successfully." 
    : "Failed to delete pet.";

header('Location: view.php');
exit;
