<?php

namespace App\Infrastructure\Http;

use App\Infrastructure\Http\JsonResponse;

class Router
{
    private $routes;

    public function __construct()
    {
        $this->routes = array();
    }

    public function addRoute(string $method, string $pattern, array $handler): void
    {
        $this->routes[] = array($method, $pattern, $handler);
    }

    public function run(Request $request): JsonResponse
    {
        $method = $request->getMethod();
        $uri = $request->getUri();
        foreach ($this->routes as $route) {
            list($routeMethod, $routePattern, $routeHandler) = $route;
            /*if ($method === $routeMethod && preg_match("#$routePattern#", $uri, $matches)) {
                $request = $request->withAttributes($matches);
                return $routeHandler($request);
            }*/
            if ($method === $routeMethod && BASE_URL . $routePattern == $uri) {
                $request = $request->withAttributes(array($routePattern));
                return $routeHandler($request);
            }
        }
        return new JsonResponse(404, array("error" => "Route not found"));
    }
}
