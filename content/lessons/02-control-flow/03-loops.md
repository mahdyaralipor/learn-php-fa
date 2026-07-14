---
title: "حلقه‌ها"
weight: 3
---

# حلقه‌ها

حلقه کار تکراری را برایت انجام می‌دهد: پیمایش آرایه، شمارش، جستجو، یا تلاش مجدد تا رسیدن به شرط.

## while

تا وقتی شرط true باشد اجرا می‌شود:

```php
<?php

$count = 1;

while ($count <= 5) {
    echo $count . PHP_EOL;
    $count++;
}
```

## do-while

حداقل **یک‌بار** اجرا می‌شود، بعد شرط را چک می‌کند:

```php
<?php

$number = 10;

do {
    echo $number . PHP_EOL;
    $number++;
} while ($number <= 5);
```

اینجا یک‌بار `10` چاپ می‌شود چون بعد از اجرا شرط false است.

## for

وقتی تعداد تکرار مشخص است:

```php
<?php

for ($i = 1; $i <= 5; $i++) {
    echo "شماره {$i}" . PHP_EOL;
}
```

سه بخش: مقداردهی اولیه؛ شرط؛ به‌روزرسانی.

## foreach

بهترین انتخاب برای آرایه:

```php
<?php

$languages = ['PHP', 'Python', 'Go'];

foreach ($languages as $lang) {
    echo $lang . PHP_EOL;
}
```

با کلید:

```php
<?php

$user = ['name' => 'علی', 'age' => 30];

foreach ($user as $key => $value) {
    echo "{$key}: {$value}" . PHP_EOL;
}
```

## break و continue

```php
<?php

for ($i = 1; $i <= 10; $i++) {
    if ($i % 2 === 0) {
        continue; // زوج‌ها را رد کن
    }

    if ($i > 7) {
        break; // بعد از ۷ متوقف شو
    }

    echo $i . PHP_EOL;
}
```

## break با عمق (PHP 7.3+)

```php
<?php

foreach (range(1, 3) as $i) {
    foreach (range(1, 3) as $j) {
        if ($j === 2) {
            break 2; // از هر دو حلقه خارج شو
        }
        echo "{$i}-{$j}" . PHP_EOL;
    }
}
```

## مثال: جمع اعداد

```php
<?php

$numbers = [10, 20, 30, 40];
$sum = 0;

foreach ($numbers as $n) {
    $sum += $n;
}

echo "جمع: {$sum}" . PHP_EOL;
```

## مثال: جستجو در آرایه

```php
<?php

$users = ['ali', 'sara', 'reza'];
$target = 'sara';
$found = false;

foreach ($users as $user) {
    if ($user === $target) {
        $found = true;
        break;
    }
}

echo $found ? 'پیدا شد' : 'پیدا نشد';
echo PHP_EOL;
```

در عمل بعداً `in_array` هم کافی است؛ این مثال منطق حلقه را نشان می‌دهد.

## حلقهٔ بی‌نهایت و خروج امن

```php
<?php

$attempts = 0;

while (true) {
    $attempts++;

    if ($attempts >= 3) {
        echo "توقف بعد از ۳ تلاش" . PHP_EOL;
        break;
    }

    echo "تلاش {$attempts}" . PHP_EOL;
}
```

## for با آرایه (کمتر رایج)

```php
<?php

$items = ['a', 'b', 'c'];

for ($i = 0, $len = count($items); $i < $len; $i++) {
    echo $items[$i] . PHP_EOL;
}
```

برای آرایه معمولاً `foreach` خواناتر است.

## حلقه تو در تو

```php
<?php

$matrix = [
    [1, 2],
    [3, 4],
];

foreach ($matrix as $row) {
    foreach ($row as $cell) {
        echo $cell . ' ';
    }
    echo PHP_EOL;
}
```

## کدام حلقه را انتخاب کنم؟

| موقعیت | پیشنهاد |
|--------|---------|
| پیمایش آرایه | `foreach` |
| تعداد مشخص تکرار | `for` |
| تا وقتی شرط برقرار است | `while` |
| حداقل یک‌بار اجرا | `do-while` |

## اشتباهات رایج

### 1. حلقهٔ بی‌پایان

```php
<?php

$i = 0;
while ($i < 5) {
    echo $i . PHP_EOL;
    // فراموش کردن $i++
}
```

### 2. تغییر آرایه هنگام foreach

```php
foreach ($items as $k => $v) {
    unset($items[$k]); // رفتار عجیب
}
```

### 3. off-by-one

```php
for ($i = 0; $i <= count($arr); $i++) // یکی بیشتر
```

### 4. استفاده از for برای آرایه انجمنی بدون نیاز

## تمرین

1. اعداد ۱ تا ۱۰۰ را جمع کن (با `for`).
2. فقط اعداد زوج ۱ تا ۲۰ را چاپ کن.
3. در آرایه‌ای از نام‌ها، اولین نام با حرف «س» را پیدا کن و `break` بزن.
4. جدول ضرب ۱ تا ۵ را با حلقهٔ تو در تو چاپ کن.
5. `while` و `do-while` را برای یک شرط false از ابتدا مقایسه کن.

## جمع‌بندی

`while` و `for` برای تکرار عمومی، `foreach` برای آرایه و `break`/`continue` برای کنترل جریان داخل حلقه به‌کار می‌روند.
حلقهٔ درست یعنی شرط واضح، خروج مشخص و بدون تغییر ناامن آرایه هنگام پیمایش.
در درس بعدی ternary و nullsafe را برای کوتاه‌کردن کد می‌بینیم.
