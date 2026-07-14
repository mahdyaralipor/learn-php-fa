<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

/** مدل ساده پست با PDO/SQLite */
final class Post
{
    public function __construct(private PDO $pdo)
    {
    }

    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT posts.*, users.username AS author
             FROM posts
             LEFT JOIN users ON users.id = posts.user_id
             ORDER BY posts.created_at DESC'
        );

        return $stmt->fetchAll();
    }

    /** @return array<string, mixed>|null */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT posts.*, users.username AS author
             FROM posts
             LEFT JOIN users ON users.id = posts.user_id
             WHERE posts.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function create(string $title, string $body, ?int $userId): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO posts (title, body, user_id) VALUES (:title, :body, :user_id)'
        );
        $stmt->execute([
            'title' => $title,
            'body' => $body,
            'user_id' => $userId,
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
