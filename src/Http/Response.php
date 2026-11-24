<?php

declare(strict_types=1);

namespace App\Http;

final class Response
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        private readonly string $content = '',
        private readonly int $statusCode = 200,
        private readonly array $headers = [],
    ) {
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function content(): string
    {
        return $this->content;
    }

    /**
     * @return array<string, string>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    public function header(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    public function send(): void
    {
        \http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            \header("{$name}: {$value}");
        }

        echo $this->content;
    }

    public static function redirect(string $url): self
    {
        return new self('', 302, ['Location' => $url]);
    }
}
