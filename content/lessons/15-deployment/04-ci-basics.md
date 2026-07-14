---
title: مقدمهٔ CI و ایدهٔ GitHub Actions
weight: 4
---

# CI چیست و چرا مفید است؟

`CI` یا Continuous Integration یعنی هر بار که تغییر جدیدی وارد پروژه می‌شود، یک‌سری چک خودکار اجرا شوند تا سریع بفهمیم چیزی خراب شده یا نه.

حتی یک CI خیلی ساده هم ارزش زیادی دارد.

## چه چک‌هایی برای شروع خوب‌اند؟

- syntax check با `php -l`
- نصب dependencyها
- اجرای تست‌ها
- شاید بعداً static analysis یا code style

## ایدهٔ workflow ساده

در GitHub Actions می‌توانی یک workflow بسازی که روی push یا pull request اجرا شود.

### نمونهٔ ساده

```yaml
name: ci

on:
  push:
  pull_request:

jobs:
  test:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v4

      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'

      - name: Validate composer files
        run: composer validate

      - name: Install dependencies
        run: composer install --no-interaction --prefer-dist

      - name: Lint PHP files
        run: |
          find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -0 -n1 php -l

      - name: Run tests
        run: composer test
```

## این workflow چه ارزشی می‌دهد؟

- اگر syntax خراب باشد سریع می‌فهمی
- اگر dependency نصب نشود معلوم می‌شود
- اگر تستی شکسته باشد قبل از merge دیده می‌شود

## چرا CI حتی برای پروژهٔ آموزشی مفید است؟

چون از همین الان عادت می‌کنی که کیفیت فقط با «روی لپ‌تاپ من کار می‌کند» سنجیده نشود.

## نکتهٔ عملی

اگر هنوز تست نداری، CI را با `php -l` و `composer validate` شروع کن. بعد کم‌کم تست و تحلیل‌های دیگر را اضافه کن.

## تمرین

1. توضیح بده CI چه مشکلی را در همکاری تیمی حل می‌کند.
2. سه مرحلهٔ اولیه برای workflow پروژهٔ PHP بنویس.
3. بگو چرا اجرای `composer test` در CI ارزش دارد.

## جمع‌بندی

CI یک نگهبان خودکار برای پروژه است. لازم نیست از روز اول پیچیده باشد؛ حتی یک workflow ساده که `composer validate`، `php -l` و تست‌ها را اجرا کند، قدم بزرگی به سمت انتشار مطمئن‌تر است.
