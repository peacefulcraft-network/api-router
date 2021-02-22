<?php
namespace ncsa\phpmvj\router;

use ncsa\phpmvj\Application;
use ncsa\phpmvj\exceptions\UnrouteableRequestException;
use ncsa\phpmvj\util\Validator;

class Router {
  private static $_routes = [
    'DELETE'=>[],
    'GET'=>[],
    'PATCH'=>[],
    'POST'=>[],
    'PUT'=>[],
    'OPTIONS'=>[],
  ];
    public static function getRoutes():array { return SELF::$_routes; }

  private static $_route = "";
    public static function getRoute():string { return SELF::$_route; }

  private static $_is_preflight = false;
    public static function isPreflight():bool { return SELF::$_is_preflight; }

  private static $_uri = "";
    public static function getUri():string { return SELF::$_uri; }

  private static $_params = [];
    public static function getUriParams():array { return $_GET; }

  private static $_matched_handler = false;
    public static function hasMatchedHandler():bool { return SELF::$_matched_handler; }

  private static $_handler = null;
    public static function getMatchedHandler():?RequestHandler { return SELF::$_handler; }

  public function __construct(){}

  /**
   * @param String HTTP Request method (GET, POST, PUT, OPTIONS, DELETE, etc)
   * @param String $route The route to register the callable to
   * @param String $handler The RouteHandler that would handle the request
   */
  public static function registerRoute(string $method, string $route, string $handler):void {
    $path = explode('/', $route);

    $method = strtoupper($method);
    SELF::_registerRoute($path, SELF::$_routes[$method], $handler);

    // All routes need to have an OPTIONS round as well. If the call didn't already register one, do it automatically for them.
    if ($method !== 'OPTIONS') {
      SELF::_registerRoute($path, SELF::$_routes['OPTIONS'], $handler);
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
    private static function _registerRoute(array &$path, array &$route, string $handler):void {
      $level = strtolower(array_shift($path));
      if (count($path) === 0) {
        if (isset($route['branch'][$level])) {
          $route['branch'][$level]['controller'] = $handler;
        } else {
          $route['branch'][$level] = ['controller'=> $handler];
        }
      } else {
        if (!isset($route['branch'][$level])) {
          $route['branch'][$level] = ['branch'=> []];
        }
        SELF::_registerRoute($path, $route['branch'][$level], $handler);
      }
    }

    /**
     * Resolve the request URI to a RequestHandler
     */
    public static function resolve() {
      SELF::$_uri = strtolower($_SERVER["REQUEST_URI"]);
      $queryParamStart = strpos(SELF::$_uri, '?'); 
      if ($queryParamStart !== false) {
        SELF::$_uri = substr(SELF::$_uri, 0, $queryParamStart);
      } 

      if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        SELF::$_is_preflight = true;
      }

      $path = explode('/', SELF::$_uri);
      
      // Shift off the empty string from a leading forward slash
      if(count($path) > 0 && !Validator::meaningfullyExists($path[0])) {
        array_shift($path);
      }

      $branchResults = ['param_count' => 0, 'controller' => null, 'path' => ''];
      if (isset(SELF::$_routes[$_SERVER['REQUEST_METHOD']])) {
        $match = SELF::_matchRoute($path, SELF::$_routes[$_SERVER['REQUEST_METHOD']], $branchResults);
      } else {
        $match = null;
      }
      
      if ($match  === null) {
        // Return with no matched handler
        return;
      }

      SELF::$_route = $match['path'];
      SELF::_assignURIParameters();

      if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH') {
        SELF::_parseRequestBody();
      }

      SELF::$_handler = new $match['controller'];
      SELF::$_matched_handler = true;
    }
  
      /**
       * Recursevly traverse the $routes associative array to determine the request handler
       * for the given request $path, broken up by '/'s.
       * @param array $path The request URI, explode()d into an array on the '/' characters
       * @param array $routes The route tree or subtree to check for the route
       * @return array An array containing the results of the route resolution
       */
      private static function _matchRoute(array &$paths, array $routes, array &$results):?array {
        $level = array_shift($paths);
        if ($level === null) { return null; }

        /*
          Static route match.
        */
        if(isset($routes['branch'][$level])) {
          if (count($paths) === 0) {
            if (isset($routes['branch'][$level]['controller'])) {
              $results['controller'] = $routes['branch'][$level]['controller'];
              $results['path'] .= '/' . $level;
              return $results;
            }
          } else {
            if (isset($routes['branch'][$level])) {
              $results['path'] .= '/' . $level;
              return SELF::_matchRoute($paths, $routes['branch'][$level], $results);
            }
          }
        }

        /*
          Check for dynamic route with URI parameter 
        */
        $branches = [];
        $pathsCopy = json_decode(json_encode($paths), true);
        
        foreach(@$routes['branch'] as $pathSeg => $branch) {
          // echo "path " . $level. " has controller [" . $branch['controller'] . "] with branches " . print_r($branch['branch'], true) . "<br />";
          if (strpos($pathSeg, ':') === 0) {
            if (count($paths) === 0) {
              if (isset($branch['controller'])) {
                array_push($branches, ['param_count' => $results['param_count'] + 1, 'controller'=> $branch['controller'], 'path'=> $results['path'] . '/' . $pathSeg]);
              }
            } else {
              if (isset($branch['branch'])) {
                $branchResult = ['param_count' => $results['param_count'] + 1, 'path' => $results['path'] . '/' . $pathSeg];
                array_push($branches, SELF::_matchRoute($pathsCopy, $branch, $branchResult));
              }
            }
          }
        }

        if (count($branches) > 0) {
          $results['param_count']++;
          $fewestParams = $branches[0];
          foreach($branches as $branchResult) {
            if ($branch === null) { continue; }

            if (!isset($branchResult['param_count']) || $branchResult['param_count'] < $fewestParams['param_count']) {
              $fewestParams = $branchResult;
            }
          }

          if ($fewestParams === null) { return null; }

          $results['param_count'] += $fewestParams['param_count'];
          $results['path'] = $fewestParams['path'];
          $results['controller'] = $fewestParams['controller'];
          return $results;
        }

        /*
          No matching routes found
        */
        return null;
      }

      /**
       * Resolves URI params in the matched route (/:uriparam/noturiparam)
       */
      private static function _assignURIParameters() {
        $requestUriParts = explode('/', SELF::$_uri);
        $routeParts = explode('/', SELF::$_route);

        $parts = count($routeParts);
        for($i=0; $i<$parts; $i++) {
          if (strpos($routeParts[$i], ":") === 0) {
            $_GET[substr($routeParts[$i], 1)] = $requestUriParts[$i];
          }
        }
      }

      /**
       * Parses the request body and assigns values to $_POST
       * Supports JSON. multipart/form & x-www-form-urlencoded parsed by PhP nativley
       */
      private static function _parseRequestBody() {
        // var_dump(file_get_contents('php://input'));
        if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
          $requestBody = json_decode(file_get_contents('php://input'), true);
          if (json_last_error() === JSON_ERROR_NONE) {
            foreach($requestBody as $key => $value) {
              $_POST[$key] = $value;
            } 
          }
        }
      }
}
?>
