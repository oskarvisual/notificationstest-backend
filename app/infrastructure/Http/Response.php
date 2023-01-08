<?php

namespace App\Infrastructure\Http;

abstract class Response
{
    protected $headers = array();
    protected $statusCode = 200;
    protected $body;

    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function send()
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->body;
    }
}
