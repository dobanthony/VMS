<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch current user to verify password and get old avatar
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
        if (!password_verify($currentPassword, $user['password'])) {
            $errors[] = "Invalid current password.";
        }

        if ($newPassword !== $confirmPassword) {
            $errors[] = "New password and confirm password do not match.";
        }

        if (empty($errors)) {
            $updatePassword = true;
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        }
    }

    // Handle avatar upload
    $avatarName = $user['avatar'] ?? null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileSize = $_FILES['avatar']['size'];
        $fileType = $_FILES['avatar']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid('avatar_', true) . '.' . $fileExtension;
            $uploadDir = '../assets/img/avatars/';
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Delete old avatar if it exists
                if (!empty($avatarName) && file_exists($uploadDir . $avatarName)) {
                    unlink($uploadDir . $avatarName);
                }
                $avatarName = $newFileName;
            } else {
                $errors[] = "There was an error uploading the avatar.";
            }
        } else {
            $errors[] = "Invalid avatar file type. Allowed: jpg, jpeg, png, gif.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
        header("Location: edit_user.php");
        exit;
    }

    // Build SQL
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
        email = :email,
        avatar = :avatar";

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
        ':avatar'       => $avatarName,
        ':id'           => $user_id
    ];

    if ($updatePassword) {
        $params[':password'] = $hashedPassword;
    }

    $stmt->execute($params);

    // Refresh session user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $updatedUser = $stmt->fetch();
    $_SESSION['user'] = $updatedUser;

    $_SESSION['success'] = "Profile updated successfully!";
    header("Location: edit_user.php");
    exit;
}
