<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Middleware\AuthMiddleware;
use App\Models\Post;
use App\Models\User;
use App\Router;
use PDO;

return static function (Router $router, PDO $pdo): void {
    $auth = new AuthMiddleware();

    $home = new HomeController();
    $posts = new PostController(new Post($pdo));
    $authController = new AuthController(new User($pdo));

    $router->get('/', [$home, 'index']);

    $router->get('/posts', [$posts, 'index']);
    $router->get('/posts/create', [$posts, 'create'], [[$auth, '__invoke']]);
    $router->post('/posts', [$posts, 'store'], [[$auth, '__invoke']]);
    $router->get('/posts/{id}', [$posts, 'show']);

    $router->get('/login', [$authController, 'showLogin']);
    $router->post('/login', [$authController, 'login']);
    $router->post('/logout', [$authController, 'logout'], [[$auth, '__invoke']]);
};
