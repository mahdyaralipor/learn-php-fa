---
title: "type hint"
weight: 3
---

# type hint

type hint به PHP می‌گوید پارامتر و خروجی تابع چه نوعی باید باشند.
در PHP 8.2+ این ابزار خیلی قوی شده و کد را قابل‌اعتمادتر می‌کند.

## چرا type hint؟

```php
<?php

function add($a, $b)
{
    return $a + $b;
}

echo add("2", "3"); // 5 — شاید تصادفی درست باشد
echo add("2", "three"); // Warning و رفتار عجیب
```

با type hint:

```php
<?php

declare(strict_types=1);

function add(int $a, int $b): int
{
    return $a + $b;
}

echo add(2, 3) . PHP_EOL;
// add("2", 3); // TypeError
```

## declare(strict_types=1)

اول فایل:

```php
<?php

declare(strict_types=1);
```

در همان فایل، PHP تبدیل خودکار نوع در type hint را انجام نمی‌دهد.
`"5"` به `int` تبدیل نمی‌شود — خطا می‌گیری.

## type hint پارامتر

```php
<?php

function printUser(string $name, int $age, bool $active): void
{
    echo "{$name} ({$age}) - " . ($active ? 'فعال' : 'غیرفعال') . PHP_EOL;
}

printUser('علی', 30, true);
```

## return type

```php
<?php

function isAdult(int $age): bool
{
    return $age >= 18;
}
```

## nullable با `?`

```php
<?php

function findUser(int $id): ?array
{
    if ($id <= 0) {
        return null;
    }

    return ['id' => $id, 'name' => 'کاربر'];
}
```

## union types (PHP 8+)

```php
<?php

function normalizeId(int|string $id): int
{
    return (int) $id;
}

echo normalizeId(42) . PHP_EOL;
echo normalizeId('42') . PHP_EOL;
```

## mixed

وقتی واقعاً هر نوعی ممکن است:

```php
<?php

function debug(mixed $value): void
{
    var_dump($value);
}
```

`mixed` یعنی تقریباً بدون محدودیت — زیاد استفاده نکن.

## آرایه با شکل مشخص (docblock / بعداً DTO)

PHP نوع آرایهٔ انجمنی دقیق ندارد؛ فعلاً:

```php
<?php

/** @param array{name: string, age: int} $user */
function showProfile(array $user): void
{
    echo "{$user['name']} - {$user['age']}" . PHP_EOL;
}
```

در فصل OOP از کلاس استفاده می‌کنیم.

## readonly و object type hint

```php
<?php

class Product
{
    public function __construct(
        public readonly string $title,
        public int $price,
    ) {
    }
}

function showProduct(Product $product): void
{
    echo "{$product->title}: {$product->price}" . PHP_EOL;
}
```

## void و never

```php
<?php

function redirect(string $url): never
{
    header("Location: {$url}");
    exit;
}
```

`never` یعنی تابع هرگز عادی برنمی‌گردد.

## type hint برای property (در کلاس)

```php
<?php

class Order
{
    public int $id;
    public string $status = 'pending';
}
```

## intersection types (PHP 8.1+)

```php
<?php

interface JsonSerializable
{
    public function jsonSerialize(): mixed;
}

interface Countable
{
    public function count(): int;
}

function process(JsonSerializable&Countable $value): void
{
    // باید هر دو interface را پیاده کند
}
```

## مثال کاربردی

```php
<?php

declare(strict_types=1);

function calculateBmi(float $weightKg, float $heightM): float
{
    if ($heightM <= 0) {
        throw new InvalidArgumentException('قد باید بزرگ‌تر از صفر باشد.');
    }

    return $weightKg / ($heightM ** 2);
}

printf("BMI: %.2f" . PHP_EOL, calculateBmi(70, 1.75));
```

## اشتباهات رایج

### 1. type hint بدون strict و اتکا به تبدیل خودکار

### 2. return نوع اشتباه

```php
function getName(): string
{
    return null; // TypeError
}
```

### 3. استفادهٔ بیش از حد از `mixed`

### 4. فراموش کردن `?` برای nullable

## تمرین

1. تابع `slugify` با ورودی `string` و خروجی `string` بنویس.
2. تابعی بنویس که `int|string` بگیرد و همیشه `int` برگرداند.
3. با `strict_types` عمداً `"10"` به پارامتر `int` بده و خطا را ببین.
4. تابع `divide` با خروجی `?float` بنویس.
5. کلاس `Book` بساز و تابعی با type hint `Book` بنویس.

## جمع‌بندی

type hint قرارداد بین توابع و بقیهٔ کد است؛ `strict_types` آن را سخت‌گیرانه می‌کند و union/nullable انعطاف مدرن می‌دهد.
هر چه زودتر عادت کنی، باگ‌های نوع در پروژه‌های بزرگ کمتر می‌شوند.
در درس بعدی variadic و unpacking را می‌بینیم.
