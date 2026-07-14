---
title: هدرهای امنیتی پایه
weight: 5
---

# هدرهای امنیتی پایه

امنیت فقط داخل منطق PHP نیست. بخشی از آن در تنظیم پاسخ HTTP و رفتار مرورگر هم شکل می‌گیرد.

این درس یک مرور مقدماتی است تا وقتی بعداً پروژهٔ واقعی‌تر ساختی، این مفاهیم برایت غریبه نباشند.

## HTTPS اول از همه

اگر سایت روی HTTPS نباشد، خیلی از دفاع‌ها ضعیف می‌شوند. اطلاعات می‌تواند در مسیر شنود یا دستکاری شود، و cookieهای حساس هم در خطر قرار می‌گیرند.

پس این را در ذهن نگه دار:

امنیت وب مدرن بدون HTTPS ناقص است.

## `HttpOnly` برای کوکی

بعداً مفصل‌تر دربارهٔ کوکی صحبت می‌کنیم، اما از همین حالا این مفهوم را بشناس:

اگر کوکی `HttpOnly` باشد، JavaScript مرورگر به آن دسترسی مستقیم ندارد. این کار در کاهش ریسک سرقت cookie در برخی سناریوهای XSS مفید است.

نمونه:

```php
setcookie('session', 'value', [
    'httponly' => true,
]);
```

البته این فقط یک لایه است، نه درمان کامل XSS.

## `Secure` برای کوکی

اگر `Secure` فعال باشد، کوکی فقط روی اتصال HTTPS ارسال می‌شود:

```php
setcookie('session', 'value', [
    'secure' => true,
]);
```

در production برای cookieهای حساس، این تقریباً باید پیش‌فرض باشد.

## `SameSite`

این ویژگی کمک می‌کند cookie در بعضی درخواست‌های cross-site ارسال نشود و در کاهش ریسک CSRF مفید است.

```php
setcookie('session', 'value', [
    'samesite' => 'Lax',
]);
```

مقادیر رایج:

- `Lax`
- `Strict`
- `None` که معمولاً نیازمند `Secure` است

## Content Security Policy یا CSP

CSP یک ایدهٔ مهم است: به مرورگر می‌گویی چه منابعی برای اسکریپت، استایل، تصویر و غیره مجاز هستند.

نمونهٔ خیلی پایه:

```php
header("Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none'");
```

این یعنی:

- به‌طور پیش‌فرض فقط منابع همان دامنه مجازند
- اسکریپت فقط از همان دامنه
- `object` کلاً ممنوع

## آیا CSP جای `htmlspecialchars()` را می‌گیرد؟

نه. CSP لایهٔ مکمل است. اگر خروجی را بدون escape درست چاپ کنی، هنوز مشکل داری. امنیت خوب یعنی چند لایه دفاع روی هم.

## چند هدر مفید دیگر

نمونه‌های آشنایی:

```php
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
```

این‌ها همه‌چیز را حل نمی‌کنند، اما بخشی از بهداشت امنیتی وب هستند.

## مثال ترکیبی ساده

```php
<?php

declare(strict_types=1);

header('Content-Type: text/html; charset=UTF-8');
header("Content-Security-Policy: default-src 'self'; object-src 'none'");
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');

echo '<h1>صفحهٔ امن‌تر</h1>';
```

## جمع‌بندی

در این درس خواستیم ذهنیتت را بزرگ‌تر کنیم:

- امنیت از HTTPS شروع می‌شود
- cookieها باید با flagهای مناسب تنظیم شوند
- CSP یک لایهٔ مکمل مهم است
- هیچ‌کدام جای escape درست خروجی و اعتبارسنجی ورودی را نمی‌گیرند

در فصل بعدی سراغ سشن و کوکی می‌رویم؛ یعنی جایی که state کاربر بین درخواست‌ها حفظ می‌شود.
