---
title: "آرایه‌ها"
weight: 7
---

# آرایه‌ها

آرایه در PHP یکی از پرکاربردترین ساختارهاست.
می‌تواند لیست ساده باشد یا نگاشت کلید به مقدار — و گاهی هر دو را با هم دارد.

## آرایهٔ اندیسی (Indexed)

```php
<?php

$fruits = ["سیب", "موز", "پرتقال"];

echo $fruits[0] . PHP_EOL; // سیب
echo $fruits[2] . PHP_EOL; // پرتقال
```

ایندکس از `0` شروع می‌شود.

## آرایهٔ انجمنی (Associative)

```php
<?php

$user = [
    'name' => 'سارا',
    'age' => 27,
    'email' => 'sara@example.com',
];

echo $user['name'] . PHP_EOL;
echo $user['age'] . PHP_EOL;
```

## ساخت با `array()`

سینتکس قدیمی هنوز معتبر است:

```php
<?php

$colors = array('قرمز', 'سبز', 'آبی');
```

در کد جدید بیشتر `[]` استفاده می‌شود.

## افزودن و ویرایش

```php
<?php

$items = ['الف', 'ب'];
$items[] = 'پ';              // انتهای آرایه
$items[1] = 'بِ';            // ویرایش
$items['key'] = 'مقدار';     // کلید رشته‌ای

print_r($items);
```

## `count` و `empty`

```php
<?php

$list = [1, 2, 3];

echo count($list) . PHP_EOL; // 3
var_dump(empty($list));      // false
var_dump(empty([]));         // true
```

`empty([])` یعنی آرایه خالی — یکی از مقادیر falsy.

## پیمایش با `foreach`

```php
<?php

$scores = [
    'علی' => 18,
    'سارا' => 19,
    'رضا' => 17,
];

foreach ($scores as $name => $score) {
    echo "{$name}: {$score}" . PHP_EOL;
}
```

فقط مقدار:

```php
<?php

foreach (['PHP', 'Go', 'Rust'] as $lang) {
    echo $lang . PHP_EOL;
}
```

## `array_push` و `array_pop`

```php
<?php

$stack = [];
array_push($stack, 'اول', 'دوم');
$last = array_pop($stack);

var_dump($stack); // ['اول']
var_dump($last);  // 'دوم'
```

## `in_array`

```php
<?php

$roles = ['admin', 'editor', 'viewer'];

var_dump(in_array('admin', $roles));      // true
var_dump(in_array('admin', $roles, true)); // true با مقایسه سخت‌گیرانه
```

پارامتر سوم `true` یعنی از `===` استفاده کن.

## `array_key_exists` و `isset`

```php
<?php

$data = ['name' => 'علی', 'age' => null];

var_dump(array_key_exists('name', $data)); // true
var_dump(isset($data['name']));            // true
var_dump(isset($data['age']));             // false چون null است
var_dump(array_key_exists('age', $data));  // true
```

تفاوت مهم: `isset` برای کلید با مقدار `null` false می‌دهد.

## `array_merge`

```php
<?php

$defaults = ['theme' => 'light', 'lang' => 'fa'];
$custom = ['theme' => 'dark'];

$config = array_merge($defaults, $custom);
print_r($config);
```

کلیدهای تکراری در آرایهٔ دوم جایگزین می‌شوند.

## `array_map`

```php
<?php

$numbers = [1, 2, 3, 4];
$squared = array_map(fn(int $n): int => $n * $n, $numbers);

print_r($squared);
```

## `array_filter`

```php
<?php

$values = [0, 1, 2, '', 'hello', null];

$filtered = array_filter($values);
print_r($filtered);
```

بدون callback، مقادیر falsy حذف می‌شوند.

## مرتب‌سازی

```php
<?php

$nums = [3, 1, 4, 2];
sort($nums);
print_r($nums);

$users = [
    ['name' => 'رضا', 'score' => 15],
    ['name' => 'سارا', 'score' => 20],
];

usort($users, fn(array $a, array $b): int => $b['score'] <=> $a['score']);
print_r($users);
```

## آرایهٔ چندبعدی

```php
<?php

$matrix = [
    [1, 2, 3],
    [4, 5, 6],
];

echo $matrix[1][2] . PHP_EOL; // 6
```

## List و آرایهٔ مختلط

```php
<?php

$mixed = [
    0 => 'اول',
    'id' => 42,
    1 => 'دوم',
];
```

PHP آرایه را به‌صورت مرتب‌شده نگه می‌دارد؛ هم ایندکس عددی دارد هم کلید رشته‌ای.

## Destructuring (PHP 7.1+)

```php
<?php

$point = [10, 20];
[$x, $y] = $point;

echo "{$x}, {$y}" . PHP_EOL;
```

با کلید:

```php
<?php

$user = ['name' => 'علی', 'age' => 30];
['name' => $name, 'age' => $age] = $user;
```

## Spread در آرایه (PHP 7.4+)

```php
<?php

$part1 = [1, 2];
$part2 = [3, 4];
$all = [...$part1, ...$part2];

print_r($all);
```

## مثال کاربردی: سبد خرید ساده

```php
<?php

$cart = [
    ['title' => 'ماوس', 'price' => 350000, 'qty' => 1],
    ['title' => 'کیبورد', 'price' => 1200000, 'qty' => 1],
];

$total = 0;

foreach ($cart as $item) {
    $lineTotal = $item['price'] * $item['qty'];
    $total += $lineTotal;
    echo "{$item['title']}: {$lineTotal}" . PHP_EOL;
}

echo "جمع کل: {$total}" . PHP_EOL;
```

## اشتباهات رایج

### 1. فرض کردن وجود کلید

```php
echo $user['phone']; // Warning اگر تعریف نشده
```

بهتر:

```php
$phone = $user['phone'] ?? 'ندارد';
```

### 2. مقایسهٔ آرایه با `==`

دو آرایه با `==` ممکن است برابر باشند در حالی که ترتیب یا نوع داخلی فرق دارد.
برای ساختار دقیق از `===` یا مقایسهٔ دستی استفاده کن.

### 3. تغییر آرایه هنگام `foreach`

```php
foreach ($items as $key => $value) {
    unset($items[$key]); // رفتار غیرمنتظره
}
```

### 4. استفاده از `count` روی null

```php
count(null); // TypeError در PHP 8+
```

## تمرین

1. آرایه‌ای از ۵ شهر بساز و با `foreach` چاپ کن.
2. آرایهٔ انجمنی محصول با `name`, `price`, `stock` بساز.
3. با `array_filter` فقط محصولاتی که `stock > 0` دارند را نگه دار.
4. با `usort` دانش‌آموزان را بر اساس نمره مرتب کن.
5. تفاوت `isset($arr['key'])` و `array_key_exists('key', $arr)` را وقتی مقدار `null` است تست کن.

## جمع‌بندی

آرایهٔ اندیسی برای لیست و آرایهٔ انجمنی برای نگاشت کلید-مقدار است.
`foreach` ابزار اصلی پیمایش است و توابعی مثل `array_map`, `array_filter`, `array_merge` کار روزمره را سریع می‌کنند.
با پایان این درس، فصل مبانی تمام می‌شود و در فصل بعدی سراغ شرط و حلقه می‌رویم.
