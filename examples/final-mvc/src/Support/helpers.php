<?php

declare(strict_types=1);

use App\Support\View;

/** escape خروجی HTML — در viewها از e() استفاده کنید */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** shorthand برای رندر view */
function view(string $name, array $data = []): void
{
    View::render($name, $data);
}
