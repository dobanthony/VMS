<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'vet') {
    header("Location: ../auth/login.php");
    exit;
}
$pet_id = $_GET['pet_id'];
?>

<h2>Add Vaccination Record</h2>

<form action="../../controllers/vaccinationController.php" method="POST">
    <input type="hidden" name="pet_id" value="<?= $pet_id ?>">

    <div class="mb-3">
        <label>Vaccine Name</label>
        <input type="text" name="vaccine_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Date Given</label>
        <input type="date" name="date_given" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Next Due Date</label>
        <input type="date" name="next_due_date" class="form-control">
    </div>

    <div class="mb-3">
        <label>Notes</label>
        <textarea name="notes" class="form-control"></textarea>
    </div>

    <button type="submit" name="add_vaccination" class="btn btn-success">Save</button>
    <a href="list.php?pet_id=<?= $pet_id ?>" class="btn btn-secondary">Back</a>
</form>
