<?php
namespace net\peacefulcraft\apirouter\spec\route;

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
	 * @param IRequest The Request object from this request
	 * @return IResponse Return a Response object which indicates to framework that this middleware
	 *                  is overriding that requested route and the request will complete using the
	 *                  returned Response object.
	 *                  (Terminate immediatly, skip remaining middleware and controllers)
	 * @return null Indicates that middleware does not wish to modify Response behavior. Pass through /
	 *              go to the next registered middleware function, or the controller.
	 */
	public function run(array $config, IRequest $request): ?IResponse;
}