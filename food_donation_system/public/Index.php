<?php

set_exception_handler(function ($e) {
    error_log($e->getMessage());
    echo "Something went wrong. Please try again later.";
});

session_start();


// Redirect logged-in users to their dashboards
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'donor':
            header("Location: ../donor/dashboard.php");
            exit();
        case 'ngo':
            header("Location: ../ngo/dashboard.php");
            exit();
        case 'admin':
            header("Location: ../admin/dashboard.php");
            exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Food Donation & Distribution System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay">
            <h1>Welcome to the Food Donation & Distribution System</h1>
            <p>Donate food, help NGOs, and volunteer for deliveries!</p>
            <div class="cta-buttons">
                <a class="btn btn-primary" href="register.php">Register</a>
                <a class="btn btn-secondary" href="login.php">Login</a>
            </div>
        </div>
    </section>


    <!-- Stats Section -->
    <section class="stats">
        <div class="stat-box">
            <h3>1,200+</h3>
            <p>Food Donations</p>
        </div>

        <div class="stat-box">
            <h3>350+</h3>
            <p>Active Donors</p>
        </div>

        <div class="stat-box">
            <h3>75+</h3>
            <p>Partner NGOs</p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <h2>How It Works</h2>
        <div class="feature-cards">
            <div class="card">
                <h3>Donors</h3>
                <p>Donate leftover food easily and help those in need.</p>
            </div>
            <div class="card">
                <h3>NGOs</h3>
                <p>Request food donations for your community programs.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <h2>What Our Users Say</h2>
        <div class="testimonial-cards">
            <div class="testimonial">
                <p>"Donating food has never been easier! I love this platform."</p>
                <span>- Mahfuz, Donor</span>
            </div>
            <div class="testimonial">
                <p>"Volunteers are prompt and efficient. Great experience."</p>
                <span>- NGO Worker, Nourish Bangladesh</span>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="public-footer">
        <p>&copy; <?php echo date("Y"); ?> Food Donation & Distribution System</p>
    </footer>

</body>

</html>