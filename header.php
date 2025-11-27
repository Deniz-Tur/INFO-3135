<?php
// Simple helper to mark the active tab
if (!isset($activeTab)) {
    $activeTab = 'home';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Golden Plate - Scheduling App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-wrapper">
    <div class="app-card">
        <header class="app-header">
            <div>
                <h1 class="app-title">
                    <span class="logo-icon">🍽</span>
                    Golden Plate
                </h1>
                <p class="app-subtitle">
                    Restaurant Reservation & Scheduling System
                </p>
            </div>
            <div class="app-header-right">
                <p class="tagline">Smooth reservations. Happy customers.</p>
                
            </div>
        </header>

        <nav class="tabs-nav">
            <a href="index.php"
               class="tab-link <?php echo $activeTab === 'home' ? 'active' : ''; ?>">
                <span class="tab-icon">🏠</span> Home
            </a>
            <a href="reservations.php"
               class="tab-link <?php echo $activeTab === 'reservations' ? 'active' : ''; ?>">
                <span class="tab-icon">📅</span> Make Reservation
            </a>
            <a href="my_reservations.php"
               class="tab-link <?php echo $activeTab === 'my_reservations' ? 'active' : ''; ?>">
                <span class="tab-icon">🧾</span> My Reservations
            </a>
            <a href="admin_dashboard.php"
               class="tab-link <?php echo $activeTab === 'admin' ? 'active' : ''; ?>">
                <span class="tab-icon">🛠</span> Admin Dashboard
            </a>
            <a href="contact.php"
               class="tab-link <?php echo $activeTab === 'contact' ? 'active' : ''; ?>">
                <span class="tab-icon">☎️</span> Contact
            </a>
        </nav>

        <main class="app-content">
