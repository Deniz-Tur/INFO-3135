<?php
session_start();
$activeTab = 'home';
include 'includes/header.php';
?>

<h2 class="app-section-title">Welcome to Golden Plate</h2>
<p class="app-section-subtitle">
    Use the navigation above to access your dashboard and features.
</p>

<div class="card">
    <h3 class="card-title">Restaurant Calendar</h3>
    <p class="card-text">
        View staff shifts, events, and general restaurant availability.
    </p>
    <a href="schedule/calendar.php" class="btn btn-outline">
        📆 View Calendar
    </a>
</div>

<?php if (($_SESSION['user_role'] ?? null) === null): ?>
    <div class="card">
        <h3 class="card-title">Get Started</h3>
        <p class="card-text">
            Create an account or log in to make reservations and manage schedules.
        </p>
        <a href="register.php" class="btn btn-primary" style="margin-right: 8px;">📝 Register</a>
        <a href="login.php" class="btn btn-outline">🔐 Login</a>
    </div>
<?php endif; ?>

<?php
include 'includes/footer.php';
?>
