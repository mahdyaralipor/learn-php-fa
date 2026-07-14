---
title: "closure و callable"
weight: 3
---

# closure و callable

در PHP تابع فقط چیزی نیست که با نام تعریف شود. می‌توانی تابع ناشناس بسازی، داخل متغیر بگذاری، به تابع دیگری پاس بدهی و از scope بیرون داده capture کنی. این مفاهیم در callbackها و APIهای مدرن خیلی مهم‌اند.

## anonymous function

```php
<?php

$greet = function (string $name): void {
    echo "سلام {$name}" . PHP_EOL;
};

$greet("ندا");
```

این تابع ناشناس معمولا یک شیء از نوع `Closure` می‌سازد.

## `callable`

`callable` یعنی چیزی که قابل فراخوانی است؛ مثلا:

- نام یک تابع
- anonymous function
- متد استاتیک
- آرایه‌ای شامل شیء و نام متد

## نمونه با `callable`

```php
<?php

function runTwice(callable $callback): void
{
    $callback();
    $callback();
}

runTwice(function (): void {
    echo "اجرا شد" . PHP_EOL;
});
```

## callback با ورودی

```php
<?php

function formatPrice(int $price, callable $formatter): string
{
    return $formatter($price);
}

echo formatPrice(250000, function (int $price): string {
    return number_format($price) . " تومان";
});
```

## capture با `use`

Closure معمولی به‌صورت پیش‌فرض به متغیرهای scope بیرونی دسترسی مستقیم ندارد:

```php
<?php

$currency = "تومان";

$formatter = function (int $price) use ($currency): string {
    return number_format($price) . " " . $currency;
};
```

اگر بخواهی متغیر بیرونی را با reference بگیری:

```php
<?php

$count = 0;

$increment = function () use (&$count): void {
    $count++;
};
```

این کار شدنی است، ولی نباید بی‌دلیل زیاد استفاده شود.

## arrow function

از PHP 7.4 به بعد arrow function داریم:

```php
<?php

$double = fn (int $value): int => $value * 2;
```

Arrow function برای منطق کوتاه عالی است و متغیرهای بیرونی را به‌طور خودکار by value capture می‌کند:

```php
<?php

$tax = 0.1;
$calculate = fn (int $price): float => $price + ($price * $tax);
```

## مثال با `array_map`

```php
<?php

$prices = [100000, 250000, 800000];

$formatted = array_map(
    fn (int $price): string => number_format($price) . " تومان",
    $prices
);
```

## متد کلاس به‌عنوان callable

```php
<?php

class Greeter
{
    public function sayHello(string $name): string
    {
        return "سلام {$name}";
    }
}

$greeter = new Greeter();
$callable = [$greeter, 'sayHello'];
echo $callable('رضا');
```

## اشتباهات رایج

### 1. فراموش‌کردن `use`

در closure معمولی بدون `use` به متغیر بیرونی دسترسی نداری.

### 2. استفاده از arrow function برای منطق شلوغ

اگر منطق چندخطی شد، closure معمولی خواناتر است.

### 3. capture by reference بدون نیاز

این کار state پنهان می‌سازد و اشکال‌زدایی را سخت‌تر می‌کند.

### 4. قاطی‌کردن `Closure` و `callable`

هر `Closure` یک `callable` است، ولی هر `callable` لزوما `Closure` نیست.

## تمرین

1. یک تابع `applyToPrices(array $prices, callable $callback): array` بنویس.
2. آن را یک بار با closure معمولی و یک بار با arrow function صدا بزن.
3. یک formatter بساز که با `use ($currency)` واحد پول را از scope بیرون بگیرد.
4. یک کلاس `TextHelper` بساز و یکی از متدهایش را به‌صورت callable پاس بده.

## جمع‌بندی

Closureها به تو اجازه می‌دهند تابع را مثل داده حمل کنی. `callable` برای type hint بسیار مفید است، `use` متغیر بیرونی را capture می‌کند و arrow function برای منطق‌های کوتاه انتخاب عالی است. درس بعدی دربارهٔ generator و iterator است.
