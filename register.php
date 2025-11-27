<?php
// register.php
$activeTab = 'register';
require 'db.php';
include 'header.php';

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // Validation
    if ($fullName === '') {
        $errors[] = 'Full name is required.';
    }
    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Password and confirmation do not match.';
    }

    if (empty($errors)) {
        // Check if email already exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'An account with this email already exists.';
        } else {
            // Create customer user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                'INSERT INTO users (full_name, email, password_hash, role) 
                 VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$fullName, $email, $hash, 'customer']);
            $successMessage = 'Registration successful! You can now log in.';
        }
    }
}
?>

<h2 class="app-section-title">Customer Registration</h2>
<p class="app-section-subtitle">
    Create an account to make reservations at Golden Plate.
</p>

<?php if (!empty($errors)): ?>
    <div class="card" style="border-left: 4px solid #c0392b;">
        <h3 class="card-title">There were some problems:</h3>
        <ul class="card-text" style="margin-left: 18px; list-style: disc;">
            <?php foreach ($errors as $err): ?>
                <li><?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($successMessage): ?>
    <div class="card" style="border-left: 4px solid #27ae60;">
        <p class="card-text">
            <?php echo htmlspecialchars($successMessage); ?>
            <a href="login.php" class="btn btn-primary" style="margin-left:10px;">Go to Login</a>
        </p>
    </div>
<?php endif; ?>

<div class="card">
    <h3 class="card-title">Register</h3>

    <form action="register.php" method="post" style="margin-top: 12px;">
        <div style="margin-bottom: 10px;">
            <label>Full Name</label><br>
            <input type="text" name="full_name"
                   value="<?php echo isset($fullName) ? htmlspecialchars($fullName) : ''; ?>"
                   required
                   style="padding: 6px 10px; width: 100%; max-width: 350px; border-radius: 6px; border: 1px solid #ccc;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Email</label><br>
            <input type="email" name="email"
                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                   required
                   style="padding: 6px 10px; width: 100%; max-width: 350px; border-radius: 6px; border: 1px solid #ccc;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Password</label><br>
            <input type="password" name="password" required
                   style="padding: 6px 10px; width: 100%; max-width: 250px; border-radius: 6px; border: 1px solid #ccc;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Confirm Password</label><br>
            <input type="password" name="confirm_password" required
                   style="padding: 6px 10px; width: 100%; max-width: 250px; border-radius: 6px; border: 1px solid #ccc;">
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<?php
include 'includes/footer.php';
?>
