---
title: Prepared Statements
weight: 3
---

# Prepared Statements

اگر از این فصل فقط یک چیز را عمیق یاد بگیری، همین است.

`Prepared statement` یعنی query و data را از هم جدا نگه داری. این هم از نظر امنیتی عالی است، هم کد را تمیزتر می‌کند.

## الگوی پایه

```php
<?php

declare(strict_types=1);

$stmt = $pdo->prepare('SELECT id, email FROM users WHERE email = :email');
$stmt->execute([
    'email' => $email,
]);

$user = $stmt->fetch();
```

## `prepare()` و `execute()`

- `prepare()` ساختار query را تعریف می‌کند
- `execute()` مقدارهای واقعی را می‌فرستد

این جداسازی همان چیزی است که جلوی SQL injection را می‌گیرد.

## مثال با `INSERT`

```php
$stmt = $pdo->prepare(
    'INSERT INTO users (name, email) VALUES (:name, :email)'
);

$stmt->execute([
    'name' => $name,
    'email' => $email,
]);
```

## مثال با `UPDATE`

```php
$stmt = $pdo->prepare(
    'UPDATE users SET name = :name WHERE id = :id'
);

$stmt->execute([
    'name' => $name,
    'id' => $id,
]);
```

## `bindValue()` و `bindParam()`

گاهی به‌جای آرایهٔ `execute()` از bind استفاده می‌کنی:

```php
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
```

برای خیلی از سناریوها، `execute([...])` ساده‌تر و خواناتر است. ولی خوب است `bindValue()` را هم بشناسی.

## fetch mode

برای گرفتن یک ردیف:

```php
$user = $stmt->fetch(PDO::FETCH_ASSOC);
```

برای گرفتن همه:

```php
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

اگر نتیجه‌ای پیدا نشود، `fetch()` مقدار `false` برمی‌گرداند. پس بررسی strict لازم است:

```php
if ($user === false) {
    echo 'کاربر پیدا نشد.';
}
```

## مثال کامل‌تر

```php
<?php

declare(strict_types=1);

$email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);

if ($email === false || $email === null) {
    exit('ایمیل معتبر نیست.');
}

$stmt = $pdo->prepare('SELECT id, email, name FROM users WHERE email = :email');
$stmt->execute([
    'email' => $email,
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user === false) {
    exit('کاربر پیدا نشد.');
}

echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
```

ببین چطور چند عادت خوب کنار هم آمده‌اند:

- `filter_input()`
- prepared statement
- `=== false`
- `htmlspecialchars()`

## چیزی که هرگز نباید بکنی

```php
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $pdo->query($sql);
```

این همان چیزی است که می‌خواهیم برای همیشه کنار بگذاری.

## جمع‌بندی

در کار با دیتابیس، prepared statement باید پیش‌فرض ذهنی تو باشد.  
هرجا ورودی کاربر وارد SQL می‌شود، پاسخ درست تقریباً همیشه یکی است:

`prepare()` + `execute()`

در درس بعدی می‌بینی چطور چند query مرتبط را داخل یک transaction ایمن اجرا کنیم.
