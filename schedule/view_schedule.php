<?php
include("../db.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get date from URL
$date = $_GET['date'] ?? null;
if (!$date) {
    die("No date provided.");
}

// Extract year + month for the "Back to Month" button
$year  = date("Y", strtotime($date));
$month = date("m", strtotime($date));

// Fetch schedules for that day
$sql = "
    SELECT schedules.*, employees.name, employees.role
    FROM schedules
    JOIN employees ON schedules.employee_id = employees.employee_id
    WHERE shift_date = '$date'
    ORDER BY shift_start ASC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Schedule for <?= $date ?></title>
    <link rel="stylesheet" href="schedule.css">
</head>
<body>

<h1>Employee Schedule for <?= $date ?></h1>

<!-- Buttons -->
<div class="actions-row">
    <a class="btn" href="add_schedule.php?date=<?= $date ?>">➕ Add New Shift</a>
    <a class="btn" href="calendar.php?year=<?= $year ?>&month=<?= $month ?>">📅 View Full Month</a>
</div>

<table class="schedule-table">
    <tr>
        <th>Employee</th>
        <th>Role</th>
        <th>Start</th>
        <th>End</th>
        <th>Notes</th>
        <th>Actions</th>
    </tr>

    <?php if ($result->num_rows === 0): ?>
        <tr><td colspan="6" style="text-align:center;">No schedules for today.</td></tr>
    <?php endif; ?>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['role'] ?></td>
            <td><?= date("H:i", strtotime($row['shift_start'])) ?></td>
            <td><?= date("H:i", strtotime($row['shift_end'])) ?></td>
            <td><?= $row['notes'] ?></td>

            <td>
                <a class="btn" href="edit_schedule.php?id=<?= $row['schedule_id'] ?>">✏️ Edit</a>
                <a class="btn delete" href="delete_schedule.php?id=<?= $row['schedule_id'] ?>" 
                   onclick="return confirm('Are you sure?');">🗑 Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
