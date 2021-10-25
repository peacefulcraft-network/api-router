<?php
namespace net\peacefulcraft\apirouter;

use Exception;
use JsonSerializable;
use net\peacefulcraft\apirouter\exceptions\HTTPSemanticRuntimeException;
use net\peacefulcraft\apirouter\exceptions\RenderingEngineException;
use net\peacefulcraft\apirouter\render\JsonSerializableRenderingEngine;
use net\peacefulcraft\apirouter\render\PlainTextRenderingEngine;
use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\router\Router;
use net\peacefulcraft\apirouter\spec\application\WebApplication;
use net\peacefulcraft\apirouter\spec\application\WebLifecycleHook;
use net\peacefulcraft\apirouter\spec\exception\HTTPReportableException;
use net\peacefulcraft\apirouter\spec\ext\WebApplicationPlugin;
use net\peacefulcraft\apirouter\spec\route\IRequest;
use net\peacefulcraft\apirouter\spec\route\IResponse;
use net\peacefulcraft\apirouter\spec\route\IRouter;
use RuntimeException;

class Application implements WebApplication {

	private array $_plugins = [];

	private array $_lifecycleHooks = [];

	private array $_config = [];
		public function getConfig(): array { return $this->_config; }

	private ?IRouter $_router = null;
		public function getRouter(): IRouter { return $this->_router; }

	private ?IRequest $_request = null;
		public function getRequest(): ?Request { return $this->_request; }

	private ?IResponse $_response = null;
		public function getResponse(): IResponse { return $this->_response; }

	/**
	 * @param string Content of 'Accept' header. Framework will attempt to
	 *               honor this output if it is supported for returning
	 *               internal errors.
	 */
	private string $_acceptContentType;

	public function __construct(array $config) {
		$this->_config = $config;
		$this->_router = new Router();

		$this->_extractDefaults();
	}

	/**
	 * Setup computed, internal framework defaults
	 */
	private function _extractDefaults(): void {
		$this->_acceptContentType = 'application/json';

		if (array_key_exists('Accept', $_SERVER)) {
			switch($acceptContentType = strtolower($_SERVER['Accept'])) {
				case 'text/html':
					$this->_acceptContentType = $acceptContentType;
				
				break; case 'text/plain':
					$this->_acceptContentType = $acceptContentType;

				break;
			}
		}
	}

	public function getActivePlugins(): array {
		return $this->_plugins;
	}

