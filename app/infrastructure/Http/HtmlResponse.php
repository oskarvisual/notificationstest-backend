<?php

namespace App\Infrastructure\Http;

class HtmlResponse extends Response
{
    public function __construct($statusCode, $body)
    {
        parent::setHeader("Content-Type", "text/html");
        parent::setStatusCode($statusCode);
        parent::setBody($body);
    }
}
