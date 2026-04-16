<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ngo') {
    header("Location: ../public/login.php");
    exit();
}

require_once "../config/Database.php";
require_once "../repositories/PdoRequestRepository.php";
require_once "../repositories/PdoDonationRepository.php";
require_once "../services/RequestService.php";
require_once "../services/DonationService.php";

$db = (new Database())->connect();

$requestService  = new RequestService(new PdoRequestRepository($db));
$donationService = new DonationService(new PdoDonationRepository($db));

if (isset($_GET['donation_id'])) {
    $donationId = (int)$_GET['donation_id'];

    $requestService->createRequest($_SESSION['user_id'], $donationId);
    $donationService->markDonationRequested($donationId);

    $_SESSION['success'] = "Request sent successfully!";
}

header("Location: dashboard.php");
exit();
