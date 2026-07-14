---
title: Transaction
weight: 4
---

# Transaction چیست؟

گاهی چند عملیات دیتابیسی به هم وابسته‌اند و باید یا همه با هم موفق شوند، یا هیچ‌کدام انجام نشوند. Transaction دقیقاً برای همین است.

## مثال ذهنی

فرض کن می‌خواهی:

1. از حساب A پول کم کنی
2. به حساب B پول اضافه کنی

اگر اولی انجام شود و دومی وسط کار fail شود، داده خراب می‌شود. Transaction کمک می‌کند این دو عملیات اتمی باشند.

## الگوی اصلی

```php
<?php

declare(strict_types=1);

try {
    $pdo->beginTransaction();

    // query 1
    // query 2

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    throw $e;
}
```

## مثال عملی

```php
<?php

declare(strict_types=1);

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('UPDATE accounts SET balance = balance - :amount WHERE id = :id');
    $stmt->execute([
        'amount' => 100,
        'id' => 1,
    ]);

    $stmt = $pdo->prepare('UPDATE accounts SET balance = balance + :amount WHERE id = :id');
    $stmt->execute([
        'amount' => 100,
        'id' => 2,
    ]);

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    exit('انتقال انجام نشد.');
}
```

## چرا داخل `try/catch`؟

چون اگر یکی از queryها exception بدهد، باید rollback کنیم تا دیتابیس در وضعیت نیمه‌کاره نماند.

## نکتهٔ مهم

همچنان داخل transaction هم باید از prepared statements استفاده کنی. Transaction جایگزین امنیت SQL نیست؛ فقط consistency را بهتر می‌کند.

## کِی از transaction استفاده کنیم؟

وقتی چند عملیات منطقیِ مرتبط داری. مثلاً:

- ثبت سفارش و کم کردن موجودی
- ساخت کاربر و ساخت پروفایل وابسته
- انتقال پول

اگر فقط یک `INSERT` ساده داری، معمولاً transaction اضافی لازم نیست.

## جمع‌بندی

Transaction برای «همه یا هیچ» است:

- `beginTransaction()`
- اجرای queryها
- `commit()`
- در خطا: `rollBack()`

در درس بعدی یک قدم معماری هم برمی‌داریم و دسترسی به دیتابیس را داخل یک `UserRepository` ساده جمع می‌کنیم.
