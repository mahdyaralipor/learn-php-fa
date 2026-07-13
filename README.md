# آموزش PHP از صفر (learn-php-fa)

سایت مستندات این مسیر یادگیری با Hugo و تم hugo-book ساخته شده.

سایت زنده: https://mahdyaralipor.github.io/learn-php-fa/

## راه‌اندازی روی گیت‌هاب پیجز

1. ریپو `learn-php-fa` رو بساز و این پوشه رو پوش کن (شامل پوشه‌ی مخفی `.github`).
2. تو تنظیمات ریپو → Settings → Pages → بخش **Source** رو بذار روی **GitHub Actions** (نه Deploy from a branch).
3. برو تب **Actions**، workflow به اسم "Deploy Hugo site to Pages" باید خودکار اجرا بشه.
4. بعد از اتمام (چند دقیقه)، سایت روی آدرس بالا در دسترسه.

نسخه‌ی Hugo (0.158.0) داخل خود workflow (`.github/workflows/hugo.yml`) پین شده، پس همیشه دقیقاً همون نسخه‌ای که لوکال تست شده استفاده می‌شه — مشکل ناسازگاری تم پیش نمیاد.

## اضافه کردن درس جدید

فایل جدید تو `content/lessons/` بساز، مثلاً `02-variables.md`:

```markdown
---
title: "۲. متغیرها"
weight: 3
---

محتوا...
```

بعد کامیت و پوش کن؛ سایت خودکار آپدیت می‌شه.
