<?php
namespace ncsa\phpmcj\router;

use ncsa\phpmcj\util\Validator;

class Router {
	private $_routes = [
		RequestMethod::OTHER=>[],
		RequestMethod::DELETE=>[],
		RequestMethod::GET=>[],
		RequestMethod::PATCH=>[],
		RequestMethod::POST=>[],
		RequestMethod::PUT=>[],
		RequestMethod::OPTIONS=>[],
	];
		public function getRoutes():array { return $this->_routes; }

	public function __construct(){}

	/**
	 * @param method A RequestMethod:: constant
	 * @param route The route to register the callable to
	 * @param handler The RouteHandler that would handle the request
	 */
	public function registerRoute(int $method, string $route, array $middleware, string $handler):void {
		$path = explode('/', $route);
		// Shift off the empty string from a leading forward slash
		if(count($path) > 0 && !Validator::meaningfullyExists($path[0])) {
			array_shift($path);
		}

		// Copy path because _registerRoute takes a pointer and will modify the value it is given. We want to keep the orignal value
		$pathCopy = $path;
		$this->_registerRoute($pathCopy, $this->_routes[$method], $middleware, $handler);

		// All routes need to have an OPTIONS route as well. If the call didn't already register one, do it automatically for them.
		if ($method !== RequestMethod::OPTIONS && $method !== RequestMethod::OTHER) {
			$pathCopy = $path;
			$this->_registerRoute($pathCopy, $this->_routes[RequestMethod::OPTIONS], $middleware, $handler);
		}
	}

	/**
	 * Recursivley builds out an associate array of routes broken up heirarchicaly by their path,
	 * to fully qualified RouteHandler's classpaths that would handle requests to that url.
	 * IE
	 * [
	 *    'branch' => [
	 *        'index' => [
	 *           'controller' => '\ncsa\insights\controllers\index\home',
	 *           'branch' => []
	 *        ],
	 *     ],
	 *     ...
	 * ]
	 */
	private function _registerRoute(array &$path, array &$route, $middleware, string $handler):void {
		$level = strtolower(array_shift($path));
		if (count($path) === 0) {
			if (isset($route['branch'][$level])) {
				$route['branch'][$level]['controller'] = $handler;
				$route['branch'][$level]['middleware'] = $middleware;
			} else {
				$route['branch'][$level] = ['controller'=> $handler, 'middleware'=>$middleware];
			}
		} else {
			if (!isset($route['branch'][$level])) {
				$route['branch'][$level] = ['branch'=> []];
			}
			$this->_registerRoute($path, $route['branch'][$level], $middleware, $handler);
		}
	}

	/**
	 * Resolve the request URI to a RequestHandler
	 */
	public function resolve($uri):Request {
		$request = new Request($uri);
		$is_web_request = isset($_SERVER['REQUEST_METHOD']) && strlen($_SERVER['REQUEST_METHOD']) > 0;
		if ($is_web_request) {
			$request->setEMethod(RequestMethod::valueOf($_SERVER['REQUEST_METHOD']));
		} else {
			$request->setEMethod(RequestMethod::OTHER);
		}

		$queryStartPos = strpos($uri, '?');
		$preservedCaseUri = $uri;
		if ($queryStartPos > 0) {
			$preservedCaseUri = substr($uri, 0, $queryStartPos);
			$uri = strtolower($preservedCaseUri);
		}

		$path = explode('/', $uri);
		
		// Shift off the empty string from a leading forward slash
		if(count($path) > 0 && !Validator::meaningfullyExists($path[0])) {
			array_shift($path);
		}

		// Recusivley traverse router tree to resolve the requested URI
		$branchResults = ['param_count' => 0, 'controller' => null, 'path' => ''];
		if (isset($this->_routes[$request->getEMethod()])) {
			$match = $this->_matchRoute($path, $this->_routes[$request->getEMethod()], $branchResults);
		} else {
			$match = null;
		}
		
		if ($match  === null) {
			// Return with no matched handler
			return $request;
		}

		$request->setUriParameters($this->_resolveDynamicPathSegments($match['path'], $preservedCaseUri));
		$request->setMiddleware($match['middleware']);
		$request->setMatchedHandler($match['controller']);
		$request->setHasMatchedHandler(true);
		return $request;
	}
	
