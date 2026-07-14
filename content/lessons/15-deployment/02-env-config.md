---
title: تنظیمات مبتنی بر env و مراقبت از secretها
weight: 2
---

# تنظیمات محیطی و `.env`

یکی از مهم‌ترین تفاوت‌های کد حرفه‌ای و کد دردسرساز این است که تنظیمات حساس را داخل سورس هاردکد نکنی.

## چه چیزهایی نباید hardcode شوند؟

- رمز دیتابیس
- API key
- secret token
- host و port محیط production
- credentialهای سرویس‌های بیرونی

## الگوی `.env`

در توسعهٔ محلی، یک فایل `.env` می‌تواند مقادیر را نگه دارد:

```dotenv
APP_ENV=local
APP_DEBUG=true
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=app
DB_USER=root
DB_PASS=secret
API_TOKEN=my-local-token
```

بعد با کتابخانه‌ای مثل `vlucas/phpdotenv` آن‌ها را load می‌کنی.

## نمونهٔ استفاده

```php
<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$dbHost = $_ENV['DB_HOST'] ?? '127.0.0.1';
```

## چرا این الگو خوب است؟

- تنظیمات از کد جدا می‌شوند
- محیط local و production می‌توانند مقادیر متفاوت داشته باشند
- secretها وارد repo نمی‌شوند

## قانون خیلی مهم

فایل `.env` واقعی را commit نکن.

در عوض معمولاً این‌ها را نگه می‌داری:

- `.env.example`
- توضیح اینکه هر متغیر چه کاری می‌کند

## `.env.example`

```dotenv
APP_ENV=local
APP_DEBUG=false
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=app
DB_USER=
DB_PASS=
API_TOKEN=
```

## در production چه کنیم؟

در production الزاماً لازم نیست فایل `.env` روی دیسک داشته باشی. خیلی وقت‌ها بهتر است متغیرها را از خود محیط سرور یا سیستم deploy بگیری.

## اشتباهات رایج

- commit کردن `.env`
- log کردن secretها
- استفاده از defaultهای ناامن
- یکی‌کردن تنظیمات dev و production

## تمرین

1. یک `.env.example` برای پروژهٔ MVC خودت طراحی کن.
2. پنج متغیر محیطی مهم برای پروژهٔ وب بنویس.
3. توضیح بده چرا commit کردن `.env` خطرناک است.

## جمع‌بندی

تنظیمات محیطی کمک می‌کنند پروژه بین محیط‌های مختلف قابل‌انتقال باشد و secretها از سورس جدا بمانند. `.env` در توسعهٔ محلی مفید است، اما اصل مهم‌تر این است که secretها هرگز وارد repo نشوند.
