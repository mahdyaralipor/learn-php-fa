<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controller;
use App\Models\Post;

final class PostController extends Controller
{
    public function __construct(private Post $posts)
    {
    }

    public function index(): void
    {
        $this->view('posts/index', [
            'title' => 'همهٔ پست‌ها',
            'posts' => $this->posts->all(),
        ]);
    }

    public function show(string $id): void
    {
        $post = $this->posts->find((int) $id);

        if ($post === null) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'پست پیدا نشد']);

            return;
        }

        $this->view('posts/show', [
            'title' => (string) $post['title'],
            'post' => $post,
        ]);
    }

    public function create(): void
    {
        $this->view('posts/create', [
            'title' => 'پست جدید',
        ]);
    }

    public function store(): void
    {
        $this->requireCsrf();

        $title = trim((string) ($_POST['title'] ?? ''));
        $body = trim((string) ($_POST['body'] ?? ''));

        if ($title === '' || $body === '') {
            \App\Support\Session::flash('error', 'عنوان و متن الزامی است.');
            $this->redirect('/posts/create');
        }

        $user = $this->authUser();
        $id = $this->posts->create($title, $body, isset($user['id']) ? (int) $user['id'] : null);

        \App\Support\Session::flash('success', 'پست با موفقیت ذخیره شد.');
        $this->redirect('/posts/' . $id);
    }
}
