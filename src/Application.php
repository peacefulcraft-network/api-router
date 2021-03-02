<?php
namespace ncsa\phpmvj;

use ncsa\phpmvj\router\Request;
use ncsa\phpmvj\router\RequestMethod;
use ncsa\phpmvj\router\Response;
use \ncsa\phpmvj\router\Router;

class Application {

	private $_config = null;
		public function getConfig():?array { return $this->_config; }

	private $_router = null;
		public function getRouter():Router { return $this->_router; }

	private $_request = null;
		public function getRequest():?Request { return $this->_request; }

	private $_response = null;
		public function getResponse():Response { return $this->_response; }

	public function __construct(array $config) {
		$this->_config = $config;
		$this->_router = new Router();
		$this->_response = new Response();
		$this->_response->setHeader('Content-Type', 'application/json');
	}

	public function handle() {
		$this->_request = $this->_router->resolve($_SERVER['REQUEST_URI']);

		if ($this->_request->hasMatchedHandler()) {
			
			// CORS pre-flight requests
			if ($this->_request->getEMethod() === RequestMethod::OPTIONS) {
				// Replace handler string with handler object
				$this->_request->setMatchedHandler(new ($this->_request->getMatchedHandler()));
				
				$this->_request->getMatchedHandler()->options($this->_config, $this->_request, $this->_response);
				$this->_response->setHeader('Access-Control-Allow-Credentials', true);
				$this->_response->setHeader('Access-Control-Max-Age', $this->_config['cors']['max-age']);

				// Pre-flight requests end here
				ob_flush();
				return;
			}
			
			// Parse request body
			$this->_request->setBody($this->_parseRequestBody());

			// Middleware / body transformations
			foreach($this->_request->getMiddleware() as $middlewareFunction) {
				$func = new ($middlewareFunction);

				// Middleware must explicity allow request to continue
				if ($func->run($this->_request, $this->_response)) {
					continue;
				
				// Otherwise, stop processing and write out
				} else {
					echo $this->_response;
					ob_flush();
					exit();
				}
			}

			// Replace handler string with handler object
			$this->_request->setMatchedHandler(new ($this->_request->getMatchedHandler()));

			// Send the CORS headers
			$this->_request->getMatchedHandler()->options($this->_config, $this->_request, $this->_response);

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
	}

	/**
	 * Parses the request body and assigns values to $_POST
	 * Supports JSON. multipart/form & x-www-form-urlencoded parsed by PhP nativley
	 */
	private function _parseRequestBody():array {
		$body = [];

		// If Content-Type is not specified, fallback to default behavior
		if (!isset($_SERVER['CONTENT_TYPE'])) {
			return $_POST;
		}

		// Handle json requests
		if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
			$requestBody = json_decode(file_get_contents('php://input'), true);
			if (json_last_error() === JSON_ERROR_NONE) {
				foreach($requestBody as $key => $value) {
					$body[$key] = $value;
				} 
			}
		} else {
			// Fall-back to PHP for non-overriden content-types
			$body = $_POST;
		}
		return $body;
	}
}
