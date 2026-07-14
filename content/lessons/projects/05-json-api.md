---
title: "پروژه ۵: ساخت JSON API برای پست‌ها"
weight: 5
---

# پروژه: JSON API برای پست‌ها

این پروژه جمع‌بندی فصل API است. قرار است یک API ساده اما تمیز برای مدیریت پست‌ها بسازی.

## هدف پروژه

یک API برای resource پست‌ها با این قابلیت‌ها:

- گرفتن لیست پست‌ها
- گرفتن جزئیات یک پست
- ساخت پست جدید
- ویرایش پست
- حذف پست
- پاسخ‌های JSON یکدست
- احراز هویت ساده برای عملیات حساس

## مسیرهای پیشنهادی

- `GET /api/posts`
- `GET /api/posts/{id}`
- `POST /api/posts`
- `PUT /api/posts/{id}`
- `DELETE /api/posts/{id}`

## ساختار پیشنهادی پروژه

```text
public/
  index.php
src/
  Router.php
  Controllers/
    Api/PostController.php
  Repositories/
    PostRepository.php
  Support/
    JsonResponse.php
    Auth.php
views/
```

در API شاید view HTML نداشته باشی، اما هنوز controller و repository خیلی مفیدند.

## جدول `posts`

```sql
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## قرارداد پاسخ موفق

### لیست پست‌ها

```json
{
  "data": [
    {
      "id": 1,
      "title": "اولین پست",
      "body": "متن..."
    }
  ],
  "meta": {
    "limit": 10,
    "offset": 0,
    "total": 42
  }
}
```

### نمایش یک پست

```json
{
  "data": {
    "id": 1,
    "title": "اولین پست",
    "body": "متن..."
  }
}
```

## قرارداد خطا

```json
{
  "error": {
    "code": "validation_failed",
    "message": "ورودی معتبر نیست.",
    "details": {
      "title": [
        "عنوان الزامی است."
      ]
    }
  }
}
```

## Router و front controller

همهٔ درخواست‌ها را از `public/index.php` عبور بده. از همان Router ساده‌ای که در فصل MVC ساختی می‌توانی استفاده کنی و به‌مرور بهترش کنی.

## helper برای JSON response

```php
<?php

declare(strict_types=1);

function jsonResponse(array $payload, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}
```

## `PostRepository`

مسئول کار با دیتابیس باشد:

- `paginate(int $limit, int $offset): array`
- `countAll(): int`
- `find(int $id): ?array`
- `create(string $title, string $body): int`
- `update(int $id, string $title, string $body): bool`
- `delete(int $id): bool`

## `PostController`

مسئول هماهنگی request و response:

- parse کردن JSON body
- validation
- صدا زدن repository
- برگرداندن status code و payload مناسب

## نمونهٔ ساخت پست

```php
<?php

declare(strict_types=1);

$rawBody = file_get_contents('php://input');
$data = json_decode($rawBody, true);

if (!is_array($data)) {
    jsonResponse([
        'error' => [
            'code' => 'invalid_json',
            'message' => 'بدنهٔ درخواست JSON معتبر نیست.',
            'details' => [],
        ],
    ], 400);
}

$title = trim((string) ($data['title'] ?? ''));
$body = trim((string) ($data['body'] ?? ''));
$errors = [];

if ($title === '') {
    $errors['title'][] = 'عنوان الزامی است.';
}

if ($body === '') {
    $errors['body'][] = 'متن پست الزامی است.';
}

if ($errors !== []) {
    jsonResponse([
        'error' => [
            'code' => 'validation_failed',
            'message' => 'ورودی معتبر نیست.',
            'details' => $errors,
        ],
    ], 422);
}

$postId = $postRepository->create($title, $body);
$post = $postRepository->find($postId);

jsonResponse(['data' => $post], 201);
```

## auth ساده برای عملیات حساس

می‌توانی برای `POST`، `PUT` و `DELETE` از Bearer token استفاده کنی. برای `GET`ها API را عمومی نگه داری.

## گام‌های پیشنهادی ساخت پروژه

1. جدول `posts` را بساز.
2. اتصال PDO را راه‌اندازی کن.
3. `PostRepository` را پیاده کن.
4. `jsonResponse()` و `jsonError()` بساز.
5. Router را برای endpointهای API تنظیم کن.
6. متدهای `index`, `show`, `store`, `update`, `destroy` را در controller بنویس.
7. validation و status codeها را تمیز کن.
8. pagination را به endpoint لیست اضافه کن.
9. token auth ساده را برای endpointهای تغییردهنده فعال کن.

## پیشنهاد تست دستی

با ابزارهایی مثل `curl` یا Postman این سناریوها را امتحان کن:

- گرفتن لیست خالی پست‌ها
- ساخت پست معتبر
- ساخت پست نامعتبر
- گرفتن پست ناموجود
- ویرایش بدون token
- حذف موفق

## خروجی نهایی مطلوب

اگر این پروژه را خوب بسازی، دیگر فقط «PHP که JSON چاپ می‌کند» نداری؛ یک API کوچک ولی استاندارد داری که مسیرها، status codeها، validation، auth و ساختار خطاهایش معنی‌دار است.
