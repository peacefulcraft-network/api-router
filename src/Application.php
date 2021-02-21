<?php
namespace ncsa\phpmvj;

use ncsa\phpmvj\router\Response;
use \ncsa\phpmvj\router\Router;

class Application {

  private static $_config = null;
    public static function getConfig():array { return SELF::$_config; }

  private static $_router;
		public static function getRouter():Router { return SELF::$_router; }

  private static $_response;
    public static function getResponse():Response { return SELF::$_response; }

  public function __construct(array $config) {
    SELF::$_config = $config;
		SELF::$_router = new Router();
    SELF::$_response = new Response();
  }

  public function handle() {
    global $config;
    Router::resolve();

    session_start();

    if (Router::hasMatchedHandler()) {
      // Set CORS headers
      Router::getMatchedHandler()->options();
      if (Router::isPreflight()) {
        header('Access-Control-Allow-Credentials: ', true);
        header('Access-Control-Max-Age: ' . $config['cors']['max-age']);
      } else {
        // If not preflight (HTTP/OPTIONS), then send actual content too
        Router::getMatchedHandler()->handle();
        http_response_code(SELF::$_response->getHttpResponseCode());
        echo json_encode(SELF::$_response);
      }
    } else {
      SELF::$_response->setHttpResponseCode(Response::HTTP_NOT_FOUND);
      SELF::$_response->setErrorMessage('Resource not found');
      echo json_encode(SELF::$_response);
    }

    ob_flush();
  }
}
