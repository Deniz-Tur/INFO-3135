<?php
session_start();
// set working directory to project root
chdir(__DIR__ . '/..');
// include DB + header
require 'includes/db.php';
$activeTab = 'calendar'; // highlight Staff Calendar tab

// ========== HANDLE FORM SUBMISSION ==========
$debug = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'] ?? null;
    $shift_date = $_POST['shift_date'] ?? null;
    $shift_start = $_POST['shift_start'] ?? null;
    $shift_end = $_POST['shift_end'] ?? null;
    $notes = $_POST['notes'] ?? '';

    $debug[] = "Form submitted";
    $debug[] = "Employee ID: $employee_id";
    $debug[] = "Date: $shift_date";
    $debug[] = "Start: $shift_start";
    $debug[] = "End: $shift_end";

    // Validate required fields
    if ($employee_id && $shift_date && $shift_start && $shift_end) {
        try {
            $sql = "INSERT INTO schedules (employee_id, shift_date, shift_start, shift_end, notes) 
                    VALUES (:employee_id, :shift_date, :shift_start, :shift_end, :notes)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                ':employee_id' => $employee_id,
                ':shift_date' => $shift_date,
                ':shift_start' => $shift_start,
                ':shift_end' => $shift_end,
                ':notes' => $notes
            ]);

            $debug[] = "Insert result: " . ($result ? 'SUCCESS' : 'FAILED');
            $debug[] = "Last insert ID: " . $pdo->lastInsertId();

            if ($result) {
                // Redirect back to the schedule view for that date
                header("Location: calendar.php");
                exit;
            }
        } catch (PDOException $e) {
            $error = "Error saving schedule: " . $e->getMessage();
            $debug[] = "Exception: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all required fields.";
        $debug[] = "Validation failed";
    }
}

include 'includes/header.php';
?>
<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="schedule.css">
<!-- Hide original header nav and load custom navigation -->
<style>
    nav.tabs-nav { display: none; }
</style>
<nav class="tabs-nav">
    <a href="/the-golden-plate/index.php" class="tab-link">
        <span class="tab-icon">🏠</span> Home
    </a>
    <a href="/the-golden-plate/admin_dashboard.php" class="tab-link">
        <span class="tab-icon">🛠</span> Admin Dashboard
    </a>
    <a href="/the-golden-plate/admin_reservations.php" class="tab-link">
        <span class="tab-icon">📋</span> Reservations
    </a>
    <a href="/the-golden-plate/schedule/calendar.php" class="tab-link active">
        <span class="tab-icon">📆</span> Staff Calendar
    </a>
    <a href="/the-golden-plate/logout.php" class="tab-link">
        <span class="tab-icon">🚪</span> Logout
    </a>
</nav>
<?php
// fetch employees
$empStmt = $pdo->query("SELECT employee_id, name, role FROM employees ORDER BY name");
$employees = $empStmt->fetchAll();
$defaultDate = $_GET['date'] ?? date('Y-m-d');
?>
<div class="app-wrapper">
    <!-- page header card -->
    <div class="card card-page-header">
        <h2 class="page-title">Add Staff Schedule</h2>
        <a href="calendar.php" class="btn btn-outline back-btn">
            ← Back to Calendar
        </a>
    </div>

    <!-- DEBUG INFO -->
    <?php if (!empty($debug)): ?>
        <div class="card" style="background:#fffacd; border:2px solid #ffa500; margin-bottom:20px;">
            <h3>Debug Info:</h3>
            <ul>
                <?php foreach ($debug as $msg): ?>
                    <li><?= htmlspecialchars($msg) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <!-- form card -->
    <div class="card form-card">
        <?php if (isset($error)): ?>
            <div class="alert alert-error" style="background:#fee; color:#c33; padding:12px; border-radius:8px; margin-bottom:16px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label>Employee</label>
                <select name="employee_id" required>
                    <option value="">-- Select Employee --</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?= $emp['employee_id'] ?>">
                            <?= htmlspecialchars($emp['name'] . " ({$emp['role']})") ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Shift Date</label>
                <input type="date" name="shift_date" value="<?= htmlspecialchars($defaultDate) ?>" required>
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
            <button type="submit" class="btn btn-primary">➕ Save Schedule</button>
        </form>
    </div>
</div>
<?php include 'includes/footer.php'; ?>