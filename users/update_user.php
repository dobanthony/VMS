<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch current user to verify password
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['error'] = "User not found.";
        header("Location: edit_user.php");
        exit;
    }

    // Handle optional password change
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $updatePassword = false;
    $errors = [];

    if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
        // Check current password
        if (!password_verify($currentPassword, $user['password'])) {
            $errors[] = "Invalid current password.";
        }

        // Check if new password matches confirm
        if ($newPassword !== $confirmPassword) {
            $errors[] = "New password and confirm password do not match.";
        }

        if (empty($errors)) {
            $updatePassword = true;
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        }
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
        header("Location: edit_user.php");
        exit;
    }

    // Update user info (including password if applicable)
    $sql = "UPDATE users SET 
        name = :name,
        middle_name = :middle_name,
        last_name = :last_name,
        dob = :dob,
        age = :age,
        sex = :sex,
        blood_type = :blood_type,
        address = :address,
        contact = :contact,
        email = :email";

    if ($updatePassword) {
        $sql .= ", password = :password";
    }

    $sql .= " WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $params = [
        ':name'         => $_POST['name'],
        ':middle_name'  => $_POST['middle_name'],
        ':last_name'    => $_POST['last_name'],
        ':dob'          => $_POST['dob'],
        ':age'          => $_POST['age'],
        ':sex'          => $_POST['sex'],
        ':blood_type'   => $_POST['blood_type'],
        ':address'      => $_POST['address'],
        ':contact'      => $_POST['contact'],
        ':email'        => $_POST['email'],
        ':id'           => $user_id
    ];

    if ($updatePassword) {
        $params[':password'] = $hashedPassword;
    }

    $stmt->execute($params);

    // Refresh session
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $updatedUser = $stmt->fetch();
    $_SESSION['user'] = $updatedUser;

    $_SESSION['success'] = "Profile updated successfully!";
    header("Location: edit_user.php");
    exit;
}