	/**
	 * Recursevly traverse the $routes associative array to determine the request handler
	 * for the given request $path, broken up by '/'s.
	 * @param path The request URI, explode()d into an array on the '/' characters
	 * @param routes The route tree or subtree to check for the route
	 * @return array An array containing the results of the route resolution
	 * @return void When no route is matched
	 */
	private function _matchRoute(array &$paths, array $routes, array &$results):?array {
		$level = array_shift($paths);
		if ($level === null) { return null; }

		/*
			Static route match (no params in url).
			Exact matches are prefered over those with a dynamic parameter
		*/
		if(isset($routes['branch'][$level])) {
			// If there is no more path to check, see if there is a controller here
			if (count($paths) === 0) {
				if (isset($routes['branch'][$level]['controller'])) {
					$results['middleware'] = $routes['branch'][$level]['middleware'];
					$results['controller'] = $routes['branch'][$level]['controller'];
					$results['path'] .= '/' . $level;
					return $results;
				} // else, we continue to dynamic path segment checking
			// Else there is more path, we keep going
			} else {
				if (isset($routes['branch'][$level])) {
					$results['path'] .= '/' . $level;
					return $this->_matchRoute($paths, $routes['branch'][$level], $results);
				}
			}
		}

		/*
			Check for dynamic segment (/:variable) 
		*/

		/*
			Whenever a dynamic parameter is involved, it creates a posibilty of a 'run-off' between routes.
			We prefer routes which require fewer assumptions - assuming that a value is a dynamic parameter.
		*/
		$branches = [];
		$pathsCopy = json_decode(json_encode($paths), true);
		
		foreach(@$routes['branch'] as $pathSeg => $branch) {
			// Check if there is a dynamic segment on this branch
			if (strpos($pathSeg, ':') === 0) {
				// If there is no more path, this should be our stop
				if (count($paths) === 0) {
					// Check for a controller at this level
					if (isset($branch['controller'])) {
						array_push($branches, [
							'param_count' => $results['param_count'] + 1, 
							'controller'=> $branch['controller'], 
							'middleware'=> $branch['middleware'],
							'path'=> $results['path'] . '/' . $pathSeg
						]);
					}
				// Else, there is more path and we keep going
				} else {
					if (isset($branch['branch'])) {
						$branchResult = [
							'param_count' => $results['param_count'] + 1,
							'path' => $results['path'] . '/' . $pathSeg
						];
						array_push($branches, $this->_matchRoute($pathsCopy, $branch, $branchResult));
					}
				}
			}// Else, this is a branch and has already be ruled out above.
		}

		/**
		 * At this point, all recursive calls have returned to this level.
		 * In the case of multiple branch matches, choose the one with the fewest
		 * dynamic segments (/:variable).
		 * 
		 * IE;
		 * Is preferred /exact/matching/route
		 * Over /exact/matching/:untilhere
		 * when /exact/matching/route is requested.
		 * 
		 * IF the requested route was /exact/matching/literally anything else
		 * the branch /exact/matching/route wouldn't have returned as a possibility,
		 * leaving /exact/matching/:untilhere as the only plausable route to select.
		 */
		if (count($branches) > 0) {
			$results['param_count']++;
			$fewestParams = $branches[0];
			foreach($branches as $branchResult) {
				if ($branch === null) { continue; }

				if (!isset($branchResult['param_count']) || $branchResult['param_count'] < $fewestParams['param_count']) {
					$fewestParams = $branchResult;
				}
			}

			// No matching routes were found at this level.
			if ($fewestParams === null) { return null; }

			$results['param_count'] += $fewestParams['param_count'];
			$results['path'] = $fewestParams['path'];
			$results['middleware'] = $fewestParams['middleware'];
			$results['controller'] = $fewestParams['controller'];
			return $results;
		}

		/*
			No matching routes found at this level.
		*/
		return null;
	}

	/**
	 * Resolves URI params in the matched route (/:uriparam/noturiparam)
	 * @param matchedPath The path returned by the router, including dynamic segment indicators
	 * @param uri The request uri to extra dynamic segments from
	 */
	private function _resolveDynamicPathSegments(string $matchedPath, string $uri):array {
		$requestUriParts = explode('/', $uri);
		$routeParts = explode('/', $matchedPath);

		// copy-on-write
		// Gets the query params
		$uriParams = $_GET;

		// Parse stuff out of the uri if there were dynamic path segments
		$parts = count($routeParts);
		for($i=0; $i<$parts; $i++) {
			if (strpos($routeParts[$i], ":") === 0) {
				$uriParams[substr($routeParts[$i], 1)] = $requestUriParts[$i];
			}
		}

		return $uriParams;
	}
}
?>
