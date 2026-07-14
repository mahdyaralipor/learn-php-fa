---
title: شروع کار با composer.json و دستورهای اصلی
weight: 2
---

# `composer.json` و دستورهای اصلی Composer

اگر Composer قلب مدیریت وابستگی باشد، فایل `composer.json` قرارداد اصلی پروژه است. این فایل به Composer می‌گوید پروژه چیست، چه پکیج‌هایی لازم دارد، autoload چگونه کار می‌کند و چه ابزارهای dev باید نصب شوند.

## ساخت `composer.json`

دو راه رایج داری:

### 1. ساخت تعاملی

```bash
composer init
```

Composer از تو چند سؤال می‌پرسد و در آخر فایل `composer.json` را می‌سازد.

### 2. ساخت خودکار با `require`

اگر پروژه هنوز `composer.json` نداشته باشد و این را اجرا کنی:

```bash
composer require monolog/monolog
```

Composer معمولاً فایل را هم برایت ایجاد می‌کند.

## یک نمونهٔ ساده از `composer.json`

```json
{
  "name": "panafor/learn-php-fa-app",
  "description": "A simple PHP learning project",
  "type": "project",
  "require": {
    "php": "^8.2",
    "vlucas/phpdotenv": "^5.6"
  },
  "require-dev": {
    "phpunit/phpunit": "^11.0"
  }
}
```

## فیلدهای مهم

### `require`

پکیج‌هایی که برنامه برای اجرا به آن‌ها نیاز دارد.

مثلاً:

- router
- dotenv
- http client
- logger

اگر برنامه در production بدون آن پکیج بالا نمی‌آید، معمولاً جای آن در `require` است.

### `require-dev`

پکیج‌هایی که فقط برای توسعه، تست، تحلیل کد یا ابزارهای کمکی لازم‌اند.

مثلاً:

- `phpunit/phpunit`
- `phpstan/phpstan`
- `friendsofphp/php-cs-fixer`

اگر این ابزارها روی سرور production نصب نباشند، برنامه هنوز باید کار کند. برای همین داخل `require-dev` می‌روند.

## نصب پکیج

### پکیج runtime

```bash
composer require vlucas/phpdotenv
```

### پکیج توسعه

```bash
composer require --dev phpunit/phpunit
```

این دستورها معمولاً چند کار را با هم انجام می‌دهند:

- dependency را resolve می‌کنند
- `composer.json` را به‌روزرسانی می‌کنند
- `composer.lock` را به‌روزرسانی می‌کنند
- پکیج‌ها را داخل `vendor/` نصب می‌کنند

## تفاوت `install` و `update`

این بخش خیلی مهم است و خیلی‌ها در شروع قاطی می‌کنند.

### `composer install`

وقتی می‌خواهی دقیقاً همان نسخه‌هایی را نصب کنی که در `composer.lock` ثبت شده‌اند.

سناریوی رایج:

- پروژه را clone کرده‌ای
- `composer.lock` داخل repo وجود دارد
- می‌خواهی محیطت دقیقاً مثل بقیه شود

```bash
composer install
```

### `composer update`

وقتی می‌خواهی Composer دوباره بر اساس constraintها نسخه‌های جدید مناسب را پیدا کند.

```bash
composer update
```

یعنی ممکن است نسخه‌ها تغییر کنند و `composer.lock` هم عوض شود.

## قانون طلایی

در بیشتر پروژه‌های اپلیکیشنی:

- بعد از clone کردن: `composer install`
- وقتی عمداً می‌خواهی وابستگی‌ها را به‌روزرسانی کنی: `composer update`

اگر بی‌هدف `update` بزنی، ممکن است ناگهان نسخه‌های جدیدی بگیری که باگ یا رفتار متفاوت دارند.

## اگر فقط یک پکیج را می‌خواهی آپدیت کنی

```bash
composer update monolog/monolog
```

این کار از `update` کور روی کل پروژه امن‌تر است.

## نصب بدون dev dependency

روی بعضی سرورها یا محیط‌های production ممکن است بخواهی پکیج‌های dev نصب نشوند:

```bash
composer install --no-dev
```

## ساختار فایل‌ها بعد از نصب

معمولاً چنین چیزی می‌بینی:

```text
composer.json
composer.lock
vendor/
```

پوشهٔ `vendor/` خروجی نصب است، نه جایی برای نوشتن کد خودت.

## یک workflow ساده و سالم

1. `composer init`
2. افزودن پکیج‌ها با `composer require`
3. اجرای `composer install`
4. commit کردن `composer.json` و `composer.lock`
5. ننوشتن هیچ کدی داخل `vendor/`

## تمرین

1. یک `composer.json` فرضی برای پروژه‌ای بنویس که به PHP 8.2، dotenv و PHPUnit نیاز دارد.
2. توضیح بده چرا PHPUnit باید در `require-dev` باشد.
3. با زبان خودت فرق `install` و `update` را در یک جمله بگو.
4. فرض کن پروژهٔ هم‌تیمی‌ات را clone کرده‌ای؛ بگو اول چه دستوری اجرا می‌کنی و چرا.

## جمع‌بندی

`composer.json` قرارداد وابستگی‌های پروژه است. `require` برای زمان اجرای برنامه است، `require-dev` برای ابزارهای توسعه. `install` محیط را از روی lock file تکرار می‌کند و `update` نسخه‌های جدید سازگار را پیدا می‌کند. در درس بعدی autoload استاندارد با PSR-4 را می‌سازیم.
