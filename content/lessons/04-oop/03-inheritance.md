---
title: "ارث‌بری"
weight: 3
---

# ارث‌بری

اگر دو کلاس خیلی شبیه هم باشند، لازم نیست همه‌چیز را دوباره بنویسی. یکی از ابزارهای reuse در OOP، **ارث‌بری** یا `inheritance` است.

## ارث‌بری یعنی چه؟

در PHP با `extends` می‌گویی یک کلاس از کلاس دیگری ویژگی‌ها و رفتارها را به ارث ببرد:

```php
<?php

class Animal
{
    public function eat(): void
    {
        echo "در حال غذا خوردن..." . PHP_EOL;
    }
}

class Cat extends Animal
{
    public function meow(): void
    {
        echo "میو!" . PHP_EOL;
    }
}

$cat = new Cat();
$cat->eat();
$cat->meow();
```

## چه چیزهایی به ارث می‌رسند؟

- اعضای `public`
- اعضای `protected`
- اعضای `private` مستقیما در کلاس فرزند در دسترس نیستند

## مثال واقعی‌تر

```php
<?php

class User
{
    public function __construct(
        protected string $name,
        protected string $email,
    ) {
    }
}

class AdminUser extends User
{
    public function accessPanel(): void
    {
        echo "{$this->name} وارد پنل مدیریت شد." . PHP_EOL;
    }
}
```

## override

کلاس فرزند می‌تواند متد والد را بازنویسی کند:

```php
<?php

class User
{
    public function role(): string
    {
        return "user";
    }
}

class AdminUser extends User
{
    public function role(): string
    {
        return "admin";
    }
}
```

## `parent::`

اگر بخواهی رفتار والد را حفظ و روی آن چیزی اضافه کنی، از `parent::` استفاده می‌کنی:

```php
<?php

class Logger
{
    public function log(string $message): void
    {
        echo "[LOG] {$message}" . PHP_EOL;
    }
}

class FileLogger extends Logger
{
    public function log(string $message): void
    {
        parent::log($message);
        echo "پیام داخل فایل هم ذخیره شد." . PHP_EOL;
    }
}
```

## constructor در ارث‌بری

اگر کلاس والد constructor مهمی دارد، معمولا کلاس فرزند باید آن را صدا بزند:

```php
<?php

class Person
{
    public function __construct(protected string $name)
    {
    }
}

class Teacher extends Person
{
    public function __construct(string $name, private string $course)
    {
        parent::__construct($name);
    }
}
```

## `final`
با `final` می‌توانی جلوی ارث‌بری یا override را بگیری:

```php
final class DatabaseConnection
{
}
```

یا:
```php
class BaseReport
{
    final public function export(): void
    {
        echo "خروجی نهایی ساخته شد." . PHP_EOL;
    }
}
```

## آیا ارث‌بری همیشه خوب است؟

نه. ارث‌بری زمانی خوب است که واقعا رابطهٔ **"یک نوع از"** وجود داشته باشد؛ مثل `AdminUser` برای `User` یا `Cat` برای `Animal`. اگر فقط می‌خواهی کمی کد مشترک reuse شود، همیشه بهترین راه نیست.

## اشتباهات رایج

### 1. ارث‌بری فقط برای فرار از تکرار

شباهت کد به‌تنهایی دلیل کافی برای `extends` نیست.

### 2. دسترسی به property خصوصی والد

```php
class ParentClass
{
    private string $name = "test";
}

class ChildClass extends ParentClass
{
    public function show(): void
    {
        echo $this->name; // خطا
    }
}
```

### 3. فراموش‌کردن `parent::__construct()`

این باعث می‌شود state والد درست مقداردهی نشود.

## تمرین

1. یک کلاس `Vehicle` با متد `move()` بساز و کلاس‌های `Car` و `Bike` را از آن ارث بده.
2. در `Car` متد `move()` را override کن.
3. یک کلاس `Employee` با constructor بساز و `Manager` را از آن ارث بده؛ constructor فرزند باید `parent::__construct()` را صدا بزند.
4. یک متد `generateId()` را `final` کن و بررسی کن در فرزند قابل override نیست.

## جمع‌بندی

با `extends` از یک کلاس ارث می‌گیریم، کلاس فرزند به اعضای `public` و `protected` والد دسترسی دارد، با override رفتار را تغییر می‌دهیم، با `parent::` رفتار والد را تکمیل می‌کنیم و با `final` جلوی override یا ارث‌بری را می‌گیریم. درس بعدی دربارهٔ `abstract class` و `interface` است.
