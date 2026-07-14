---
title: آشنایی با mock و stub در PHPUnit
weight: 4
---

# mock و stub به زبان ساده

وقتی یک کلاس به سرویس یا وابستگی دیگری تکیه دارد، گاهی نمی‌خواهیم در تست نسخهٔ واقعی آن وابستگی را اجرا کنیم. اینجاست که test doubleها وارد می‌شوند.

دو مفهوم پایه که برای شروع کافی‌اند:

- `stub`: دادهٔ کنترل‌شده برمی‌گرداند
- `mock`: علاوه بر داده، روی تعامل هم assertion می‌گذارد

## چرا به آن‌ها نیاز داریم؟

فرض کن کلاسی داری که برای ارسال ایمیل به یک gateway خارجی وصل می‌شود. در unit test معمولاً نمی‌خواهی واقعاً ایمیل ارسال شود.

می‌خواهی:

- پاسخ وابستگی را کنترل کنی
- تست سریع بماند
- وابستگی بیرونی باعث flaky شدن تست نشود

## مثال: سرویس ثبت‌نام

### فایل `src/Mailer.php`

```php
<?php

declare(strict_types=1);

namespace App;

interface Mailer
{
    public function sendWelcomeEmail(string $email): bool;
}
```

### فایل `src/RegistrationService.php`

```php
<?php

declare(strict_types=1);

namespace App;

final class RegistrationService
{
    public function __construct(private Mailer $mailer)
    {
    }

    public function register(string $email): bool
    {
        if ($email === '') {
            return false;
        }

        return $this->mailer->sendWelcomeEmail($email);
    }
}
```

## استفاده از stub

اگر فقط می‌خواهی پاسخ وابستگی را کنترل کنی:

```php
<?php

declare(strict_types=1);

namespace Tests;

use App\Mailer;
use App\RegistrationService;
use PHPUnit\Framework\TestCase;

final class RegistrationServiceTest extends TestCase
{
    public function testRegisterReturnsTrueWhenMailerSucceeds(): void
    {
        $mailer = $this->createStub(Mailer::class);
        $mailer->method('sendWelcomeEmail')->willReturn(true);

        $service = new RegistrationService($mailer);

        $result = $service->register('ali@example.com');

        $this->assertTrue($result);
    }
}
```

اینجا مهم نیست متد چند بار صدا زده شده؛ فقط می‌خواهیم خروجی کنترل شود.

## استفاده از mock

اگر بخواهی interaction را هم بررسی کنی:

```php
<?php

declare(strict_types=1);

namespace Tests;

use App\Mailer;
use App\RegistrationService;
use PHPUnit\Framework\TestCase;

final class RegistrationServiceTest extends TestCase
{
    public function testRegisterSendsWelcomeEmailToGivenAddress(): void
    {
        $mailer = $this->createMock(Mailer::class);
        $mailer->expects($this->once())
            ->method('sendWelcomeEmail')
            ->with('ali@example.com')
            ->willReturn(true);

        $service = new RegistrationService($mailer);

        $result = $service->register('ali@example.com');

        $this->assertTrue($result);
    }
}
```

اینجا دیگر فقط خروجی مهم نیست؛ می‌خواهیم مطمئن شویم این متد دقیقاً با ورودی درست صدا زده شده است.

## کِی از کدام استفاده کنیم؟

- اگر فقط لازم است وابستگی رفتاری قابل‌پیش‌بینی داشته باشد: `stub`
- اگر لازم است تعامل با وابستگی هم بررسی شود: `mock`

## هشدار مهم

زیاده‌روی در mock کردن می‌تواند تست‌ها را شکننده کند. اگر هر جزئیات داخلی را mock کنی، با کوچک‌ترین refactor تست‌ها بی‌جهت می‌شکنند.

برای شروع این سؤال را بپرس:

- آیا فقط خروجی این همکاری مهم است؟
- یا اینکه واقعاً باید interaction مشخصی تضمین شود؟

## تمرین

1. برای سناریوی failure، یک stub بساز که `false` برگرداند.
2. تستی بنویس که وقتی ایمیل خالی است، `sendWelcomeEmail()` اصلاً صدا زده نشود.
3. با زبان خودت تفاوت `stub` و `mock` را در دو جمله توضیح بده.

## جمع‌بندی

mock و stub ابزار جداکردن وابستگی‌ها در unit test هستند. stub برای کنترل پاسخ خوب است و mock برای بررسی interaction. اگر با اعتدال از آن‌ها استفاده کنی، تست‌ها هم سریع می‌مانند و هم معنی‌دار.
