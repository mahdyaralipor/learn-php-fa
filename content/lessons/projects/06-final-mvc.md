---
title: "پروژه ۶: اپ نهایی MVC برای بلاگ یا مدیریت کارها"
weight: 6
---

# پروژه نهایی: ساخت اپ MVC کامل

این پروژه جمع‌بندی بخش حرفه‌ای دوره است. حالا دیگر قرار نیست فقط یک فایل یا یک مفهوم را تمرین کنی؛ قرار است یک اپ کوچک ولی واقعی با ساختار تمیز بسازی.

## مثال آماده در مخزن

یک اسکلت کامل و قابل‌اجرا در پوشهٔ `examples/final-mvc` قرار دارد. می‌توانی:

1. ساختار را بخوانی و اجرا کنی
2. مرحله‌به‌مرحله همان را خودت بازسازی کنی
3. یا روی آن feature جدید (ویرایش، حذف، API) اضافه کنی

### اجرای سریع مثال

```bash
cd examples/final-mvc
composer install
php -S localhost:8000 -t public
```

مرورگر: `http://localhost:8000`

ورود پیش‌فرض: `admin` / `secret`

> تا `composer install` اجرا نشود، `vendor/autoload.php` وجود ندارد.

## هدف پروژه

ساخت یک اپ MVC با این ویژگی‌ها:

- front controller
- router ساده
- controller و view مجزا
- model/repository برای کار با داده
- session و auth ساده
- validation، CSRF و escaping
- SQLite با PDO

## ساختار پوشه‌ها (مثال بلاگ)

```text
examples/final-mvc/
  public/
    index.php          ← front controller
    .htaccess          ← rewrite (Apache)
  config/
    app.php
  routes/
    web.php
  src/
    Router.php
    Controller.php
    Controllers/
    Middleware/
    Models/
    Support/
  views/
  database/
    schema.sql
    seed.sql
  composer.json
```

## گام ۱: Composer و autoload

فایل `composer.json`:

```json
{
    "require": { "php": ">=8.2" },
    "autoload": {
        "psr-4": { "App\\": "src/" },
        "files": ["src/Support/helpers.php"]
    }
}
```

سپس:

```bash
composer install
```

## گام ۲: front controller

`public/index.php` تنها نقطهٔ ورود وب است:

1. `vendor/autoload.php` را load کن
2. session را شروع کن
3. PDO/SQLite را بساز (schema در اولین اجرا)
4. routeها را ثبت کن
5. درخواست را dispatch کن

```php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$router->dispatch($method, $path);
```

## گام ۳: Router

Router سادهٔ دوره:

- `get()` و `post()` برای ثبت مسیر
- پارامتر مسیر مثل `/posts/{id}`
- middleware اختیاری قبل از handler

مسیرهای اصلی مثال:

| Method | Path | توضیح |
|--------|------|-------|
| GET | `/` | خانه |
| GET | `/posts` | لیست پست‌ها |
| GET | `/posts/{id}` | نمایش پست |
| GET | `/posts/create` | فرم ساخت (نیاز به login) |
| POST | `/posts` | ذخیره پست (login + CSRF) |
| GET | `/login` | فرم ورود |
| POST | `/login` | ورود (CSRF) |
| POST | `/logout` | خروج |

**نکته:** مسیر `/posts/create` را قبل از `/posts/{id}` ثبت کن تا `create` به‌عنوان id تفسیر نشود.

## گام ۴: Controller و View

Controller فقط هماهنگ‌کننده است:

```php
final class PostController extends Controller
{
    public function index(): void
    {
        $this->view('posts/index', [
            'title' => 'همهٔ پست‌ها',
            'posts' => $this->posts->all(),
        ]);
    }
}
```

Viewها از helper `e()` برای escape استفاده می‌کنند:

```php
<h1><?= e($post['title']) ?></h1>
```

Layout مشترک (`views/layout.php`) nav، flash message و CSRF logout را یکجا نگه می‌دارد.

## گام ۵: Model و SQLite

`database/schema.sql` جداول `users` و `posts` را می‌سازد. در اولین اجرا:

- فایل `database/app.db` ساخته می‌شود
- `seed.sql` کاربر `admin` و یک پست نمونه اضافه می‌کند

Model `Post` فقط prepared statement:

```php
$stmt = $this->pdo->prepare('SELECT * FROM posts WHERE id = :id');
$stmt->execute(['id' => $id]);
```

## گام ۶: Session و Auth

- بعد از login موفق: `session_regenerate_id(true)`
- رمز با `password_hash()` / `password_verify()`
- `AuthMiddleware` مسیرهای محافظت‌شده را چک می‌کند

## گام ۷: CSRF

هر فرم POST باید توکن داشته باشد:

```php
<form method="post">
    <?= \App\Support\Csrf::field() ?>
    ...
</form>
```

در controller:

```php
$this->requireCsrf();
```

## گام ۸: Flash message

پیام‌های یک‌بارمصرف بعد از redirect:

```php
Session::flash('success', 'پست ذخیره شد.');
$this->redirect('/posts/' . $id);
```

در layout:

```php
<?php if ($msg = Session::flash('success')): ?>
    <div class="flash"><?= e($msg) ?></div>
<?php endif; ?>
```

## گام ۹: تست دستی

چک‌لیست:

- [ ] `/` بدون خطا باز می‌شود
- [ ] `/posts` لیست seed را نشان می‌دهد
- [ ] `/posts/1` پست تکی را نشان می‌دهد
- [ ] بدون login، `/posts/create` به login redirect می‌شود
- [ ] با `admin`/`secret` login و پست جدید بساز
- [ ] POST بدون CSRF رد می‌شود
- [ ] خروجی HTML escape شده (XSS پایه)

## گام ۱۰: گسترش (اختیاری)

اگر مثال پایه را فهمیدی:

- ویرایش و حذف پست
- validation جداگانه (کلاس Validator)
- endpoint JSON (`GET /api/posts`)
- PHPUnit برای Model و Router
- deploy با `public/` به‌عنوان document root

## معیار موفقیت

پروژهٔ خوب الزاماً feature زیاد ندارد. این‌ها مهم‌اند:

- ساختار قابل‌فهم
- مسئولیت‌های جدا
- امنیت پایه (CSRF، escape، prepared statement، password_hash)
- flow روشن از request تا response

## جمع‌بندی

پروژهٔ نهایی جایی است که Composer، routing، MVC، session، PDO، امنیت و deploy به هم می‌رسند. مثال `examples/final-mvc` نقطهٔ شروع عملی است — آن را اجرا کن، کد را بخوان، بعد مرحله‌به‌مرحله خودت بساز یا گسترش بده.
