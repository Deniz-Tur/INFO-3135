<link rel="stylesheet" href="schedule.css">

<?php 
include("../db.php");

// Enable error display (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get year and month from URL, default = current month
$year  = isset($_GET['year'])  ? intval($_GET['year'])  : date("Y");
$month = isset($_GET['month']) ? intval($_GET['month']) : date("m");

// Number of days in this month
$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Weekday of the first day (0 = Sunday)
$first_day_week = date('w', strtotime("$year-$month-01"));

// Fetch schedules for this month
$start_date = "$year-$month-01";
$end_date   = "$year-$month-$total_days";

$sql = "
    SELECT schedules.*, employees.name, employees.department, employees.role
    FROM schedules
    JOIN employees ON schedules.employee_id = employees.employee_id
    WHERE shift_date BETWEEN '$start_date' AND '$end_date'
    ORDER BY shift_start ASC
";

$result = $conn->query($sql);

// Group schedules by date
$daily = [];
while ($row = $result->fetch_assoc()) {
    $daily[$row['shift_date']][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Monthly Employee Schedule</title>
    <link rel="stylesheet" href="schedule.css">
</head>
<body>

<h1 class="calendar-title">Monthly Schedule: <?= "$year - $month" ?></h1>

<!-- Month Navigation -->
<div class="calendar-nav">
    <?php
    $prev_month = $month - 1;
    $prev_year = $year;
    if ($prev_month < 1) { $prev_month = 12; $prev_year--; }

    $next_month = $month + 1;
    $next_year = $year;
    if ($next_month > 12) { $next_month = 1; $next_year++; }
    ?>
    <a href="calendar.php?year=<?= $prev_year ?>&month=<?= $prev_month ?>">← Previous</a>
    <a href="calendar.php?year=<?= $next_year ?>&month=<?= $next_month ?>">Next →</a>
</div>

<div class="calendar-container">

    <!-- Weekday Bar -->
    <div class="calendar-grid weekdays">
        <div>Sun</div>
        <div>Mon</div>
        <div>Tue</div>
        <div>Wed</div>
        <div>Thu</div>
        <div>Fri</div>
        <div>Sat</div>
    </div>

    <!-- Calendar Day Boxes -->
    <div class="calendar-grid days">

        <!-- Empty boxes before month start -->
        <?php for ($i = 0; $i < $first_day_week; $i++): ?>
            <div class="day-box empty"></div>
        <?php endfor; ?>

        <!-- Actual days -->
        <?php for ($day = 1; $day <= $total_days; $day++): ?>
            <?php $date = "$year-$month-" . str_pad($day, 2, "0", STR_PAD_LEFT); ?>

            <?php 
                $today = date("Y-m-d"); 
                $is_today = ($date === $today) ? "today" : "";
            ?>
            <div class="day-box <?= $is_today ?>" onclick="location.href='view_schedule.php?date=<?= $date ?>'">

                <div class="day-number clickable">
                    <?= $day ?>
                </div>


                <!-- Shifts -->
                <?php if (!empty($daily[$date])): ?>
                    <?php foreach ($daily[$date] as $shift): ?>

                        <?php 
                            $start = date("H:i", strtotime($shift['shift_start']));
                            $end   = date("H:i", strtotime($shift['shift_end']));
                        ?>

                        <div class="shift-box">
                            <strong><?= $shift['name'] ?> (<?= $shift['role'] ?>)</strong><br>
                            <?= $start ?> - <?= $end ?>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

        <?php endfor; ?>

    </div>
</div>

</body>
</html>
