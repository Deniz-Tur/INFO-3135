<?php
session_start();

$activeTab = 'customer_dashboard';
require 'includes/db.php';
include 'includes/header.php';

// Only customers can view this
if (($_SESSION['user_role'] ?? '') !== 'customer') {
    echo '<div class="card" style="border-left:4px solid #c0392b;">
            <p class="card-text">Access denied. Customers only.</p>
          </div>';
    include 'includes.footer.php';
    exit;
}
?>

<h2 class="app-section-title">Customer Dashboard</h2>
<p class="app-section-subtitle">
    Book tables, view your reservations, and check upcoming events.
</p>

<div class="card">
    <h3 class="card-title">Make a Reservation</h3>
    <p class="card-text">Choose your table, date, and time.</p>
    <a href="reservations.php" class="btn btn-primary">Reserve a Table</a>
</div>

<div class="card">
    <h3 class="card-title">My Reservations</h3>
    <p class="card-text">View or manage your existing bookings.</p>
    <a href="my_reservations.php" class="btn btn-outline">View My Reservations</a>
</div>

<div class="card">
    <h3 class="card-title">Events Calendar</h3>
    <p class="card-text">Check restaurant events and busy days.</p>
    <a href="schedule/calendar.php" class="btn btn-outline">🎉 View Events Calendar</a>
</div>

<?php include 'includes/footer.php'; ?>
