---
title: "پروژه ۳: TODO با فایل JSON و فرم وب"
weight: 3
---

# پروژه: TODO با JSON و فرم‌های ساده

این پروژه یک تمرین عالی برای اتصال همهٔ چیزهایی است که تا اینجا یاد گرفته‌ای:

- فرم HTML
- `$_POST`
- اعتبارسنجی
- `htmlspecialchars()`
- ریدایرکت
- ذخیره‌سازی در فایل

هنوز دیتابیس نداریم، پس داده‌ها را داخل یک فایل JSON ذخیره می‌کنیم.

## هدف پروژه

یک اپ کوچک TODO بساز که این قابلیت‌ها را داشته باشد:

- افزودن کار جدید
- نمایش فهرست کارها
- علامت‌زدن به‌عنوان انجام‌شده
- حذف کار

## ساختار پیشنهادی

```text
public/
  index.php
storage/
  todos.json
src/
  TodoStorage.php
```

## مدل داده

هر TODO می‌تواند این شکل را داشته باشد:

```json
{
  "id": "a1b2c3",
  "title": "خرید نان",
  "completed": false
}
```

## فایل ذخیره‌سازی

اگر فایل هنوز وجود ندارد، با آرایهٔ خالی شروع کن:

```php
<?php

declare(strict_types=1);

$path = __DIR__ . '/../storage/todos.json';

if (!file_exists($path)) {
    file_put_contents($path, json_encode([], JSON_THROW_ON_ERROR));
}
```

## کلاس سادهٔ ذخیره‌سازی

```php
<?php

declare(strict_types=1);

final class TodoStorage
{
    public function __construct(private string $path)
    {
    }

    public function all(): array
    {
        if (!file_exists($this->path)) {
            return [];
        }

        $json = file_get_contents($this->path);

        if ($json === false || $json === '') {
            return [];
        }

        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public function saveAll(array $todos): void
    {
        file_put_contents(
            $this->path,
            json_encode($todos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
        );
    }
}
```

## افزودن TODO

در `index.php`:

```php
<?php

declare(strict_types=1);

$storage = new TodoStorage(__DIR__ . '/../storage/todos.json');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');

    if ($title === '') {
        $errors[] = 'عنوان کار الزامی است.';
    } elseif (mb_strlen($title) > 200) {
        $errors[] = 'عنوان کار نباید بیشتر از ۲۰۰ کاراکتر باشد.';
    }

    if ($errors === []) {
        $todos = $storage->all();
        $todos[] = [
            'id' => bin2hex(random_bytes(8)),
            'title' => $title,
            'completed' => false,
        ];

        $storage->saveAll($todos);

        header('Location: /');
        exit;
    }
}
```

## نمایش لیست

```php
$todos = $storage->all();
```

و در HTML:

```php
<?php foreach ($todos as $todo): ?>
    <li>
        <?= htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8') ?>

        <?php if ($todo['completed'] === true): ?>
            <strong>(انجام شد)</strong>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
```

به `=== true` و `htmlspecialchars()` دقت کن.

## فرم ثبت کار

```php
<form method="post" action="">
    <label for="title">عنوان کار</label>
    <input id="title" name="title" type="text">
    <button type="submit">افزودن</button>
</form>
```

## تکمیل‌کردن یک کار

می‌توانی با یک فرم جدا برای هر آیتم این کار را انجام بدهی:

```php
<form method="post" action="/toggle.php">
    <input type="hidden" name="id" value="<?= htmlspecialchars($todo['id'], ENT_QUOTES, 'UTF-8') ?>">
    <button type="submit">تغییر وضعیت</button>
</form>
```

در `toggle.php`:

1. `id` را بخوان
2. لیست را بارگذاری کن
3. TODO موردنظر را پیدا کن
4. `completed` را برعکس کن
5. ذخیره کن
6. redirect کن

## حذف

برای حذف هم همین الگو را برو:

- فرم `POST`
- فیلد hidden برای `id`
- جست‌وجو در آرایه
- حذف مورد
- ذخیرهٔ دوباره

برای عملیات تغییر state از `GET` استفاده نکن.

## نکات امنیتی و تمیزی

- عنوان TODO را قبل از ذخیره validate کن
- هنگام نمایش، همیشه `htmlspecialchars()` بزن
- برای مسیر فایل، به ورودی کاربر اعتماد نکن
- بعد از `POST`، redirect کن

## ایده برای توسعه

- فیلتر «همه / انجام‌شده / انجام‌نشده»
- زمان ساخت
- flash message
- CSRF token روی فرم‌ها

## جمع‌بندی

این پروژه عمداً ساده است، اما از نظر آموزشی خیلی ارزش دارد. چون به تو یاد می‌دهد:

- فرم واقعاً چطور به PHP وصل می‌شود
- داده چطور validate و ذخیره می‌شود
- چرا `htmlspecialchars()` باید پیش‌فرض باشد
- چرا `POST` + redirect الگوی خوبی است

اگر این پروژه را تمیز بسازی، برای پروژهٔ بعدی که auth + PDO دارد آماده‌ای.
