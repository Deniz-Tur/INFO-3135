<?php
// admin_dashboard.php
$activeTab = 'admin';
require 'db.php';
include 'header.php';

// Only admin can see this page
if (($_SESSION['user_role'] ?? '') !== 'admin') {
    echo '<div class="card" style="border-left:4px solid #c0392b;">
            <p class="card-text">Access denied. Admins only.</p>
          </div>';
    include 'includes/footer.php';
    exit;
}

// Fetch all users
$stmt = $pdo->query('SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC');
$users = $stmt->fetchAll();
?>

<h2 class="app-section-title">Admin Dashboard</h2>
<p class="app-section-subtitle">
    View all registered users (customers and admins).
</p>

<div class="card">
    <h3 class="card-title">Registered Users</h3>

    <?php if (!$users): ?>
        <p class="card-text muted">No users found.</p>
    <?php else: ?>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Registered At</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?php echo (int)$u['id']; ?></td>
                    <td><?php echo htmlspecialchars($u['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td>
                        <span class="badge <?php echo $u['role'] === 'admin' ? 'badge-warning' : 'badge-success'; ?>">
                            <?php echo htmlspecialchars($u['role']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($u['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
include 'includes/footer.php';
?>
