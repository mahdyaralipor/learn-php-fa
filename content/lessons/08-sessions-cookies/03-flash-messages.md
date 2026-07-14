---
title: الگوی Flash Message
weight: 3
---

# Flash Message چیست؟

Flash message پیامی است که فقط برای درخواست بعدی نگه داشته می‌شود و بعد حذف می‌شود. مثلاً:

- «با موفقیت ذخیره شد»
- «ورود موفق بود»
- «خطایی رخ داد»

این الگو معمولاً بعد از redirect خیلی مفید است.

## چرا به آن نیاز داریم؟

فرض کن فرم را پردازش کردی و بعد کاربر را redirect کردی. اگر پیام موفقیت را فقط در متغیر عادی ذخیره کرده باشی، در درخواست بعدی از بین می‌رود.

پس پیام را موقتاً در session می‌گذاریم.

## ذخیرهٔ پیام

```php
<?php

declare(strict_types=1);

session_start();

$_SESSION['flash'] = 'اطلاعات با موفقیت ذخیره شد.';

header('Location: /profile.php');
exit;
```

## نمایش و حذف پیام

```php
<?php

declare(strict_types=1);

session_start();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<?php if ($flash !== null): ?>
    <p><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
```

## چرا اول می‌خوانیم، بعد حذف می‌کنیم؟

چون پیام باید فقط یک‌بار نمایش داده شود. اگر حذف نکنی، در هر refresh دوباره دیده می‌شود.

## ساخت helper کوچک

می‌توانی یک helper ساده بسازی:

```php
<?php

declare(strict_types=1);

function flashSet(string $message): void
{
    $_SESSION['flash'] = $message;
}

function flashGet(): ?string
{
    $message = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return is_string($message) ? $message : null;
}
```

استفاده:

```php
session_start();

flashSet('عملیات با موفقیت انجام شد.');
header('Location: /dashboard.php');
exit;
```

و در صفحهٔ مقصد:

```php
session_start();

$flash = flashGet();
```

## چند نوع پیام

اگر بخواهی پیام موفقیت و خطا را از هم جدا کنی:

```php
$_SESSION['flash'] = [
    'type' => 'success',
    'message' => 'ثبت انجام شد.',
];
```

و بعد:

```php
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
```

فقط یادت باشد هر چیزی که در HTML نشان می‌دهی باید escape شود.

## ارتباط با الگوی PRG

Flash message معمولاً با الگوی `Post/Redirect/Get` استفاده می‌شود:

1. کاربر فرم را `POST` می‌کند
2. سرور پردازش می‌کند
3. پیام را در session می‌گذارد
4. redirect می‌کند
5. صفحهٔ مقصد با `GET` پیام را نشان می‌دهد

این الگو هم تجربهٔ کاربری بهتری می‌دهد، هم از ارسال دوبارهٔ فرم در refresh جلوگیری می‌کند.

## جمع‌بندی

Flash message یک pattern ساده ولی بسیار کاربردی است:

- پیام را در session بگذار
- بعد از redirect آن را بخوان
- بلافاصله حذفش کن

در درس بعدی از همین مفاهیم برای ساختن یک جریان login/logout ساده استفاده می‌کنیم.
