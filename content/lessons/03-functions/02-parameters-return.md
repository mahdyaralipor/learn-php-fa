---
title: "پارامترها و return"
weight: 2
---

# پارامترها و return

تابع بدون ورودی و خروجی محدود است.
این درس پارامترها، مقدار پیش‌فرض، نام‌گذاری‌شده و چند نوع `return` را پوشش می‌دهد.

## پارامتر اجباری

```php
<?php

function multiply(int $a, int $b): int
{
    return $a * $b;
}

echo multiply(4, 5) . PHP_EOL;
```

## مقدار پیش‌فرض

```php
<?php

function greet(string $name, string $prefix = 'سلام'): void
{
    echo "{$prefix}، {$name}" . PHP_EOL;
}

greet('علی');
greet('سارا', 'درود');
```

پارامترهای با پیش‌فرض باید **آخر** لیست باشند.

## ترتیب پارامترها

```php
<?php

function createUser(string $email, string $role = 'user', bool $active = true): array
{
    return [
        'email' => $email,
        'role' => $role,
        'active' => $active,
    ];
}

print_r(createUser('ali@example.com'));
print_r(createUser('sara@example.com', 'admin'));
```

## Named Arguments (PHP 8+)

```php
<?php

$user = createUser(
    email: 'reza@example.com',
    active: false,
);

print_r($user);
```

با named argument ترتیب مهم نیست و خوانایی بهتر می‌شود.

## return چند نوع خروجی

### مقدار ساده

```php
<?php

function fullName(string $first, string $last): string
{
    return "{$first} {$last}";
}
```

### آرایه

```php
<?php

function minMax(array $numbers): array
{
    return [
        'min' => min($numbers),
        'max' => max($numbers),
    ];
}

print_r(minMax([3, 9, 1, 7]));
```

### early return

```php
<?php

function divide(float $a, float $b): ?float
{
    if ($b === 0.0) {
        return null;
    }

    return $a / $b;
}

var_dump(divide(10, 2));
var_dump(divide(10, 0));
```

`?float` یعنی float یا null.

## بدون return صریح

```php
<?php

function logMessage(string $msg): void
{
    echo $msg . PHP_EOL;
    // return; اختیاری در انتها
}
```

## return در چند شاخه

```php
<?php

function gradeLabel(int $score): string
{
    if ($score >= 90) {
        return 'عالی';
    }

    if ($score >= 75) {
        return 'خوب';
    }

    return 'نیاز به تلاش';
}
```

## pass by value

در PHP آرایه و object به‌صورت handle پاس می‌شوند اما reassignment داخل تابع روی اصل اثر نمی‌گذارد:

```php
<?php

function resetCounter(int $count): void
{
    $count = 0;
}

$n = 5;
resetCounter($n);
echo $n . PHP_EOL; // هنوز 5
```

## reference با `&`

```php
<?php

function resetCounterRef(int &$count): void
{
    $count = 0;
}

$n = 5;
resetCounterRef($n);
echo $n . PHP_EOL; // 0
```

برای شروع کمتر به reference نیاز داری؛ وقتی لازم شد استفاده کن.

## nullable پارامتر

```php
<?php

function showBio(?string $bio): void
{
    echo $bio ?? 'بیوگرافی ندارد';
    echo PHP_EOL;
}

showBio(null);
showBio('توسعه‌دهنده PHP');
```

## مثال کاربردی: قیمت با تخفیف

```php
<?php

function finalPrice(
    int $basePrice,
    int $discountPercent = 0,
    int $shipping = 0,
): int {
    $discount = (int) ($basePrice * $discountPercent / 100);
    return $basePrice - $discount + $shipping;
}

echo finalPrice(1_000_000) . PHP_EOL;
echo finalPrice(1_000_000, discountPercent: 10, shipping: 50_000) . PHP_EOL;
```

## اشتباهات رایج

### 1. پارامتر پیش‌فرض قبل از اجباری

```php
function bad($a = 1, $b) {} // خطای parse
```

### 2. فراموش کردن return در شاخه

```php
<?php

function sign(int $n): string
{
    if ($n > 0) {
        return 'positive';
    }
    // اگر n <= 0 چه برمی‌گردد؟
}
```

### 3. return نوع ناسازگار

```php
<?php

declare(strict_types=1);

function getCount(): int
{
    return "5"; // TypeError با strict
}
```

### 4. named argument برای پارامتر ناموجود

## تمرین

1. تابع `rectangleArea` با طول و عرض و مقدار پیش‌فرض برای عرض بنویس.
2. تابع `clamp` بنویس که عدد را بین min و max نگه دارد.
3. با named arguments یک کاربر بساز.
4. تابعی بنویس که اگر آرایه خالی بود `null` وگرنه میانگین برگرداند.
5. تفاوت pass by value و `&` را با یک مثال کوچک نشان بده.

## جمع‌بندی

پارامتر ورودی تابع را مشخص می‌کند؛ پیش‌فرض و named argument انعطاف می‌دهند و `return` خروجی را برمی‌گرداند.
برای کد قابل اعتماد، همهٔ شاخه‌ها باید نوع بازگشتی سازگار داشته باشند.
در درس بعدی type hint را عمیق‌تر می‌بینیم.
