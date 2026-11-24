<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Request Class
 * Captures immutable HTTP request data for OOP access.
 */
final class Request
{
    public function __construct(
        private readonly array $query = [],
        private readonly array $post = [],
        private readonly array $cookies = [],
        private readonly array $server = [],
    ) {
    }

    public static function fromGlobals(): self
    {
        return new self(
            query: $_GET,
            post: $_POST,
            cookies: $_COOKIE,
            server: $_SERVER,
        );
    }

    public function getPost(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    public function getQuery(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    public function getCookie(string $key, mixed $default = null): mixed
    {
        return $this->cookies[$key] ?? $default;
    }

    public function isPost(): bool
    {
        return ($this->server['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    public function setCookie(string $name, string $value, int $expire = 0, string $path = '/'): void
    {
        \setcookie($name, $value, $expire, $path);
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->query[$key] ?? $default;
    }
}
