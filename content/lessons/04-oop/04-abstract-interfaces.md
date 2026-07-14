---
title: "کلاس انتزاعی و اینترفیس"
weight: 4
---

# کلاس انتزاعی و اینترفیس

گاهی می‌خواهی یک پایهٔ مشترک داشته باشی که بخشی از رفتار را آماده بدهد، و گاهی فقط می‌خواهی یک قرارداد تعریف کنی. برای این دو نیاز، PHP ابزارهای `abstract class` و `interface` را دارد.

## abstract class

کلاس `abstract` می‌تواند property داشته باشد، متد واقعی داشته باشد و متد انتزاعی هم تعریف کند، اما مستقیم از آن شیء ساخته نمی‌شود:

```php
<?php

abstract class PaymentMethod
{
    public function start(): void
    {
        echo "فرآیند پرداخت شروع شد." . PHP_EOL;
    }

    abstract public function pay(int $amount): void;
}
```

متد abstract فقط قرارداد را تعریف می‌کند و کلاس فرزند باید آن را پیاده‌سازی کند:

```php
<?php

class CardPayment extends PaymentMethod
{
    public function pay(int $amount): void
    {
        echo "پرداخت {$amount} تومان با کارت انجام شد." . PHP_EOL;
    }
}
```

## interface

`interface` قرارداد خالص‌تری است. می‌گوید هر کلاسی که این قرارداد را می‌پذیرد، باید چه متدهایی داشته باشد:

```php
<?php

interface Notifiable
{
    public function send(string $message): void;
}
```

پیاده‌سازی:

```php
<?php

class SmsNotifier implements Notifiable
{
    public function send(string $message): void
    {
        echo "SMS: {$message}" . PHP_EOL;
    }
}

class EmailNotifier implements Notifiable
{
    public function send(string $message): void
    {
        echo "Email: {$message}" . PHP_EOL;
    }
}
```

## مزیت interface

تابعی که به `Notifiable` وابسته است، لازم نیست بداند پیاده‌سازی واقعی SMS است یا Email:

```php
<?php

function notifyUser(Notifiable $notifier, string $message): void
{
    $notifier->send($message);
}
```

این همان وابستگی به قرارداد به‌جای وابستگی به کلاس خاص است.

## تفاوت اصلی

### abstract class

- می‌تواند state و behavior واقعی داشته باشد
- می‌تواند متد abstract هم داشته باشد
- فقط از یک کلاس می‌توان `extends` گرفت

### interface

- بیشتر برای تعریف قرارداد است
- معمولا state داخلی ندارد
- یک کلاس می‌تواند چند interface را `implements` کند

## مثال ترکیبی

```php
<?php

interface Jsonable
{
    public function toJson(): string;
}

abstract class Model
{
    public function save(): void
    {
        echo "ذخیره در دیتابیس" . PHP_EOL;
    }
}

class User extends Model implements Jsonable
{
    public function __construct(
        public string $name,
        public string $email,
    ) {
    }

    public function toJson(): string
    {
        return json_encode([
            'name' => $this->name,
            'email' => $this->email,
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }
}
```

## چه زمانی کدام بهتر است؟

از abstract class وقتی استفاده کن که چند کلاس، کد مشترک واقعی دارند. از interface وقتی استفاده کن که فقط می‌خواهی یک توانایی یا قرارداد تعریف کنی و پیاده‌سازی‌های مختلف قابل‌تعویض داشته باشی.

## اشتباهات رایج

### 1. استفاده از abstract class فقط برای قرارداد

اگر state یا behavior مشترک نداری، interface تمیزتر است.

### 2. فراموش‌کردن پیاده‌سازی متدهای interface

در این صورت کلاس خطا می‌دهد.

### 3. قاطی‌کردن `extends` و `implements`

برای کلاس از `extends` و برای interface از `implements` استفاده می‌شود.

### 4. ساختن شیء از کلاس abstract

این کار مجاز نیست.

## تمرین

1. یک `LoggerInterface` با متد `log(string $message): void` بساز.
2. دو کلاس `FileLogger` و `DatabaseLogger` بنویس که آن را پیاده‌سازی کنند.
3. یک abstract class به نام `Shape` بساز با متد `area(): float`.
4. کلاس‌های `Circle` و `Rectangle` را از `Shape` ارث بده و `area()` را پیاده‌سازی کن.

## جمع‌بندی

`abstract class` برای پایهٔ مشترک همراه با کد واقعی مناسب است و `interface` برای قرارداد خالص و type hint حرفه‌ای. در درس بعدی سراغ `trait` و `enum` می‌رویم.
