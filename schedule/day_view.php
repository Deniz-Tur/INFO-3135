<link rel="stylesheet" href="schedule.css">

<?php
include("../db.php");

$date = $_GET['date'];

$sql = "
    SELECT schedules.*, employees.name, employees.department 
    FROM schedules 
    JOIN employees ON schedules.employee_id = employees.employee_id
    WHERE shift_date = '$date'
    ORDER BY department, shift_start
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Schedule for <?= $date ?></title>
    <style>
        body { font-family: Arial; margin: 20px; }
        h1 { margin-bottom: 20px; }
        .employee-box {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>

<body>

<h1>Schedule for <?= $date ?></h1>

<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="employee-box">
            <strong><?= $row['name'] ?></strong> (<?= $row['department'] ?>)<br>
            Shift: <?= $row['shift_start'] ?> - <?= $row['shift_end'] ?><br>
            Notes: <?= $row['notes'] ?: "None" ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No shifts for this day.</p>
<?php endif; ?>

<p><a href="calendar.php">← Back to Calendar</a></p>

</body>
</html>
