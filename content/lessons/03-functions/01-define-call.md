---
title: "تعریف و فراخوانی تابع"
weight: 1
---

# تعریف و فراخوانی تابع

تابع یعنی بلوک نام‌دار کد که هر وقت بخواهی اجرا می‌شود.
در PHP تعریف تابع ساده است و از همان ابتدا می‌توانی type hint هم اضافه کنی.

## ساده‌ترین تابع

```php
<?php

function sayHello(): void
{
    echo "سلام" . PHP_EOL;
}

sayHello();
sayHello();
```

`void` یعنی تابع چیزی برنمی‌گرداند.

## تابع با پارامتر

```php
<?php

function greet(string $name): void
{
    echo "سلام، {$name}" . PHP_EOL;
}

greet("علی");
greet("سارا");
```

## فراخوانی = صدا زدن

وقتی `greet("علی")` می‌نویسی، می‌گویی تابع را **فراخوانی** کن.
PHP اجرا را به بدنهٔ تابع می‌برد و بعد برمی‌گردد.

## ترتیب تعریف

در PHP معمولاً لازم نیست تابع را قبل از فراخوانی تعریف کنی — مگر در شرایط خاص:

```php
<?php

greet("رضا"); // کار می‌کند

function greet(string $name): void
{
    echo $name . PHP_EOL;
}
```

## نام‌گذاری

- با حرف یا `_` شروع شود
- معمولاً camelCase: `calculateTotal`, `isValidEmail`
- فعل باشد نه اسم: `user` بد، `getUser` خوب

## تابع که مقدار برمی‌گرداند

```php
<?php

function add(int $a, int $b): int
{
    return $a + $b;
}

$sum = add(3, 5);
echo $sum . PHP_EOL;
```

## early return

```php
<?php

function absolute(int $n): int
{
    if ($n < 0) {
        return -$n;
    }

    return $n;
}
```

## تابع و echo

```php
<?php

// فقط چاپ می‌کند
function printPrice(int $price): void
{
    echo number_format($price) . PHP_EOL;
}

// مقدار برمی‌گرداند — انعطاف‌پذیرتر
function formatPrice(int $price): string
{
    return number_format($price);
}

echo formatPrice(1500000) . PHP_EOL;
```

تابعی که **مقدار** برمی‌گرداند معمولاً قابل‌استفاده‌تر است.

## Arrow Function (توابع کوتاه)

```php
<?php

$double = fn(int $n): int => $n * 2;

echo $double(4) . PHP_EOL;
```

برای callback یک‌خطی عالی است.
متغیرهای بیرون را به‌صورت خودکار capture می‌کند.

## تابع ناشناس (closure)

```php
<?php

$greet = function (string $name): void {
    echo "سلام {$name}" . PHP_EOL;
};

$greet("نگار");
```

## callable

```php
<?php

function apply(int $value, callable $operation): int
{
    return $operation($value);
}

$result = apply(5, fn(int $n): int => $n * $n);
echo $result . PHP_EOL;
```

## مثال کاربردی

```php
<?php

function isEven(int $number): bool
{
    return $number % 2 === 0;
}

function printNumbersStatus(int $from, int $to): void
{
    for ($i = $from; $i <= $to; $i++) {
        $status = isEven($i) ? 'زوج' : 'فرد';
        echo "{$i}: {$status}" . PHP_EOL;
    }
}

printNumbersStatus(1, 5);
```

## اشتباهات رایج

### 1. فراموش کردن پرانتز در فراخوانی

```php
greet; // اشتباه — فقط به تابع اشاره می‌کند
greet(); // درست اگر پارامتر ندارد
```

### 2. تعداد پارامتر اشتباه

```php
add(1); // ArgumentCountError در PHP 8+
```

### 3. استفاده از تابع برای side effect زیاد

### 4. نام مبهم مثل `doStuff()`

## تمرین

1. تابع `square` بنویس که مربع عدد را برگرداند.
2. تابع `maxOfThree` برای بزرگ‌ترین سه عدد بنویس.
3. با arrow function آرایهٔ اعداد را دوبرابر کن (`array_map`).
4. تابعی بنویس که فقط `"OK"` چاپ کند و نوع بازگشتی `void` داشته باشد.
5. تابع `greet` را بعد از فراخوانی تعریف کن و ببین کار می‌کند.

## جمع‌بندی

تابع با `function` تعریف و با نامش فراخوانی می‌شود؛ `return` مقدار برمی‌گرداند و `void` یعنی بدون خروجی.
arrow function برای callback کوتاه و closure برای توابع ناشناس به‌کار می‌رود.
در درس بعدی پارامترها و `return` را عمیق‌تر می‌بینیم.
