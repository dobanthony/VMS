<?php
require_once __DIR__ . '/../includes/db.php';

class User
{
    // Register a new user
    public static function register($name, $email, $password, $role = 'client')
    {
        global $pdo;

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            return false; // Email exists
        }

        // Insert new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $hashedPassword, $role]);
    }

    // Login
    public static function login($email, $password)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    // Get all users with a specific role
    public static function getAllByRole($role)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id, name FROM users WHERE role = ?");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }

    // Get user by ID
    public static function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Update email
    public static function updateEmail($userId, $newEmail)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
        return $stmt->execute([$newEmail, $userId]);
    }

    // Update password
    public static function updatePassword($userId, $newPassword)
    {
        global $pdo;
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashed, $userId]);
    }
}
