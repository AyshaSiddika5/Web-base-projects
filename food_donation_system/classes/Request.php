<?php
class Request
{
    private int $id;
    private int $ngoId;
    private int $donationId;
    private string $status;

    public function __construct(int $id, int $ngoId, int $donationId, string $status)
    {
        $this->id = $id;
        $this->ngoId = $ngoId;
        $this->donationId = $donationId;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getNgoId(): int
    {
        return $this->ngoId;
    }
    public function getDonationId(): int
    {
        return $this->donationId;
    }
    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function approve()
    {
        if ($this->status !== 'pending') {
            throw new Exception("Only pending requests can be approved.");
        }
        $this->status = 'approved';
    }

    public function reject()
    {
        if ($this->status !== 'pending') {
            throw new Exception("Only pending requests can be rejected.");
        }
        $this->status = 'rejected';
    }
}
