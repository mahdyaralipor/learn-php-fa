<h1><?= e($title) ?></h1>

<form action="/login" method="post">
    <?= \App\Support\Csrf::field() ?>

    <label for="username">نام کاربری</label>
    <input type="text" id="username" name="username" required autocomplete="username">

    <label for="password">رمز عبور</label>
    <input type="password" id="password" name="password" required autocomplete="current-password">

    <p style="margin-top:1rem;">
        <button type="submit">ورود</button>
    </p>
</form>

<p class="meta">کاربر پیش‌فرض: <code>admin</code> / <code>secret</code></p>
