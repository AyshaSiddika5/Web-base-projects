<?php
class User
{
    private int $id;
    private string $name;
    private string $email;
    private string $passwordHash;
    private string $role;

    public function __construct(int $id, string $name, string $email, string $passwordHash, string $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getRole(): string
    {
        return $this->role;
    }

    public function verifyPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->passwordHash);
    }

    public function canCreateDonation(): bool
    {
        return false;
    }
    public function canRequestDonation(): bool
    {
        return false;
    }
}
