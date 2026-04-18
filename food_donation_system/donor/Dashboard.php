<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: ../public/login.php");
    exit();
}

require_once "../config/Database.php";
require_once "../repositories/PdoDonationRepository.php";
require_once "../services/DonationService.php";

$db = (new Database())->connect();
$donationService = new DonationService(new PdoDonationRepository($db));

$myDonations = $donationService->getDonationsByDonor($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <title>Donor Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="popup success">
            <?= $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>


    <div class="navbar">
        <div class="logo">DONOR DASHBOARD</div>
        <div class="nav-links">
            <a href="../public/logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h3>Add New Donation</h3>
            <form method="POST" action="add_donation.php">
                <div style="margin-bottom:10px;">
                    <label style="display:inline-block; width:150px;">Food Type:</label>
                    <input type="text" name="food_type" required style="width:250px;">
                </div>

                <div style="margin-bottom:10px;">
                    <label style="display:inline-block; width:150px;">Quantity:</label>
                    <input type="text" name="quantity" required style="width:250px;">
                </div>

                <div style="margin-bottom:10px;">
                    <label style="display:inline-block; width:150px;">Pickup Location:</label>
                    <input type="text" name="pickup_location" required style="width:250px;">
                </div>

                <div style="margin-bottom:10px;">
                    <label style="display:inline-block; width:150px;">Expiry Time:</label>
                    <input type="date" name="expiry_time" required style="width:250px;">
                </div>

                <button type="submit">Add Donation</button>
            </form>
        </div>

        <hr>

        <div class="table-card">
            <h3>My Donations</h3>
            <table>
                <thead>
                    <tr>
                        <th>Food Type</th>
                        <th>Quantity</th>
                        <th>Pickup Location</th>
                        <th>Expiry Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($myDonations as $donation): ?>
                        <tr>
                            <td><?= htmlspecialchars($donation->getFoodType()) ?></td>
                            <td><?= htmlspecialchars($donation->getQuantity()) ?></td>
                            <td><?= htmlspecialchars($donation->getPickupLocation()) ?></td>
                            <td><?= htmlspecialchars(substr($donation->getExpiryTime(), 0, 10)) ?></td>
                            <td><span class="status-<?= strtolower($donation->getStatus()) ?>"><?= htmlspecialchars($donation->getStatus()) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

</body>

</html>