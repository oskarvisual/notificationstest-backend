<?php

namespace App\Infrastructure\Http;

class JsonResponse extends Response
{
    public function __construct($statusCode, $body)
    {
        parent::setHeader("Content-Type", "application/json");
        parent::setStatusCode($statusCode);
        parent::setBody(json_encode($body));
        parent::send();
    }
}
