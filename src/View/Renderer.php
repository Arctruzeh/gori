<?php

declare(strict_types=1);

namespace App\View;

final class Renderer
{
    private readonly string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = \rtrim($basePath, '/') . '/';
    }

    public function render(string $template, array $data = []): string
    {
        \extract($data);
        \ob_start();
        include "{$this->basePath}{$template}.php";
        return (string) \ob_get_clean();
    }

    public function renderWithLayout(string $template, array $data = [], string $layout = 'layouts/base'): string
    {
        $content = $this->render($template, $data);
        $layoutData = \array_merge($data, ['content' => $content]);
        return $this->render($layout, $layoutData);
    }

    public function partial(string $template, array $data = []): string
    {
        return $this->render($template, $data);
    }
}