	/**
	 * Register an Application plugin
	 * 
	 * @return bool True if the plugin enabled without incident
	 * @return bool Fakse if the plugin reported errors during startup.
	 */
	public function usePlugin(WebApplicationPlugin $Plugin): void {
		try {
			$Plugin->startUp($this);

			array_push($this->_plugins, $Plugin);

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
	public function handleRequest(?IRequest $Request=null): void {
		try {
			$this->runApplicationLifecycleHooks(WebLifecycleHook::BEFORE_REQUEST_ROUTE);
			$this->_request = ($Request === null)? $this->_router->resolve($_SERVER['REQUEST_URI']) : $Request;

			// Check that we matched a route
			if ($this->_request !== null) {
				
				// Parse request body, fallback to PHP native parsing if no Content-Type header is sent.
				if (array_key_exists('CONTENT_TYPE', $_SERVER)) {
					$this->_request->setBody($this->parseRequestBody($_SERVER['CONTENT_TYPE'], file_get_contents('php://input')));
				} else {
					$this->_request->setBody($_POST);
				}

				$this->runApplicationLifecycleHooks(WebLifecycleHook::BEFORE_MIDDLEWARE_EXEC);
				if (($this->_response = $this->runMiddleware()) instanceof IResponse) {
					$this->respondToRequest($this->_response);
					$this->runApplicationShutdown();
					exit();
				}

				// Actually handle the request
				$this->runApplicationLifecycleHooks(WebLifecycleHook::BEFORE_CONTROLLER_EXEC);
				$this->_response = $this->_request->getController()->handle($this->_config, $this->_request);
				$this->respondToRequest($this->_response);
				$this->runApplicationShutdown();

			} else {
				// Respond with 404
				$this->respondToRequestWithException(
					new HTTPSemanticRuntimeException(IResponse::HTTP_NOT_FOUND, IResponse::HTTP_NOT_FOUND,
						'Unable to route ' . $_SERVER['REQUEST_URI'],
						'Resouce not found.'
					)
				);
			}

		/*
			Handle generic Exception where we can extract few details.
		 */
		} catch (Exception $Ex) {
			$SemanticException = new HTTPSemanticRuntimeException($Ex->getCode(), IResponse::HTTP_INTERNAL_ERROR, $Ex->getMessage(), 'An internal server error occured. Please contact support.', $Ex);
			$this->_response = new Response($SemanticException->getHTTPResponseCode(), []);
			$this->_response = new Response($SemanticException->getHTTPResponseCode(), []);
			$this->respondToRequestWithException($SemanticException);

		/*
			Handle detailed, semantic Exception with reportable values.
		*/
		} catch (HTTPReportableException $SemanticException) {
			$this->_response = new Response($SemanticException->getHTTPResponseCode(), []);
			$this->respondToRequestWithException($SemanticException);
		}
		
		$this->runApplicationShutdown();
	}

	/**
	 * Takes the given $input and parses according to the mime type in $type
	 * 
	 * @param string $type MimeType of the content in $input
	 * @param string $input Request body to parse.
	 * 
	 * @throws HTTPSemanticRuntimeException Parsing error occured. Malformed body or mime type does not match $input format.
	 */
	protected function parseRequestBody(string $type, string $input): array {
		switch(strtolower(($type))) {
			case 'application/json':
				$body = json_decode($input, true);
				if ($body === false) {
					throw new HTTPSemanticRuntimeException(IResponse::HTTP_BAD_REQUEST, IResponse::HTTP_BAD_REQUEST, 'mime type indicated request body of type application/json, but input was not JSON parsable. ' . json_last_error_msg(), 'mime type indicated request body of type application/json, but input was not JSON parsable.');
				}

				return $body;

			// No explicit parsing required or implemented. Fallback to PHP native parsing.
			break; default:
				return $_POST;
			break;
		}
	}

	/**
	 * Execute request middleware
	 * 
	 * @return bool Ffalse Continue Request processing.
	 * @return bool True Halt processing.
	 */
	protected function runMiddleware(): ?IResponse {
		// Middleware / body transformations
		foreach($this->_request->getMiddleware() as $middlewareFunction) {
			if (is_string($middlewareFunction)) {
				$func = new ($middlewareFunction);
			} else {
				$func = $middlewareFunction;
			}

			$Response = $func->run($this->_config, $this->_request);

			// Check if middleware is overriding Response
			if ($Response instanceof IResponse) {
				return $Response;
			}
		}

		return null;
	}

	/**
	 * Best-effort attaches a RenderEngine to the Response that will output according to
	 * the 'Accept' header on the request. If an unknown or unsupported 'Accept' value
	 * is received, the framework will default to JSON output or text if the Response
	 * does not appear to be JSON serializable.
	 * 
	 * @param Exception $Exception The thrown Exception to respond with.
	 */
	protected function respondToRequestWithException(HTTPReportableException $Exception): void {
		$this->_response = new Response($Exception->getHTTPResponseCode(), []);

		switch($this->_acceptContentType) {
			case 'text/html':
				$text = "<h2>Exception: " . get_class($Exception) . "</h2>";
				$text .= "<i>Something went wrong while handling your request.</i><br/>";
				$text .= "<p><b>Code:</b> " . $Exception->getCode() . "</p>";
				$text .= "<p><b>Message:</b> " . $Exception->getHTTPResponseErrorMessage() . "</p>";
				$RenderEngine = new PlainTextRenderingEngine($text);

			break; case 'text/plain':
				$text = "Exception: " . get_class($Exception) . PHP_EOL;
				$text .= "Something went wrong while handling your request." . PHP_EOL;
				$text .= "Code: " . $Exception->getCode() . PHP_EOL;
				$text .= "Message: " . $Exception->getHTTPResponseErrorMessage() . PHP_EOL;
				$RenderEngine = new PlainTextRenderingEngine($text);

			// Default JSON
			break; default:
				$jsonSerializableException = $Exception;
				if (!($Exception instanceof JsonSerializable)) {
					$jsonSerializableException = [
						"error_no" => $Exception->getCode(),
						"error_message" => $Exception->getHTTPResponseErrorMessage()
					];
				}

				$RenderEngine = new JsonSerializableRenderingEngine($jsonSerializableException);

			break;
		}

		$this->_response->setRenderEngine($RenderEngine);
		try {
			$output = $RenderEngine->render($this->_response);
			foreach ($this->_response->getResponseHeaders() as $header => $value) {
				header("${header}: $value");
			}
			echo $output;
		} catch (RenderingEngineException $ex) {
			/*
				If something goes wrong in rendering the Exception,
				last-resort fallback to plaintext.

				If text/plain rendering fails, bail out.
			*/
			if ($this->_acceptContentType === 'text/plain') {
				echo "Several crtical errors occured during request processing, including during error reporting mechanisms. Check server logs for more details.";
			} else {
				$this->_acceptContentType === 'text/plain';
				$this->respondToRequestWithException($Exception);
			}
		}
		ob_flush();
	}

	/**
	 * Handle outputing Application Response during normal Request lifecycle.
	 * This method assumes the Response object has a RenderEngine associated with it.
	 * For outputing internal framework errors, see Application::respondToRequestWithException().
	 * 
	 * @param Response $Response Programmed Application Response object to output.
	 */
	protected function respondToRequest(): void{
		if ($this->_response->getRenderEngine() === null) {
			$this->respondToRequestWithException(new RenderingEngineException('Request completed succesfully, but no RenderEngine was defined for this Response.', 418));
		
		} else {
			foreach ($this->_response->getResponseHeaders() as $header => $value) {
				header("${header}: $value");
			}
			$output = $this->_response->getRenderEngine()->render($this->_response);
			foreach ($this->_response->getResponseHeaders() as $header => $value) {
				header("${header}: $value");
			}
			echo $output;
		}

		$this->runApplicationLifecycleHooks(WebLifecycleHook::BEFORE_RESPONSE_FLUSH);
		ob_flush();
		$this->runApplicationLifecycleHooks(WebLifecycleHook::AFTER_RESPONSE_FLUSH);
	} 

	protected function runApplicationShutdown(): void {
		$this->runApplicationLifecycleHooks(WebLifecycleHook::BEFORE_TEARDOWN);

		foreach($this->_plugins as $Plugin) {
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
	 * Register one or more lifecycle hooks with the Application
	 * 
	 * @param string $hook The lifecycle hook to register these callables under. See WebLifecycleHook.
	 * @param callable $callable One or more methods to call when the this hook is triggered. No args are passed, return values are ignored.
	 */
	public function registerWebLifecycleHook(string $hook, callable ...$callabe): void {
		if (!array_key_exists($hook, $this->_lifecycleHooks)) {
			$this->_lifecycleHooks[$hook] = [...$callabe];
		} else {
			array_push($this->_lifecycleHooks[$hook], ...$callabe);
		}
	}

	/**
	 * Call all registered lifecycle hooks for the given lifecycle event.
	 * 
	 * @param string $hook Lifecycle point that was been reached.
	 */
	protected function runApplicationLifecycleHooks(string $hook): void {
		if (array_key_exists($hook, $this->_lifecycleHooks)) {
			foreach($this->_lifecycleHooks[$hook] as $hook) {
				$hook();
			}
		}
	}
}

?>