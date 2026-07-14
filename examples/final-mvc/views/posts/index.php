<h1><?= e($title) ?></h1>

<?php if (empty($posts)): ?>
    <p>هنوز پستی ثبت نشده.</p>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <article>
            <h2><a href="/posts/<?= e((string) $post['id']) ?>"><?= e($post['title']) ?></a></h2>
            <p class="meta">
                <?= e($post['author'] ?? 'ناشناس') ?>
                · <?= e($post['created_at']) ?>
            </p>
            <p><?= e(mb_strimwidth($post['body'], 0, 120, '…')) ?></p>
        </article>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (\App\Support\Session::has('user')): ?>
    <p><a class="btn" href="/posts/create">پست جدید</a></p>
<?php endif; ?>
