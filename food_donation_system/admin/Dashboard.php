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

$status     = $_GET['status'] ?? '';
$donationId = $_GET['donation_id'] ?? '';
$ngoId      = $_GET['ngo_id'] ?? '';
$sort       = $_GET['sort'] ?? '';

$allRequests = $requestService->getAllRequests();
$filteredRequests = $allRequests ?: [];


if ($status !== '') {
    $filteredRequests = array_filter($filteredRequests, fn($r) => $r->getStatus() === $status);
}


if ($donationId !== '') {
    $filteredRequests = array_filter($filteredRequests, fn($r) => $r->getDonationId() == $donationId);
}


if ($ngoId !== '') {
    $filteredRequests = array_filter($filteredRequests, fn($r) => $r->getNgoId() == $ngoId);
}


if ($sort === 'latest') {
    usort($filteredRequests, fn($a, $b) => $b->getId() <=> $a->getId());
} elseif ($sort === 'oldest') {
    usort($filteredRequests, fn($a, $b) => $a->getId() <=> $b->getId());
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="popup success">
            <?= $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="popup error">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>


    <div class="navbar">
        <div class="logo">ADMIN DASHBOARD</div>
        <div class="nav-links">
            <a href="../public/logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-error">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h3>Search, Filter & Sort Requests</h3>

            <form method="get" class="filter-form">

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All</option>
                        <option value="pending" <?= ($status == 'pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= ($status == 'approved') ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= ($status == 'rejected') ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Donation ID</label>
                    <input type="text" name="donation_id" value="<?= htmlspecialchars($donationId) ?>" placeholder="Enter ID">
                </div>

                <div class="form-group">
                    <label>NGO ID</label>
                    <input type="text" name="ngo_id" value="<?= htmlspecialchars($ngoId) ?>" placeholder="Enter ID">
                </div>

                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort">
                        <option value="">Default</option>
                        <option value="latest" <?= ($sort == 'latest') ? 'selected' : '' ?>>Latest First</option>
                        <option value="oldest" <?= ($sort == 'oldest') ? 'selected' : '' ?>>Oldest First</option>
                    </select>
                </div>

                <div class="form-group button-group">
                    <button type="submit">Apply Filters</button>
                </div>

            </form>
        </div>

        <div class="stats-container">

            <div class="stat-box">
                <h4>Total Requests</h4>
                <p><?= count($allRequests) ?></p>
            </div>

            <div class="stat-box pending">
                <h4>Pending</h4>
                <p><?= count(array_filter($allRequests, fn($r) => $r->getStatus() == "pending")) ?></p>
            </div>

            <div class="stat-box approved">
                <h4>Approved</h4>
                <p><?= count(array_filter($allRequests, fn($r) => $r->getStatus() == "approved")) ?></p>
            </div>

            <div class="stat-box rejected">
                <h4>Rejected</h4>
                <p><?= count(array_filter($allRequests, fn($r) => $r->getStatus() == "rejected")) ?></p>
            </div>

        </div>

        <div class="table-card">
            <h3>Manage All Requests</h3>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Req ID</th>
                            <th>Donation ID</th>
                            <th>NGO ID</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($filteredRequests)): ?>
                            <?php foreach ($filteredRequests as $request): ?>
                                <tr>
                                    <td><?= $request->getId() ?></td>
                                    <td><?= $request->getDonationId() ?></td>
                                    <td><?= $request->getNgoId() ?></td>
                                    <td>
                                        <span class="status-badge status-<?= $request->getStatus() ?>">
                                            <?= ucfirst($request->getStatus()) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($request->getStatus() == 'pending'): ?>
                                            <a class="btn-approve" href="approve_request.php?request_id=<?= $request->getId() ?>">Approve</a>
                                            <a class="btn-reject" href="reject_request.php?request_id=<?= $request->getId() ?>">Reject</a>
                                        <?php else: ?>
                                            <span class="no-action">No Action</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>

</html>