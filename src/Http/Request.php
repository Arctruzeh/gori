<?php

namespace App\Http;

/**
 * Request Class
 * Wraps PHP's $_POST, $_GET, $_SERVER and $_COOKIE for OOP access.
 */
class Request
{
    public function getPost(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    public function getQuery(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public function getCookie(string $key, $default = null)
    {
        return $_COOKIE[$key] ?? $default;
    }

    public function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function setCookie(string $name, $value, int $expire = 0, string $path = "/"): void
    {
        \setcookie($name, $value, $expire, $path);
    }
    public function input(string $key, $default = null)
    {
        return $_REQUEST[$key] ?? $default;
    }
}
