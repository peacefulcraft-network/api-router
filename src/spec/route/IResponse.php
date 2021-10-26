<?php namespace net\peacefulcraft\apirouter\spec\route;

use net\peacefulcraft\apirouter\spec\render\RenderEngine;

interface IResponse {

	/**
	 * @return int HTTP Response code that will/was sent back to the client.
	 */
	public function getHttpResponseCode(): int;

	/**
	 * @param int $response_code HTTP response code to send back to client.
	 */
	public function setHttpResponseCode(int $response_code): void;

	/**
	 * @return array Assoc-list of HTTP response headers that will be set by the Application when
	 *               writing the HTTP response out to the client.
	 */
	public function getResponseHeaders(): array;

	/**
	 * @param string $header HTTP response header value to retrieve.
	 * @return null|string Value of the requested header or null if it is not set.
	 */
	public function getResponseHeader(string $header): ?string;

	/**
	 * This method can opt to set headers at time of invocation, or buffer them for output later.
	 * Note that the standard Api-Router Application object will output all getResponseHeaders()
	 * prior to calling RenderEngine::render(). It avoid this, you must override the Application::respondToRequest()
	 * method and use that custom Application implementation.
	 * 
	 * @param string $header HTTP response header to set
	 * @param null|string $value Value to set the header to. Null to unset. 
	 */
	public function setHeader(string $header, ?string $value): void;

	/**
	 * @return RenderEngine Rendering engine that will output response to stdout.
	 */
	public function getRenderEngine(): ?RenderEngine;

	/**
	 * @param RenderEngine $Engine Provide a render engine to handle rendering this request.
	 */
	public function setRenderEngine(RenderEngine $Engine): void;

	public const HTTP_OK = 200;
	public const HTTP_CREATED = 201;
	public const HTTP_ACCEPTED = 202;
	public const HTTP_EMPTY_RESPONSE = 204;
	public const HTTP_REDIRECT_PERMANENTLY = 301;
	public const HTTP_REDIRECT_TEMPORARLY = 302;
	public const HTTP_REDIRECT_JUST_THIS_ONCE = 303;
	public const HTTP_NOT_MODIFIED = 304;
	public const HTTP_BAD_REQUEST = 400;
	public const HTTP_UNAUTHORIZED = 401;
	public const HTTP_NOT_PERMITTED = 403;
	public const HTTP_NOT_FOUND = 404;
	public const HTTP_TIMEOUT = 408;
	public const HTTP_TEA_POT = 418;
	public const HTTP_RATE_LIMIT = 429;
	public const HTTP_INTERNAL_ERROR = 500;
	public const HTTP_BAD_GATEWAY = 502;
	public const HTTP_SERVICE_UNAVAILABLE = 503;
	public const HTTP_GATEWAY_TIMEOUT = 504;
}

?>