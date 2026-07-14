---
title: SQL Injection و رمزهای عبور
weight: 4
---

# چرا string concatenation برای SQL خطرناک است؟

اگر دادهٔ کاربر را مستقیم داخل query بچسبانی، عملاً به کاربر اجازه می‌دهی ساختار query را تغییر دهد.

اشتباه کلاسیک:

```php
$email = $_POST['email'] ?? '';
$sql = "SELECT * FROM users WHERE email = '$email'";
```

اگر ورودی کاربر دستکاری شود، query هم عوض می‌شود. این یعنی SQL injection.

## نسخهٔ درست: prepared statements

به‌جای چسباندن داده به SQL، query و data را از هم جدا کن:

```php
<?php

declare(strict_types=1);

$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute([
    'email' => $email,
]);

$user = $stmt->fetch();
```

این باید تبدیل به عادت پیش‌فرض تو شود:

- query ثابت
- داده جدا
- execute با پارامتر

## چرا prepared statement امن‌تر است؟

چون database driver داده را به‌عنوان «مقدار» می‌فهمد، نه «بخشی از دستور SQL».

پس هر وقت ورودی کاربر وارد query می‌شود، پاسخ درست تقریباً همیشه این است: prepared statements.

## رمز عبور را هرگز plaintext ذخیره نکن

اگر در دیتابیس بنویسی:

```php
$password = $_POST['password'] ?? '';
```

و همان را ذخیره کنی، با اولین نشت دیتابیس همه‌چیز از دست رفته است.

نسخهٔ درست:

```php
$hash = password_hash($password, PASSWORD_DEFAULT);
```

و هنگام ورود:

```php
if (password_verify($password, $user['password_hash'])) {
    // login success
}
```

## ثبت‌نام امن

```php
<?php

declare(strict_types=1);

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

if ($email === false || $email === null) {
    exit('ایمیل معتبر نیست.');
}

if (!is_string($password) || $password === '') {
    exit('رمز عبور الزامی است.');
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    'INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)'
);

$stmt->execute([
    'email' => $email,
    'password_hash' => $hash,
]);
```

## ورود امن

```php
<?php

declare(strict_types=1);

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

if ($email === false || $email === null || !is_string($password)) {
    exit('ورودی نامعتبر است.');
}

$stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE email = :email');
$stmt->execute([
    'email' => $email,
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user === false || !password_verify($password, $user['password_hash'])) {
    exit('ایمیل یا رمز عبور اشتباه است.');
}

echo 'ورود موفق';
```

## چرا `=== false` مهم است؟

توابعی مثل `fetch()` وقتی داده پیدا نشود `false` برمی‌گردانند. اگر با `==` مقایسه کنی، در بعضی شرایط ممکن است رفتار مبهم شود. پس برای این موارد strict comparison بهتر است.

## چند عادت امنیتی مهم

- query را با string concatenation نساز
- همیشه prepared statement استفاده کن
- رمز عبور را فقط با `password_hash()` ذخیره کن
- بررسی رمز را فقط با `password_verify()` انجام بده
- پیام خطا را بیش از حد دقیق نکن

مثلاً بهتر است بگویی:

`ایمیل یا رمز عبور اشتباه است.`

نه این‌که:

`این ایمیل وجود ندارد.`

## جمع‌بندی

دو پیش‌فرض طلایی این درس:

- برای SQL: prepared statements
- برای password: `password_hash()` و `password_verify()`

در درس بعدی چند لایهٔ امنیتی دیگر را می‌بینیم که شاید همیشه در کد اولیه نباشند، اما برای وب حرفه‌ای خیلی مهم‌اند.
