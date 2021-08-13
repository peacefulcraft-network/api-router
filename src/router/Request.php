<?php
namespace net\peacefulcraft\apirouter\router;

use net\peacefulcraft\apirouter\spec\router\Controller;
use net\peacefulcraft\apirouter\spec\router\Request as RouterRequest;

class Request implements RouterRequest{

	/**
	 * Coresponds to an \net\peacefulcraft\apirouter\router\RequestMethod constant
	 */
	private RequestMethod $_request_method;
		public function getEMethod(): RequestMethod { return $this->_request_method; }
		public function setEMethod(RequestMethod $request_method) {$this->_request_method = $request_method; }

	/**
	 * The request path. Include query parameters, inline and ?.
	 */
	private string $_path;
		public function getPath():string { return $this->_path; }

	/**
	 * This method exists here mostly for completeness of the Request class.
	 * This method only wrappers php's native getallheaders() method.
	 * @return array Associative array with the request headers
	 * @return false An error occured while parsing the request headers
	 */
	public function getHeaders():array|bool { return getallheaders(); }
	
	/**
	 * The query parameters parsed out of the request to an associative array.
	 */
	private array $_uri_parameters = [];
		public function getUriParameters(): array { return $this->_uri_parameters; }
		public function setUriParameters(array $uri_parameters):void { $this->_uri_parameters = $uri_parameters; }

	/**
	 * The request body, pasred into to an associative array.
	 */
	private array $_body = [];
		public function getBody(): array { return $this->_body; }
		public function setBody(array $body): void { $this->_body = $body; }

	/**
	 * List of Middleware functions to be invoked during request pre-processing.
	 */
	private array $_middleware = [];
		public function getMiddleware(): array { return $this->_middleware; }

	/**
	 * The handler which a router found is responsible for processing requests to this uri
	 * Handler is kept as a fully qualified, namespaced class name until after middleware is
	 * run. This allows for use of Controller constructors in an intuitive manor.
	 */
	private string|Controller|null $_controller = null;
		public function getController():string|Controller|null { return $this->_controller; }

	public function __construct(string $path, array $middleware, string|Controller $controller) {
		$this->_path = $path;
		$this->_middleware = $middleware;
		$this->_controller = $controller;
	}

	/**
	 * Parses the request body and assigns values to $_POST
	 */
	public function parseBody(string $body): void {
		// Handle json requests
		$this->_body = $_POST;

		if (!array_key_exists('CONTENT_TYPE', $_SERVER)) { return; }

		switch(strtolower($_SERVER['CONTENT_TYPE'])) {
			case 'application/json':
				$requestBody = json_decode($body, true);
				if (json_last_error() === JSON_ERROR_NONE) {
					$this->_body = array_merge($this->_body, $requestBody);
				}
			break;
		}
	}
}