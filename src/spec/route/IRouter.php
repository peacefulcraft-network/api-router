<?php namespace net\peacefulcraft\apirouter\spec\route;

use net\peacefulcraft\apirouter\router\RequestMethod;
use net\peacefulcraft\apirouter\spec\route\Controller;

interface IRouter {

	/**
	 * Register a route for the given, middleware, and handler.
	 * 
	 * @param RequestMethod|string $method The HTTP Method this Route should be registered under.
	 * @param string $route Path with any positional :urlparms.
	 * @param array $middleware array with middleware ::class strings, or class instances.
	 * @param string|Controller $controller Controller ::class string, or instance.
	 */
	public function registerRoute(RequestMethod|string $method, string $route, array $middleware, string|Controller $controller): void;

	/**
	 * Match the given uri to a routable Request, or null if no route is registered to handle the given path.
	 * 
	 * @param string $path Path to route
	 * @param return Request object with matched middleware/Controller pairing, or null if no handler is registered for the given path.
	 */
	public function resolve(string $path): ?IRequest;
}

?>