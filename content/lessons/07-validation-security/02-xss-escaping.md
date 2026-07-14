---
title: XSS و فراردهی خروجی
weight: 2
---

# XSS چیست؟

XSS مخفف `Cross-Site Scripting` است. به زبان ساده، وقتی دادهٔ کاربر بدون escape مناسب در صفحه قرار بگیرد، ممکن است مرورگر آن را به‌عنوان کد اجرا کند.

مثلاً اگر کاربر به‌جای نام، این را وارد کند:

```html
<script>alert('xss')</script>
```

و تو همان را مستقیم چاپ کنی، مرورگر اسکریپت را اجرا می‌کند.

## دفاع پایه: `htmlspecialchars()`

برای خروجی HTML، ابزار اصلی ما این است:

```php
htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
```

چرا این نسخه را به‌عنوان پیش‌فرض حفظ می‌کنیم؟

- `ENT_QUOTES` هم نقل‌قول تکی و هم دوتایی را escape می‌کند
- `'UTF-8'` encoding را شفاف می‌کند

## مثال مستقیم

اشتباه:

```php
echo $_GET['name'] ?? '';
```

درست:

```php
echo htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES, 'UTF-8');
```

## escape وابسته به context است

این خیلی مهم است: «یک escape برای همه‌جا» وجود ندارد.

چند context رایج:

- متن داخل HTML
- مقدار داخل attribute
- داخل JavaScript
- داخل URL

`htmlspecialchars()` برای HTML و attributeهای معمولی خوب است، اما مثلاً برای جاگذاری مستقیم در JavaScript کافی نیست.

## داخل متن HTML

```php
<p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
```

## داخل attribute

```php
<input value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
```

برای همین است که `ENT_QUOTES` مهم است.

## اشتباه رایج: اعتماد به «بی‌خطر بودن داده»

خیلی‌ها می‌گویند:

- این داده از دیتابیس آمده
- این داده قبلاً validate شده
- این داده فقط اسم کاربر است

اما XSS به «منبع داده» ربط ندارد؛ به «نحوهٔ خروجی دادن» ربط دارد. حتی دادهٔ سالم اگر در context اشتباه بدون escape قرار بگیرد، خطرناک می‌شود.

## مثال از الگوی درست

```php
<?php

declare(strict_types=1);

$comment = trim($_POST['comment'] ?? '');
?>

<h2>نظر شما</h2>
<p><?= htmlspecialchars($comment, ENT_QUOTES, 'UTF-8') ?></p>
```

## اگر HTML واقعی بخواهیم چه؟

اگر قرار باشد کاربر HTML وارد کند، دیگر `htmlspecialchars()` کل HTML را به متن عادی تبدیل می‌کند. این رفتار برای بیشتر فرم‌ها خوب است، اما اگر واقعاً HTML مجاز می‌خواهی، باید whitelist و sanitization جدی‌تری داشته باشی. این کار موضوع ساده‌ای نیست و نباید سرسری انجام شود.

برای بیشتر اپلیکیشن‌های آموزشی و واقعی، اجازه ندادن به HTML کاربر انتخاب امن‌تری است.

## XSS فقط `<script>` نیست

حمله می‌تواند با event handlerها، URLهای خاص، یا تزریق در attributeها هم انجام شود. پس دنبال blacklist کردن چند رشته نباش.

این اشتباه است:

```php
$input = str_replace('<script>', '', $input);
```

چرا؟ چون مهاجم راه‌های زیادی دارد و blacklist تقریباً همیشه ناقص است.

## قانون عملی

وقتی داده را در HTML نشان می‌دهی، پیش‌فرضت باید این باشد:

```php
htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
```

و فقط اگر واقعاً دلیل خاصی داری، از این الگو فاصله بگیر.

## جمع‌بندی

برای مقابله با XSS:

- ورودی را validate کن
- خروجی را بر اساس context escape کن
- برای HTML معمولی، `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` را پیش‌فرض قرار بده
- به blacklistها اعتماد نکن

در درس بعدی سراغ CSRF می‌رویم؛ حمله‌ای که حتی بدون تزریق کد هم می‌تواند کاربر را وادار به انجام عملیات ناخواسته کند.
