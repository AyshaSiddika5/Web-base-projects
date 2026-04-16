<?php
class Donation
{
    private int $id;
    private int $donorId;
    private string $foodType;
    private string $quantity;
    private string $pickupLocation;
    private string $expiryTime;
    private string $status;

    public function __construct(int $id, int $donorId, string $foodType, string $quantity, string $pickupLocation, string $expiryTime, string $status)
    {
        $this->id = $id;
        $this->donorId = $donorId;
        $this->foodType = $foodType;
        $this->quantity = $quantity;
        $this->pickupLocation = $pickupLocation;
        $this->expiryTime = $expiryTime;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getDonorId(): int
    {
        return $this->donorId;
    }
    public function getFoodType(): string
    {
        return $this->foodType;
    }
    public function getQuantity(): string
    {
        return $this->quantity;
    }
    public function getPickupLocation(): string
    {
        return $this->pickupLocation;
    }
    public function getExpiryTime(): string
    {
        return $this->expiryTime;
    }
    public function getStatus(): string
    {
        return $this->status;
    }

    public function markRequested()
    {
        if ($this->status !== 'available') throw new Exception("Only available donations can be requested.");
        $this->status = 'requested';
    }

    public function markAvailable()
    {
        $this->status = 'available';
    }
}
