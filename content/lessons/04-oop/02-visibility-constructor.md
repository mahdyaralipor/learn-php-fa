---
title: "سطح دسترسی و سازنده"
weight: 2
---

# سطح دسترسی و سازنده

در OOP قرار نیست همه‌چیز از بیرون آزادانه در دسترس باشد. برای همین PHP سه سطح دسترسی دارد: `public`, `private`, `protected`.

## `public`

اعضای `public` از هر جایی قابل استفاده‌اند:

```php
<?php

class User
{
    public string $name;
}

$user = new User();
$user->name = "سارا";
echo $user->name;
```

## `private`

عضو `private` فقط داخل همان کلاس قابل دسترسی است:

```php
<?php

class BankAccount
{
    public string $owner;
    private int $balance = 0;

    public function deposit(int $amount): void
    {
        $this->balance += $amount;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }
}
```

اگر بنویسی `echo $account->balance;` خطا می‌گیری. این محدودیت مفید است، چون نمی‌خواهی هر بخشی از برنامه مستقیم موجودی را خراب کند.

## `protected`

`protected` شبیه `private` است، با این تفاوت که کلاس‌های فرزند هم به آن دسترسی دارند:

```php
<?php

class Person
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}

class Employee extends Person
{
    public function greet(): void
    {
        echo "سلام {$this->name}" . PHP_EOL;
    }
}
```

## constructor

سازنده یا `__construct()` هنگام ساخته‌شدن شیء اجرا می‌شود و کمک می‌کند شیء از همان ابتدا در وضعیت معتبر باشد:

```php
<?php

class User
{
    public string $name;
    public string $email;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }
}

$user = new User("رضا", "reza@example.com");
```

بدون constructor ممکن است بخشی از داده‌ها را فراموش کنی و شیء ناقص بماند.

## property promotion در PHP 8

```php
<?php

class User
{
    public function __construct(
        public string $name,
        public string $email,
        private bool $isAdmin = false,
    ) {
    }
}
```

این قابلیت تعریف property و مقداردهی آن را کوتاه‌تر می‌کند.

## validation در constructor

```php
<?php

class Product
{
    public function __construct(
        public string $title,
        private int $price,
    ) {
        if ($price < 0) {
            throw new InvalidArgumentException("قیمت نمی‌تواند منفی باشد.");
        }
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}
```

## `readonly`

از PHP 8.1 به بعد می‌توانی propertyهایی داشته باشی که فقط یک بار مقداردهی شوند:

```php
<?php

class Invoice
{
    public function __construct(
        public readonly string $number,
        public readonly DateTimeImmutable $issuedAt,
    ) {
    }
}
```

این برای چیزهایی مثل شماره فاکتور، شناسه سفارش و تاریخ ایجاد خیلی مناسب است.

## getter همیشه لازم است؟

نه. گاهی `public readonly` کاملا کافی است. اما اگر بخواهی بعدا روی دسترسی کنترل داشته باشی، `private` همراه getter انتخاب انعطاف‌پذیرتری است.

## اشتباهات رایج

### 1. `public` کردن همه‌چیز

این کار کلاس را بی‌دفاع می‌کند و هر بخشی از برنامه می‌تواند state را خراب کند.

### 2. استفادهٔ بی‌دلیل از `protected`

اگر فقط می‌خواهی عضو از بیرون مخفی باشد، خیلی وقت‌ها `private` امن‌تر است.

### 3. constructor خیلی شلوغ

اگر constructor ده‌ها پارامتر دارد، احتمالا کلاس بیش از حد مسئولیت گرفته است.

### 4. تلاش برای تغییر `readonly`

```php
$invoice->number = "INV-2000"; // خطا
```

## تمرین

1. یک کلاس `Post` بساز که با constructor، `title` و `body` بگیرد.
2. `authorId` را به شکل `public readonly` اضافه کن.
3. یک کلاس `Wallet` بساز که `balance` خصوصی داشته باشد و فقط با `deposit()` و `withdraw()` تغییر کند.
4. یک کلاس `AdminUser` بساز که از `User` ارث ببرد و یک property `protected` در کلاس والد را در فرزند استفاده کند.

## جمع‌بندی

`public` برای دسترسی عمومی است، `private` برای محافظت از state داخلی، `protected` برای کلاس و فرزندانش. constructor شیء را از همان ابتدا معتبرتر می‌سازد، property promotion کد را کوتاه می‌کند و `readonly` برای داده‌های تغییرناپذیر فوق‌العاده است. درس بعدی دربارهٔ ارث‌بری است.
