<?php

declare(strict_types=1);

namespace App\Support;

/** محافظت CSRF برای فرم‌های POST */
final class Csrf
{
    private const TOKEN_KEY = '_csrf_token';

    public static function token(): string
    {
        if (! Session::has(self::TOKEN_KEY)) {
            Session::set(self::TOKEN_KEY, bin2hex(random_bytes(32)));
        }

        return (string) Session::get(self::TOKEN_KEY);
    }

    public static function field(): string
    {
        $token = self::token();

        return '<input type="hidden" name="_token" value="' . e($token) . '">';
    }

    public static function verify(?string $token): bool
    {
        $expected = Session::get(self::TOKEN_KEY);

        if (! is_string($expected) || ! is_string($token)) {
            return false;
        }

        return hash_equals($expected, $token);
    }
}
