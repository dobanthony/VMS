<?php
session_start();
require_once '../includes/db.php';


// ====================
// REGISTER
// ====================
if (isset($_POST['register'])) {
    $name             = trim($_POST['name']);
    $email            = trim($_POST['email']);
    $password         = $_POST['password'];
    $confirmPassword  = $_POST['confirm_password'];
    $role             = 'client'; // Force default role to 'client'

    // Password confirmation check
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../views/auth/register.php");
        exit;
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Email is already registered.";
        header("Location: ../views/auth/register.php");
        exit;
    }

    // Hash and insert new user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hashedPassword, $role]);

    $_SESSION['success'] = "Registration successful. Please log in.";
    header("Location: ../views/auth/login.php");
    exit;
}


// ====================
// LOGIN
// ====================
if (isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // ✅ Set session variables explicitly
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['name']    = $user['name'];

        // Optional: store entire user array
        $_SESSION['user'] = $user;

        // ✅ Redirect user based on role
        switch ($user['role']) {
            case 'admin':
                header("Location: ../views/Admin/admin.php");
                break;
            case 'veterinarian':
                header("Location: ../views/Veterinarian/veterinarian.php");
                break;
            case 'client':
            default:
                header("Location: ../views/Client/client.php");
                break;
        }
        exit;
    } else {
        $_SESSION['error'] = "Invalid login credentials.";
        header("Location: ../views/auth/login.php");
        exit;
    }
}
?>
