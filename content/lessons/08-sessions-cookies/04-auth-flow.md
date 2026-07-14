---
title: اسکلت جریان ورود و خروج
weight: 4
---

# اسکلت سادهٔ auth flow

در این درس هنوز دیتابیس نداریم. هدف این است که جریان کلی ورود و خروج را با session بفهمی.

بعداً همین الگو را با PDO و `password_hash()` کامل می‌کنیم.

## ایدهٔ کلی

در ساده‌ترین حالت:

1. کاربر فرم login را پر می‌کند
2. سرور ورودی را بررسی می‌کند
3. اگر معتبر بود، `user_id` را در session می‌گذارد
4. بعد از login، session ID را regenerate می‌کند
5. برای logout، session را پاک می‌کند

## صفحهٔ login

```php
<?php

declare(strict_types=1);

session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'ایمیل و رمز عبور الزامی هستند.';
    } elseif ($email === 'admin@example.com' && $password === 'secret') {
        session_regenerate_id(true);
        $_SESSION['user_id'] = 1;

        header('Location: /dashboard.php');
        exit;
    } else {
        $errors[] = 'ایمیل یا رمز عبور اشتباه است.';
    }
}
```

## فرم login

```php
<?php foreach ($errors as $error): ?>
    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endforeach; ?>

<form method="post" action="">
    <label for="email">ایمیل</label>
    <input id="email" name="email" type="email">

    <label for="password">رمز عبور</label>
    <input id="password" name="password" type="password">

    <button type="submit">ورود</button>
</form>
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

## چرا فقط `user_id`؟

بهتر است در session کمینهٔ لازم را نگه داری. معمولاً `user_id` کافی است و اگر اطلاعات بیشتری لازم شد، بعداً از دیتابیس می‌خوانی.

نگه داشتن دادهٔ زیاد در session معمولاً design تمیزی نیست.

## چیزهایی که بعداً اضافه می‌کنیم

در نسخهٔ واقعی‌تر معمولاً این‌ها را هم می‌خواهی:

- CSRF token روی فرم login
- flash message
- rate limiting
- `password_hash()` و `password_verify()`
- ذخیره و خواندن کاربر از دیتابیس

## جمع‌بندی

اسکلت auth flow از همین چند قطعه تشکیل می‌شود:

- فرم login
- اعتبارسنجی ورودی
- `session_regenerate_id(true)` بعد از login
- نگه داشتن `user_id` در session
- redirect برای دسترسی‌های غیرمجاز
- logout با پاک‌سازی session

در فصل بعدی این جریان را با دیتابیس و PDO واقعی‌تر می‌کنیم.
