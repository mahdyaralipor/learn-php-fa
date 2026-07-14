---
title: راه‌اندازی PHPUnit با Composer
weight: 2
---

# راه‌اندازی PHPUnit

برای تست‌نویسی در PHP، معروف‌ترین ابزار `PHPUnit` است. در این درس یک setup ساده و واقعی می‌سازیم.

## نصب با Composer

چون PHPUnit ابزار توسعه است، باید در `require-dev` نصب شود:

```bash
composer require --dev phpunit/phpunit
```

بعد از نصب معمولاً این فایل‌ها و پوشه‌ها را خواهی داشت:

```text
composer.json
composer.lock
vendor/
```

## ساختار پیشنهادی پروژه

```text
src/
  Calculator.php
tests/
  CalculatorTest.php
phpunit.xml
```

## autoload برای کد و تست

نمونهٔ `composer.json`:

```json
{
  "require": {
    "php": "^8.2"
  },
  "require-dev": {
    "phpunit/phpunit": "^11.0"
  },
  "autoload": {
    "psr-4": {
      "App\\": "src/"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "Tests\\": "tests/"
    }
  }
}
```

بعد از این تغییر:

```bash
composer dump-autoload
```

## فایل `phpunit.xml`

این فایل تنظیمات PHPUnit را نگه می‌دارد.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="vendor/autoload.php" colors="true">
    <testsuites>
        <testsuite name="Application Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

برای شروع همین نسخهٔ ساده کافی است.

## اجرای تست‌ها

ساده‌ترین راه:

```bash
./vendor/bin/phpunit
```

یا اگر خواستی در Composer script تعریف کنی:

```json
{
  "scripts": {
    "test": "phpunit"
  }
}
```

بعد:

```bash
composer test
```

## چرا `bootstrap` مهم است؟

وقتی `bootstrap="vendor/autoload.php"` را می‌گذاری، PHPUnit قبل از اجرای تست‌ها autoload پروژه را load می‌کند. این یعنی در تست لازم نیست فایل‌ها را دستی include کنی.

## اگر تست‌ها اجرا نشدند چه چیزهایی را چک کنیم؟

- PHPUnit در `require-dev` نصب شده؟
- `vendor/autoload.php` وجود دارد؟
- namespace و مسیر فایل‌های `src/` و `tests/` درست‌اند؟
- `phpunit.xml` در ریشهٔ پروژه قرار دارد؟

## یک نکتهٔ عملی

برای شروع دنبال setup عجیب و پیشرفته نرو. یک ساختار کوچک ولی تمیز بهتر از کانفیگ سنگینی است که خودت هم نمی‌فهمی چرا آنجاست.

## تمرین

1. یک `composer.json` بنویس که `App\` و `Tests\` را autoload کند.
2. فایل `phpunit.xml` ساده بساز.
3. دستور اجرای تست‌ها را یک‌بار با `./vendor/bin/phpunit` و یک‌بار با `composer test` بنویس.

## جمع‌بندی

راه‌اندازی PHPUnit سخت نیست: با Composer آن را در `require-dev` نصب می‌کنی، autoload را تنظیم می‌کنی، یک `phpunit.xml` ساده می‌سازی و تست‌ها را از پوشهٔ `tests/` اجرا می‌کنی. در درس بعدی تست واقعی می‌نویسیم.
