---
title: "استثناها و مدیریت خطا"
weight: 2
---

# exception و مدیریت خطا

یکی از فرق‌های مهم بین کد شلخته و کد حرفه‌ای، نحوهٔ برخورد با خطاست. به‌جای `echo` و `die` و `return false` در سناریوهای جدی، PHP ابزار `exception` را دارد.

## exception چیست؟

Exception یعنی بگویی: "یک وضعیت غیرعادی رخ داده و مسیر عادی اجرا دیگر مناسب نیست."

## پرتاب exception با `throw`

```php
<?php

function divide(int $a, int $b): float
{
    if ($b === 0) {
        throw new InvalidArgumentException("تقسیم بر صفر مجاز نیست.");
    }

    return $a / $b;
}
```

## گرفتن exception با `try/catch`

```php
<?php

try {
    echo divide(10, 0);
} catch (InvalidArgumentException $e) {
    echo "خطا: " . $e->getMessage() . PHP_EOL;
}
```

## `finally`

بلوک `finally` چه خطا رخ بدهد چه ندهد اجرا می‌شود:

```php
<?php

try {
    echo "شروع پردازش" . PHP_EOL;
} finally {
    echo "پاکسازی نهایی" . PHP_EOL;
}
```

برای بستن فایل، connection یا cleanup خیلی مفید است.

## چرا exception بهتر از `false` است؟

چون:

- مسیر عادی و خطا را جدا می‌کند
- اطلاعات بیشتری حمل می‌کند
- باعث می‌شود خطا بی‌صدا پنهان نشود

## سلسله‌مراتب exception

در PHP کلاس پایهٔ رایج `Exception` است و فرزندهایی مثل این دارد:

- `InvalidArgumentException`
- `RuntimeException`
- `LogicException`

در سطح بالاتر هم `Throwable` وجود دارد که `Exception` و `Error` را پوشش می‌دهد.

## custom exception

برای خطاهای دامنه‌ای بهتر است exception سفارشی بسازی:

```php
<?php

class InsufficientBalanceException extends RuntimeException
{
}

class Wallet
{
    public function __construct(private int $balance)
    {
    }

    public function withdraw(int $amount): void
    {
        if ($amount > $this->balance) {
            throw new InsufficientBalanceException("موجودی کیف پول کافی نیست.");
        }

        $this->balance -= $amount;
    }
}
```

و بعد:

```php
try {
    $wallet->withdraw(900000);
} catch (InsufficientBalanceException $e) {
    echo $e->getMessage();
}
```

## چند catch

```php
<?php

try {
    // ...
} catch (InvalidArgumentException $e) {
    echo "ورودی نامعتبر" . PHP_EOL;
} catch (RuntimeException $e) {
    echo "خطای اجرایی" . PHP_EOL;
}
```

## rethrow

گاهی exception را log می‌کنی و دوباره پرتاب می‌کنی:

```php
try {
    // ...
} catch (RuntimeException $e) {
    error_log($e->getMessage());
    throw $e;
}
```

## exception را کجا استفاده کنیم؟

برای وضعیت‌های واقعا غیرعادی:

- ورودی نامعتبر
- موجودی ناکافی
- فایل پیدا نشد
- پاسخ سرویس بیرونی خراب بود

اما برای هر چیز کوچکی exception نساز. مثلا خالی‌بودن یک آرایه همیشه استثنا نیست.

## اشتباهات رایج

### 1. بلعیدن exception

```php
catch (Exception $e) {
}
```

این کار خطا را پنهان می‌کند.

### 2. catch کردن خیلی کلی

اگر همه‌چیز را با `Throwable` بگیری، handling دقیق سخت‌تر می‌شود.

### 3. پرتاب exception با پیام مبهم

پیام باید معنی‌دار باشد، نه فقط `"Error"`.

### 4. استفادهٔ افراطی از exception

هر چیز کوچکی استثنا نیست.

## تمرین

1. تابعی بنویس که اگر سن منفی بود `InvalidArgumentException` پرتاب کند.
2. یک کلاس `BankAccount` بساز که در برداشت بیش از موجودی `InsufficientFundsException` پرتاب کند.
3. یک `try/catch/finally` بنویس که در `finally` پیام پاکسازی چاپ شود.
4. یک سناریو با دو `catch` متفاوت بساز.

## جمع‌بندی

با `throw` exception پرتاب می‌شود، با `try/catch/finally` مدیریت می‌شود، سلسله‌مراتب exceptionها مهم است و exception سفارشی برای منطق دامنه بسیار مفید است. درس بعدی دربارهٔ `closure` و `callable` است.
