---
title: "متغیرها"
weight: 3
---

# متغیرها

متغیر جایی است که مقدار را با یک نام نگه می‌داری.
در PHP تعریف متغیر ساده است، اما رفتارش کمی با زبان‌هایی مثل Java یا TypeScript فرق دارد.

## تعریف اولین متغیر

```php
<?php

$name = "علی";
$age = 28;
$isActive = true;

echo $name . PHP_EOL;
echo $age . PHP_EOL;
var_dump($isActive);
```

## قوانین نام‌گذاری

- نام با `$` شروع می‌شود: `$count`
- بعد از `$` باید حرف یا `_` بیاید: `$user_name`
- حساس به حروف بزرگ و کوچک است: `$Name` و `$name` متفاوت‌اند
- کلمات رزرو شده مثل `if` و `echo` نام متغیر نیستند

نام‌های خوانا:

```php
<?php

$totalPrice = 150000;
$hasDiscount = false;
$userEmail = "sara@example.com";
```

## تایپ پویا

در PHP لازم نیست هنگام تعریف نوع را بنویسی:

```php
<?php

$value = 10;       // int
$value = "ده";    // حالا string
$value = 3.14;    // حالا float
```

همین انعطاف مزیت است و گاهی منبع باگ.
به همین دلیل در کد حرفه‌ای بیشتر سراغ type hint و `strict_types` می‌رویم.

## مقداردهی و به‌روزرسانی

```php
<?php

$score = 10;
$score = $score + 5;
$score += 2;

echo $score . PHP_EOL; // 17
```

## چند متغیر در یک خط

```php
<?php

$a = $b = $c = 0;
```

قابل استفاده است، اما برای خوانایی بهتر است جدا بنویسی.

## ثابت‌ها با `const` و `define`

```php
<?php

const APP_NAME = "فروشگاه من";
define("MAX_LOGIN_ATTEMPTS", 5);

echo APP_NAME . PHP_EOL;
echo MAX_LOGIN_ATTEMPTS . PHP_EOL;
```

ثابت بعد از تعریف عوض نمی‌شود.

تفاوت کوتاه:

- `const` در زمان کامپایل تعریف می‌شود و داخل کلاس هم رایج است
- `define` تابع است و می‌تواند شرطی تعریف شود

## unset کردن متغیر

```php
<?php

$temp = "موقت";
unset($temp);

// echo $temp; // خطا: متغیر تعریف‌نشده
```

## متغیرهای سوپرگلوبال (معرفی کوتاه)

PHP چند متغیر سراسری از پیش آماده دارد:

- `$_GET`
- `$_POST`
- `$_SERVER`
- `$_SESSION`

فعلاً لازم نیست عمیق شوی؛ فقط بدان وجود دارند و در فصل وب استفاده می‌شوند.

## مقدار پیش‌فرض و null

```php
<?php

$nickname = null;
$city = "تهران";

var_dump($nickname); // NULL
var_dump($city);     // string
```

`null` یعنی «مقدار ندارد» یا «خالی است».

## unset و isset

```php
<?php

$email = "ali@example.com";

var_dump(isset($email));   // true
unset($email);
var_dump(isset($email));   // false
```

`isset($x)` یعنی «آیا `$x` تعریف شده و null نیست؟»

## مثال کاربردی

```php
<?php

$productName = "ماوس";
$unitPrice = 350000;
$quantity = 2;

$subtotal = $unitPrice * $quantity;
$shipping = 45000;
$total = $subtotal + $shipping;

echo "محصول: {$productName}" . PHP_EOL;
echo "جمع جزء: {$subtotal}" . PHP_EOL;
echo "هزینه ارسال: {$shipping}" . PHP_EOL;
echo "مبلغ نهایی: {$total}" . PHP_EOL;
```

## اشتباهات رایج

### 1. استفاده از متغیر تعریف‌نشده

```php
echo $username; // Warning
```

### 2. اشتباه گرفتن `=` با `==`

```php
if ($status = "active") { // مقداردهی، نه مقایسه
}
```

### 3. نام‌های مبهم

```php
$d = 10;
$x = "sara";
```

بهتر:

```php
$discountPercent = 10;
$username = "sara";
```

### 4. فراموش کردن `$`

```php
name = "رضا"; // خطای parse
$name = "رضا"; // درست
```

## تمرین

1. متغیرهای `firstName`, `lastName`, `birthYear` بساز و سن تقریبی چاپ کن.
2. ثابت `TAX_RATE` با مقدار `0.09` تعریف کن و قیمت با مالیات حساب کن.
3. یک متغیر بساز، `unset` کن و بعد `isset` را روی آن تست کن.
4. عمداً یک متغیر تعریف‌نشده را `echo` کن و پیام هشدار را ببین.
5. سه نام بد و سه نام خوب برای متغیر بنویس و تفاوت را توضیح بده.

## جمع‌بندی

متغیر نامِ مقدار است، PHP تایپ پویا دارد، ثابت‌ها با `const` یا `define` تعریف می‌شوند و `isset`/`unset` برای بررسی وجود مقدار به‌کار می‌روند.
در درس بعدی انواع داده و رفتار تبدیل نوع را عمیق بررسی می‌کنیم.
