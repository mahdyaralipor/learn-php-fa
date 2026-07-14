---
title: "رشته‌ها"
weight: 6
---

# رشته‌ها

در PHP تقریباً همه‌جا با رشته سر و کار داری: نام کاربر، پیام خطا، HTML، JSON و خیلی چیزهای دیگر.
پس بهتر است از همین ابتدا ابزارهای اصلی کار با رشته را خوب بشناسی.

## تعریف رشته

```php
<?php

$single = 'سلام';
$double = "سلام";
$persian = "برنامه‌نویسی PHP";

echo $single . PHP_EOL;
echo $double . PHP_EOL;
```

## تفاوت کوتیشن تکی و دوتایی

```php
<?php

$name = "رضا";

echo 'سلام، $name' . PHP_EOL;   // سلام، $name
echo "سلام، {$name}" . PHP_EOL; // سلام، رضا
```

در کوتیشن دوتایی، متغیر جایگزین می‌شود.
در کوتیشن تکی، متن همان‌طور چاپ می‌شود.

### کاراکتر escape

```php
<?php

echo "خط اول\nخط دوم" . PHP_EOL;
echo 'خط اول\nخط دوم' . PHP_EOL;
echo "او گفت: \"سلام\"" . PHP_EOL;
```

## Heredoc و Nowdoc

### Heredoc (مثل کوتیشن دوتایی)

```php
<?php

$name = "سارا";

$text = <<<TEXT
سلام {$name}
این متن
چندخطی است.
TEXT;

echo $text;
```

### Nowdoc (مثل کوتیشن تکی)

```php
<?php

$name = "سارا";

$text = <<<'TEXT'
سلام {$name}
متغیر جایگزین نمی‌شود.
TEXT;

echo $text;
```

## طول رشته

```php
<?php

$message = "سلام دنیا";
echo strlen($message) . PHP_EOL; // بر حسب بایت
echo mb_strlen("سلام") . PHP_EOL; // برای یونیکد بهتر است
```

برای فارسی و یونیکد، `mb_*` توصیه می‌شود.

## الحاق و درون‌گذاری

```php
<?php

$first = "رضا";
$last = "محمدی";

$full = $first . " " . $last;
$greeting = "سلام، {$full}!";

echo $greeting . PHP_EOL;
```

## دسترسی به کاراکتر

```php
<?php

$word = "PHP";
echo $word[0] . PHP_EOL; // P
echo $word[2] . PHP_EOL; // P
```

## توابع پرکاربرد

### `strtolower` / `strtoupper`

```php
<?php

echo strtoupper("hello") . PHP_EOL;
echo mb_strtolower("سلام") . PHP_EOL;
```

### `trim`, `ltrim`, `rtrim`

```php
<?php

$input = "  سلام  ";
echo "|" . trim($input) . "|" . PHP_EOL;
```

### `str_contains` (PHP 8+)

```php
<?php

$email = "user@example.com";

if (str_contains($email, "@")) {
    echo "فرمت ایمیلی به نظر می‌رسد" . PHP_EOL;
}
```

### `str_starts_with` / `str_ends_with` (PHP 8+)

```php
<?php

$file = "report.pdf";

var_dump(str_ends_with($file, ".pdf"));   // true
var_dump(str_starts_with($file, "rep"));  // true
```

### `substr`

```php
<?php

$text = "برنامه‌نویسی PHP";
echo substr($text, 0, 6) . PHP_EOL;
echo mb_substr($text, 0, 6) . PHP_EOL; // برای فارسی
```

### `str_replace`

```php
<?php

$template = "سلام، {name}";
$message = str_replace("{name}", "علی", $template);
echo $message . PHP_EOL;
```

### `explode` و `implode`

```php
<?php

$csv = "علی,سارا,رضا";
$names = explode(",", $csv);

echo implode(" | ", $names) . PHP_EOL;
```

### `sprintf`

```php
<?php

$price = 1250000;
$formatted = sprintf("قیمت: %s تومان", number_format($price));
echo $formatted . PHP_EOL;
```

## مقایسهٔ رشته

```php
<?php

var_dump("abc" === "abc"); // true
var_dump(strcmp("abc", "abd") < 0); // true
```

برای مقایسهٔ دقیق مقدار، `===` را استفاده کن.

## رشته و عدد

```php
<?php

$quantity = "3";
$total = $quantity * 1000;

var_dump($total); // int(3000)
```

PHP در عملگرهای حسابی رشتهٔ عددی را تبدیل می‌کند.
برای اعتبارسنجی ورودی کاربر، قبل از تبدیل بررسی کن.

## مثال کاربردی: نرمال‌سازی ورودی

```php
<?php

function normalizeUsername(string $input): string
{
    $trimmed = trim($input);
    $lower = mb_strtolower($trimmed);
    return str_replace(' ', '_', $lower);
}

echo normalizeUsername("  Ali Reza  ") . PHP_EOL;
```

## اشتباهات رایج

### 1. `strlen` برای متن فارسی

`strlen` بایت می‌شمارد، نه تعداد حرف.

### 2. فراموش کردن `trim` روی ورودی کاربر

```php
if ($password === "secret") // " secret " رد می‌شود
```

### 3. الحاق در حلقه با `.=`

در حجم زیاد کند می‌شود؛ بعداً `implode` یا بافر بهتر است.

### 4. جایگزینی ناامن در HTML

برای خروجی HTML باید escape کنی — در فصل امنیت برمی‌گردیم.

## تمرین

1. رشته‌ای با Heredoc بساز که نام و سن را درون‌گذاری کند.
2. فایل `notes.txt` را با `str_contains` بررسی کن که پسوند `.txt` دارد.
3. رشته `"  علی, سارا , رضا "` را trim و explode کن و با ` | ` implode کن.
4. تابعی بنویس که اگر ایمیل `@` نداشت، `false` برگرداند.
5. تفاوت `strlen("سلام")` و `mb_strlen("سلام")` را با `var_dump` ببین.

## جمع‌بندی

رشته‌ها با `'` و `"` تعریف می‌شوند، Heredoc/Nowdoc برای متن چندخطی مفیدند و توابعی مثل `trim`, `explode`, `str_contains` کار روزمره را ساده می‌کنند.
در درس بعدی آرایه‌ها را می‌بینیم — ساختار اصلی نگه‌داری چند مقدار کنار هم.
