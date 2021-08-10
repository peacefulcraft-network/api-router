<?php namespace net\peacefulcraft\apirouter\spec\router;

use net\peacefulcraft\apirouter\router\Controller;

interface Router {

	/**
	 * Register a route for the given, middleware, and handler.
	 * 
	 * @param string $route Path with any positional :urlparms.
	 * @param array $middleware array with middleware ::class strings, or class instances.
	 * @param string|Controller $controller Controller ::class string, or instance.
	 */
	public function registerRoute(string $route, array $middleware, string|Controller $controller): void;

	/**
	 * Register a body parser for the given $content_type
	 * @param string $content_type Content-Type this $Parser will handle
	 * @param string ::class string for the BodyParser class, or an instance of the Parser.
	 */
	public function registerBodyParser(string $content_type, string|BodyParser $Parser): void;

	/**
	 * Match the given uri to a routable Request, or null if no route is registered to handle the given path.
	 * 
	 * @param string $path Path to route
	 * @param return Request object with matched middleware/Controller pairing, or null if no handler is registered for the given path.
	 */
	public function resolve(string $path): ?Request;
}

?>