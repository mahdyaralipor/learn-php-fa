---
title: "عملگر سه‌تایی و nullsafe"
weight: 4
---

# عملگر سه‌تایی و nullsafe

گاهی می‌خواهی در یک خط تصمیم بگیری یا مقدار پیش‌فرض بگذاری.
PHP ابزارهای کوتاه و خوانایی برای این کار دارد — اگر زیاده‌روی نکنی.

## عملگر سه‌تایی `? :`

```php
<?php

$age = 20;
$label = ($age >= 18) ? 'بزرگسال' : 'نوجوان';

echo $label . PHP_EOL;
```

ساختار: `شرط ? مقدار_اگر_درست : مقدار_اگر_نادرست`

## ternary تو در تو (با احتیاط)

```php
<?php

$score = 85;

$grade = ($score >= 90) ? 'A'
    : (($score >= 80) ? 'B'
    : 'C');

echo $grade . PHP_EOL;
```

زیاد تو در تو کننده خوانایی را کم می‌کند؛ گاهی `match` یا `if` بهتر است.

## Elvis Operator `?:`

```php
<?php

$name = "";
$display = $name ?: 'مهمان';

echo $display . PHP_EOL; // مهمان
```

`$a ?: $b` یعنی اگر `$a` truthy بود همان را برگردان، وگرنه `$b`.

تفاوت با `??`: `?:` روی truthy/falsy حساس است؛ `??` فقط null و undefined.

```php
<?php

$zero = 0;

var_dump($zero ?: 10);  // int(10) چون 0 falsy است
var_dump($zero ?? 10);  // int(0)
```

## Null Coalescing `??`

```php
<?php

$config = ['theme' => 'dark'];

$theme = $config['theme'] ?? 'light';
$lang = $config['lang'] ?? 'fa';

echo "{$theme}, {$lang}" . PHP_EOL;
```

## زنجیرهٔ `??`

```php
<?php

$nickname = $user['nickname'] ?? $user['name'] ?? 'مهمان';
```

## Null Coalescing Assignment `??=`

```php
<?php

$options = [];
$options['cache'] ??= true;
$options['cache'] ??= false;

var_dump($options['cache']); // true
```

## Nullsafe `?->`

برای دسترسی به property یا method روی مقداری که ممکن است null باشد:

```php
<?php

class Address
{
    public function __construct(public ?string $city = null)
    {
    }
}

class Customer
{
    public function __construct(public ?Address $address = null)
    {
    }
}

$customer = new Customer(null);

echo $customer?->address?->city ?? 'شهر نامشخص';
echo PHP_EOL;
```

بدون nullsafe:

```php
$city = null;
if ($customer !== null && $customer->address !== null) {
    $city = $customer->address->city;
}
```

## Nullsafe با متد

```php
<?php

class Logger
{
    public function log(string $msg): void
    {
        echo $msg . PHP_EOL;
    }
}

class Service
{
    public function __construct(public ?Logger $logger = null)
    {
    }

    public function run(): void
    {
        $this->logger?->log('سرویس اجرا شد');
    }
}

(new Service(null))->run(); // هیچ خروجی‌ای — بدون خطا
```

## ترکیب ternary و null coalescing

```php
<?php

$role = null;
$access = ($role === 'admin') ? 'full' : ($role ?? 'guest');

echo $access . PHP_EOL; // guest
```

## چه زمانی کدام را استفاده کنی؟

| ابزار | کاربرد |
|-------|--------|
| `? :` | انتخاب بین دو مقدار بر اساس شرط |
| `?:` | مقدار پیش‌فرض وقتی falsy مهم است |
| `??` | مقدار پیش‌فرض فقط برای null/تعریف‌نشده |
| `??=` | مقداردهی اولیهٔ lazy |
| `?->` | دسترسی امن به شیء nullable |

## مثال کاربردی: پروفایل کاربر

```php
<?php

$profile = [
    'name' => 'سارا',
    // 'bio' تعریف نشده
];

$bio = $profile['bio'] ?? 'بیوگرافی ثبت نشده';
$title = !empty($profile['name']) ? "پروفایل {$profile['name']}" : 'پروفایل مهمان';

echo $title . PHP_EOL;
echo $bio . PHP_EOL;
```

## اشتباهات رایج

### 1. ternary خیلی تو در تو

### 2. استفاده از `?:` وقتی فقط null مهم است (باید `??` باشد)

### 3. فراموش کردن پرانتز در ternary داخل رشته

```php
echo "نتیجه: " . $ok ? 'بله' : 'خیر'; // اولویت عملگر اشتباه
echo "نتیجه: " . ($ok ? 'بله' : 'خیر'); // درست
```

### 4. nullsafe روی مقدار غیر شیء

`?->` فقط روی object و null معنی دارد.

## تمرین

1. با ternary پیام «موجودی کافی» / «موجودی ناکافی» بنویس.
2. با `??` برای `title`, `author`, `year` در آرایهٔ کتاب مقدار پیش‌فرض بگذار.
3. تفاوت `0 ?: 5` و `0 ?? 5` را اجرا و توضیح بده.
4. کلاس سه‌لایه با `?->` بساز و وقتی میانی null است بدون خطا خروجی بگیر.
5. یک ternary تو در تو را با `match` بازنویسی کن و خوانایی را مقایسه کن.

## جمع‌بندی

Ternary برای انتخاب سریع، `??` و `??=` برای مقادیر پیش‌فرض null-safe و `?->` برای زنجیرهٔ امن روی شیءهای nullable است.
از فصل بعدی وارد دنیای **توابع** می‌شویم — جایی که منطق را نام‌گذاری و قابل‌استفادهٔ مجدد می‌کنی.
