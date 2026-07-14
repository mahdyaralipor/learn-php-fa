---
title: هدرها و ریدایرکت
weight: 5
---

# `header()` و ریدایرکت

در PHP فقط HTML تولید نمی‌کنی. گاهی لازم است به مرورگر بگویی:

- به صفحهٔ دیگری برو
- این پاسخ JSON است
- این فایل برای دانلود است

این کارها با headerهای HTTP انجام می‌شوند.

## تابع `header()`

تابع `header()` یک header به پاسخ HTTP اضافه می‌کند:

```php
<?php

declare(strict_types=1);

header('Content-Type: text/plain; charset=UTF-8');
echo 'سلام';
```

## نکتهٔ خیلی مهم: قبل از خروجی

معمولاً باید header را قبل از هر خروجی بفرستی. یعنی اگر قبل از `header()` چیزی echo کنی، ممکن است با خطای `headers already sent` روبه‌رو شوی.

اشتباه:

```php
echo 'شروع';
header('Location: /login.php');
```

## ریدایرکت با `Location`

برای فرستادن کاربر به صفحهٔ دیگر:

```php
<?php

declare(strict_types=1);

header('Location: /login.php');
exit;
```

این `exit;` خیلی مهم است. بعد از ریدایرکت، اجرای اسکریپت باید متوقف شود. در غیر این صورت ممکن است کدهای بعدی ناخواسته اجرا شوند.

## الگوی استاندارد بعد از فرم

بعد از پردازش موفق فرم، معمولاً کاربر را redirect می‌کنیم:

```php
<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // پردازش فرم

    header('Location: /success.php');
    exit;
}
```

این الگو باعث می‌شود با refresh صفحه، فرم دوباره ارسال نشود.

## تعیین نوع محتوا

اگر پاسخ HTML نیست، خودت باید نوع آن را مشخص کنی.

مثلاً JSON:

```php
<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

echo json_encode([
    'ok' => true,
    'message' => 'درخواست موفق بود',
], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
```

یا متن ساده:

```php
header('Content-Type: text/plain; charset=UTF-8');
```

## status code

گاهی فقط ریدایرکت کافی نیست و می‌خواهی کد وضعیت را هم صریح بگویی:

```php
header('Location: /login.php', true, 302);
exit;
```

برای خطا:

```php
http_response_code(404);
echo 'صفحه پیدا نشد.';
```

## نمونهٔ واقعی‌تر

```php
<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Method Not Allowed';
    exit;
}

$name = trim($_POST['name'] ?? '');

if ($name === '') {
    header('Location: /form.php?error=1');
    exit;
}

header('Location: /form.php?success=1');
exit;
```

## چند خطای رایج

- فراموش کردن `exit` بعد از `Location`
- ارسال خروجی قبل از `header()`
- اعتماد به این‌که redirect یعنی امنیت

یادت باشد: ریدایرکت فقط به مرورگر دستور می‌دهد. منطق امنیتی اصلی باید در سمت سرور باشد.

## جمع‌بندی

از این درس این سه عادت را بردار:

- قبل از ارسال خروجی، headerها را تنظیم کن
- بعد از `header('Location: ...')` همیشه `exit;` بزن
- برای JSON یا متن ساده، `Content-Type` را شفاف مشخص کن

فصل بعدی کاملاً روی اعتبارسنجی و امنیت می‌چرخد؛ جایی که این عادت‌ها تبدیل به ستون فقرات کد تو می‌شوند.
