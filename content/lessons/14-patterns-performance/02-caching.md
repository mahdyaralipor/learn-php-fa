---
title: Cache کردن داده‌ها با فایل یا APCu
weight: 2
---

# Cache چیست و چه زمانی مفید است؟

Cache یعنی نتیجهٔ یک محاسبه یا دادهٔ پرهزینه را موقتاً نگه داری تا دفعهٔ بعد دوباره از صفر محاسبه نشود.

## چه چیزهایی کاندید cache هستند؟

- queryهای تکراری و گران
- نتیجهٔ فراخوانی API بیرونی
- خروجی محاسبه‌های نسبتاً سنگین
- تنظیمات یا داده‌های کم‌تغییر

## file cache ساده

یک روش آموزشی و قابل‌فهم، ذخیرهٔ داده در فایل است.

```php
<?php

declare(strict_types=1);

function remember(string $key, int $ttl, callable $callback): mixed
{
    $path = __DIR__ . '/cache/' . sha1($key) . '.php';

    if (is_file($path)) {
        $payload = require $path;

        if (($payload['expires_at'] ?? 0) > time()) {
            return $payload['value'] ?? null;
        }
    }

    $value = $callback();

    file_put_contents($path, '<?php return ' . var_export([
        'expires_at' => time() + $ttl,
        'value' => $value,
    ], true) . ';');

    return $value;
}
```

استفاده:

```php
$posts = remember('homepage.posts', 60, function () use ($postRepository): array {
    return $postRepository->latest();
});
```

## APCu چیست؟

`APCu` یک cache در حافظهٔ محلی PHP است. سریع‌تر از file cache است، ولی نیاز به extension و setup دارد.

ایدهٔ استفاده‌اش شبیه این است:

```php
$value = apcu_fetch('homepage.posts', $success);

if ($success) {
    return $value;
}

$value = $postRepository->latest();
apcu_store('homepage.posts', $value, 60);
```

## خطر اصلی cache: invalidation

سخت‌ترین بخش cache اغلب «پاک کردن یا به‌روزرسانی درست» آن است، نه ذخیره‌کردنش.

مثلاً اگر لیست پست‌ها را cache کرده‌ای و پست جدید ساخته می‌شود، باید بدانی آیا cache قبلی هنوز معتبر است یا نه.

## قانون عملی

قبل از cache کردن از خودت بپرس:

- واقعاً bottleneck داری؟
- هزینهٔ stale data را می‌پذیری؟
- strategy invalidate کردن را می‌دانی؟

## cache کور نزن

cache خوب است، اما اگر بی‌هدف اضافه شود:

- اشکال‌زدایی را سخت می‌کند
- دادهٔ قدیمی نشان می‌دهد
- پیچیدگی پروژه را بالا می‌برد

## تمرین

1. یک file cache ساده برای لیست دسته‌بندی‌ها طراحی کن.
2. توضیح بده اگر بعد از ساخت پست جدید cache لیست پست‌ها پاک نشود چه مشکلی پیش می‌آید.
3. با زبان خودت فرق file cache و APCu را توضیح بده.

## جمع‌بندی

cache ابزار ارزشمندی برای کاهش هزینهٔ محاسبه و query است، اما فقط وقتی ارزش دارد که با اندازه‌گیری و strategy invalidation همراه باشد. برای شروع، file cache و ایدهٔ APCu تصویر ذهنی خوبی می‌سازند.
