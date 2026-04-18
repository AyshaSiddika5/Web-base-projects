<?php
require_once __DIR__ . '/../repositories/DonationRepositoryInterface.php';

class DonationService
{

    private DonationRepositoryInterface $donationRepo;

    public function __construct(DonationRepositoryInterface $donationRepo)
    {
        $this->donationRepo = $donationRepo;
    }

    public function createDonation(
        int $donorId,
        string $foodType,
        string $quantity,
        string $pickupLocation,
        string $expiryTime
    ): bool {
        return $this->donationRepo->create(
            $donorId,
            $foodType,
            $quantity,
            $pickupLocation,
            $expiryTime
        );
    }

    public function getAvailableDonations(): array
    {
        return $this->donationRepo->findAvailable();
    }

    public function getDonationsByDonor(int $donorId): array
    {
        return $this->donationRepo->findByDonor($donorId);
    }

    public function markDonationRequested(int $donationId): bool
    {
        try {
            $donation = $this->donationRepo->findById($donationId);
            if (!$donation) {
                throw new Exception("Donation not found.");
            }

            $donation->markRequested();
            return $this->donationRepo->save($donation);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }


    public function searchAvailableDonations(string $keyword): array
    {
        if (method_exists($this->donationRepo, 'searchAvailableByFoodType')) {
            return $this->donationRepo->searchAvailableByFoodType($keyword);
        }
        return [];
    }

    public function getAvailableExpiringAfter(string $datetime): array
    {
        if (method_exists($this->donationRepo, 'findAvailableExpiringAfter')) {
            return $this->donationRepo->findAvailableExpiringAfter($datetime);
        }
        return [];
    }

    public function getAvailableLatest(): array
    {
        if (method_exists($this->donationRepo, 'findAvailableSortedLatest')) {
            return $this->donationRepo->findAvailableSortedLatest();
        }
        return [];
    }
}
