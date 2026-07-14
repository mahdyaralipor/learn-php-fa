<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

/** مدل کاربر برای ورود */
final class User
{
    public function __construct(private PDO $pdo)
    {
    }

    /** @return array<string, mixed>|null */
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function verify(string $username, string $password): ?array
    {
        $user = $this->findByUsername($username);

        if ($user === null) {
            return null;
        }

        if (! password_verify($password, (string) $user['password_hash'])) {
            return null;
        }

        return $user;
    }
}
