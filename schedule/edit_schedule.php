<?php
include("../db.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Schedule ID from URL
$id = $_GET['id'] ?? null;
if (!$id) {
    die("No schedule ID provided.");
}

// Fetch schedule info
$schedule_sql = "
    SELECT * FROM schedules 
    WHERE schedule_id = $id
";
$schedule_result = $conn->query($schedule_sql);
$schedule = $schedule_result->fetch_assoc();
if (!$schedule) {
    die("Schedule not found.");
}

// Fetch all employees (for dropdown)
$employee_sql = "SELECT * FROM employees ORDER BY name ASC";
$employees = $conn->query($employee_sql);

// Extract year + month for back buttons
$year  = date("Y", strtotime($schedule['shift_date']));
$month = date("m", strtotime($schedule['shift_date']));

// Save changes
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employee_id = $_POST['employee_id'];
    $shift_date  = $_POST['shift_date'];
    $shift_start = $_POST['shift_start'];
    $shift_end   = $_POST['shift_end'];
    $notes       = $_POST['notes'];

    $update_sql = "
        UPDATE schedules SET
            employee_id = '$employee_id',
            shift_date  = '$shift_date',
            shift_start = '$shift_start',
            shift_end   = '$shift_end',
            notes       = '$notes'
        WHERE schedule_id = $id
    ";

    if ($conn->query($update_sql)) {
        header("Location: view_schedule.php?date=" . $shift_date);
        exit;
    } else {
        echo "Error updating schedule: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Schedule</title>
    <link rel="stylesheet" href="schedule.css">
</head>
<body>

<div class="form-wrapper">

    <h1>Edit Schedule for <?= $schedule['shift_date'] ?></h1>

    <form action="" method="POST">

        <div class="form-group">
            <label>Employee:</label>
            <select name="employee_id" required>
                <?php while ($emp = $employees->fetch_assoc()): ?>
                    <option value="<?= $emp['employee_id'] ?>"
                        <?= ($emp['employee_id'] == $schedule['employee_id']) ? "selected" : "" ?>>
                        <?= $emp['name'] ?> (<?= $emp['role'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Date:</label>
            <input type="date" name="shift_date" value="<?= $schedule['shift_date'] ?>" required>
        </div>

        <div class="form-group">
            <label>Start Time:</label>
            <input type="time" name="shift_start" value="<?= substr($schedule['shift_start'], 0, 5) ?>" required>
        </div>

        <div class="form-group">
            <label>End Time:</label>
            <input type="time" name="shift_end" value="<?= substr($schedule['shift_end'], 0, 5) ?>" required>
        </div>

        <div class="form-group">
            <label>Notes:</label>
            <textarea name="notes" rows="3"><?= $schedule['notes'] ?></textarea>
        </div>

        <div class="button-row">
            <button type="submit">💾 Save Changes</button>
        </div>
    </form>

    <div class="back-links">
        <a href="view_schedule.php?date=<?= $schedule['shift_date'] ?>">⬅ Back to Day Schedule</a>
        <a href="calendar.php?year=<?= $year ?>&month=<?= $month ?>">📅 Back to Calendar</a>
    </div>

</div>

</body>
</html>
