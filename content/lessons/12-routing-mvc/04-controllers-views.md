---
title: Controller و View در عمل
weight: 4
---

# Controller و View در عمل

حالا می‌خواهیم MVC را از حالت مفهومی بیرون بیاوریم. ایدهٔ ساده این است:

- controller داده را آماده می‌کند
- view آن داده را نمایش می‌دهد
- view باید خروجی HTML امن تولید کند

## مثال: نمایش پست‌ها

### فایل `src/Controllers/PostController.php`

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PostRepository;

final class PostController
{
    public function __construct(private PostRepository $posts)
    {
    }

    public function index(): void
    {
        $allPosts = $this->posts->all();

        render('posts/index', [
            'posts' => $allPosts,
            'pageTitle' => 'همهٔ پست‌ها',
        ]);
    }
}
```

### یک helper ساده برای render

```php
<?php

declare(strict_types=1);

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);

    require __DIR__ . '/../views/' . $view . '.php';
}
```

## فایل view

### فایل `views/posts/index.php`

```php
<?php

declare(strict_types=1);
?>
<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>

    <?php foreach ($posts as $post): ?>
        <article>
            <h2><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h2>
            <p><?= nl2br(htmlspecialchars($post['body'], ENT_QUOTES, 'UTF-8')) ?></p>
        </article>
    <?php endforeach; ?>
</body>
</html>
```

## چرا escaping این‌قدر مهم است؟

چون view جایی است که دادهٔ ورودی ممکن است وارد HTML شود. اگر بدون escaping چاپش کنی، راه برای XSS باز می‌شود.

به همین خاطر در HTML معمولاً باید از این الگو استفاده کنی:

```php
htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
```

## controller چه چیزی نباید بکند؟

- ساختن HTML طولانی با `echo`
- نوشتن query مستقیم همه‌جا
- مخلوط‌کردن منطق نمایش با منطق دامنه

## view چه چیزی نباید بکند؟

- query زدن به دیتابیس
- تصمیم‌های سنگین کسب‌وکار
- دست‌کاری session و redirect

## `extract()` خوب است یا بد؟

برای پروژهٔ آموزشی و view helper ساده، قابل‌قبول است. اما باید با احتیاط استفاده شود. `EXTR_SKIP` کمک می‌کند متغیر موجود بی‌جهت overwrite نشود.

اگر خواستی شفاف‌تر باشی، می‌توانی آرایهٔ `$data` را مستقیم به view بدهی و از `extract()` استفاده نکنی. ولی برای شروع، این helper ساده خواناست.

## تمرین

1. یک `HomeController` با متد `index()` بساز که view صفحهٔ اصلی را render کند.
2. در view، آرایه‌ای از taskها را با escaping نمایش بده.
3. عمداً یک title مخرب مثل `<script>alert(1)</script>` تصور کن و توضیح بده escaping چه چیزی را خنثی می‌کند.

## جمع‌بندی

controller باید داده را آماده کند و view باید فقط نمایش امن را انجام دهد. همین تفکیک ساده، قدم بزرگی به سمت MVC تمیز است. در درس بعدی middleware را به این جریان اضافه می‌کنیم.
