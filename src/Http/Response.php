<?php

namespace App\Http;

class Response
{
    private $content;
    private $statusCode;
    private $headers;

    public function __construct($content = '', $statusCode = 200, $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function send()
    {
        \http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            \header("$name: $value");
        }

        echo $this->content;
    }

    public static function redirect($url): self
    {
        return new self('', 302, ['Location' => $url]);
    }
}
