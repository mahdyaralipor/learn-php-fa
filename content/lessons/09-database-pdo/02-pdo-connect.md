---
title: اتصال به دیتابیس با PDO
weight: 2
---

# اتصال با PDO

`PDO` مخفف `PHP Data Objects` است. این API راهی استاندارد برای کار با دیتابیس‌های مختلف می‌دهد.

## ساخت شیء PDO

نمونهٔ MySQL:

```php
<?php

declare(strict_types=1);

$dsn = 'mysql:host=127.0.0.1;port=3306;dbname=app;charset=utf8mb4';
$username = 'root';
$password = 'secret';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$pdo = new PDO($dsn, $username, $password, $options);
```

## DSN چیست؟

`DSN` رشته‌ای است که مشخصات اتصال را توصیف می‌کند. در مثال بالا:

- driver: `mysql`
- host: `127.0.0.1`
- port: `3306`
- dbname: `app`
- charset: `utf8mb4`

## چرا `charset=utf8mb4` مهم است؟

اگر charset را درست مشخص نکنی، ممکن است با مشکلات encoding یا حتی بعضی رفتارهای ناخواسته روبه‌رو شوی. برای متن فارسی و داده‌های مدرن، `utf8mb4` انتخاب خوبی است.

## چرا `ERRMODE_EXCEPTION`؟

این باید تقریباً پیش‌فرض تو باشد:

```php
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
```

چون به‌جای این‌که خطاها بی‌سروصدا پنهان شوند، exception می‌گیری و می‌توانی آن را مدیریت کنی.

## چرا `FETCH_ASSOC`؟

اگر fetch mode پیش‌فرض را associative بگذاری، نتیجه‌ها معمولاً خواناتر می‌شوند:

```php
[
    'id' => 1,
    'email' => 'ali@example.com',
]
```

به‌جای این‌که هم index عددی داشته باشی هم اسمی.

## چرا `ATTR_EMULATE_PREPARES => false`؟

در خیلی از پروژه‌ها بهتر است از prepared statement واقعی driver استفاده کنی، نه emulation سمت PHP.

## مدیریت خطا

```php
<?php

declare(strict_types=1);

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    exit('اتصال به دیتابیس برقرار نشد.');
}
```

در production نباید جزئیات حساس خطا را مستقیم به کاربر نشان بدهی.

## نکته دربارهٔ credentialها

نام کاربری و رمز دیتابیس را hard-code کردن در فایل، برای آموزش قابل‌قبول است ولی برای پروژهٔ واقعی بهتر است از environment variable یا تنظیمات امن استفاده کنی.

## جمع‌بندی

الگوی اتصال خوب با PDO این ویژگی‌ها را دارد:

- DSN شفاف
- charset درست
- `ERRMODE_EXCEPTION`
- `FETCH_ASSOC`
- `ATTR_EMULATE_PREPARES => false`

در درس بعدی مهم‌ترین بخش کار با PDO را می‌بینیم: prepared statements.
