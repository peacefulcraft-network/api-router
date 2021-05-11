<?php
namespace net\peacefulcraft\apirouter\router;

class Router {
	private RoutingTreeNode $_routes;

	public function __construct() {
		$this->_routes = new RoutingTreeNode(false, '');
	}

	/**
	 * Register a route under the given method, at the given path, with the given middleware, served by the given controller
	 */
	public function registerRoute(RequestMethod|string $method, string $path, ?array $middleware, string|Controller $handler) {
		// unpack enum
		if ($method instanceof $method) {
			$method = $method->_value;
		}

		if (array_key_exists($method, $this->_routes->getChildren())) {
			$parent = $this->_routes->getChildren()[$method];
		} else {
			$parent = new RoutingTreeNode(false, $method);
			$this->_routes->addChild($parent);
		}

		// Shift off empty string from leading forward-slash for consistency, unless this is the path '/' or ''
		$path = explode('/', $path);
		if (count($path) > 1 && $path[0] === '') {
			array_shift($path);
		}

		// Transform middleware to empty array on null
		if ($middleware === null) {
			$middleware = array();
		}

		$this->_registerRoute($parent, $path, $middleware, $handler);
	}
	private function _registerRoute(RoutingTreeNode $parent, array &$path, array $middleware, string|Controller $handler): void {
		// If no more path, the current level is where this handler should reside
		if (count($path) === 0) {
			$parent->setMiddleware($middleware);
			$parent->setController($handler);

		// More path; we must go deeper
		} else {
			$segment = strtolower(array_shift($path));
			$type = (strpos($segment, ':', 0) === 0)? 'Parameter' : '';
			$num_assumptions = ($type === 'Parameter')? $parent->numAssumptionsRequired() + 1 : $parent->numAssumptionsRequired();

			// getChildren(): array || getParameterChildren(): array
			$target = $parent->{"get{$type}Children"}();
			if (array_key_exists($segment, $target)) {
				$child = $target[$segment];
			} else {
				$child = new RoutingTreeNode((bool)$type, $segment, $num_assumptions);
				// addChild() || addParameterChild()
				$parent->{"add{$type}Child"}($child);
			}

			$this->_registerRoute($child, $path, $middleware, $handler);
		}
	}

	/**
	 * Resolve the given URI into a Request with populated URI parameters, registered middleware,
	 * and the controller responsible for this route, assuming a route has been registred which matches the $URI 
	 */
	public function resolve(string $uri) : Request {
		$Request = new Request($uri);
		$is_web_request = isset($_SERVER['REQUEST_METHOD']) && strlen($_SERVER['REQUEST_METHOD']) > 0;
		if ($is_web_request) {
			$Request->setEMethod(new RequestMethod(strtolower($_SERVER['REQUEST_METHOD'])));
		} else {
			$Request->setEMethod(new RequestMethod(RequestMethod::OTHER));
		}

		$queryStartPos = strpos($uri, '?');
		$preservedCaseUri = $uri;
		if ($queryStartPos > 0) {
			$preservedCaseUri = substr($uri, 0, $queryStartPos);
			$uri = strtolower($preservedCaseUri);
		}

		if (array_key_exists($Request->getEMethod()->_value, $this->_routes->getChildren())) {
			$path = explode('/', $uri);
			array_shift($path);
			$match = $this->_resolveRoute($path, $this->_routes->getChildren()[$Request->getEMethod()->_value]);

			// Return if no matched route
			if ($match === null) {
				return $Request;
			}

			// set uri parameters
			$preservedCasePath = explode('/', $preservedCaseUri);
			$Request->setUriParameters($this->_resolveParameterSegments($match, $preservedCasePath));
			$Request->setMiddleware($match->getMiddleware());
			$Request->setMatchedHandler($match->getController());
		}

		return $Request;
	}
	private function _resolveRoute(array &$path, RoutingTreeNode $parent): ?RoutingTreeNode {
		$segment = array_shift($path);
		if ($segment === null) {
			if ($parent->getController() === null) {
				return null;
			}
			return $parent;
		}

		$static_options = $parent->getChildren();
		// Prefer a static segment match
		if (array_key_exists($segment, $static_options)) {
			return $this->_resolveRoute($path, $static_options[$segment]);

		// If no static segment match, consider all parameters paths
		} else {
			$parameterized_options = $parent->getParameterChildren();
			$parameterized_returns = array();
			foreach($parameterized_options as $child) {
				$pathCopy = $path;
				array_push($parameterized_returns, $this->_resolveRoute($pathCopy, $child));
			}

			// Choose the parameter route with the fewest assumed parameter segments
			$route = null;
			foreach($parameterized_returns as $option) {
				if ($option === null) { continue; }
			
				// If this route requires fewer assumed parameters, prefer it
				if (
					$route === null
					|| $route->numAssumptionsRequired() < $option->numAssumptionsRequired()
				) { $route = $option; }
			}

			return $route;
		}
	}

	private function _resolveParameterSegments(RoutingTreeNode $leaf, array $path): array {
		$parameters = $_GET;
		$i = count($path) - 1;
		while ($leaf !== null) {
			if ($leaf->isParamter()) {
				$parameters[substr($leaf->getSegment(), 1)] = $path[$i];
			}
			$i--;
			$leaf = $leaf->getParent();
		}

		return $parameters;
	}
}