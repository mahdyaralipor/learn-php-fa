---
title: "scope و global"
weight: 5
---

# scope و global

متغیر داخل تابع با متغیر بیرون تابع یکی نیست — مگر این‌که بدانی چه می‌کنی.
این درس محدودهٔ متغیر (scope) و `global` و `static` را توضیح می‌دهد.

## scope محلی

```php
<?php

function demo(): void
{
    $message = 'داخل تابع';
    echo $message . PHP_EOL;
}

demo();
// echo $message; // خطا — تعریف نشده
```

متغیر داخل تابع فقط همان‌جا دیده می‌شود.

## scope سراسری (global) در سطح فایل

```php
<?php

$appName = 'دوره PHP';

function showApp(): void
{
    // echo $appName; // Warning — داخل تابع دیده نمی‌شود
}
```

## دسترسی با `global`

```php
<?php

$counter = 0;

function incrementGlobal(): void
{
    global $counter;
    $counter++;
}

incrementGlobal();
incrementGlobal();
echo $counter . PHP_EOL; // 2
```

`global` توصیهٔ روزمره نیست — کد را درهم‌تنیده می‌کند.

## راه بهتر: پارامتر و return

```php
<?php

function increment(int $value): int
{
    return $value + 1;
}

$counter = 0;
$counter = increment($counter);
$counter = increment($counter);
echo $counter . PHP_EOL;
```

## `$GLOBALS`

```php
<?php

$counter = 5;

function readCounter(): void
{
    echo $GLOBALS['counter'] . PHP_EOL;
}

readCounter();
```

باز هم برای کد جدید کمتر توصیه می‌شود.

## static داخل تابع

```php
<?php

function counter(): int
{
    static $count = 0;
    $count++;
    return $count;
}

echo counter() . PHP_EOL; // 1
echo counter() . PHP_EOL; // 2
echo counter() . PHP_EOL; // 3
```

`static` مقدار را **بین فراخوانی‌ها** نگه می‌دارد، اما از بیرون قابل دسترسی نیست.

## کاربرد static: شمارنده یا cache ساده

```php
<?php

function nextId(): int
{
    static $id = 100;
    return ++$id;
}

echo nextId() . PHP_EOL;
echo nextId() . PHP_EOL;
```

## تفاوت static و global

| | static | global |
|---|--------|--------|
| محل | داخل تابع | سطح فایل |
| دسترسی بیرون | خیر | بله |
| کاربرد رایج | شمارندهٔ داخلی | state مشترک (کمتر توصیه) |

## closure و `use`

```php
<?php

$factor = 2;

$double = function (int $n) use ($factor): int {
    return $n * $factor;
};

echo $double(5) . PHP_EOL;
```

برای capture مقدار بیرون بدون global از `use` استفاده می‌شود.

## capture by reference

```php
<?php

$total = 0;

$add = function (int $n) use (&$total): void {
    $total += $n;
};

$add(10);
$add(5);
echo $total . PHP_EOL; // 15
```

## ثابت‌ها در scope

```php
<?php

const TAX = 0.09;

function priceWithTax(int $price): float
{
    return $price * (1 + TAX);
}

echo priceWithTax(100000) . PHP_EOL;
```

ثابت‌ها scope سراسری دارند و داخل تابع بدون `global` دیده می‌شوند.

## مثال بد: state با global

```php
<?php

$cartTotal = 0;

function addToCart(int $price): void
{
    global $cartTotal;
    $cartTotal += $price;
}
```

مشکل: هر جای برنامه می‌تواند `$cartTotal` را عوض کند.

## مثال بهتر: تابع خالص‌تر

```php
<?php

function addToTotal(int $current, int $price): int
{
    return $current + $price;
}

$cartTotal = 0;
$cartTotal = addToTotal($cartTotal, 50000);
$cartTotal = addToTotal($cartTotal, 120000);
echo $cartTotal . PHP_EOL;
```

## اشتباهات رایج

### 1. فرض کردن متغیر بیرون داخل تابع دیده می‌شود

### 2. استفادهٔ زیاد از `global`

### 3. اشتباه گرفتن `static` با متغیر معمولی محلی

### 4. فراموش کردن `use` در closure

## تمرین

1. تابعی با `static` بنویس که شمارندهٔ فراخوانی باشد.
2. همان شمارنده را بدون static با پارامتر و return پیاده کن.
3. closure با `use` بنویس که ضریب ثابت را اعمال کند.
4. مثال `global` بنویس و بعد نسخهٔ بهتر با پارامتر بساز.
5. توضیح بده چرا `static` برای شمارندهٔ داخلی از `global` بهتر است.

## جمع‌بندی

متغیر داخل تابع محلی است؛ `global` دسترسی به متغیر سراسری می‌دهد اما معمولاً پارامتر و return تمیزترند.
`static` state را بین فراخوانی‌ها نگه می‌دارد و closure با `use` مقدار بیرون را capture می‌کند.
با پایان این فصل، آمادهٔ فصل OOP و پروژه‌های عملی هستی.
