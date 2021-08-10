<?php
namespace net\peacefulcraft\apirouter;

use BadMethodCallException;
use Exception;
use net\peacefulcraft\apirouter\api\ApplicationCommandProvider;
use net\peacefulcraft\apirouter\api\ApplicationPlugin;
use net\peacefulcraft\apirouter\api\ApplicationRouteProvider;
use net\peacefulcraft\apirouter\api\ExtensibleApplication;
use net\peacefulcraft\apirouter\console\APIRouterSTDPlugin;
use net\peacefulcraft\apirouter\console\Console;
use net\peacefulcraft\apirouter\spec\router\Controller;
use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\router\RequestMethod;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\router\Router;
use ReflectionClass;
use RuntimeException;

class Application implements ExtensibleApplication {

	private array $_config = [];
		public function getConfig():array { return $this->_config; }

	private ?Router $_router = null;
		public function getRouter():Router { return $this->_router; }

	private ?Request $_request = null;
		public function getRequest():?Request { return $this->_request; }

	private ?Response $_response = null;
		public function getResponse():Response { return $this->_response; }

	private ?Console $_console = null;

	private array $_Plugins = [];
	private APIRouterSTDPlugin $_STDPack;

	public function __construct(array $config) {
		$this->_config = $config;
		$this->_router = new Router();
		$this->_response = new Response();
		$this->_STDPack = new APIRouterSTDPlugin();
		$this->usePlugin($this->_STDPack);
		$this->_response->setHeader('Content-Type', 'application/json');
	}

	public function getActivePlugins(): array {
		return $this->_Plugins;
	}

	/**
	 * Register an Application plugin
	 * 
	 * @return bool True if the plugin enabled without incident
	 * @return bool Fakse if the plugin reported errors during startup.
	 */
	public function usePlugin(ApplicationPlugin $Plugin): void {
		try {
			$Plugin->enablePlugin($this);
			array_push($this->_Plugins, $Plugin);

		} catch (Exception $ex) {
			error_log("API-Router plugin " . get_class($Plugin) . " emitted exception during application boot.");
			error_log($ex->getTraceAsString());
			error_log($ex->getMessage());
		}
	}

	/**
	 * Execute Application as a Request router and handler.
	 * @param $Request Optionally provide a Request object to route and skip route resolution.
	 */
	public function handleRequest(?Request $Request=null) {
		$this->_registerPluginWebRoutes();
		$this->_request = ($Request === null)? $this->_router->resolve($_SERVER['REQUEST_URI']) : $Request;

		if ($this->_request->hasMatchedHandler()) {
			
			// Parse request body
			$this->_request->parseBody(file_get_contents('php://input'));

			// Middleware / body transformations
			foreach($this->_request->getMiddleware() as $middlewareFunction) {
				if (is_string($middlewareFunction)) {
					$func = new ($middlewareFunction);
				} else {
					$func = $middlewareFunction;
				}

				// Middleware must explicity allow request to continue
				if ($func->run($this->_config, $this->_request, $this->_response)) {
					continue;
				
				// Otherwise, stop processing and write out
				} else {
					echo $this->_response;
					ob_flush();
					exit();
				}
			}

			// Check if handler is an FQNS string, or already a Controller object
			if (is_string($this->_request->getMatchedHandler())) {
				// Replace handler string with handler object
				$this->_request->setMatchedHandler(new ($this->_request->getMatchedHandler()));
			}

			// Actually handle the request
			$this->_request->getMatchedHandler()->handle($this->_config, $this->_request, $this->_response);
			echo $this->_response;

		} else {
			// No matched handler, issue a 4040
			$this->_response->setHttpResponseCode(Response::HTTP_NOT_FOUND);
			$this->_response->setErrorMessage('Resource not found');
			echo $this->_response;
		}

		ob_flush();

		foreach($this->_Plugins as $Plugin) {
			try {
				$Plugin->disablePlugin();
			} catch(RuntimeException $ex) {
				error_log("API-Router plugin " . get_class($Plugin) . " emitted exception during application boot.");
				error_log($ex->getTraceAsString());
				error_log($ex->getMessage());
			}
		}
	}

	/**
	 * Reach out to all the registered plugins and ask them to register their routes.
	 */
	private function _registerPluginWebRoutes(): void {
		/**
		 * Anonymous class used to force plugin-registered web routes to use
		 * prefixes to avoid collissions with maintainer and other plugin routes.
		 */
		$routePrefix = '';
		$anonRoutePrefixingClass = new class($this->_router, $routePrefix) extends Router {
			private Router $_wrappedRouter;
			private string $_pathPrefix;

			public function __construct(Router $wrappedRouter, string &$pathPrefix) {
				$this->_wrappedRouter = $wrappedRouter;
				// Avoid re-declaring the class everytime and just replace var value directly so we can re-use the wrapper.
				$this->_pathPrefix = &$pathPrefix;
			}

			public function registerRoute(RequestMethod|string $method, string $path, ?array $middleware, string|Controller $handler) {
				if ($path[0] === '/') {
					$path = $this->_pathPrefix . $path;
				} else {
					$path = $this->_pathPrefix . "/${path}";
				}
				$this->_wrappedRouter->registerRoute($method, $path, $middleware, $handler);
			}

			public function resolve(string $uri): Request {
				throw new BadMethodCallException('Anonymous Router wrapper prohibits access to Router::resolve().');
			}
		};


		/**
		 * Loop through all loaded plugins and ask them to register their web-routes
		 */
		foreach($this->_Plugins as $Plugin) {
			if ($Plugin instanceof ApplicationRouteProvider) {
				$routePrefix = $Plugin->getPrefix();
				if (strlen($routePrefix) === 0) { $routePrefix = strtolower((new ReflectionClass($Plugin))->getShortName()); }
				
				$Plugin->registerRoutes($anonRoutePrefixingClass);
				// var_dump($this->_router);
			}
		}
	}

	/**
	 * Execute Application in CLI mode, loading commands and diagnostic route information.
	 */
	public function launchConsole(): void {
		// Only load commands the first time around.
		if ($this->_console === null) {
			$this->_console = new Console($this->_config);
			$this->_registerCliCommands();
		}
		$this->_console->run();
	}

	/**
	 * Execute Application command in non-interactive mode.
	 */
	public function runConsoleCommand(string $console_command): int {
		// Only load commands the first time around.
		if ($this->_console === null) {
			$this->_console = new Console($this->_config);
			$this->_registerCliCommands();
		}
		return $this->_console->runCommand($console_command);
	}

	/**
	 * Loop through all loaded plugins and ask them to register their CLI commands
	 */
	private function _registerCliCommands(): void {
		/**
		 * Loop through all loaded plugins and ask them to register their CommandProviders
		 */
		foreach($this->_Plugins as $Plugin) {
			if ($Plugin instanceof ApplicationCommandProvider) {
				$Plugin->registerCommands($this->_console);
			}
		}
	}
}

?>