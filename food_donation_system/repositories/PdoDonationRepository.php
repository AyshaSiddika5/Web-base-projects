<?php
require_once __DIR__ . '/../classes/Donation.php';
require_once 'DonationRepositoryInterface.php';

class PdoDonationRepository implements DonationRepositoryInterface
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $donorId, string $foodType, string $quantity, string $pickupLocation, string $expiryTime): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO donations (donor_id, food_type, quantity, pickup_location, expiry_time, status)
             VALUES (?, ?, ?, ?, ?, 'available')"
        );
        return $stmt->execute([$donorId, $foodType, $quantity, $pickupLocation, $expiryTime]);
    }

    public function findById(int $id): ?Donation
    {
        $stmt = $this->pdo->prepare("SELECT * FROM donations WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new Donation(
            $row['id'],
            $row['donor_id'],
            $row['food_type'],
            $row['quantity'],
            $row['pickup_location'],
            $row['expiry_time'],
            $row['status']
        );
    }

    public function save(Donation $donation): bool
    {
        $stmt = $this->pdo->prepare("UPDATE donations SET status=? WHERE id=?");
        return $stmt->execute([$donation->getStatus(), $donation->getId()]);
    }

    public function findAvailable(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM donations WHERE status='available'");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $donations = [];
        foreach ($rows as $row) {
            $donations[] = new Donation(
                $row['id'],
                $row['donor_id'],
                $row['food_type'],
                $row['quantity'],
                $row['pickup_location'],
                $row['expiry_time'],
                $row['status']
            );
        }
        return $donations;
    }

    public function findByDonor(int $donorId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM donations WHERE donor_id=?");
        $stmt->execute([$donorId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $donations = [];
        foreach ($rows as $row) {
            $donations[] = new Donation(
                $row['id'],
                $row['donor_id'],
                $row['food_type'],
                $row['quantity'],
                $row['pickup_location'],
                $row['expiry_time'],
                $row['status']
            );
        }
        return $donations;
    }

    public function findDonationIdByRequest(int $requestId): int
    {
        $stmt = $this->pdo->prepare("SELECT donation_id FROM requests WHERE id=?");
        $stmt->execute([$requestId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) throw new Exception("Request not found: $requestId");

        return (int)$row['donation_id'];
    }

    public function searchAvailableByFoodType(string $keyword): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM donations 
         WHERE status='available' 
         AND food_type LIKE ?"
        );

        $stmt->execute(['%' . $keyword . '%']);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $donations = [];
        foreach ($rows as $row) {
            $donations[] = new Donation(
                $row['id'],
                $row['donor_id'],
                $row['food_type'],
                $row['quantity'],
                $row['pickup_location'],
                $row['expiry_time'],
                $row['status']
            );
        }
        return $donations;
    }

    public function findAvailableExpiringAfter(string $datetime): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM donations
         WHERE status='available'
         AND expiry_time >= ?
         ORDER BY expiry_time ASC"
        );

        $stmt->execute([$datetime]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $donations = [];
        foreach ($rows as $row) {
            $donations[] = new Donation(
                $row['id'],
                $row['donor_id'],
                $row['food_type'],
                $row['quantity'],
                $row['pickup_location'],
                $row['expiry_time'],
                $row['status']
            );
        }
        return $donations;
    }

    public function findAvailableSortedLatest(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM donations
         WHERE status='available'
         ORDER BY id DESC"
        );
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $donations = [];
        foreach ($rows as $row) {
            $donations[] = new Donation(
                $row['id'],
                $row['donor_id'],
                $row['food_type'],
                $row['quantity'],
                $row['pickup_location'],
                $row['expiry_time'],
                $row['status']
            );
        }
        return $donations;
    }
}
