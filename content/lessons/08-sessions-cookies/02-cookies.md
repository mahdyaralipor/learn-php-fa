---
title: کار با Cookie
weight: 2
---

# Cookie چیست؟

کوکی قطعهٔ کوچکی از داده است که مرورگر نگه می‌دارد و در درخواست‌های بعدی به سرور برمی‌گرداند.

کوکی‌ها برای سشن، ترجیح کاربر، remember-me و موارد مشابه کاربرد دارند.

## ساخت کوکی

در PHP با `setcookie()`:

```php
<?php

declare(strict_types=1);

setcookie('theme', 'dark');
```

اما در عمل، بهتر است از آرایهٔ تنظیمات استفاده کنی.

## نسخهٔ بهتر

```php
<?php

declare(strict_types=1);

setcookie('theme', 'dark', [
    'expires' => time() + 60 * 60 * 24 * 30,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax',
]);
```

## معنی flagها

- `secure`: فقط روی HTTPS ارسال شود
- `httponly`: JavaScript به آن دسترسی مستقیم نداشته باشد
- `samesite`: در بعضی درخواست‌های cross-site ارسال نشود

برای کوکی‌های حساس این‌ها خیلی مهم‌اند.

## خواندن کوکی

```php
$theme = $_COOKIE['theme'] ?? 'light';
```

باز هم این داده را ورودی بدان، نه حقیقت مطلق. چون کاربر می‌تواند کوکی را تغییر دهد.

## حذف کوکی

برای حذف، معمولاً همان نام و path را با زمان انقضای گذشته می‌فرستی:

```php
setcookie('theme', '', [
    'expires' => time() - 3600,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax',
]);
```

## چه چیزهایی را در کوکی نگذاریم؟

- رمز عبور
- اطلاعات حساس plaintext
- هر چیزی که با دست‌کاری‌اش منطق برنامه به هم بریزد

اگر لازم شد اطلاعات مهمی را در کوکی نگه داری، باید طراحی دقیق‌تری داشته باشی. در اکثر پروژه‌ها بهتر است کوکی فقط شناسه یا token غیرمستقیم نگه دارد.

## مثال ساده: ترجیح زبان

```php
<?php

declare(strict_types=1);

$lang = $_POST['lang'] ?? 'fa';
$allowed = ['fa', 'en'];

if (in_array($lang, $allowed, true)) {
    setcookie('lang', $lang, [
        'expires' => time() + 30 * 24 * 60 * 60,
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}
```

به whitelist و `in_array(..., true)` دقت کن.

## ارتباط cookie و session

در PHP سنتی، session معمولاً با یک cookie شناسه‌دار مدیریت می‌شود. یعنی خود دادهٔ session سمت سرور می‌ماند، ولی مرورگر یک شناسه را در cookie نگه می‌دارد.

## جمع‌بندی

کوکی ابزار مفیدی است، اما باید با احتیاط استفاده شود:

- دادهٔ کوکی قابل دست‌کاری است
- برای cookieهای حساس از `Secure`, `HttpOnly`, `SameSite` استفاده کن
- دادهٔ حساس را بی‌فکر در cookie نریز

در درس بعدی از session برای ساختن flash message استفاده می‌کنیم؛ الگویی که تقریباً در همهٔ اپ‌های وب دیده می‌شود.
