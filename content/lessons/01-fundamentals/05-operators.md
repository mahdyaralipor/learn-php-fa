---
title: "عملگرها"
weight: 5
---

# عملگرها

عملگرها ابزار انجام محاسبه، مقایسه و تصمیم‌گیری هستند.
در PHP تعدادشان زیاد است؛ اینجا مهم‌ترین‌ها را با تمرکز روی موارد مدرن PHP 8+ می‌بینیم.

## عملگرهای حسابی

```php
<?php

$a = 10;
$b = 3;

echo $a + $b . PHP_EOL;  // 13
echo $a - $b . PHP_EOL;  // 7
echo $a * $b . PHP_EOL;  // 30
echo $a / $b . PHP_EOL;  // 3.333...
echo $a % $b . PHP_EOL;  // 1 باقیمانده
echo $a ** $b . PHP_EOL; // 1000 توان
```

### تقسیم و نوع

```php
<?php

var_dump(10 / 4);   // float(2.5)
var_dump(10 / 2);   // int(5) در PHP 8+
```

## عملگرهای ترکیبی

```php
<?php

$count = 5;
$count += 2;  // 7
$count -= 1;  // 6
$count *= 2;  // 12
$count /= 3;  // 4
$count %= 3;  // 1
```

## الحاق رشته: `.`

```php
<?php

$first = "سلام";
$second = "دنیا";

echo $first . " " . $second . PHP_EOL;
```

`.=` هم وجود دارد:

```php
<?php

$message = "سلام";
$message .= " رضا";
echo $message . PHP_EOL;
```

## عملگرهای مقایسه

```php
<?php

var_dump(5 > 3);    // true
var_dump(5 >= 5);   // true
var_dump(3 < 10);   // true
var_dump(4 <= 4);   // true
var_dump(5 == 5);   // true (سست)
var_dump(5 === 5);  // true (سخت‌گیرانه)
var_dump(5 != 4);   // true
var_dump(5 !== "5"); // true
```

یادآوری: برای مقایسهٔ مقدار، `===` را ترجیح بده.

## عملگرهای منطقی

```php
<?php

$isLoggedIn = true;
$isAdmin = false;

var_dump($isLoggedIn && $isAdmin); // false
var_dump($isLoggedIn || $isAdmin); // true
var_dump(!$isAdmin);               // true
```

### کوتاه‌مدار (Short-circuit)

```php
<?php

function expensiveCheck(): bool
{
    echo "اجرا شد" . PHP_EOL;
    return false;
}

false && expensiveCheck(); // expensiveCheck اصلاً اجرا نمی‌شود
true || expensiveCheck();  // این هم اجرا نمی‌شود
```

## عملگرهای افزایش/کاهش

```php
<?php

$i = 5;
echo ++$i . PHP_EOL; // 6 — پیش‌افزایش
echo $i++ . PHP_EOL; // 6 چاپ، بعد 7 می‌شود
echo --$i . PHP_EOL; // 6
echo $i-- . PHP_EOL; // 6 چاپ، بعد 5 می‌شود
```

## عملگر سه‌تایی `? :`

```php
<?php

$age = 17;
$status = ($age >= 18) ? "بزرگسال" : "نوجوان";

echo $status . PHP_EOL;
```

## Null Coalescing: `??`

اگر سمت چپ `null` باشد یا اصلاً تعریف نشده باشد، مقدار سمت راست برمی‌گردد:

```php
<?php

$username = $_GET['user'] ?? 'مهمان';
echo $username . PHP_EOL;
```

بدون `??` باید `isset` می‌نوشتی.

## Null Coalescing Assignment: `??=`

```php
<?php

$config = [];
$config['theme'] ??= 'light';
$config['theme'] ??= 'dark';

var_dump($config['theme']); // string(5) "light"
```

فقط وقتی مقدار `null` یا تعریف‌نشده باشد، مقداردهی می‌کند.

## Nullsafe Operator: `?->`

برای دسترسی امن به property یا method وقتی ممکن است شیء `null` باشد:

```php
<?php

class Profile
{
    public function __construct(public ?string $bio = null)
    {
    }
}

class User
{
    public function __construct(public ?Profile $profile = null)
    {
    }
}

$user = new User(null);

echo $user?->profile?->bio ?? 'بیوگرافی ندارد';
echo PHP_EOL;
```

بدون `?->` خطای تلاش برای دسترسی به property روی `null` می‌گیری.

## Spaceship Operator: `<=>`

```php
<?php

var_dump(5 <=> 10);  // -1
var_dump(10 <=> 10); // 0
var_dump(20 <=> 10); // 1
```

برای مرتب‌سازی آرایه‌ها مفید است.

## عملگر `@` (سرکوب خطا)

```php
<?php

@file_get_contents('missing.txt');
```

توصیه نمی‌شود.
به‌جای سرکوب خطا، شرط یا exception بنویس.

## اولویت عملگرها

```php
<?php

$result = 2 + 3 * 4;     // 14 نه 20
$ok = true && false || true; // true

echo $result . PHP_EOL;
var_dump($ok);
```

وقتی شک داشتی، پرانتز بگذار:

```php
<?php

$result = (2 + 3) * 4; // 20
```

## مثال ترکیبی

```php
<?php

$items = 3;
$pricePerItem = 120000;
$discountPercent = 10;

$subtotal = $items * $pricePerItem;
$discount = ($discountPercent / 100) * $subtotal;
$total = $subtotal - $discount;

$label = $total > 0 ? "مبلغ قابل پرداخت" : "رایگان";
echo "{$label}: {$total}" . PHP_EOL;
```

## اشتباهات رایج

### 1. `+` به‌جای `.` برای رشته

```php
"5" + "2"; // int(7)
"5" . "2"; // string(2) "52"
```

### 2. مقایسه با `==` به‌جای `===`

### 3. فراموش کردن پرانتز در شرط‌های ترکیبی

### 4. استفاده از `@` برای پنهان کردن مشکل واقعی

## تمرین

1. برنامه‌ای بنویس که قیمت، تخفیف و مالیات را با عملگرهای ترکیبی حساب کند.
2. با `??` مقدار پیش‌فرض برای `city` و `country` از آرایه بگیر.
3. با `??=` یک آرایهٔ تنظیمات را فقط یک‌بار مقداردهی کن.
4. سه مقایسه با `<=>` انجام بده و خروجی `-1, 0, 1` را ببین.
5. عبارت `true && false || true` را با و بدون پرانتز اجرا کن.

## جمع‌بندی

عملگرهای حسابی، مقایسه‌ای و منطقی پایهٔ هر برنامه‌اند.
در PHP مدرن `??`، `??=` و `?->` کار با مقدارهای اختیاری و null را خیلی ساده‌تر کرده‌اند.
در درس بعدی عمیق‌تر سراغ رشته‌ها می‌رویم.
