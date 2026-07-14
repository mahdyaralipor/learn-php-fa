---
title: "variadic و unpacking"
weight: 4
---

# variadic و unpacking

گاهی نمی‌دانی چند آرگومان می‌آید — مثلاً جمع چند عدد یا ساخت پیام از قطعات.
PHP با **variadic** و **spread/unpacking** این کار را ساده کرده.

## variadic parameter با `...`

```php
<?php

function sum(int ...$numbers): int
{
    return array_sum($numbers);
}

echo sum(1, 2, 3) . PHP_EOL;
echo sum(10, 20, 30, 40) . PHP_EOL;
```

`...$numbers` یعنی «هر تعداد آرگومان int بگیر و داخل آرایه بریز».

## variadic باید آخر باشد

```php
<?php

function greet(string $prefix, string ...$names): void
{
    foreach ($names as $name) {
        echo "{$prefix} {$name}" . PHP_EOL;
    }
}

greet('سلام', 'علی', 'سارا', 'رضا');
```

## ترکیب پارامتر معمولی و variadic

```php
<?php

function joinParts(string $separator, string ...$parts): string
{
    return implode($separator, $parts);
}

echo joinParts(' - ', 'PHP', '8', 'Course') . PHP_EOL;
```

## unpacking آرگومان با `...`

```php
<?php

function multiply(int $a, int $b, int $c): int
{
    return $a * $b * $c;
}

$values = [2, 3, 4];
echo multiply(...$values) . PHP_EOL;
```

معادل `multiply(2, 3, 4)` است.

## unpacking در آرایه (PHP 7.4+)

```php
<?php

$first = [1, 2];
$second = [3, 4];
$all = [...$first, ...$second];

print_r($all);
```

## spread در فراخوانی تابع

```php
<?php

function maxOf(int ...$nums): int
{
    return max($nums);
}

$data = [5, 12, 3, 9];
echo maxOf(...$data) . PHP_EOL;
```

## مثال: تابع `sprintf` style

```php
<?php

function logLine(string $level, string ...$messages): void
{
    $body = implode(' ', $messages);
    echo "[{$level}] {$body}" . PHP_EOL;
}

logLine('INFO', 'کاربر', 'وارد', 'شد');
```

## variadic با type hint دقیق

```php
<?php

declare(strict_types=1);

function average(float ...$values): float
{
    if ($values === []) {
        throw new InvalidArgumentException('حداقل یک مقدار لازم است.');
    }

    return array_sum($values) / count($values);
}

printf("%.2f" . PHP_EOL, average(18.5, 17.0, 19.5));
```

## unpacking associative — named arguments

```php
<?php

function createProfile(string $name, int $age, string $city = 'تهران'): array
{
    return compact('name', 'age', 'city');
}

$args = [
    'name' => 'سارا',
    'age' => 28,
    'city' => 'شیراز',
];

print_r(createProfile(...$args));
```

در PHP 8+ کلیدهای آرایه به named argument نگاشت می‌شوند.

## تفاوت variadic و آرایهٔ واحد

```php
<?php

// variadic — فراخوانی راحت‌تر
sum(1, 2, 3);

// آرایه — باید unpack کنی
$nums = [1, 2, 3];
sum(...$nums);
```

## مثال کاربردی: ماشین‌حساب ساده

```php
<?php

declare(strict_types=1);

function calculate(string $operation, int ...$numbers): int
{
    if ($numbers === []) {
        throw new InvalidArgumentException('حداقل یک عدد لازم است.');
    }

    return match ($operation) {
        'add' => array_sum($numbers),
        'max' => max($numbers),
        'min' => min($numbers),
        default => throw new InvalidArgumentException('عملیات نامعتبر'),
    };
}

echo calculate('add', 10, 20, 5) . PHP_EOL;
echo calculate('max', 3, 9, 1) . PHP_EOL;
```

## اشتباهات رایج

### 1. variadic نه در آخر لیست

### 2. unpack آرایه با تعداد اشتباه

```php
multiply(...[1, 2]); // ArgumentCountError
```

### 3. فراموش کردن spread

```php
maxOf($data); // آرایه به‌عنوان یک آرگومان می‌رود
maxOf(...$data); // درست
```

### 4. variadic خالی بدون بررسی

## تمرین

1. تابع `concat` با `...$parts` بنویس.
2. آرایهٔ اعداد را با `...` به تابع `maxOf` بده.
3. تابع `average` با variadic و بررسی آرایهٔ خالی بنویس.
4. دو آرایه را با spread در یک آرایهٔ سوم ادغام کن.
5. تابعی بنویس که عملیات و variadic اعداد بگیرد (مثل مثال ماشین‌حساب).

## جمع‌بندی

`...$args` تعداد نامحدود آرگومان می‌گیرد و `...$array` هنگام فراخوانی آرایه را باز می‌کند.
برای API انعطاف‌پذیر و کد تمیز در PHP مدرن پرکاربردند.
در درس بعدی scope، global و `static` را می‌بینیم.
