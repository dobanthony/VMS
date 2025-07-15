<?php
require_once '../includes/db.php';

class Vaccination {
    // Get all vaccinations for a specific pet
    public static function getByPetId($pet_id) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT v.*, u.name AS vet_name
            FROM vaccinations v
            JOIN users u ON v.vet_id = u.id
            WHERE v.pet_id = ?
            ORDER BY v.date_given DESC
        ");
        $stmt->execute([$pet_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new vaccination record
    public static function create($data) {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO vaccinations (pet_id, vet_id, vaccine_name, date_given, next_due_date, notes)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['pet_id'],
            $data['vet_id'],
            $data['vaccine_name'],
            $data['date_given'],
            $data['next_due_date'],
            $data['notes']
        ]);
    }

    // Delete a vaccination record
    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM vaccinations WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
