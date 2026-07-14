---
title: مدیریت خطا و logging در production
weight: 3
---

# مدیریت خطا و لاگ در production

چیزی که در محیط توسعه مفید است، ممکن است در production خطرناک باشد. نمونهٔ واضحش نمایش مستقیم خطاها به کاربر است.

## `display_errors` در production

در production معمولاً باید نمایش خطا به کاربر خاموش باشد:

```ini
display_errors = Off
log_errors = On
```

چرا؟ چون stack trace یا جزئیات exception ممکن است:

- ساختار داخلی پروژه را لو بدهد
- مسیر فایل‌ها را نشان دهد
- اطلاعات حساس را نشت دهد

## پس خطاها کجا بروند؟

به log.

در PHP می‌توانی از `error_log()` استفاده کنی:

```php
<?php

declare(strict_types=1);

error_log('Payment failed for order #' . $orderId);
```

## error_log file

در سطح پیکربندی سرور می‌توانی مقصد log را تعیین کنی. بسته به setup، ممکن است فایل log یا جمع‌کنندهٔ مرکزی باشد.

## برخورد با exception در لایهٔ ورودی

بهتر است در نقطهٔ ورود برنامه، exceptionهای پیش‌بینی‌نشده را بگیری:

```php
<?php

declare(strict_types=1);

try {
    $router->dispatch($method, $path);
} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo 'خطای داخلی سرور';
}
```

برای API، به‌جای متن ساده، JSON استاندارد برگردان.

## چه چیزهایی را log کنیم؟

- پیام خطا
- شناسهٔ درخواست یا کاربر، اگر موجود است
- context محدود و مفید
- زمان و نوع عملیات

## چه چیزهایی را log نکنیم؟

- رمز عبور
- access token کامل
- اطلاعات بسیار حساس بدون masking

## تعادل مهم است

log کم باعث می‌شود اشکال‌زدایی سخت شود. log بیش از حد یا log حساس، خودش ریسک امنیتی و هزینهٔ نگهداری دارد.

## تمرین

1. یک try/catch ساده برای front controller بنویس که در خطای غیرمنتظره `500` برگرداند.
2. سه نمونه از داده‌هایی را نام ببر که نباید کامل داخل log بیایند.
3. توضیح بده چرا `display_errors=On` در production خطرناک است.

## جمع‌بندی

در production باید خطاها را از چشم کاربر پنهان و در log ثبت کنی. خاموش‌بودن `display_errors` و روشن‌بودن `log_errors` از حداقل‌های بسیار مهم هر پروژهٔ PHP است.
