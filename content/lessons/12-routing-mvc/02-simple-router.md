---
title: ساخت یک Router ساده
weight: 2
---

# ساخت Router ساده

Router کاری خیلی عجیب نمی‌کند: درخواست را می‌گیرد، method و path را نگاه می‌کند، و تصمیم می‌گیرد کدام callable اجرا شود.

## مسئله

اگر router نداشته باشی، معمولاً به if/elseهای طولانی می‌رسی:

```php
if ($method === 'GET' && $path === '/') {
    echo 'Home';
} elseif ($method === 'GET' && $path === '/login') {
    echo 'Login';
} elseif ($method === 'POST' && $path === '/login') {
    echo 'Handle login';
}
```

برای سه مسیر قابل‌تحمل است. برای سی مسیر نه.

## ایدهٔ حداقلی

یک آرایه نگه می‌داریم که کلیدش ترکیب method و path باشد و مقدارش handler.

### فایل `src/Router.php`

```php
<?php

declare(strict_types=1);

namespace App;

final class Router
{
    /** @var array<string, callable> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->map('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->map('POST', $path, $handler);
    }

    public function map(string $method, string $path, callable $handler): void
    {
        $key = $this->routeKey($method, $path);
        $this->routes[$key] = $handler;
    }

    public function dispatch(string $method, string $path): void
    {
        $key = $this->routeKey($method, $path);
        $handler = $this->routes[$key] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo 'Page not found';
            return;
        }

        $handler();
    }

    private function routeKey(string $method, string $path): string
    {
        return strtoupper($method) . ' ' . $path;
    }
}
```

## استفاده از Router

```php
<?php

declare(strict_types=1);

use App\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

$router->get('/', function (): void {
    echo 'خانه';
});

$router->get('/posts', function (): void {
    echo 'لیست پست‌ها';
});

$router->post('/posts', function (): void {
    echo 'ساخت پست';
});

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->dispatch($method, $path);
```

## این Router چه چیزهایی ندارد؟

فعلاً خیلی چیزها ندارد، و اشکالی هم ندارد:

- route parameter مثل `/posts/10`
- middleware
- named routes
- response object
- dependency injection خودکار

هدف فعلاً فهمیدن اصل ماجراست.

## چرا `callable` خوب است؟

چون handler می‌تواند چیزهای مختلفی باشد:

- closure
- تابع
- متد یک آبجکت

بعداً می‌توانی آن را به controller method هم وصل کنی.

## نکتهٔ عملی

وقتی path را از `REQUEST_URI` می‌گیری، بهتر است query string را حذف کنی. برای همین از `parse_url(..., PHP_URL_PATH)` استفاده کردیم.

## تمرین

1. متد `delete()` به Router اضافه کن.
2. یک route برای `GET /about` و یک route برای `POST /login` ثبت کن.
3. اگر route پیدا نشد، علاوه بر متن، status code `404` هم بفرست.
4. فکر کن برای route parameterها چه تغییری باید در Router بدهی.

## جمع‌بندی

Router ساده فقط یک جدول نگاشت بین method+path و handler است. همین ایدهٔ کوچک، پایهٔ اصلی frameworkهای وب را می‌سازد. در درس بعدی می‌فهمیم MVC چطور روی این routing سوار می‌شود.
