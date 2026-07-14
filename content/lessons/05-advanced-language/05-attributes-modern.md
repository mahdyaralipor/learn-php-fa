---
title: "قابلیت‌های مدرن PHP"
weight: 5
---

# attribute و چند ویژگی مدرن PHP

در این درس چند قابلیت مهم PHP 8+ را کنار هم می‌بینیم: `attribute`، مرور typed property، اشاره به `match` و معرفی خیلی کوتاه `Fiber`.

## attribute چیست؟

Attribute راهی استاندارد برای چسباندن metadata به کلاس، متد، property یا پارامتر است:

```php
#[SomeAttribute]
```

## تعریف attribute سفارشی

```php
<?php

#[Attribute]
class Route
{
    public function __construct(
        public string $method,
        public string $path,
    ) {
    }
}
```

## استفاده از attribute

```php
<?php

class UserController
{
    #[Route('GET', '/users')]
    public function index(): void
    {
        echo "لیست کاربران";
    }
}
```

Attribute خودش جادو نمی‌کند؛ یک بخش دیگر از برنامه باید آن را با reflection بخواند و از روی آن تصمیم بگیرد.

## کجاها attribute می‌بینی؟

- routeها
- validation
- serialization
- dependency injection
- ORM mapping

## typed property

از PHP 7.4 به بعد می‌توانی برای property نوع تعریف کنی:

```php
<?php

class Product
{
    public string $title;
    public int $price;
    public bool $isAvailable = true;
}
```

این کار intent کد را روشن‌تر و خطاها را زودتر آشکار می‌کند.

## typed property همراه `readonly`

```php
<?php

class UserProfile
{
    public function __construct(
        public readonly int $id,
        public string $displayName,
    ) {
    }
}
```

## یادآوری `match`

`match` برای branching تمیز در PHP مدرن خیلی کاربردی است:

```php
<?php

$roleLabel = match ($role) {
    'admin' => 'مدیر',
    'editor' => 'ویرایشگر',
    'user' => 'کاربر',
    default => 'نامشخص',
};
```

مزیت‌هایش:

- مقدار برمی‌گرداند
- strictتر از `switch` است
- fall-through ندارد

## Fiber چیست؟

`Fiber` از PHP 8.1 آمده و بیشتر در دنیای async و کتابخانه‌ها مهم است. خیلی خلاصه، ابزاری برای suspend و resume کردن execution در userland است.

```php
<?php

$fiber = new Fiber(function (): void {
    echo "شروع fiber" . PHP_EOL;
    Fiber::suspend('paused');
    echo "ادامه fiber" . PHP_EOL;
});

echo $fiber->start() . PHP_EOL;
$fiber->resume();
```

لازم نیست فعلا عمیق وارد Fiber شوی؛ فقط بدان در زبان وجود دارد و در ابزارهای async اهمیت دارد.

## اشتباهات رایج

### 1. فکر کردن به این‌که attribute خودش رفتار را اجرا می‌کند

نه، attribute فقط metadata است.

### 2. نادیده‌گرفتن typeها در PHP مدرن

اگر هنوز همه‌چیز را بی‌نوع می‌نویسی، از مزیت مهم PHP 8 جا می‌مانی.

### 3. استفاده از قابلیت جدید فقط چون جدید است

همیشه ببین آیا واقعا به آن نیاز داری یا نه.

### 4. درگیرشدن زودهنگام با Fiber

اگر هنوز OOP و exception و namespace جا نیفتاده، Fiber اولویت آموزشی تو نیست.

## تمرین

1. یک attribute سفارشی `Label` بساز که یک متن فارسی بگیرد.
2. آن را روی یک property یا متد در یک کلاس نمونه قرار بده.
3. یک کلاس با چند typed property و یک `readonly` property بنویس.
4. با `match` برای سه وضعیت مختلف label فارسی بساز.
5. مثال سادهٔ Fiber را اجرا کن و ترتیب خروجی را بررسی کن.

## جمع‌بندی

`attribute` برای metadata رسمی و مدرن است، typed propertyها بخش مهمی از PHP جدید هستند، `match` branching تمیزتری می‌دهد و `Fiber` قابلیتی پیشرفته‌تر برای async است. با این درس فصل ویژگی‌های پیشرفته تمام می‌شود.
