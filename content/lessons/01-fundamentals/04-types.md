---
title: "نوع‌های داده"
weight: 4
---

# نوع‌های داده

PHP چند نوع اصلی دارد.
اگر این درس را جدی بگیری، خیلی از باگ‌های عجیب بعدی اصلاً پیش نمی‌آیند — مخصوصاً جایی که `==` و `===` وارد میدان می‌شوند.

## انواع اصلی

| نوع | مثال | توضیح کوتاه |
|-----|------|-------------|
| `int` | `42`, `-7` | عدد صحیح |
| `float` | `3.14`, `1.2e3` | عدد اعشاری |
| `string` | `"سلام"`, `'PHP'` | رشته |
| `bool` | `true`, `false` | درست/نادرست |
| `array` | `[1, 2]`, `['a' => 1]` | آرایه |
| `null` | `null` | بدون مقدار |
| `object` | `new User()` | شیء |
| `callable` | `fn() => 1` | قابل فراخوانی |
| `resource` | فایل باز | منبع سیستمی |

در PHP 8+ این‌ها هم هست: `mixed`, union types, `enum` — فعلاً روی پایه‌ها تمرکز می‌کنیم.

## بررسی نوع

```php
<?php

$value = "42";

var_dump($value);
echo gettype($value) . PHP_EOL; // string

$number = 42;
var_dump(is_int($number));     // true
var_dump(is_string($number));  // false
```

توابع پرکاربرد:

- `is_int()`, `is_float()`, `is_string()`, `is_bool()`, `is_array()`, `is_null()`
- `gettype()` نوع را به‌صورت رشته برمی‌گرداند

## تبدیل صریح (Casting)

```php
<?php

$text = "123";
$number = (int) $text;
$price = (float) "19.99";
$flag = (bool) 1;

var_dump($number); // int(123)
var_dump($price);  // float(19.99)
var_dump($flag);   // bool(true)
```

Castهای رایج:

```php
(int) $x
(float) $x
(string) $x
(bool) $x
(array) $x
```

## Type Juggling یعنی چه؟

**Type juggling** یعنی PHP در بعضی موقعیت‌ها نوع را خودکار عوض می‌کند.

مثال:

```php
<?php

$result = "5" + 2;
var_dump($result); // int(7)
```

رشتهٔ `"5"` به عدد تبدیل شد و جمع انجام گرفت.

مثال دیگر:

```php
<?php

$value = "0";

if ($value) {
    echo "truthy";
} else {
    echo "falsy";
}
```

خروجی: `falsy` — چون `"0"` در PHP یکی از مقادیر falsy است.

## مقادیر Falsy در PHP

این‌ها در شرط «نادرست» حساب می‌شوند:

- `false`
- `0` (int)
- `0.0` (float)
- `"0"` (string)
- `""` (رشته خالی)
- `[]` (آرایه خالی)
- `null`

بقیهٔ مقادیر معمولاً truthy هستند.

## مقایسه: `==` در برابر `===`

### `==` (برابری سست)

مقدار را مقایسه می‌کند و در صورت نیاز **تبدیل نوع** انجام می‌دهد.

```php
<?php

var_dump(5 == "5");    // true
var_dump(0 == false);  // true
var_dump(0 == "0");    // true
var_dump(1 == true);   // true
var_dump("" == false); // true
var_dump("0" == false);// true  ← خطرناک
```

### `===` (برابری سخت‌گیرانه)

هم **مقدار** و هم **نوع** باید یکی باشند.

```php
<?php

var_dump(5 === "5");    // false
var_dump(0 === false);  // false
var_dump(0 === "0");    // false
var_dump(1 === true);   // false
var_dump("" === false); // false
var_dump("0" === false);// false
```

### قانون طلایی

**به‌صورت پیش‌فرض `===` و `!==` را استفاده کن.**

`==` فقط وقتی که عمداً می‌خواهی تبدیل نوع انجام شود — و آن هم نادر است.

## مثال باگ واقعی با `==`

```php
<?php

$input = "0";

if ($input == false) {
    echo "ورودی نامعتبر است";
}
```

کاربر `"0"` وارد کرده، اما برنامه فکر می‌کند ورودی خالی/نامعتبر است.

نسخهٔ درست:

