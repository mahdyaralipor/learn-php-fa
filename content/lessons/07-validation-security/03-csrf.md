---
title: محافظت در برابر CSRF
weight: 3
---

# CSRF چیست؟

CSRF مخفف `Cross-Site Request Forgery` است. ایدهٔ حمله این است که کاربر در سایت تو login کرده و مرورگرش cookie معتبر دارد، بعد یک سایت مخرب او را وادار می‌کند ناخواسته یک درخواست معتبر به سایت تو بفرستد.

مثلاً تغییر رمز، حذف حساب، یا ثبت سفارش.

## چرا این حمله جواب می‌دهد؟

چون مرورگر معمولاً cookieهای مربوط به سایت مقصد را همراه درخواست می‌فرستد. پس اگر فقط به «کاربر login است» تکیه کنی، کافی نیست.

## دفاع پایه: CSRF token

برای فرم‌های حساس، یک token تصادفی در session نگه می‌داریم و همان token را داخل فرم می‌فرستیم. بعد در درخواست `POST` بررسی می‌کنیم که دقیقاً برابر باشد.

## ساخت token

```php
<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
```

## قرار دادن token در فرم

```php
<form method="post" action="">
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
    >

    <button type="submit">ارسال</button>
</form>
```

باز هم خروجی داخل HTML است، پس `htmlspecialchars()` را فراموش نمی‌کنیم.

## بررسی token

```php
<?php

declare(strict_types=1);

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (!is_string($submittedToken) || !is_string($sessionToken)) {
        http_response_code(400);
        exit('درخواست نامعتبر است.');
    }

    if (!hash_equals($sessionToken, $submittedToken)) {
        http_response_code(403);
        exit('CSRF token نامعتبر است.');
    }
}
```

## چرا `hash_equals()`؟

برای مقایسهٔ مقادیر حساس، `hash_equals()` بهتر از `===` است چون برای مقایسهٔ امن‌تر زمانی طراحی شده. برای CSRF token و چیزهای مشابه، انتخاب خوبی است.

## فقط برای `POST`؟

به‌طور معمول، عملیات‌هایی که state را تغییر می‌دهند باید با `POST`, `PUT`, `PATCH`, `DELETE` انجام شوند و CSRF protection روی آن‌ها باشد.

در مقابل، `GET` نباید عملیات مخرب یا تغییردهنده انجام دهد.

اگر لینک `/delete-account?id=1` با `GET` داشته باشی، از پایه طراحی خطرناکی داری.

## نمونهٔ کامل

```php
<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (!is_string($submittedToken) || !hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        $errors[] = 'درخواست امن نیست.';
    }

    if ($errors === []) {
        echo 'فرم با موفقیت پردازش شد.';
        exit;
    }
}
```

## آیا SameSite کافی است؟

ویژگی `SameSite` برای cookieها کمک بزرگی است و بعداً آن را می‌بینیم، اما جایگزین کامل CSRF token نیست. لایه‌های دفاعی باید کنار هم باشند.

## جمع‌بندی

برای فرم‌های حساس این الگو را پیش‌فرض کن:

1. `session_start()`
2. ساخت token تصادفی
3. قرار دادن token در فرم
4. بررسی token در `POST`
5. رد کردن درخواست نامعتبر

در درس بعدی سراغ یکی از خطرناک‌ترین اشتباه‌های وب می‌رویم: SQL injection و ذخیرهٔ غلط رمز عبور.
