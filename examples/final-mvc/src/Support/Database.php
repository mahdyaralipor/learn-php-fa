<?php

declare(strict_types=1);

namespace App\Support;

use PDO;

/** اتصال SQLite و اجرای schema/seed در اولین اجرا */
final class Database
{
    public static function connect(string $dbPath): PDO
    {
        $dir = dirname($dbPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $pdo = new PDO('sqlite:' . $dbPath, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $pdo->exec('PRAGMA foreign_keys = ON');

        return $pdo;
    }

    public static function migrate(PDO $pdo, string $schemaPath): void
    {
        if (! is_file($schemaPath)) {
            return;
        }

        $pdo->exec((string) file_get_contents($schemaPath));
    }

    public static function seed(PDO $pdo, string $seedPath): void
    {
        if (! is_file($seedPath)) {
            return;
        }

        // فقط اگر جدول users خالی است seed بزن
        $count = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
        if ($count > 0) {
            return;
        }

        $pdo->exec((string) file_get_contents($seedPath));
    }
}
