<?php
// schedule/calendar.php
session_start();
require __DIR__ . '/../includes/db.php';

// Determine year & month
$year  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');

if ($year < 1970 || $year > 2100) {
    $year = (int)date('Y');
}
if ($month < 1 || $month > 12) {
    $month = (int)date('n');
}

$firstOfMonth = new DateTime("$year-$month-01");
$daysInMonth  = (int)$firstOfMonth->format('t');
$startWeekday = (int)$firstOfMonth->format('w'); // 0 (Sun) - 6 (Sat)
$monthName    = $firstOfMonth->format('F Y');

// Compute prev / next month
$prev = clone $firstOfMonth;
$prev->modify('-1 month');
$next = clone $firstOfMonth;
$next->modify('+1 month');

// Fetch schedules for this whole month
$monthStart = $firstOfMonth->format('Y-m-01');
$monthEnd   = $firstOfMonth->format('Y-m-t');

$sql = "
    SELECT s.*, e.name AS employee_name, e.role AS employee_role
    FROM schedules s
    JOIN employees e ON s.employee_id = e.employee_id
    WHERE s.shift_date BETWEEN :start AND :end
    ORDER BY s.shift_date, s.shift_start
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':start' => $monthStart,
    ':end'   => $monthEnd
]);
$schedules = $stmt->fetchAll();

// Group by date
$shiftsByDate = [];
foreach ($schedules as $row) {
    $d = $row['shift_date'];
    if (!isset($shiftsByDate[$d])) {
        $shiftsByDate[$d] = [];
    }
    $shiftsByDate[$d][] = $row;
}

// Today
$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Schedule Calendar - <?php echo htmlspecialchars($monthName); ?></title>
    <link rel="stylesheet" href="schedule.css">
</head>
<body>

<h1>Staff Schedule Calendar</h1>

<div class="calendar-nav">
    <a href="calendar.php?year=<?php echo $prev->format('Y'); ?>&month=<?php echo $prev->format('n'); ?>">
        &laquo; Previous
    </a>
    <strong><?php echo htmlspecialchars($monthName); ?></strong>
    <a href="calendar.php?year=<?php echo $next->format('Y'); ?>&month=<?php echo $next->format('n'); ?>">
        Next &raquo;
    </a>
</div>

<div class="calendar-container">
    <!-- Weekday header -->
    <div class="calendar-grid weekdays">
        <div>Sun</div>
        <div>Mon</div>
        <div>Tue</div>
        <div>Wed</div>
        <div>Thu</div>
        <div>Fri</div>
        <div>Sat</div>
    </div>

    <!-- Days -->
    <div class="calendar-grid">
        <?php
        // empty boxes before first day
        for ($i = 0; $i < $startWeekday; $i++) {
            echo '<div class="day-box empty"></div>';
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $isToday = ($dateStr === $today);

            $classes = 'day-box';
            if ($isToday) {
                $classes .= ' today';
            }

            echo '<div class="' . $classes . '">';

            echo '<div class="day-number">' . $day . '</div>';

            // Show shifts
            if (!empty($shiftsByDate[$dateStr])) {
                foreach ($shiftsByDate[$dateStr] as $shift) {
                    $start = substr($shift['shift_start'], 0, 5);
                    $end   = substr($shift['shift_end'], 0, 5);
                    $emp   = htmlspecialchars($shift['employee_name']);
                    echo '<div class="shift-box">';
                    echo $emp . '<br>' . $start . ' - ' . $end;
                    echo '</div>';
                }
            }

            // Link to full list for this day
            echo '<div style="margin-top:6px; font-size:11px;">';
            echo '<a href="view_schedule.php?date=' . $dateStr . '">View day &raquo;</a>';
            echo '</div>';

            echo '</div>';
        }
        ?>
    </div>
</div>

<div class="calendar-nav" style="margin-top:20px;">
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="add_schedule.php">➕ Add New Schedule</a>
    <?php endif; ?>
</div>

</body>
</html>
