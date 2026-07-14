---
title: REST، JSON و پاسخ‌های API
weight: 1
---

# REST و JSON در PHP

وقتی می‌گوییم API، معمولاً منظورمان یک قرارداد برای تبادل داده بین سیستم‌هاست. در وب، رایج‌ترین شکل آن APIهای JSON روی HTTP هستند.

## REST یعنی چه؟

REST بیشتر از اینکه یک قانون خشک باشد، یک سبک طراحی است. یکی از ایده‌های اصلی‌اش این است که به‌جای فکر کردن به «صفحه‌ها»، به «منابع» فکر کنی.

مثلاً به‌جای این ذهنیت:

- `getPosts.php`
- `savePost.php`

به این ذهنیت می‌رسی:

- `GET /posts`
- `POST /posts`
- `GET /posts/10`
- `PUT /posts/10`
- `DELETE /posts/10`

## verbهای مهم HTTP

- `GET`: دریافت داده
- `POST`: ساختن دادهٔ جدید
- `PUT` یا `PATCH`: به‌روزرسانی
- `DELETE`: حذف

## چرا JSON؟

JSON سبک، خوانا و تقریباً همه‌جایی است. PHP هم ابزارهای built-in خوبی برای کار با آن دارد.

## ساخت response JSON

```php
<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'status' => 'success',
    'data' => [
        'id' => 10,
        'title' => 'اولین پست',
    ],
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
```

## چرا `Content-Type` مهم است؟

با این header به کلاینت می‌گویی پاسخ از نوع JSON است:

```php
header('Content-Type: application/json; charset=utf-8');
```

اگر این را نگذاری، بعضی کلاینت‌ها یا ابزارها ممکن است پاسخ را نادرست تفسیر کنند.

## خواندن JSON ورودی

در requestهای JSON، داده معمولاً داخل `php://input` است، نه `$_POST`.

```php
<?php

declare(strict_types=1);

$rawBody = file_get_contents('php://input');
$data = json_decode($rawBody, true);

if (!is_array($data)) {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
        'error' => [
            'code' => 'invalid_json',
            'message' => 'بدنهٔ درخواست JSON معتبر نیست.',
        ],
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
```

## `json_encode()` و `json_decode()`

دو تابع کلیدی همین‌ها هستند:

- `json_encode()` برای تبدیل آرایه/آبجکت به JSON
- `json_decode()` برای تبدیل JSON به دادهٔ PHP

اگر `json_decode(..., true)` بدهی، خروجی آرایهٔ associative می‌گیری.

## خطاهای رایج

### 1. فرض‌کردن اینکه `$_POST` برای JSON پر می‌شود

برای فرم‌های سنتی خوب است، نه برای body خام JSON.

### 2. فراموش‌کردن `Content-Type`

پاسخ را مبهم می‌کند.

### 3. encode کردن دادهٔ غیرقابل‌نمایش بدون بررسی

گاهی بهتر است از flagهای مناسب و error handling استفاده کنی.

## تمرین

1. یک response JSON بنویس که لیستی از دو پست را در کلید `data` برگرداند.
2. یک بدنهٔ JSON فرضی برای ساخت پست طراحی کن.
3. توضیح بده چرا در APIهای JSON معمولاً از `php://input` استفاده می‌کنیم.

## جمع‌بندی

در APIهای PHP، JSON زبان مشترک داده است. REST کمک می‌کند مسیرها و verbها معنی‌دار باشند، و `Content-Type` و `json_encode/json_decode` ابزارهای پایهٔ اجرای این قرارداد هستند.
