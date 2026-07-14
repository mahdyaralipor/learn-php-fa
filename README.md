# آموزش PHP از صفر تا پیشرفته (learn-php-fa)

دورهٔ فارسی کامل PHP — از مبانی تا MVC، API، تست و دیپلوی — با Hugo و تم hugo-book.

سایت زنده: https://mahdyaralipor.github.io/learn-php-fa/

## ساختار محتوا

```
content/lessons/
  01-fundamentals/ … 15-deployment/   # ۱۵ فصل تو در تو
  projects/                           # ۶ پروژهٔ عملی
examples/final-mvc/                   # اسکلت اپ MVC قابل‌اجرا
```

هر درس: **مفهوم → کد → اشتباهات رایج → تمرین**.

## راه‌اندازی سایت مستندات

1. ریپو را پوش کن (شامل `.github`).
2. Settings → Pages → Source = **GitHub Actions**.
3. workflow «Deploy Hugo site to Pages» سایت را منتشر می‌کند.

نسخهٔ Hugo در `.github/workflows/hugo.yml` پین شده است.

### پیش‌نمایش لوکال

```bash
hugo server -D
```

## افزودن درس جدید

داخل فصل مربوطه، مثلاً `content/lessons/03-functions/06-new-topic.md`:

```markdown
---
title: "موضوع جدید"
weight: 6
---

محتوا...
```

## پروژهٔ نهایی MVC

```bash
cd examples/final-mvc
composer install
php -S localhost:8000 -t public
```

ورود پیش‌فرض: `admin` / `secret` (ببین `README.md` همان پوشه).
