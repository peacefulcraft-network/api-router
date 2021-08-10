<?php namespace net\peacefulcraft\apirouter\spec\router;

use net\peacefulcraft\apirouter\router\Controller;

interface Router {

	public function registerRoute(string $route, array $middleware, string|Controller $controller): void;

	public function registerBodyParser(string $content_type, string|BodyParser $Parser): void;

	public function resolve(string $uri): ?Request;
}

?>