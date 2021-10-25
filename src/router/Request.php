<?php
namespace net\peacefulcraft\apirouter\router;

use net\peacefulcraft\apirouter\spec\route\Controller;
use net\peacefulcraft\apirouter\spec\route\IRequest;

class Request implements IRequest {

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
	 * Response will just be $_SERVER
	 * @return array Associative array with the request headers
	 * @return false An error occured while parsing the request headers
	 */
	public function getHeaders():array|bool { return $_SERVER; }
	
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
	private string|Controller $_controller;
		public function getController():string|Controller { return $this->_controller; }

	public function __construct(string $path, array $middleware, string|Controller $controller) {
		$this->_path = $path;
		$this->_middleware = $middleware;
		$this->_controller = $controller;
	}
}