<link rel="stylesheet" href="schedule.css">

<?php
include("../db.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure schedule id exists
$id = $_GET['id'] ?? null;
if (!$id) {
    die("No schedule ID provided.");
}

// Find schedule date for redirect
$date_sql = "SELECT shift_date FROM schedules WHERE schedule_id = $id";
$date_result = $conn->query($date_sql);

if ($date_result->num_rows === 0) {
    die("Error: Schedule not found.");
}

$row = $date_result->fetch_assoc();
$shift_date = $row['shift_date'];

// Delete query
$delete_sql = "DELETE FROM schedules WHERE schedule_id = $id";

if ($conn->query($delete_sql)) {
    // Redirect back to the day list
    header("Location: view_schedule.php?date=" . $shift_date);
    exit;
} else {
    echo "Error deleting schedule: " . $conn->error;
}
?>
