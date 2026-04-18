<?php
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Admin.php';
require_once __DIR__ . '/../classes/Donor.php';
require_once __DIR__ . '/../classes/NGO.php';

require_once 'UserRepositoryInterface.php';

class PdoUserRepository implements UserRepositoryInterface
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(string $name, string $email, string $passwordHash, string $role): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$name, $email, $passwordHash, $role]);
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        switch ($row['role']) {

            case 'admin':
                return new Admin(
                    $row['id'],
                    $row['name'],
                    $row['email'],
                    $row['password'],
                    $row['role']
                );

            case 'donor':
                return new Donor(
                    $row['id'],
                    $row['name'],
                    $row['email'],
                    $row['password'],
                    $row['role']
                );

            case 'ngo':
                return new NGO(
                    $row['id'],
                    $row['name'],
                    $row['email'],
                    $row['password'],
                    $row['role']
                );

            default:
                return new User(
                    $row['id'],
                    $row['name'],
                    $row['email'],
                    $row['password'],
                    $row['role']
                );
        }
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        switch ($row['role']) {

            case 'admin':
                return new Admin(
                    $row['id'],
                    $row['name'],
                    $row['email'],
                    $row['password'],
                    $row['role']
                );

            case 'donor':
                return new Donor(
                    $row['id'],
                    $row['name'],
                    $row['email'],
                    $row['password'],
                    $row['role']
                );

            case 'ngo':
                return new NGO(
                    $row['id'],
                    $row['name'],
                    $row['email'],
                    $row['password'],
                    $row['role']
                );

            default:
                return new User(
                    $row['id'],
                    $row['name'],
                    $row['email'],
                    $row['password'],
                    $row['role']
                );
        }
    }
}
