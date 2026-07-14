---
title: الگوی Repository ساده
weight: 5
---

# Repository Pattern به زبان ساده

وقتی کد دیتابیس در همهٔ فایل‌ها پخش شود، نگهداری سخت می‌شود. Repository یک لایهٔ ساده است که queryهای مربوط به یک مفهوم را در یک کلاس جمع می‌کند.

اینجا یک `UserRepository` خیلی ساده می‌سازیم.

## چرا این الگو مفید است؟

- queryهای کاربر یک‌جا جمع می‌شوند
- کد controller یا صفحه تمیزتر می‌شود
- بعداً تغییر ساختار queryها آسان‌تر می‌شود

## نمونهٔ کلاس

```php
<?php

declare(strict_types=1);

final class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, name, email FROM users WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user === false ? null : $user;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, name, email, password_hash FROM users WHERE email = :email'
        );
        $stmt->execute([
            'email' => $email,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user === false ? null : $user;
    }

    public function create(string $name, string $email, string $passwordHash): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (name, email, password_hash)
             VALUES (:name, :email, :password_hash)'
        );
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
```

## استفاده از repository

```php
<?php

declare(strict_types=1);

$repository = new UserRepository($pdo);

$user = $repository->findByEmail('ali@example.com');

if ($user === null) {
    echo 'کاربر پیدا نشد.';
}
```

## نکتهٔ مهم

Repository قرار نیست جادو کند. اگر queryهایت کم و پروژه کوچک است، همین نسخهٔ ساده کافی است. هدف ما فعلاً نظم دادن به دسترسی دیتاست، نه ساختن یک abstraction سنگین.

## ارتباط با امنیت

حتی داخل repository هم عادت‌های امنیتی تغییر نمی‌کنند:

- prepared statements
- مقایسه‌های strict مثل `=== false`
- بازگرداندن `null` برای «پیدا نشد» به‌جای رفتار مبهم

## جمع‌بندی

Repository یک لایهٔ کوچک و مفید بین منطق برنامه و PDO است. برای شروع:

- `UserRepository`
- متدهای `findById`, `findByEmail`, `create`
- استفاده از PDO تزریق‌شده در constructor

در پروژه‌های این بخش، دقیقاً از همین الگو استفاده خواهیم کرد.
