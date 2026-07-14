---
title: "تریت و enum"
weight: 5
---

# trait و enum

در این درس دو ابزار مهم PHP مدرن را می‌بینی: `trait` برای اشتراک رفتار و `enum` برای محدودکردن مقدارها به مجموعه‌ای معتبر.

## trait چیست؟

`trait` راهی برای reuse کردن کد بین چند کلاس است، بدون این‌که وارد ارث‌بری مفهومی شوی:

```php
<?php

trait HasLogger
{
    public function log(string $message): void
    {
        echo "[LOG] {$message}" . PHP_EOL;
    }
}

class OrderService
{
    use HasLogger;
}
```

Trait رابطهٔ "یک نوع از" نمی‌سازد. `OrderService` یک نوع `HasLogger` نیست؛ فقط رفتاری را از آن گرفته است.

## چند trait در یک کلاس

```php
<?php

trait HasTimestamp
{
    public function touch(): void
    {
        echo "زمان به‌روزرسانی شد." . PHP_EOL;
    }
}

class PostService
{
    use HasTimestamp, HasLogger;
}
```

## تداخل نام

اگر دو trait متد هم‌نام داشته باشند، باید مشخص کنی کدام استفاده شود:

```php
<?php

trait A
{
    public function hello(): void
    {
        echo "A" . PHP_EOL;
    }
}

trait B
{
    public function hello(): void
    {
        echo "B" . PHP_EOL;
    }
}

class Demo
{
    use A, B {
        A::hello insteadof B;
    }
}
```

اگر زیاد به این تداخل‌ها می‌خوری، شاید طراحی دارد پیچیده می‌شود.

## enum چیست؟

از PHP 8.1 به بعد `enum` برای نمایش مجموعهٔ محدودی از مقدارهای معتبر آمده است. مثلا وضعیت سفارش:

```php
<?php

enum OrderStatus
{
    case Pending;
    case Paid;
    case Shipped;
    case Cancelled;
}
```

استفاده:

```php
<?php

class Order
{
    public function __construct(
        public int $id,
        public OrderStatus $status,
    ) {
    }
}
```

این کار جلوی اشتباه‌هایی مثل `"pendding"` را می‌گیرد.

## متد داخل enum

```php
<?php

enum OrderStatus
{
    case Pending;
    case Paid;
    case Shipped;
    case Cancelled;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'در انتظار',
            self::Paid => 'پرداخت‌شده',
            self::Shipped => 'ارسال‌شده',
            self::Cancelled => 'لغوشده',
        };
    }
}
```

## backed enum

اگر لازم است مقدار رشته‌ای یا عددی برای دیتابیس و API داشته باشی:

```php
<?php

enum UserRole: string
{
    case Admin = 'admin';
    case Editor = 'editor';
    case Customer = 'customer';
}
```

```php
echo UserRole::Admin->value; // admin
```

و:

```php
$role = UserRole::from('editor');
$maybe = UserRole::tryFrom('unknown'); // null
```

## trait یا inheritance؟

از trait برای رفتار کوچک و مشترک بین کلاس‌های نامرتبط استفاده کن. اگر واقعا رابطهٔ نوعی وجود دارد، inheritance بهتر است.

## اشتباهات رایج

### 1. استفادهٔ افراطی از trait

Trait قرار نیست جای طراحی خوب را بگیرد.

### 2. گذاشتن state پیچیده داخل trait

Trait بیشتر برای رفتارهای کوچک مناسب است.

### 3. استفاده از string آزاد به‌جای enum

وقتی مجموعهٔ مقادیر محدود است، enum تمیزتر است.

## تمرین

1. یک trait به نام `HasSlug` با متد `generateSlug()` بساز.
2. دو کلاس `Post` و `Category` بساز که از آن استفاده کنند.
3. یک enum به نام `TicketStatus` با `Open`, `Closed`, `Pending` بساز.
4. آن را به backed enum رشته‌ای تبدیل کن و برای هر case یک `label()` فارسی برگردان.
5. یک کلاس `Ticket` بنویس که وضعیتش از نوع `TicketStatus` باشد.

## جمع‌بندی

`trait` برای اشتراک رفتار بین چند کلاس است و `enum` برای مجموعهٔ محدود از مقدارهای معتبر. `backed enum` هم برای اتصال بهتر به دیتابیس و API خیلی مفید است. درس بعدی دربارهٔ `static` و چند magic method است.
