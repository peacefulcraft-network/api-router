<?php namespace net\peacefulcraft\apirouter\spec\route;

interface IRequest {
	
	/**
	 * @return string Raw request path requested by the HTTP client.
	 */
	public function getPath(): string;

	/**
	 * @return array Assoc-array of named and positional URI parameters parsed from the Request.
	 */
	public function getUriParameters(): array;

	/**
	 * Primarly intdend for usage by middleware to transform the HTTP params.
	 * 
	 * @param array $params Updated, parsed HTTP params.
	 */
	public function setUriParameters(array $params): void;

	/**
	 * @return array Assoc-array of data in request, as parsed by the matched BodyParser
	 */
	public function getBody(): array;

	/**
	 * Primarly tntdend for usage by middleware to transform the parsed HTTP body.
	 * 
	 * @param array $body Updated, pasred HTTP body.
	 */
	public function setBody(array $body): void;

	/**
	 * Get Middleware functions that need run on for this Request.
	 * 
	 * @return array List of Middleware.
	 */
	public function getMiddleware(): array;

	/**
	 * @return string|Controller Matched Controller that will be handling the request,
	 *                    the symbol for the handler (IE ::class)
	 */
	public function getController(): string|Controller;
}

?>