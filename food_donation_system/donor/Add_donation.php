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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donor_id = $_SESSION['user_id'];
    $food_type = trim($_POST['food_type']);
    $quantity = trim($_POST['quantity']);
    $pickup_location = trim($_POST['pickup_location']);
    $expiry_time = $_POST['expiry_time'];


    if ($donationService->createDonation($donor_id, $food_type, $quantity, $pickup_location, $expiry_time)) {
        $_SESSION['success'] = "Donation added successfully!";
        header("Location: dashboard.php");
        exit();
    }
} else {
    echo "Failed to add donation.";
}
