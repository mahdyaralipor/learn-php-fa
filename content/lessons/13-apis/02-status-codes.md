---
title: status codeهای مهم در API
weight: 2
---

# status codeهای مهم در API

یکی از اشتباهات رایج در API این است که برای همه‌چیز فقط `200` برگردانیم. این کار باعث می‌شود کلاینت نفهمد واقعاً چه اتفاقی افتاده است.

status code باید خلاصهٔ وضعیت پاسخ باشد.

## `200 OK`

برای درخواست موفقی که پاسخ عادی برمی‌گرداند.

مثلاً:

- گرفتن لیست پست‌ها
- گرفتن جزئیات یک پست
- به‌روزرسانی موفقی که body دارد

## `201 Created`

وقتی یک resource جدید با موفقیت ساخته شده است.

مثلاً بعد از `POST /posts`.

می‌توانی body پاسخ را هم برگردانی:

```php
http_response_code(201);
```

## `400 Bad Request`

وقتی ساختار کلی درخواست مشکل دارد.

مثلاً:

- JSON خراب است
- پارامتر لازم اصلاً وجود ندارد
- فرمت ورودی از پایه اشتباه است

## `401 Unauthorized`

وقتی احراز هویت لازم است ولی انجام نشده یا معتبر نیست.

مثلاً:

- Bearer token نیست
- token نامعتبر است

## `404 Not Found`

وقتی resource خواسته‌شده وجود ندارد.

مثلاً:

- `GET /posts/9999`

## `422 Unprocessable Entity`

وقتی ساختار درخواست درست است، اما validation شکست خورده.

مثلاً:

- `title` خالی است
- طول `body` کمتر از حداقل لازم است

این کد برای validation error خیلی رایج و مناسب است.

## `500 Internal Server Error`

وقتی خطای غیرمنتظره در سرور رخ می‌دهد. معمولاً نباید جزئیات فنی را به کاربر نهایی بدهی.

## مثال عملی

```php
<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($tokenInvalid) {
    http_response_code(401);
    echo json_encode([
        'error' => [
            'code' => 'unauthorized',
            'message' => 'توکن معتبر نیست.',
        ],
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($title === '') {
    http_response_code(422);
    echo json_encode([
        'error' => [
            'code' => 'validation_failed',
            'message' => 'ورودی معتبر نیست.',
            'details' => [
                'title' => ['عنوان الزامی است.'],
            ],
        ],
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(201);
echo json_encode([
    'data' => [
        'id' => 1,
        'title' => $title,
    ],
], JSON_UNESCAPED_UNICODE);
```

## اصل مهم

status code جای body را نمی‌گیرد. بهتر است هم کد درست بدهی، هم body معنادار.

## تمرین

1. برای سناریوی «پست پیدا نشد» پاسخ `404` طراحی کن.
2. برای «JSON خراب» یک پاسخ `400` بنویس.
3. تفاوت `400` و `422` را با زبان خودت توضیح بده.

## جمع‌بندی

API خوب فقط داده برنمی‌گرداند؛ وضعیت را هم دقیق اعلام می‌کند. انتخاب درست بین `200`، `201`، `400`، `401`، `404`، `422` و `500` باعث می‌شود کلاینت رفتار قابل‌پیش‌بینی‌تری داشته باشد.
