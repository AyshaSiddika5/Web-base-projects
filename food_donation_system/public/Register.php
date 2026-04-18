<?php
require_once "../config/Database.php";
require_once "../repositories/PdoUserRepository.php";
require_once "../services/AuthService.php";

$db = (new Database())->connect();
$authService = new AuthService(new PdoUserRepository($db));

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($authService->register($name, $email, $password, $role)) {
        $success = "Registration successful! You can now <a href='login.php'>login</a>.";
    } else {
        $error = "Registration failed! Please try again.";
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Register - Food Donation System</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>
    <nav class="top-nav">
        <div class="nav-brand">Food Donation & Distribution System</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="login.php" class="btn-register-nav" style="background-color: #007bff;">Login</a>
        </div>
    </nav>

    <div class="auth-container">
        <div class="auth-card">
            <h2>Create Account</h2>

            <?php if (!empty($success)): ?>
                <div class="popup success">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="popup error">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>

                <select name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="donor">Donor</option>
                    <option value="ngo">NGO</option>
                </select>

                <button type="submit" class="auth-btn">Register</button>
            </form>

            <div class="auth-link">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</body>

</html>