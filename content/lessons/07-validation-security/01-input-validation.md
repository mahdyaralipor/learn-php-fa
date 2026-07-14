---
title: اعتبارسنجی ورودی
weight: 1
---

# اعتبارسنجی ورودی

اعتبارسنجی یعنی بررسی این‌که داده‌ای که وارد برنامه شده، همان چیزی هست که انتظارش را داریم یا نه.

این کار فقط برای امنیت نیست. برای کیفیت منطق برنامه هم ضروری است. اگر اعتبارسنجی نکنی، خیلی زود با داده‌های ناقص، عجیب، یا ناسازگار روبه‌رو می‌شوی.

## اعتبارسنجی با sanitize یکی نیست

دو مفهوم را قاطی نکن:

- `validation`: آیا داده معتبر است؟
- `escaping/sanitizing`: چطور داده را برای یک context خاص امن یا تمیز کنیم؟

مثلاً اگر ایمیل خالی باشد، `htmlspecialchars()` مشکل را حل نمی‌کند. چون مشکل اینجا «نامعتبر بودن» است، نه «خطرناک بودن در HTML».

## قانون اول: required

یکی از رایج‌ترین قواعد این است که بعضی فیلدها الزامی‌اند:

```php
<?php

declare(strict_types=1);

$name = trim($_POST['name'] ?? '');
$errors = [];

if ($name === '') {
    $errors['name'] = 'نام الزامی است.';
}
```

باز هم می‌بینی که `=== ''` از مقایسه‌های مبهم بهتر است.

## قانون دوم: طول

فقط پر بودن کافی نیست. معمولاً طول هم مهم است:

```php
if (mb_strlen($name) < 2) {
    $errors['name'] = 'نام باید حداقل ۲ کاراکتر باشد.';
}

if (mb_strlen($name) > 100) {
    $errors['name'] = 'نام نباید بیشتر از ۱۰۰ کاراکتر باشد.';
}
```

برای متن فارسی، `mb_strlen()` از `strlen()` مناسب‌تر است چون با چندبایتی‌ها بهتر رفتار می‌کند.

## قانون سوم: فرمت ایمیل

```php
<?php

declare(strict_types=1);

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if ($email === false || $email === null) {
    $errors['email'] = 'ایمیل معتبر نیست.';
}
```

این یک اعتبارسنجی پایه است. برای خیلی از پروژه‌ها کافی است.

## قانون چهارم: whitelist

اگر قرار است کاربر یکی از چند مقدار مشخص را انتخاب کند، بهترین الگو whitelist است:

```php
$role = $_POST['role'] ?? '';
$allowedRoles = ['user', 'editor'];

if (!in_array($role, $allowedRoles, true)) {
    $errors['role'] = 'نقش انتخاب‌شده معتبر نیست.';
}
```

چرا whitelist بهتر است؟ چون به‌جای این‌که بگویی چه چیزهایی ممنوع‌اند، می‌گویی فقط چه چیزهایی مجازند.

## اعتبارسنجی عدد

```php
$age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);

if ($age === false || $age === null) {
    $errors['age'] = 'سن باید عدد صحیح باشد.';
} elseif ($age < 18 || $age > 120) {
    $errors['age'] = 'سن خارج از بازهٔ مجاز است.';
}
```

## نمونهٔ کامل‌تر

```php
<?php

declare(strict_types=1);

$errors = [];

$name = trim($_POST['name'] ?? '');
$role = $_POST['role'] ?? '';
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if ($name === '') {
    $errors['name'] = 'نام الزامی است.';
} elseif (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
    $errors['name'] = 'نام باید بین ۲ تا ۱۰۰ کاراکتر باشد.';
}

if ($email === false || $email === null) {
    $errors['email'] = 'ایمیل معتبر نیست.';
}

$allowedRoles = ['user', 'editor'];
if (!in_array($role, $allowedRoles, true)) {
    $errors['role'] = 'نقش معتبر نیست.';
}
```

## اعتبارسنجی باید در سمت سرور باشد

اعتبارسنجی سمت کلاینت خوب است، اما کافی نیست. هر کسی می‌تواند مرورگر را دور بزند و مستقیم درخواست بفرستد.

پس این را قانون بدان:

اگر اعتبارسنجی فقط در JavaScript باشد، اعتبارسنجی واقعی نیست.

## نمایش خطاها

اگر خواستی خطا را در HTML نشان بدهی، باز هم خروجی را escape کن:

```php
<?php foreach ($errors as $error): ?>
    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endforeach; ?>
```

## جمع‌بندی

اعتبارسنجی خوب معمولاً شامل این‌هاست:

- الزامی بودن
- نوع داده
- طول یا بازه
- فرمت
- whitelist

در درس بعدی سراغ XSS می‌رویم؛ یعنی این‌که حتی دادهٔ معتبر هم اگر درست escape نشود، می‌تواند خطرناک شود.
