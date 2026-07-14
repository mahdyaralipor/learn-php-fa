---
title: "کلاس و شیء"
weight: 1
---

# کلاس و شیء

تا قبل از این بیشتر با متغیر و تابع کار می‌کردیم، اما وقتی چند داده و چند رفتار واقعا به هم مربوط‌اند، بهتر است آن‌ها را کنار هم نگه داریم. این‌جا پای **کلاس** و **شیء** وسط می‌آید.

## کلاس و شیء در یک نگاه

کلاس (`class`) را مثل نقشهٔ ساخت در نظر بگیر. `User` تعریف است و `$ali = new User();` شیء واقعی ساخته‌شده از روی آن تعریف.

## اولین مثال

```php
<?php

class User
{
    public string $name;
    public string $email;

    public function introduce(): void
    {
        echo "سلام، من {$this->name} هستم." . PHP_EOL;
    }
}
```

در این مثال، `name` و `email` property هستند و `introduce()` یک method است.

## ساختن شیء با `new`

```php
<?php

$user = new User();
$user->name = "علی";
$user->email = "ali@example.com";
$user->introduce();
```

خروجی:

```text
سلام، من علی هستم.
```

برای دسترسی به property و method از `->` استفاده می‌کنیم:

```php
$user->name = "رضا";
echo $user->name;
$user->introduce();
```

## `$this` یعنی چه؟

داخل کلاس، `$this` یعنی "همین شیء فعلی".

```php
<?php

class Product
{
    public string $title;
    public int $price;

    public function printSummary(): void
    {
        echo "محصول: {$this->title}" . PHP_EOL;
        echo "قیمت: {$this->price}" . PHP_EOL;
    }
}
```

## مثال کاربردی‌تر

```php
<?php

class BankAccount
{
    public string $owner;
    public int $balance = 0;

    public function deposit(int $amount): void
    {
        $this->balance += $amount;
    }

    public function withdraw(int $amount): void
    {
        if ($amount > $this->balance) {
            echo "موجودی کافی نیست." . PHP_EOL;
            return;
        }

        $this->balance -= $amount;
    }

    public function showBalance(): void
    {
        echo "{$this->owner}: {$this->balance}" . PHP_EOL;
    }
}

$account = new BankAccount();
$account->owner = "مریم";
$account->deposit(500000);
$account->withdraw(120000);
$account->showBalance();
```

اینجا دادهٔ حساب و رفتارهای مربوط به حساب داخل یک کلاس جمع شده‌اند.

## چند شیء از یک کلاس

از یک کلاس می‌توانی هر چند نمونه بخواهی بسازی:

```php
<?php

$first = new User();
$first->name = "رضا";

$second = new User();
$second->name = "نگار";

echo $first->name . PHP_EOL;
echo $second->name . PHP_EOL;
```

هر شیء state خودش را دارد. تغییر نام `first` روی `second` اثری ندارد.

## property با مقدار پیش‌فرض

```php
<?php

class Cart
{
    public int $itemsCount = 0;
    public bool $isActive = true;
}
```

## اشتباهات رایج

### 1. فراموش‌کردن `new`

```php
$user = User(); // اشتباه
```

شکل درست:

```php
$user = new User();
```

### 2. جا انداختن `$this->`

```php
public function introduce(): void
{
    echo $name; // اشتباه
}
```

باید بنویسی:

```php
echo $this->name;
```

### 3. استفاده از property تعریف‌نشده

در PHP مدرن ساخت property پویا ایدهٔ خوبی نیست.

### 4. یکی گرفتن کلاس و شیء

`User` فقط تعریف کلاس است؛ شیء زمانی ساخته می‌شود که `new User()` اجرا شود.

## تمرین

1. یک کلاس `Car` با propertyهای `brand`, `model`, `year` و متد `describe()` بساز.
2. یک کلاس `Counter` با property `count` و متدهای `increment()` و `reset()` بنویس.
3. یک کلاس `Book` بساز و سه شیء مختلف از آن ایجاد کن.
4. داخل یک متد عمدا به‌جای `$this->title` فقط `title` بنویس و پیام خطا را ببین.

## جمع‌بندی

در این درس یاد گرفتی کلاس نقشه است، شیء نمونهٔ واقعی آن است، propertyها داده را نگه می‌دارند، methodها رفتار را تعریف می‌کنند و `$this` برای اشاره به شیء فعلی به‌کار می‌رود. در درس بعدی سراغ سطح دسترسی، constructor و property promotion می‌رویم.
