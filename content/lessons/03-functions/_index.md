---
title: "توابع"
weight: 3
---

# فصل سوم: توابع

تا اینجا کد را خط‌به‌خط در یک فایل نوشتی.
وقتی همان منطق را چند بار نیاز داری، یا می‌خواهی برنامه را مرتب‌تر کنی، وقتش رسیده سراغ **تابع** بروی.

تابع بلوکی از کد است با یک نام — می‌توانی هر چند بار بخواهی صدایش بزنی.

## چرا تابع؟

- **تکرار نکن**: منطق یک‌بار نوشته می‌شود
- **خوانایی**: `calculateTotal()` از ده خط کد inline گویاتر است
- **تست**: تابع کوچک را جدا آزمودن راحت‌تر است
- **سازماندهی**: فایل بزرگ به قطعات معنی‌دار تقسیم می‌شود

## در این فصل چه می‌گیری؟

- تعریف و فراخوانی تابع
- پارامتر، مقدار پیش‌فرض و `return`
- type hint و return type
- variadic و unpacking با `...`
- scope محلی، global و `static`

## پیش‌نیاز

- متغیر و نوع داده
- شرط و حلقه
- آرایه (برای variadic/unpacking)

## ترتیب پیشنهادی مطالعه

1. [تعریف و فراخوانی](/learn-php-fa/lessons/03-functions/01-define-call/)
2. [پارامتر و return](/learn-php-fa/lessons/03-functions/02-parameters-return/)
3. [type hint](/learn-php-fa/lessons/03-functions/03-type-hints/)
4. [variadic و unpacking](/learn-php-fa/lessons/03-functions/04-variadic-unpacking/)
5. [scope و global](/learn-php-fa/lessons/03-functions/05-scope-globals/)

## یک پیش‌نمایش

```php
<?php

declare(strict_types=1);

function greet(string $name, string $title = 'آقا/خانم'): string
{
    return "سلام {$title} {$name}";
}

echo greet('رضا') . PHP_EOL;
echo greet('سارا', 'خانم') . PHP_EOL;
```

## قانون‌های طلایی فصل

- تابع کوچک و یک‌کاره بنویس
- نام تابع باید **فعل** باشد: `calculate`, `validate`, `format`
- تا جایی ممکن است type hint بزن
- از global زیاد استفاده نکن — داده را پارامتر بده

## اشتباه رایج

نوشتن تابع ۸۰ خطی که «همه‌کاره» است.
بهتر است بشکنیش به چند تابع کوچک.

## تمرین قبل از شروع

این کد را بخوان و بگو خروجی چیست:

```php
<?php

function double(int $n): int
{
    return $n * 2;
}

echo double(5) + double(3) . PHP_EOL;
```

## خروجی مورد انتظار

بعد از این فصل باید بتوانی:

- تابع با پارامتر اختیاری و اجباری بنویسی
- نوع ورودی و خروجی مشخص کنی
- از `...$args` و spread استفاده کنی
- تفاوت scope محلی و global را بدانی

از درس بعدی اولین تابعت را تعریف می‌کنی.
