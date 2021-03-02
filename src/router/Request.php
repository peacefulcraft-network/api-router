<?php
namespace ncsa\phpmvj\router;

class Request {

	/**
	 * Coresponds to an \ncsa\phpmvj\router\RequestMethod constant
	 */
	private int $_request_method = -1;
		public function getEMethod():int { return $this->_request_method; }
		public function setEMethod(int $request_method) {$this->_request_method = $request_method; }

	/**
	 * The request uri. Include query parameters, inline and ?.
	 */
	private ?string $_uri = null;
		public function getUri():string { return $this->_uri; }

	/**
	 * The query parameters parsed out of the request to an associative array.
	 */
	private ?array $_uri_parameters = null;
		public function getUriParameters():array { return $this->_uri_parameters; }
		public function setUriParameters(array $uri_parameters):void { $this->_uri_parameters = $uri_parameters; }

	/**
	 * The request body, pasred into to an associative array.
	 */
	private ?array $_body = null;
		public function getBody():array { return $this->_body; }
		public function setBody(array $body):void { $this->_body = $body; } 

	/**
	 * List of Middleware functions to be invoked during request pre-processing.
	 */
	private array $_middleware = [];
		public function getMiddleware(): array { return $this->_middleware; }
		public function setMiddleware(array $middleware) { $this->_middleware = $middleware; }

	/**
	 * Indicates whether the Request has been resolved to a RequestHandler by a router.
	 */
	private bool $_has_matched_handler = false;
		public function hasMatchedHandler():bool { return $this->_has_matched_handler; }
		public function setHasMatchedHandler(bool $has_matched_handler):void { $this->_has_matched_handler = $has_matched_handler; }

	/**
	 * The handler which a router found is responsible for processing requests to this uri
	 * Handler is kept as a fully qualified, namespaced class name until after middleware is
	 * run. This allows for use of RequestHandler constructors in an intuitive manor.
	 */
	private string|RequestHandler|null $_matched_handler = null;
		public function getMatchedHandler():string|RequestHandler|null { return $this->_matched_handler; }
		public function setMatchedHandler(string|RequestHandler $request_handler) { $this->_matched_handler = $request_handler; }

	public function __construct(string $uri) {
		$this->_uri = $uri;
	}
}