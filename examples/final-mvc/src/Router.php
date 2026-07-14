<?php

declare(strict_types=1);

namespace App;

/** Router ساده با پارامتر مسیر و middleware */
final class Router
{
    /** @var list<array{method: string, path: string, handler: callable|array, middleware: list<callable>}> */
    private array $routes = [];

    public function get(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->map('GET', $path, $handler, $middleware);
    }

    public function post(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->map('POST', $path, $handler, $middleware);
    }

    public function map(string $method, string $path, callable|array $handler, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        $match = $this->match(strtoupper($method), $path);

        if ($match === null) {
            http_response_code(404);
            view('errors/404', ['title' => 'صفحه پیدا نشد']);

            return;
        }

        foreach ($match['middleware'] as $middleware) {
            $middleware();
        }

        $this->invoke($match['handler'], $match['params']);
    }

    /** @return array{handler: callable|array, params: array<string, string>, middleware: list<callable>}|null */
    private function match(string $method, string $path): ?array
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (! preg_match($pattern, $path, $matches)) {
                continue;
            }

            $params = array_filter(
                $matches,
                static fn ($key) => is_string($key),
                ARRAY_FILTER_USE_KEY
            );

            return [
                'handler' => $route['handler'],
                'params' => $params,
                'middleware' => $route['middleware'],
            ];
        }

        return null;
    }

    /** @param callable|array{class-string|object, string} $handler */
    private function invoke(callable|array $handler, array $params): void
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $instance = is_object($class) ? $class : new $class();
            $handler = [$instance, $method];
        }

        $handler(...array_values($params));
    }
}
