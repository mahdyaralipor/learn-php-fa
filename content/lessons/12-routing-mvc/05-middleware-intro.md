---
title: مقدمهٔ Middleware
weight: 5
---

# Middleware چیست؟

بعضی کارها قبل از رسیدن درخواست به handler اصلی باید انجام شوند. مثلاً:

- آیا کاربر login کرده؟
- آیا request معتبر است؟
- آیا باید چیزی لاگ شود؟
- آیا باید بعد از اجرا header خاصی ست شود؟

برای این نوع کارهای برشی، ایدهٔ `Middleware` خیلی مفید است.

## تعریف ساده

middleware لایه‌ای است که قبل یا بعد از handler اصلی اجرا می‌شود.

به زبان ساده:

- request وارد می‌شود
- از چند لایه رد می‌شود
- در نهایت به controller یا handler می‌رسد

## مثال ذهنی: auth check

فرض کن مسیر `/dashboard` فقط برای کاربر واردشده است. به‌جای اینکه داخل هر controller این شرط را تکرار کنی، می‌توانی middleware بنویسی.

## نمونهٔ خیلی ساده

```php
<?php

declare(strict_types=1);

$authMiddleware = function (callable $next): void {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    $next();
};
```

## استفاده کنار handler

```php
<?php

declare(strict_types=1);

$dashboardHandler = function (): void {
    echo 'داشبورد کاربر';
};

$authMiddleware(function () use ($dashboardHandler): void {
    $dashboardHandler();
});
```

این مثال خیلی پایه‌ای است، ولی ایده را منتقل می‌کند: middleware تصمیم می‌گیرد اجازهٔ عبور بدهد یا نه.

## before و after

middleware فقط برای «قبل از اجرا» نیست. گاهی می‌تواند بعد از اجرای handler هم کاری انجام دهد:

- زمان اجرا را اندازه بگیرد
- header اضافه کند
- response را log کند

## مثال pipeline ساده

```php
<?php

declare(strict_types=1);

function runMiddlewarePipeline(array $middlewares, callable $destination): void
{
    $runner = array_reduce(
        array_reverse($middlewares),
        function (callable $next, callable $middleware): callable {
            return function () use ($middleware, $next): void {
                $middleware($next);
            };
        },
        $destination
    );

    $runner();
}
```

حالا می‌توانی چند middleware را پشت سر هم اجرا کنی.

## نمونهٔ استفاده

```php
<?php

declare(strict_types=1);

$auth = function (callable $next): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo 'Unauthorized';
        return;
    }

    $next();
};

$log = function (callable $next): void {
    error_log('Request started');
    $next();
    error_log('Request finished');
};

runMiddlewarePipeline([$log, $auth], function (): void {
    echo 'Protected page';
});
```

## نکتهٔ طراحی

middleware نباید به جای controller یا service بنشیند. هدفش رسیدگی به concerns مشترک است، نه حمل‌کردن کل منطق پروژه.

## تمرین

1. یک middleware بنویس که اگر request method غیر از `POST` بود، `405` برگرداند.
2. یک middleware ساده برای logging قبل و بعد از اجرا بنویس.
3. توضیح بده چرا auth check در middleware معمولاً بهتر از تکرار در چند controller است.

## جمع‌بندی

middleware راهی تمیز برای قرار دادن منطق مشترک در مسیر request است. با همین ایدهٔ ساده، می‌توانی auth، logging، validation و کارهای برشی دیگر را منظم‌تر مدیریت کنی.
