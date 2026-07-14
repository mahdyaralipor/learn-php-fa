---
title: Pagination و ساختار یکدست خطاها
weight: 4
---

# Pagination و خطاهای یکدست در API

اگر API لیست داده برمی‌گرداند، خیلی زود به pagination نیاز پیدا می‌کنی. از طرف دیگر، اگر خطاها شکل ثابتی نداشته باشند، کار برای کلاینت سخت می‌شود.

## چرا pagination؟

اگر هزاران رکورد داشته باشی، برگرداندن همهٔ آن‌ها در یک پاسخ:

- کند است
- حافظهٔ بیشتری مصرف می‌کند
- برای کلاینت هم سنگین است

## مدل `limit/offset`

ساده‌ترین مدل همین است:

- `limit`: چند مورد برگردانده شود
- `offset`: از کجا شروع شود

مثلاً:

- `GET /posts?limit=10&offset=20`

## نمونهٔ پاسخ

```json
{
  "data": [
    {
      "id": 21,
      "title": "پست ۲۱"
    }
  ],
  "meta": {
    "limit": 10,
    "offset": 20,
    "total": 135
  }
}
```

## ایدهٔ cursor pagination

برای داده‌های بزرگ‌تر یا مرتب‌سازی حساس، گاهی `cursor` بهتر از `offset` است. در این دوره لازم نیست آن را عمیق پیاده کنیم؛ فقط بدان که گزینهٔ دیگری هم وجود دارد.

## ساختار یکدست خطاها

یکی از بهترین کارها برای API این است که همهٔ خطاها shape مشابهی داشته باشند.

مثلاً:

```json
{
  "error": {
    "code": "validation_failed",
    "message": "ورودی معتبر نیست.",
    "details": {
      "title": [
        "عنوان الزامی است."
      ]
    }
  }
}
```

## چرا این شکل خوب است؟

چون کلاینت می‌داند همیشه دنبال چه فیلدهایی بگردد:

- `error.code`
- `error.message`
- `error.details`

## مثال PHP برای پاسخ استاندارد خطا

```php
<?php

declare(strict_types=1);

function jsonError(string $code, string $message, int $status, array $details = []): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
        'error' => [
            'code' => $code,
            'message' => $message,
            'details' => $details,
        ],
    ], JSON_UNESCAPED_UNICODE);

    exit;
}
```

استفاده:

```php
if ($title === '') {
    jsonError(
        'validation_failed',
        'ورودی معتبر نیست.',
        422,
        ['title' => ['عنوان الزامی است.']]
    );
}
```

## نکتهٔ طراحی

در API خوب، موفقیت‌ها و خطاها هر دو قابل‌پیش‌بینی‌اند. این پیش‌بینی‌پذیری از خیلی از پیچیدگی‌های کلاینت کم می‌کند.

## تمرین

1. پاسخ paginated برای لیست taskها طراحی کن.
2. helper خطا بنویس که `404` را با همان shape استاندارد برگرداند.
3. با زبان خودت بگو چرا consistency در خطاها مهم است.

## جمع‌بندی

pagination جلوی پاسخ‌های سنگین را می‌گیرد و shape ثابت خطاها، کار کلاینت را بسیار ساده‌تر می‌کند. این دو مورد شاید کوچک به نظر برسند، اما روی کیفیت تجربهٔ توسعه‌دهنده و نگهداری API اثر زیادی دارند.
