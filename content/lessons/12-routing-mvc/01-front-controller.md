---
title: Front Controller و نقطهٔ ورود واحد
weight: 1
---

# Front Controller چیست؟

در پروژه‌های خیلی ساده ممکن است برای هر صفحه یک فایل مستقل داشته باشی:

- `public/index.php`
- `public/login.php`
- `public/posts.php`
- `public/profile.php`

این روش برای شروع بد نیست، اما خیلی زود پراکندگی ایجاد می‌کند. الگوی `Front Controller` می‌گوید:

- همهٔ درخواست‌های ورودی اول به یک فایل واحد برسند
- آن فایل تصمیم بگیرد کدام کد باید اجرا شود

معمولاً این فایل می‌شود:

- `public/index.php`

## چرا این کار خوب است؟

چون کارهای مشترک را یک‌جا انجام می‌دهی:

- load کردن autoload
- ساخت container یا objectهای اصلی
- شروع session
- مسیریابی
- مدیریت خطا
- ارسال response

## نمونهٔ خیلی ساده از `public/index.php`

```php
<?php

declare(strict_types=1);

use App\Router;

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

$router = new Router();

$router->get('/', function (): void {
    echo 'صفحهٔ اصلی';
});

$router->get('/login', function (): void {
    echo 'صفحهٔ ورود';
});

$router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'
);
```

## rewrite یعنی چه؟

اگر فقط `index.php` نقطهٔ ورود باشد، وب‌سرور باید تقریباً همهٔ درخواست‌ها را به آن بفرستد؛ مگر اینکه فایل static واقعی مثل CSS یا تصویر وجود داشته باشد.

این ایده را با rewrite یا routing rule وب‌سرور پیاده می‌کنند.

## مفهوم `.htaccess` در Apache

در Apache معمولاً از ruleهایی استفاده می‌شود که اگر فایل یا پوشهٔ واقعی وجود نداشت، درخواست را به `index.php` بفرستند.

فهمیدن ایده کافی است؛ لازم نیست فعلاً جزئیات همهٔ directiveها را حفظ کنی.

## در Nginx چه می‌شود؟

در Nginx هم معمولاً چیزی شبیه این مفهوم وجود دارد:

- اول فایل static را بررسی کن
- اگر نبود، درخواست را به `index.php` بده

## مزیت اصلی

وقتی همه‌چیز از یک در وارد می‌شود، کنترل برنامه متمرکز می‌شود. این یعنی:

- auth check راحت‌تر
- error handling یکدست‌تر
- router ساده‌تر
- view rendering منظم‌تر

## اشتباه رایج

بعضی پروژه‌ها هم front controller دارند و هم هنوز پر از فایل‌های اجرایی پراکنده‌اند. نتیجه این می‌شود که نیمی از منطق از router رد می‌شود و نیمی مستقیم اجرا می‌شود. بهتر است از یک مدل روشن پیروی کنی.

## تمرین

1. توضیح بده چرا `public/index.php` جای مناسبی برای front controller است.
2. سه کاری را نام ببر که بهتر است در front controller انجام شوند.
3. با زبان خودت مفهوم rewrite را توضیح بده.

## جمع‌بندی

Front Controller یعنی یک نقطهٔ ورود واحد برای درخواست‌های وب. این الگو نظم ایجاد می‌کند و پایهٔ طبیعی routing و MVC است. در درس بعدی روی همین پایه، یک router خیلی ساده می‌سازیم.
