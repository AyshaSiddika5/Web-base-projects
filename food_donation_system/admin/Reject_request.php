<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

require_once "../config/Database.php";
require_once "../repositories/PdoRequestRepository.php";
require_once "../services/RequestService.php";

$db = (new Database())->connect();
$requestService = new RequestService(new PdoRequestRepository($db));

if (isset($_GET['request_id'])) {
    try {
        $requestService->rejectRequest((int)$_GET['request_id']);
        $_SESSION['success'] = "Request rejected!";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

header("Location: dashboard.php");
exit();
