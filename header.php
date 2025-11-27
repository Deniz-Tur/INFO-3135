<?php
// includes/header.php

// Start session before any HTML
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper to know which tab is active
if (!isset($activeTab)) {
    $activeTab = 'home';
}

// Shortcuts
$userId   = $_SESSION['user_id']   ?? null;
$userName = $_SESSION['user_name'] ?? null;
$userRole = $_SESSION['user_role'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Golden Plate - Scheduling App</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
                    Restaurant Reservation &amp; Scheduling System
                </p>
            </div>
            <div class="app-header-right">
                <?php if ($userId): ?>
                    <p class="tagline">Welcome, <?php echo htmlspecialchars($userName); ?>!</p>
                    <p class="muted">
                        Role: <?php echo htmlspecialchars($userRole); ?>
                    </p>
                <?php else: ?>
                    <p class="tagline">Smooth reservations. Happy customers.</p>
                    <p class="muted">Please register or log in.</p>
                <?php endif; ?>
            </div>
        </header>

        <nav class="tabs-nav">
            <a href="index.php"
               class="tab-link <?php echo $activeTab === 'home' ? 'active' : ''; ?>">
                <span class="tab-icon">🏠</span> Home
            </a>

            <?php if ($userRole === 'customer'): ?>
                <a href="reservations.php"
                   class="tab-link <?php echo $activeTab === 'reservations' ? 'active' : ''; ?>">
                    <span class="tab-icon">📅</span> Make Reservation
                </a>
                <a href="my_reservations.php"
                   class="tab-link <?php echo $activeTab === 'my_reservations' ? 'active' : ''; ?>">
                    <span class="tab-icon">🧾</span> My Reservations
                </a>
            <?php endif; ?>

            <?php if ($userRole === 'admin'): ?>
                <a href="admin_dashboard.php"
                   class="tab-link <?php echo $activeTab === 'admin' ? 'active' : ''; ?>">
                    <span class="tab-icon">🛠</span> Admin Dashboard
                </a>
            <?php endif; ?>

            <a href="contact.php"
               class="tab-link <?php echo $activeTab === 'contact' ? 'active' : ''; ?>">
                <span class="tab-icon">☎️</span> Contact
            </a>

            <?php if (!$userId): ?>
                <a href="register.php"
                   class="tab-link <?php echo $activeTab === 'register' ? 'active' : ''; ?>">
                    <span class="tab-icon">📝</span> Register
                </a>
                <a href="login.php"
                   class="tab-link <?php echo $activeTab === 'login' ? 'active' : ''; ?>">
                    <span class="tab-icon">🔐</span> Login
                </a>
            <?php else: ?>
                <a href="logout.php" class="tab-link">
                    <span class="tab-icon">🚪</span> Logout
                </a>
            <?php endif; ?>
        </nav>

        <main class="app-content">
