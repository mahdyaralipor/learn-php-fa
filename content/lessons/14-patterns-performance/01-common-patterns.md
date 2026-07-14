---
title: "الگوهای رایج: Singleton، Factory، Strategy و DI"
weight: 1
---

# چند pattern رایج به زبان ساده

patternها نسخهٔ جادویی موفقیت نیستند. آن‌ها فقط راه‌حل‌های تکرارشونده برای مسئله‌های تکرارشونده‌اند. اگر مسئله را درست نفهمی، pattern فقط پیچیدگی اضافه می‌کند.

## Singleton

Singleton می‌گوید از یک کلاس فقط یک نمونه در کل برنامه وجود داشته باشد.

نمونهٔ ساده:

```php
<?php

declare(strict_types=1);

final class Config
{
    private static ?self $instance = null;

    private function __construct()
    {
    }

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
```

### چرا باید حواست جمع باشد؟

Singleton خیلی وقت‌ها در عمل به global state تبدیل می‌شود و:

- تست را سخت می‌کند
- coupling را بالا می‌برد
- dependency واقعی را پنهان می‌کند

برای همین معمولاً باید با احتیاط و تردید به آن نگاه کنی.

## Factory

Factory وقتی مفید است که ساختن یک آبجکت پیچیده باشد یا بخواهی منطق ساخت را از مصرف‌کننده جدا کنی.

```php
<?php

declare(strict_types=1);

interface PaymentGateway
{
    public function charge(int $amount): void;
}

final class PaymentGatewayFactory
{
    public function make(string $driver): PaymentGateway
    {
        return match ($driver) {
            'zarinpal' => new ZarinpalGateway(),
            'offline' => new OfflineGateway(),
            default => throw new InvalidArgumentException('Unknown driver.'),
        };
    }
}
```

## Strategy

Strategy یعنی الگوریتم‌های مختلف را پشت یک interface قابل‌تعویض پنهان کنی.

مثلاً برای محاسبهٔ هزینهٔ ارسال:

```php
<?php

declare(strict_types=1);

interface ShippingStrategy
{
    public function cost(int $weight): int;
}

final class StandardShipping implements ShippingStrategy
{
    public function cost(int $weight): int
    {
        return 50000;
    }
}

final class ExpressShipping implements ShippingStrategy
{
    public function cost(int $weight): int
    {
        return 90000;
    }
}
```

حالا service اصلی به‌جای شرط‌های پراکنده، یک strategy می‌گیرد.

## Dependency Injection

Dependency Injection یعنی وابستگی‌ها را از بیرون به کلاس بدهی، نه اینکه کلاس خودش همه‌چیز را بسازد.

```php
<?php

declare(strict_types=1);

final class OrderService
{
    public function __construct(private PaymentGateway $gateway)
    {
    }
}
```

این کار چند مزیت مهم دارد:

- تست آسان‌تر می‌شود
- coupling کمتر می‌شود
- پیاده‌سازی‌ها قابل‌جایگزینی می‌شوند

## نکتهٔ مهم

DI لزوماً یعنی constructor injection یا setter injection؛ لزوماً به معنای داشتن container پیچیده نیست. در پروژهٔ کوچک، تزریق دستی هم کاملاً کافی است.

## چه زمانی pattern استفاده کنیم؟

وقتی:

- مسئلهٔ واقعی وجود دارد
- pattern کد را ساده‌تر می‌کند
- تیم آن را می‌فهمد

نه وقتی:

- فقط می‌خواهی کد حرفه‌ای‌تر به نظر برسد
- در حال حدس‌زدن نیازهای آینده هستی

## تمرین

1. یک مثال از Strategy برای تخفیف سفارش طراحی کن.
2. توضیح بده چرا Singleton معمولاً تست‌پذیری را بدتر می‌کند.
3. یک factory کوچک برای ساخت `Logger` یا `PaymentGateway` بنویس.

## جمع‌بندی

Factory، Strategy و DI معمولاً خیلی کاربردی‌اند. Singleton اما باید با احتیاط جدی استفاده شود. همیشه از خودت بپرس این pattern واقعاً مشکلی را حل می‌کند یا فقط شکل کد را پیچیده‌تر کرده است.