```php
<?php

$input = "0";

if ($input === "" || $input === null) {
    echo "ورودی نامعتبر است";
}
```

یا اگر می‌خواهی فقط خالی بودن را بگیری:

```php
<?php

if ($input === "") {
    echo "ورودی خالی است";
}
```

## `!=` و `!==`

```php
<?php

var_dump(5 != "5");   // false  (چون == برابر است)
var_dump(5 !== "5");  // true   (نوع فرق دارد)
```

## مقایسهٔ رشته و عدد

```php
<?php

var_dump("10" > "2");   // false! مقایسه lexicographic
var_dump(10 > 2);         // true
var_dump((int)"10" > 2); // true
```

وقتی هر دو طرف رشته باشند، PHP به‌صورت **lexicographic** مقایسه می‌کند نه عددی.

## `strcmp` و مقایسهٔ دقیق‌تر رشته

```php
<?php

var_dump(strcmp("abc", "abc")); // 0 یعنی برابر
var_dump(strcmp("abc", "abd")); // منفی
```

## جدول مقایسه‌های گیج‌کننده

```php
<?php

$tests = [
    ['0', false],
    ['0', 0],
    ['', false],
    ['00', 0],
    ['0e123', 0],
    [0, ''],
];

foreach ($tests as [$a, $b]) {
    $loose = $a == $b ? 'true' : 'false';
    $strict = $a === $b ? 'true' : 'false';
    echo var_export($a, true) . " vs " . var_export($b, true);
    echo " => ==: {$loose}, ===: {$strict}" . PHP_EOL;
}
```

این جدول را خودت اجرا کن.
تفاوت `==` و `===` را با چشم می‌بینی.

## `0e123` چیست؟

`"0e123"` در PHP به‌صورت عدد علمی با مقدار **صفر** تفسیر می‌شود.
به همین دلیل `"0e123" == 0` برقرار است — یکی از دلایلی که `==` خطرناک است.

## تبدیل به bool

```php
<?php

var_dump((bool) 1);      // true
var_dump((bool) 0);      // false
var_dump((bool) -1);     // true
var_dump((bool) "");     // false
var_dump((bool) "0");    // false
var_dump((bool) "hello"); // true
var_dump((bool) []);     // false
var_dump((bool) [1]);    // true
```

## `intval`, `floatval`, `strval`

```php
<?php

var_dump(intval("42px"));   // 42 — فقط بخش عددی ابتدای رشته
var_dump(floatval("3.14")); // 3.14
var_dump(strval(100));      // "100"
```

## مثال کاربردی: اعتبارسنجی ساده

```php
<?php

$ageInput = "25";

if ($ageInput === "") {
    echo "سن وارد نشده" . PHP_EOL;
} elseif (!is_numeric($ageInput)) {
    echo "سن باید عدد باشد" . PHP_EOL;
} else {
    $age = (int) $ageInput;
    echo "سن شما: {$age}" . PHP_EOL;
}
```

## اشتباهات رایج

### 1. استفادهٔ پیش‌فرض از `==`

### 2. فرض کردن `"10" > "2"`

### 3. نادیده گرفتن falsy بودن `"0"`

### 4. تکیه بر تبدیل خودکار بدون `var_dump`

## تمرین

1. جدول ۱۰ مقایسه با `==` و `===` بساز و خروجی را یادداشت کن.
2. برنامه‌ای بنویس که اگر ورودی دقیقاً رشته `"0"` بود، پیام «صفر وارد شد» بدهد — نه «ورودی خالی».
3. سه مقدار truthy و سه مقدار falsy پیدا کن که شاید غافلگیرت کنند.
4. `"42px"` را به int تبدیل کن و نتیجه را با `var_dump` ببین.
5. تفاوت `"5" + 2` و `"5" . 2` را اجرا کن و توضیح بده.

## جمع‌بندی

نوع داده رفتار برنامه را تعیین می‌کند.
PHP گاهی خودکار نوع را عوض می‌کند (type juggling) و `==` هم این تبدیل را در مقایسه دخالت می‌دهد.
برای کد قابل اعتماد، `===` را عادت کن و هر وقت شک داشتی `var_dump` بزن.
در درس بعدی عملگرها — از جمله `??` و `?->` — را می‌بینیم.
