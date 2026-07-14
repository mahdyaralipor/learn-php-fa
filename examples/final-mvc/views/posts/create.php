<h1><?= e($title) ?></h1>

<form action="/posts" method="post">
    <?= \App\Support\Csrf::field() ?>

    <label for="title">عنوان</label>
    <input type="text" id="title" name="title" required maxlength="200">

    <label for="body">متن</label>
    <textarea id="body" name="body" rows="8" required></textarea>

    <p style="margin-top:1rem;">
        <button type="submit">ذخیره</button>
        <a href="/posts">انصراف</a>
    </p>
</form>
