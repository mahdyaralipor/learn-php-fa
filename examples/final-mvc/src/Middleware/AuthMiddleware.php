<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Support\Session;

/** مسیرهای محافظت‌شده را فقط برای کاربر واردشده باز می‌کند */
final class AuthMiddleware
{
    public function __invoke(): void
    {
        if (! Session::has('user')) {
            Session::flash('error', 'برای دسترسی باید وارد شوید.');
            header('Location: /login');
            exit;
        }
    }
}
