# پروژه نهایی MVC — بلاگ ساده

اسکلت آموزشی MVC بدون فریم‌ورک برای دوره PHP فارسی.

## پیش‌نیاز

- PHP 8.2+
- افزونه‌های `pdo` و `pdo_sqlite`
- [Composer](https://getcomposer.org/)

## نصب

```bash
cd examples/final-mvc
composer install
```

> **توجه:** تا وقتی `composer install` اجرا نشود، فایل `vendor/autoload.php` وجود ندارد و اپ اجرا نمی‌شود.

## اجرا

```bash
php -S localhost:8000 -t public
```

سپس در مرورگر باز کنید: [http://localhost:8000](http://localhost:8000)

## ورود

- نام کاربری: `admin`
- رمز عبور: `secret`

برای ساخت پست جدید باید وارد شوید.

## پایگاه داده

در اولین اجرا، فایل SQLite در `database/app.db` ساخته می‌شود و `schema.sql` و `seed.sql` اعمال می‌شوند.

## ساختار

```text
public/index.php     ← front controller
routes/web.php       ← تعریف مسیرها
src/                 ← Router، Controller، Model، Middleware
views/               ← قالب‌های HTML
config/app.php       ← تنظیمات
database/            ← schema و seed
```

## امکانات

- مسیر `GET /` → صفحهٔ خانه
- CRUD پست‌ها: لیست، نمایش، ساخت (SQLite + PDO)
- ورود/خروج با session
- CSRF روی فرم‌های POST
- escape خروجی با helper `e()`
- flash message برای پیام‌های موقت

## Apache

اگر Apache دارید، `public/` را document root قرار دهید. فایل `public/.htaccess` درخواست‌ها را به `index.php` هدایت می‌کند.
