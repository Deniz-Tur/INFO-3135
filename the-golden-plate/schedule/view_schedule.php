<?php
// schedule/view_schedule.php
session_start();
require __DIR__ . '/../includes/db.php';

$date = $_GET['date'] ?? date('Y-m-d');

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    $date = date('Y-m-d');
}

$sql = "
    SELECT s.*, e.name AS employee_name, e.role AS employee_role
    FROM schedules s
    JOIN employees e ON s.employee_id = e.employee_id
    WHERE s.shift_date = :date
    ORDER BY s.shift_start
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':date' => $date]);
$shifts = $stmt->fetchAll();

$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule for <?php echo htmlspecialchars($date); ?></title>
    <link rel="stylesheet" href="schedule.css">
</head>
<body>

<div class="form-wrapper">
    <h1>Schedule for <?php echo htmlspecialchars($date); ?></h1>

    <div class="back-links">
        <a href="calendar.php">&laquo; Back to Calendar</a>
        <?php if ($isAdmin): ?>
            | <a href="add_schedule.php?date=<?php echo htmlspecialchars($date); ?>">➕ Add Schedule</a>
        <?php endif; ?>
    </div>

    <?php if (empty($shifts)): ?>
        <p style="margin-top:15px;">No shifts for this day.</p>
    <?php else: ?>
        <table class="schedule-table" style="margin-top:20px;">
            <thead>
            <tr>
                <th>Employee</th>
                <th>Role</th>
                <th>Start</th>
                <th>End</th>
                <th>Notes</th>
                <?php if ($isAdmin): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($shifts as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s['employee_name']); ?></td>
                    <td><?php echo htmlspecialchars($s['employee_role']); ?></td>
                    <td><?php echo htmlspecialchars(substr($s['shift_start'], 0, 5)); ?></td>
                    <td><?php echo htmlspecialchars(substr($s['shift_end'], 0, 5)); ?></td>
                    <td><?php echo htmlspecialchars($s['notes']); ?></td>
                    <?php if ($isAdmin): ?>
                        <td>
                            <a class="link-btn" href="edit_schedule.php?id=<?php echo (int)$s['schedule_id']; ?>">Edit</a>
                            |
                            <a class="link-btn delete" href="delete_schedule.php?id=<?php echo (int)$s['schedule_id']; ?>"
                               onclick="return confirm('Are you sure you want to delete this schedule?');">
                                Delete
                            </a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
