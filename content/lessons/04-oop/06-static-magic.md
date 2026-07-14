---
title: "استاتیک و متدهای جادویی"
weight: 6
---

# استاتیک و متدهای جادویی

در این درس اعضای `static` و دو magic method پرکاربرد را می‌بینیم. این‌ها مفیدند، ولی اگر بی‌دلیل زیاد استفاده شوند، کد را سخت‌تر می‌کنند.

## `static` یعنی چه؟

اعضای `static` به خود کلاس تعلق دارند، نه به یک شیء خاص؛ یعنی برای استفاده از آن‌ها لازم نیست `new` بزنی.

## static property

```php
<?php

class PageViewCounter
{
    public static int $count = 0;
}

PageViewCounter::$count++;
echo PageViewCounter::$count;
```

## static method

```php
<?php

class MathHelper
{
    public static function square(int $number): int
    {
        return $number * $number;
    }
}

echo MathHelper::square(5);
```

متدهای کمکی که به state یک شیء وابسته نیستند، کاندید خوبی برای `static` هستند.

## `self` و `static`

داخل کلاس معمولا از `self::` برای دسترسی به اعضای استاتیک استفاده می‌شود:

```php
<?php

class Config
{
    public static string $appName = 'Learn PHP FA';

    public static function printAppName(): void
    {
        echo self::$appName . PHP_EOL;
    }
}
```

اما `self` و `static` همیشه یکی نیستند. `self` به کلاسِ محل تعریف کد اشاره می‌کند، در حالی که `static` در بعضی contextها از late static binding استفاده می‌کند:

```php
<?php

class Animal
{
    public static function make(): static
    {
        return new static();
    }
}

class Dog extends Animal
{
}

$dog = Dog::make();
echo $dog::class . PHP_EOL;
```

## factory method استاتیک

```php
<?php

class User
{
    public function __construct(
        public string $name,
        public string $email,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self($data['name'], $data['email']);
    }
}
```

## هشدار دربارهٔ static

اگر همه‌چیز را static کنی:

- تست‌پذیری بدتر می‌شود
- وابستگی‌ها پنهان می‌شوند
- state سراسری بی‌دلیل زیاد می‌شود

پس static ابزار است، نه پیش‌فرض.

## magic method

در PHP متدهایی با نام ویژه وجود دارند که در رویدادهای خاص خودکار صدا زده می‌شوند. اینجا فقط `__toString()` و `__get()` را کوتاه می‌بینیم.

## `__toString()`

وقتی شیء در context رشته‌ای استفاده شود، این متد اجرا می‌شود:

```php
<?php

class Product
{
    public function __construct(
        public string $title,
        public int $price,
    ) {
    }

    public function __toString(): string
    {
        return "{$this->title} ({$this->price})";
    }
}

$product = new Product('ماوس', 850000);
echo $product;
```

## `__get()`

وقتی به propertyای دسترسی بگیری که قابل‌دسترسی نیست یا وجود ندارد، `__get()` می‌تواند وارد عمل شود:

```php
<?php

class User
{
    private array $data = [
        'name' => 'آرمان',
        'email' => 'arman@example.com',
    ];

    public function __get(string $name): mixed
    {
        return $this->data[$name] ?? null;
    }
}
```

اما دربارهٔ `__get()` باید محتاط باشی، چون می‌تواند خطاهای تایپی را پنهان کند و رفتار کلاس را مبهم سازد.

## اشتباهات رایج

### 1. static کردن چیزهایی که به state شیء تعلق دارند

مثلا موجودی هر حساب بانکی نباید static باشد.

### 2. یکی گرفتن `self` و `static`

این دو مخصوصا در ارث‌بری رفتار یکسانی ندارند.

### 3. افراط در magic methodها

اگر کلاس برای هر رفتار عجیبی به magic method متکی شود، نگهداری آن سخت می‌شود.

### 4. استفاده از `__get()` برای پوشاندن طراحی ضعیف

خیلی وقت‌ها getter شفاف و صریح بهتر است.

## تمرین

1. یک کلاس `StrHelper` بساز و دو متد استاتیک `slug()` و `truncate()` در آن قرار بده.
2. یک کلاس `Temperature` بساز که با `__toString()` مقدار را به شکل `"23C"` برگرداند.
3. یک کلاس پایه و فرزند بساز و تفاوت `self` و `static` را در factory method بررسی کن.
4. یک نمونه با `__get()` بساز و بعد همان مسئله را با getter معمولی بازنویسی کن.

## جمع‌بندی

اعضای `static` به کلاس تعلق دارند، نه شیء. `self` و `static` یکی نیستند. `__toString()` برای تبدیل شیء به رشته مفید است و `__get()` باید با احتیاط استفاده شود. با این درس فصل شی‌گرایی تمام می‌شود.
