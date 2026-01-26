<?php

namespace App\View;

class Renderer
{
    private $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/') . '/';
    }

    public function render(string $template, array $data = []): string
    {
        extract($data);
        ob_start();
        include $this->basePath . $template . '.php';
        return ob_get_clean();
    }
}
