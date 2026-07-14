---
title: مبانی SQL
weight: 1
---

# مبانی SQL

قبل از این‌که PDO را یاد بگیری، باید زبان پایهٔ صحبت با دیتابیس را بشناسی: SQL.

چهار دستور اصلی که هر روز با آن‌ها سروکار داری:

- `SELECT`
- `INSERT`
- `UPDATE`
- `DELETE`

## `SELECT`

برای خواندن داده:

```sql
SELECT id, name, email
FROM users;
```

اگر `WHERE` نگذاری، معمولاً همهٔ ردیف‌ها را می‌گیری.

## `WHERE`

برای محدود کردن نتیجه:

```sql
SELECT id, name, email
FROM users
WHERE id = 10;
```

یا:

```sql
SELECT id, name, email
FROM users
WHERE email = 'ali@example.com';
```

در کد واقعی، این مقدارها را با prepared statement می‌فرستیم، نه با چسباندن رشته.

## `INSERT`

برای ساخت ردیف جدید:

```sql
INSERT INTO users (name, email)
VALUES ('Ali', 'ali@example.com');
```

## `UPDATE`

برای تغییر:

```sql
UPDATE users
SET name = 'Ali Rezaei'
WHERE id = 10;
```

## `DELETE`

برای حذف:

```sql
DELETE FROM users
WHERE id = 10;
```

## چرا `WHERE` مهم است؟

اگر در `UPDATE` یا `DELETE` شرط نگذاری، ممکن است همهٔ ردیف‌ها تغییر یا حذف شوند. این از آن اشتباه‌های دردناک و واقعی است.

## `JOIN` به‌صورت خیلی کوتاه

وقتی داده در چند جدول پخش شده، `JOIN` آن‌ها را به هم وصل می‌کند.

مثلاً:

```sql
SELECT posts.title, users.name
FROM posts
JOIN users ON users.id = posts.user_id;
```

اینجا عنوان پست و نام نویسنده با هم برمی‌گردند.

## انتخاب ستون بهتر از `SELECT *`

در آموزش `SELECT *` زیاد می‌بینی، اما در کد واقعی معمولاً بهتر است ستون‌های موردنیاز را صریح بنویسی:

```sql
SELECT id, email
FROM users
WHERE id = 10;
```

این کار هم شفاف‌تر است، هم گاهی کارآمدتر.

## جمع‌بندی

در این درس با واژگان پایهٔ SQL آشنا شدی. برای فعلاً کافی است بدانی:

- `SELECT` برای خواندن
- `INSERT` برای ساختن
- `UPDATE` برای تغییر
- `DELETE` برای حذف
- `WHERE` برای محدود کردن
- `JOIN` برای اتصال جدول‌ها

در درس بعدی همین مفاهیم را با PDO وارد PHP می‌کنیم.
