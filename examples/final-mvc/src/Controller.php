<?php

declare(strict_types=1);

namespace App;

use App\Support\Csrf;
use App\Support\Session;
use App\Support\View;

/** کلاس پایه controllerها */
abstract class Controller
{
    protected function view(string $name, array $data = []): void
    {
        View::render($name, $data);
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . $path);
        exit;
    }

    protected function requireCsrf(): void
    {
        $token = $_POST['_token'] ?? null;

        if (! Csrf::verify(is_string($token) ? $token : null)) {
            http_response_code(419);
            Session::flash('error', 'توکن CSRF نامعتبر است.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

    protected function authUser(): ?array
    {
        $user = Session::get('user');

        return is_array($user) ? $user : null;
    }
}
