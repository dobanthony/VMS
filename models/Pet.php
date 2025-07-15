<?php
require_once __DIR__ . '/../includes/db.php';

class Pet
{
    // Add a new pet
    public static function create($user_id, $name, $species, $breed, $age, $gender)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO pets (user_id, name, species, breed, age, gender, created_at)
                               VALUES (?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $name, $species, $breed, $age, $gender]);
    }

    // Update pet info
    public static function update($id, $name, $species, $breed, $age, $gender)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE pets SET name = ?, species = ?, breed = ?, age = ?, gender = ? WHERE id = ?");
        return $stmt->execute([$name, $species, $breed, $age, $gender, $id]);
    }

    // Delete a pet
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM pets WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Get all pets for a specific user (client)
    public static function getByUser($user_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM pets WHERE user_id = ? ORDER BY name");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // Get a single pet by ID
    public static function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM pets WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Get all pets (admin use)
    public static function getAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT p.*, u.name AS owner_name
                             FROM pets p
                             JOIN users u ON p.user_id = u.id
                             ORDER BY p.name");
        return $stmt->fetchAll();
    }
}
