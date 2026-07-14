---
title: "generator و iterator"
weight: 4
---

# generator و iterator

وقتی داده کم است، معمولا یک آرایه می‌سازی و روی آن loop می‌زنی. اما برای دادهٔ زیاد یا stream، `generator` خیلی مفید است.

## مشکل آرایهٔ کامل

```php
<?php

$numbers = range(1, 1000000);
```

این کار کل داده را یک‌جا در حافظه می‌گذارد.

## `yield`

با `yield` می‌توانی داده را یکی‌یکی و lazy تولید کنی:

```php
<?php

function generateNumbers(int $max): Generator
{
    for ($i = 1; $i <= $max; $i++) {
        yield $i;
    }
}

foreach (generateNumbers(5) as $number) {
    echo $number . PHP_EOL;
}
```

تابعی که `yield` دارد، به‌جای آرایه، یک `Generator` برمی‌گرداند.

## مزیت generator

- مصرف حافظه کمتر
- مناسب برای فایل‌های بزرگ و stream
- کد تمیزتر نسبت به مدیریت دستی state

## key و value

```php
<?php

function users(): Generator
{
    yield 10 => 'علی';
    yield 20 => 'مریم';
}
```

## مثال کاربردی: خواندن فایل خط‌به‌خط

```php
<?php

function readLines(string $path): Generator
{
    $handle = fopen($path, 'r');

    if ($handle === false) {
        throw new RuntimeException("فایل باز نشد.");
    }

    try {
        while (($line = fgets($handle)) !== false) {
            yield rtrim($line, "\r\n");
        }
    } finally {
        fclose($handle);
    }
}
```

## `yield from`

```php
<?php

function firstPart(): Generator
{
    yield 1;
    yield 2;
}

function allParts(): Generator
{
    yield from firstPart();
    yield 3;
}
```

## generator آرایه نیست

Generator مثل آرایه نیست که هر وقت خواستی آزادانه index بزنی. این یک جریان مرحله‌ای از داده است.

## Iterator چیست؟

اگر بخواهی یک iterable سفارشی رسمی بسازی، می‌توانی interface `Iterator` را پیاده‌سازی کنی. این interface متدهایی مثل `current()`, `key()`, `next()`, `rewind()`, `valid()` دارد.

## مثال کوتاه

```php
<?php

class Countdown implements Iterator
{
    private int $current;

    public function __construct(private int $start)
    {
        $this->current = $start;
    }

    public function current(): int { return $this->current; }
    public function key(): int { return $this->current; }
    public function next(): void { $this->current--; }
    public function rewind(): void { $this->current = $this->start; }
    public function valid(): bool { return $this->current > 0; }
}
```

## generator یا Iterator؟

اگر فقط می‌خواهی مقادیر را lazy تولید کنی، generator معمولا ساده‌تر است. اگر کنترل رسمی‌تر و دقیق‌تری روی iteration لازم داری، `Iterator` مهم می‌شود.

## `iterable`

برای type hint منعطف می‌توانی از `iterable` استفاده کنی:

```php
function printItems(iterable $items): void
{
    foreach ($items as $item) {
        echo $item . PHP_EOL;
    }
}
```

## اشتباهات رایج

### 1. استفاده از generator برای مسئله‌های خیلی کوچک

همه‌چیز لازم نیست پیچیده شود.

### 2. فرض‌کردن generator مثل آرایه

رفتار generator با آرایه یکی نیست.

### 3. فراموش‌کردن بستن resource

در کار با فایل و stream، `finally` مهم است.

### 4. پیاده‌سازی ناقص Iterator

اگر یکی از متدها اشتباه باشد، iteration خراب می‌شود.

## تمرین

1. یک generator بنویس که اعداد زوج را تا یک حد مشخص تولید کند.
2. یک generator برای نام روزهای هفته بنویس.
3. تابعی با ورودی `iterable` بنویس که همهٔ آیتم‌ها را چاپ کند.
4. یک iterator ساده برای پیمایش حروف یک کلمه پیاده‌سازی کن.

## جمع‌بندی

`yield` داده را lazy تولید می‌کند، توابع دارای `yield` یک `Generator` برمی‌گردانند، generator برای دادهٔ بزرگ عالی است و `Iterator` راه رسمی ساخت iterable سفارشی است. درس بعدی دربارهٔ attribute و چند قابلیت مدرن PHP است.
