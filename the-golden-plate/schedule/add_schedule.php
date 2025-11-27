<?php
// schedule/add_schedule.php
session_start();
require __DIR__ . '/../includes/db.php';

// Only admin can add schedules
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "Access denied. Admins only.";
    exit;
}

$errors = [];
$defaultDate = $_GET['date'] ?? date('Y-m-d');

// Fetch employees
$empStmt = $pdo->query("SELECT employee_id, name, role FROM employees ORDER BY name");
$employees = $empStmt->fetchAll();

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
        $sql = "
            INSERT INTO schedules (employee_id, shift_date, shift_start, shift_end, notes)
            VALUES (:employee_id, :shift_date, :shift_start, :shift_end, :notes)
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':employee_id' => $employee_id,
            ':shift_date'  => $shift_date,
            ':shift_start' => $shift_start,
            ':shift_end'   => $shift_end,
            ':notes'       => $notes
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
    <title>Add Staff Schedule</title>
    <link rel="stylesheet" href="schedule.css">
</head>
<body>

<div class="form-wrapper">
    <h1>Add Staff Schedule</h1>

    <div class="back-links">
        <a href="calendar.php">&laquo; Back to Calendar</a>
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
                    <option value="<?php echo (int)$emp['employee_id']; ?>">
                        <?php
                        echo htmlspecialchars($emp['name'] . ' (' . $emp['role'] . ')');
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Shift Date</label>
            <input type="date" name="shift_date" value="<?php echo htmlspecialchars($defaultDate); ?>" required>
        </div>

        <div class="form-group">
            <label>Shift Start Time</label>
            <input type="time" name="shift_start" required>
        </div>

        <div class="form-group">
            <label>Shift End Time</label>
            <input type="time" name="shift_end" required>
        </div>

        <div class="form-group">
            <label>Notes (optional)</label>
            <textarea name="notes" rows="3"></textarea>
        </div>

        <div class="button-row">
            <button type="submit">Save Schedule</button>
        </div>
    </form>
</div>

</body>
</html>
