---
title: PSR-4 و autoload استاندارد
weight: 3
---

# PSR-4 و autoload با Composer

یکی از بهترین ویژگی‌های Composer فقط نصب پکیج نیست؛ autoload استاندارد هم برایت می‌سازد. این یعنی به‌جای اینکه برای هر کلاس `require_once` بنویسی، یک‌بار namespace و mapping را تعریف می‌کنی و بقیهٔ کار خودکار انجام می‌شود.

## مشکل روش دستی

اگر پروژه‌ات این‌طوری شود:

```php
require_once __DIR__ . '/src/Controllers/HomeController.php';
require_once __DIR__ . '/src/Services/PostService.php';
require_once __DIR__ . '/src/Models/Post.php';
```

خیلی زود با این دردسرها روبه‌رو می‌شوی:

- فراموش‌کردن include یک فایل
- ترتیب بارگذاری شکننده
- سخت‌شدن refactor
- شلوغی فایل‌های ورودی

## ایدهٔ PSR-4

PSR-4 یک استاندارد برای نگاشت namespace به مسیر فایل است.

مثلاً اگر بگویی:

- prefix: `App\`
- directory: `src/`

آن وقت کلاس زیر:

- `App\Controllers\HomeController`

باید در این فایل باشد:

- `src/Controllers/HomeController.php`

## تنظیم داخل `composer.json`

```json
{
  "autoload": {
    "psr-4": {
      "App\\": "src/"
    }
  }
}
```

اگر برای تست namespace جدا داشته باشی، می‌توانی autoload-dev هم تعریف کنی:

```json
{
  "autoload-dev": {
    "psr-4": {
      "Tests\\": "tests/"
    }
  }
}
```

## ساخت فایل autoload

بعد از تغییر `composer.json` معمولاً این را اجرا می‌کنی:

```bash
composer dump-autoload
```

یا اگر تازه پکیج نصب کرده‌ای، معمولاً Composer خودش این مرحله را هم انجام می‌دهد.

## استفاده در نقطهٔ ورود برنامه

فقط کافی است یک‌بار این فایل را load کنی:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
```

از اینجا به بعد، هر وقت از یک کلاس با namespace درست استفاده کنی، Composer فایلش را پیدا می‌کند.

## مثال کامل

### فایل `composer.json`

```json
{
  "autoload": {
    "psr-4": {
      "App\\": "src/"
    }
  }
}
```

### فایل `src/Services/Slugger.php`

```php
<?php

declare(strict_types=1);

namespace App\Services;

final class Slugger
{
    public function slugify(string $text): string
    {
        $text = trim(mb_strtolower($text));
        $text = preg_replace('/\s+/', '-', $text) ?? '';

        return $text;
    }
}
```

### فایل `public/index.php`

```php
<?php

declare(strict_types=1);

use App\Services\Slugger;

require_once __DIR__ . '/../vendor/autoload.php';

$slugger = new Slugger();

echo $slugger->slugify('Hello PHP World');
```

## `use` و namespace هنوز مهم‌اند

Composer autoload را حل می‌کند، اما namespace را به‌جای تو حدس نمی‌زند. هنوز باید:

- namespace کلاس را درست تعریف کنی
- فایل را در مسیر درست بگذاری
- با `use` یا نام کامل کلاس از آن استفاده کنی

## اشتباهات رایج

### 1. namespace با مسیر فایل نمی‌خواند

مثلاً کلاس را در `src/Service/Slugger.php` گذاشته‌ای ولی namespace را `App\Services` نوشته‌ای. این mismatch دردسر درست می‌کند.

### 2. فراموش‌کردن `dump-autoload`

بعد از تغییر mapping یا ساختار namespace، گاهی لازم است `composer dump-autoload` اجرا کنی.

### 3. نوشتن کد داخل `vendor/`

`vendor/` جای کد تو نیست. هر بار نصب یا آپدیت شود، تغییراتت از بین می‌رود.

### 4. استفاده از namespaceهای بی‌معنا

مثلاً `namespace MyApp\Core\Base\Abstract\Utils;` در پروژهٔ کوچک. این نوع پیچیدگی زودتر از آنکه مفید باشد، خسته‌کننده می‌شود.

## چه ساختاری خوب است؟

برای شروع، ساختاری مثل این کاملاً کافی است:

```text
src/
  Controllers/
  Models/
  Services/
  Support/
public/
```

## تمرین

1. mapping `App\` به `src/` را در یک `composer.json` فرضی بنویس.
2. یک کلاس `App\Support\Str` تعریف کن و یک متد ساده داخلش بگذار.
3. از همان کلاس در `public/index.php` با `vendor/autoload.php` استفاده کن.
4. عمداً namespace را اشتباه بنویس و حدس بزن چه خطایی رخ می‌دهد.

## جمع‌بندی

PSR-4 کاری می‌کند که namespace و ساختار فایل‌ها به یک قرارداد استاندارد تبدیل شوند. Composer هم از روی این قرارداد، autoload پروژه را می‌سازد. نتیجه: فایل ورودی تمیزتر، کد قابل‌نگهداری‌تر و حذف `require_once`های پراکنده.
