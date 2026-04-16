<?php
require_once __DIR__ . '/../repositories/UserRepositoryInterface.php';

class AuthService
{

    private UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register(string $name, string $email, string $password, string $role): bool
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $allowedRoles = ['donor', 'ngo'];

        if (!in_array($role, $allowedRoles)) {
            return false;
        }

        return $this->userRepo->create($name, $email, $hash, $role);
    }

    public function login(string $email, string $password): ?User
    {
        $user = $this->userRepo->findByEmail($email);
        if (!$user) return null;

        if (!$user->verifyPassword($password)) return null;

        return $user;
    }
}
