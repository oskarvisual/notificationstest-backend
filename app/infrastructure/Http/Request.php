<?php

namespace App\Infrastructure\Http;

class Request
{
    private array $attributes = array();
    private array $query = array();
    private array $request = array();
    private array $cookies = array();
    private array $files = array();
    private array $server = array();
    private array $headers = array();

    public function __construct()
    {
        $this->attributes = &$_REQUEST;
        $this->query = &$_GET;
        $this->request = &$_POST;
        $this->cookies = &$_COOKIE;
        $this->files = &$_FILES;
        $this->server = &$_SERVER;

        $this->headers = $this->getHeaders();
    }

    public function getPath(): string
    {
        $path = $this->server['REQUEST_URI'];
        $basePath = dirname($this->server['SCRIPT_NAME']);

        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }

        if (strpos($path, '?') !== false) {
            $path = substr($path, 0, strpos($path, '?'));
        }

        return '/' . trim($path, '/');
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getUri(): string
    {
        return parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function withAttributes(array $attributes): self
    {
        return new self($this->getMethod(), $this->getUri(), $attributes);
    }

    public function getQuery(string $name)
    {
        return $this->query[$name] ?? null;
    }

    public function getRequests()
    {
        if ($this->getMethod() == 'PUT') {
            parse_str(file_get_contents("php://input"), $_PUT);

            foreach ($_PUT as $key => $value) {
                unset($_PUT[$key]);

                $_PUT[str_replace('amp;', '', $key)] = $value;
            }

            $this->request = array_merge($this->request, $_PUT);
        }
        
        return $this->request ?? null;
    }

    public function getRequest(string $name)
    {
        $this->getRequests();
        return $this->request[$name] ?? null;
    }

    public function getCookie(string $name)
    {
        return $this->cookies[$name] ?? null;
    }

    public function getFile(string $name)
    {
        return $this->files[$name] ?? null;
    }

    public function getServer(string $name)
    {
        return $this->server[$name] ?? null;
    }

    public function getHeader(string $name)
    {
        return $this->headers[$name] ?? null;
    }

    private function getHeaders(): array
    {
        $headers = [];
        foreach ($this->server as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))))] = $value;
            }
        }
        return $headers;
    }
}
