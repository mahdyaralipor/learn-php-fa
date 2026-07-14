---
title: احراز هویت ساده با Bearer Token یا API Key
weight: 3
---

# احراز هویت ساده با token

خیلی از APIها به‌جای session سنتی، از token استفاده می‌کنند. ایدهٔ پایه ساده است:

- کلاینت یک secret یا token دارد
- آن را همراه درخواست می‌فرستد
- سرور اعتبارش را بررسی می‌کند

## دو الگوی رایج

### Bearer Token

در header `Authorization`:

```http
Authorization: Bearer your-secret-token
```

### API Key

مثلاً:

```http
X-API-Key: your-api-key
```

یا حتی در query string، هرچند این روش معمولاً ضعیف‌تر و کم‌امن‌تر است.

## مثال ساده با Bearer Token

```php
<?php

declare(strict_types=1);

function getBearerToken(): ?string
{
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

    if (!is_string($header)) {
        return null;
    }

    if (!preg_match('/^Bearer\s+(.+)$/i', $header, $matches)) {
        return null;
    }

    return trim($matches[1]);
}

$token = getBearerToken();

if ($token === null || $token !== 'super-secret-token') {
    http_response_code(401);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
        'error' => [
            'code' => 'unauthorized',
            'message' => 'توکن احراز هویت معتبر نیست.',
        ],
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
```

## این مثال آموزشی است، نه نسخهٔ نهایی production

در پروژهٔ واقعی بهتر است:

- token را plaintext هاردکد نکنی
- آن را در env یا دیتابیس نگه داری
- در صورت لزوم hash شده ذخیره کنی
- برای expiration و revoke هم فکر کنی

## API Key برای چه سناریوهایی خوب است؟

- ارتباط سادهٔ سرور با سرور
- ابزارهای داخلی
- webhookهایی که امنیتشان با secret کنترل می‌شود

## Bearer Token برای چه چیزی رایج است؟

- APIهای مبتنی بر کاربر
- سیستم‌هایی که بعد از login، token برمی‌گردانند
- موبایل اپ‌ها یا SPAها

## نکتهٔ امنیتی مهم

- token را داخل کد commit نکن
- آن را در logهای عمومی نریز
- از HTTPS استفاده کن
- در صورت نشت token، راه revoke یا rotate داشته باش

## تمرین

1. یک helper بنویس که API key را از header `X-API-Key` بخواند.
2. پاسخ `401` استاندارد برای token نامعتبر طراحی کن.
3. توضیح بده چرا نگه‌داشتن token در `.env` بهتر از هاردکد کردن در سورس است.

## جمع‌بندی

احراز هویت توکنی در APIها بسیار رایج است. برای شروع، Bearer token یا API key الگوی مناسبی است. مهم‌تر از پیاده‌سازی ساده، رعایت اصول پایه مثل نگه‌داری امن secret و استفاده از HTTPS است.
