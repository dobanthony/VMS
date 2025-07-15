<?php
session_start();
require_once '../../models/Vaccination.php';

if (!isset($_GET['pet_id'])) {
    die("Missing pet ID.");
}

$pet_id = $_GET['pet_id'];
$vaccinations = Vaccination::getByPetId($pet_id);
$role = $_SESSION['user']['role'];
?>

<h2>Vaccination Records</h2>

<?php if ($role === 'vet'): ?>
    <a href="add.php?pet_id=<?= $pet_id ?>" class="btn btn-primary mb-3">+ Add Vaccination</a>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Vaccine</th>
            <th>Date Given</th>
            <th>Next Due</th>
            <th>Vet</th>
            <th>Notes</th>
            <?php if ($role === 'vet'): ?>
                <th>Action</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($vaccinations as $v): ?>
        <tr>
            <td><?= $v['vaccine_name'] ?></td>
            <td><?= $v['date_given'] ?></td>
            <td><?= $v['next_due_date'] ?></td>
            <td><?= $v['vet_name'] ?></td>
            <td><?= $v['notes'] ?></td>
            <?php if ($role === 'vet'): ?>
            <td>
                <form method="POST" action="../../controllers/vaccinationController.php" onsubmit="return confirm('Are you sure?')">
                    <input type="hidden" name="id" value="<?= $v['id'] ?>">
                    <button name="delete_vaccination" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
