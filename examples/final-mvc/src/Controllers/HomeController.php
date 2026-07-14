<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home', [
            'title' => 'خانه',
        ]);
    }
}
