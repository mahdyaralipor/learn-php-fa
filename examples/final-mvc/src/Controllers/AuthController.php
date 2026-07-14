<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controller;
use App\Models\User;
use App\Support\Session;

final class AuthController extends Controller
{
    public function __construct(private User $users)
    {
    }

    public function showLogin(): void
    {
        if ($this->authUser() !== null) {
            $this->redirect('/');
        }

        $this->view('auth/login', [
            'title' => 'ورود',
        ]);
    }

    public function login(): void
    {
        $this->requireCsrf();

        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $user = $this->users->verify($username, $password);

        if ($user === null) {
            Session::flash('error', 'نام کاربری یا رمز عبور اشتباه است.');
            $this->redirect('/login');
        }

        Session::regenerate();
        Session::set('user', [
            'id' => (int) $user['id'],
            'username' => (string) $user['username'],
        ]);

        Session::flash('success', 'خوش آمدید!');
        $this->redirect('/posts');
    }

    public function logout(): void
    {
        $this->requireCsrf();

        Session::remove('user');
        Session::flash('success', 'با موفقیت خارج شدید.');
        $this->redirect('/');
    }
}
