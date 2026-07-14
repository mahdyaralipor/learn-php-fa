---
title: "پروژه ۱: ماشین‌حساب CLI"
weight: 1
---

# پروژه: ماشین‌حساب خط فرمان

اولین پروژهٔ دوره عمداً کوچک است — اما همهٔ چیزهایی که در سه فصل اول یاد گرفتی را کنار هم می‌گذارد:
متغیر، نوع داده، شرط، `match`، تابع، type hint و ورودی از خط فرمان.

## هدف پروژه

یک اسکریپت PHP بساز که از ترمینال دو عدد و یک عملگر بگیرد و نتیجه را چاپ کند.

مثال اجرا:

```bash
php calculator.php 10 + 5
```

خروجی:

```text
نتیجه: 15
```

## پیش‌نیاز

- فصل ۱: مبانی (متغیر، نوع، عملگر)
- فصل ۲: جریان کنترل (`match`, شرط)
- فصل ۳: توابع و type hint

## ساختار پیشنهادی

```text
projects/cli-calculator/
  calculator.php
```

فعلاً یک فایل کافی است. بعداً می‌توانی توابع را به فایل جدا منتقل کنی.

## گام ۱: خواندن آرگومان‌ها

PHP آرگومان‌های خط فرمان را در `$argv` می‌دهد:

```php
<?php

declare(strict_types=1);

print_r($argv);
```

```bash
php calculator.php 10 + 5
```

خروجی شبیه:

```text
Array
(
    [0] => calculator.php
    [1] => 10
    [2] => +
    [3] => 5
)
```

## گام ۲: اعتبارسنجی ورودی

```php
<?php

declare(strict_types=1);

function usage(): void
{
    echo "استفاده: php calculator.php <عدد> <عملگر> <عدد>" . PHP_EOL;
    echo "عملگرهای مجاز: + - * /" . PHP_EOL;
}

if ($argc !== 4) {
    usage();
    exit(1);
}

[, $leftRaw, $operator, $rightRaw] = $argv;
```

## گام ۳: تبدیل به عدد

```php
<?php

declare(strict_types=1);

function toNumber(string $value): ?float
{
    if (!is_numeric($value)) {
        return null;
    }

    return (float) $value;
}
```

با `strict_types` هنوز می‌توانی از `is_numeric` برای بررسی رشتهٔ ورودی استفاده کنی، بعد cast صریح بزنی.

## گام ۴: تابع محاسبه با `match`

```php
<?php

declare(strict_types=1);

function calculate(float $left, string $operator, float $right): ?float
{
    return match ($operator) {
        '+' => $left + $right,
        '-' => $left - $right,
        '*' => $left * $right,
        '/' => $right === 0.0 ? null : $left / $right,
        default => null,
    };
}
```

تقسیم بر صفر را با `null` نشان می‌دهیم تا برنامه کرش نکند.

## گام ۵: نسخهٔ کامل

```php
<?php

declare(strict_types=1);

function usage(): void
{
    echo "استفاده: php calculator.php <عدد> <عملگر> <عدد>" . PHP_EOL;
    echo "عملگرهای مجاز: + - * /" . PHP_EOL;
}

function toNumber(string $value): ?float
{
    if (!is_numeric($value)) {
        return null;
    }

    return (float) $value;
}

function calculate(float $left, string $operator, float $right): ?float
{
    return match ($operator) {
        '+' => $left + $right,
        '-' => $left - $right,
        '*' => $left * $right,
        '/' => $right === 0.0 ? null : $left / $right,
        default => null,
    };
}

if ($argc !== 4) {
    usage();
    exit(1);
}

[, $leftRaw, $operator, $rightRaw] = $argv;

$left = toNumber($leftRaw);
$right = toNumber($rightRaw);

if ($left === null || $right === null) {
    echo "خطا: هر دو طرف باید عدد معتبر باشند." . PHP_EOL;
    exit(1);
}

$result = calculate($left, $operator, $right);

if ($result === null) {
    if ($operator === '/') {
        echo "خطا: تقسیم بر صفر مجاز نیست." . PHP_EOL;
    } else {
        echo "خطا: عملگر نامعتبر است. فقط + - * /" . PHP_EOL;
    }
    exit(1);
}

echo "نتیجه: {$result}" . PHP_EOL;
```

## تست دستی

```bash
php calculator.php 10 + 5
php calculator.php 20 - 3
php calculator.php 4 '*' 8
php calculator.php 100 / 4
php calculator.php 10 / 0
php calculator.php 10 % 3
php calculator.php hello + 2
```

آخرین دو مورد باید پیام خطای مناسب بدهند.

## بهبودهای پیشنهادی

### ۱. فرمت خروجی

```php
<?php

function formatResult(float $value): string
{
    if (floor($value) === $value) {
        return (string) (int) $value;
    }

    return number_format($value, 2, '.', '');
}
```

### ۲. حالت تعاملی

اگر آرگومان نداد، از کاربر بپرس:

```php
<?php

function prompt(string $message): string
{
    echo $message;
    $line = fgets(STDIN);
    return trim($line === false ? '' : $line);
}
```

### ۳. تاریخچهٔ عملیات

آخرین N عملیات را در آرایه نگه دار و با دستور `history` نمایش بده.

### ۴. variadic — جمع چند عدد

```php
<?php

function sumAll(float ...$numbers): float
{
    return array_sum($numbers);
}
```

## اشتباهات رایج

### 1. فراموش کردن `$argc` و `$argv`

### 2. استفاده از `==` برای بررسی تقسیم بر صفر

```php
if ($right == 0) // "0.0" هم مشکل‌ساز است با نوع‌های مختلف
```

بهتر: `$right === 0.0`

### 3. echo کردن float بدون فرمت

`0.1 + 0.2` ممکن است خروجی اعشاری عجیب بدهد — برای نمایش کاربر `number_format` مفید است.

### 4. عملگر `*` در شل

در bash گاهی `*` باید escape یا داخل کوتیشن باشد:

```bash
php calculator.php 4 '*' 8
```

## تمرین

1. عملگر `%` (باقیمانده) را اضافه کن.
2. اگر هیچ آرگومانی نبود، حالت تعاملی (prompt) اجرا شود.
3. تابع `formatResult` را برای نمایش اعداد صحیح بدون `.00` پیاده کن.
4. دستور `history` برای نمایش آخرین ۵ عملیات بساز.
5. نسخه‌ای بنویس که سه عدد و دو عملگر بگیرد (مثلاً `10 + 5 * 2`) — راهنمایی: فعلاً فقط چپ به راست حساب کن.

## جمع‌بندی

ماشین‌حساب CLI پل بین درس و پروژه است: ورودی واقعی، اعتبارسنجی، تابع با type hint و `match` برای انتخاب عملگر.
اگر این پروژه را خودت کامل کردی، برای [پروژه ۲: دفتر نمرات](/learn-php-fa/lessons/projects/02-gradebook/) آماده‌ای — بعد از فصل شی‌گرایی.
