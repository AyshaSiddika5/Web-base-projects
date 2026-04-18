<?php
interface RequestRepositoryInterface
{
    public function create(int $ngoId, int $donationId): bool;
    public function findById(int $id): ?Request;
    public function save(Request $request): bool;
    public function findPending(): array;
    public function findApproved(): array;
    public function findByNgo(int $ngoId): array;
    public function findAll(): array;
    public function findRequestsWithDonationDetailsByNgo(int $ngoId): array;
}
