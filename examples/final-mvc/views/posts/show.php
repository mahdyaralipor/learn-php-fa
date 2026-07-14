<p><a href="/posts">← بازگشت به لیست</a></p>

<article>
    <h1><?= e($post['title']) ?></h1>
    <p class="meta">
        <?= e($post['author'] ?? 'ناشناس') ?>
        · <?= e($post['created_at']) ?>
    </p>
    <div><?= nl2br(e($post['body'])) ?></div>
</article>
