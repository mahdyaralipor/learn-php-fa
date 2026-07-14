---
title: "شرط if و else"
weight: 1
---

# شرط if و else

شرط‌ها به برنامه می‌گویند «اگر این‌طور بود، این کار را بکن؛ وگرنه آن کار را».
پایهٔ اکثر منطق برنامه‌نویسی همین‌جاست.

## ساده‌ترین if

```php
<?php

$age = 20;

if ($age >= 18) {
    echo "بزرگسال" . PHP_EOL;
}
```

## if / else

```php
<?php

$age = 15;

if ($age >= 18) {
    echo "بزرگسال" . PHP_EOL;
} else {
    echo "نوجوان" . PHP_EOL;
}
```

## if / elseif / else

```php
<?php

$score = 72;

if ($score >= 90) {
    echo "عالی" . PHP_EOL;
} elseif ($score >= 75) {
    echo "خوب" . PHP_EOL;
} elseif ($score >= 50) {
    echo "قابل قبول" . PHP_EOL;
} else {
    echo "نیاز به تلاش بیشتر" . PHP_EOL;
}
```

## شرط‌های ترکیبی

```php
<?php

$isLoggedIn = true;
$isAdmin = false;

if ($isLoggedIn && $isAdmin) {
    echo "پنل مدیریت" . PHP_EOL;
} elseif ($isLoggedIn) {
    echo "پنل کاربر" . PHP_EOL;
} else {
    echo "لطفاً وارد شوید" . PHP_EOL;
}
```

## پرانتز برای وضوح

```php
<?php

$hasTicket = true;
$isVip = false;
$age = 16;

if ($hasTicket && ($isVip || $age >= 18)) {
    echo "ورود مجاز" . PHP_EOL;
} else {
    echo "ورود مجاز نیست" . PHP_EOL;
}
```

## مقایسهٔ سخت‌گیرانه در شرط

```php
<?php

$status = "0";

if ($status === false) {
    echo "غیرفعال";
} else {
    echo "وضعیت: {$status}";
}
```

با `===` از باگ `"0" == false` جلوگیری می‌کنی.

## شرط با مقدار truthy/falsy

```php
<?php

$username = "";

if ($username) {
    echo "سلام {$username}";
} else {
    echo "کاربر مهمان";
}
```

رشتهٔ خالی falsy است.

## ساختار جایگزین (Alternative Syntax)

برای فایل‌های HTML-PHP:

```php
<?php if ($isAdmin): ?>
    <p>خوش آمدید مدیر</p>
<?php else: ?>
    <p>خوش آمدید کاربر</p>
<?php endif; ?>
```

در اسکریپت CLI معمولاً از آکولاد `{}` استفاده می‌شود.

## نگه‌داشتن شرط خوانا

به‌جای این:

```php
<?php

if ($user['age'] >= 18 && $user['country'] === 'IR' && !$user['banned']) {
    // ...
}
```

بهتر:

```php
<?php

$canVote = $user['age'] >= 18
    && $user['country'] === 'IR'
    && $user['banned'] === false;

if ($canVote) {
    // ...
}
```

## مثال کاربردی: اعتبارسنجی سن

```php
<?php

function canRegister(int $age, bool $termsAccepted): bool
{
    if ($age < 13) {
        return false;
    }

    if (!$termsAccepted) {
        return false;
    }

    return true;
}

var_dump(canRegister(14, true));  // true
var_dump(canRegister(10, true));  // false
var_dump(canRegister(20, false)); // false
```

## `else if` یا `elseif`؟

هر دو معتبرند؛ `elseif` رایج‌تر است:

```php
<?php

if ($x < 0) {
} elseif ($x === 0) {
} else {
}
```

## اشتباهات رایج

### 1. `=` به‌جای `==` یا `===`

```php
if ($status = 'active') { } // همیشه true و مقداردهی می‌کند
```

### 2. شرط بدون آکولاد و چند خط

```php
if ($ok)
    doSomething();
    doAnother(); // همیشه اجرا می‌شود!
```

همیشه آکولاد بگذار.

### 3. تودرتویی بیش از حد

اگر بیش از دو سطح `if` داری، بازنویسی کن.

### 4. `==` برای دادهٔ کاربر

## تمرین

1. برنامه‌ای بنویس که عدد را بگیرد و زوج/فرد بودن را چاپ کند.
2. با `elseif` نمره را به A/B/C/D/F تبدیل کن.
3. شرطی بنویس که فقط وقتی `email` خالی نیست و شامل `@` است، قبول کند.
4. همان منطق را با متغیر میانی `$isValidEmail` بازنویسی کن.
5. عمداً `if ($x = 5)` بنویس و تفاوت با `if ($x === 5)` را ببین.

## جمع‌بندی

`if` و `else` تصمیم‌گیری را ممکن می‌کنند؛ `elseif` چند حالت را پوشش می‌دهد و ترکیب با `&&`/`||` منطق واقعی را می‌سازد.
در شرط‌ها `===` را عادت کن و شرط‌های پیچیده را با نام خوانا ساده کن.
در درس بعدی `switch` و `match` را می‌بینیم.
