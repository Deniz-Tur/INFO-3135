<?php
// schedule/edit_schedule.php
session_start();
require __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "Access denied. Admins only.";
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "Invalid schedule ID.";
    exit;
}

// Fetch schedule
$stmt = $pdo->prepare("
    SELECT * FROM schedules
    WHERE schedule_id = :id
");
$stmt->execute([':id' => $id]);
$schedule = $stmt->fetch();

if (!$schedule) {
    echo "Schedule not found.";
    exit;
}

// Fetch employees
$empStmt = $pdo->query("SELECT employee_id, name, role FROM employees ORDER BY name");
$employees = $empStmt->fetchAll();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'] ?? '';
    $shift_date  = $_POST['shift_date'] ?? '';
    $shift_start = $_POST['shift_start'] ?? '';
    $shift_end   = $_POST['shift_end'] ?? '';
    $notes       = $_POST['notes'] ?? '';

    if ($employee_id === '' || $shift_date === '' || $shift_start === '' || $shift_end === '') {
        $errors[] = 'All required fields must be filled.';
    }

    if (empty($errors)) {
        $update = $pdo->prepare("
            UPDATE schedules
            SET employee_id = :employee_id,
                shift_date  = :shift_date,
                shift_start = :shift_start,
                shift_end   = :shift_end,
                notes       = :notes
            WHERE schedule_id = :id
        ");
        $update->execute([
            ':employee_id' => $employee_id,
            ':shift_date'  => $shift_date,
            ':shift_start' => $shift_start,
            ':shift_end'   => $shift_end,
            ':notes'       => $notes,
            ':id'          => $id
        ]);

        header('Location: view_schedule.php?date=' . urlencode($shift_date));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Staff Schedule</title>
    <link rel="stylesheet" href="schedule.css">
</head>
<body>

<div class="form-wrapper">
    <h1>Edit Staff Schedule</h1>

    <div class="back-links">
        <a href="view_schedule.php?date=<?php echo htmlspecialchars($schedule['shift_date']); ?>">
            &laquo; Back to Day Schedule
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div style="color:#c0392b; margin-bottom:10px;">
            <?php foreach ($errors as $e): ?>
                <div><?php echo htmlspecialchars($e); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Employee</label>
            <select name="employee_id" required>
                <option value="">-- Select Employee --</option>
                <?php foreach ($employees as $emp): ?>
                    <option value="<?php echo (int)$emp['employee_id']; ?>"
                        <?php if ($emp['employee_id'] == $schedule['employee_id']) echo 'selected'; ?>>
                        <?php
                        echo htmlspecialchars($emp['name'] . ' (' . $emp['role'] . ')');
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Shift Date</label>
            <input type="date" name="shift_date"
                   value="<?php echo htmlspecialchars($schedule['shift_date']); ?>" required>
        </div>

        <div class="form-group">
            <label>Shift Start Time</label>
            <input type="time" name="shift_start"
                   value="<?php echo htmlspecialchars(substr($schedule['shift_start'], 0, 5)); ?>" required>
        </div>

        <div class="form-group">
            <label>Shift End Time</label>
            <input type="time" name="shift_end"
                   value="<?php echo htmlspecialchars(substr($schedule['shift_end'], 0, 5)); ?>" required>
        </div>

        <div class="form-group">
            <label>Notes (optional)</label>
            <textarea name="notes" rows="3"><?php echo htmlspecialchars($schedule['notes']); ?></textarea>
        </div>

        <div class="button-row">
            <button type="submit">Save Changes</button>
        </div>
    </form>
</div>

</body>
</html>
