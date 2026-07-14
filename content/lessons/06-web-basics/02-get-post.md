---
title: کار با GET و POST
weight: 2
---

# `$_GET` و `$_POST`

وقتی داده از URL یا فرم وارد برنامه می‌شود، PHP آن را معمولاً در superglobalها قرار می‌دهد.

- `$_GET` برای پارامترهای URL
- `$_POST` برای بدنهٔ فرم‌های `POST`
- `$_REQUEST` ترکیبی از چند منبع

## خواندن از `$_GET`

مثلاً اگر آدرس این باشد:

```text
/search.php?q=php&page=2
```

می‌توانی این‌طور بخوانی:

```php
<?php

declare(strict_types=1);

$query = $_GET['q'] ?? '';
$page = $_GET['page'] ?? '1';

echo 'Query: ' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
echo '<br>';
echo 'Page: ' . htmlspecialchars($page, ENT_QUOTES, 'UTF-8');
```

اینجا دو نکته مهم داریم:

1. از `??` استفاده کردیم تا اگر کلید وجود نداشت خطا نگیریم.
2. برای نمایش خروجی در HTML از `htmlspecialchars()` استفاده کردیم.

## خواندن از `$_POST`

فرم `POST` معمولاً داده را در URL نشان نمی‌دهد و برای عملیات‌هایی مثل ثبت‌نام، ورود، یا ارسال فرم مناسب‌تر است.

```php
<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';

    echo 'سلام ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
}
```

به `===` دقت کن. برای مقایسهٔ متد درخواست، مقایسهٔ strict عادت بهتری است.

## چرا `$_REQUEST` را discourage می‌کنیم؟

`$_REQUEST` معمولاً ترکیبی از `GET`، `POST` و گاهی `COOKIE` است. این موضوع منبع داده را مبهم می‌کند.

مثلاً اگر بنویسی:

```php
$email = $_REQUEST['email'] ?? '';
```

دیگر واضح نیست این مقدار از URL آمده، از فرم آمده، یا از کوکی. برای کد تمیز و امن، منبع ورودی باید مشخص باشد.

پس:

- اگر از URL می‌خوانی، `$_GET`
- اگر از فرم POST می‌خوانی، `$_POST`
- اگر می‌خواهی تمیزتر و امن‌تر باشی، `filter_input()`

## استفاده از `filter_input()`

راه بهتر برای خواندن ورودی، مخصوصاً برای داده‌های ساده، این است:

```php
<?php

declare(strict_types=1);

$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);

if ($page === false || $page === null) {
    $page = 1;
}

echo $page;
```

اینجا:

- `null` یعنی ورودی وجود نداشته
- `false` یعنی بوده ولی معتبر نبوده

این تفاوت خیلی مهم است.

## نمونه برای ایمیل

```php
<?php

declare(strict_types=1);

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if ($email === false || $email === null) {
    echo 'ایمیل معتبر نیست.';
    exit;
}

echo 'ایمیل معتبر است: ' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
```

## نوع داده را خودت نادیده نگیر

همهٔ ورودی‌های HTTP در نهایت به‌صورت رشته می‌آیند. حتی اگر کاربر در فرم عدد وارد کند، تو باید خودت آن را اعتبارسنجی و تبدیل کنی.

اشتباه رایج:

```php
$age = $_POST['age'] ?? 0;
if ($age > 18) {
    // ...
}
```

نسخهٔ بهتر:

```php
<?php

declare(strict_types=1);

$age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);

if ($age === false || $age === null) {
    echo 'سن معتبر نیست.';
    exit;
}

if ($age >= 18) {
    echo 'مجاز';
}
```

## قانون طلایی

خواندن ورودی با گرفتن مقدار فرق دارد. تو باید هم‌زمان این سه سؤال را بپرسی:

1. این داده از کجا آمده؟
2. آیا وجود دارد؟
3. آیا معتبر است؟

## جمع‌بندی

برای کد واقعی این پیش‌فرض را داشته باش:

- `$_GET` و `$_POST` بهتر از `$_REQUEST` هستند چون منبع داده را روشن می‌کنند.
- `filter_input()` برای ورودی‌های ساده انتخاب تمیزتری است.
- هر داده‌ای که نمایش می‌دهی باید با `htmlspecialchars()` escape شود.

در درس بعدی فرم HTML را به PHP وصل می‌کنیم تا این مسیر را در عمل ببینی.
