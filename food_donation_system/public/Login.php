<?php
session_start();

require_once "../config/Database.php";
require_once "../repositories/PdoUserRepository.php";
require_once "../services/AuthService.php";

$db = (new Database())->connect();
$authService = new AuthService(new PdoUserRepository($db));

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $authService->login($email, $password);

    if ($user) {

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['role']    = $user->getRole();

        switch ($user->getRole()) {
            case 'admin':
                header("Location: ../admin/dashboard.php");
                break;
            case 'donor':
                header("Location: ../donor/dashboard.php");
                break;
            case 'ngo':
                header("Location: ../ngo/dashboard.php");
                break;
        }
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>



<!DOCTYPE html>
<html>

<head>
    <title>Login - Food Donation System</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>
    <nav class="top-nav">
        <div class="nav-brand">Food Donation & Distribution System</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="register.php" class="btn-register-nav">Register</a>
        </div>
    </nav>

    <div class="auth-container">
        <div class="auth-card">
            <h2>Login</h2>

            <?php if (isset($error)): ?>
                <div class="popup error">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="auth-btn">Login</button>
            </form>

            <div class="auth-link">
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </div>
        </div>
    </div>
</body>

</html>