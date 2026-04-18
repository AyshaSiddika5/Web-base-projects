<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ngo') {
    header("Location: ../public/login.php");
    exit();
}

require_once "../config/Database.php";
require_once "../repositories/PdoDonationRepository.php";
require_once "../repositories/PdoRequestRepository.php";
require_once "../services/DonationService.php";
require_once "../services/RequestService.php";

$db = (new Database())->connect();

$donationService = new DonationService(new PdoDonationRepository($db));
$requestService  = new RequestService(new PdoRequestRepository($db));


$search = trim($_GET['search'] ?? '');
$expiry = $_GET['expiry_after'] ?? '';
$sort   = $_GET['sort'] ?? '';



$donations = $donationService->getAvailableDonations();


if ($search !== '') {
    $donations = array_filter($donations, function ($donation) use ($search) {
        return stripos($donation->getFoodType(), $search) !== false;
    });
}


if ($expiry !== '') {
    $expiryTimestamp = strtotime($expiry);
    $donations = array_filter($donations, function ($donation) use ($expiryTimestamp) {
        return strtotime(substr($donation->getExpiryTime(), 0, 10)) >= $expiryTimestamp;
    });
}


if ($sort === 'latest') {
    usort($donations, function ($a, $b) {
        return $b->getId() <=> $a->getId();
    });
}


$myRequests = $requestService->getRequestsWithDonationDetailsByNgo($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>

<head>
    <title>NGO Dashboard</title>
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
        <div class="logo">NGO DASHBOARD</div>
        <div class="nav-links">
            <a href="../public/logout.php" class="logout-btn">Logout</a>
        </div>
    </div>


    <div class="container">
        <div class="card">
            <h3>Search & Filter Donations</h3>
            <form method="get">
                <label>Food Type:</label>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="e.g. Rice">

                <label>Expiry After:</label>
                <input type="date" name="expiry_after" value="<?= htmlspecialchars($expiry) ?>">

                <label>Sort:</label>
                <select name="sort">
                    <option value="">Default</option>
                    <option value="latest" <?= $sort === 'latest' ? 'selected' : '' ?>>Latest First</option>
                </select>

                <button type="submit">Apply Filters</button>
            </form>
        </div>

        <div class="table-card">
            <h3>Available Donations</h3>
            <?php if (count($donations) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Food Type</th>
                            <th>Quantity</th>
                            <th>Pickup Address</th>
                            <th>Expiry</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($donations as $donation): ?>
                            <tr>
                                <td><?= htmlspecialchars($donation->getFoodType()) ?></td>
                                <td><?= htmlspecialchars($donation->getQuantity()) ?></td>
                                <td><?= htmlspecialchars($donation->getPickupLocation()) ?></td>
                                <td><?= htmlspecialchars(substr($donation->getExpiryTime(), 0, 10)) ?></td>
                                <td>
                                    <a href="request_donation.php?donation_id=<?= $donation->getId() ?>"
                                        style="background:#2ecc71; color:white; padding:5px 10px; text-decoration:none; border-radius:4px; font-size:12px;">
                                        Request
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
    <?php else: ?>
        <p class="card">No donations found matching your search/filter criteria.</p>
    <?php endif; ?>

    <div class="table-card">
        <h3>My Requests</h3>
        <table>

            <?php if (count($myRequests) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Food Type</th>
                            <th>Quantity</th>
                            <th>Pickup Address</th>
                            <th>Expiry</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($myRequests as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['food_type']) ?></td>
                                <td><?= htmlspecialchars($row['quantity']) ?></td>
                                <td><?= htmlspecialchars($row['pickup_location']) ?></td>
                                <td><?= htmlspecialchars(substr($row['expiry_time'], 0, 10)) ?></td>
                                <td><span class="status-<?= strtolower($row['status']) ?>"><?= htmlspecialchars(ucfirst($row['status'])) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </table>
    </div>
<?php else: ?>
    <p class="card">You have not requested any donations yet.</p>
<?php endif; ?>
    </div>

</body>

</html>