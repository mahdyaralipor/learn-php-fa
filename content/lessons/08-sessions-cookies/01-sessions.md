---
title: کار با Session
weight: 1
---

# Session چیست؟

سشن راهی است برای این‌که بین درخواست‌های مختلف، مقداری state مربوط به کاربر را نگه داریم.

در PHP معمولاً با `$_SESSION` کار می‌کنیم، اما قبل از آن باید سشن را شروع کنیم.

## شروع سشن

```php
<?php

declare(strict_types=1);

session_start();
```

این فراخوانی باید قبل از خروجی انجام شود؛ درست مثل `header()`.

## ذخیرهٔ داده در سشن

```php
<?php

declare(strict_types=1);

session_start();

$_SESSION['user_id'] = 42;
$_SESSION['name'] = 'Ali';
```

## خواندن از سشن

```php
<?php

declare(strict_types=1);

session_start();

$userId = $_SESSION['user_id'] ?? null;

if ($userId === null) {
    echo 'کاربر وارد نشده است.';
} else {
    echo 'شناسهٔ کاربر: ' . htmlspecialchars((string) $userId, ENT_QUOTES, 'UTF-8');
}
```

## حذف مقدار از سشن

```php
unset($_SESSION['user_id']);
```

و اگر بخواهی کل داده‌های سشن را پاک کنی:

```php
$_SESSION = [];
```

## چرا `session_regenerate_id()` مهم است؟

بعد از login موفق، بهتر است session ID را عوض کنی:

```php
session_regenerate_id(true);
```

این کار در کاهش ریسک session fixation مهم است.  
یعنی مهاجم نتواند کاربر را وادار کند با یک session ID از پیش معلوم وارد شود.

## الگوی login ساده

```php
<?php

declare(strict_types=1);

session_start();

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email === 'admin@example.com' && $password === 'secret') {
    session_regenerate_id(true);
    $_SESSION['user_id'] = 1;

    header('Location: /dashboard.php');
    exit;
}
```

اینجا هنوز دیتابیس نداریم؛ فقط هدف این است که جریان کلی را بفهمی.

## logout

```php
<?php

declare(strict_types=1);

session_start();

$_SESSION = [];
session_destroy();

header('Location: /login.php');
exit;
```

## چند نکتهٔ مهم

- سشن معمولاً با یک cookie شناخته می‌شود.
- نباید داده‌های خیلی بزرگ را بی‌دلیل در session بریزی.
- بهتر است فقط چیزهای ضروری مثل `user_id`، نقش کاربر، یا tokenهای موقت را نگه داری.

## آیا دادهٔ session امن است؟

از ورودی مستقیم کاربر امن‌تر است، اما هنوز هم باید با دقت استفاده شود. مثلاً اگر `$_SESSION['user_id']` داری، باز هم هنگام استفاده در HTML باید escape کنی، و هنگام استفاده در SQL باید bind کنی.

## جمع‌بندی

سشن ابزار اصلی تو برای نگه‌داشتن state کاربر است. عادت‌های خوب:

- همیشه `session_start()`
- برای کلیدهای اختیاری از `??`
- بعد از login موفق `session_regenerate_id(true)`
- بعد از logout پاک‌سازی و redirect

در درس بعدی سراغ خود cookie می‌رویم؛ چیزی که معمولاً سشن روی آن سوار می‌شود.
