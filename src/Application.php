<?php
namespace ncsa\phpmvj;

use \ncsa\phpmvj\router\Router;

class Application {

  private static $_config = null;
    public static function getConfig():array { return SELF::$_config; }
			public static function getDefaultCORSController() { return @SELF::$_config['cors']['default_controller']; }

  private static $_router;
		public static function getRouter():Router { return SELF::$_router; }

  public function __construct(array $config) {
    SELF::$_config = $config;
		SELF::$_router = new Router();
  }

  public function handle() {
    Router::resolve();

    session_start();

    if (Router::hasMatchedHandler()) {
      echo json_encode(Router::getMatchedHandler()->handle());
    } else {
    }
  }
}
