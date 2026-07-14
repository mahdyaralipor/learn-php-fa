---
title: نوشتن تست با assertion و الگوی AAA
weight: 3
---

# نوشتن تست واقعی با PHPUnit

حالا که PHPUnit آماده است، وقت نوشتن تست است. برای شروع بهترین مثال یک کلاس ساده است که رفتار واضحی دارد.

## مثال: `Calculator`

### فایل `src/Calculator.php`

```php
<?php

declare(strict_types=1);

namespace App;

final class Calculator
{
    public function add(int $left, int $right): int
    {
        return $left + $right;
    }

    public function divide(int $left, int $right): float
    {
        if ($right === 0) {
            throw new InvalidArgumentException('Division by zero.');
        }

        return $left / $right;
    }
}
```

## الگوی AAA

یکی از ساده‌ترین و مفیدترین الگوها برای نوشتن تست، `AAA` است:

- `Arrange`: آماده‌سازی داده و آبجکت‌ها
- `Act`: اجرای رفتار مورد نظر
- `Assert`: بررسی نتیجه

این الگو باعث می‌شود تستت خوانا بماند.

## تست کلاس `Calculator`

### فایل `tests/CalculatorTest.php`

```php
<?php

declare(strict_types=1);

namespace Tests;

use App\Calculator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase
{
    public function testAddReturnsSumOfTwoIntegers(): void
    {
        // Arrange
        $calculator = new Calculator();

        // Act
        $result = $calculator->add(10, 5);

        // Assert
        $this->assertSame(15, $result);
    }

    public function testDivideReturnsFloatResult(): void
    {
        $calculator = new Calculator();

        $result = $calculator->divide(9, 2);

        $this->assertSame(4.5, $result);
    }

    public function testDivideThrowsExceptionWhenRightOperandIsZero(): void
    {
        $calculator = new Calculator();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Division by zero.');

        $calculator->divide(10, 0);
    }
}
```

## assertion چیست؟

assertion همان جملهٔ قابل‌بررسی تست است. یعنی چیزی که PHPUnit باید صحتش را چک کند.

چند assertion پرکاربرد:

- `assertSame()`
- `assertEquals()`
- `assertTrue()`
- `assertFalse()`
- `assertCount()`
- `assertNull()`
- `assertInstanceOf()`

## `assertSame` یا `assertEquals`؟

برای داده‌های ساده، `assertSame()` معمولاً انتخاب امن‌تری است چون هم مقدار و هم نوع را بررسی می‌کند.

مثلاً:

```php
$this->assertSame(5, $value);
```

این با رشتهٔ `'5'` یکی در نظر گرفته نمی‌شود.

## اسم تست‌ها مهم است

نام تست باید رفتار را توضیح دهد، نه فقط نام متد را تکرار کند.

خوب:

- `testAddReturnsSumOfTwoIntegers`
- `testDivideThrowsExceptionWhenRightOperandIsZero`

ضعیف:

- `testAdd`
- `testCalculator`

## هر تست فقط یک رفتار اصلی

اگر یک تست همزمان سه رفتار مختلف را چک کند، وقتی شکست می‌خورد فهمیدن علت سخت می‌شود. بهتر است تست‌ها کوچک و متمرکز باشند.

## چه چیزی را تست کنیم؟

در این مثال ما behavior را تست می‌کنیم:

- جمع درست انجام می‌شود
- تقسیم اعشاری درست برمی‌گردد
- تقسیم بر صفر exception می‌دهد

ما کاری به اینکه متد داخلش دقیقاً یک `if` دارد یا دو `if` نداریم؛ چیزی که مهم است رفتار بیرونی است.

## تمرین

1. به `Calculator` متد `subtract()` اضافه کن و برایش تست بنویس.
2. متد `multiply()` اضافه کن و دست‌کم دو سناریو تست کن.
3. یک متد `mod()` بساز و برای تقسیم بر صفر هم تست exception بنویس.
4. یکی از تست‌ها را عمداً خراب کن و خروجی شکست PHPUnit را بخوان.

## جمع‌بندی

برای تست‌نویسی خوب لازم نیست از روز اول پیچیده باشی. یک کلاس ساده، چند assertion درست و الگوی `AAA` کاملاً کافی است. مهم این است که تست رفتاری واضح را قفل کند و در زمان refactor به تو اعتماد بدهد.
