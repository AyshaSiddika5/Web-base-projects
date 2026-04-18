<?php
require_once __DIR__ . '/../classes/Request.php';
require_once 'RequestRepositoryInterface.php';

class PdoRequestRepository implements RequestRepositoryInterface
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $ngoId, int $donationId): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO requests (ngo_id, donation_id, status) VALUES (?, ?, 'pending')"
        );
        return $stmt->execute([$ngoId, $donationId]);
    }

    public function findById(int $id): ?Request
    {
        $stmt = $this->pdo->prepare("SELECT * FROM requests WHERE id=?");
        $stmt->execute([$id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$r) return null;

        return new Request($r['id'], $r['ngo_id'], $r['donation_id'], $r['status']);
    }

    public function save(Request $request): bool
    {
        $stmt = $this->pdo->prepare("UPDATE requests SET status=? WHERE id=?");
        return $stmt->execute([$request->getStatus(), $request->getId()]);
    }


    public function findPending(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM requests WHERE status='pending'");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $requests = [];
        foreach ($rows as $r) {
            $requests[] = new Request($r['id'], $r['ngo_id'], $r['donation_id'], $r['status']);
        }
        return $requests;
    }

    public function findApproved(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM requests WHERE status='approved'");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $requests = [];
        foreach ($rows as $r) {
            $requests[] = new Request($r['id'], $r['ngo_id'], $r['donation_id'], $r['status']);
        }
        return $requests;
    }

    public function findByNgo(int $ngoId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM requests WHERE ngo_id=?");
        $stmt->execute([$ngoId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $requests = [];
        foreach ($rows as $r) {
            $requests[] = new Request($r['id'], $r['ngo_id'], $r['donation_id'], $r['status']);
        }
        return $requests;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM requests ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $requests = [];
        foreach ($rows as $r) {
            $requests[] = new Request($r['id'], $r['ngo_id'], $r['donation_id'], $r['status']);
        }
        return $requests;
    }

    public function findRequestsWithDonationDetailsByNgo($ngoId): array
    {
        $sql = "SELECT r.id as request_id,
                   r.status,
                   d.food_type,
                   d.quantity,
                   d.pickup_location,
                   d.expiry_time
            FROM requests r
            JOIN donations d ON r.donation_id = d.id
            WHERE r.ngo_id = ?
            ORDER BY r.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$ngoId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
