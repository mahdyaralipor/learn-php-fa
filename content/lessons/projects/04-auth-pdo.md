---
title: "پروژه ۴: ثبت‌نام و ورود با PDO"
weight: 4
---

# پروژه: ثبت‌نام و ورود با PDO و Session

این پروژه نقطهٔ اتصال چند فصل مهم دوره است:

- فرم‌های وب
- اعتبارسنجی
- امنیت
- session
- PDO
- `password_hash()` و `password_verify()`

اگر این پروژه را خوب بفهمی، ستون فقرات خیلی از اپ‌های واقعی را درک کرده‌ای.

## هدف پروژه

یک سیستم auth ساده بساز که این قابلیت‌ها را داشته باشد:

- ثبت‌نام با ایمیل و رمز عبور
- ورود با ایمیل و رمز عبور
- logout
- صفحهٔ محافظت‌شده برای کاربران واردشده

## ساختار پیشنهادی

```text
public/
  register.php
  login.php
  logout.php
  dashboard.php
src/
  Database.php
  UserRepository.php
```

## جدول `users`

یک schema ساده:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## اتصال PDO

```php
<?php

declare(strict_types=1);

$pdo = new PDO(
    'mysql:host=127.0.0.1;port=3306;dbname=app;charset=utf8mb4',
    'root',
    'secret',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
```

## `UserRepository`

از همان الگوی فصل قبل استفاده کن:

```php
<?php

declare(strict_types=1);

final class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, email, password_hash FROM users WHERE email = :email'
        );
        $stmt->execute([
            'email' => $email,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user === false ? null : $user;
    }

    public function create(string $email, string $passwordHash): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)'
        );
        $stmt->execute([
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
```

## ثبت‌نام

مراحل:

1. ایمیل را validate کن
2. طول رمز عبور را چک کن
3. بررسی کن ایمیل تکراری نباشد
4. با `password_hash()` هش بساز
5. با prepared statement ذخیره کن

نمونه:

```php
<?php

declare(strict_types=1);

session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($email === false || $email === null) {
        $errors[] = 'ایمیل معتبر نیست.';
    }

    if (!is_string($password) || mb_strlen($password) < 8) {
        $errors[] = 'رمز عبور باید حداقل ۸ کاراکتر باشد.';
    }

    if ($errors === []) {
        $existingUser = $userRepository->findByEmail($email);

        if ($existingUser !== null) {
            $errors[] = 'این ایمیل قبلاً ثبت شده است.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $userId = $userRepository->create($email, $passwordHash);

            session_regenerate_id(true);
            $_SESSION['user_id'] = $userId;

            header('Location: /dashboard.php');
            exit;
        }
    }
}
```

## ورود

```php
<?php

declare(strict_types=1);

session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($email === false || $email === null || !is_string($password)) {
        $errors[] = 'ورودی نامعتبر است.';
    } else {
        $user = $userRepository->findByEmail($email);

        if ($user === null || !password_verify($password, $user['password_hash'])) {
            $errors[] = 'ایمیل یا رمز عبور اشتباه است.';
        } else {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];

            header('Location: /dashboard.php');
            exit;
        }
    }
}
```

## صفحهٔ محافظت‌شده

```php
<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

echo 'خوش آمدی.';
```

## logout

```php
<?php

declare(strict_types=1);

session_start();

$_SESSION = [];
session_destroy();

header('Location: /login.php');
exit;
```

## نکات امنیتی که باید حتماً رعایت کنی

- از `$_REQUEST` استفاده نکن
- ایمیل را validate کن
- رمز عبور را plaintext ذخیره نکن
- فقط از `password_hash()` و `password_verify()` استفاده کن
- تمام queryها را با prepared statements بنویس
- بعد از login از `session_regenerate_id(true)` استفاده کن
- خطاها را با `htmlspecialchars()` نمایش بده

## بهبودهای بعدی

بعد از کامل شدن نسخهٔ پایه، این‌ها را اضافه کن:

- CSRF token
- flash message
- محدودسازی تعداد تلاش ورود
- یادآوری کاربر با cookie امن
- لایهٔ service برای منطق auth

## جمع‌بندی

این پروژه اولین نقطه‌ای است که PHP وب، امنیت، سشن و دیتابیس واقعاً به هم می‌رسند. اگر همین نسخهٔ ساده را تمیز و امن پیاده‌سازی کنی، برای ساخت login واقعی در پروژه‌های بزرگ‌تر آماده‌ای.
