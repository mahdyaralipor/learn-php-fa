<?php

declare(strict_types=1);

namespace App\Support;

/** رندر view با layout و helper خروجی امن */
final class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'layout'): void
    {
        extract($data, EXTR_SKIP);

        ob_start();
        require dirname(__DIR__, 2) . '/views/' . $view . '.php';
        $content = ob_get_clean() ?: '';

        if ($layout === null) {
            echo $content;

            return;
        }

        require dirname(__DIR__, 2) . '/views/' . $layout . '.php';
    }
}
