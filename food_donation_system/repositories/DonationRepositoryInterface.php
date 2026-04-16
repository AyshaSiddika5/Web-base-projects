<?php

interface DonationRepositoryInterface
{
    public function findById(int $id): ?Donation;
    public function save(Donation $donation): bool;
    public function create(
        int $donorId,
        string $foodType,
        string $quantity,
        string $pickupLocation,
        string $expiryTime
    ): bool;

    public function findAvailable(): array;
    public function findByDonor(int $donorId): array;
    public function findDonationIdByRequest(int $requestId): int;
    public function searchAvailableByFoodType(string $keyword): array;
    public function findAvailableExpiringAfter(string $datetime): array;
    public function findAvailableSortedLatest(): array;
}
