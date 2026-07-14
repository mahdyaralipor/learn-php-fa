---
title: "switch و match"
weight: 2
---

# switch و match

وقتی یک متغیر چند حالت مشخص دارد، نوشتن پشت‌سرهم `elseif` خسته‌کننده می‌شود.
اینجا `switch` و در PHP 8+ تابع `match` به کمک می‌آیند.

## switch پایه

```php
<?php

$day = 'sat';

switch ($day) {
    case 'sat':
        echo 'شنبه' . PHP_EOL;
        break;
    case 'sun':
        echo 'یکشنبه' . PHP_EOL;
        break;
    default:
        echo 'روز دیگر' . PHP_EOL;
}
```

`break` یعنی «از switch خارج شو».
بدون `break` اجرا به case بعدی **می‌افتد** (fall-through).

## Fall-through عمدی

```php
<?php

$level = 'gold';

switch ($level) {
    case 'gold':
    case 'silver':
        echo 'تخفیف ویژه' . PHP_EOL;
        break;
    default:
        echo 'بدون تخفیف' . PHP_EOL;
}
```

## switch با مقایسهٔ سست

`switch` از `==` استفاده می‌کند، نه `===`:

```php
<?php

$value = '1';

switch ($value) {
    case 1:
        echo 'یک'; // اجرا می‌شود!
        break;
}
```

اگر دقت نوع مهم است، `match` یا `if` با `===` بهتر است.

## match (PHP 8+)

`match` یک **عبارت** است و مقدار برمی‌گرداند.
مقایسه با `===` انجام می‌شود.

```php
<?php

$httpCode = 404;

$message = match ($httpCode) {
    200 => 'موفق',
    404 => 'پیدا نشد',
    500 => 'خطای سرور',
    default => 'کد ناشناخته',
};

echo $message . PHP_EOL;
```

## تفاوت‌های مهم switch و match

| ویژگی | switch | match |
|-------|--------|-------|
| مقایسه | `==` | `===` |
| خروجی | statement | expression (مقدار) |
| fall-through | بله (بدون break) | خیر |
| default | `default:` | `default =>` |
| چند حالت | با fall-through | `1, 2 => '...'` |

## چند مقدار در یک شاخهٔ match

```php
<?php

$role = 'editor';

$label = match ($role) {
    'admin', 'superadmin' => 'مدیر',
    'editor', 'author' => 'نویسنده',
    default => 'بازدیدکننده',
};

echo $label . PHP_EOL;
```

## match بدون default

اگر هیچ شاخه‌ای نخورد، `UnhandledMatchError` می‌گیری:

```php
<?php

$code = 418;

// $msg = match ($code) { 200 => 'ok' }; // خطا
```

پس یا `default` بگذار یا مطمئن شو همهٔ حالت‌ها پوشش داده شده‌اند.

## match با شرط (PHP 8.0+)

```php
<?php

$age = 25;

$category = match (true) {
    $age < 13 => 'کودک',
    $age < 18 => 'نوجوان',
    $age < 65 => 'بزرگسال',
    default => 'سالمند',
};

echo $category . PHP_EOL;
```

این الگو وقتی محدوده یا شرط پیچیده داری مفید است.

## مثال: وضعیت سفارش

```php
<?php

function orderStatusLabel(string $status): string
{
    return match ($status) {
        'pending' => 'در انتظار پرداخت',
        'paid' => 'پرداخت‌شده',
        'shipped' => 'ارسال‌شده',
        'cancelled' => 'لغوشده',
        default => 'نامشخص',
    };
}

echo orderStatusLabel('paid') . PHP_EOL;
```

## switch در کد واقعی

هنوز در پروژه‌های قدیمی زیاد دیده می‌شود.
برای کد جدید، وقتی می‌خواهی **مقدار** برگردانی، `match` خواناتر است.

## مثال تبدیل switch به match

قبل:

```php
<?php

switch ($type) {
    case 'pdf':
        $icon = 'file-pdf';
        break;
    case 'jpg':
    case 'png':
        $icon = 'file-image';
        break;
    default:
        $icon = 'file';
}
```

بعد:

```php
<?php

$icon = match ($type) {
    'pdf' => 'file-pdf',
    'jpg', 'png' => 'file-image',
    default => 'file',
};
```

## اشتباهات رایج

### 1. فراموش کردن `break` در switch

### 2. فرض کردن switch مثل `===` است

### 3. استفاده از switch فقط برای side effect وقتی match مناسب‌تر است

### 4. نداشتن `default` وقتی ورودی نامطمئن است

## تمرین

1. با `switch` روز هفته را به نام فارسی تبدیل کن.
2. همان منطق را با `match` بازنویسی کن.
3. تابعی بنویس که کد HTTP را به پیام فارسی با `match` تبدیل کند.
4. با `match (true)` سن را به دستهٔ سنی تبدیل کن.
5. مقدار `'1'` را در `switch` با case عددی `1` تست کن و نتیجه را توضیح بده.

## جمع‌بندی

`switch` برای چند شاخهٔ کلاسیک است اما `==` دارد و fall-through دارد.
`match` در PHP 8 مدرن‌تر، سخت‌گیرانه و expression است — برای انتخاب مقدار معمولاً انتخاب بهتری است.
در درس بعدی حلقه‌ها را می‌بینیم.
