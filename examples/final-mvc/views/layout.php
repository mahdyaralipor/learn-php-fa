<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'بلاگ MVC') ?></title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 720px; margin: 2rem auto; padding: 0 1rem; line-height: 1.6; }
        nav { margin-bottom: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }
        nav a { color: #2563eb; text-decoration: none; }
        nav a:hover { text-decoration: underline; }
        .flash { padding: .75rem 1rem; border-radius: .5rem; margin-bottom: 1rem; }
        .flash-success { background: #dcfce7; color: #166534; }
        .flash-error { background: #fee2e2; color: #991b1b; }
        label { display: block; margin-top: .75rem; }
        input[type=text], input[type=password], textarea { width: 100%; padding: .5rem; margin-top: .25rem; box-sizing: border-box; }
        button, .btn { display: inline-block; padding: .5rem 1rem; background: #2563eb; color: #fff; border: none; border-radius: .375rem; cursor: pointer; text-decoration: none; }
        button:hover, .btn:hover { background: #1d4ed8; }
        article { border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1rem; }
        .meta { color: #6b7280; font-size: .875rem; }
    </style>
</head>
<body>
    <nav>
        <a href="/">خانه</a>
        <a href="/posts">پست‌ها</a>
        <?php if (\App\Support\Session::has('user')): ?>
            <a href="/posts/create">پست جدید</a>
            <form action="/logout" method="post" style="display:inline;margin:0;">
                <?= \App\Support\Csrf::field() ?>
                <button type="submit">خروج (<?= e(\App\Support\Session::get('user')['username'] ?? '') ?>)</button>
            </form>
        <?php else: ?>
            <a href="/login">ورود</a>
        <?php endif; ?>
    </nav>

    <?php if ($msg = \App\Support\Session::flash('success')): ?>
        <div class="flash flash-success"><?= e($msg) ?></div>
    <?php endif; ?>

    <?php if ($msg = \App\Support\Session::flash('error')): ?>
        <div class="flash flash-error"><?= e($msg) ?></div>
    <?php endif; ?>

    <?= $content ?>
</body>
</html>
