<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userName = $_SESSION['user_name'] ?? null;
$userRole = $_SESSION['user_role'] ?? null;
$activeTab = $activeTab ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Golden Plate</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app-wrapper">
    <div class="app-card">

        <!-- Header -->
        <header class="app-header">
            <div class="app-title">
                <span class="logo-icon">🍽</span>
                <span>Golden Plate</span>
            </div>

            <div class="app-header-right">
                <?php if ($userName): ?>
                    <div class="tagline">Welcome, <?php echo htmlspecialchars($userName); ?></div>
                <?php else: ?>
                    <div class="tagline">Welcome to Golden Plate Restaurant</div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Navigation -->
        <nav class="tabs-nav">

            <!-- Public / Everyone -->
            <a href="index.php"
               class="tab-link <?php echo $activeTab === 'home' ? 'active' : ''; ?>">
                <span class="tab-icon">🏠</span> Home
            </a>

            <!-- CUSTOMER NAVIGATION -->
            <?php if ($userRole === 'customer'): ?>
                <a href="customer_dashboard.php"
                   class="tab-link <?php echo $activeTab === 'customer_dashboard' ? 'active' : ''; ?>">
                    <span class="tab-icon">👤</span> Dashboard
                </a>

                <a href="booking.php"
                   class="tab-link <?php echo $activeTab === 'reservations' ? 'active' : ''; ?>">
                    <span class="tab-icon">📅</span> Make Reservation
                </a>

                <a href="my_reservations.php"
                   class="tab-link <?php echo $activeTab === 'my_reservations' ? 'active' : ''; ?>">
                    <span class="tab-icon">🧾</span> My Reservations
                </a>

                <a href="schedule/calendar.php"
                   class="tab-link <?php echo $activeTab === 'events_calendar' ? 'active' : ''; ?>">
                    <span class="tab-icon">🎉</span> Events Calendar
                </a>
            <?php endif; ?>

            <!-- ADMIN NAVIGATION -->
            <?php if ($userRole === 'admin'): ?>
                <a href="admin_dashboard.php"
                   class="tab-link <?php echo $activeTab === 'admin' ? 'active' : ''; ?>">
                    <span class="tab-icon">🛠</span> Admin Dashboard
                </a>

                <a href="admin_reservations.php"
                   class="tab-link <?php echo $activeTab === 'admin_reservations' ? 'active' : ''; ?>">
                    <span class="tab-icon">📋</span> Reservations
                </a>

                <a href="schedule/calendar.php"
                   class="tab-link <?php echo $activeTab === 'calendar' ? 'active' : ''; ?>">
                    <span class="tab-icon">📆</span> Staff Calendar
                </a>
            <?php endif; ?>

            <!-- Auth section (right side) -->
            <?php if ($userName): ?>
                <a href="logout.php" class="tab-link">
                    <span class="tab-icon">🚪</span> Logout
                </a>
            <?php else: ?>
                <a href="login.php"
                   class="tab-link <?php echo $activeTab === 'login' ? 'active' : ''; ?>">
                    <span class="tab-icon">🔐</span> Login
                </a>
                <a href="register.php"
                   class="tab-link <?php echo $activeTab === 'register' ? 'active' : ''; ?>">
                    <span class="tab-icon">📝</span> Register
                </a>
            <?php endif; ?>

        </nav>

        <!-- Page Content -->
        <main class="app-content">
