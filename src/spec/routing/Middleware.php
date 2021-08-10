<?php
namespace net\peacefulcraft\apirouter\spec\router;

interface Middleware {

	/**
	 * Function to be invokved by the router during middleware processing.
     *
	 * If Middleware is being invoked, it is implied that the Request has a matched handler.
	 * However, during Middleware processing, $resquest->getMatchedHandler() will return the fullyqualified
	 * class name for the matched handler. The RequestHandler is not instantiated until after Middleware
	 * processing completes. This is an intentional design choice which enables Middleware-based, internal
	 * redirects, as well avoid placing arbitrarty data access restrictions on the RequestHandler constructor.
	 *
	 * @param array Application configuration array
	 * @param Request The Request object from this request
	 * @param Response The Response object for this request to populate.
	 * @return True Request is allowed to continue
	 * @return False Request will terminate and return an HTTP/418 response code, unless
	 *               the response object has been otherwise populated.
	 */
	public function run(array $config, Request $request, Response $response): bool;
}