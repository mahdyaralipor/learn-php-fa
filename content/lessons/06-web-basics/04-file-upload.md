---
title: آپلود فایل به‌صورت امن
weight: 4
---

# آپلود فایل

آپلود فایل از آن قسمت‌هایی است که تازه‌کارها خیلی راحت در آن اشتباه امنیتی می‌کنند. دلیلش هم روشن است: کاربر دارد یک فایل دلخواه از سیستم خودش به سمت سرور تو می‌فرستد.

پس این درس را با ذهنیت «بدبین ولی حرفه‌ای» بخوان.

## فرم آپلود

برای آپلود فایل، فرم باید این ویژگی را داشته باشد:

```html
<form method="post" action="" enctype="multipart/form-data">
    <input type="file" name="avatar">
    <button type="submit">آپلود</button>
</form>
```

بدون `enctype="multipart/form-data"` فایل به `$_FILES` نمی‌رسد.

## ساختار `$_FILES`

وقتی فایل ارسال می‌شود، PHP اطلاعاتش را در `$_FILES` می‌گذارد:

```php
$_FILES['avatar']['name']
$_FILES['avatar']['type']
$_FILES['avatar']['tmp_name']
$_FILES['avatar']['error']
$_FILES['avatar']['size']
```

به این نکته دقت کن:

- `name` اسم اصلی فایل از سمت کاربر است و قابل اعتماد نیست.
- `type` هم معمولاً از کلاینت می‌آید و قابل اعتماد کامل نیست.
- `tmp_name` مسیر فایل موقت روی سرور است.

## بررسی خطای آپلود

اولین قدم، چک کردن `error` است:

```php
<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['avatar'])) {
        exit('فایلی ارسال نشده است.');
    }

    if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        exit('آپلود فایل با خطا مواجه شد.');
    }
}
```

باز هم `!==` و `===` را جدی بگیر.

## محدود کردن اندازه

هر فایل آپلودی نباید پذیرفته شود:

```php
$maxSize = 2 * 1024 * 1024; // 2MB

if ($_FILES['avatar']['size'] > $maxSize) {
    exit('حجم فایل بیش از حد مجاز است.');
}
```

## محدود کردن نوع فایل

فقط به پسوند اعتماد نکن. فایل `virus.php` را می‌شود به `image.jpg` تغییر نام داد.

راه بهتر: MIME type را در سمت سرور بررسی کن.

```php
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
finfo_close($finfo);

$allowedMimeTypes = [
    'image/jpeg',
    'image/png',
    'image/webp',
];

if (!in_array($mimeType, $allowedMimeTypes, true)) {
    exit('نوع فایل مجاز نیست.');
}
```

به `in_array(..., true)` دقت کن. مقایسهٔ strict عادت امن‌تری است.

## نام فایل را خودت بساز

هیچ‌وقت فایل را با نام اصلی کاربر ذخیره نکن:

```php
$fileName = bin2hex(random_bytes(16)) . '.jpg';
```

البته پسوند را هم باید بر اساس نوع فایل تعیین کنی، نه بر اساس اسم کاربر.

```php
$extensions = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
];

$fileName = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
```

## ذخیرهٔ امن با `move_uploaded_file()`

بعد از همهٔ بررسی‌ها:

```php
<?php

declare(strict_types=1);

$uploadDir = __DIR__ . '/uploads';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$destination = $uploadDir . '/' . $fileName;

if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
    exit('ذخیرهٔ فایل شکست خورد.');
}

echo 'آپلود با موفقیت انجام شد.';
```

## یک نمونهٔ کامل‌تر

```php
<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        exit('آپلود ناموفق بود.');
    }

    $maxSize = 2 * 1024 * 1024;

    if ($_FILES['avatar']['size'] > $maxSize) {
        exit('فایل بیش از حد بزرگ است.');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
    finfo_close($finfo);

    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!array_key_exists($mimeType, $extensions)) {
        exit('فرمت فایل پشتیبانی نمی‌شود.');
    }

    $uploadDir = __DIR__ . '/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
    $destination = $uploadDir . '/' . $fileName;

    if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
        exit('انتقال فایل انجام نشد.');
    }

    echo 'آپلود موفق بود.';
}
```

## چند عادت امنیتی مهم

- فایل آپلودی را خارج از web root نگه دار اگر می‌توانی.
- هرگز به نام اصلی فایل اعتماد نکن.
- نوع فایل را در سرور بررسی کن.
- حجم را محدود کن.
- اگر فایل تصویری است و امنیت خیلی مهم است، حتی می‌توانی تصویر را دوباره پردازش و بازتولید کنی.

## جمع‌بندی

آپلود فایل یعنی پذیرش داده‌ای پیچیده و پرریسک از کاربر. الگوی امن این است:

1. وجود فایل را چک کن
2. خطا را چک کن
3. اندازه را محدود کن
4. نوع فایل را whitelist کن
5. نام جدید امن بساز
6. با `move_uploaded_file()` ذخیره کن

در درس بعدی می‌بینی چطور با `header()` نوع پاسخ را عوض کنیم یا کاربر را به صفحهٔ دیگری بفرستیم.
