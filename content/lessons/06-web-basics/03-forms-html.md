---
title: فرم HTML و اتصال آن به PHP
weight: 3
---

# فرم HTML به PHP

بخش زیادی از برنامه‌های وب از فرم ساخته می‌شوند: ورود، ثبت‌نام، جست‌وجو، ارسال دیدگاه، آپلود فایل و خیلی چیزهای دیگر.

اگر فرم‌ها را خوب بفهمی، نصف راه برنامه‌نویسی وب را رفته‌ای.

## دو ویژگی مهم فرم

فرم HTML معمولاً این دو ویژگی را دارد:

- `method`
- `action`

مثال:

```html
<form method="post" action="/submit.php">
    <label for="name">نام</label>
    <input id="name" name="name" type="text">

    <button type="submit">ارسال</button>
</form>
```

## `method` چه می‌کند؟

- `get`: داده را در URL می‌گذارد
- `post`: داده را در body می‌فرستد

برای جست‌وجو، `GET` معمولاً خوب است.  
برای فرم‌هایی که داده را تغییر می‌دهند، `POST` بهتر است.

## `action` چه می‌کند؟

`action` مشخص می‌کند فرم به کدام آدرس فرستاده شود. اگر خالی باشد، معمولاً به همان صفحهٔ فعلی ارسال می‌شود.

خیلی وقت‌ها در آموزش از فرم self-submit استفاده می‌شود:

```php
<?php

declare(strict_types=1);

$name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
}
?>

<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>فرم ساده</title>
</head>
<body>
    <form method="post" action="">
        <label for="name">نام</label>
        <input
            id="name"
            name="name"
            type="text"
            value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
        >

        <button type="submit">ارسال</button>
    </form>

    <?php if ($name !== ''): ?>
        <p>سلام <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
</body>
</html>
```

## چرا `htmlspecialchars()` اینجا دو بار آمده؟

به خاطر این‌که دو جای مختلف خروجی HTML می‌سازیم:

1. داخل ویژگی `value`
2. داخل متن `<p>`

هر دو باید escape شوند. این یکی از پایه‌ای‌ترین دفاع‌های ما در برابر XSS است.

## `trim()` چرا مفید است؟

کاربر ممکن است اول و آخر ورودی فاصله بگذارد. `trim()` آن را حذف می‌کند:

```php
$name = trim($_POST['name'] ?? '');
```

این کار اعتبارسنجی را ساده‌تر می‌کند.

## فرم با چند فیلد

```php
<?php

declare(strict_types=1);

$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
}
?>

<form method="post" action="">
    <label for="name">نام</label>
    <input id="name" name="name" type="text"
        value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">

    <label for="email">ایمیل</label>
    <input id="email" name="email" type="email"
        value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">

    <button type="submit">ثبت</button>
</form>
```

## اشتباه رایج: اعتماد به input type

اگر `type="email"` گذاشتی، خوب است ولی کافی نیست. مرورگر ممکن است کمی کمک کند، اما امنیت و اعتبارسنجی واقعی باید در سمت سرور انجام شود.

یعنی این کافی نیست:

```html
<input type="email" name="email">
```

باید در PHP هم چک کنی.

## ساختار تمیزتر برای پردازش فرم

الگوی ساده و خوب:

1. مقدارهای پیش‌فرض را تعریف کن
2. اگر درخواست `POST` بود، داده را بخوان
3. اعتبارسنجی کن
4. اگر خطا نبود، پردازش کن
5. خروجی امن بساز

نمونه:

```php
<?php

declare(strict_types=1);

$errors = [];
$name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
        $errors[] = 'نام الزامی است.';
    }
}
```

## نکتهٔ امنیتی

چیزی که از فرم می‌آید را مستقیم:

- در HTML چاپ نکن
- در فایل ذخیره نکن
- در SQL نگذار
- در header نگذار

همیشه اول بررسی کن، بعد در context مناسب escape یا bind کن.

## جمع‌بندی

فرم HTML فقط ظاهر ورود داده است. مسئولیت واقعی در PHP شروع می‌شود:

- `method` و `action` را درست انتخاب کن
- فقط روی اعتبارسنجی مرورگر حساب نکن
- برای هر خروجی HTML از `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')` استفاده کن

در درس بعدی همین ورودی‌ها را برای آپلود فایل استفاده می‌کنیم؛ جایی که بی‌احتیاطی می‌تواند خیلی خطرناک‌تر شود.
