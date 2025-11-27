<link rel="stylesheet" href="schedule.css">


<?php
// Connect to database
include '../db.php';

// Fetch all employees for the dropdown
$employees = $conn->query("SELECT employee_id, name, role FROM employees ORDER BY name");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $employee_id = $_POST['employee_id'];
    $shift_date = $_POST['shift_date'];
    $shift_start = $_POST['shift_start'];
    $shift_end = $_POST['shift_end'];
    $notes = $_POST['notes'];

    // Insert a new schedule record
    $sql = "INSERT INTO schedules (employee_id, shift_date, shift_start, shift_end, notes)
            VALUES ('$employee_id', '$shift_date', '$shift_start', '$shift_end', '$notes')";

    if ($conn->query($sql) === TRUE) {
        // Redirect user after successful insert
        echo "<script>alert('Schedule added successfully!'); window.location='view_schedule.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee Schedule</title>
</head>
<body>

    <h2>Add New Employee Schedule</h2>

    <!-- Schedule Form -->
    <form method="POST">

        <!-- Employee Selection (Dropdown) -->
        <label>Employee:</label><br>
        <select name="employee_id" required>
            <option value="">-- Select Employee --</option>
            <?php while($row = $employees->fetch_assoc()): ?>
                <option value="<?= $row['employee_id'] ?>">
                    <?= $row['name'] ?> (<?= $row['role'] ?>)
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <!-- Shift Date -->
        <label>Date:</label><br>
        <input type="date" name="shift_date" required>
        <br><br>

        <!-- Start Time -->
        <label>Start Time:</label><br>
        <input type="time" name="shift_start" required>
        <br><br>

        <!-- End Time -->
        <label>End Time:</label><br>
        <input type="time" name="shift_end" required>
        <br><br>

        <!-- Notes (Optional) -->
        <label>Notes (optional):</label><br>
        <textarea name="notes" placeholder="Optional notes..."></textarea>
        <br><br>

        <!-- Submit Button -->
        <button type="submit">Add Schedule</button>

    </form>

</body>
</html>
