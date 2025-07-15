<?php
require_once __DIR__ . '/../includes/db.php';

class MedicalRecord
{
    // Add new medical record
    public static function create($pet_id, $vet_id, $diagnosis, $treatment, $record_date)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO medical_records (pet_id, vet_id, diagnosis, treatment, record_date) 
                               VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$pet_id, $vet_id, $diagnosis, $treatment, $record_date]);
    }

    // Get all records (admin/vet)
    // public static function getAll()
    // {
    //     global $pdo;
    //     $stmt = $pdo->query("SELECT mr.*, p.name AS pet_name, u.name AS vet_name 
    //                          FROM medical_records mr
    //                          JOIN pets p ON mr.pet_id = p.id
    //                          JOIN users u ON mr.vet_id = u.id
    //                          ORDER BY mr.record_date DESC");
    //     return $stmt->fetchAll();
    // }
    public static function getAll()
    {
        global $pdo;
        $stmt = $pdo->query("
            SELECT mr.*, 
                p.name AS pet_name, 
                u.name AS owner_name,
                v.name AS vet_name
            FROM medical_records mr
            JOIN pets p ON mr.pet_id = p.id
            JOIN users u ON p.user_id = u.id
            JOIN users v ON mr.vet_id = v.id
            ORDER BY mr.record_date DESC
        ");
        return $stmt->fetchAll();
    }


    // Get records by pet
    public static function getByPet($pet_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT mr.*, u.name AS vet_name 
                               FROM medical_records mr
                               JOIN users u ON mr.vet_id = u.id
                               WHERE mr.pet_id = ?
                               ORDER BY mr.record_date DESC");
        $stmt->execute([$pet_id]);
        return $stmt->fetchAll();
    }

    // Get single record by ID
    public static function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT mr.*, p.name AS pet_name, u.name AS vet_name
                               FROM medical_records mr
                               JOIN pets p ON mr.pet_id = p.id
                               JOIN users u ON mr.vet_id = u.id
                               WHERE mr.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Delete record
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM medical_records WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public static function getByClientId($client_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT mr.*, p.name AS pet_name, u.name AS vet_name
            FROM medical_records mr
            JOIN pets p ON mr.pet_id = p.id
            JOIN users u ON mr.vet_id = u.id
            WHERE p.user_id = ?
            ORDER BY mr.record_date DESC
        ");
        $stmt->execute([$client_id]);
        return $stmt->fetchAll();
    }

    public static function getByIdAndClient($record_id, $client_id)
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT mr.*, p.name AS pet_name, u.name AS vet_name
        FROM medical_records mr
        JOIN pets p ON mr.pet_id = p.id
        JOIN users u ON mr.vet_id = u.id
        WHERE mr.id = ? AND p.user_id = ?
    ");
    $stmt->execute([$record_id, $client_id]);
    return $stmt->fetch();
}

}
