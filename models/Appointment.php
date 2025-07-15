<?php
require_once __DIR__ . '/../includes/db.php';

class Appointment
{
    // Create new appointment
    public static function create($client_id, $vet_id, $pet_id, $appointment_date, $notes)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO appointments (client_id, vet_id, pet_id, appointment_date, status, notes, created_at)
                               VALUES (?, ?, ?, ?, 'pending', ?, NOW())");
        return $stmt->execute([$client_id, $vet_id, $pet_id, $appointment_date, $notes]);
    }

    // Get appointments by client
    // Get appointments by client (with vet and pet names)
    public static function getByClient($client_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT a.*, 
                v.name AS vet_name, 
                p.name AS pet_name
            FROM appointments a
            JOIN users v ON a.vet_id = v.id
            JOIN pets p ON a.pet_id = p.id
            WHERE a.client_id = ?
            ORDER BY a.appointment_date DESC
        ");
        $stmt->execute([$client_id]);
        return $stmt->fetchAll();
    }


    // Get all appointments (for admin or vet)
    public static function getAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT a.*, 
                                    u.name AS client_name, 
                                    v.name AS vet_name 
                             FROM appointments a
                             JOIN users u ON a.client_id = u.id
                             JOIN users v ON a.vet_id = v.id
                             ORDER BY appointment_date DESC");
        return $stmt->fetchAll();
    }

    // Update appointment status
    public static function updateStatus($id, $status)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Get appointment by ID
    public static function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
