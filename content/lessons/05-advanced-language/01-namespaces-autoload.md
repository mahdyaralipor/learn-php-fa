---
title: "فضاهای نام و autoload"
weight: 1
---

# namespace و autoload

در پروژه‌های واقعی خیلی زود با کلاس‌های زیاد و نام‌های تکراری روبه‌رو می‌شوی. برای همین PHP ابزار `namespace` را دارد.

## namespace چیست؟

`namespace` یا فضای نام راهی برای گروه‌بندی کلاس‌ها و جلوگیری از تداخل اسمی است:

```php
<?php

namespace App\Models;

class User
{
}
```

و همزمان:

```php
<?php

namespace App\Admin;

class User
{
}
```

این دو کلاس هم‌نام‌اند، اما چون در namespaceهای مختلف هستند با هم قاطی نمی‌شوند.

## نام کامل کلاس

نام کامل این کلاس‌ها می‌شود:

- `App\Models\User`
- `App\Admin\User`

به این نام کامل، fully qualified name هم می‌گویند.

## `use`

اگر بیرون از namespace کلاس باشی، یا باید نام کامل را بنویسی یا از `use` استفاده کنی:

```php
<?php

namespace App\Http;

use App\Services\Mailer;

class ContactController
{
    public function submit(): void
    {
        $mailer = new Mailer();
        $mailer->send("پیام تماس ثبت شد.");
    }
}
```

## alias با `as`

اگر تداخل نام داری:

```php
use App\Admin\User as AdminUser;
use App\Models\User as CustomerUser;
```

## مسیر پوشه و namespace

معمولا namespace را هم‌راستا با ساختار پوشه‌ها طراحی می‌کنند:

- `src/Models/User.php` -> `App\Models\User`
- `src/Services/Mailer.php` -> `App\Services\Mailer`

## autoload چیست؟

autoload یعنی فایل کلاس‌ها خودکار بارگذاری شوند و مجبور نباشی دستی `require_once` بنویسی. در پروژه‌های مدرن این کار معمولا با Composer انجام می‌شود.

## ایدهٔ PSR-4

PSR-4 خیلی ساده می‌گوید:

- یک prefix برای namespace تعریف کن
- آن را به یک پوشه وصل کن
- از روی namespace، فایل را پیدا کن

مثلا اگر prefix برابر `App\` و پوشه برابر `src/` باشد، کلاس `App\Services\Mailer` به فایل `src/Services/Mailer.php` نگاشت می‌شود.

## namespace جهانی

بعضی کلاس‌های built-in در root namespace هستند:

```php
$date = new \DateTimeImmutable();
```

بک‌اسلش اول یعنی از namespace ریشه شروع کن.

## اشتباهات رایج

### 1. بی‌نظمی در namespaceها

اگر namespace و ساختار پروژه هماهنگ نباشند، پیدا کردن کلاس‌ها سخت می‌شود.

### 2. فراموش‌کردن `use`

وقتی داخل namespace دیگری هستی، صرف نوشتن `new Mailer()` همیشه کافی نیست.

### 3. قاطی‌کردن `/` و `\`

در namespace از `\` استفاده می‌شود.

### 4. انتظار autoload بدون تنظیم

autoload جادویی از هیچ ظاهر نمی‌شود؛ باید ابزار یا پیکربندی لازم را داشته باشی.

## تمرین

1. سه namespace فرضی برای `Models`، `Services` و `Http\Controllers` طراحی کن.
2. یک کلاس `App\Services\Slugger` بنویس و در فایل دیگری با `use` از آن استفاده کن.
3. دو کلاس هم‌نام `User` در دو namespace متفاوت بساز و با alias از هر دو استفاده کن.
4. مسیر فرضی چند کلاس را به سبک PSR-4 روی کاغذ مپ کن.

## جمع‌بندی

`namespace` برای جلوگیری از تداخل نام و سازمان‌دهی کد است، `use` کد را خواناتر می‌کند، `as` alias می‌سازد و PSR-4 ایدهٔ نگاشت namespace به فایل را توضیح می‌دهد. درس بعدی دربارهٔ exception است.
