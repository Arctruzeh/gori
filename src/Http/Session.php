<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Session Class
 * Wraps PHP's $_SESSION superglobal for OOP access.
 */
final class Session
{
    public function __construct()
    {
        if (\session_status() === PHP_SESSION_NONE) {
            \session_start();
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function all(): array
    {
        return $_SESSION;
    }

    public function setFlash(string $key, string $message): void
    {
        $_SESSION['flash'][$key] = $message;
    }

    public function getFlash(string $key): ?string
    {
        if (isset($_SESSION['flash'][$key])) {
            $message = (string) $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
}
