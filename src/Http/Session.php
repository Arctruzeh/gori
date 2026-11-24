<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Wraps PHP's $_SESSION superglobal for OOP access with lazy initialization.
 */
final class Session implements SessionInterface
{
    public function get(string $key, mixed $default = null): mixed
    {
        $this->ensureStarted();
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->ensureStarted();
        $_SESSION[$key] = $value;
    }

    public function has(string $key): bool
    {
        $this->ensureStarted();
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void
    {
        $this->ensureStarted();
        unset($_SESSION[$key]);
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        $this->ensureStarted();
        return $_SESSION;
    }

    public function setFlash(string $key, string $message): void
    {
        $this->ensureStarted();
        if (!isset($_SESSION['flash']) || !\is_array($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][$key] = $message;
    }

    public function getFlash(string $key): ?string
    {
        $this->ensureStarted();
        if (isset($_SESSION['flash'][$key])) {
            $message = (string) $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    public function regenerateId(bool $deleteOldSession = true): void
    {
        $this->ensureStarted();
        \session_regenerate_id($deleteOldSession);
    }

    private function ensureStarted(): void
    {
        if (\session_status() === PHP_SESSION_NONE) {
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

            \session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);

            \session_start();
        }
    }
}
